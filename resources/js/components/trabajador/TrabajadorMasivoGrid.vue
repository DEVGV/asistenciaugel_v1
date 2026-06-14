<script setup lang="ts">
/**
 * TrabajadorMasivoGrid — Spreadsheet-like editor to register workers in bulk.
 *
 * - Loads all catalogs (doc types, genders, countries, conditions, contracts, roles, situations, areas, cargos, profiles) in parallel.
 * - Supports typeahead/manual input with inline SUNAT/RENIEC lookup per row.
 * - Supports optional bulk assignment to educational institutions (Alta Laboral) with inline IE search.
 * - Shows validation feedback per cell before submitting.
 * - Submits all rows in a batch transaction to /trabajadores-masivos.
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

interface FilaTrabajador {
    _id: number;
    tipoDocIdentidad_id: number | null;
    docIdentidad: string;
    paterno: string;
    materno: string;
    nombre: string;
    sexo_id: number | null;
    pais_id: number | null;
    fechaNac: string;

    // Alta fields
    institucionEducativa_id: number | null;
    _ieNombre: string;
    condicionLaboral_id: number | null;
    tipoContrato_id: number | null;
    rolTrabajador_id: number | null;
    situacionLaboral_id: number | null;
    area_id: number | null;
    cargo_id: number | null;
    fechaInicio: string;
    fechaFin: string;
    codigoAirsp: string;
    observacion: string;
    perfil_id: number | null;
    localInstEduc_id: number | null;

    _errors: Record<string, string>;
    _reniecLoading: boolean;
    // Locales de marcación de la IE de esta fila (cargados on-demand)
    _locales: CatItem[];
    _localesLoading: boolean;
}

// ─── Catálogos ────────────────────────────────────────────────────────────────
const cats = reactive<Record<string, CatItem[]>>({
    tiposDoc: [],
    sexos: [],
    paises: [],
    condiciones: [],
    contratos: [],
    roles: [],
    situaciones: [],
    areas: [],
    cargos: [],
    perfiles: [],
});
const catsLoading = ref(true);
const incluirAltaGlobal = ref(false);

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
        const [
            tiposDoc,
            sexos,
            paises,
            condiciones,
            contratos,
            roles,
            situaciones,
            areas,
            cargos,
            perfiles,
        ] = await Promise.all([
            fetchCat('tipos-doc-identidad'),
            fetchCat('sexos'),
            fetchCat('paises'),
            fetchCat('condiciones-laborales'),
            fetchCat('tipos-contrato'),
            fetchCat('roles-trabajador'),
            fetchCat('situaciones-laborales'),
            fetchCat('areas'),
            fetchCat('cargos'),
            fetchCat('perfiles'),
        ]);
        cats.tiposDoc = tiposDoc;
        cats.sexos = sexos;
        cats.paises = paises;
        cats.condiciones = condiciones;
        cats.contratos = contratos;
        cats.roles = roles;
        cats.situaciones = situaciones;
        cats.areas = areas;
        cats.cargos = cargos;
        cats.perfiles = perfiles;
    } catch (e) {
        console.error('Error cargando catálogos masivos:', e);
    } finally {
        catsLoading.value = false;
    }
});

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;
function nuevaFila(): FilaTrabajador {
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

        // Alta fields
        institucionEducativa_id: null,
        _ieNombre: '',
        condicionLaboral_id: null,
        tipoContrato_id: null,
        rolTrabajador_id: null,
        situacionLaboral_id: null,
        area_id: null,
        cargo_id: null,
        fechaInicio: '',
        fechaFin: '',
        codigoAirsp: '',
        observacion: '',
        perfil_id: null,
        localInstEduc_id: null,

        _errors: {},
        _reniecLoading: false,
        _locales: [],
        _localesLoading: false,
    };
}

const filas = ref<FilaTrabajador[]>([]);

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

// ─── Dropdown inline ──────────────────────────────────────────────────────────
const activeDropdown = ref<string | null>(null); // `${filaId}_${campo}`
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

function selectCat(
    fila: FilaTrabajador,
    campo: keyof FilaTrabajador,
    item: CatItem,
) {
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

// ─── Typeahead de IE por fila ──────────────────────────────────────────────────
const ieSearch = reactive<Record<number, string>>({}); // filaId → query
const ieOptions = reactive<Record<number, { id: number; nombre: string }[]>>(
    {},
);
const ieLoading = reactive<Record<number, boolean>>({});
const ieDropOpen = ref<number | null>(null);
const ieTimers: Record<number, ReturnType<typeof setTimeout>> = {};

function onIeInput(fila: FilaTrabajador, val: string) {
    fila.institucionEducativa_id = null;
    fila._ieNombre = val;
    fila._errors['institucionEducativa_id'] = '';
    ieSearch[fila._id] = val;
    clearTimeout(ieTimers[fila._id]);

    if (!val || val.length < 1) {
        ieOptions[fila._id] = [];
        ieDropOpen.value = null;

        return;
    }

    ieTimers[fila._id] = setTimeout(() => buscarIe(fila), 300);
}

async function buscarIe(fila: FilaTrabajador) {
    const q = ieSearch[fila._id] ?? '';

    if (!q) {
        return;
    }

    ieLoading[fila._id] = true;
    ieDropOpen.value = fila._id;

    try {
        const res = await fetch(
            `/api/instituciones/search?search=${encodeURIComponent(q)}&per_page=30`,
        );
        const json = await res.json();
        const lista: any[] = json.data ?? [];
        ieOptions[fila._id] = lista.map((ie: any) => ({
            id: ie.id,
            nombre: ie.nombreLegal ?? ie.codigoInstitucion ?? String(ie.id),
        }));
    } catch {
        ieOptions[fila._id] = [];
    } finally {
        ieLoading[fila._id] = false;
    }
}

function selectIe(fila: FilaTrabajador, ie: { id: number; nombre: string }) {
    fila.institucionEducativa_id = ie.id;
    fila._ieNombre = ie.nombre;
    fila._errors['institucionEducativa_id'] = '';
    ieDropOpen.value = null;
    ieOptions[fila._id] = [];
    // Al cambiar de IE, el local previo deja de ser válido: limpiar y recargar.
    fila.localInstEduc_id = null;
    cargarLocalesFila(fila);
}

// Carga los locales de marcación de la IE de esta fila.
async function cargarLocalesFila(fila: FilaTrabajador) {
    if (!fila.institucionEducativa_id) {
        fila._locales = [];

        return;
    }

    fila._localesLoading = true;

    try {
        const res = await fetch(
            `/api/instituciones/${fila.institucionEducativa_id}/locales`,
        );
        const json = await res.json();
        fila._locales = (json.data ?? []).map((i: any) => ({
            id: i.id,
            nombre: i.nombre || String(i.id),
        }));
    } catch {
        fila._locales = [];
    } finally {
        fila._localesLoading = false;
    }
}

// ─── RENIEC/SUNAT Lookup per row ──────────────────────────────────────────────
async function buscarReniecRow(fila: FilaTrabajador) {
    if (!fila.docIdentidad || fila.docIdentidad.length !== 8) {
        return;
    }

    const isDni =
        cats.tiposDoc.find((t) => t.id === fila.tipoDocIdentidad_id)
            ?.abreviatura === 'DNI';

    if (!isDni) {
        return;
    }

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

        if (!response.ok) {
            throw new Error(result.message || 'No encontrado');
        }

        if (result.data) {
            fila.nombre = result.data.nombres || '';
            fila.paterno = result.data.apellido_paterno || '';
            fila.materno = result.data.apellido_materno || '';
            fila._errors['nombre'] = '';
            fila._errors['paterno'] = '';
            fila._errors['materno'] = '';
        }
    } catch (e: any) {
        fila._errors['docIdentidad'] = 'DNI no encontrado';
    } finally {
        fila._reniecLoading = false;
    }
}

function checkDuplicadoEnCarga(fila: FilaTrabajador): boolean {
    if (!fila.docIdentidad) {
        return false;
    }

    const key = `${fila.tipoDocIdentidad_id}_${fila.docIdentidad.trim().toUpperCase()}`;
    const otras = filas.value.filter(
        (f) =>
            f._id !== fila._id &&
            (f.docIdentidad || f.paterno || f.nombre) &&
            `${f.tipoDocIdentidad_id}_${f.docIdentidad.trim().toUpperCase()}` ===
                key,
    );

    return otras.length > 0;
}

function onDniInput(fila: FilaTrabajador, val: string) {
    fila.docIdentidad = val;
    fila._errors['docIdentidad'] = '';

    // Verificar duplicado en tiempo real
    if (val && checkDuplicadoEnCarga({ ...fila, docIdentidad: val })) {
        fila._errors['docIdentidad'] = 'Documento repetido en esta carga';

        return;
    }

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

    // Detectar documentos duplicados dentro de la misma carga
    const filasActivas = filas.value.filter(
        (f) => f.docIdentidad || f.paterno || f.nombre,
    );
    const docsSeen = new Map<string, number[]>(); // doc → [índices _id]

    for (const fila of filasActivas) {
        if (!fila.docIdentidad) {
            continue;
        }

        const key = `${fila.tipoDocIdentidad_id}_${fila.docIdentidad.trim().toUpperCase()}`;

        if (!docsSeen.has(key)) {
            docsSeen.set(key, []);
        }

        docsSeen.get(key)!.push(fila._id);
    }

    for (const fila of filas.value) {
        if (!fila.docIdentidad && !fila.paterno && !fila.nombre) {
            continue;
        }

        fila._errors = {};

        // Validar Persona
        const personaReq: (keyof FilaTrabajador)[] = [
            'tipoDocIdentidad_id',
            'docIdentidad',
            'paterno',
            'materno',
            'nombre',
            'sexo_id',
            'pais_id',
        ];

        for (const campo of personaReq) {
            if (!fila[campo]) {
                fila._errors[campo as string] = 'Requerido';
                ok = false;
            }
        }

        // Validar duplicado de documento en esta carga
        if (fila.docIdentidad) {
            const key = `${fila.tipoDocIdentidad_id}_${fila.docIdentidad.trim().toUpperCase()}`;
            const duplicados = docsSeen.get(key) ?? [];

            if (duplicados.length > 1) {
                fila._errors['docIdentidad'] =
                    'Documento repetido en esta carga';
                ok = false;
            }
        }

        // Validar Alta Laboral
        if (incluirAltaGlobal.value) {
            const altaReq: (keyof FilaTrabajador)[] = [
                'institucionEducativa_id',
                'condicionLaboral_id',
                'tipoContrato_id',
                'situacionLaboral_id',
                'area_id',
                'cargo_id',
                'fechaInicio',
            ];

            for (const campo of altaReq) {
                if (!fila[campo]) {
                    fila._errors[campo as string] = 'Requerido';
                    ok = false;
                }
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
        tipoDocIdentidad_id: f.tipoDocIdentidad_id,
        docIdentidad: f.docIdentidad,
        paterno: f.paterno,
        materno: f.materno,
        nombre: f.nombre,
        sexo_id: f.sexo_id,
        pais_id: f.pais_id,
        fechaNac: f.fechaNac || null,

        // Alta Laboral
        incluir_alta: incluirAltaGlobal.value,
        institucionEducativa_id: incluirAltaGlobal.value
            ? f.institucionEducativa_id
            : null,
        condicionLaboral_id: incluirAltaGlobal.value
            ? f.condicionLaboral_id
            : null,
        tipoContrato_id: incluirAltaGlobal.value ? f.tipoContrato_id : null,
        rolTrabajador_id: incluirAltaGlobal.value ? f.rolTrabajador_id : null,
        situacionLaboral_id: incluirAltaGlobal.value
            ? f.situacionLaboral_id
            : null,
        area_id: incluirAltaGlobal.value ? f.area_id : null,
        cargo_id: incluirAltaGlobal.value ? f.cargo_id : null,
        fechaInicio: incluirAltaGlobal.value ? f.fechaInicio : null,
        fechaFin: incluirAltaGlobal.value ? f.fechaFin || null : null,
        codigoAirsp: incluirAltaGlobal.value ? f.codigoAirsp || null : null,
        observacion: incluirAltaGlobal.value ? f.observacion || null : null,
        perfil_id: incluirAltaGlobal.value ? f.perfil_id : null,
        localInstEduc_id: incluirAltaGlobal.value
            ? f.localInstEduc_id || null
            : null,
    }));

    try {
        const res = await fetch('/trabajadores-masivos', {
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

    if (!target.closest('[data-dropdown]')) {
        closeDropdown();
    }

    if (!target.closest('[data-ie-drop]')) {
        ieDropOpen.value = null;
    }
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
                            Carga Masiva de Trabajadores
                        </h2>
                        <span
                            class="hidden text-xs text-muted-foreground sm:inline"
                        >
                            Ingrese los datos. Se creará automáticamente la
                            cuenta de usuario del trabajador.
                        </span>
                        <div
                            class="ml-4 flex items-center space-x-2 rounded-md border bg-muted/60 px-3 py-1"
                        >
                            <input
                                id="inc_alta_global"
                                type="checkbox"
                                v-model="incluirAltaGlobal"
                                class="h-3.5 w-3.5 cursor-pointer rounded border-input accent-primary"
                            />
                            <label
                                for="inc_alta_global"
                                class="cursor-pointer text-xs font-semibold text-foreground select-none"
                            >
                                Incluir Alta Laboral y Perfil
                            </label>
                        </div>
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
                            {{ resultado.insertados }} trabajador{{
                                resultado.insertados !== 1 ? 'es' : ''
                            }}
                            creado{{ resultado.insertados !== 1 ? 'os' : '' }}
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
                    <div id="grid-scroll" class="flex-1 overflow-auto">
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

                                    <!-- PERSONA -->
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

                                    <!-- ALTA LABORAL (OPCIONAL) -->
                                    <template v-if="incluirAltaGlobal">
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 220px"
                                        >
                                            I.E. Destino
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 120px"
                                        >
                                            Cód. AIRSP
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 130px"
                                        >
                                            Condición
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 130px"
                                        >
                                            Contrato
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 120px"
                                        >
                                            Rol
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 130px"
                                        >
                                            Situación
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 130px"
                                        >
                                            Área
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 130px"
                                        >
                                            Cargo
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 135px"
                                        >
                                            Fecha Inicio
                                            <span class="text-destructive"
                                                >*</span
                                            >
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 135px"
                                        >
                                            Fecha Fin
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 160px"
                                        >
                                            Observación
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 140px"
                                        >
                                            Perfil IE
                                        </th>
                                        <th
                                            class="border-r border-b bg-primary/5 px-2 py-2 text-left font-semibold text-primary"
                                            style="min-width: 160px"
                                        >
                                            Local Marcación
                                        </th>
                                    </template>

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
                                        <!-- Dropdown -->
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

                                    <!-- Fecha Nacimiento -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaNac"
                                            class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                        />
                                    </td>

                                    <!-- ALTA LABORAL COLUMNS -->
                                    <template v-if="incluirAltaGlobal">
                                        <!-- Institución Educativa (typeahead) -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-ie-drop
                                        >
                                            <div class="relative">
                                                <input
                                                    type="text"
                                                    :value="fila._ieNombre"
                                                    @input="
                                                        onIeInput(
                                                            fila,
                                                            (
                                                                $event.target as HTMLInputElement
                                                            ).value,
                                                        )
                                                    "
                                                    @focus="
                                                        ieDropOpen = fila._id
                                                    "
                                                    placeholder="Buscar I.E..."
                                                    class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/60"
                                                    :class="
                                                        fila._errors
                                                            .institucionEducativa_id
                                                            ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                            : ''
                                                    "
                                                />
                                                <Loader2
                                                    v-if="ieLoading[fila._id]"
                                                    class="absolute top-1.5 right-2 h-3.5 w-3.5 animate-spin text-muted-foreground"
                                                />
                                                <div
                                                    v-if="
                                                        ieDropOpen ===
                                                            fila._id &&
                                                        (ieOptions[fila._id]
                                                            ?.length ?? 0) > 0
                                                    "
                                                    class="absolute top-full left-0 z-40 mt-0.5 w-80 rounded-md border bg-background shadow-lg"
                                                >
                                                    <div
                                                        v-for="ie in ieOptions[
                                                            fila._id
                                                        ]"
                                                        :key="ie.id"
                                                        @mousedown.prevent="
                                                            selectIe(fila, ie)
                                                        "
                                                        class="cursor-pointer px-3 py-2 text-left hover:bg-accent hover:text-accent-foreground"
                                                    >
                                                        <div
                                                            class="truncate text-xs font-semibold"
                                                        >
                                                            {{ ie.nombre }}
                                                        </div>
                                                        <div
                                                            class="text-[10px] text-muted-foreground"
                                                        >
                                                            ID: {{ ie.id }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    v-if="
                                                        fila.institucionEducativa_id
                                                    "
                                                    class="pointer-events-none absolute inset-0 flex items-center gap-1 bg-primary/5 px-2.5 text-xs"
                                                >
                                                    <Check
                                                        class="h-3 w-3 shrink-0 text-emerald-500"
                                                    />
                                                    <span
                                                        class="truncate font-semibold text-primary"
                                                        >{{
                                                            fila._ieNombre
                                                        }}</span
                                                    >
                                                    <button
                                                        @mousedown.prevent="
                                                            fila.institucionEducativa_id =
                                                                null;
                                                            fila._ieNombre = '';
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

                                        <!-- AIRSP -->
                                        <td
                                            class="border-r border-b bg-primary/5 p-0"
                                        >
                                            <input
                                                type="text"
                                                v-model="fila.codigoAirsp"
                                                placeholder="Cód. AIRSP"
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                            />
                                        </td>

                                        <!-- Condición -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'condicionLaboral_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                                :class="
                                                    fila._errors
                                                        .condicionLaboral_id
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : 'hover:bg-muted/40'
                                                "
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.condicionLaboral_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.condiciones,
                                                            fila.condicionLaboral_id,
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
                                                        'condicionLaboral_id',
                                                    )
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
                                                        :id="`ds_${dropKey(fila._id, 'condicionLaboral_id')}`"
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
                                                            cats.condiciones,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'condicionLaboral_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.condicionLaboral_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.condicionLaboral_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Tipo Contrato -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'tipoContrato_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                                :class="
                                                    fila._errors.tipoContrato_id
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : 'hover:bg-muted/40'
                                                "
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.tipoContrato_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.contratos,
                                                            fila.tipoContrato_id,
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
                                                        'tipoContrato_id',
                                                    )
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
                                                        :id="`ds_${dropKey(fila._id, 'tipoContrato_id')}`"
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
                                                            cats.contratos,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'tipoContrato_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.tipoContrato_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.tipoContrato_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Rol -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'rolTrabajador_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40"
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.rolTrabajador_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.roles,
                                                            fila.rolTrabajador_id,
                                                        ) || 'Opcional...'
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
                                                        'rolTrabajador_id',
                                                    )
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
                                                        :id="`ds_${dropKey(fila._id, 'rolTrabajador_id')}`"
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
                                                            cats.roles,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'rolTrabajador_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.rolTrabajador_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.rolTrabajador_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Situación -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'situacionLaboral_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                                :class="
                                                    fila._errors
                                                        .situacionLaboral_id
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : 'hover:bg-muted/40'
                                                "
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.situacionLaboral_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.situaciones,
                                                            fila.situacionLaboral_id,
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
                                                        'situacionLaboral_id',
                                                    )
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
                                                        :id="`ds_${dropKey(fila._id, 'situacionLaboral_id')}`"
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
                                                            cats.situaciones,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'situacionLaboral_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.situacionLaboral_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.situacionLaboral_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Área -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'area_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                                :class="
                                                    fila._errors.area_id
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : 'hover:bg-muted/40'
                                                "
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.area_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.areas,
                                                            fila.area_id,
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
                                                    dropKey(fila._id, 'area_id')
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
                                                        :id="`ds_${dropKey(fila._id, 'area_id')}`"
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
                                                            cats.areas,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'area_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.area_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.area_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Cargo -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'cargo_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                                :class="
                                                    fila._errors.cargo_id
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : 'hover:bg-muted/40'
                                                "
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.cargo_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.cargos,
                                                            fila.cargo_id,
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
                                                        'cargo_id',
                                                    )
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
                                                        :id="`ds_${dropKey(fila._id, 'cargo_id')}`"
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
                                                            cats.cargos,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'cargo_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.cargo_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.cargo_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Fecha Inicio -->
                                        <td
                                            class="border-r border-b bg-primary/5 p-0"
                                        >
                                            <input
                                                type="date"
                                                v-model="fila.fechaInicio"
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs font-medium outline-none"
                                                :class="
                                                    fila._errors.fechaInicio
                                                        ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                        : ''
                                                "
                                            />
                                        </td>

                                        <!-- Fecha Fin -->
                                        <td
                                            class="border-r border-b bg-primary/5 p-0"
                                        >
                                            <input
                                                type="date"
                                                v-model="fila.fechaFin"
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs font-medium outline-none"
                                            />
                                        </td>

                                        <!-- Observación -->
                                        <td
                                            class="border-r border-b bg-primary/5 p-0"
                                        >
                                            <input
                                                type="text"
                                                v-model="fila.observacion"
                                                placeholder="Observación"
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none"
                                            />
                                        </td>

                                        <!-- Perfil IE -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'perfil_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40"
                                            >
                                                <span
                                                    class="truncate"
                                                    :class="
                                                        !fila.perfil_id
                                                            ? 'text-muted-foreground/50'
                                                            : ''
                                                    "
                                                >
                                                    {{
                                                        catNombre(
                                                            cats.perfiles,
                                                            fila.perfil_id,
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
                                                        'perfil_id',
                                                    )
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
                                                        :id="`ds_${dropKey(fila._id, 'perfil_id')}`"
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
                                                            cats.perfiles,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'perfil_id',
                                                                item,
                                                            )
                                                        "
                                                        class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                        :class="
                                                            fila.perfil_id ===
                                                            item.id
                                                                ? 'bg-primary/10 font-medium text-primary'
                                                                : ''
                                                        "
                                                    >
                                                        <Check
                                                            v-if="
                                                                fila.perfil_id ===
                                                                item.id
                                                            "
                                                            class="h-3 w-3 shrink-0"
                                                        />
                                                        <span
                                                            v-else
                                                            class="w-3 shrink-0"
                                                        />
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Local de Marcación (depende de la IE de la fila) -->
                                        <td
                                            class="relative border-r border-b bg-primary/5 p-0"
                                            data-dropdown
                                        >
                                            <button
                                                type="button"
                                                :disabled="
                                                    !fila.institucionEducativa_id
                                                "
                                                @click.stop="
                                                    toggleDropdown(
                                                        fila._id,
                                                        'localInstEduc_id',
                                                    )
                                                "
                                                class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40 disabled:cursor-not-allowed disabled:opacity-50"
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
                                                        !fila.institucionEducativa_id
                                                            ? 'Seleccione IE'
                                                            : fila._localesLoading
                                                              ? 'Cargando...'
                                                              : catNombre(
                                                                    fila._locales,
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
                                                        'localInstEduc_id',
                                                    )
                                                "
                                                class="absolute top-full right-0 z-40 mt-0.5 w-56 rounded-md border bg-background shadow-lg"
                                                data-dropdown
                                            >
                                                <div
                                                    class="flex items-center gap-1.5 border-b px-2.5 py-1.5"
                                                >
                                                    <Search
                                                        class="h-3.5 w-3.5 shrink-0 text-muted-foreground"
                                                    />
                                                    <input
                                                        :id="`ds_${dropKey(fila._id, 'localInstEduc_id')}`"
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
                                                            fila._locales,
                                                        )"
                                                        :key="item.id"
                                                        @mousedown.prevent="
                                                            selectCat(
                                                                fila,
                                                                'localInstEduc_id',
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
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                item.nombre
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        v-if="
                                                            fila.institucionEducativa_id &&
                                                            !fila._locales
                                                                .length &&
                                                            !fila._localesLoading
                                                        "
                                                        class="py-3 text-center text-xs text-muted-foreground"
                                                    >
                                                        IE sin locales
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </template>

                                    <!-- Eliminar Fila -->
                                    <td
                                        class="border-b bg-muted/5 px-1 py-1 text-center"
                                    >
                                        <button
                                            type="button"
                                            @click="eliminarFila(fila._id)"
                                            class="animate-pulse-hover rounded p-1 text-muted-foreground/75 hover:bg-destructive/10 hover:text-destructive"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </button>
                                    </td>
                                </tr>

                                <tr v-if="!filas.length">
                                    <td
                                        :colspan="incluirAltaGlobal ? 23 : 10"
                                        class="py-8 text-center text-sm text-muted-foreground"
                                    >
                                        No hay filas añadidas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer / Panel de control -->
                    <div
                        class="flex shrink-0 items-center justify-between border-t bg-muted/10 px-6 py-4"
                    >
                        <div class="flex items-center gap-2">
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                @click="agregarFila"
                            >
                                <Plus class="mr-1.5 h-3.5 w-3.5" /> Añadir Fila
                            </Button>
                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                @click="agregarFilas(5)"
                            >
                                +5 Filas
                            </Button>
                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                @click="agregarFilas(10)"
                            >
                                +10 Filas
                            </Button>
                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                class="text-muted-foreground hover:text-foreground"
                                @click="resetGrid"
                            >
                                Limpiar Todo
                            </Button>
                        </div>

                        <div class="flex items-center gap-2">
                            <Button
                                type="button"
                                variant="outline"
                                @click="emit('close')"
                                :disabled="enviando"
                            >
                                Cancelar
                            </Button>
                            <Button
                                type="button"
                                @click="enviar"
                                :disabled="enviando || !filasConDatos.length"
                            >
                                <Loader2
                                    v-if="enviando"
                                    class="mr-2 h-4 w-4 animate-spin"
                                />
                                <CheckCircle2 v-else class="mr-2 h-4 w-4" />
                                Guardar Trabajadores
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
