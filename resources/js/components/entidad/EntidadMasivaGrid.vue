<script setup lang="ts">
/**
 * EntidadMasivaGrid — Spreadsheet-like editor to register entities in bulk.
 *
 * - Loads catalogs (entity types) in parallel on mount.
 * - Supports inline RUC/SUNAT lookup per row.
 * - Shows per-cell validation feedback before submitting.
 * - Submits all rows in a batch transaction to /entidades-masivas.
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
    Building2,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import SunatController from '@/actions/App/Http/Controllers/Api/SunatController';

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success', count: number): void;
}>();

// ─── Tipos ────────────────────────────────────────────────────────────────────
interface CatItem {
    id: number;
    nombre: string;
}

interface FilaEntidad {
    _id: number;
    tipoEntidad_id: number | null;
    ruc: string;
    razonSocial: string;
    razonComercial: string;
    activo: boolean;

    _errors: Record<string, string>;
    _sunatLoading: boolean;
}

// ─── Catálogos ────────────────────────────────────────────────────────────────
const cats = reactive<{ tiposEntidad: CatItem[] }>({
    tiposEntidad: [],
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
        const [tiposEntidad] = await Promise.all([fetchCat('tipo-entidad')]);
        cats.tiposEntidad = tiposEntidad;
    } catch (e) {
        console.error('Error cargando catálogo de tipos de entidad:', e);
    } finally {
        catsLoading.value = false;
    }
});

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;
function nuevaFila(): FilaEntidad {
    return {
        _id: nextId++,
        tipoEntidad_id: null,
        ruc: '',
        razonSocial: '',
        razonComercial: '',
        activo: true,

        _errors: {},
        _sunatLoading: false,
    };
}

const filas = ref<FilaEntidad[]>([]);

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
        const container = document.getElementById('entidad-grid-scroll');
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

function selectCat(fila: FilaEntidad, campo: keyof FilaEntidad, item: CatItem) {
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

// ─── SUNAT RUC Lookup per row ──────────────────────────────────────────────────
async function buscarRucRow(fila: FilaEntidad) {
    const ruc = fila.ruc.trim();
    if (ruc.length !== 11 || !/^\d{11}$/.test(ruc)) {
        return;
    }

    fila._sunatLoading = true;
    fila._errors['ruc'] = '';

    try {
        const response = await fetch(SunatController.ruc().url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN':
                    (
                        document.querySelector(
                            'meta[name="csrf-token"]',
                        ) as HTMLMetaElement
                    )?.content ?? '',
            },
            body: JSON.stringify({ ruc }),
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || 'No encontrado');
        if (result.data) {
            fila.razonSocial = result.data.nombre_o_razon_social || '';
            fila._errors['razonSocial'] = '';
        }
    } catch (e: any) {
        fila._errors['ruc'] = e.message || 'RUC no encontrado';
    } finally {
        fila._sunatLoading = false;
    }
}

function onRucInput(fila: FilaEntidad, val: string) {
    fila.ruc = val.replace(/\D/g, '').slice(0, 11);
    fila._errors['ruc'] = '';
    if (fila.ruc.length === 11) {
        buscarRucRow(fila);
    }
}

// ─── Validación ───────────────────────────────────────────────────────────────
function validar(): boolean {
    let ok = true;
    for (const fila of filas.value) {
        if (!fila.ruc && !fila.razonSocial) continue;
        fila._errors = {};

        if (!fila.tipoEntidad_id) {
            fila._errors['tipoEntidad_id'] = 'Requerido';
            ok = false;
        }

        if (!fila.ruc) {
            fila._errors['ruc'] = 'Requerido';
            ok = false;
        } else if (fila.ruc.length !== 11) {
            fila._errors['ruc'] = 'Debe tener 11 dígitos';
            ok = false;
        }

        if (!fila.razonSocial) {
            fila._errors['razonSocial'] = 'Requerido';
            ok = false;
        }
    }
    return ok;
}

const filasConDatos = computed(() =>
    filas.value.filter((f) => f.ruc || f.razonSocial),
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
        tipoEntidad_id: f.tipoEntidad_id,
        ruc: f.ruc,
        razonSocial: f.razonSocial,
        razonComercial: f.razonComercial || null,
        activo: f.activo,
    }));

    try {
        const res = await fetch('/entidades-masivas', {
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
                        <Building2 class="h-5 w-5 text-primary" />
                        <h2 class="text-base font-semibold">
                            Carga Masiva de Entidades
                        </h2>
                        <span
                            class="hidden text-xs text-muted-foreground sm:inline"
                        >
                            Ingrese los datos. Rellene el RUC para realizar la
                            consulta SUNAT automática.
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
                    <Loader2 class="h-5 w-5 animate-spin" /> Cargando catálogo
                    de tipo de entidad...
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
                            {{ resultado.insertados }} entidade{{
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
                    <div id="entidad-grid-scroll" class="flex-1 overflow-auto">
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
                                        style="min-width: 180px"
                                    >
                                        Tipo Entidad
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 160px"
                                    >
                                        RUC
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 320px"
                                    >
                                        Razón Social
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 250px"
                                    >
                                        Razón Comercial
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-center font-semibold"
                                        style="min-width: 80px"
                                    >
                                        Activo
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

                                    <!-- Tipo Entidad -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    'tipoEntidad_id',
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors.tipoEntidad_id
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.tipoEntidad_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.tiposEntidad,
                                                        fila.tipoEntidad_id,
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
                                                    'tipoEntidad_id',
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
                                                    :id="`ds_${dropKey(fila._id, 'tipoEntidad_id')}`"
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
                                                        cats.tiposEntidad,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            'tipoEntidad_id',
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.tipoEntidad_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.tipoEntidad_id ===
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

                                    <!-- RUC -->
                                    <td class="relative border-r border-b p-0">
                                        <div class="relative flex items-center">
                                            <input
                                                type="text"
                                                :value="fila.ruc"
                                                @input="
                                                    onRucInput(
                                                        fila,
                                                        (
                                                            $event.target as HTMLInputElement
                                                        ).value,
                                                    )
                                                "
                                                placeholder="11 dígitos"
                                                maxlength="11"
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/60"
                                                :class="
                                                    fila._errors.ruc
                                                        ? 'bg-destructive/5 text-destructive ring-1 ring-destructive ring-inset'
                                                        : ''
                                                "
                                            />
                                            <button
                                                type="button"
                                                @click="buscarRucRow(fila)"
                                                :disabled="
                                                    fila._sunatLoading ||
                                                    fila.ruc.length !== 11
                                                "
                                                class="absolute right-1 rounded p-1 text-muted-foreground hover:bg-muted disabled:opacity-30"
                                                title="Buscar RUC en SUNAT"
                                            >
                                                <Loader2
                                                    v-if="fila._sunatLoading"
                                                    class="h-3 w-3 animate-spin"
                                                />
                                                <Search
                                                    v-else
                                                    class="h-3 w-3"
                                                />
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Razón Social -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.razonSocial"
                                            placeholder="Nombre o Razón social"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs font-medium outline-none"
                                            :class="
                                                fila._errors.razonSocial
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : ''
                                            "
                                        />
                                    </td>

                                    <!-- Razón Comercial -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.razonComercial"
                                            placeholder="Nombre comercial (Opcional)"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                        />
                                    </td>

                                    <!-- Activo -->
                                    <td
                                        class="border-r border-b p-0 text-center"
                                    >
                                        <div
                                            class="flex items-center justify-center px-2 py-1.5"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="fila.activo"
                                                class="h-4 w-4 cursor-pointer rounded border-input accent-primary"
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
                                    entidad{{
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
