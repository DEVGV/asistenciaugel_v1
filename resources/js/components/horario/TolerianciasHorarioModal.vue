<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Clock, LogIn, LogOut, Save, Loader2, Info } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';

type Detalle = {
    id: number;
    nroDia: number;
    nombreTurno?: string | null;
    aplicar?: boolean | null;
    entHoraInicio: string | null;
    entHoraFin: string | null;
    entTolerancia: number | null;
    salHoraInicio: string | null;
    salHoraFin: string | null;
    salTolerancia: number | null;
};

const props = defineProps<{
    show: boolean;
    horarioId: number;
    detalles: Detalle[];
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'saved'): void;
}>();

const DAYS: Record<number, string> = {
    1: 'Lunes',
    2: 'Martes',
    3: 'Miércoles',
    4: 'Jueves',
    5: 'Viernes',
    6: 'Sábado',
    7: 'Domingo',
};

const editable = ref<Detalle[]>([]);
const saving = ref(false);
const error = ref<string | null>(null);

function normalizeHora(hora: string | null | undefined): string {
    if (!hora) return '';
    // "08:00:00" → "08:00"
    return hora.length >= 5 ? hora.substring(0, 5) : hora;
}

watch(
    () => props.show,
    (open) => {
        if (open) {
            editable.value = (props.detalles || []).map((d) => ({
                ...d,
                entHoraInicio: normalizeHora(d.entHoraInicio),
                entHoraFin: normalizeHora(d.entHoraFin) || normalizeHora(d.entHoraInicio),
                salHoraInicio: normalizeHora(d.salHoraInicio),
                salHoraFin: normalizeHora(d.salHoraFin) || normalizeHora(d.salHoraInicio),
                entTolerancia: Number(d.entTolerancia ?? 15),
                salTolerancia: Number(d.salTolerancia ?? 15),
            }));
            error.value = null;
        }
    },
    { immediate: true },
);

const tieneCambios = computed(() => editable.value.length > 0);
const rowErrors = ref<Record<number, string>>({});

function applyAllTolerancia(field: 'entTolerancia' | 'salTolerancia', valor: number) {
    editable.value = editable.value.map((d) => ({ ...d, [field]: valor }));
}

async function guardar() {
    saving.value = true;
    error.value = null;
    rowErrors.value = {};

    // Validar rangos horarios: fin >= inicio para entrada y salida
    let hasError = false;
    for (const d of editable.value) {
        const errors: string[] = [];
        if (d.entHoraInicio && d.entHoraFin && d.entHoraFin < d.entHoraInicio) {
            errors.push('Entrada Hasta debe ser ≥ Entrada Desde');
        }
        if (d.salHoraInicio && d.salHoraFin && d.salHoraFin < d.salHoraInicio) {
            errors.push('Salida Hasta debe ser ≥ Salida Desde');
        }
        if (errors.length) {
            rowErrors.value[d.id] = errors.join('. ');
            hasError = true;
        }
    }
    if (hasError) {
        error.value = 'Corrija los rangos horarios marcados en rojo.';
        saving.value = false;
        return;
    }

    try {
        const csrf = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '';
        const res = await fetch(`/horarios-trabajador/${props.horarioId}/tolerancias`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                detalles: editable.value.map((d) => ({
                    id: d.id,
                    entTolerancia: d.entTolerancia,
                    salTolerancia: d.salTolerancia,
                    entHoraInicio: d.entHoraInicio || null,
                    entHoraFin: d.entHoraFin || null,
                    salHoraInicio: d.salHoraInicio || null,
                    salHoraFin: d.salHoraFin || null,
                    aplicar: d.aplicar,
                })),
            }),
        });

        if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            throw new Error(data.mensaje || data.message || 'No se pudo guardar');
        }

        emit('saved');
        emit('update:show', false);
    } catch (e: unknown) {
        error.value = (e as Error).message || 'Error al guardar tolerancias.';
    } finally {
        saving.value = false;
    }
}
</script>

