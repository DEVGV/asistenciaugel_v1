<script setup lang="ts">
import {
    AlertCircle,
    CalendarCheck2,
    ChevronLeft,
    ChevronRight,
    LogIn,
    LogOut,
    RefreshCw,
} from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';

// ── Tipos ──────────────────────────────────────────────────────────────────────
interface Marcacion {
    id: number;
    codigo: string;
    fechaMarcacion: string;
    tipo: 'E' | 'S';
    medioMarcacion: string | null;
    procesado: boolean | null;
    altaTrabajador_id: number | null;
}

// ── Props ──────────────────────────────────────────────────────────────────────
const props = defineProps<{
    trabajadorId: number;
}>();

// ── Estado ─────────────────────────────────────────────────────────────────────
const CURRENT_DATE = new Date();
const selectedAnio = ref<number>(CURRENT_DATE.getFullYear());
const selectedMes = ref<number>(CURRENT_DATE.getMonth() + 1);
const marcaciones = ref<Marcacion[]>([]);
const loading = ref(false);
const error = ref(false);

// ── Constantes ─────────────────────────────────────────────────────────────────
const MESES = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
];

// ── Navegación de mes ──────────────────────────────────────────────────────────
const mesLabel = computed(() => `${MESES[selectedMes.value - 1]} ${selectedAnio.value}`);

function mesAnterior() {
    if (selectedMes.value === 1) {
        selectedMes.value = 12;
        selectedAnio.value--;
    } else {
        selectedMes.value--;
    }
}

function mesSiguiente() {
    if (selectedMes.value === 12) {
        selectedMes.value = 1;
        selectedAnio.value++;
    } else {
        selectedMes.value++;
    }
}

