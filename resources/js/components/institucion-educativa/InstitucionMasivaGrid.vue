<script setup lang="ts">
/**
 * InstitucionMasivaGrid — Spreadsheet-like editor to register IEs in bulk.
 *
 * - Loads static catalogs (tipo-inst-educ, regimen-educ, modalidades-form, niveles-ciclo)
 *   in parallel on mount.
 * - UGEL and Entidad Admin use per-row debounced typeahead against /api/entidades/search.
 * - Inline dropdown selects with search for catalog fields.
 * - Per-cell client-side validation feedback.
 * - Submits all rows in a batch transaction to /instituciones-masivas.
 * - Shows result banner with insertados / errores_validacion / errores_db.
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
    School,
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
}

interface EntidadItem {
    id: number;
    nombre: string;
    codigo: string;
}

interface FilaIE {
    _id: number;
    codigoInstitucion: string;
    codigoModular: string;
    nombreLegal: string;
    tipoInstEduc_id: number | null;
    regimenEduc_id: number | null;
    modalidadFormativa_id: number | null;
    nivelCiclo_id: number | null;
    entidadUgel_id: number | null;
    entidadAdmin_id: number | null;
    fechaInicio: string;
    fechaFin: string;

    _errors: Record<string, string>;

    // typeahead state per row
    _ugelQuery: string;
    _ugelResults: EntidadItem[];
    _ugelLoading: boolean;
    _ugelNombre: string;

    _adminQuery: string;
    _adminResults: EntidadItem[];
    _adminLoading: boolean;
    _adminNombre: string;
}

// ─── Catálogos estáticos ──────────────────────────────────────────────────────
const cats = reactive<Record<string, CatItem[]>>({
    tiposInstEduc: [],
    regimenesEduc: [],
    modalidades: [],
    nivelesCiclo: [],
});
const catsLoading = ref(true);

async function fetchCat(type: string): Promise<CatItem[]> {
    const res = await fetch(`/api/params/${type}`);
    const json = await res.json();
    return (json.data ?? []).map((i: any) => ({
        id: i.id,
        nombre: i.nombre || i.descripcion || String(i.id),
    }));
}

onMounted(async () => {
    try {
        const [tiposInstEduc, regimenesEduc, modalidades, nivelesCiclo] =
            await Promise.all([
                fetchCat('tipo-inst-educ'),
                fetchCat('regimen-educ'),
                fetchCat('modalidades-form'),
                fetchCat('niveles-ciclo'),
            ]);
        cats.tiposInstEduc = tiposInstEduc;
        cats.regimenesEduc = regimenesEduc;
        cats.modalidades = modalidades;
        cats.nivelesCiclo = nivelesCiclo;
    } catch (e) {
        console.error('Error cargando catálogos masivos IE:', e);
    } finally {
        catsLoading.value = false;
    }
});

// ─── Typeahead de entidades (UGEL y Entidad Admin) ───────────────────────────
// Usar abreviatura de tipo_entidad (estable, no cambia con el id)
const TIPO_UGEL = 'UGEL';
const TIPO_ADMIN = 'IE';

async function fetchEntidades(
    q: string,
    tipoCodigo: string,
): Promise<EntidadItem[]> {
    const url = new URL('/api/entidades/search', window.location.origin);
    url.searchParams.append('tipo_entidad_codigo', tipoCodigo);
    if (q) {
        url.searchParams.append('q', q);
    }
    const res = await fetch(url.toString());
    const json = await res.json();
    return (json.data ?? []).map((i: any) => ({
        id: i.id,
        nombre: i.nombre,
        codigo: i.codigo ?? '',
    }));
}

// Timers de debounce por (filaId, campo)
const debounceTimers: Record<string, ReturnType<typeof setTimeout>> = {};

function onEntidadInput(fila: FilaIE, campo: 'ugel' | 'admin', val: string) {
    if (campo === 'ugel') {
        fila._ugelQuery = val;
        fila._ugelNombre = val;
        if (!val) {
            fila.entidadUgel_id = null;
            fila._ugelResults = [];
            return;
        }
    } else {
        fila._adminQuery = val;
        fila._adminNombre = val;
        if (!val) {
            fila.entidadAdmin_id = null;
            fila._adminResults = [];
            return;
        }
    }

    const timerKey = `${fila._id}_${campo}`;
    clearTimeout(debounceTimers[timerKey]);
    debounceTimers[timerKey] = setTimeout(async () => {
        if (campo === 'ugel') {
            fila._ugelLoading = true;
            fila._ugelResults = await fetchEntidades(val, TIPO_UGEL);
            fila._ugelLoading = false;
        } else {
            fila._adminLoading = true;
            fila._adminResults = await fetchEntidades(val, TIPO_ADMIN);
            fila._adminLoading = false;
        }
    }, 300);
}

function selectEntidad(
    fila: FilaIE,
    campo: 'ugel' | 'admin',
    item: EntidadItem,
) {
    if (campo === 'ugel') {
        fila.entidadUgel_id = item.id;
        fila._ugelNombre = item.nombre;
        fila._ugelQuery = item.nombre;
        fila._ugelResults = [];
    } else {
        fila.entidadAdmin_id = item.id;
        fila._adminNombre = item.nombre;
        fila._adminQuery = item.nombre;
        fila._adminResults = [];
    }
    // Close typeahead by blurring
    (document.activeElement as HTMLElement)?.blur();
}

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;

function nuevaFila(): FilaIE {
    return {
        _id: nextId++,
        codigoInstitucion: '',
        codigoModular: '',
        nombreLegal: '',
        tipoInstEduc_id: null,
        regimenEduc_id: null,
        modalidadFormativa_id: null,
        nivelCiclo_id: null,
        entidadUgel_id: null,
        entidadAdmin_id: null,
        fechaInicio: '',
        fechaFin: '',
        _errors: {},
        _ugelQuery: '',
        _ugelResults: [],
        _ugelLoading: false,
        _ugelNombre: '',
        _adminQuery: '',
        _adminResults: [],
        _adminLoading: false,
        _adminNombre: '',
    };
}

const filas = ref<FilaIE[]>([]);

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
        const container = document.getElementById('ie-grid-scroll');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
}

function agregarFilas(n: number) {
    for (let i = 0; i < n; i++) {
        filas.value.push(nuevaFila());
    }
}

function eliminarFila(id: number) {
    filas.value = filas.value.filter((f) => f._id !== id);
    if (filas.value.length === 0) {
        filas.value.push(nuevaFila());
    }
}

// ─── Dropdown inline (catálogos estáticos) ────────────────────────────────────
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

function selectCat(fila: FilaIE, campo: keyof FilaIE, item: CatItem) {
    (fila as any)[campo] = item.id;
    fila._errors[campo as string] = '';
    closeDropdown();
}

function catNombre(lista: CatItem[], id: number | null): string {
    if (!id) {
        return '';
    }
    return lista.find((i) => i.id === id)?.nombre ?? String(id);
}

function filteredCat(lista: CatItem[]): CatItem[] {
    const q = dropdownSearch.value.toLowerCase().trim();
    if (!q) {
        return lista.slice(0, 80);
    }
    return lista.filter((i) => i.nombre.toLowerCase().includes(q)).slice(0, 80);
}

function onGridClick(e: MouseEvent) {
    const target = e.target as HTMLElement;
    if (!target.closest('[data-dropdown]')) {
        closeDropdown();
    }
}

// ─── Validación cliente ───────────────────────────────────────────────────────
function validar(): boolean {
    let ok = true;
    for (const fila of filas.value) {
        if (!fila.codigoInstitucion && !fila.nombreLegal) {
            continue;
        }
        fila._errors = {};

        const requeridos: (keyof FilaIE)[] = [
            'codigoInstitucion',
            'nombreLegal',
            'modalidadFormativa_id',
            'nivelCiclo_id',
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
    filas.value.filter((f) => f.codigoInstitucion || f.nombreLegal),
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
    if (!validar()) {
        return;
    }
    const filasFiltradas = filasConDatos.value;
    if (!filasFiltradas.length) {
        errorGeneral.value = 'No hay filas con datos para enviar.';
        return;
    }
    errorGeneral.value = '';
    enviando.value = true;
    resultado.value = null;

    const payload = filasFiltradas.map((f) => ({
        codigoInstitucion: f.codigoInstitucion,
        codigoModular: f.codigoModular || null,
        nombreLegal: f.nombreLegal,
        tipoInstEduc_id: f.tipoInstEduc_id,
        regimenEduc_id: f.regimenEduc_id,
        modalidadFormativa_id: f.modalidadFormativa_id,
        nivelCiclo_id: f.nivelCiclo_id,
        entidadUgel_id: f.entidadUgel_id,
        entidadAdmin_id: f.entidadAdmin_id,
        fechaInicio: f.fechaInicio || null,
        fechaFin: f.fechaFin || null,
    }));

    try {
        const res = await fetch('/instituciones-masivas', {
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
                        <div class="flex items-center gap-2">
                            <School class="h-5 w-5 text-primary" />
                            <h2 class="text-base font-semibold">
                                Carga Masiva de Instituciones Educativas
                            </h2>
                        </div>
                        <span
                            class="hidden text-xs text-muted-foreground sm:inline"
                        >
                            Ingrese un registro por fila. Los campos marcados
                            con
                            <span class="text-destructive">*</span> son
                            obligatorios.
                        </span>
                        <span
                            v-if="filasConDatos.length"
                            class="rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                        >
                            {{ filasConDatos.length }}
                            fila{{ filasConDatos.length !== 1 ? 's' : '' }} con
                            datos
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
                    <Loader2 class="h-5 w-5 animate-spin" />
                    Cargando catálogos...
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
                            {{ resultado.insertados }}
                            institución{{
                                resultado.insertados !== 1 ? 'es' : ''
                            }}
                            educativa{{
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
                    <div id="ie-grid-scroll" class="flex-1 overflow-auto">
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
                                        style="min-width: 140px"
                                    >
                                        Código IE
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 120px"
                                    >
                                        Cód. Modular
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 250px"
                                    >
                                        Nombre Legal
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 155px"
                                    >
                                        Tipo Institución
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 155px"
                                    >
                                        Régimen Educ.
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 165px"
                                    >
                                        Modalidad
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 155px"
                                    >
                                        Nivel / Ciclo
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 200px"
                                    >
                                        UGEL
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 200px"
                                    >
                                        Entidad Admin.
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 125px"
                                    >
                                        Fecha Inicio
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 125px"
                                    >
                                        Fecha Fin
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
                                    }"
                                >
                                    <!-- N° fila -->
                                    <td
                                        class="border-r border-b bg-muted/10 px-2 py-1 text-center text-muted-foreground select-none"
                                    >
                                        {{ idx + 1 }}
                                    </td>

                                    <!-- Código IE -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.codigoInstitucion"
                                            placeholder="Ej: 0123456"
                                            maxlength="30"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs font-semibold uppercase outline-none placeholder:text-muted-foreground/60 placeholder:normal-case"
                                            :class="
                                                fila._errors.codigoInstitucion
                                                    ? 'bg-destructive/5 text-destructive ring-1 ring-destructive ring-inset'
                                                    : ''
                                            "
                                        />
                                    </td>

                                    <!-- Cód. Modular -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.codigoModular"
                                            placeholder="Ej: 0654321"
                                            maxlength="30"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/60"
                                        />
                                    </td>

                                    <!-- Nombre Legal -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.nombreLegal"
                                            placeholder="Nombre legal de la institución"
                                            maxlength="200"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs font-medium outline-none placeholder:text-muted-foreground/60"
                                            :class="
                                                fila._errors.nombreLegal
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : ''
                                            "
                                        />
                                    </td>

                                    <!-- Tipo Institución -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'tipoInstEduc_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40"
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.tipoInstEduc_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.tiposInstEduc,
                                                        fila.tipoInstEduc_id,
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
                                                    'tipoInstEduc_id',
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
                                                    :id="`ds_${dropKey(fila._id, 'tipoInstEduc_id')}`"
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
                                                        cats.tiposInstEduc,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'tipoInstEduc_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.tipoInstEduc_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.tipoInstEduc_id ===
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

                                    <!-- Régimen Educ. -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'regimenEduc_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40"
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.regimenEduc_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.regimenesEduc,
                                                        fila.regimenEduc_id,
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
                                                    'regimenEduc_id',
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
                                                    :id="`ds_${dropKey(fila._id, 'regimenEduc_id')}`"
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
                                                        cats.regimenesEduc,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'regimenEduc_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.regimenEduc_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.regimenEduc_id ===
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

                                    <!-- Modalidad -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'modalidadFormativa_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors
                                                    .modalidadFormativa_id
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.modalidadFormativa_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.modalidades,
                                                        fila.modalidadFormativa_id,
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
                                                    'modalidadFormativa_id',
                                                )
                                            "
                                            class="absolute top-full left-0 z-40 mt-0.5 w-64 rounded-md border bg-background shadow-lg"
                                            data-dropdown
                                        >
                                            <div
                                                class="flex items-center gap-1.5 border-b px-2.5 py-1.5"
                                            >
                                                <Search
                                                    class="h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                                />
                                                <input
                                                    :id="`ds_${dropKey(fila._id, 'modalidadFormativa_id')}`"
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
                                                        cats.modalidades,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'modalidadFormativa_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.modalidadFormativa_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.modalidadFormativa_id ===
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

                                    <!-- Nivel / Ciclo -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'nivelCiclo_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors.nivelCiclo_id
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.nivelCiclo_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.nivelesCiclo,
                                                        fila.nivelCiclo_id,
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
                                                    'nivelCiclo_id',
                                                )
                                            "
                                            class="absolute top-full left-0 z-40 mt-0.5 w-60 rounded-md border bg-background shadow-lg"
                                            data-dropdown
                                        >
                                            <div
                                                class="flex items-center gap-1.5 border-b px-2.5 py-1.5"
                                            >
                                                <Search
                                                    class="h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                                />
                                                <input
                                                    :id="`ds_${dropKey(fila._id, 'nivelCiclo_id')}`"
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
                                                        cats.nivelesCiclo,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'nivelCiclo_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.nivelCiclo_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.nivelCiclo_id ===
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

                                    <!-- UGEL (typeahead) -->
                                    <td class="relative border-r border-b p-0">
                                        <div class="relative flex items-center">
                                            <Search
                                                class="absolute left-2 h-3 w-3 shrink-0 text-muted-foreground/40"
                                            />
                                            <input
                                                type="text"
                                                :value="fila._ugelNombre"
                                                @input="
                                                    onEntidadInput(
                                                        fila,
                                                        'ugel',
                                                        (
                                                            $event.target as HTMLInputElement
                                                        ).value,
                                                    )
                                                "
                                                @focus="
                                                    fila._ugelResults.length ===
                                                        0 &&
                                                    onEntidadInput(
                                                        fila,
                                                        'ugel',
                                                        fila._ugelQuery || '',
                                                    )
                                                "
                                                placeholder="Buscar UGEL..."
                                                class="w-full bg-transparent py-1.5 pr-2.5 pl-6 text-xs outline-none placeholder:text-muted-foreground/60"
                                                :class="
                                                    fila.entidadUgel_id
                                                        ? 'font-medium text-foreground'
                                                        : ''
                                                "
                                            />
                                            <Loader2
                                                v-if="fila._ugelLoading"
                                                class="absolute right-1.5 h-3 w-3 animate-spin text-muted-foreground"
                                            />
                                        </div>
                                        <!-- Resultados typeahead UGEL -->
                                        <div
                                            v-if="fila._ugelResults.length"
                                            class="absolute top-full left-0 z-40 mt-0.5 w-72 rounded-md border bg-background shadow-lg"
                                        >
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <div
                                                    v-for="item in fila._ugelResults"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectEntidad(
                                                            fila,
                                                            'ugel',
                                                            item,
                                                        )
                                                    "
                                                    class="cursor-pointer px-3 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.entidadUgel_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <div
                                                        class="truncate leading-tight font-medium"
                                                    >
                                                        {{ item.nombre }}
                                                    </div>
                                                    <div
                                                        class="text-[10px] text-muted-foreground"
                                                    >
                                                        {{ item.codigo }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Entidad Admin (typeahead) -->
                                    <td class="relative border-r border-b p-0">
                                        <div class="relative flex items-center">
                                            <Search
                                                class="absolute left-2 h-3 w-3 shrink-0 text-muted-foreground/40"
                                            />
                                            <input
                                                type="text"
                                                :value="fila._adminNombre"
                                                @input="
                                                    onEntidadInput(
                                                        fila,
                                                        'admin',
                                                        (
                                                            $event.target as HTMLInputElement
                                                        ).value,
                                                    )
                                                "
                                                @focus="
                                                    fila._adminResults
                                                        .length === 0 &&
                                                    onEntidadInput(
                                                        fila,
                                                        'admin',
                                                        fila._adminQuery || '',
                                                    )
                                                "
                                                placeholder="Buscar entidad admin..."
                                                class="w-full bg-transparent py-1.5 pr-2.5 pl-6 text-xs outline-none placeholder:text-muted-foreground/60"
                                                :class="
                                                    fila.entidadAdmin_id
                                                        ? 'font-medium text-foreground'
                                                        : ''
                                                "
                                            />
                                            <Loader2
                                                v-if="fila._adminLoading"
                                                class="absolute right-1.5 h-3 w-3 animate-spin text-muted-foreground"
                                            />
                                        </div>
                                        <!-- Resultados typeahead Admin -->
                                        <div
                                            v-if="fila._adminResults.length"
                                            class="absolute top-full left-0 z-40 mt-0.5 w-72 rounded-md border bg-background shadow-lg"
                                        >
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <div
                                                    v-for="item in fila._adminResults"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectEntidad(
                                                            fila,
                                                            'admin',
                                                            item,
                                                        )
                                                    "
                                                    class="cursor-pointer px-3 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.entidadAdmin_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <div
                                                        class="truncate leading-tight font-medium"
                                                    >
                                                        {{ item.nombre }}
                                                    </div>
                                                    <div
                                                        class="text-[10px] text-muted-foreground"
                                                    >
                                                        {{ item.codigo }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Fecha Inicio -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaInicio"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                        />
                                    </td>

                                    <!-- Fecha Fin -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaFin"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                        />
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
                                    IE{{
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
