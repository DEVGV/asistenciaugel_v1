<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Paperclip } from 'lucide-vue-next';
import { computed, watch } from 'vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { AltaPermisoOption } from '@/types/models/tramite';

const props = defineProps<{
    show: boolean;
    /** Altas seleccionables (del trabajador o de la IE según el contexto). */
    altas: AltaPermisoOption[];
    /** Etiqueta del select de altas según contexto. */
    altaLabel?: string;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'success'): void;
}>();

const form = useForm({
    trabajador_id: null as number | null,
    altaTrabajador_id: null as number | null,
    tipo: 'J' as 'J' | 'E',
    asunto: '',
    fechaInicio: '',
    fechaFin: '',
    marcaApli: 'E' as 'E' | 'S' | 'ES',
    turno: null as number | null,
    documento_id: null as number | null,
    nroDoc: '',
    observacion: '',
    sustento: null as File | null,
});

const esExoneracion = computed(() => form.tipo === 'E');

watch(
    () => props.show,
    (val) => {
        if (!val) {
            form.reset();
            form.clearErrors();
        }
    },
);

watch(
    () => form.altaTrabajador_id,
    (altaId) => {
        const alta = props.altas.find((a) => a.id === altaId);
        form.trabajador_id = alta?.trabajador_id ?? null;
    },
);

// Por defecto, fechaFin = fechaInicio
watch(
    () => form.fechaInicio,
    (fecha) => {
        if (fecha && !form.fechaFin) {
            form.fechaFin = fecha;
        }
    },
);

function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    form.sustento = target.files?.[0] ?? null;
}

function submit() {
    form.post('/permisos', {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit('update:show', false);
            emit('success');
        },
    });
}
</script>

