<script setup lang="ts">
import { Check, Download, FileText, Loader2, X, XCircle } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import CudFormDinamico from '@/components/tramite/CudFormDinamico.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { TIPO_EXPEDIENTE_LABELS, estadoLabel, type Expediente } from '@/types/models/tramite';

const props = defineProps<{
    show: boolean;
    expedienteId: number | null;
}>();

const emit = defineEmits<{
    (e: 'update:show', val: boolean): void;
    (e: 'updated'): void;
}>();

const expediente = ref<Expediente | null>(null);
const loading = ref(false);
const error = ref(false);
const actionLoading = ref(false);
const actionError = ref('');
const showRechazoInput = ref(false);
const motivoRechazo = ref('');

const ESTADO_CLASES: Record<string, string> = {
    '1': 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
    '2': 'bg-sky-100 text-sky-700 ring-1 ring-sky-200',
    '3': 'bg-red-100 text-red-700 ring-1 ring-red-200',
    '4': 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
    '5': 'bg-muted text-muted-foreground ring-1 ring-border',
};

function estadoClase(exp: Expediente): string {
    return ESTADO_CLASES[exp.estado?.codigo ?? ''] ?? ESTADO_CLASES['1'];
}

function nombreTrabajador(exp: Expediente): string {
    const p = exp.trabajador?.persona;
    if (!p) return `#${exp.trabajador_id}`;
    return [p.paterno, p.materno, p.nombre].filter(Boolean).join(' ');
}

function getCsrfToken(): string {
    return decodeURIComponent(
        document.cookie
            .split('; ')
            .find((c) => c.startsWith('XSRF-TOKEN='))
            ?.split('=')[1] ?? '',
    );
}

