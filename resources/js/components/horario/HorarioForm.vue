<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import SearchSelect from '@/components/shared/SearchSelect.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import type { HorarioCurso } from '@/types/models/horario';
import type { CursoIE } from '@/types/models/institucion-educativa';
import { User } from 'lucide-vue-next';

const props = defineProps<{
    seccionId: number;
    anio: number;
    cursos: CursoIE[];
    horarioCurso?: HorarioCurso | null;
    ieId?: number | null;
}>();

const emit = defineEmits<{
    (e: 'submit', data: any): void;
    (e: 'cancel'): void;
}>();

// ── Datos del horario ──────────────────────────────────────────────────────────
const cursoId = ref<number | null>(null);
const nroDia = ref<number>(1);
const turnoId = ref<number | null>(null);
const horaInicio = ref<string>('08:00');
const horaFin = ref<string>('09:30');

const TURNOS = [
    { id: 1, label: 'Mañana' },
    { id: 2, label: 'Tarde' },
    { id: 3, label: 'Noche' },
];

const cursoError = ref<string | null>(null);
const horaError = ref<string | null>(null);
const cursoContainerRef = ref<HTMLElement | null>(null);

const DAYS = [
    { nro: 1, label: 'Lunes', char: 'L' },
    { nro: 2, label: 'Martes', char: 'M' },
    { nro: 3, label: 'Miércoles', char: 'X' },
    { nro: 4, label: 'Jueves', char: 'J' },
    { nro: 5, label: 'Viernes', char: 'V' },
    { nro: 6, label: 'Sábado', char: 'S' },
    { nro: 7, label: 'Domingo', char: 'D' },
];

// ── Datos del docente ──────────────────────────────────────────────────────────
const workersList = ref<any[]>([]);
const searchingWorkers = ref(false);
const selectedWorkerId = ref<string | null>(null);
const selectedWorker = ref<any>(null);
const titularSuplencia = ref<'T' | 'S'>('T');
const fechaInicioDocente = ref<string>('');
const fechaFinDocente = ref<string>('');

let workerSearchTimeout: any = null;

// Adaptar cursos al formato que espera SearchSelect
const cursosItems = computed(() =>
    props.cursos.map((c) => ({
        id: c.id,
        label: c.nombre,
    })),
);

const workerItems = computed(() =>
    workersList.value.map((w) => ({
        id: String(w.id),
        label: `${w.persona?.paterno ?? ''} ${w.persona?.materno ?? ''}, ${w.persona?.nombre ?? ''}`.trim(),
        sublabel: `DNI: ${w.persona?.docIdentidad ?? '—'} | Cód: ${w.codigo ?? '—'}`,
    })),
);

onMounted(() => {
    resetForm();
    triggerSearchWorkers('');
});

watch(
    () => props.horarioCurso,
    () => resetForm(),
);

watch(selectedWorkerId, (val) => {
    if (!val) {
        selectedWorker.value = null;
        return;
    }
    const w = workersList.value.find((x) => String(x.id) === String(val));
    if (w) {
        selectedWorker.value = w;
    }
});

watch(cursoId, () => {
    if (cursoId.value) {
        cursoError.value = null;
    }
});

function resetForm() {
    if (props.horarioCurso) {
        cursoId.value = props.horarioCurso.curso_id;
        nroDia.value = props.horarioCurso.nroDia;
        turnoId.value = (props.horarioCurso as any).detalles_ini?.[0]?.turno_id ?? null;
        horaInicio.value = props.horarioCurso.horaInicio.substring(0, 5);
        horaFin.value = props.horarioCurso.horaFin.substring(0, 5);

        // Pre-poblar docente si ya tiene asignación
        const cargaExistente = (props.horarioCurso as any).cargas?.[0];
        if (cargaExistente?.trabajador) {
            const w = cargaExistente.trabajador;
            workersList.value = [w];
            selectedWorkerId.value = String(w.id);
            selectedWorker.value = w;
            titularSuplencia.value = cargaExistente.titularSuplencia ?? 'T';
            fechaInicioDocente.value = cargaExistente.fechaInicio ?? '';
            fechaFinDocente.value = cargaExistente.fechaFin ?? '';
        } else {
            selectedWorkerId.value = null;
            selectedWorker.value = null;
            titularSuplencia.value = 'T';
            fechaInicioDocente.value = '';
            fechaFinDocente.value = '';
        }
    } else {
        cursoId.value = null;
        nroDia.value = 1;
        turnoId.value = null;
        horaInicio.value = '08:00';
        horaFin.value = '09:30';
        selectedWorkerId.value = null;
        selectedWorker.value = null;
        titularSuplencia.value = 'T';
        fechaInicioDocente.value = '';
        fechaFinDocente.value = '';
    }
}