// ── Carga de datos ─────────────────────────────────────────────────────────────
async function cargarDatos() {
    loading.value = true;
    error.value = false;

    try {
        const res = await fetch(
            `/trabajadores/${props.trabajadorId}/marcaciones?anio=${selectedAnio.value}&mes=${selectedMes.value}`,
            { headers: { Accept: 'application/json' } },
        );

        if (!res.ok) throw new Error();

        const data = await res.json();
        marcaciones.value = data.data;
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(() => cargarDatos());
watch([selectedAnio, selectedMes], () => cargarDatos());

// ── Días para la cuadrícula del calendario ──────────────────────────────────────
const diasCalendario = computed(() => {
    const anio = selectedAnio.value;
    const mes = selectedMes.value;
    const firstDayDate = new Date(anio, mes - 1, 1);
    const firstDayOfWeek = firstDayDate.getDay();
    const diasEnMes = new Date(anio, mes, 0).getDate();

    const DIAS_LABORABLES = [1, 2, 3, 4, 5]; // Lun-Vie

    const totalCells = Math.ceil((firstDayOfWeek + diasEnMes) / 7) * 7;
    const resultado = [];

    for (let i = 0; i < totalCells; i++) {
        const offset = i - firstDayOfWeek;
        const fecha = new Date(anio, mes - 1, 1 + offset);

        const fAnio = fecha.getFullYear();
        const fMes = fecha.getMonth() + 1;
        const fDia = fecha.getDate();
        const diaSemana = fecha.getDay();
        const esDiaLaboral = DIAS_LABORABLES.includes(diaSemana);
        const fechaStr = `${fAnio}-${String(fMes).padStart(2, '0')}-${String(fDia).padStart(2, '0')}`;
        const esMesActual = fAnio === anio && fMes === mes;

        // Todas las marcaciones del día (pueden ser varias)
        const marcasDia = marcaciones.value.filter((m) => {
            const f = new Date(m.fechaMarcacion);
            return f.getFullYear() === fAnio && f.getMonth() + 1 === fMes && f.getDate() === fDia;
        });

        const hoy = new Date();
        const esHoy = hoy.getFullYear() === fAnio && hoy.getMonth() + 1 === fMes && hoy.getDate() === fDia;

        resultado.push({
            fecha: fechaStr,
            dia: String(fDia),
            numeroDia: fDia,
            esDiaLaboral,
            esMesActual,
            esHoy,
            marcas: marcasDia,
        });
    }

    return resultado;
});

// ── Resumen simple ──────────────────────────────────────────────────────────────
const resumen = computed(() => {
    const anio = selectedAnio.value;
    const mes = selectedMes.value;
    const diasEnMes = new Date(anio, mes, 0).getDate();
    const DIAS_LABORABLES = [1, 2, 3, 4, 5];

    const hoy = new Date();
    let diasLaborales = 0;
    let diasConMarcas = 0;

    for (let dia = 1; dia <= diasEnMes; dia++) {
        const fecha = new Date(anio, mes - 1, dia);
        const diaSemana = fecha.getDay();
        if (!DIAS_LABORABLES.includes(diaSemana)) continue;
        if (fecha > hoy) continue;

        diasLaborales++;

        const tieneMarcas = marcaciones.value.some((m) => {
            const f = new Date(m.fechaMarcacion);
            return f.getFullYear() === anio && f.getMonth() + 1 === mes && f.getDate() === dia;
        });
        if (tieneMarcas) diasConMarcas++;
    }

    return {
        diasLaborales,
        diasConMarcas,
        diasSinMarcas: diasLaborales - diasConMarcas,
        totalMarcaciones: marcaciones.value.length,
    };
});

// ── Helpers ─────────────────────────────────────────────────────────────────────
function formatHora(hora: string): string {
    if (!hora) return '';
    if (!hora.includes('T') && !hora.includes(' ')) {
        return hora.substring(0, 5);
    }
    const d = new Date(hora);
    return d.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit', hour12: false });
}

function formatDiaLabel(dia: { dia: string; fecha: string }): string {
    if (dia.dia === '1') {
        const [, mes] = dia.fecha.split('-').map(Number);
        const mesNombreCorto = MESES[mes - 1].substring(0, 3);
        return `1 ${mesNombreCorto}.`;
    }
    return dia.dia;
}
</script>

<template>
    <div class="flex flex-col gap-5">
        <!-- Cabecera -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <CalendarCheck2 class="h-5 w-5 text-primary" />
                <h2 class="text-base font-bold">Registro de Asistencia</h2>
            </div>

            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 rounded-lg border bg-background px-1 py-0.5">
                    <button
                        class="flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        @click="mesAnterior"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </button>
                    <span class="min-w-36 text-center text-sm font-semibold">{{ mesLabel }}</span>
                    <button
                        class="flex h-7 w-7 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        @click="mesSiguiente"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </button>
                </div>

                <Button
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8"
                    title="Recargar"
                    :disabled="loading"
                    @click="cargarDatos"
                >
                    <RefreshCw class="h-4 w-4" :class="{ 'animate-spin': loading }" />
                </Button>
            </div>
        </div>

        <!-- Resumen simple -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="flex flex-col items-center gap-1 rounded-xl border bg-card p-3 text-center shadow-xs">
                <span class="text-xs text-muted-foreground">Días laborales</span>
                <span class="text-2xl font-bold text-foreground">{{ resumen.diasLaborales }}</span>
            </div>
            <div class="flex flex-col items-center gap-1 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-center dark:border-emerald-900 dark:bg-emerald-950/20">
                <span class="text-xs text-emerald-600 dark:text-emerald-400">Con marcas</span>
                <span class="text-2xl font-bold text-emerald-700 dark:text-emerald-300">{{ resumen.diasConMarcas }}</span>
            </div>
            <div class="flex flex-col items-center gap-1 rounded-xl border border-gray-200 bg-gray-50 p-3 text-center dark:border-gray-700 dark:bg-gray-950/20">
                <span class="text-xs text-gray-600 dark:text-gray-400">Sin marcas</span>
                <span class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ resumen.diasSinMarcas }}</span>
            </div>
            <div class="flex flex-col items-center gap-1 rounded-xl border border-blue-200 bg-blue-50 p-3 text-center dark:border-blue-900 dark:bg-blue-950/20">
                <span class="text-xs text-blue-600 dark:text-blue-400">Total marcaciones</span>
                <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ resumen.totalMarcaciones }}</span>
            </div>
        </div>

        <!-- Error -->
        <div
            v-if="error"
            class="flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/20 dark:text-red-300"
        >
            <AlertCircle class="h-4 w-4 shrink-0" />
            Error al cargar las marcaciones. Intente nuevamente.
        </div>

        <!-- Skeleton -->
        <div v-else-if="loading" class="space-y-4">
            <div class="grid grid-cols-7 gap-2">
                <div v-for="i in 7" :key="i" class="h-4 animate-pulse rounded bg-muted/40" />
            </div>
            <div class="grid grid-cols-7 border border-muted/50 rounded-lg overflow-hidden bg-background">
                <div
                    v-for="i in 35"
                    :key="i"
                    class="border-b border-r border-muted/50 p-2 min-h-[100px] animate-pulse flex flex-col justify-between"
                >
                    <div class="flex justify-end">
                        <div class="h-4 w-4 rounded-full bg-muted/40" />
                    </div>
                    <div class="space-y-2 grow mt-4">
                        <div class="h-4 w-5/6 rounded bg-muted/40" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario Grid -->
        <div v-else class="flex flex-col gap-3">
            <!-- Encabezados días de la semana -->
            <div class="grid grid-cols-7 text-center border-b border-muted pb-2 bg-muted/10 pt-2 rounded-t-lg">
                <div
                    v-for="dia in ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO']"
                    :key="dia"
                    class="text-xs font-semibold text-muted-foreground uppercase tracking-wider hidden md:block"
                >
                    {{ dia }}
                </div>
                <div
                    v-for="dia in ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']"
                    :key="dia"
                    class="text-xs font-semibold text-muted-foreground uppercase tracking-wider md:hidden"
                >
                    {{ dia }}
                </div>
            </div>

            <!-- Grid de días -->
            <div class="grid grid-cols-7 border-t border-l border-muted/50 rounded-b-lg overflow-hidden bg-background shadow-xs">
                <div
                    v-for="dia in diasCalendario"
                    :key="dia.fecha"
                    class="border-b border-r border-muted/50 p-2 min-h-[100px] flex flex-col transition-colors relative group hover:bg-muted/5"
                    :class="[
                        !dia.esMesActual ? 'bg-muted/15 opacity-50' : '',
                        !dia.esDiaLaboral && dia.esMesActual ? 'bg-muted/8' : '',
                    ]"
                >
                    <!-- Cabecera del día -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span
                                v-if="!dia.esDiaLaboral && dia.esMesActual"
                                class="text-[9px] font-semibold text-muted-foreground/40 uppercase tracking-wider"
                            >
                                FINDE
                            </span>
                        </div>
                        <div
                            :class="[
                                dia.esHoy
                                    ? 'flex items-center justify-center w-6 h-6 rounded-full bg-primary text-primary-foreground text-xs font-bold shadow-xs'
                                    : 'text-xs font-semibold text-muted-foreground',
                                dia.esMesActual && !dia.esHoy ? 'text-foreground font-bold' : '',
                            ]"
                        >
                            {{ formatDiaLabel(dia) }}
                        </div>
                    </div>

                    <!-- Cuerpo: marcaciones del día -->
                    <div class="mt-1.5 flex flex-col gap-1 grow">
                        <template v-if="dia.esMesActual">
                            <!-- Mostrar cada marca registrada -->
                            <div
                                v-for="marca in dia.marcas"
                                :key="marca.id"
                                class="flex items-center gap-1 rounded px-1.5 py-0.5 text-[10px] sm:text-[11px] font-medium font-mono"
                                :class="
                                    marca.tipo === 'E'
                                        ? 'bg-emerald-50 text-emerald-800 border border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/30'
                                        : 'bg-blue-50 text-blue-800 border border-blue-100 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-900/30'
                                "
                                :title="marca.tipo === 'E' ? 'Entrada' : 'Salida'"
                            >
                                <LogIn v-if="marca.tipo === 'E'" class="h-3 w-3 shrink-0 text-emerald-600 dark:text-emerald-400" />
                                <LogOut v-else class="h-3 w-3 shrink-0 text-blue-600 dark:text-blue-400" />
                                <span>{{ formatHora(marca.fechaMarcacion) }}</span>
                            </div>

                            <!-- Indicador sutil de día no laboral sin marcas -->
                            <div
                                v-if="!dia.esDiaLaboral && !dia.marcas.length"
                                class="flex items-center justify-center grow"
                            >
                                <span class="text-[9px] text-muted-foreground/30">No laboral</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
