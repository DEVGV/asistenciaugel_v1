<script setup lang="ts">
/**
 * HorarioMasivoGrid — Grilla masiva para crear y editar horarios de cursos.
 *
 * Columnas:
 *   # | Estado | Curso* | Día* | H.Inicio* | H.Fin | Docente | T/S | F.Inicio | F.Fin | ✕
 *
 * Casuísticas cubiertas:
 *  - Crear fila nueva (id = null)
 *  - Editar fila existente (id = número, pre-cargada con datos actuales)
 *  - Duplicado curso×día×sección → se sobreescribe (backend lo detecta y actualiza)
 *  - Asignar / cambiar / quitar docente en la misma fila
 *  - Titular / Suplente
 *  - Fechas de vigencia de la carga del docente
 *  - Validación visual por celda antes de enviar
 *  - Resultado por fila en el feedback post-envío
 *  - Se pre-carga con todos los horarios actuales de la sección para edición inline
 */
import { ref, reactive, computed, onMounted, nextTick, watch } from 'vue';
import {
    Plus,
    Trash2,
    Loader2,
    CheckCircle2,
    AlertCircle,
    X,
    ChevronDown,
    Search,
    Check,
    RefreshCw,
    Upload,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import type { HorarioCurso } from '@/types/models/horario';
import type { CursoIE } from '@/types/models/institucion-educativa';

// ─── Props / Emits ────────────────────────────────────────────────────────────
const props = defineProps<{
    seccionId: number;
    anio: number;
    cursos: CursoIE[];
    ieId?: number | null;
    /** Horarios actuales de la sección — se usan para pre-poblar la grilla */
    horariosExistentes?: HorarioCurso[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success'): void;
}>();

// ─── Constantes ───────────────────────────────────────────────────────────────
const DAYS = [
    { nro: 1, label: 'Lunes', char: 'L' },
    { nro: 2, label: 'Martes', char: 'M' },
    { nro: 3, label: 'Miércoles', char: 'X' },
    { nro: 4, label: 'Jueves', char: 'J' },
    { nro: 5, label: 'Viernes', char: 'V' },
    { nro: 6, label: 'Sábado', char: 'S' },
    { nro: 7, label: 'Domingo', char: 'D' },
];

function csrfToken(): string {
    return (
        (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)
            ?.content ?? ''
    );
}

// ─── Tipos ────────────────────────────────────────────────────────────────────
interface FilaHorario {
    _id: number; // identificador local de fila
    id: number | null; // id del HorarioCurso (null = nuevo)
    carga_id: number | null; // id de CargaHoraria existente
    curso_id: number | null;
    _cursoNombre: string;
    nroDia: number;
    turno_id: number | null;
    horaInicio: string;
    horaFin: string;
    trabajador_id: number | null;
    _trabajadorNombre: string;
    altaTrabajador_id: number | null;
    titularSuplencia: 'T' | 'S';
    fechaInicioDocente: string;
    fechaFinDocente: string;
    _isExisting: boolean; // true si vino de horariosExistentes
    _errors: Record<string, string>;
}

const TURNOS_MASIVO = [
    { id: 1, label: 'M', title: 'Mañana' },
    { id: 2, label: 'T', title: 'Tarde' },
    { id: 3, label: 'N', title: 'Noche' },
];

// ─── Catálogo cursos (dropdown inline) ───────────────────────────────────────
const activeDropdown = ref<string | null>(null);
const dropdownSearch = ref('');

function toggleCursoDropdown(filaId: number) {
    const key = `curso_${filaId}`;
    if (activeDropdown.value === key) {
        activeDropdown.value = null;
    } else {
        activeDropdown.value = key;
        dropdownSearch.value = '';
        nextTick(() => document.getElementById(`dsc_${filaId}`)?.focus());
    }
}

function closeCursoDropdown() {
    activeDropdown.value = null;
    dropdownSearch.value = '';
}

function filteredCursos() {
    const q = dropdownSearch.value.toLowerCase().trim();
    if (!q) return props.cursos.slice(0, 100);
    return props.cursos
        .filter((c) => c.nombre.toLowerCase().includes(q))
        .slice(0, 100);
}

function selectCurso(fila: FilaHorario, curso: CursoIE) {
    fila.curso_id = curso.id;
    fila._cursoNombre = curso.nombre;
    fila._errors['curso_id'] = '';
    closeCursoDropdown();
}

// ─── Dropdown día ─────────────────────────────────────────────────────────────
function toggleDiaDropdown(filaId: number) {
    const key = `dia_${filaId}`;
    activeDropdown.value = activeDropdown.value === key ? null : key;
}

function selectDia(fila: FilaHorario, day: (typeof DAYS)[0]) {
    fila.nroDia = day.nro;
    fila._errors['nroDia'] = '';
    activeDropdown.value = null;
}

// ─── Typeahead docente ────────────────────────────────────────────────────────
interface TrabItem {
    id: number;
    nombre: string;
    codigo: string;
    docId: string;
}
const trabOptions = reactive<Record<number, TrabItem[]>>({});
const trabLoading = reactive<Record<number, boolean>>({});
const trabDropOpen = ref<number | null>(null);
let trabTimers: Record<number, ReturnType<typeof setTimeout>> = {};

function onTrabInput(fila: FilaHorario, val: string) {
    fila.trabajador_id = null;
    fila._trabajadorNombre = val;
    fila.altaTrabajador_id = null;
    clearTimeout(trabTimers[fila._id]);
    if (!val || val.length < 1) {
        trabOptions[fila._id] = [];
        trabDropOpen.value = null;
        return;
    }
    trabTimers[fila._id] = setTimeout(() => buscarTrabajador(fila, val), 300);
}

async function buscarTrabajador(fila: FilaHorario, q: string) {
    trabLoading[fila._id] = true;
    trabDropOpen.value = fila._id;
    try {
        const params = new URLSearchParams({ search: q });
        if (props.ieId) params.set('ie_id', String(props.ieId));
        const res = await fetch(`/api/trabajadores/search?${params}`);
        const json = await res.json();
        const lista: any[] = Array.isArray(json) ? json : (json.data ?? []);
        trabOptions[fila._id] = lista.map((t: any) => {
            const p = t.persona ?? {};
            const apellidos = [p.paterno, p.materno].filter(Boolean).join(' ');
            const nombre = [apellidos, p.nombre].filter(Boolean).join(', ');
            return {
                id: t.id,
                nombre: nombre || `Trabajador #${t.id}`,
                codigo: t.codigo ?? '',
                docId: p.docIdentidad ?? '',
            };
        });
    } catch {
        trabOptions[fila._id] = [];
    } finally {
        trabLoading[fila._id] = false;
    }
}

function selectTrabajador(fila: FilaHorario, trab: TrabItem) {
    fila.trabajador_id = trab.id;
    fila._trabajadorNombre = trab.nombre;
    trabDropOpen.value = null;
    trabOptions[fila._id] = [];
}

function clearTrabajador(fila: FilaHorario) {
    fila.trabajador_id = null;
    fila._trabajadorNombre = '';
    fila.altaTrabajador_id = null;
}

// ─── Filas ────────────────────────────────────────────────────────────────────
let nextId = 0;

function filaFromExistente(h: HorarioCurso): FilaHorario {
    const carga = (h as any).cargas?.[0] ?? null;
    const w = carga?.trabajador ?? null;
    let nombre = '';
    if (w?.persona) {
        const ap = [w.persona.paterno, w.persona.materno]
            .filter(Boolean)
            .join(' ');
        nombre = [ap, w.persona.nombre].filter(Boolean).join(', ');
    }
    return {
        _id: nextId++,
        id: h.id,
        carga_id: carga?.id ?? null,
        curso_id: h.curso_id,
        _cursoNombre: h.curso?.nombre ?? '',
        nroDia: h.nroDia,
        turno_id: h.turno_id ?? null,
        horaInicio: h.horaInicio?.substring(0, 5) ?? '08:00',
        horaFin: h.horaFin?.substring(0, 5) ?? '09:30',
        trabajador_id: w?.id ?? null,
        _trabajadorNombre: nombre,
        altaTrabajador_id: carga?.altaTrabajador_id ?? null,
        titularSuplencia: carga?.titularSuplencia ?? 'T',
        fechaInicioDocente: carga?.fechaInicio ?? '',
        fechaFinDocente: carga?.fechaFin ?? '',
        _isExisting: true,
        _errors: {},
    };
}

function nuevaFila(): FilaHorario {
    return {
        _id: nextId++,
        id: null,
        carga_id: null,
        curso_id: null,
        _cursoNombre: '',
        nroDia: 1,
        turno_id: null,
        horaInicio: '08:00',
        horaFin: '09:30',
        trabajador_id: null,
        _trabajadorNombre: '',
        altaTrabajador_id: null,
        titularSuplencia: 'T',
        fechaInicioDocente: '',
        fechaFinDocente: '',
        _isExisting: false,
        _errors: {},
    };
}

const filas = ref<FilaHorario[]>([]);

function inicializarFilas() {
    const existentes = (props.horariosExistentes ?? []).map(filaFromExistente);
    // Agregar filas vacías al final para poder añadir nuevos
    const vacias = Array.from(
        { length: Math.max(5, 10 - existentes.length) },
        () => nuevaFila(),
    );
    filas.value = [...existentes, ...vacias];
}

onMounted(() => inicializarFilas());

// Recargar si cambian los horarios existentes
watch(
    () => props.horariosExistentes,
    () => inicializarFilas(),
    { deep: true },
);

function agregarFila() {
    filas.value.push(nuevaFila());
    nextTick(() => {
        const el = document.getElementById('horario-masivo-scroll');
        if (el) el.scrollTop = el.scrollHeight;
    });
}

function agregarFilas(n: number) {
    for (let i = 0; i < n; i++) filas.value.push(nuevaFila());
}

function eliminarFila(id: number) {
    filas.value = filas.value.filter((f) => f._id !== id);
    if (!filas.value.length) filas.value.push(nuevaFila());
}

function limpiarFilasVacias() {
    const llenas = filas.value.filter(
        (f) => f.curso_id !== null || f._isExisting,
    );
    filas.value = llenas.length ? llenas : [nuevaFila()];
}

const filasConDatos = computed(() =>
    filas.value.filter((f) => f.curso_id !== null),
);

const nuevasFilas = computed(() =>
    filasConDatos.value.filter((f) => !f._isExisting),
);
const editandoFilas = computed(() =>
    filasConDatos.value.filter((f) => f._isExisting),
);

// ─── Cerrar dropdowns al click fuera ─────────────────────────────────────────
function onGridClick(e: MouseEvent) {
    const t = e.target as HTMLElement;
    if (!t.closest('[data-dropdown]')) closeCursoDropdown();
    if (!t.closest('[data-trab-drop]')) trabDropOpen.value = null;
}

// ─── Validación ───────────────────────────────────────────────────────────────
function validar(): boolean {
    let ok = true;
    filas.value.forEach((fila) => {
        fila._errors = {};
        if (!fila.curso_id && !fila._isExisting) return; // fila vacía, ignorar
        if (!fila.curso_id) {
            fila._errors['curso_id'] = 'Requerido';
            ok = false;
        }
        if (!fila.horaInicio) {
            fila._errors['horaInicio'] = 'Requerido';
            ok = false;
        }
        if (!fila.horaFin) {
            fila._errors['horaFin'] = 'Requerido';
            ok = false;
        }
        if (
            fila.horaInicio &&
            fila.horaFin &&
            fila.horaFin <= fila.horaInicio
        ) {
            fila._errors['horaFin'] = 'Debe ser > inicio';
            ok = false;
        }
    });
    return ok;
}

// ─── Envío ────────────────────────────────────────────────────────────────────
const enviando = ref(false);
const resultado = ref<{
    creados: number;
    actualizados: number;
    docentes_asignados: number;
    docentes_actualizados: number;
    errores: Record<number, string>;
} | null>(null);
const errorGeneral = ref('');
// mapa rowNum → fila para feedback post-envío
const filaRowMap = reactive<Record<number, number>>({});

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
    Object.keys(filaRowMap).forEach((k) => delete (filaRowMap as any)[k]);

    const payload = filasFiltradas.map((f, i) => {
        const rowNum = i + 1;
        const localIdx = filas.value.findIndex((x) => x._id === f._id);
        filaRowMap[rowNum] = localIdx;

        return {
            id: f.id,
            carga_id: f.carga_id,
            seccion_id: props.seccionId,
            anio: props.anio,
            curso_id: f.curso_id,
            nroDia: f.nroDia,
            turno_id: f.turno_id,
            diaSemana: DAYS.find((d) => d.nro === f.nroDia)?.char ?? 'L',
            horaInicio: f.horaInicio,
            horaFin: f.horaFin,
            trabajador_id: f.trabajador_id,
            altaTrabajador_id: f.altaTrabajador_id,
            titularSuplencia: f.titularSuplencia,
            fechaInicioDocente: f.fechaInicioDocente || null,
            fechaFinDocente: f.fechaFinDocente || null,
        };
    });

    try {
        const res = await fetch('/horarios-masivos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                Accept: 'application/json',
            },
            body: JSON.stringify({ filas: payload }),
        });
        const data = await res.json();

        if (!res.ok) {
            console.error('Horario masivo error:', JSON.stringify(data, null, 2));
            errorGeneral.value = data.message ?? 'Error del servidor.';
            // Marcar errores de validación laravel (campo filas.N.campo)
            if (data.errors) {
                Object.entries(data.errors as Record<string, string[]>).forEach(
                    ([key, msgs]) => {
                        const match = key.match(/^filas\.(\d+)\./);
                        if (match) {
                            const idx = parseInt(match[1]);
                            const localIdx = filaRowMap[idx + 1];
                            if (localIdx !== undefined) {
                                const campo = key.split('.').pop() ?? 'error';
                                filas.value[localIdx]._errors[campo] = msgs[0];
                            }
                        }
                    },
                );
            }
            // Marcar errores por fila del servicio (cuando ningún registro se procesó)
            if (data.errores) {
                Object.entries(data.errores as Record<number, string>).forEach(
                    ([rowNum, msg]) => {
                        const localIdx = filaRowMap[Number(rowNum)];
                        if (localIdx !== undefined) {
                            filas.value[localIdx]._errors['_row'] = msg as string;
                        }
                    },
                );
            }
        } else {
            resultado.value = data;
            // Marcar errores de fila en el grid
            if (data.errores) {
                Object.entries(data.errores as Record<number, string>).forEach(
                    ([rowNum, msg]) => {
                        const localIdx = filaRowMap[Number(rowNum)];
                        if (localIdx !== undefined) {
                            filas.value[localIdx]._errors['_row'] = msg;
                        }
                    },
                );
            }
            emit('success');
        }
    } catch {
        errorGeneral.value = 'Error de conexión al servidor.';
    } finally {
        enviando.value = false;
    }
}

