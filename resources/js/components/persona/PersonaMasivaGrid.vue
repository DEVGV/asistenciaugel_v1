<script setup lang="ts">
/**
 * PersonaMasivaGrid — Spreadsheet-like editor to register personas in bulk.
 *
 * - Loads catalogs (doc types, genders, countries) in parallel on mount.
 * - Supports inline RENIEC/SUNAT lookup per row (DNI only).
 * - Optional "es_trabajador" checkbox per row to also create worker + user account.
 * - Shows per-cell validation feedback before submitting.
 * - Submits all rows in a batch transaction to /personas-masivas.
 */
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import {
    Check,
    ChevronDown,
    Plus,
    Trash2,
    Search,
    Loader2,
    CheckCircle2,
    AlertCircle,
    X,
    UserCheck,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success', count: number): void;
}>();

// ─── Tipos ────────────────────────────────────────────────────────────────────
interface CatItem {
    id: number;
    nombre: string;
    abreviatura?: string;
}

interface FilaPersona {
    _id: number;
    tipoDocIdentidad_id: number | null;
    docIdentidad: string;
    paterno: string;
    materno: string;
    nombre: string;
    sexo_id: number | null;
    pais_id: number | null;
    fechaNac: string;
    es_trabajador: boolean;

    _errors: Record<string, string>;
    _reniecLoading: boolean;
}

// ─── Catálogos ────────────────────────────────────────────────────────────────
const cats = reactive<Record<string, CatItem[]>>({
    tiposDoc: [],
    sexos: [],
    paises: [],
});
const catsLoading = ref(true);

async function fetchCat(type: string): Promise<CatItem[]> {
    const res = await fetch(`/api/params/${type}`);
    const json = await res.json();
    return (json.data ?? []).map((i: any) => ({
        id: i.id,
        nombre: i.nombre || i.descripcion || String(i.id),
        abreviatura: i.abreviatura || undefined,
    }));
}

onMounted(async () => {
    try {
        const [tiposDoc, sexos, paises] = await Promise.all([
            fetchCat('tipos-doc-identidad'),
            fetchCat('sexos'),
            fetchCat('paises'),
        ]);
        cats.tiposDoc = tiposDoc;
        cats.sexos = sexos;
        cats.paises = paises;
    } catch (e) {
        console.error('Error cargando catálogos masivos:', e);
    } finally {
        catsLoading.value = false;
    }
});

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;
function nuevaFila(): FilaPersona {
    const DniDoc = cats.tiposDoc.find((t) => t.abreviatura === 'DNI');
    const peruPais = cats.paises.find((p) => p.nombre.toUpperCase() === 'PERÚ');

    return {
        _id: nextId++,
        tipoDocIdentidad_id: DniDoc?.id || null,
        docIdentidad: '',
        paterno: '',
        materno: '',
        nombre: '',
        sexo_id: null,
        pais_id: peruPais?.id || null,
        fechaNac: '',
        es_trabajador: false,

        _errors: {},
        _reniecLoading: false,
    };
}

const filas = ref<FilaPersona[]>([]);

onMounted(() => {
    const interval = setInterval(() => {
        if (!catsLoading.value) {
            clearInterval(interval);
            filas.value = [nuevaFila(), nuevaFila(), nuevaFila()];
        }
    }, 50);
});

function agregarFila() {
    filas.value.push(nuevaFila());
    nextTick(() => {
        const container = document.getElementById('persona-grid-scroll');
        if (container) container.scrollTop = container.scrollHeight;
    });
}

function agregarFilas(n: number) {
    for (let i = 0; i < n; i++) filas.value.push(nuevaFila());
}

function eliminarFila(id: number) {
    filas.value = filas.value.filter((f) => f._id !== id);
    if (filas.value.length === 0) filas.value.push(nuevaFila());
}

// ─── Dropdown inline ──────────────────────────────────────────────────────────
const activeDropdown = ref<string | null>(null);
const dropdownSearch = ref('');

function dropKey(filaId: number, campo: string) {
    return `${filaId}_${campo}`;
}