<template>
    <Dialog :open="show" @update:open="(v) => emit('update:show', v)">
        <DialogContent class="max-w-[1100px] w-[95vw] sm:max-w-[1100px]">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <Clock class="h-5 w-5 text-primary" />
                    Configurar tolerancias y rangos horarios
                </DialogTitle>
                <DialogDescription>
                    Ajusta la ventana de tiempo aceptada por el procedimiento
                    de asistencia. La tolerancia se resta en la entrada y se
                    suma en la salida para dar margen a marcaciones cercanas al
                    horario oficial.
                </DialogDescription>
            </DialogHeader>

            <!-- Aviso didáctico -->
            <div class="rounded-md border border-blue-200 bg-blue-50 p-3 text-xs text-blue-900 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-200">
                <div class="flex items-start gap-2">
                    <Info class="h-4 w-4 mt-0.5 shrink-0" />
                    <div class="space-y-1">
                        <p><b>Entrada Desde–Hasta:</b> ventana en que se aceptan marcas de ingreso.
                            Marcas fuera de este rango se ignoran.</p>
                        <p><b>Salida Desde–Hasta:</b> ventana en que se aceptan marcas de salida.
                            Marcas después de "Hasta" se ignoran.</p>
                        <p><b>Tolerancia entrada:</b> minutos extra después de "Entrada Hasta"
                            que aún cuentan como puntual (A); pasado eso se marca como Tardanza (T).</p>
                        <p class="opacity-80">Sugerencia docente: Entrada 07:45–08:00 (tol. 15), Salida 09:30–09:45.</p>
                    </div>
                </div>
            </div>

            <!-- Aplicar a todos -->
            <div class="flex flex-wrap items-center gap-2 rounded-md border p-2 text-xs">
                <span class="font-semibold text-muted-foreground">Aplicar a todos:</span>
                <Button type="button" size="sm" variant="outline" class="h-7 text-xs"
                        @click="applyAllTolerancia('entTolerancia', 15); applyAllTolerancia('salTolerancia', 15)">
                    ±15 min
                </Button>
                <Button type="button" size="sm" variant="outline" class="h-7 text-xs"
                        @click="applyAllTolerancia('entTolerancia', 10); applyAllTolerancia('salTolerancia', 10)">
                    ±10 min
                </Button>
                <Button type="button" size="sm" variant="outline" class="h-7 text-xs"
                        @click="applyAllTolerancia('entTolerancia', 5); applyAllTolerancia('salTolerancia', 5)">
                    ±5 min
                </Button>
            </div>

            <!-- Grid por día -->
            <div class="max-h-[420px] overflow-auto rounded-md border">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-muted/40 backdrop-blur">
                        <tr class="text-left">
                            <th class="p-2 font-semibold">Día</th>
                            <th class="p-2 font-semibold text-center" colspan="2">
                                <span class="inline-flex items-center gap-1 text-emerald-700">
                                    <LogIn class="h-3.5 w-3.5" /> Entrada (Desde – Hasta)
                                </span>
                            </th>
                            <th class="p-2 font-semibold text-center">Tol. entrada</th>
                            <th class="p-2 font-semibold text-center" colspan="2">
                                <span class="inline-flex items-center gap-1 text-red-700">
                                    <LogOut class="h-3.5 w-3.5" /> Salida (Desde – Hasta)
                                </span>
                            </th>
                            <th class="p-2 font-semibold text-center">Tol. salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="d in editable" :key="d.id"
                            class="border-t hover:bg-muted/20"
                            :class="rowErrors[d.id] ? 'bg-red-50/50 dark:bg-red-950/10' : ''">
                            <td class="p-2 font-medium">
                                {{ DAYS[d.nroDia] ?? `Día ${d.nroDia}` }}
                                <div v-if="d.nombreTurno" class="text-[10px] text-muted-foreground">
                                    {{ d.nombreTurno }}
                                </div>
                                <div v-if="rowErrors[d.id]" class="text-[10px] text-red-500 font-normal mt-0.5">
                                    {{ rowErrors[d.id] }}
                                </div>
                            </td>
                            <td class="p-2">
                                <input v-model="d.entHoraInicio" type="time"
                                    class="w-24 rounded border px-2 py-1 font-mono text-xs"
                                    aria-label="Entrada desde" />
                            </td>
                            <td class="p-2">
                                <input v-model="d.entHoraFin" type="time"
                                    class="w-24 rounded border px-2 py-1 font-mono text-xs"
                                    :class="rowErrors[d.id] && d.entHoraFin && d.entHoraInicio && d.entHoraFin < d.entHoraInicio ? 'border-red-500 ring-1 ring-red-500' : ''"
                                    aria-label="Entrada hasta"
                                    @input="delete rowErrors[d.id]" />
                            </td>
                            <td class="p-2 text-center">
                                <input v-model.number="d.entTolerancia" type="number" min="0" max="120"
                                    class="w-14 rounded border px-2 py-1 text-center font-mono text-xs" />
                                <span class="ml-1 text-[10px] text-muted-foreground">min</span>
                            </td>
                            <td class="p-2">
                                <input v-model="d.salHoraInicio" type="time"
                                    class="w-24 rounded border px-2 py-1 font-mono text-xs"
                                    aria-label="Salida desde" />
                            </td>
                            <td class="p-2">
                                <input v-model="d.salHoraFin" type="time"
                                    class="w-24 rounded border px-2 py-1 font-mono text-xs"
                                    :class="rowErrors[d.id] && d.salHoraFin && d.salHoraInicio && d.salHoraFin < d.salHoraInicio ? 'border-red-500 ring-1 ring-red-500' : ''"
                                    aria-label="Salida hasta"
                                    @input="delete rowErrors[d.id]" />
                            </td>
                            <td class="p-2 text-center">
                                <input v-model.number="d.salTolerancia" type="number" min="0" max="120"
                                    class="w-14 rounded border px-2 py-1 text-center font-mono text-xs" />
                                <span class="ml-1 text-[10px] text-muted-foreground">min</span>
                            </td>
                        </tr>
                        <tr v-if="editable.length === 0">
                            <td colspan="7" class="p-6 text-center text-muted-foreground">
                                Este horario aún no tiene días configurados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p v-if="error" class="text-xs text-red-600">{{ error }}</p>

            <DialogFooter>
                <Button type="button" variant="ghost" @click="emit('update:show', false)" :disabled="saving">
                    Cancelar
                </Button>
                <Button type="button" @click="guardar" :disabled="saving || !tieneCambios">
                    <Loader2 v-if="saving" class="mr-2 h-4 w-4 animate-spin" />
                    <Save v-else class="mr-2 h-4 w-4" />
                    Guardar tolerancias
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
