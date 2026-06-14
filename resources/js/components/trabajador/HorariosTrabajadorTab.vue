<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Calendar,
    School,
    ArrowRight,
    BookOpen,
    AlertCircle,
    RefreshCw,
    LogIn,
    LogOut,
    Timer,
} from 'lucide-vue-next';
import { ref, onMounted, watch } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const props = defineProps<{
    trabajadorId: number;
}>();

const CURRENT_YEAR = new Date().getFullYear();
const YEARS = [CURRENT_YEAR, CURRENT_YEAR - 1, CURRENT_YEAR - 2];

const selectedAnio = ref<string>(String(CURRENT_YEAR));
const horarios = ref<any[]>([]);
const loading = ref(false);
const error = ref(false);

const DAYS_OF_WEEK: Record<number, string> = {
    1: 'Lunes',
    2: 'Martes',
    3: 'Miércoles',
    4: 'Jueves',
    5: 'Viernes',
    6: 'Sábado',
    7: 'Domingo',
};

const TURNO_CONFIG: Record<
    number,
    { label: string; class: string; dot: string }
> = {
    1: {
        label: 'MAÑANA',
        class: 'bg-amber-100 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:ring-amber-900/50',
        dot: 'bg-amber-400',
    },
    2: {
        label: 'TARDE',
        class: 'bg-blue-100 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-950/30 dark:text-blue-300 dark:ring-blue-900/50',
        dot: 'bg-blue-400',
    },
    3: {
        label: 'NOCHE',
        class: 'bg-violet-100 text-violet-700 ring-1 ring-violet-200 dark:bg-violet-950/30 dark:text-violet-300 dark:ring-violet-900/50',
        dot: 'bg-violet-400',
    },
};

function getTurno(turnoId: number | null) {
    return TURNO_CONFIG[turnoId ?? 1] ?? TURNO_CONFIG[1];
}

function formatTime(timeStr: string | null): string {
    if (!timeStr) {
        return '—';
    }

    const parts = timeStr.split(':');

    return `${parts[0]}:${parts[1]}`;
}

function totalHoras(detalles: any[]): string {
    if (!detalles) {
        return '0.00';
    }

    return detalles
        .filter((d) => d.aplicar)
        .reduce((sum: number, d: any) => sum + Number(d.horaAcumula || 0), 0)
        .toFixed(2);
}

