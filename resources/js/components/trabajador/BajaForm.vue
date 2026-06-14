<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import FormModal from '@/components/shared/FormModal.vue';
import ParamSelect from '@/components/shared/ParamSelect.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import altasRoutes from '@/routes/altas';
import type { AltaTrabajador } from '@/types/models/trabajador';

const props = defineProps<{
    show: boolean;
    alta: AltaTrabajador;
}>();

const emit = defineEmits<{
    (e: 'update:show', value: boolean): void;
}>();

const form = useForm({
    fechaBaja: '',
    motivoBaja_id: null as number | null,
});

watch(
    () => props.show,
    (visible) => {
        if (!visible) {
            form.reset();
            form.clearErrors();
        }
    },
);

function submit() {
    form.post(altasRoutes.baja({ alta: props.alta.id }).url, {
        onSuccess: () => emit('update:show', false),
    });
}
</script>

<template>
    <FormModal
        :show="props.show"
        title="Registrar Baja"
        :description="`Dar de baja al trabajador en ${(props.alta.institucion_educativa || props.alta.institucionEducativa)?.nombreLegal ?? 'la institución'}.`"
        :processing="form.processing"
        @update:show="emit('update:show', $event)"
        @submit="submit"
    >
        <div class="grid gap-4">
            <div class="rounded-md border bg-muted/30 p-3 text-sm">
                <p class="font-medium">Información del Alta</p>
                <p class="mt-1 text-muted-foreground">
                    Inicio:
                    <span class="font-medium text-foreground">{{
                        props.alta.fechaInicio
                    }}</span>
                    &nbsp;·&nbsp; IE:
                    <span class="font-medium text-foreground">{{
                        (
                            props.alta.institucion_educativa ||
                            props.alta.institucionEducativa
                        )?.nombreLegal ?? '-'
                    }}</span>
                </p>
            </div>

            <div class="grid gap-2">
                <Label>Fecha de Baja *</Label>
                <Input
                    v-model="form.fechaBaja"
                    type="date"
                    :class="{ 'border-destructive': form.errors.fechaBaja }"
                    :min="props.alta.fechaInicio"
                />
                <p
                    v-if="form.errors.fechaBaja"
                    class="text-sm text-destructive"
                >
                    {{ form.errors.fechaBaja }}
                </p>
            </div>

            <ParamSelect
                type="motivos-baja"
                label="Motivo de Baja *"
                v-model="form.motivoBaja_id"
                placeholder="Seleccionar motivo..."
                :error="form.errors.motivoBaja_id"
            />
        </div>
    </FormModal>
</template>
