<script setup lang="ts">
/**
 * AltaMasivaGrid — Editor tipo spreadsheet para crear altas masivas en una IE.
 *
 * - Carga los catálogos (condición, tipo contrato, rol, situación, área, cargo)
 *   en paralelo al montar el componente.
 * - Cada fila es un alta a crear. Las columnas con catálogo muestran un select
 *   inline con búsqueda. La columna "Trabajador" hace typeahead contra la API.
 * - Validación visual por celda (borde rojo) antes de enviar.
 * - Inserts por lote en el backend → respuesta con conteo y errores.
 */
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
} from 'lucide-vue-next';
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import { Button } from '@/components/ui/button';

// ─── Props / Emits ────────────────────────────────────────────────────────────
const props = defineProps<{
    institucionId: number;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success', count: number): void;
}>();

// ─── Tipos ────────────────────────────────────────────────────────────────────
interface CatItem {
    id: number;
    nombre: string;
}
interface TrabajadorItem {
    id: number;
    codigo: string;
    nombre: string;
    docIdentidad: string;
}
interface FilaAlta {
    _id: number;
    trabajador_id: number | null;
    _trabajadorNombre: string;
    condicionLaboral_id: number | null;
    tipoContrato_id: number | null;
    rolTrabajador_id: number | null;
    situacionLaboral_id: number | null;
    area_id: number | null;
    cargo_id: number | null;
    localInstEduc_id: number | null;
    fechaInicio: string;
    fechaFin: string;
    codigoAirsp: string;
    observacion: string;
    _errors: Record<string, string>;
}