function toggleDropdown(filaId: number, campo: string) {
    const key = dropKey(filaId, campo);
    if (activeDropdown.value === key) {
        activeDropdown.value = null;
    } else {
        activeDropdown.value = key;
        dropdownSearch.value = '';
        nextTick(() => {
            const el = document.getElementById(`ds_${key}`);
            el?.focus();
        });
    }
}

function closeDropdown() {
    activeDropdown.value = null;
    dropdownSearch.value = '';
}

function selectCat(fila: FilaPersona, campo: keyof FilaPersona, item: CatItem) {
    (fila as any)[campo] = item.id;
    fila._errors[campo as string] = '';
    closeDropdown();
}

function catNombre(lista: CatItem[], id: number | null): string {
    if (!id) return '';
    return lista.find((i) => i.id === id)?.nombre ?? String(id);
}

function filteredCat(lista: CatItem[]): CatItem[] {
    const q = dropdownSearch.value.toLowerCase().trim();
    if (!q) return lista.slice(0, 80);
    return lista.filter((i) => i.nombre.toLowerCase().includes(q)).slice(0, 80);
}

// ─── RENIEC/SUNAT Lookup per row ──────────────────────────────────────────────
async function buscarReniecRow(fila: FilaPersona) {
    if (!fila.docIdentidad || fila.docIdentidad.length !== 8) return;
    const isDni =
        cats.tiposDoc.find((t) => t.id === fila.tipoDocIdentidad_id)
            ?.abreviatura === 'DNI';
    if (!isDni) return;

    fila._reniecLoading = true;
    fila._errors['docIdentidad'] = '';

    try {
        const response = await fetch('/api/sunat/dni', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({ dni: fila.docIdentidad }),
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'No encontrado');
        if (result.data) {
            fila.nombre = result.data.nombres || '';
            fila.paterno = result.data.apellido_paterno || '';
            fila.materno = result.data.apellido_materno || '';
            fila._errors['nombre'] = '';
            fila._errors['paterno'] = '';
            fila._errors['materno'] = '';
        }
    } catch (_e: any) {
        fila._errors['docIdentidad'] = 'DNI no encontrado';
    } finally {
        fila._reniecLoading = false;
    }
}

function onDniInput(fila: FilaPersona, val: string) {
    fila.docIdentidad = val;
    fila._errors['docIdentidad'] = '';
    const isDni =
        cats.tiposDoc.find((t) => t.id === fila.tipoDocIdentidad_id)
            ?.abreviatura === 'DNI';
    if (val.length === 8 && isDni) {
        buscarReniecRow(fila);
    }
}

// ─── Validación ───────────────────────────────────────────────────────────────
function validar(): boolean {
    let ok = true;
    for (const fila of filas.value) {
        if (!fila.docIdentidad && !fila.paterno && !fila.nombre) continue;
        fila._errors = {};

        const requeridos: (keyof FilaPersona)[] = [
            'tipoDocIdentidad_id',
            'docIdentidad',
            'paterno',
            'materno',
            'nombre',
            'sexo_id',
            'pais_id',
        ];
        for (const campo of requeridos) {
            if (!fila[campo]) {
                fila._errors[campo as string] = 'Requerido';
                ok = false;
            }
        }
    }
    return ok;
}

const filasConDatos = computed(() =>
    filas.value.filter((f) => f.docIdentidad || f.paterno || f.nombre),
);

// ─── Envío ────────────────────────────────────────────────────────────────────
const enviando = ref(false);
const resultado = ref<{
    insertados: number;
    errores_validacion: Record<number, string>;
    errores_db: string[];
} | null>(null);
const errorGeneral = ref('');