function getDiaSemanaChar(dayNum: number): string {
    return DAYS.find((d) => d.nro === dayNum)?.char ?? 'L';
}

async function triggerSearchWorkers(query = '') {
    searchingWorkers.value = true;
    try {
        const params = new URLSearchParams();
        if (query.trim()) params.set('search', query);
        if (props.ieId) params.set('ie_id', String(props.ieId));
        const res = await fetch(
            `/api/trabajadores/search?${params.toString()}`,
        );
        if (res.ok) workersList.value = await res.json();
    } catch (e) {
        console.error(e);
    } finally {
        searchingWorkers.value = false;
    }
}

function handleWorkerSearch(query: string) {
    clearTimeout(workerSearchTimeout);
    workerSearchTimeout = setTimeout(() => triggerSearchWorkers(query), 300);
}

function formatWorkerName(worker: any) {
    if (!worker?.persona) return '';
    return `${worker.persona.paterno} ${worker.persona.materno}, ${worker.persona.nombre}`;
}

function handleSubmit() {
    if (!cursoId.value) {
        cursoError.value = 'El curso es requerido.';
        cursoContainerRef.value?.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
        });
        return;
    }
    cursoError.value = null;

    if (horaFin.value && horaInicio.value && horaFin.value <= horaInicio.value) {
        horaError.value = 'La hora de fin debe ser posterior a la hora de inicio.';
        return;
    }
    horaError.value = null;

    emit('submit', {
        id: props.horarioCurso?.id,
        anio: props.anio,
        seccion_id: props.seccionId,
        curso_id: cursoId.value,
        nroDia: nroDia.value,
        diaSemana: getDiaSemanaChar(nroDia.value),
        turno_id: turnoId.value,
        horaInicio: horaInicio.value,
        horaFin: horaFin.value,
        // Datos del docente (opcionales)
        trabajador_id: selectedWorker.value?.id ?? null,
        titularSuplencia: titularSuplencia.value,
        fechaInicioDocente: fechaInicioDocente.value || null,
        fechaFinDocente: fechaFinDocente.value || null,
    });
}

defineExpose({ handleSubmit });
</script>