// ─── Catálogos ────────────────────────────────────────────────────────────────
const cats = reactive<Record<string, CatItem[]>>({
    condiciones: [],
    contratos: [],
    roles: [],
    situaciones: [],
    areas: [],
    cargos: [],
    locales: [],
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

// Locales de marcación de ESTA IE (endpoint distinto a /api/params)
async function fetchLocales(): Promise<CatItem[]> {
    const res = await fetch(
        `/api/instituciones/${props.institucionId}/locales`,
    );
    const json = await res.json();

    return (json.data ?? []).map((i: any) => ({
        id: i.id,
        nombre: i.nombre || String(i.id),
    }));
}

onMounted(async () => {
    const [condiciones, contratos, roles, situaciones, areas, cargos, locales] =
        await Promise.all([
            fetchCat('condiciones-laborales'),
            fetchCat('tipos-contrato'),
            fetchCat('roles-trabajador'),
            fetchCat('situaciones-laborales'),
            fetchCat('areas'),
            fetchCat('cargos'),
            fetchLocales(),
        ]);
    cats.condiciones = condiciones;
    cats.contratos = contratos;
    cats.roles = roles;
    cats.situaciones = situaciones;
    cats.areas = areas;
    cats.cargos = cargos;
    cats.locales = locales;
    catsLoading.value = false;
});

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;
function nuevaFila(): FilaAlta {
    return {
        _id: nextId++,
        trabajador_id: null,
        _trabajadorNombre: '',
        condicionLaboral_id: null,
        tipoContrato_id: null,
        rolTrabajador_id: null,
        situacionLaboral_id: null,
        area_id: null,
        cargo_id: null,
        localInstEduc_id: null,
        fechaInicio: '',
        fechaFin: '',
        codigoAirsp: '',
        observacion: '',
        _errors: {},
    };
}

const filas = ref<FilaAlta[]>([nuevaFila(), nuevaFila(), nuevaFila()]);

function agregarFila() {
    filas.value.push(nuevaFila());
    nextTick(() => {
        const container = document.getElementById('grid-scroll');

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

function limpiarFilasVacias() {
    const llenas = filas.value.filter((f) => f.trabajador_id !== null);
    filas.value = llenas.length ? llenas : [nuevaFila()];
}

// ─── Dropdown inline (un solo dropdown activo a la vez) ───────────────────────
const activeDropdown = ref<string | null>(null); // formato: `${filaId}_${campo}`
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

function selectCat(fila: FilaAlta, campo: keyof FilaAlta, item: CatItem) {
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

// ─── Typeahead de Trabajador ───────────────────────────────────────────────────
const trabSearch = reactive<Record<number, string>>({}); // filaId → query
const trabOptions = reactive<Record<number, TrabajadorItem[]>>({});
const trabLoading = reactive<Record<number, boolean>>({});
const trabDropOpen = ref<number | null>(null);
const trabTimers: Record<number, ReturnType<typeof setTimeout>> = {};

function onTrabInput(fila: FilaAlta, val: string) {
    fila.trabajador_id = null;
    fila._trabajadorNombre = val;
    fila._errors['trabajador_id'] = '';
    trabSearch[fila._id] = val;
    clearTimeout(trabTimers[fila._id]);

    if (!val || val.length < 1) {
        trabOptions[fila._id] = [];
        trabDropOpen.value = null;

        return;
    }

    trabTimers[fila._id] = setTimeout(() => buscarTrabajador(fila), 300);
}

async function buscarTrabajador(fila: FilaAlta) {
    const q = trabSearch[fila._id] ?? '';

    if (!q) {
        return;
    }

    trabLoading[fila._id] = true;
    trabDropOpen.value = fila._id;

    try {
        const res = await fetch(
            `/api/trabajadores/search?search=${encodeURIComponent(q)}`,
        );
        const json = await res.json();
        // El endpoint devuelve array directo (no paginado)
        const lista: any[] = Array.isArray(json) ? json : (json.data ?? []);
        trabOptions[fila._id] = lista.map((t: any) => {
            const p = t.persona ?? {};
            const apellidos = [p.paterno, p.materno].filter(Boolean).join(' ');
            const nombre = [apellidos, p.nombre].filter(Boolean).join(', ');

            return {
                id: t.id,
                codigo: t.codigo ?? '',
                nombre: nombre || `Trabajador #${t.id}`,
                docIdentidad: p.docIdentidad ?? '',
            };
        });
    } catch {
        trabOptions[fila._id] = [];
    } finally {
        trabLoading[fila._id] = false;
    }
}

function selectTrabajador(fila: FilaAlta, trab: TrabajadorItem) {
    fila.trabajador_id = trab.id;
    fila._trabajadorNombre = trab.nombre;
    fila._errors['trabajador_id'] = '';
    trabDropOpen.value = null;
    trabOptions[fila._id] = [];
}

// ─── Validación ───────────────────────────────────────────────────────────────
const REQUERIDOS: (keyof FilaAlta)[] = [
    'trabajador_id',
    'condicionLaboral_id',
    'tipoContrato_id',
    'rolTrabajador_id',
    'situacionLaboral_id',
    'area_id',
    'cargo_id',
    'fechaInicio',
];

function validar(): boolean {
    let ok = true;

    for (const fila of filas.value) {
        // Si la fila está completamente vacía, ignorarla
        if (
            !fila.trabajador_id &&
            !fila.condicionLaboral_id &&
            !fila.fechaInicio
        ) {
            continue;
        }

        fila._errors = {};

        for (const campo of REQUERIDOS) {
            if (!fila[campo]) {
                fila._errors[campo as string] = 'Requerido';
                ok = false;
            }
        }
    }

    return ok;
}

const filasConDatos = computed(() =>
    filas.value.filter(
        (f) => f.trabajador_id || f.condicionLaboral_id || f.fechaInicio,
    ),
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
        trabajador_id: f.trabajador_id,
        condicionLaboral_id: f.condicionLaboral_id,
        tipoContrato_id: f.tipoContrato_id,
        rolTrabajador_id: f.rolTrabajador_id,
        situacionLaboral_id: f.situacionLaboral_id,
        area_id: f.area_id,
        cargo_id: f.cargo_id,
        localInstEduc_id: f.localInstEduc_id || null,
        fechaInicio: f.fechaInicio,
        fechaFin: f.fechaFin || null,
        codigoAirsp: f.codigoAirsp || null,
        observacion: f.observacion || null,
    }));

    try {
        const res = await fetch(
            `/instituciones/${props.institucionId}/altas-masivas`,
            {
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
            },
        );
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

// Cerrar dropdowns al hacer clic fuera
function onGridClick(e: MouseEvent) {
    const target = e.target as HTMLElement;

    if (!target.closest('[data-dropdown]')) {
        closeDropdown();
    }

    if (!target.closest('[data-trab-drop]')) {
        trabDropOpen.value = null;
    }
}

// Columnas de catálogo
const colsCat = [
    {
        campo: 'condicionLaboral_id',
        lista: 'condiciones',
        label: 'Condición',
        width: '140px',
    },
    {
        campo: 'tipoContrato_id',
        lista: 'contratos',
        label: 'Tipo Contrato',
        width: '140px',
    },
    { campo: 'rolTrabajador_id', lista: 'roles', label: 'Rol', width: '130px' },
    {
        campo: 'situacionLaboral_id',
        lista: 'situaciones',
        label: 'Situación Lab.',
        width: '140px',
    },
    { campo: 'area_id', lista: 'areas', label: 'Área', width: '130px' },
    { campo: 'cargo_id', lista: 'cargos', label: 'Cargo', width: '130px' },
] as const;

// Columna de local de marcación (opcional, no obligatoria)
const colLocal = {
    campo: 'localInstEduc_id',
    lista: 'locales',
    label: 'Local Marcación',
    width: '160px',
} as const;
</script>

<template>
    <Teleport to="body">
        <!-- Overlay — z-[9999] garantiza que esté sobre sidebar, navbar y cualquier otro elemento -->
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
                            Carga Masiva de Altas
                        </h2>
                        <span class="text-xs text-muted-foreground">
                            Completa la grilla y presiona
                            <strong>Guardar Altas</strong>.
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
                        class="shrink-0 space-y-2 border-b px-5 py-3"
                    >
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-emerald-700 dark:text-emerald-400"
                        >
                            <CheckCircle2 class="h-4 w-4 shrink-0" />
                            {{ resultado.insertados }} alta{{
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
                            <span class="font-semibold">Filas rechazadas:</span>
                            <span
                                v-for="(
                                    msg, row
                                ) in resultado.errores_validacion"
                                :key="row"
                                class="ml-2"
                                >fila {{ row }}: {{ msg }};</span
                            >
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
                    <div id="grid-scroll" class="flex-1 overflow-auto">
                        <table
                            class="w-max min-w-full border-separate border-spacing-0 text-xs"
                        >
                            <!-- Cabecera fija -->
                            <thead
                                class="sticky top-0 z-20 bg-muted/90 backdrop-blur-sm"
                            >
                                <tr>
                                    <th
                                        class="w-8 border-r border-b px-2 py-2 text-center text-muted-foreground"
                                    >
                                        #
                                    </th>
                                    <!-- Trabajador -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 220px"
                                    >
                                        Trabajador
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Catálogos -->
                                    <th
                                        v-for="col in colsCat"
                                        :key="col.campo"
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        :style="`min-width:${col.width}`"
                                    >
                                        {{ col.label }}
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Local de marcación (opcional) -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        :style="`min-width:${colLocal.width}`"
                                    >
                                        {{ colLocal.label }}
                                    </th>
                                    <!-- Fechas y extras -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 130px"
                                    >
                                        Fecha Inicio
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 130px"
                                    >
                                        Fecha Fin
                                    </th>
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 110px"
                                    >
                                        Cód. AIRSP
                                    </th>
                                    <th
                                        class="border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 160px"
                                    >
                                        Observación
                                    </th>
                                    <th class="w-8 border-b px-2 py-2"></th>
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
                                        class="border-r border-b px-2 py-1 text-center text-muted-foreground select-none"
                                    >
                                        {{ idx + 1 }}
                                    </td>

                                    <!-- ── TRABAJADOR (typeahead) ── -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-trab-drop
                                    >
                                        <div class="relative">
                                            <input
                                                type="text"
                                                :value="fila._trabajadorNombre"
                                                @input="
                                                    onTrabInput(
                                                        fila,
                                                        (
                                                            $event.target as HTMLInputElement
                                                        ).value,
                                                    )
                                                "
                                                @focus="trabDropOpen = fila._id"
                                                placeholder="Buscar trabajador..."
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/60"
                                                :class="
                                                    fila._errors[
                                                        'trabajador_id'
                                                    ]
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : ''
                                                "
                                            />
                                            <Loader2
                                                v-if="trabLoading[fila._id]"
                                                class="absolute top-1.5 right-2 h-3.5 w-3.5 animate-spin text-muted-foreground"
                                            />
                                            <!-- Dropdown trabajador -->
                                            <div
                                                v-if="
                                                    trabDropOpen === fila._id &&
                                                    (trabOptions[fila._id]
                                                        ?.length ?? 0) > 0
                                                "
                                                class="absolute top-full left-0 z-40 mt-0.5 w-72 rounded-md border bg-background shadow-lg"
                                            >
                                                <div
                                                    v-for="trab in trabOptions[
                                                        fila._id
                                                    ]"
                                                    :key="trab.id"
                                                    @mousedown.prevent="
                                                        selectTrabajador(
                                                            fila,
                                                            trab,
                                                        )
                                                    "
                                                    class="cursor-pointer px-3 py-2 hover:bg-accent hover:text-accent-foreground"
                                                >
                                                    <div
                                                        class="truncate font-medium"
                                                    >
                                                        {{ trab.nombre }}
                                                    </div>
                                                    <div
                                                        class="text-[11px] text-muted-foreground"
                                                    >
                                                        {{ trab.codigo }} ·
                                                        {{ trab.docIdentidad }}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Chip si ya seleccionó -->
                                            <div
                                                v-if="fila.trabajador_id"
                                                class="pointer-events-none absolute inset-0 flex items-center gap-1 bg-background/95 px-2.5 text-xs"
                                            >
                                                <Check
                                                    class="h-3 w-3 shrink-0 text-emerald-500"
                                                />
                                                <span
                                                    class="truncate font-medium"
                                                    >{{
                                                        fila._trabajadorNombre
                                                    }}</span
                                                >
                                                <button
                                                    @mousedown.prevent="
                                                        fila.trabajador_id =
                                                            null;
                                                        fila._trabajadorNombre =
                                                            '';
                                                    "
                                                    class="pointer-events-auto ml-auto shrink-0 rounded p-0.5 hover:bg-muted"
                                                >
                                                    <X
                                                        class="h-3 w-3 text-muted-foreground"
                                                    />
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ── CATÁLOGOS ── -->
                                    <td
                                        v-for="col in colsCat"
                                        :key="col.campo"
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    col.campo,
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors[col.campo]
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !(fila as any)[col.campo]
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats[col.lista],
                                                        (fila as any)[
                                                            col.campo
                                                        ],
                                                    ) || 'Seleccionar...'
                                                }}
                                            </span>
                                            <ChevronDown
                                                class="h-3 w-3 shrink-0 text-muted-foreground/60"
                                            />
                                        </button>

                                        <!-- Dropdown catálogo -->
                                        <div
                                            v-if="
                                                activeDropdown ===
                                                dropKey(fila._id, col.campo)
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
                                                    :id="`ds_${dropKey(fila._id, col.campo)}`"
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
                                                        cats[col.lista],
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            col.campo as keyof FilaAlta,
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        (fila as any)[
                                                            col.campo
                                                        ] === item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            (fila as any)[
                                                                col.campo
                                                            ] === item.id
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
                                                <div
                                                    v-if="
                                                        !filteredCat(
                                                            cats[col.lista],
                                                        ).length
                                                    "
                                                    class="py-3 text-center text-xs text-muted-foreground"
                                                >
                                                    Sin resultados
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ── LOCAL DE MARCACIÓN (opcional) ── -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDropdown(
                                                    fila._id,
                                                    colLocal.campo,
                                                )
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40"
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.localInstEduc_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    catNombre(
                                                        cats.locales,
                                                        fila.localInstEduc_id,
                                                    ) || 'Sin local'
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
                                                    colLocal.campo,
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
                                                    :id="`ds_${dropKey(fila._id, colLocal.campo)}`"
                                                    v-model="dropdownSearch"
                                                    type="text"
                                                    placeholder="Buscar local..."
                                                    class="flex-1 bg-transparent text-xs outline-none placeholder:text-muted-foreground/60"
                                                    @click.stop
                                                />
                                            </div>
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <!-- Opción "sin local" -->
                                                <div
                                                    @mousedown.prevent="
                                                        fila.localInstEduc_id =
                                                            null;
                                                        closeDropdown();
                                                    "
                                                    class="cursor-pointer px-2.5 py-1.5 text-xs text-muted-foreground hover:bg-accent hover:text-accent-foreground"
                                                >
                                                    Sin local
                                                </div>
                                                <div
                                                    v-for="item in filteredCat(
                                                        cats.locales,
                                                    )"
                                                    :key="item.id"
                                                    @mousedown.prevent="
                                                        selectCat(
                                                            fila,
                                                            colLocal.campo as keyof FilaAlta,
                                                            item,
                                                        )
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.localInstEduc_id ===
                                                        item.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.localInstEduc_id ===
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
                                                <div
                                                    v-if="!cats.locales.length"
                                                    class="py-3 text-center text-xs text-muted-foreground"
                                                >
                                                    IE sin locales
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ── FECHA INICIO ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaInicio"
                                            @change="
                                                fila._errors['fechaInicio'] = ''
                                            "
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                            :class="
                                                fila._errors['fechaInicio']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- ── FECHA FIN ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaFin"
                                            :min="fila.fechaInicio || undefined"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- ── AIRSP ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.codigoAirsp"
                                            maxlength="50"
                                            placeholder="Opcional"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- ── OBSERVACIÓN ── -->
                                    <td class="border-b p-0">
                                        <input
                                            type="text"
                                            v-model="fila.observacion"
                                            maxlength="500"
                                            placeholder="Opcional"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/40 hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- ── ELIMINAR FILA ── -->
                                    <td class="border-b px-1 py-1 text-center">
                                        <button
                                            type="button"
                                            @click="eliminarFila(fila._id)"
                                            class="rounded p-1 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100 hover:bg-destructive/10 hover:text-destructive"
                                            title="Eliminar fila"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ── Acciones de fila ── -->
                    <div
                        class="flex shrink-0 items-center gap-2 border-t bg-muted/20 px-4 py-2.5"
                    >
                        <button
                            type="button"
                            @click="agregarFila"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary"
                        >
                            <Plus class="h-3.5 w-3.5" /> Agregar fila
                        </button>
                        <button
                            type="button"
                            @click="agregarFilas(10)"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary"
                        >
                            <Plus class="h-3.5 w-3.5" /> +10 filas
                        </button>
                        <button
                            type="button"
                            @click="limpiarFilasVacias"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-amber-500/50 hover:text-amber-600"
                        >
                            Limpiar vacías
                        </button>
                    </div>

                    <!-- ── Footer ── -->
                    <div
                        class="flex shrink-0 items-center justify-between border-t bg-background px-5 py-3.5"
                    >
                        <div class="flex items-center gap-3">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="emit('close')"
                                :disabled="enviando"
                            >
                                Cerrar
                            </Button>
                            <Button
                                v-if="resultado"
                                variant="outline"
                                size="sm"
                                @click="resetGrid"
                            >
                                Nueva carga
                            </Button>
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                v-if="filasConDatos.length"
                                class="text-xs text-muted-foreground"
                            >
                                {{ filasConDatos.length }} alta{{
                                    filasConDatos.length !== 1 ? 's' : ''
                                }}
                                a crear
                            </span>
                            <Button
                                size="sm"
                                @click="enviar"
                                :disabled="
                                    enviando ||
                                    !filasConDatos.length ||
                                    !!resultado
                                "
                                class="min-w-[140px]"
                            >
                                <Loader2
                                    v-if="enviando"
                                    class="mr-2 h-4 w-4 animate-spin"
                                />
                                {{
                                    enviando
                                        ? 'Guardando...'
                                        : `Guardar ${filasConDatos.length || ''} Alta${filasConDatos.length !== 1 ? 's' : ''}`
                                }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
