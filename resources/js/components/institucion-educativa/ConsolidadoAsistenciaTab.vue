<script setup lang="ts">
import { ref, computed } from 'vue';
import { Loader2, Play, Search, ChevronDown, ChevronUp, AlertTriangle, CheckCircle, XCircle, RefreshCw, FileText } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

const props = defineProps<{
    institucionId: number;
}>();

const currentYear = new Date().getFullYear();
const currentMonth = new Date().getMonth() + 1;

const anio = ref(currentYear);
const mes = ref(currentMonth);
const isProcessing = ref(false);
const isLoading = ref(false);
const resultadoProceso = ref<{
    message: string;
    procesados: number;
    total: number;
    sin_horario: Array<{ trabajador_id: number; nombre: string }>;
    errores: Array<{ trabajador_id: number; nombre: string; error: string }>;
} | null>(null);

const consolidado = ref<Array<{
    id: number;
    trabajador: { id: number; nombre: string; dni: string };
    condicion: string;
    rol: string;
    fechaDesde: string;
    fechaHasta: string;
    resumen: Array<{ sigla: string; sigla_pers: string; ndias: number; remunerado: boolean; motivo: string }>;
}>>([]);

const expandedRow = ref<number | null>(null);
const detalleData = ref<Record<number, any[]>>({});
const loadingDetalle = ref<number | null>(null);
const reprocessingId = ref<number | null>(null);
const reprocessResult = ref<{ ok: boolean; mensaje: string; trabajadorId: number } | null>(null);

const meses = [
    { value: 1, label: 'Enero' },
    { value: 2, label: 'Febrero' },
    { value: 3, label: 'Marzo' },
    { value: 4, label: 'Abril' },
    { value: 5, label: 'Mayo' },
    { value: 6, label: 'Junio' },
    { value: 7, label: 'Julio' },
    { value: 8, label: 'Agosto' },
    { value: 9, label: 'Septiembre' },
    { value: 10, label: 'Octubre' },
    { value: 11, label: 'Noviembre' },
    { value: 12, label: 'Diciembre' },
];

const mesLabel = computed(() => meses.find(m => m.value === mes.value)?.label ?? '');

const diasEnMes = computed(() => {
    return new Date(anio.value, mes.value, 0).getDate();
});