async function cargar(id: number) {
    loading.value = true;
    error.value = false;
    expediente.value = null;
    actionError.value = '';
    showRechazoInput.value = false;
    motivoRechazo.value = '';

    try {
        const res = await fetch(`/api/expedientes/${id}/detalle`, {
            headers: { Accept: 'application/json' },
        });
        if (!res.ok) throw new Error();
        expediente.value = await res.json();
    } catch {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

/** Estado helpers */
function esRegistrado(exp: Expediente): boolean {
    return exp.estado?.codigo === '1';
}

function esAprobado(exp: Expediente): boolean {
    return exp.estado?.codigo === '2';
}

// ── Autorizar / Rechazar (cuando Aprobado + puedeResolver) ──────────────

async function autorizar() {
    if (!expediente.value) return;
    actionLoading.value = true;
    actionError.value = '';
    try {
        const res = await fetch(`/api/expedientes/${expediente.value.id}/autorizar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
        });
        if (!res.ok) {
            const json = await res.json();
            actionError.value = json.message ?? 'Error al autorizar.';
            return;
        }
        await cargar(expediente.value.id);
        emit('updated');
    } catch {
        actionError.value = 'Error de conexión.';
    } finally {
        actionLoading.value = false;
    }
}

async function rechazar() {
    if (!expediente.value) return;
    actionLoading.value = true;
    actionError.value = '';
    try {
        const res = await fetch(`/api/expedientes/${expediente.value.id}/rechazar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({ motivoRechazo: motivoRechazo.value || null }),
        });
        if (!res.ok) {
            const json = await res.json();
            actionError.value = json.message ?? 'Error al rechazar.';
            return;
        }
        await cargar(expediente.value.id);
        emit('updated');
    } catch {
        actionError.value = 'Error de conexión.';
    } finally {
        actionLoading.value = false;
        showRechazoInput.value = false;
        motivoRechazo.value = '';
    }
}

function onCudSuccess() {
    if (expediente.value) {
        cargar(expediente.value.id);
    }
    emit('updated');
}

watch(
    () => props.expedienteId,
    (id) => {
        if (props.show && id) cargar(id);
    },
);

watch(
    () => props.show,
    (val) => {
        if (val && props.expedienteId) cargar(props.expedienteId);
        if (!val) expediente.value = null;
    },
);

function close() {
    emit('update:show', false);
}
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="props.show"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                @click.self="close"
            >
                <Transition
                    enter-active-class="transition duration-150 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                    appear
                >
                    <div
                        v-if="props.show"
                        class="relative w-full max-w-2xl rounded-xl border bg-background shadow-xl"
                    >
                        <!-- Header del modal -->
                        <div class="flex items-center justify-between border-b px-6 py-4">
                            <div class="flex items-center gap-2">
                                <FileText class="h-5 w-5 text-primary" />
                                <span class="font-mono text-base font-bold text-primary">
                                    {{ expediente?.codigo ?? (loading ? '…' : 'Expediente') }}
                                </span>
                                <span
                                    v-if="expediente"
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                    :class="estadoClase(expediente)"
                                >
                                    {{ estadoLabel(expediente) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Button variant="ghost" size="icon" class="h-8 w-8" @click="close">
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>

                        <!-- Cuerpo -->
                        <div class="max-h-[75vh] overflow-y-auto p-6">
                            <!-- Loading -->
                            <div v-if="loading" class="flex items-center justify-center py-12">
                                <Loader2 class="h-6 w-6 animate-spin text-muted-foreground" />
                            </div>

                            <!-- Error -->
                            <div
                                v-else-if="error"
                                class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                            >
                                No se pudo cargar el expediente. Intente nuevamente.
                            </div>

                            <!-- Contenido -->
                            <div v-else-if="expediente" class="space-y-5">
                                <!-- Subtítulo -->
                                <p class="text-sm text-muted-foreground">
                                    {{ expediente.tipoExpediente
                                        ? TIPO_EXPEDIENTE_LABELS[expediente.tipoExpediente]
                                        : '—' }}
                                    · {{ expediente.fecha }}
                                    · Año {{ expediente.anio }}
                                </p>

                                <!-- Datos principales -->
                                <div class="rounded-xl border bg-card p-5 space-y-4">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-muted-foreground">Trabajador</p>
                                            <p class="font-medium">{{ nombreTrabajador(expediente) }}</p>
                                            <p
                                                v-if="expediente.trabajador?.persona?.docIdentidad"
                                                class="text-xs text-muted-foreground"
                                            >
                                                DNI {{ expediente.trabajador.persona.docIdentidad }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-muted-foreground">Asunto</p>
                                            <p class="font-medium">{{ expediente.asunto }}</p>
                                        </div>
                                        <div v-if="expediente.observacion" class="col-span-2">
                                            <p class="text-xs uppercase tracking-wide text-muted-foreground">Observación</p>
                                            <p class="text-sm">{{ expediente.observacion }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documentos -->
                                <div class="rounded-xl border bg-card p-5 space-y-3">
                                    <h3 class="text-sm font-semibold">Documentos</h3>

                                    <p v-if="!expediente.documentos?.length" class="text-sm text-muted-foreground">
                                        Sin documentos registrados.
                                    </p>

                                    <div
                                        v-for="doc in expediente.documentos"
                                        :key="doc.id"
                                        class="flex items-center justify-between rounded-lg border bg-muted/20 px-4 py-2.5"
                                    >
                                        <div class="text-sm">
                                            <span class="font-medium">{{ doc.documento?.nombre ?? 'Documento' }}</span>
                                            <span v-if="doc.nroDoc" class="ml-2 text-muted-foreground">N° {{ doc.nroDoc }}</span>
                                            <span v-if="doc.fechaDoc" class="ml-2 text-xs text-muted-foreground">· {{ doc.fechaDoc }}</span>
                                            <span v-if="doc.observacion" class="ml-2 text-xs text-muted-foreground">· {{ doc.observacion }}</span>
                                        </div>
                                        <Button
                                            v-if="doc.rutaDoc"
                                            as="a"
                                            :href="`/documentos-tram/${doc.id}/descargar`"
                                            target="_blank"
                                            variant="ghost"
                                            size="sm"
                                        >
                                            <Download class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>

                                <!-- ── Formulario CUD (solo Director/Admin UGEL, cuando Registrado y sin detalle) ── -->
                                <div
                                    v-if="esRegistrado(expediente) && !expediente.tieneDetalle && expediente.puedeRegistrarCud"
                                    class="rounded-xl border bg-card p-5"
                                >
                                    <CudFormDinamico
                                        :expediente="expediente"
                                        @success="onCudSuccess"
                                    />
                                </div>

                                <!-- ── Pendiente de resolución (Registrado, sin detalle, sin competencia) ── -->
                                <div
                                    v-else-if="esRegistrado(expediente) && !expediente.tieneDetalle && !expediente.puedeRegistrarCud"
                                    class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-300"
                                >
                                    Este expediente está pendiente de registro del detalle por parte de la instancia competente.
                                </div>

                                <!-- ── Detalle CUD ya registrado ── -->
                                <div
                                    v-if="expediente.tieneDetalle"
                                    class="rounded-xl border bg-card p-5 space-y-3"
                                >
                                    <h3 class="text-sm font-semibold">Detalle registrado</h3>

                                    <!-- Suspensión -->
                                    <template v-if="expediente.suspension">
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Motivo</p>
                                                <p class="font-medium">{{ (expediente.suspension as any).motivo_susp_lab?.descripcion ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Período</p>
                                                <p class="font-medium">{{ (expediente.suspension as any).fechaHoraInicio }} — {{ (expediente.suspension as any).fechaHoraFin }}</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Justificación -->
                                    <template v-if="expediente.justificacion">
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div v-if="(expediente.justificacion as any).motivo_susp_lab">
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Motivo</p>
                                                <p class="font-medium">{{ (expediente.justificacion as any).motivo_susp_lab?.descripcion ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Período</p>
                                                <p class="font-medium">{{ (expediente.justificacion as any).fechaInicio }} — {{ (expediente.justificacion as any).fechaFin }}</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Incapacidad -->
                                    <template v-if="expediente.incapacidad">
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Motivo</p>
                                                <p class="font-medium">{{ (expediente.incapacidad as any).motivo_susp_lab?.descripcion ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">N° certificado</p>
                                                <p class="font-medium">{{ (expediente.incapacidad as any).nroCertificado }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Período</p>
                                                <p class="font-medium">{{ (expediente.incapacidad as any).fechaInicio }} — {{ (expediente.incapacidad as any).fechaFin }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Subsidio</p>
                                                <p class="font-medium">{{ (expediente.incapacidad as any).condicionSubsidio === 'SI' ? 'Subsidiado' : 'No subsidiado' }}</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Exoneración -->
                                    <template v-if="expediente.exoneracion">
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div v-if="(expediente.exoneracion as any).motivo_susp_lab">
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Motivo</p>
                                                <p class="font-medium">{{ (expediente.exoneracion as any).motivo_susp_lab?.descripcion ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-muted-foreground">Período</p>
                                                <p class="font-medium">{{ (expediente.exoneracion as any).fechaInicio }} — {{ (expediente.exoneracion as any).fechaFin }}</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- ── Autorizar / Rechazar (cuando Aprobado y puedeResolver) ── -->
                                <div
                                    v-if="esAprobado(expediente) && expediente.puedeResolver"
                                    class="rounded-xl border bg-card p-5 space-y-3"
                                >
                                    <h3 class="text-sm font-semibold">Autorización</h3>

                                    <p v-if="actionError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                                        {{ actionError }}
                                    </p>

                                    <!-- Input de motivo de rechazo (condicional) -->
                                    <div v-if="showRechazoInput" class="space-y-3">
                                        <div class="grid gap-2">
                                            <Label>Motivo de rechazo</Label>
                                            <Input v-model="motivoRechazo" placeholder="Opcional — razón del rechazo" />
                                        </div>
                                        <div class="flex gap-2">
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                :disabled="actionLoading"
                                                @click="rechazar"
                                            >
                                                <Loader2 v-if="actionLoading" class="mr-1.5 h-3.5 w-3.5 animate-spin" />
                                                Confirmar rechazo
                                            </Button>
                                            <Button variant="outline" size="sm" @click="showRechazoInput = false">
                                                Cancelar
                                            </Button>
                                        </div>
                                    </div>

                                    <!-- Botones principales -->
                                    <div v-else class="flex gap-2">
                                        <Button
                                            size="sm"
                                            :disabled="actionLoading"
                                            class="bg-emerald-600 hover:bg-emerald-700"
                                            @click="autorizar"
                                        >
                                            <Check class="mr-1.5 h-3.5 w-3.5" />
                                            Autorizar
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            :disabled="actionLoading"
                                            class="border-red-200 text-red-700 hover:bg-red-50"
                                            @click="showRechazoInput = true"
                                        >
                                            <XCircle class="mr-1.5 h-3.5 w-3.5" />
                                            Rechazar
                                        </Button>
                                    </div>
                                </div>

                                <!-- ── Pendiente de autorización (Aprobado pero NO puedeResolver) ── -->
                                <div
                                    v-else-if="esAprobado(expediente) && !expediente.puedeResolver"
                                    class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-300"
                                >
                                    Este expediente está pendiente de autorización por parte de la instancia competente.
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
