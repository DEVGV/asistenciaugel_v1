<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SearchSelect from '@/components/shared/SearchSelect.vue';
import type {
    Expediente,
    ExoneracionForm,
    IncapacidadForm,
    JustificacionForm,
    MotivoSuspLab,
    SuspensionForm,
    TipoExpediente,
} from '@/types/models/tramite';

const props = defineProps<{
    expediente: Expediente;
}>();

const emit = defineEmits<{
    (e: 'success'): void;
}>();

const tipo = ref<TipoExpediente | null>(props.expediente.tipoExpediente);
const processing = ref(false);
const errors = ref<Record<string, string>>({});
const successMsg = ref('');

// ── Motivos (para todos los tipos) ──────────────────────────────────────────
const motivos = ref<MotivoSuspLab[]>([]);
const loadingMotivos = ref(false);

const motivosEndpointMap: Record<TipoExpediente, string> = {
    S: '/api/motivos-suspension',
    I: '/api/motivos-incapacidad',
    J: '/api/motivos-justificacion',
    E: '/api/motivos-exoneracion',
};

async function cargarMotivos() {
    if (!tipo.value) return;
    loadingMotivos.value = true;
    try {
        const endpoint = motivosEndpointMap[tipo.value];
        const res = await fetch(endpoint, { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error();
        const json = await res.json();
        motivos.value = json.data ?? [];
    } catch {
        motivos.value = [];
    } finally {
        loadingMotivos.value = false;
    }
}

onMounted(cargarMotivos);
watch(tipo, cargarMotivos);

// Items formateados para SearchSelect { id, label }
const motivoItems = computed(() =>
    motivos.value.map((m) => ({
        id: m.id,
        label: m.descripcion ?? '',
        sublabel: m.codigo ?? undefined,
    })),
);

// Opciones fijas para turnos
const turnoItems = [
    { id: 1, label: 'Mañana' },
    { id: 2, label: 'Tarde' },
    { id: 3, label: 'Noche' },
];

// Opciones fijas para condición de subsidio
const subsidioItems = [
    { id: 'SI', label: 'Subsidiado' },
    { id: 'NO', label: 'No subsidiado' },
];

// ── Formularios por tipo ─────────────────────────────────────────────────────

const suspForm = ref<SuspensionForm>({
    motivoSuspLab_id: null,
    fechaHoraInicio: '',
    fechaHoraFin: '',
    totalDias: null,
    totalHoras: null,
    observacion: '',
});

// Cálculo automático de días y horas
watch(
    () => [suspForm.value.fechaHoraInicio, suspForm.value.fechaHoraFin],
    ([inicio, fin]) => {
        if (!inicio || !fin) {
            suspForm.value.totalDias = null;
            suspForm.value.totalHoras = null;
            return;
        }
        const ms = new Date(fin).getTime() - new Date(inicio).getTime();
        if (ms <= 0) {
            suspForm.value.totalDias = null;
            suspForm.value.totalHoras = null;
            return;
        }
        const totalHoras = ms / (1000 * 60 * 60);
        suspForm.value.totalHoras = Math.round(totalHoras * 100) / 100;
        suspForm.value.totalDias = Math.round((totalHoras / 24) * 100) / 100;
    },
);

const justForm = ref<JustificacionForm>({
    motivoSuspLab_id: null,
    turno: null,
    fechaInicio: '',
    fechaFin: '',
    observacion: '',
});

const incapForm = ref<IncapacidadForm>({
    motivoSuspLab_id: null,
    condicionSubsidio: '',
    fechaInicio: '',
    fechaFin: '',
    nroDias: null,
    nroCertificado: '',
    observacion: '',
});

// Cálculo automático de días para incapacidad
watch(
    () => [incapForm.value.fechaInicio, incapForm.value.fechaFin],
    ([inicio, fin]) => {
        if (!inicio || !fin) {
            incapForm.value.nroDias = null;
            return;
        }
        const ms = new Date(fin).getTime() - new Date(inicio).getTime();
        if (ms <= 0) {
            incapForm.value.nroDias = null;
            return;
        }
        incapForm.value.nroDias = Math.round(ms / (1000 * 60 * 60 * 24));
    },
);

const exonForm = ref<ExoneracionForm>({
    motivoSuspLab_id: null,
    fechaInicio: '',
    fechaFin: '',
    observacion: '',
});

// ── Submit ───────────────────────────────────────────────────────────────────

async function submit() {
    processing.value = true;
    errors.value = {};
    successMsg.value = '';

    const endpointMap: Record<TipoExpediente, string> = {
        S: 'suspension',
        J: 'justificacion',
        I: 'incapacidad',
        E: 'exoneracion',
    };

    const bodyMap: Record<TipoExpediente, unknown> = {
        S: suspForm.value,
        J: justForm.value,
        I: incapForm.value,
        E: exonForm.value,
    };

    if (!tipo.value) return;

    try {
        const res = await fetch(`/api/expedientes/${props.expediente.id}/${endpointMap[tipo.value]}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify(bodyMap[tipo.value]),
        });

        if (!res.ok) {
            const json = await res.json();
            if (json.errors) {
                // Laravel devuelve { field: ["msg1", "msg2"] } — aplanar a strings
                const flat: Record<string, string> = {};
                for (const [key, val] of Object.entries(json.errors)) {
                    flat[key] = Array.isArray(val) ? val[0] : String(val);
                }
                errors.value = flat;
            } else if (json.message) {
                errors.value = { _general: json.message };
            }
            return;
        }

        successMsg.value = 'Registrado correctamente.';
        emit('success');
    } catch {
        errors.value = { _general: 'Error de conexión.' };
    } finally {
        processing.value = false;
    }
}

function getCsrfToken(): string {
    return decodeURIComponent(
        document.cookie
            .split('; ')
            .find((c) => c.startsWith('XSRF-TOKEN='))
            ?.split('=')[1] ?? '',
    );
}
</script>

<template>
    <div class="space-y-4">
        <h3 class="text-sm font-semibold">Registrar detalle del trámite</h3>

        <p v-if="errors._general" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ errors._general }}
        </p>

        <p v-if="successMsg" class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
            {{ successMsg }}
        </p>

        <!-- ── Suspensión ────────────────────────────────────────── -->
        <template v-if="tipo === 'S'">
            <div class="grid gap-2">
                <Label>Motivo de suspensión *</Label>
                <SearchSelect
                    :items="motivoItems"
                    :model-value="suspForm.motivoSuspLab_id"
                    :loading="loadingMotivos"
                    placeholder="Seleccione motivo…"
                    @update:model-value="suspForm.motivoSuspLab_id = $event as number | null"
                />
                <p v-if="errors.motivoSuspLab_id" class="text-sm text-destructive">{{ errors.motivoSuspLab_id }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label>Fecha/hora inicio *</Label>
                    <Input v-model="suspForm.fechaHoraInicio" type="datetime-local" :class="{ 'border-destructive': errors.fechaHoraInicio }" />
                    <p v-if="errors.fechaHoraInicio" class="text-sm text-destructive">{{ errors.fechaHoraInicio }}</p>
                </div>
                <div class="grid gap-2">
                    <Label>Fecha/hora fin *</Label>
                    <Input v-model="suspForm.fechaHoraFin" type="datetime-local" :class="{ 'border-destructive': errors.fechaHoraFin }" />
                    <p v-if="errors.fechaHoraFin" class="text-sm text-destructive">{{ errors.fechaHoraFin }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label>Total días</Label>
                    <Input :model-value="suspForm.totalDias ?? ''" type="text" readonly class="bg-muted" />
                </div>
                <div class="grid gap-2">
                    <Label>Total horas</Label>
                    <Input :model-value="suspForm.totalHoras ?? ''" type="text" readonly class="bg-muted" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label>Observación</Label>
                <Input v-model="suspForm.observacion" placeholder="Opcional" />
            </div>
        </template>

        <!-- ── Justificación ─────────────────────────────────────── -->
        <template v-else-if="tipo === 'J'">
            <div class="grid gap-2">
                <Label>Motivo</Label>
                <SearchSelect
                    :items="motivoItems"
                    :model-value="justForm.motivoSuspLab_id"
                    :loading="loadingMotivos"
                    placeholder="Seleccione motivo…"
                    clearable
                    @update:model-value="justForm.motivoSuspLab_id = $event as number | null"
                />
                <p v-if="errors.motivoSuspLab_id" class="text-sm text-destructive">{{ errors.motivoSuspLab_id }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label>Fecha inicio *</Label>
                    <Input v-model="justForm.fechaInicio" type="date" :class="{ 'border-destructive': errors.fechaInicio }" />
                    <p v-if="errors.fechaInicio" class="text-sm text-destructive">{{ errors.fechaInicio }}</p>
                </div>
                <div class="grid gap-2">
                    <Label>Fecha fin *</Label>
                    <Input v-model="justForm.fechaFin" type="date" :class="{ 'border-destructive': errors.fechaFin }" />
                    <p v-if="errors.fechaFin" class="text-sm text-destructive">{{ errors.fechaFin }}</p>
                </div>
            </div>

            <div class="grid gap-2">
                <Label>Turno</Label>
                <SearchSelect
                    :items="turnoItems"
                    :model-value="justForm.turno"
                    placeholder="No especificado"
                    clearable
                    @update:model-value="justForm.turno = $event as number | null"
                />
            </div>

            <div class="grid gap-2">
                <Label>Observación</Label>
                <Input v-model="justForm.observacion" placeholder="Opcional" />
            </div>
        </template>

        <!-- ── Incapacidad ───────────────────────────────────────── -->
        <template v-else-if="tipo === 'I'">
            <div class="grid gap-2">
                <Label>Motivo de incapacidad *</Label>
                <SearchSelect
                    :items="motivoItems"
                    :model-value="incapForm.motivoSuspLab_id"
                    :loading="loadingMotivos"
                    placeholder="Seleccione motivo…"
                    @update:model-value="incapForm.motivoSuspLab_id = $event as number | null"
                />
                <p v-if="errors.motivoSuspLab_id" class="text-sm text-destructive">{{ errors.motivoSuspLab_id }}</p>
            </div>

            <div class="grid gap-2">
                <Label>Condición de subsidio *</Label>
                <SearchSelect
                    :items="subsidioItems"
                    :model-value="incapForm.condicionSubsidio || null"
                    placeholder="Seleccione…"
                    @update:model-value="incapForm.condicionSubsidio = ($event as string) ?? ''"
                />
                <p v-if="errors.condicionSubsidio" class="text-sm text-destructive">{{ errors.condicionSubsidio }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label>Fecha inicio *</Label>
                    <Input v-model="incapForm.fechaInicio" type="date" :class="{ 'border-destructive': errors.fechaInicio }" />
                    <p v-if="errors.fechaInicio" class="text-sm text-destructive">{{ errors.fechaInicio }}</p>
                </div>
                <div class="grid gap-2">
                    <Label>Fecha fin *</Label>
                    <Input v-model="incapForm.fechaFin" type="date" :class="{ 'border-destructive': errors.fechaFin }" />
                    <p v-if="errors.fechaFin" class="text-sm text-destructive">{{ errors.fechaFin }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label>N° días</Label>
                    <Input :model-value="incapForm.nroDias ?? ''" type="text" readonly class="bg-muted" />
                </div>
                <div class="grid gap-2">
                    <Label>N° certificado médico *</Label>
                    <Input v-model="incapForm.nroCertificado" :class="{ 'border-destructive': errors.nroCertificado }" />
                    <p v-if="errors.nroCertificado" class="text-sm text-destructive">{{ errors.nroCertificado }}</p>
                </div>
            </div>

            <div class="grid gap-2">
                <Label>Observación</Label>
                <Input v-model="incapForm.observacion" placeholder="Opcional" />
            </div>
        </template>

        <!-- ── Exoneración ───────────────────────────────────────── -->
        <template v-else-if="tipo === 'E'">
            <div class="grid gap-2">
                <Label>Motivo</Label>
                <SearchSelect
                    :items="motivoItems"
                    :model-value="exonForm.motivoSuspLab_id"
                    :loading="loadingMotivos"
                    placeholder="Seleccione motivo…"
                    clearable
                    @update:model-value="exonForm.motivoSuspLab_id = $event as number | null"
                />
                <p v-if="errors.motivoSuspLab_id" class="text-sm text-destructive">{{ errors.motivoSuspLab_id }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label>Fecha inicio *</Label>
                    <Input v-model="exonForm.fechaInicio" type="date" :class="{ 'border-destructive': errors.fechaInicio }" />
                    <p v-if="errors.fechaInicio" class="text-sm text-destructive">{{ errors.fechaInicio }}</p>
                </div>
                <div class="grid gap-2">
                    <Label>Fecha fin *</Label>
                    <Input v-model="exonForm.fechaFin" type="date" :class="{ 'border-destructive': errors.fechaFin }" />
                    <p v-if="errors.fechaFin" class="text-sm text-destructive">{{ errors.fechaFin }}</p>
                </div>
            </div>

            <div class="grid gap-2">
                <Label>Observación</Label>
                <Input v-model="exonForm.observacion" placeholder="Opcional" />
            </div>
        </template>

        <!-- ── Botón submit ──────────────────────────────────────── -->
        <div v-if="!successMsg" class="flex justify-end pt-2">
            <Button :disabled="processing || loadingMotivos" @click="submit">
                <Loader2 v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                {{ processing ? 'Registrando…' : 'Registrar detalle' }}
            </Button>
        </div>
    </div>
</template>
