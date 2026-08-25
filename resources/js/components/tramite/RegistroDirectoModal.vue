<script setup lang="ts">
import { ArrowLeft, FileCheck, Loader2 } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import SearchSelect from '@/components/shared/SearchSelect.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type {
    ExoneracionForm,
    IncapacidadForm,
    JustificacionForm,
    MotivoSuspLab,
    PersonalActivoOption,
    SuspensionForm,
    TipoExpediente,
} from '@/types/models/tramite';
import { TIPO_EXPEDIENTE_LABELS } from '@/types/models/tramite';

const props = defineProps<{
    show: boolean;
    /** IE del contexto — carga el personal activo de esa IE. */
    institucionId?: number | null;
    /** trabajador_id pre-cargado cuando se abre desde el tab de un trabajador. */
    trabajadorId?: number | null;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'success'): void;
}>();

// ── Estado del modal ─────────────────────────────────────────────────────────
const step = ref<'tipo' | 'form'>('tipo');
const selectedTipo = ref<TipoExpediente | null>(null);
const processing = ref(false);
const errors = ref<Record<string, string>>({});
const successMsg = ref('');

// ── Selección de trabajador (contexto IE) ───────────────────────────────────
const personal = ref<PersonalActivoOption[]>([]);
const loadingPersonal = ref(false);
const selectedTrabajadorId = ref<number | null>(props.trabajadorId ?? null);
const selectedAltaId = ref<number | null>(null);

// Cuando viene desde IE, cargar personal activo
async function cargarPersonal() {
    if (!props.institucionId) return;
    loadingPersonal.value = true;
    try {
        const res = await fetch(
            `/api/instituciones/${props.institucionId}/personal-activo`,
            { headers: { Accept: 'application/json' } },
        );
        if (!res.ok) throw new Error();
        const json = await res.json();
        personal.value = json.data ?? [];
    } catch {
        personal.value = [];
    } finally {
        loadingPersonal.value = false;
    }
}

const personalItems = computed(() =>
    personal.value.map((p) => ({
        id: p.alta_id,
        label: p.label,
        sublabel: [p.docIdentidad, p.cargo].filter(Boolean).join(' — '),
    })),
);

// Cuando el usuario selecciona un trabajador del personal activo
function onSelectPersonal(altaId: number | string | null) {
    if (!altaId) {
        selectedTrabajadorId.value = null;
        selectedAltaId.value = null;
        return;
    }
    const item = personal.value.find((p) => p.alta_id === altaId);
    if (item) {
        selectedTrabajadorId.value = item.trabajador_id;
        selectedAltaId.value = item.alta_id;
    }
}

// Altas del trabajador (contexto trabajador)
const altasTrabajador = ref<{ alta_id: number; label: string; sublabel?: string }[]>([]);
const loadingAltas = ref(false);

async function cargarAltasTrabajador() {
    if (!props.trabajadorId) return;
    loadingAltas.value = true;
    try {
        const res = await fetch(
            `/api/trabajadores/${props.trabajadorId}/altas-activas`,
            { headers: { Accept: 'application/json' } },
        );
        if (!res.ok) throw new Error();
        const json = await res.json();
        altasTrabajador.value = (json.data ?? []).map((a: any) => ({
            id: a.alta_id,
            label: a.label,
            sublabel: a.sublabel ?? undefined,
        }));

        // Si solo hay una alta, seleccionarla automáticamente
        if (altasTrabajador.value.length === 1) {
            selectedAltaId.value = altasTrabajador.value[0].alta_id;
        }
    } catch {
        altasTrabajador.value = [];
    } finally {
        loadingAltas.value = false;
    }
}

// ── Motivos (para todos los tipos) ──────────────────────────────────────────
const motivos = ref<MotivoSuspLab[]>([]);
const loadingMotivos = ref(false);