<template>
    <FormModal
        :show="props.show"
        title="Nueva Solicitud de Permiso"
        description="Registre la solicitud y adjunte el documento de sustento."
        :processing="form.processing"
        submit-text="Registrar Solicitud"
        max-width="2xl"
        @update:show="emit('update:show', $event)"
        @submit="submit"
    >
        <div class="space-y-4">
            <!-- Alta / vínculo laboral -->
            <div class="grid gap-2">
                <Label>{{ props.altaLabel ?? 'Vínculo laboral' }} *</Label>
                <select
                    v-model="form.altaTrabajador_id"
                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                    :class="{
                        'border-destructive': form.errors.altaTrabajador_id,
                    }"
                >
                    <option :value="null" disabled>Seleccione…</option>
                    <option
                        v-for="alta in props.altas"
                        :key="alta.id"
                        :value="alta.id"
                    >
                        {{ alta.label
                        }}{{ alta.cargo ? ` — ${alta.cargo}` : '' }}
                    </option>
                </select>
                <p
                    v-if="
                        form.errors.altaTrabajador_id ||
                        form.errors.trabajador_id
                    "
                    class="text-sm text-destructive"
                >
                    {{
                        form.errors.altaTrabajador_id ||
                        form.errors.trabajador_id
                    }}
                </p>
            </div>

            <!-- Tipo de permiso -->
            <div class="grid gap-2">
                <Label>Tipo de permiso *</Label>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        class="rounded-md border px-3 py-2 text-left text-sm transition-colors"
                        :class="
                            form.tipo === 'J'
                                ? 'border-primary bg-primary/5 font-medium text-primary'
                                : 'text-muted-foreground hover:bg-muted/40'
                        "
                        @click="form.tipo = 'J'"
                    >
                        Permiso por día(s)
                        <span class="block text-xs font-normal opacity-70">
                            Justificación de uno o varios días
                        </span>
                    </button>
                    <button
                        type="button"
                        class="rounded-md border px-3 py-2 text-left text-sm transition-colors"
                        :class="
                            form.tipo === 'E'
                                ? 'border-primary bg-primary/5 font-medium text-primary'
                                : 'text-muted-foreground hover:bg-muted/40'
                        "
                        @click="form.tipo = 'E'"
                    >
                        Exoneración de marcación
                        <span class="block text-xs font-normal opacity-70">
                            Marca de entrada y/o salida
                        </span>
                    </button>
                </div>
                <p v-if="form.errors.tipo" class="text-sm text-destructive">
                    {{ form.errors.tipo }}
                </p>
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-2">
                    <Label>Fecha inicio *</Label>
                    <Input
                        v-model="form.fechaInicio"
                        type="date"
                        :class="{
                            'border-destructive': form.errors.fechaInicio,
                        }"
                    />
                    <p
                        v-if="form.errors.fechaInicio"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.fechaInicio }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label>Fecha fin *</Label>
                    <Input
                        v-model="form.fechaFin"
                        type="date"
                        :class="{ 'border-destructive': form.errors.fechaFin }"
                    />
                    <p
                        v-if="form.errors.fechaFin"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.fechaFin }}
                    </p>
                </div>
            </div>

            <!-- Marca aplicable (solo exoneración) + turno -->
            <div class="grid grid-cols-2 gap-4">
                <div v-if="esExoneracion" class="grid gap-2">
                    <Label>Marca a exonerar *</Label>
                    <select
                        v-model="form.marcaApli"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                        :class="{
                            'border-destructive': form.errors.marcaApli,
                        }"
                    >
                        <option value="E">Entrada</option>
                        <option value="S">Salida</option>
                        <option value="ES">Entrada y Salida</option>
                    </select>
                    <p
                        v-if="form.errors.marcaApli"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.marcaApli }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <ParamSelect
                        v-model="form.turno"
                        type="turnos"
                        label="Turno (opcional)"
                        placeholder="Todos los turnos"
                        :error="form.errors.turno"
                    />
                </div>
            </div>

            <!-- Motivo -->
            <div class="grid gap-2">
                <Label>Motivo del permiso *</Label>
                <Input
                    v-model="form.asunto"
                    placeholder="Ej.: Permiso por salud, motivos particulares, capacitación…"
                    :class="{ 'border-destructive': form.errors.asunto }"
                />
                <p v-if="form.errors.asunto" class="text-sm text-destructive">
                    {{ form.errors.asunto }}
                </p>
            </div>

            <!-- Sustento -->
            <div class="rounded-lg border bg-muted/20 p-4">
                <div class="mb-3 flex items-center gap-2">
                    <Paperclip class="h-4 w-4 text-primary" />
                    <h4 class="text-sm font-semibold">
                        Documento de Sustento
                    </h4>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <ParamSelect
                            v-model="form.documento_id"
                            type="documentos"
                            label="Tipo de documento *"
                            placeholder="Seleccione…"
                            :error="form.errors.documento_id"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label>N° de documento</Label>
                        <Input
                            v-model="form.nroDoc"
                            placeholder="Opcional"
                            :class="{ 'border-destructive': form.errors.nroDoc }"
                        />
                    </div>
                </div>
                <div class="mt-3 grid gap-2">
                    <Label>Archivo (PDF, JPG o PNG — máx. 5 MB) *</Label>
                    <input
                        type="file"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs file:mr-3 file:rounded file:border-0 file:bg-primary/10 file:px-2 file:py-1 file:text-xs file:font-medium file:text-primary"
                        :class="{ 'border-destructive': form.errors.sustento }"
                        @change="onFileChange"
                    />
                    <p
                        v-if="form.errors.sustento"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.sustento }}
                    </p>
                </div>
            </div>

            <!-- Observación -->
            <div class="grid gap-2">
                <Label>Observación</Label>
                <Input
                    v-model="form.observacion"
                    placeholder="Opcional"
                    :class="{ 'border-destructive': form.errors.observacion }"
                />
                <p
                    v-if="form.errors.observacion"
                    class="text-sm text-destructive"
                >
                    {{ form.errors.observacion }}
                </p>
            </div>
        </div>
    </FormModal>
</template>