async function procesarAsistencia() {
    isProcessing.value = true;
    resultadoProceso.value = null;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        const res = await fetch(`/instituciones/${props.institucionId}/consolidado-asistencia/procesar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ anio: anio.value, mes: mes.value }),
        });

        const data = await res.json();

        if (!res.ok) {
            resultadoProceso.value = {
                message: data.message || 'Error al procesar',
                procesados: 0,
                total: 0,
                sin_horario: [],
                errores: [{ trabajador_id: 0, nombre: 'Sistema', error: data.message || 'Error desconocido' }],
            };
            return;
        }

        resultadoProceso.value = data;
        // Recargar el consolidado después de procesar
        await consultarConsolidado();
    } catch (err: any) {
        resultadoProceso.value = {
            message: 'Error de conexión al procesar',
            procesados: 0,
            total: 0,
            sin_horario: [],
            errores: [{ trabajador_id: 0, nombre: 'Sistema', error: err.message }],
        };
    } finally {
        isProcessing.value = false;
    }
}

async function consultarConsolidado() {
    isLoading.value = true;
    try {
        const res = await fetch(
            `/instituciones/${props.institucionId}/consolidado-asistencia/consultar?anio=${anio.value}&mes=${mes.value}`,
            {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            },
        );
        const data = await res.json();
        consolidado.value = data.data ?? [];
    } catch {
        consolidado.value = [];
    } finally {
        isLoading.value = false;
    }
}

async function reprocesarTrabajador(trabajadorId: number, event?: Event) {
    event?.stopPropagation();
    reprocessingId.value = trabajadorId;
    reprocessResult.value = null;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        const res = await fetch(
            `/instituciones/${props.institucionId}/consolidado-asistencia/reprocesar-trabajador`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    trabajador_id: trabajadorId,
                    anio: anio.value,
                    mes: mes.value,
                }),
            },
        );

        const data = await res.json();
        reprocessResult.value = {
            ok: !!data.ok,
            mensaje: data.mensaje || (res.ok ? 'Procesado' : 'Error al reprocesar'),
            trabajadorId,
        };

        if (data.ok) {
            // Invalidar cache de detalle para que se recargue con los nuevos valores
            delete detalleData.value[trabajadorId];
            await consultarConsolidado();
        }
    } catch (err) {
        reprocessResult.value = {
            ok: false,
            mensaje: (err as Error).message || 'Error de conexión.',
            trabajadorId,
        };
    } finally {
        reprocessingId.value = null;
        // Auto-limpiar el aviso en 4 segundos
        setTimeout(() => {
            if (reprocessResult.value?.trabajadorId === trabajadorId) {
                reprocessResult.value = null;
            }
        }, 4000);
    }
}

async function toggleDetalle(asistenciaId: number) {
    if (expandedRow.value === asistenciaId) {
        expandedRow.value = null;
        return;
    }

    expandedRow.value = asistenciaId;

    if (!detalleData.value[asistenciaId]) {
        loadingDetalle.value = asistenciaId;
        try {
            const res = await fetch(`/consolidado-asistencia/${asistenciaId}/detalle`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            detalleData.value[asistenciaId] = data.data ?? [];
        } catch {
            detalleData.value[asistenciaId] = [];
        } finally {
            loadingDetalle.value = null;
        }
    }
}

function condicionColor(condicion: string): string {
    const map: Record<string, string> = {
        // Siglas base
        'A': 'bg-emerald-50 text-emerald-700 ring-emerald-700/10 dark:bg-emerald-950/30 dark:text-emerald-400',
        'T': 'bg-amber-50 text-amber-700 ring-amber-700/10 dark:bg-amber-950/30 dark:text-amber-400',
        'F': 'bg-red-50 text-red-700 ring-red-700/10 dark:bg-red-950/30 dark:text-red-400',
        'I': 'bg-red-50 text-red-700 ring-red-700/10 dark:bg-red-950/30 dark:text-red-400',
        'E': 'bg-orange-50 text-orange-700 ring-orange-700/10 dark:bg-orange-950/30 dark:text-orange-400',
        'DL': 'bg-gray-50 text-gray-600 ring-gray-600/10 dark:bg-gray-950/30 dark:text-gray-400',
        '3T': 'bg-red-50 text-red-700 ring-red-700/10 dark:bg-red-950/30 dark:text-red-400',
        '3E': 'bg-red-50 text-red-700 ring-red-700/10 dark:bg-red-950/30 dark:text-red-400',
        // Suspensiones / justificaciones
        'FER': 'bg-purple-50 text-purple-700 ring-purple-700/10 dark:bg-purple-950/30 dark:text-purple-400',
        'O': 'bg-pink-50 text-pink-700 ring-pink-700/10 dark:bg-pink-950/30 dark:text-pink-400',
        'J': 'bg-sky-50 text-sky-700 ring-sky-700/10 dark:bg-sky-950/30 dark:text-sky-400',
        'V': 'bg-teal-50 text-teal-700 ring-teal-700/10 dark:bg-teal-950/30 dark:text-teal-400',
        'L': 'bg-indigo-50 text-indigo-700 ring-indigo-700/10 dark:bg-indigo-950/30 dark:text-indigo-400',
        'P': 'bg-cyan-50 text-cyan-700 ring-cyan-700/10 dark:bg-cyan-950/30 dark:text-cyan-400',
    };
    return map[condicion] ?? 'bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-950/30 dark:text-blue-400';
}

function abrirReporte(nroReporte: 1 | 2) {
    const url = `/instituciones/${props.institucionId}/consolidado-asistencia/reporte${nroReporte}?anio=${anio.value}&mes=${mes.value}`;
    window.open(url, '_blank');
}

// Cargar consolidado al montar
consultarConsolidado();
</script>

<template>
    <div class="space-y-4">
        <!-- Controles -->
        <div class="flex flex-wrap items-end gap-4 rounded-xl border bg-card p-4 shadow-xs">
            <div class="grid gap-1.5">
                <Label class="text-xs font-bold text-muted-foreground uppercase">Año</Label>
                <Input
                    v-model.number="anio"
                    type="number"
                    class="h-9 w-24"
                    :min="2020"
                    :max="2099"
                />
            </div>
            <div class="grid gap-1.5">
                <Label class="text-xs font-bold text-muted-foreground uppercase">Mes</Label>
                <select
                    v-model.number="mes"
                    class="flex h-9 w-40 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                >
                    <option v-for="m in meses" :key="m.value" :value="m.value">
                        {{ m.label }}
                    </option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <Button
                    size="sm"
                    variant="outline"
                    :disabled="isLoading"
                    @click="consultarConsolidado"
                >
                    <Loader2 v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                    <Search v-else class="mr-2 h-4 w-4" />
                    Consultar
                </Button>
                <Button
                    size="sm"
                    :disabled="isProcessing"
                    @click="procesarAsistencia"
                    class="gap-1.5 bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                >
                    <Loader2 v-if="isProcessing" class="h-4 w-4 animate-spin" />
                    <Play v-else class="h-4 w-4" />
                    Procesar Asistencia
                </Button>
            </div>
        </div>

        <!-- Resultado del procesamiento -->
        <div v-if="resultadoProceso" class="space-y-3">
            <!-- Mensaje principal -->
            <div
                :class="[
                    'flex items-start gap-3 rounded-lg border p-4',
                    resultadoProceso.errores.length > 0
                        ? 'border-red-200 bg-red-50/50 dark:border-red-900/50 dark:bg-red-950/20'
                        : 'border-emerald-200 bg-emerald-50/50 dark:border-emerald-900/50 dark:bg-emerald-950/20',
                ]"
            >
                <CheckCircle
                    v-if="resultadoProceso.errores.length === 0"
                    class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400"
                />
                <AlertTriangle
                    v-else
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600 dark:text-red-400"
                />
                <div class="text-sm">
                    <p class="font-medium">{{ resultadoProceso.message }}</p>
                    <p v-if="resultadoProceso.sin_horario.length > 0" class="mt-1 text-muted-foreground">
                        {{ resultadoProceso.sin_horario.length }} trabajador(es) sin horario asignado.
                    </p>
                </div>
            </div>

            <!-- Sin horario -->
            <div v-if="resultadoProceso.sin_horario.length > 0" class="rounded-lg border border-amber-200 bg-amber-50/30 p-3 dark:border-amber-900/50 dark:bg-amber-950/20">
                <p class="mb-2 text-xs font-semibold text-amber-700 dark:text-amber-400">
                    <AlertTriangle class="mr-1 inline h-3.5 w-3.5" />
                    Trabajadores sin horario (no procesados):
                </p>
                <ul class="space-y-0.5 text-xs text-amber-800 dark:text-amber-300">
                    <li v-for="sh in resultadoProceso.sin_horario" :key="sh.trabajador_id">
                        {{ sh.nombre }}
                    </li>
                </ul>
            </div>

            <!-- Errores -->
            <div v-if="resultadoProceso.errores.length > 0" class="rounded-lg border border-red-200 bg-red-50/30 p-3 dark:border-red-900/50 dark:bg-red-950/20">
                <p class="mb-2 text-xs font-semibold text-red-700 dark:text-red-400">
                    <XCircle class="mr-1 inline h-3.5 w-3.5" />
                    Errores en el procesamiento:
                </p>
                <ul class="space-y-0.5 text-xs text-red-800 dark:text-red-300">
                    <li v-for="err in resultadoProceso.errores" :key="err.trabajador_id">
                        <span class="font-medium">{{ err.nombre }}:</span> {{ err.error }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tabla de consolidado -->
        <div class="overflow-hidden rounded-xl border bg-card shadow-xs">
            <div class="flex items-center justify-between border-b bg-muted/30 px-4 py-3">
                <h3 class="text-sm font-semibold">
                    Consolidado {{ mesLabel }} {{ anio }}
                    <span v-if="consolidado.length > 0" class="ml-2 text-xs font-normal text-muted-foreground">
                        ({{ consolidado.length }} registros)
                    </span>
                </h3>
                <div v-if="consolidado.length > 0" class="flex items-center gap-2">
                    <Button
                        size="sm"
                        variant="outline"
                        class="h-7 gap-1.5 text-xs"
                        @click="abrirReporte(1)"
                    >
                        <FileText class="h-3.5 w-3.5" />
                        Generar Reporte 1
                    </Button>
                    <Button
                        size="sm"
                        variant="outline"
                        class="h-7 gap-1.5 text-xs"
                        @click="abrirReporte(2)"
                    >
                        <FileText class="h-3.5 w-3.5" />
                        Generar Reporte 2
                    </Button>
                </div>
            </div>

            <!-- Toast simple de reprocesamiento por trabajador -->
            <div
                v-if="reprocessResult"
                :class="[
                    'mb-3 flex items-start gap-2 rounded-md border px-3 py-2 text-xs',
                    reprocessResult.ok
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/20 dark:text-emerald-300'
                        : 'border-red-200 bg-red-50 text-red-700 dark:border-red-900/50 dark:bg-red-950/20 dark:text-red-300',
                ]"
            >
                <CheckCircle v-if="reprocessResult.ok" class="mt-0.5 h-4 w-4 shrink-0" />
                <XCircle v-else class="mt-0.5 h-4 w-4 shrink-0" />
                <span>{{ reprocessResult.mensaje }}</span>
            </div>

            <div v-if="isLoading" class="flex items-center justify-center py-12">
                <Loader2 class="h-6 w-6 animate-spin text-muted-foreground" />
                <span class="ml-2 text-sm text-muted-foreground">Cargando...</span>
            </div>

            <div v-else-if="consolidado.length === 0" class="py-12 text-center text-sm text-muted-foreground">
                No hay registros de asistencia procesados para {{ mesLabel }} {{ anio }}.
            </div>

            <Table v-else>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[40px]"></TableHead>
                        <TableHead>Trabajador</TableHead>
                        <TableHead class="w-[100px]">Condición</TableHead>
                        <TableHead class="w-[120px]">Rol</TableHead>
                        <TableHead>Resumen de Asistencia</TableHead>
                        <TableHead class="w-[130px] text-center">Acciones</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-for="item in consolidado" :key="item.id">
                        <TableRow
                            class="cursor-pointer hover:bg-muted/30"
                            @click="toggleDetalle(item.id)"
                        >
                            <TableCell class="text-center">
                                <ChevronDown
                                    v-if="expandedRow !== item.id"
                                    class="mx-auto h-4 w-4 text-muted-foreground"
                                />
                                <ChevronUp
                                    v-else
                                    class="mx-auto h-4 w-4 text-primary"
                                />
                            </TableCell>
                            <TableCell>
                                <div class="text-sm font-medium">{{ item.trabajador.nombre }}</div>
                                <div class="text-xs text-muted-foreground">{{ item.trabajador.dni }}</div>
                            </TableCell>
                            <TableCell class="text-xs">{{ item.condicion }}</TableCell>
                            <TableCell class="text-xs">{{ item.rol }}</TableCell>
                            <TableCell>
                                <div class="flex flex-wrap gap-1.5">
                                    <span
                                        v-for="r in item.resumen"
                                        :key="r.sigla"
                                        :class="[
                                            'inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[11px] font-semibold ring-1',
                                            condicionColor(r.sigla_pers || r.sigla),
                                        ]"
                                        :title="`${r.sigla_pers || r.sigla} - ${r.motivo}`"
                                    >
                                        {{ r.motivo }}: {{ r.ndias }}
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell class="text-center" @click.stop>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    class="h-7 gap-1 text-[11px]"
                                    :disabled="reprocessingId === item.trabajador.id"
                                    :title="`Volver a procesar la asistencia del mes actual para ${item.trabajador.nombre}`"
                                    @click.stop="reprocesarTrabajador(item.trabajador.id, $event)"
                                >
                                    <Loader2 v-if="reprocessingId === item.trabajador.id" class="h-3.5 w-3.5 animate-spin" />
                                    <RefreshCw v-else class="h-3.5 w-3.5" />
                                    Reprocesar
                                </Button>
                            </TableCell>
                        </TableRow>

                        <!-- Detalle expandido -->
                        <TableRow v-if="expandedRow === item.id">
                            <TableCell :colspan="6" class="bg-muted/10 p-0">
                                <div v-if="loadingDetalle === item.id" class="flex items-center justify-center py-6">
                                    <Loader2 class="h-5 w-5 animate-spin text-muted-foreground" />
                                </div>
                                <div v-else-if="detalleData[item.id]?.length" class="overflow-x-auto p-3">
                                    <div v-for="turno in detalleData[item.id]" :key="turno.id" class="mb-3 last:mb-0">
                                        <p class="mb-1.5 text-xs font-semibold text-muted-foreground">
                                            Turno {{ turno.nroTurno }}
                                            <span v-if="turno.turno">— {{ turno.turno.nombre }}</span>
                                        </p>
                                        <div class="grid gap-px rounded-md border bg-muted/20" :style="{ gridTemplateColumns: `repeat(${diasEnMes}, minmax(0, 1fr))` }">
                                            <!-- Encabezados de días -->
                                            <div
                                                v-for="d in diasEnMes"
                                                :key="'h' + d"
                                                class="bg-muted/40 px-1 py-0.5 text-center text-[10px] font-bold text-muted-foreground"
                                            >
                                                {{ d }}
                                            </div>
                                            <!-- Condiciones -->
                                            <div
                                                v-for="d in diasEnMes"
                                                :key="'c' + d"
                                                class="flex items-center justify-center px-0.5 py-1"
                                            >
                                                <span
                                                    v-if="turno['c' + d]"
                                                    :class="[
                                                        'inline-flex h-5 w-full items-center justify-center rounded text-[10px] font-bold ring-1',
                                                        condicionColor(turno['c' + d]),
                                                    ]"
                                                    :title="`E: ${turno['e' + d] || '-'} | S: ${turno['s' + d] || '-'}`"
                                                >
                                                    {{ turno['c' + d] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="py-4 text-center text-xs text-muted-foreground">
                                    Sin detalle disponible.
                                </div>
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