const motivosEndpointMap: Record<TipoExpediente, string> = {
    S: '/api/motivos-suspension?todos=1',
    I: '/api/motivos-incapacidad?todos=1',
    J: '/api/motivos-justificacion?todos=1',
    E: '/api/motivos-exoneracion?todos=1',
};

async function cargarMotivos() {
    if (!selectedTipo.value) return;
    loadingMotivos.value = true;
    try {
        const endpoint = motivosEndpointMap[selectedTipo.value];
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

const motivoItems = computed(() =>
    motivos.value.map((m) => ({
        id: m.id,
        label: m.descripcion ?? '',
        sublabel: m.codigo ?? undefined,
    })),
);

const turnoItems = [
    { id: 1, label: 'Mañana' },
    { id: 2, label: 'Tarde' },
    { id: 3, label: 'Noche' },
];

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

// ── Tipos disponibles ───────────────────────────────────────────────────────
const tiposDisponibles: { value: TipoExpediente; label: string; desc: string }[] = [
    { value: 'J', label: 'Justificación', desc: 'Justificar inasistencia o tardanza' },
    { value: 'S', label: 'Suspensión', desc: 'Suspensión laboral del trabajador' },
    { value: 'E', label: 'Exoneración', desc: 'Exonerar de marcación al trabajador' },
    { value: 'I', label: 'Incapacidad', desc: 'Incapacidad temporal del trabajador' },
];

// ── Seleccionar tipo y pasar al formulario ──────────────────────────────────
function selectTipo(tipo: TipoExpediente) {
    selectedTipo.value = tipo;
    step.value = 'form';
    cargarMotivos();
}

function goBack() {
    step.value = 'tipo';
    errors.value = {};
    successMsg.value = '';
}

// ── Reset al cerrar ─────────────────────────────────────────────────────────
watch(
    () => props.show,
    (val) => {
        if (!val) {
            step.value = 'tipo';
            selectedTipo.value = null;
            processing.value = false;
            errors.value = {};
            successMsg.value = '';
            selectedTrabajadorId.value = props.trabajadorId ?? null;
            selectedAltaId.value = null;
            suspForm.value = { motivoSuspLab_id: null, fechaHoraInicio: '', fechaHoraFin: '', totalDias: null, totalHoras: null, observacion: '' };
            justForm.value = { motivoSuspLab_id: null, turno: null, fechaInicio: '', fechaFin: '', observacion: '' };
            incapForm.value = { motivoSuspLab_id: null, condicionSubsidio: '', fechaInicio: '', fechaFin: '', nroDias: null, nroCertificado: '', observacion: '' };
            exonForm.value = { motivoSuspLab_id: null, fechaInicio: '', fechaFin: '', observacion: '' };
        } else {
            if (props.institucionId) cargarPersonal();
            if (props.trabajadorId) cargarAltasTrabajador();
        }
    },
);

// ── Submit ───────────────────────────────────────────────────────────────────
async function submit() {
    if (!selectedTipo.value || !selectedTrabajadorId.value) return;

    processing.value = true;
    errors.value = {};
    successMsg.value = '';

    const bodyMap: Record<TipoExpediente, unknown> = {
        S: suspForm.value,
        J: justForm.value,
        I: incapForm.value,
        E: exonForm.value,
    };

    const payload = {
        tipoExpediente: selectedTipo.value,
        trabajador_id: selectedTrabajadorId.value,
        altaTrabajador_id: selectedAltaId.value,
        ...(bodyMap[selectedTipo.value] as Record<string, unknown>),
    };

    try {
        const res = await fetch('/api/registro-directo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify(payload),
        });

        if (!res.ok) {
            const json = await res.json();
            if (json.errors) {
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

        // Cerrar el modal después de un breve delay
        setTimeout(() => {
            emit('update:show', false);
        }, 1000);
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
    <Dialog :open="props.show" @update:open="emit('update:show', $event)">
        <DialogContent
            class="flex max-h-[min(800px,_80vh)] flex-col gap-0 overflow-hidden p-0 font-sans duration-300 sm:max-w-2xl"
        >
            <DialogHeader class="sticky top-0 z-10 shrink-0 border-b bg-background px-6 pt-6 pb-5">
                <div class="flex items-center gap-2">
                    <Button
                        v-if="step === 'form'"
                        variant="ghost"
                        size="icon"
                        class="h-7 w-7 shrink-0"
                        @click="goBack"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <DialogTitle class="text-2xl font-semibold tracking-[-0.029375rem]">
                            {{ step === 'tipo' ? 'Registrar permiso' : TIPO_EXPEDIENTE_LABELS[selectedTipo!] }}
                        </DialogTitle>
                        <DialogDescription class="text-base text-muted-foreground">
                            {{
                                step === 'tipo'
                                    ? 'Seleccione el tipo de permiso que desea registrar.'
                                    : 'Complete los datos del registro.'
                            }}
                        </DialogDescription>
                    </div>
                </div>
            </DialogHeader>

            <div class="flex-1 overflow-y-auto px-6 py-6 text-sm">
                <!-- ═══ Paso 1: Seleccionar tipo ═══ -->
                <div v-if="step === 'tipo'" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button
                        v-for="t in tiposDisponibles"
                        :key="t.value"
                        class="flex flex-col items-start gap-1 rounded-lg border bg-card p-4 text-left transition hover:border-primary hover:bg-primary/5"
                        @click="selectTipo(t.value)"
                    >
                        <span class="text-sm font-semibold">{{ t.label }}</span>
                        <span class="text-xs text-muted-foreground">{{ t.desc }}</span>
                    </button>
                </div>

                <!-- ═══ Paso 2: Formulario CUD ═══ -->
                <div v-else class="space-y-4">
                    <!-- Mensajes -->
                    <p
                        v-if="errors._general"
                        class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"
                    >
                        {{ errors._general }}
                    </p>
                    <p
                        v-if="successMsg"
                        class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700"
                    >
                        {{ successMsg }}
                    </p>

                    <!-- Selección de trabajador (desde IE) -->
                    <div v-if="props.institucionId && !props.trabajadorId" class="grid gap-2">
                        <Label>Trabajador *</Label>
                        <SearchSelect
                            :items="personalItems"
                            :model-value="selectedAltaId"
                            :loading="loadingPersonal"
                            placeholder="Seleccione trabajador…"
                            @update:model-value="onSelectPersonal($event)"
                        />
                        <p v-if="errors.trabajador_id" class="text-sm text-destructive">{{ errors.trabajador_id }}</p>
                    </div>

                    <!-- Selección de IE (desde trabajador) -->
                    <div v-if="props.trabajadorId && altasTrabajador.length > 1" class="grid gap-2">
                        <Label>Institución Educativa *</Label>
                        <SearchSelect
                            :items="altasTrabajador"
                            :model-value="selectedAltaId"
                            :loading="loadingAltas"
                            placeholder="Seleccione IE…"
                            @update:model-value="selectedAltaId = $event as number | null"
                        />
                    </div>

                    <!-- ── Suspensión ────────────────────────────────── -->
                    <template v-if="selectedTipo === 'S'">
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

                    <!-- ── Justificación ─────────────────────────────── -->
                    <template v-else-if="selectedTipo === 'J'">
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

                    <!-- ── Incapacidad ───────────────────────────────── -->
                    <template v-else-if="selectedTipo === 'I'">
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

                    <!-- ── Exoneración ───────────────────────────────── -->
                    <template v-else-if="selectedTipo === 'E'">
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

                    <!-- ── Botón submit ──────────────────────────────── -->
                    <div v-if="!successMsg" class="flex justify-end gap-2 pt-2">
                        <Button variant="outline" :disabled="processing" @click="emit('update:show', false)">
                            Cancelar
                        </Button>
                        <Button :disabled="processing || loadingMotivos || !selectedTrabajadorId" @click="submit">
                            <Loader2 v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                            {{ processing ? 'Registrando…' : 'Registrar' }}
                        </Button>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
