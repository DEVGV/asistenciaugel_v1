<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    User,
    School,
    Calendar,
    CalendarDays,
} from 'lucide-vue-next';
import { computed } from 'vue';
import DetalleHorarioGrid from '@/components/horario/DetalleHorarioGrid.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    horario: any;
    cargas: any[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Gestión de Horarios', href: '/horarios-trabajador' },
            { title: 'Horario Consolidado del Trabajador', href: '#' },
        ],
    },
});

// Mapear las cargas horarias al formato de calendario
const mappedHorarios = computed(() => {
    return (props.cargas || []).map((carga: any) => {
        const hc = carga.horario_curso || {};

        return {
            id: hc.id,
            anio: hc.anio || props.horario.anio,
            seccion_id: hc.seccion_id || 0,
            curso_id: hc.curso_id,
            curso: hc.curso,
            nroDia: hc.nroDia,
            diaSemana: hc.diaSemana || '',
            horaInicio: hc.horaInicio,
            horaFin: hc.horaFin,
            minAcum: hc.minAcum || 0,
            created_by: hc.created_by || 0,
            seccion: hc.seccion,
            cargas: [
                {
                    id: carga.id,
                    horarioCurso_id: hc.id,
                    trabajador_id: props.horario.trabajador_id,
                    altaTrabajador_id: carga.altaTrabajador_id || 0,
                    fechaInicio: carga.fechaInicio || null,
                    fechaFin: carga.fechaFin || null,
                    titularSuplencia: carga.titularSuplencia || 'T',
                    created_by: carga.created_by || 0,
                    trabajador: props.horario.trabajador,
                },
            ],
        };
    });
});

// Calcular el total de horas semanales acumuladas
const totalHorasSemanales = computed(() => {
    if (!props.horario.detalles) {
        return 0;
    }

    return props.horario.detalles
        .filter((d: any) => d.aplicar)
        .reduce((sum: number, d: any) => sum + Number(d.horaAcumula || 0), 0);
});
</script>

<template>
    <Head
        :title="`Horario Consolidado - ${props.horario.trabajador?.persona?.paterno}`"
    />

    <div class="flex flex-col gap-6 p-4">
        <!-- Botón Volver -->
        <div class="flex items-center justify-between">
            <Button variant="outline" size="sm" as-child>
                <Link :href="`/horarios-trabajador`">
                    <ArrowLeft class="mr-2 h-4 w-4" /> Volver a Horarios
                </Link>
            </Button>
            <span class="text-xs text-muted-foreground"
                >Código de Horario:
                <span class="font-mono font-bold">{{
                    props.horario.codigo
                }}</span></span
            >
        </div>

        <!-- Información del trabajador -->
        <div
            class="grid grid-cols-1 gap-6 border-b border-border pb-6 md:grid-cols-3"
        >
            <!-- Col 1: Trabajador -->
            <div
                class="space-y-1.5 border-border pb-4 md:border-r md:pr-6 md:pb-0"
            >
                <h3
                    class="flex items-center gap-1.5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                >
                    <User class="h-3.5 w-3.5 text-blue-500" />
                    Trabajador
                </h3>
                <div>
                    <p
                        class="text-lg leading-tight font-extrabold text-foreground"
                    >
                        {{ props.horario.trabajador?.persona?.paterno }}
                        {{ props.horario.trabajador?.persona?.materno }},
                        {{ props.horario.trabajador?.persona?.nombre }}
                    </p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        DNI:
                        {{ props.horario.trabajador?.persona?.docIdentidad }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        Código Interno: {{ props.horario.trabajador?.codigo }}
                    </p>
                </div>
            </div>

            <!-- Col 2: IE y Contrato -->
            <div
                class="space-y-1.5 border-border pb-4 md:border-r md:pr-6 md:pb-0"
            >
                <h3
                    class="flex items-center gap-1.5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                >
                    <School class="h-3.5 w-3.5 text-emerald-500" />
                    Institución Educativa
                </h3>
                <div>
                    <p
                        class="text-base leading-tight font-bold text-foreground"
                    >
                        {{ props.horario.institucion_educ?.nombreLegal }}
                    </p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Código:
                        {{ props.horario.institucion_educ?.codigoInstitucion }}
                    </p>
                    <p
                        class="text-xs text-muted-foreground"
                        v-if="
                            props.horario.alta_trabajador ||
                            props.horario.altaTrabajador
                        "
                    >
                        Cargo:
                        {{
                            (
                                props.horario.alta_trabajador ||
                                props.horario.altaTrabajador
                            )?.cargo?.nombre || 'Docente'
                        }}
                    </p>
                </div>
            </div>

            <!-- Col 3: Resumen de Horas -->
            <div class="flex flex-col justify-between space-y-1.5">
                <div>
                    <h3
                        class="flex items-center gap-1.5 text-xs font-bold tracking-wider text-muted-foreground uppercase"
                    >
                        <Calendar class="h-3.5 w-3.5 text-purple-500" />
                        Año Lectivo
                    </h3>
                    <p class="text-lg font-bold text-foreground">
                        {{ props.horario.anio }}
                    </p>
                </div>
                <div
                    class="rounded-lg border border-blue-100 bg-blue-50 p-3 dark:border-blue-900/40 dark:bg-blue-950/20"
                >
                    <p
                        class="text-xs font-medium text-blue-700 dark:text-blue-300"
                    >
                        Horas Semanales Consolidadas:
                    </p>
                    <p
                        class="mt-0.5 text-2xl font-extrabold text-blue-900 dark:text-blue-100"
                    >
                        {{ totalHorasSemanales.toFixed(2) }}
                        <span class="text-sm font-semibold">Horas</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Calendario de Horarios -->
        <div class="space-y-3 pt-2">
            <div class="flex flex-col gap-0.5">
                <h2
                    class="flex items-center gap-2 text-lg font-bold text-foreground"
                >
                    <CalendarDays class="h-5 w-5 text-primary" />
                    Horario Consolidado de Clases
                </h2>
                <p class="text-xs text-muted-foreground">
                    Visualización en formato de calendario de las clases y
                    cursos asignados al docente para el año lectivo
                    {{ props.horario.anio }}.
                </p>
            </div>

            <DetalleHorarioGrid :horarios="mappedHorarios" readOnly />
        </div>
    </div>
</template>