async function enviar() {
    if (!validar()) return;
    const filasFiltradas = filasConDatos.value;
    if (!filasFiltradas.length) {
        errorGeneral.value = 'No hay filas con datos para enviar.';
        return;
    }
    errorGeneral.value = '';
    enviando.value = true;
    resultado.value = null;

    const payload = filasFiltradas.map((f) => ({
        tipoDocIdentidad_id: f.tipoDocIdentidad_id,
        docIdentidad: f.docIdentidad,
        paterno: f.paterno,
        materno: f.materno,
        nombre: f.nombre,
        sexo_id: f.sexo_id,
        pais_id: f.pais_id,
        fechaNac: f.fechaNac || null,
        es_trabajador: f.es_trabajador,
    }));

    try {
        const res = await fetch('/personas-masivas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    (
                        document.querySelector(
                            'meta[name="csrf-token"]',
                        ) as HTMLMetaElement
                    )?.content ?? '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ filas: payload }),
        });
        const data = await res.json();
        if (!res.ok) {
            errorGeneral.value = data.message ?? 'Error del servidor.';
        } else {
            resultado.value = data;
            if (data.insertados > 0) {
                emit('success', data.insertados);
            }
        }
    } catch {
        errorGeneral.value = 'Error de conexión al servidor.';
    } finally {
        enviando.value = false;
    }
}

function resetGrid() {
    filas.value = [nuevaFila(), nuevaFila(), nuevaFila()];
    resultado.value = null;
    errorGeneral.value = '';
}

