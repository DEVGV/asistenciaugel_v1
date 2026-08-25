<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import FormModal from '@/components/shared/FormModal.vue';
import ExpedienteForm from '@/components/tramite/ExpedienteForm.vue';
import type { ExpedienteForm as ExpedienteFormType } from '@/types/models/tramite';

const props = defineProps<{
    show: boolean;
    /** trabajador_id pre-cargado cuando se abre desde el tab de un trabajador. */
    trabajadorId?: number | null;
    /** IE del contexto — carga el personal activo de esa IE. */
    institucionId?: number | null;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
    (e: 'success'): void;
}>();

const form = useForm<ExpedienteFormType>({
    tipoExpediente: '',
    trabajador_id: props.trabajadorId ?? null,
    altaTrabajador_id: null,
    asunto: '',
    fecha: new Date().toISOString().slice(0, 10),
    observacion: '',
    documentos: [
        { documento_id: null, nroDoc: '', fechaDoc: '', observacion: '', archivo: null },
    ],
});

watch(
    () => props.show,
    (val) => {
        if (!val) {
            form.reset();
            form.clearErrors();
            if (props.trabajadorId) {
                form.trabajador_id = props.trabajadorId;
            }
        }
    },
);

watch(
    () => props.trabajadorId,
    (id) => {
        form.trabajador_id = id ?? null;
        form.altaTrabajador_id = null;
    },
);

function submit() {
    form.post('/expedientes', {
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
        title="Nuevo Expediente"
        description="Registre el expediente y sus documentos asociados."
        :processing="form.processing"
        submit-text="Registrar"
        max-width="2xl"
        @update:show="emit('update:show', $event)"
        @submit="submit"
    >
        <ExpedienteForm
            :form="form as any"
            :trabajador-fijo="!!props.trabajadorId"
            :trabajador-id="props.trabajadorId ?? null"
            :institucion-id="props.institucionId"
            @submit="submit"
        />
    </FormModal>
</template>
