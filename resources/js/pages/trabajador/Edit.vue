<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2 } from 'lucide-vue-next';
import TrabajadorController from '@/actions/App/Http/Controllers/Trabajador/TrabajadorController';
import TrabajadorForm from '@/components/trabajador/TrabajadorForm.vue';
import { Button } from '@/components/ui/button';
import type { Trabajador } from '@/types/models/trabajador';

const props = defineProps<{
    trabajador: Trabajador;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Trabajadores', href: TrabajadorController.index().url },
            {
                title: 'Editar Trabajador',
                href: '#',
            },
        ],
    },
});

const form = useForm({
    activo: props.trabajador.activo,
});

function submitUpdate() {
    form.put(TrabajadorController.update({ trabajador: props.trabajador.id }).url);
}
</script>

<template>
    <Head :title="`Editar Trabajador ${props.trabajador.codigo}`" />

    <div class="mx-auto max-w-2xl p-4 sm:p-6">
        <!-- Encabezado -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Editar Trabajador
                </h1>
                <p class="text-sm text-muted-foreground">
                    Modifique los datos de asignación del código del trabajador.
                </p>
            </div>
            <Button variant="outline" as-child size="sm">
                <Link :href="TrabajadorController.index().url">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Volver al Listado
                </Link>
            </Button>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="rounded-xl border bg-card p-6 shadow-xs">
            <form @submit.prevent="submitUpdate" class="space-y-4">
                <TrabajadorForm
                    :form="form"
                    :persona="props.trabajador.persona"
                />

                <div class="pt-4 border-t flex justify-end gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        as-child
                    >
                        <Link :href="TrabajadorController.index().url">
                            Cancelar
                        </Link>
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                    >
                        <CheckCircle2 class="mr-2 h-4 w-4" />
                        {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
