<script setup lang="ts">
import { Calendar, User, Clock, Trash2, Edit, UserPlus } from 'lucide-vue-next';
import { computed, ref, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import type { HorarioCurso } from '@/types/models/horario';

const props = withDefaults(
    defineProps<{
        horarios: HorarioCurso[];
        readOnly?: boolean;
    }>(),
    {
        readOnly: false,
    },
);

const emit = defineEmits<{
    (e: 'select', horario: HorarioCurso): void;
    (e: 'edit', horario: HorarioCurso): void;
    (e: 'delete', horario: HorarioCurso): void;
    (e: 'assign', horario: HorarioCurso): void;
}>();

const DAYS = [
    { nro: 1, label: 'Lunes', short: 'Lun' },
    { nro: 2, label: 'Martes', short: 'Mar' },
    { nro: 3, label: 'Miércoles', short: 'Mié' },
    { nro: 4, label: 'Jueves', short: 'Jue' },
    { nro: 5, label: 'Viernes', short: 'Vie' },
    { nro: 6, label: 'Sábado', short: 'Sáb' },
    { nro: 7, label: 'Domingo', short: 'Dom' },
];

// 24 horas × 60 px/hora = 1440 px de alto total
const PX_PER_HOUR = 60;
const TOTAL_HOURS = 24;
const GRID_HEIGHT = PX_PER_HOUR * TOTAL_HOURS; // 1440px

const HOURS = Array.from({ length: TOTAL_HOURS + 1 }, (_, i) => i); // 0..24

function parseTimeToMinutes(timeStr: string): number {
    const parts = timeStr.split(':').map(Number);

    return (parts[0] || 0) * 60 + (parts[1] || 0);
}

function getPositionStyle(horario: HorarioCurso) {
    const startMin = parseTimeToMinutes(horario.horaInicio);
    const endMin = parseTimeToMinutes(horario.horaFin);
    const duration = Math.max(endMin - startMin, 30); // mínimo 30 min visible

    const top = (startMin / 60) * PX_PER_HOUR;
    const height = (duration / 60) * PX_PER_HOUR;

    return {
        top: `${top}px`,
        height: `${height}px`,
    };
}

function formatTime(timeStr: string): string {
    const parts = timeStr.split(':');

    return `${parts[0]}:${parts[1]}`;
}

function getColorClass(cursoId: number) {
    const colors = [
        'bg-blue-50/90 border-blue-200 text-blue-800 dark:bg-blue-950/40 dark:border-blue-900/60 dark:text-blue-200 hover:bg-blue-100/90',
        'bg-emerald-50/90 border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-900/60 dark:text-emerald-200 hover:bg-emerald-100/90',
        'bg-violet-50/90 border-violet-200 text-violet-800 dark:bg-violet-950/40 dark:border-violet-900/60 dark:text-violet-200 hover:bg-violet-100/90',
        'bg-amber-50/90 border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-900/60 dark:text-amber-200 hover:bg-amber-100/90',
        'bg-rose-50/90 border-rose-200 text-rose-800 dark:bg-rose-950/40 dark:border-rose-900/60 dark:text-rose-200 hover:bg-rose-100/90',
        'bg-indigo-50/90 border-indigo-200 text-indigo-800 dark:bg-indigo-950/40 dark:border-indigo-900/60 dark:text-indigo-200 hover:bg-indigo-100/90',
        'bg-cyan-50/90 border-cyan-200 text-cyan-800 dark:bg-cyan-950/40 dark:border-cyan-900/60 dark:text-cyan-200 hover:bg-cyan-100/90',
    ];

    return colors[cursoId % colors.length];
}

const scrollContainer = ref<HTMLDivElement | null>(null);

onMounted(() => {
    if (scrollContainer.value) {
        // 6 AM * 60px/hr = 360px
        scrollContainer.value.scrollTop = 6 * PX_PER_HOUR;
    }
});
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border bg-card font-sans text-card-foreground shadow-sm"
    >
        <!-- ── Área con scroll vertical ────────────────────────────────────── -->
        <div ref="scrollContainer" class="h-[600px] overflow-y-auto">
            <!-- ── Header fijo de días ──────────────────────────────────────────── -->
            <div
                class="sticky top-0 z-10 grid grid-cols-[80px_1fr_1fr_1fr_1fr_1fr_1fr_1fr] border-b bg-muted text-center text-sm font-semibold"
            >
                <div class="border-r py-3 text-muted-foreground">Hora</div>
                <div
                    v-for="day in DAYS"
                    :key="day.nro"
                    class="flex flex-col items-center justify-center border-r py-3 last:border-r-0"
                >
                    <span class="hidden md:inline">{{ day.label }}</span>
                    <span class="md:hidden">{{ day.short }}</span>
                </div>
            </div>

            <!-- Contenedor interno de altura fija (1440px = 24h × 60px) -->
            <div
                class="relative grid grid-cols-[80px_1fr_1fr_1fr_1fr_1fr_1fr_1fr] select-none"
                :style="{ height: `${GRID_HEIGHT}px` }"
            >
                <!-- Columna de horas -->
                <div class="relative border-r bg-muted/10">
                    <div
                        v-for="hour in HOURS"
                        :key="hour"
                        class="absolute right-0 left-0 flex items-center justify-center text-[11px] font-medium text-muted-foreground/70"
                        :style="{
                            top: `${hour * PX_PER_HOUR}px`,
                            height: `${PX_PER_HOUR}px`,
                        }"
                    >
                        {{ String(hour).padStart(2, '0') }}:00
                    </div>
                </div>

                <!-- Líneas de fondo (hora a hora) -->
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 left-[80px] grid grid-cols-7"
                >
                    <div
                        v-for="day in DAYS"
                        :key="day.nro"
                        class="relative h-full border-r last:border-r-0"
                    >
                        <div
                            v-for="hour in HOURS"
                            :key="hour"
                            class="absolute right-0 left-0 border-t border-dashed border-muted/40"
                            :style="{ top: `${hour * PX_PER_HOUR}px` }"
                        />
                    </div>
                </div>

                <!-- Bloques de horario -->
                <div class="relative col-span-7 grid grid-cols-7">
                    <div
                        v-for="day in DAYS"
                        :key="day.nro"
                        class="relative border-r last:border-r-0"
                        :style="{ height: `${GRID_HEIGHT}px` }"
                    >
                        <div
                            v-for="horario in horarios.filter(
                                (h) => h.nroDia === day.nro,
                            )"
                            :key="horario.id"
                            class="group absolute right-1 left-1 flex flex-col justify-between overflow-hidden rounded-lg border p-2 shadow-xs transition-all duration-200"
                            :class="[
                                getColorClass(horario.curso_id),
                                readOnly ? 'cursor-default' : 'cursor-pointer',
                            ]"
                            :style="getPositionStyle(horario)"
                            @click="!readOnly && emit('select', horario)"
                        >
                            <div class="min-w-0">
                                <div
                                    class="truncate text-xs font-semibold md:text-sm"
                                >
                                    {{ horario.curso?.nombre || 'Curso' }}
                                </div>
                                <div
                                    v-if="horario.seccion"
                                    class="mt-0.5 truncate text-[10px] font-medium opacity-90"
                                >
                                    {{ horario.seccion.grado?.nombre || '' }} -
                                    {{ horario.seccion.nombre || '' }}
                                </div>
                                <div
                                    class="mt-0.5 flex items-center gap-1 text-[10px] opacity-80 md:text-xs"
                                >
                                    <Clock class="h-3 w-3 shrink-0" />
                                    <span
                                        >{{ formatTime(horario.horaInicio) }} -
                                        {{ formatTime(horario.horaFin) }}</span
                                    >
                                </div>
                            </div>

                            <!-- Docente + acciones -->
                            <div
                                class="mt-1 flex items-center justify-between gap-1 border-t border-current/10 pt-1"
                            >
                                <div
                                    class="flex min-w-0 items-center gap-1 truncate text-[10px] md:text-xs"
                                >
                                    <User class="h-3.5 w-3.5 shrink-0" />
                                    <span class="truncate font-medium">
                                        {{
                                            horario.cargas &&
                                            horario.cargas.length > 0
                                                ? `${horario.cargas[0].trabajador?.persona?.paterno} ${(horario.cargas[0].trabajador?.persona?.nombre || '').charAt(0)}.`
                                                : 'Sin docente'
                                        }}
                                    </span>
                                </div>

                                <div
                                    v-if="!readOnly"
                                    class="hidden items-center gap-0.5 group-hover:flex"
                                >
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="h-5 w-5 p-0 hover:bg-current/15"
                                        title="Asignar docente"
                                        @click.stop="emit('assign', horario)"
                                    >
                                        <UserPlus class="h-3 w-3" />
                                    </Button>
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="h-5 w-5 p-0 hover:bg-current/15"
                                        title="Editar"
                                        @click.stop="emit('edit', horario)"
                                    >
                                        <Edit class="h-3 w-3" />
                                    </Button>
                                    <Button
                                        size="icon"
                                        variant="ghost"
                                        class="h-5 w-5 p-0 text-red-600 hover:bg-red-100/30 hover:text-red-700 dark:text-red-400"
                                        title="Eliminar"
                                        @click.stop="emit('delete', horario)"
                                    >
                                        <Trash2 class="h-3 w-3" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