async function loadHorarios() {
    loading.value = true;
    error.value = false;

    try {
        const res = await fetch(
            `/trabajadores/${props.trabajadorId}/horarios?anio=${selectedAnio.value}`,
            { headers: { Accept: 'application/json' } },
        );

        if (!res.ok) {
            throw new Error();
        }

        const result = await res.json();
        horarios.value = result.data;
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => loadHorarios());
watch(selectedAnio, () => loadHorarios());
</script>

<template>
    <div class="flex flex-col gap-5">
        <!-- Cabecera -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <Calendar class="h-5 w-5 text-primary" />
                <h2 class="text-base font-bold">Horarios Consolidados</h2>
            </div>

            <div class="flex items-center gap-2">
                <Select v-model="selectedAnio">
                    <SelectTrigger class="h-8 w-32 text-xs">
                        <SelectValue placeholder="Año" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="y in YEARS"
                            :key="y"
                            :value="String(y)"
                        >
                            {{ y }}
                        </SelectItem>
                    </SelectContent>
                </Select>

                <Button
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8"
                    title="Recargar"
                    :disabled="loading"
                    @click="loadHorarios"
                >
                    <RefreshCw
                        class="h-4 w-4"
                        :class="{ 'animate-spin': loading }"
                    />
                </Button>
            </div>
        </div>

        <!-- Error -->
        <div
            v-if="error"
            class="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/20 dark:text-red-300"
        >
            <AlertCircle class="h-4 w-4 shrink-0" />
            Error al cargar los horarios. Intente nuevamente.
        </div>

        <!-- Skeleton -->
        <div v-else-if="loading" class="space-y-4">
            <div
                v-for="i in 2"
                :key="i"
                class="h-40 animate-pulse rounded-xl border bg-muted/30"
            />
        </div>

        <!-- Sin horarios -->
        <div
            v-else-if="horarios.length === 0"
            class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed py-14 text-center"
        >
            <Calendar class="mb-3 h-10 w-10 text-muted-foreground/40" />
            <p class="text-sm font-medium text-muted-foreground">
                Sin horarios registrados para {{ selectedAnio }}.
            </p>
            <p class="mt-1 text-xs text-muted-foreground/70">
                Los horarios se generan al asignar docentes a cursos en
                Planificación.
            </p>
        </div>

        <!-- Lista de horarios -->
        <div v-else class="space-y-6">
            <div
                v-for="horario in horarios"
                :key="horario.id"
                class="overflow-hidden rounded-xl border bg-card shadow-sm"
            >
                <!-- Header IE -->
                <div
                    class="flex flex-wrap items-start justify-between gap-3 border-b bg-muted/20 px-5 py-4"
                >
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <School class="h-4 w-4 text-emerald-500" />
                            <span class="font-bold text-foreground">
                                {{
                                    horario.institucion_educ?.nombreLegal ??
                                    'Institución'
                                }}
                            </span>
                        </div>
                        <div
                            class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-muted-foreground"
                        >
                            <span
                                >Código:
                                {{
                                    horario.institucion_educ
                                        ?.codigoInstitucion ?? '—'
                                }}</span
                            >
                            <span v-if="horario.alta_trabajador?.cargo">
                                · Cargo:
                                {{ horario.alta_trabajador.cargo.nombre }}
                            </span>
                            <span>· {{ horario.anio }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div
                            class="rounded-lg border bg-blue-50 px-3 py-1.5 text-center dark:bg-blue-950/20"
                        >
                            <p
                                class="text-[10px] font-semibold tracking-wider text-blue-500 uppercase dark:text-blue-400"
                            >
                                Hrs / semana
                            </p>
                            <p
                                class="text-xl font-extrabold text-blue-700 dark:text-blue-200"
                            >
                                {{ totalHoras(horario.detalles) }}
                            </p>
                        </div>

                        <Button
                            variant="outline"
                            size="sm"
                            class="h-8 text-xs"
                            as-child
                        >
                            <Link :href="`/horarios-trabajador/${horario.id}`">
                                Ver detalle
                                <ArrowRight class="ml-1 h-3.5 w-3.5" />
                            </Link>
                        </Button>
                    </div>
                </div>

                <!-- Sin detalles -->
                <div
                    v-if="!horario.detalles || horario.detalles.length === 0"
                    class="flex items-center justify-center gap-2 px-5 py-6 text-sm text-muted-foreground"
                >
                    <AlertCircle class="h-4 w-4" />
                    Sin días configurados.
                </div>

                <!-- Filas por día -->
                <div v-else class="divide-y">
                    <div
                        v-for="detalle in horario.detalles"
                        :key="detalle.id"
                        class="flex flex-wrap items-center gap-3 px-5 py-3 transition-colors hover:bg-muted/10"
                        :class="{ 'opacity-40': !detalle.aplicar }"
                    >
                        <!-- Día -->
                        <div class="w-24 shrink-0">
                            <span class="text-sm font-semibold">
                                {{
                                    DAYS_OF_WEEK[detalle.nroDia] ??
                                    detalle.diaSemana
                                }}
                            </span>
                        </div>

                        <!-- Turno -->
                        <div class="w-24 shrink-0">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                :class="getTurno(detalle.turno_id).class"
                            >
                                <span
                                    class="h-1.5 w-1.5 rounded-full"
                                    :class="getTurno(detalle.turno_id).dot"
                                />
                                {{
                                    detalle.nombreTurno ??
                                    getTurno(detalle.turno_id).label
                                }}
                            </span>
                        </div>

                        <!-- Entrada -->
                        <div
                            class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 dark:border-emerald-900/40 dark:bg-emerald-950/20"
                        >
                            <LogIn
                                class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400"
                            />
                            <div>
                                <p
                                    class="text-[10px] font-medium tracking-wider text-emerald-600/70 uppercase dark:text-emerald-400/60"
                                >
                                    Entrada
                                </p>
                                <p
                                    class="font-mono text-sm leading-none font-bold text-emerald-700 dark:text-emerald-300"
                                >
                                    {{ formatTime(detalle.entHoraInicio) }}
                                    <span
                                        class="ml-1 text-[10px] font-normal text-emerald-600/50"
                                    >
                                        ±{{ detalle.entTolerancia ?? 0 }}'
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Salida -->
                        <div
                            class="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 dark:border-red-900/40 dark:bg-red-950/20"
                        >
                            <LogOut
                                class="h-4 w-4 shrink-0 text-red-500 dark:text-red-400"
                            />
                            <div>
                                <p
                                    class="text-[10px] font-medium tracking-wider text-red-500/70 uppercase dark:text-red-400/60"
                                >
                                    Salida
                                </p>
                                <p
                                    class="font-mono text-sm leading-none font-bold text-red-600 dark:text-red-300"
                                >
                                    {{ formatTime(detalle.salHoraInicio) }}
                                    <span
                                        class="ml-1 text-[10px] font-normal text-red-500/50"
                                    >
                                        ±{{ detalle.salTolerancia ?? 0 }}'
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Horas acumuladas -->
                        <div class="flex items-center gap-1.5 text-sm">
                            <Timer class="h-4 w-4 text-muted-foreground/50" />
                            <span class="font-semibold tabular-nums">
                                {{
                                    Number(detalle.horaAcumula ?? 0).toFixed(2)
                                }}
                                <span
                                    class="text-xs font-normal text-muted-foreground"
                                    >hrs</span
                                >
                            </span>
                        </div>

                        <!-- Curso inicial -->
                        <div
                            class="flex min-w-0 items-center gap-1.5 text-xs text-muted-foreground"
                        >
                            <BookOpen class="h-3.5 w-3.5 shrink-0" />
                            <span class="truncate">
                                {{
                                    detalle.horario_curso_ini?.curso?.nombre ??
                                    '—'
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
