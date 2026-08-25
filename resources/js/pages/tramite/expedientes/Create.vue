<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import ExpedienteForm from '@/components/tramite/ExpedienteForm.vue';
import type { ExpedienteForm as ExpedienteFormType } from '@/types/models/tramite';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Expedientes', href: '/expedientes' },
            { title: 'Nuevo expediente', href: '/expedientes/create' },
        ],
    },
});

const form = useForm<ExpedienteFormType>({
    tipoExpediente: '',
    trabajador_id: null,
    asunto: '',
    fecha: new Date().toISOString().slice(0, 10),
    observacion: '',
    documentos: [
        { documento_id: null, nroDoc: '', fechaDoc: '', observacion: '' },
    ],
});

function submit() {
    form.post('/expedientes', {
        preserveScroll: true,
        onError: () => {},
    });
}
</script>

<template>
    <Head title="Nuevo expediente" />

    <div class="mx-auto max-w-3xl space-y-6 p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Nuevo expediente</h1>
            <Button as="a" href="/expedientes" variant="outline" size="sm">
                Cancelar
            </Button>
        </div>

        <div class="rounded-xl border bg-card p-6 shadow-xs">
            <ExpedienteForm :form="form as any" @submit="submit" />

            <div class="mt-6 flex justify-end">
                <Button :disabled="form.processing" @click="submit">
                    {{ form.processing ? 'Registrando…' : 'Registrar expediente' }}
                </Button>
            </div>
        </div>
    </div>
</template>
