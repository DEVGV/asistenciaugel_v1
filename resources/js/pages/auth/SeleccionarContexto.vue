<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Building2, Check, School } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { establecer } from '@/routes/contexto';
import type { ContextoUgelOpcion } from '@/types';

defineOptions({
    layout: {
        title: 'Selecciona tu contexto de trabajo',
        description:
            'Elige la UGEL y la institución educativa con la que deseas trabajar',
    },
});

const props = defineProps<{
    opciones: ContextoUgelOpcion[];
    actual: { ugel_id: number | null; ie_id: number | null };
}>();

const enviando = ref(false);

function esActual(ugelId: number, ieId: number | null): boolean {
    return props.actual.ugel_id === ugelId && props.actual.ie_id === ieId;
}

function seleccionar(ugelId: number, ieId: number | null): void {
    if (enviando.value) {
        return;
    }

    enviando.value = true;

    router.post(
        establecer.url(),
        { ugel_id: ugelId, ie_id: ieId },
        { onFinish: () => (enviando.value = false) },
    );
}
</script>

<template>
    <Head title="Seleccionar contexto" />

    <div class="flex flex-col gap-6">
        <div
            v-if="opciones.length === 0"
            class="rounded-lg border border-dashed p-6 text-center text-sm text-muted-foreground"
        >
            No tienes asignaciones activas. Contacta al administrador del
            sistema.
        </div>

        <div
            v-for="ugel in opciones"
            :key="ugel.id"
            class="animate-element rounded-xl border bg-card p-4 shadow-sm"
        >
            <div class="mb-3 flex items-center gap-2">
                <Building2 class="size-5 shrink-0 text-primary" />
                <h3 class="text-sm font-semibold">{{ ugel.nombre }}</h3>
                <Badge v-if="ugel.esAdmin" variant="secondary"
                    >Administrador</Badge
                >
            </div>

            <div class="flex flex-col gap-2">
                <!-- Opción "Todas las IEs" para administradores de la UGEL -->
                <button
                    v-if="ugel.esAdmin"
                    type="button"
                    :disabled="enviando"
                    class="flex w-full items-center justify-between rounded-lg border p-3 text-left text-sm transition-colors hover:border-primary hover:bg-accent disabled:opacity-50"
                    :class="{
                        'border-primary bg-accent': esActual(ugel.id, null),
                    }"
                    @click="seleccionar(ugel.id, null)"
                >
                    <span class="flex items-center gap-2">
                        <Building2 class="size-4 text-muted-foreground" />
                        <span class="font-medium"
                            >Todas las IEs de esta UGEL</span
                        >
                    </span>
                    <Check
                        v-if="esActual(ugel.id, null)"
                        class="size-4 text-primary"
                    />
                </button>

                <!-- IEs asignadas explícitamente -->
                <button
                    v-for="ie in ugel.ies"
                    :key="ie.id"
                    type="button"
                    :disabled="enviando"
                    class="flex w-full items-center justify-between rounded-lg border p-3 text-left text-sm transition-colors hover:border-primary hover:bg-accent disabled:opacity-50"
                    :class="{
                        'border-primary bg-accent': esActual(ugel.id, ie.id),
                    }"
                    @click="seleccionar(ugel.id, ie.id)"
                >
                    <span class="flex min-w-0 items-center gap-2">
                        <School class="size-4 shrink-0 text-muted-foreground" />
                        <span class="min-w-0">
                            <span class="block truncate font-medium">{{
                                ie.nombre
                            }}</span>
                            <span class="block text-xs text-muted-foreground">
                                <template v-if="ie.codigoModular"
                                    >Cód. modular
                                    {{ ie.codigoModular }}</template
                                >
                                <template v-if="ie.codigoModular && ie.perfil">
                                    ·
                                </template>
                                <template v-if="ie.perfil">{{
                                    ie.perfil
                                }}</template>
                            </span>
                        </span>
                    </span>
                    <Check
                        v-if="esActual(ugel.id, ie.id)"
                        class="size-4 shrink-0 text-primary"
                    />
                </button>
            </div>
        </div>
    </div>
</template>
