<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Building2, ChevronsUpDown, School } from 'lucide-vue-next';
import { computed } from 'vue';
import { seleccionar } from '@/routes/contexto';
import type { ContextoActivo } from '@/types';

const page = usePage();

const contexto = computed(() => page.props.contexto as ContextoActivo | null);

const etiquetaIe = computed(() => {
    if (!contexto.value) {
        return '';
    }

    return contexto.value.ie?.nombre ?? 'Todas las IEs';
});
</script>

<template>
    <div v-if="contexto?.ugel" class="flex min-w-0 items-center">
        <component
            :is="contexto.multiple ? Link : 'div'"
            :href="contexto.multiple ? seleccionar.url() : undefined"
            class="flex min-w-0 items-center gap-2 rounded-lg border bg-card px-3 py-1.5 text-sm"
            :class="
                contexto.multiple
                    ? 'cursor-pointer transition-colors hover:bg-accent'
                    : ''
            "
            :title="`${contexto.ugel.nombre} · ${etiquetaIe}`"
        >
            <Building2 class="size-4 shrink-0 text-primary" />
            <span class="hidden max-w-40 truncate font-medium md:inline">
                {{ contexto.ugel.nombre }}
            </span>
            <span class="hidden text-muted-foreground md:inline">·</span>
            <School class="size-4 shrink-0 text-muted-foreground md:hidden" />
            <span class="max-w-48 truncate text-muted-foreground">
                {{ etiquetaIe }}
            </span>
            <ChevronsUpDown
                v-if="contexto.multiple"
                class="size-3.5 shrink-0 text-muted-foreground"
            />
        </component>
    </div>
</template>