<template>
    <div class="space-y-6">
        <!-- ── Horario ──────────────────────────────────────────────────────── -->

        <!-- Curso -->
        <div ref="cursoContainerRef" class="space-y-1.5">
            <Label class="text-sm font-semibold">Curso</Label>
            <SearchSelect
                v-model="cursoId"
                :items="cursosItems"
                placeholder="Seleccione un curso..."
            />
            <InputError :message="cursoError" class="mt-1" />
            <p
                v-if="cursos.length === 0"
                class="text-xs font-medium text-amber-500"
            >
                No hay cursos registrados para esta IE. Regístrelos en el módulo
                de IE primero.
            </p>
        </div>

        <!-- Día de la semana -->
        <div class="space-y-1.5">
            <Label class="text-sm font-semibold">Día de la Semana</Label>
            <div class="grid grid-cols-4 gap-2 sm:grid-cols-7">
                <button
                    v-for="day in DAYS"
                    :key="day.nro"
                    type="button"
                    class="rounded-lg border px-2 py-2 text-sm font-medium transition-all duration-150"
                    :class="[
                        nroDia === day.nro
                            ? 'border-primary bg-primary text-primary-foreground shadow-xs'
                            : 'bg-background text-foreground hover:bg-muted',
                    ]"
                    @click="nroDia = day.nro"
                >
                    {{ day.label }}
                </button>
            </div>
        </div>

        <!-- Turno -->
        <div class="space-y-1.5">
            <Label class="text-sm font-semibold">
                Turno <span class="font-normal text-muted-foreground">(Requerido)</span>
            </Label>
            <div class="flex gap-3">
                <button
                    v-for="turno in TURNOS"
                    :key="turno.id"
                    type="button"
                    class="flex-1 rounded-lg border px-3 py-2 text-sm font-medium transition-all duration-150"
                    :class="[
                        turnoId === turno.id
                            ? 'border-primary bg-primary text-primary-foreground shadow-xs'
                            : 'bg-background text-foreground hover:bg-muted',
                    ]"
                    @click="turnoId = turno.id"
                >
                    {{ turno.label }}
                </button>
            </div>
            <p v-if="!turnoId" class="text-xs text-amber-500 font-medium">
                Seleccione el turno al que pertenece este horario.
            </p>
        </div>

        <!-- Horas -->
        <div class="space-y-1.5">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <Label for="horaInicio" class="text-sm font-semibold"
                        >Hora de Inicio</Label
                    >
                    <Input
                        id="horaInicio"
                        type="time"
                        v-model="horaInicio"
                        class="w-full"
                        @input="horaError = null"
                    />
                </div>
                <div class="space-y-1.5">
                    <Label for="horaFin" class="text-sm font-semibold"
                        >Hora de Fin</Label
                    >
                    <Input
                        id="horaFin"
                        type="time"
                        v-model="horaFin"
                        class="w-full"
                        :class="horaError ? 'border-red-500 ring-1 ring-red-500' : ''"
                        @input="horaError = null"
                    />
                </div>
            </div>
            <InputError :message="horaError" class="mt-1" />
        </div>

        <!-- ── Divisor ──────────────────────────────────────────────────────── -->
        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-border" />
            <span
                class="text-xs font-semibold tracking-wide text-muted-foreground uppercase"
            >
                Docente (Opcional)
            </span>
            <div class="h-px flex-1 bg-border" />
        </div>

        <!-- Buscador de docente -->
        <div class="space-y-1.5">
            <Label class="text-sm font-semibold">Docente / Trabajador</Label>
            <SearchSelect
                v-model="selectedWorkerId"
                :items="workerItems"
                placeholder="Buscar y seleccionar docente..."
                :loading="searchingWorkers"
                clearable
                @search="handleWorkerSearch"
            />
        </div>

        <!-- Info del docente seleccionado + fechas + condición -->
        <template v-if="selectedWorker">
            <!-- Card docente -->
            <div
                class="flex items-start gap-3 rounded-xl border bg-muted/20 p-4"
            >
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-300"
                >
                    <User class="h-5 w-5" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold">
                        {{ formatWorkerName(selectedWorker) }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        DNI: {{ selectedWorker.persona?.docIdentidad }} | Cód:
                        {{ selectedWorker.codigo }}
                    </p>
                </div>
            </div>

            <!-- Condición: Titular / Suplente -->
            <div class="space-y-1.5">
                <Label class="text-sm font-semibold">Condición del Cargo</Label>
                <div class="flex gap-6">
                    <label
                        class="flex cursor-pointer items-center gap-2 text-sm font-medium"
                    >
                        <input
                            type="radio"
                            v-model="titularSuplencia"
                            value="T"
                            class="h-4 w-4"
                        />
                        Titular
                    </label>
                    <label
                        class="flex cursor-pointer items-center gap-2 text-sm font-medium"
                    >
                        <input
                            type="radio"
                            v-model="titularSuplencia"
                            value="S"
                            class="h-4 w-4"
                        />
                        Suplente
                    </label>
                </div>
            </div>

            <!-- Fechas de la carga horaria -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <Label
                        for="fechaInicioDocente"
                        class="text-sm font-semibold"
                    >
                        Fecha Inicio
                        <span class="font-normal text-muted-foreground"
                            >(Opcional)</span
                        >
                    </Label>
                    <Input
                        id="fechaInicioDocente"
                        type="date"
                        v-model="fechaInicioDocente"
                        class="w-full"
                    />
                </div>
                <div class="space-y-1.5">
                    <Label for="fechaFinDocente" class="text-sm font-semibold">
                        Fecha Fin
                        <span class="font-normal text-muted-foreground"
                            >(Opcional)</span
                        >
                    </Label>
                    <Input
                        id="fechaFinDocente"
                        type="date"
                        v-model="fechaFinDocente"
                        class="w-full"
                    />
                </div>
            </div>
        </template>
    </div>
</template>