function onGridClick(e: MouseEvent) {
    const target = e.target as HTMLElement;
    if (!target.closest('[data-dropdown]')) closeDropdown();
}
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-0"
            @mousedown.self="emit('close')"
        >
            <div
                class="flex h-screen w-screen flex-col bg-background shadow-2xl"
                style="max-height: 100dvh"
                @click="onGridClick"
            >
                <!-- ── Header ── -->
                <div
                    class="flex shrink-0 items-center justify-between border-b px-6 py-3"
                >
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-semibold">
                            Carga Masiva de Personas
                        </h2>
                        <span
                            class="hidden text-xs text-muted-foreground sm:inline"
                        >
                            Ingrese los datos. Marque "Trabajador" para crear
                            cuenta de acceso automáticamente.
                        </span>
                        <span
                            v-if="filasConDatos.length"
                            class="rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                        >
                            {{ filasConDatos.length }} fila{{
                                filasConDatos.length !== 1 ? 's' : ''
                            }}
                            con datos
                        </span>
                    </div>
                    <button
                        @click="emit('close')"
                        class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- ── Loading catálogos ── -->
                <div
                    v-if="catsLoading"
                    class="flex flex-1 items-center justify-center gap-2 text-sm text-muted-foreground"
                >
                    <Loader2 class="h-5 w-5 animate-spin" /> Cargando
                    catálogos...
                </div>

                <!-- ── Grid ── -->
                <div v-else class="flex flex-1 flex-col overflow-hidden">
                    <!-- Resultado -->
                    <div
                        v-if="resultado"
                        class="shrink-0 space-y-2 border-b border-dashed bg-muted/25 px-5 py-3"
                    >
                        <div
                            class="flex items-center gap-2 text-sm font-semibold text-emerald-700 dark:text-emerald-400"
                        >
                            <CheckCircle2 class="h-4 w-4 shrink-0" />
                            {{ resultado.insertados }} persona{{
                                resultado.insertados !== 1 ? 's' : ''
                            }}
                            creada{{ resultado.insertados !== 1 ? 's' : '' }}
                            correctamente.
                        </div>
                        <div
                            v-if="
                                Object.keys(resultado.errores_validacion).length
                            "
                            class="text-xs text-amber-700 dark:text-amber-400"
                        >
                            <span class="font-bold">Filas rechazadas:</span>
                            <span
                                v-for="(
                                    msg, row
                                ) in resultado.errores_validacion"
                                :key="row"
                                class="ml-2 rounded border border-amber-200/50 bg-amber-50 px-1.5 py-0.5 dark:bg-amber-950/20"
                            >
                                fila {{ Number(row) + 1 }}: {{ msg }}
                            </span>
                        </div>
                        <div
                            v-if="resultado.errores_db.length"
                            class="text-xs text-destructive"
                        >
                            <span class="font-bold"
                                >Errores base de datos:</span
                            >
                            <div
                                v-for="(msg, i) in resultado.errores_db"
                                :key="i"
                                class="mt-0.5 ml-2"
                            >
                                {{ msg }}
                            </div>
                        </div>
                    </div>

                    <!-- Error general -->
                    <div
                        v-if="errorGeneral"
                        class="flex shrink-0 items-center gap-2 border-b bg-destructive/5 px-5 py-2 text-xs text-destructive"
                    >
                        <AlertCircle class="h-4 w-4 shrink-0" />
                        {{ errorGeneral }}
                    </div>

                    <!-- Tabla scrollable -->
                    <div id="persona-grid-scroll" class="flex-1 overflow-auto">
                        <table
                            class="w-max min-w-full border-separate border-spacing-0 text-xs"
                        >
                            <thead
                                class="sticky top-0 z-20 bg-muted/90 backdrop-blur-sm"
                            >
                                <tr>
                                    <th
                                        class="w-8 border-r border-b bg-muted/50 px-2 py-2 text-center text-muted-foreground"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 130px"
                                    >
                                        Tipo Doc.
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 140px"
                                    >
                                        N° Documento
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 150px"
                                    >
                                        Ap. Paterno
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 150px"
                                    >
                                        Ap. Materno
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 170px"
                                    >
                                        Nombres
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 110px"
                                    >
                                        Sexo
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 130px"
                                    >
                                        País
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 130px"
                                    >
                                        Fecha Nac.
                                    </th>
                                    <th
                                        class="border-r border-b bg-primary/5 px-2 py-2 text-center font-semibold text-primary"
                                        style="min-width: 110px"
                                        title="Crear también cuenta de trabajador y usuario"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-1"
                                        >
                                            <UserCheck class="h-3.5 w-3.5" />
                                            Trabajador
                                        </div>
                                    </th>
                                    <th
                                        class="w-8 border-b bg-muted/50 px-2 py-2"
                                    ></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(fila, idx) in filas"
                                    :key="fila._id"
                                    class="group transition-colors hover:bg-muted/30"
                                    :class="{
                                        'bg-destructive/5': Object.keys(
                                            fila._errors,
                                        ).length,
                                        'bg-primary/5': fila.es_trabajador,
                                    }"
                                >
                                    <!-- N° fila -->
                                    <td
                                        class="border-r border-b bg-muted/10 px-2 py-1 text-center text-muted-foreground select-none"
                                    >
                                        {{ idx + 1 }}
                                    </td>

                                    <!-- Tipo Doc -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'tipoDocIdentidad_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors.tipoDocIdentidad_id
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.tipoDocIdentidad_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.tiposDoc,
                                                        fila.tipoDocIdentidad_id,
                                                    ) || 'Seleccionar...'
                                                }}
                                            </span>
                                            <ChevronDown
                                                class="h-3 w-3 shrink-0 text-muted-foreground/60"
                                            />
                                        </button>
                                        <div
                                            v-if="
                                                activeDropdown ===
                                                dropKey(
                                                    fila._id,
                                                    'tipoDocIdentidad_id',
                                                )
                                            "
                                            class="absolute top-full left-0 z-40 mt-0.5 w-56 rounded-md border bg-background shadow-lg"
                                            data-dropdown
                                        >
                                            <div
                                                class="flex items-center gap-1.5 border-b px-2.5 py-1.5"
                                            >
                                                <Search
                                                    class="h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                                />
                                                <input
                                                    :id="`ds_${dropKey(fila._id, 'tipoDocIdentidad_id')}`"
                                                    v-model="dropdownSearch"
                                                    type="text"
                                                    placeholder="Buscar..."
                                                    class="flex-1 bg-transparent text-xs outline-none placeholder:text-muted-foreground/60"
                                                    @click.stop
                                                />
                                            </div>
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <div
                                                    v-for="item in filteredCat(
                                                        cats.tiposDoc,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'tipoDocIdentidad_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.tipoDocIdentidad_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.tipoDocIdentidad_id ===
                                                            item.id
                                                        "
                                                        class="h-3 w-3 shrink-0"
                                                    />
                                                    <span
                                                        v-else
                                                        class="w-3 shrink-0"
                                                    />
                                                    <span class="truncate">{{
                                                        item.nombre
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- N° Documento -->
                                    <td class="relative border-r border-b p-0">
                                        <div class="relative flex items-center">
                                            <input
                                                type="text"
                                                :value="fila.docIdentidad"
                                                @input="
                                                    onDniInput(
                                                        fila,
                                                        (
                                                            $event.target as HTMLInputElement
                                                        ).value,
                                                    )
                                                "
                                                placeholder="Documento"
                                                maxlength="20"
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/60"
                                                :class="
                                                    fila._errors.docIdentidad
                                                        ? 'bg-destructive/5 text-destructive ring-1 ring-destructive ring-inset'
                                                        : ''
                                                "
                                            />
                                            <button
                                                v-if="
                                                    cats.tiposDoc.find(
                                                        (t) =>
                                                            t.id ===
                                                            fila.tipoDocIdentidad_id,
                                                    )?.abreviatura === 'DNI'
                                                "
                                                type="button"
                                                @click="buscarReniecRow(fila)"
                                                :disabled="
                                                    fila._reniecLoading ||
                                                    fila.docIdentidad.length !==
                                                        8
                                                "
                                                class="absolute right-1 rounded p-1 text-muted-foreground hover:bg-muted disabled:opacity-30"
                                                title="Buscar DNI"
                                            >
                                                <Loader2
                                                    v-if="fila._reniecLoading"
                                                    class="h-3 w-3 animate-spin"
                                                />
                                                <Search
                                                    v-else
                                                    class="h-3 w-3"
                                                />
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Ap. Paterno -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.paterno"
                                            placeholder="Apellido paterno"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs font-medium outline-none"
                                            :class="
                                                fila._errors.paterno
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : ''
                                            "
                                        />
                                    </td>

                                    <!-- Ap. Materno -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.materno"
                                            placeholder="Apellido materno"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs font-medium outline-none"
                                            :class="
                                                fila._errors.materno
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : ''
                                            "
                                        />
                                    </td>

                                    <!-- Nombres -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.nombre"
                                            placeholder="Nombres"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs font-semibold text-foreground outline-none"
                                            :class="
                                                fila._errors.nombre
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : ''
                                            "
                                        />
                                    </td>

                                    <!-- Sexo -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'sexo_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors.sexo_id
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.sexo_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.sexos,
                                                        fila.sexo_id,
                                                    ) || 'Seleccionar...'
                                                }}
                                            </span>
                                            <ChevronDown
                                                class="h-3 w-3 shrink-0 text-muted-foreground/60"
                                            />
                                        </button>
                                        <div
                                            v-if="
                                                activeDropdown ===
                                                dropKey(fila._id, 'sexo_id')
                                            "
                                            class="absolute top-full left-0 z-40 mt-0.5 w-48 rounded-md border bg-background shadow-lg"
                                            data-dropdown
                                        >
                                            <div
                                                class="flex items-center gap-1.5 border-b px-2.5 py-1.5"
                                            >
                                                <Search
                                                    class="h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                                />
                                                <input
                                                    :id="`ds_${dropKey(fila._id, 'sexo_id')}`"
                                                    v-model="dropdownSearch"
                                                    type="text"
                                                    placeholder="Buscar..."
                                                    class="flex-1 bg-transparent text-xs outline-none placeholder:text-muted-foreground/60"
                                                    @click.stop
                                                />
                                            </div>
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <div
                                                    v-for="item in filteredCat(
                                                        cats.sexos,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'sexo_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.sexo_id === item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.sexo_id ===
                                                            item.id
                                                        "
                                                        class="h-3 w-3 shrink-0"
                                                    />
                                                    <span
                                                        v-else
                                                        class="w-3 shrink-0"
                                                    />
                                                    <span class="truncate">{{
                                                        item.nombre
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- País -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'pais_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors.pais_id
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.pais_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.paises,
                                                        fila.pais_id,
                                                    ) || 'Seleccionar...'
                                                }}
                                            </span>
                                            <ChevronDown
                                                class="h-3 w-3 shrink-0 text-muted-foreground/60"
                                            />
                                        </button>
                                        <div
                                            v-if="
                                                activeDropdown ===
                                                dropKey(fila._id, 'pais_id')
                                            "
                                            class="absolute top-full left-0 z-40 mt-0.5 w-52 rounded-md border bg-background shadow-lg"
                                            data-dropdown
                                        >
                                            <div
                                                class="flex items-center gap-1.5 border-b px-2.5 py-1.5"
                                            >
                                                <Search
                                                    class="h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                                />
                                                <input
                                                    :id="`ds_${dropKey(fila._id, 'pais_id')}`"
                                                    v-model="dropdownSearch"
                                                    type="text"
                                                    placeholder="Buscar..."
                                                    class="flex-1 bg-transparent text-xs outline-none placeholder:text-muted-foreground/60"
                                                    @click.stop
                                                />
                                            </div>
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <div
                                                    v-for="item in filteredCat(
                                                        cats.paises,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'pais_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.pais_id === item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.pais_id ===
                                                            item.id
                                                        "
                                                        class="h-3 w-3 shrink-0"
                                                    />
                                                    <span
                                                        v-else
                                                        class="w-3 shrink-0"
                                                    />
                                                    <span class="truncate">{{
                                                        item.nombre
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Fecha Nac. -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaNac"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                        />
                                    </td>

                                    <!-- Trabajador toggle -->
                                    <td
                                        class="border-r border-b p-0 text-center"
                                    >
                                        <div
                                            class="flex items-center justify-center px-2 py-1.5"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="fila.es_trabajador"
                                                class="h-4 w-4 cursor-pointer rounded border-input accent-primary"
                                                :title="
                                                    fila.es_trabajador
                                                        ? 'Se creará cuenta de trabajador y usuario'
                                                        : 'Solo se registrará como persona'
                                                "
                                            />
                                        </div>
                                    </td>

                                    <!-- Eliminar -->
                                    <td class="border-b p-0">
                                        <button
                                            type="button"
                                            @click="eliminarFila(fila._id)"
                                            class="p-1.5 text-muted-foreground/40 opacity-0 transition-opacity group-hover:opacity-100 hover:text-destructive"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ── Footer ── -->
                    <div
                        class="flex shrink-0 items-center justify-between border-t bg-background px-5 py-3"
                    >
                        <div class="flex items-center gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="agregarFila"
                                class="h-7 gap-1 text-xs"
                            >
                                <Plus class="h-3.5 w-3.5" />
                                Agregar fila
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="agregarFilas(10)"
                                class="h-7 text-xs text-muted-foreground"
                            >
                                +10 filas
                            </Button>
                            <Button
                                v-if="resultado"
                                variant="ghost"
                                size="sm"
                                @click="resetGrid"
                                class="h-7 text-xs text-muted-foreground"
                            >
                                Limpiar grilla
                            </Button>
                        </div>

                        <div class="flex items-center gap-2">
                            <span
                                v-if="
                                    filasConDatos.filter((f) => f.es_trabajador)
                                        .length
                                "
                                class="flex items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                            >
                                <UserCheck class="h-3 w-3" />
                                {{
                                    filasConDatos.filter((f) => f.es_trabajador)
                                        .length
                                }}
                                como trabajador
                            </span>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="emit('close')"
                                class="h-8"
                            >
                                Cancelar
                            </Button>
                            <Button
                                size="sm"
                                @click="enviar"
                                :disabled="
                                    enviando || filasConDatos.length === 0
                                "
                                class="h-8 gap-1.5"
                            >
                                <Loader2
                                    v-if="enviando"
                                    class="h-4 w-4 animate-spin"
                                />
                                <span v-if="enviando">Enviando...</span>
                                <span v-else>
                                    Registrar
                                    {{
                                        filasConDatos.length
                                            ? filasConDatos.length
                                            : ''
                                    }}
                                    persona{{
                                        filasConDatos.length !== 1 ? 's' : ''
                                    }}
                                </span>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