function resetGrid() {
    inicializarFilas();
    resultado.value = null;
    errorGeneral.value = '';
}
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60"
            @mousedown.self="emit('close')"
        >
            <div
                class="flex h-screen w-screen flex-col bg-background shadow-2xl"
                style="max-height: 100dvh"
                @click="onGridClick"
            >
                <!-- ── Header ─────────────────────────────────────────────────── -->
                <div
                    class="flex shrink-0 items-center justify-between border-b px-6 py-3"
                >
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-base font-semibold">
                            Carga Masiva de Horarios
                        </h2>
                        <span
                            class="rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground"
                        >
                            Sección seleccionada · {{ props.anio }}
                        </span>
                        <span
                            class="hidden text-xs text-muted-foreground sm:block"
                        >
                            Filas verdes = existentes · Blancas = nuevas. Edita
                            y presiona <strong>Guardar todo</strong>.
                        </span>
                        <div class="flex items-center gap-2 text-xs">
                            <span
                                v-if="editandoFilas.length"
                                class="rounded-full bg-emerald-100 px-2 py-0.5 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400"
                            >
                                {{ editandoFilas.length }} a editar
                            </span>
                            <span
                                v-if="nuevasFilas.length"
                                class="rounded-full bg-primary/10 px-2 py-0.5 font-medium text-primary"
                            >
                                {{ nuevasFilas.length }} nueva{{
                                    nuevasFilas.length !== 1 ? 's' : ''
                                }}
                            </span>
                        </div>
                    </div>
                    <button
                        @click="emit('close')"
                        class="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div class="flex flex-1 flex-col overflow-hidden">
                    <!-- Resultado -->
                    <div
                        v-if="resultado"
                        class="shrink-0 space-y-1 border-b px-5 py-3"
                    >
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-emerald-700 dark:text-emerald-400"
                        >
                            <CheckCircle2 class="h-4 w-4 shrink-0" />
                            Creados: {{ resultado.creados }} · Actualizados:
                            {{ resultado.actualizados }} · Docentes asignados:
                            {{ resultado.docentes_asignados }} · actualizados:
                            {{ resultado.docentes_actualizados }}
                        </div>
                        <div
                            v-if="Object.keys(resultado.errores).length"
                            class="text-xs text-amber-700 dark:text-amber-400"
                        >
                            <span class="font-semibold"
                                >Filas con errores:</span
                            >
                            <span
                                v-for="(msg, row) in resultado.errores"
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
                    <div
                        id="horario-masivo-scroll"
                        class="flex-1 overflow-auto"
                    >
                        <table
                            class="w-max min-w-full border-separate border-spacing-0 text-xs"
                        >
                            <thead
                                class="sticky top-0 z-20 bg-muted/90 backdrop-blur-sm"
                            >
                                <tr>
                                    <th
                                        class="w-7 border-r border-b px-1 py-2 text-center text-muted-foreground"
                                    >
                                        #
                                    </th>
                                    <th
                                        class="w-5 border-r border-b px-1 py-2"
                                    ></th>
                                    <!-- Curso -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 200px"
                                    >
                                        Curso
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Día -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 110px"
                                    >
                                        Día
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Turno -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 90px"
                                    >
                                        Turno
                                    </th>
                                    <!-- Hora Inicio -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 100px"
                                    >
                                        H. Inicio
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Hora Fin -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 100px"
                                    >
                                        H. Fin
                                        <span class="text-destructive">*</span>
                                    </th>
                                    <!-- Docente -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 220px"
                                    >
                                        Docente
                                    </th>
                                    <!-- T/S -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold"
                                        style="min-width: 80px"
                                    >
                                        T/S
                                    </th>
                                    <!-- Fecha inicio docente -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 120px"
                                    >
                                        F. Inicio Doc.
                                    </th>
                                    <!-- Fecha fin docente -->
                                    <th
                                        class="border-r border-b px-2 py-2 text-left font-semibold whitespace-nowrap"
                                        style="min-width: 120px"
                                    >
                                        F. Fin Doc.
                                    </th>
                                    <th class="w-8 border-b px-1 py-2"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(fila, idx) in filas"
                                    :key="fila._id"
                                    class="group transition-colors"
                                    :class="{
                                        'bg-emerald-50/40 dark:bg-emerald-950/10':
                                            fila._isExisting &&
                                            !Object.keys(fila._errors).length,
                                        'bg-destructive/5': Object.keys(
                                            fila._errors,
                                        ).length,
                                        'hover:bg-muted/30':
                                            !fila._isExisting &&
                                            !Object.keys(fila._errors).length,
                                        'hover:bg-emerald-50/70 dark:hover:bg-emerald-950/20':
                                            fila._isExisting &&
                                            !Object.keys(fila._errors).length,
                                    }"
                                >
                                    <!-- N° -->
                                    <td
                                        class="border-r border-b px-1 py-1 text-center text-muted-foreground select-none"
                                    >
                                        {{ idx + 1 }}
                                    </td>
                                    <!-- Indicador nuevo/existente -->
                                    <td
                                        class="border-r border-b px-1 py-1 text-center"
                                    >
                                        <span
                                            v-if="fila._isExisting"
                                            class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400"
                                            title="Horario existente"
                                            >✎</span
                                        >
                                        <span
                                            v-else
                                            class="text-[10px] font-bold text-primary/50"
                                            title="Nuevo horario"
                                            >+</span
                                        >
                                    </td>

                                    <!-- ── CURSO (dropdown) ── -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleCursoDropdown(fila._id)
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none"
                                            :class="
                                                fila._errors['curso_id']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/40'
                                            "
                                        >
                                            <span
                                                class="truncate"
                                                :class="
                                                    !fila.curso_id
                                                        ? 'text-muted-foreground/50'
                                                        : ''
                                                "
                                            >
                                                {{
                                                    fila._cursoNombre ||
                                                    'Seleccionar...'
                                                }}
                                            </span>
                                            <ChevronDown
                                                class="h-3 w-3 shrink-0 text-muted-foreground/60"
                                            />
                                        </button>
                                        <!-- Dropdown cursos -->
                                        <div
                                            v-if="
                                                activeDropdown ===
                                                `curso_${fila._id}`
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
                                                    :id="`dsc_${fila._id}`"
                                                    v-model="dropdownSearch"
                                                    type="text"
                                                    placeholder="Buscar curso..."
                                                    class="flex-1 bg-transparent text-xs outline-none"
                                                    @click.stop
                                                />
                                            </div>
                                            <div
                                                class="max-h-48 overflow-y-auto py-1"
                                            >
                                                <div
                                                    v-for="c in filteredCursos()"
                                                    :key="c.id"
                                                    @mousedown.prevent="
                                                        selectCurso(fila, c)
                                                    "
                                                    class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                    :class="
                                                        fila.curso_id === c.id
                                                            ? 'bg-primary/10 font-medium text-primary'
                                                            : ''
                                                    "
                                                >
                                                    <Check
                                                        v-if="
                                                            fila.curso_id ===
                                                            c.id
                                                        "
                                                        class="h-3 w-3 shrink-0"
                                                    />
                                                    <span
                                                        v-else
                                                        class="w-3 shrink-0"
                                                    />
                                                    <span class="truncate">{{
                                                        c.nombre
                                                    }}</span>
                                                </div>
                                                <div
                                                    v-if="
                                                        !filteredCursos().length
                                                    "
                                                    class="py-3 text-center text-xs text-muted-foreground"
                                                >
                                                    Sin resultados
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ── DÍA (dropdown) ── -->
                                    <td
                                        class="relative border-r border-b p-0"
                                        data-dropdown
                                    >
                                        <button
                                            type="button"
                                            @click.stop="
                                                toggleDiaDropdown(fila._id)
                                            "
                                            class="flex w-full items-center justify-between gap-1 px-2.5 py-1.5 text-left text-xs outline-none hover:bg-muted/40"
                                        >
                                            <span>{{
                                                DAYS.find(
                                                    (d) =>
                                                        d.nro === fila.nroDia,
                                                )?.label ?? '—'
                                            }}</span>
                                            <ChevronDown
                                                class="h-3 w-3 shrink-0 text-muted-foreground/60"
                                            />
                                        </button>
                                        <div
                                            v-if="
                                                activeDropdown ===
                                                `dia_${fila._id}`
                                            "
                                            class="absolute top-full left-0 z-40 mt-0.5 w-36 rounded-md border bg-background py-1 shadow-lg"
                                            data-dropdown
                                        >
                                            <div
                                                v-for="day in DAYS"
                                                :key="day.nro"
                                                @mousedown.prevent="
                                                    selectDia(fila, day)
                                                "
                                                class="flex cursor-pointer items-center gap-2 px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground"
                                                :class="
                                                    fila.nroDia === day.nro
                                                        ? 'bg-primary/10 font-medium text-primary'
                                                        : ''
                                                "
                                            >
                                                <Check
                                                    v-if="
                                                        fila.nroDia === day.nro
                                                    "
                                                    class="h-3 w-3 shrink-0"
                                                />
                                                <span
                                                    v-else
                                                    class="w-3 shrink-0"
                                                />
                                                {{ day.label }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ── TURNO ── -->
                                    <td class="border-r border-b px-1 py-1">
                                        <div class="flex gap-0.5">
                                            <button
                                                v-for="t in TURNOS_MASIVO"
                                                :key="t.id"
                                                type="button"
                                                :title="t.title"
                                                @click="fila.turno_id = fila.turno_id === t.id ? null : t.id"
                                                class="h-6 w-6 rounded text-xs font-bold transition-colors"
                                                :class="fila.turno_id === t.id
                                                    ? 'bg-primary text-primary-foreground'
                                                    : 'bg-muted text-muted-foreground hover:bg-muted/70'"
                                            >{{ t.label }}</button>
                                        </div>
                                    </td>

                                    <!-- ── HORA INICIO ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="time"
                                            v-model="fila.horaInicio"
                                            @change="
                                                fila._errors['horaInicio'] = ''
                                            "
                                            class="w-full bg-transparent px-2 py-1.5 font-mono text-xs outline-none"
                                            :class="
                                                fila._errors['horaInicio']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- ── HORA FIN ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="time"
                                            v-model="fila.horaFin"
                                            @change="
                                                fila._errors['horaFin'] = ''
                                            "
                                            :min="fila.horaInicio || undefined"
                                            class="w-full bg-transparent px-2 py-1.5 font-mono text-xs outline-none"
                                            :class="
                                                fila._errors['horaFin']
                                                    ? 'bg-destructive/5 ring-1 ring-destructive ring-inset'
                                                    : 'hover:bg-muted/30'
                                            "
                                        />
                                    </td>

                                    <!-- ── DOCENTE (typeahead) ── -->
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
                                                placeholder="Buscar docente..."
                                                class="w-full bg-transparent px-2.5 py-1.5 text-xs outline-none placeholder:text-muted-foreground/60 hover:bg-muted/20"
                                            />
                                            <Loader2
                                                v-if="trabLoading[fila._id]"
                                                class="absolute top-1.5 right-2 h-3.5 w-3.5 animate-spin text-muted-foreground"
                                            />
                                            <!-- Dropdown trabajadores -->
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
                                                        class="truncate text-xs font-medium"
                                                    >
                                                        {{ trab.nombre }}
                                                    </div>
                                                    <div
                                                        class="text-[11px] text-muted-foreground"
                                                    >
                                                        {{ trab.codigo }} ·
                                                        {{ trab.docId }}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Chip si seleccionado -->
                                            <div
                                                v-if="fila.trabajador_id"
                                                class="pointer-events-none absolute inset-0 flex items-center gap-1 bg-background/95 px-2 text-xs"
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
                                                        clearTrabajador(fila)
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

                                    <!-- ── TITULAR / SUPLENTE ── -->
                                    <td class="border-r border-b px-2 py-1">
                                        <div class="flex items-center gap-2">
                                            <label
                                                class="flex cursor-pointer items-center gap-1 text-xs"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="
                                                        fila.titularSuplencia
                                                    "
                                                    value="T"
                                                    class="h-3 w-3"
                                                />
                                                T
                                            </label>
                                            <label
                                                class="flex cursor-pointer items-center gap-1 text-xs"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="
                                                        fila.titularSuplencia
                                                    "
                                                    value="S"
                                                    class="h-3 w-3"
                                                />
                                                S
                                            </label>
                                        </div>
                                    </td>

                                    <!-- ── FECHA INICIO DOCENTE ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaInicioDocente"
                                            class="w-full bg-transparent px-2 py-1.5 text-xs outline-none hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- ── FECHA FIN DOCENTE ── -->
                                    <td class="border-r border-b p-0">
                                        <input
                                            type="date"
                                            v-model="fila.fechaFinDocente"
                                            :min="
                                                fila.fechaInicioDocente ||
                                                undefined
                                            "
                                            class="w-full bg-transparent px-2 py-1.5 text-xs outline-none hover:bg-muted/30"
                                        />
                                    </td>

                                    <!-- ── ELIMINAR ── -->
                                    <td class="border-b px-1 py-1 text-center">
                                        <button
                                            type="button"
                                            @click="eliminarFila(fila._id)"
                                            class="rounded p-1 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100 hover:bg-destructive/10 hover:text-destructive"
                                            title="Quitar fila"
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
                        <button
                            type="button"
                            @click="resetGrid"
                            class="flex items-center gap-1.5 rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground transition-colors hover:border-indigo-400/50 hover:text-indigo-500"
                        >
                            <RefreshCw class="h-3.5 w-3.5" /> Recargar
                            existentes
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
                                >Cerrar</Button
                            >
                        </div>

                        <div class="flex items-center gap-3">
                            <div
                                class="space-x-2 text-xs text-muted-foreground"
                            >
                                <span v-if="nuevasFilas.length">
                                    <span class="font-medium text-primary">{{
                                        nuevasFilas.length
                                    }}</span>
                                    nueva{{
                                        nuevasFilas.length !== 1 ? 's' : ''
                                    }}
                                </span>
                                <span v-if="editandoFilas.length">
                                    <span
                                        class="font-medium text-emerald-600"
                                        >{{ editandoFilas.length }}</span
                                    >
                                    a editar
                                </span>
                            </div>
                            <Button
                                size="sm"
                                @click="enviar"
                                :disabled="enviando || !filasConDatos.length"
                                class="min-w-[160px] gap-2"
                            >
                                <Loader2
                                    v-if="enviando"
                                    class="h-4 w-4 animate-spin"
                                />
                                <Upload v-else class="h-4 w-4" />
                                {{
                                    enviando
                                        ? 'Guardando...'
                                        : `Guardar ${filasConDatos.length || ''} Horario${filasConDatos.length !== 1 ? 's' : ''}`
                                }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
