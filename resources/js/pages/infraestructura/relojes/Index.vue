<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MapPin, Clock } from 'lucide-vue-next';
import LocalController from '@/actions/App/Http/Controllers/Infraestructura/LocalController';
import StatusBadge from '@/components/shared/StatusBadge.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Infraestructura', href: '#' },
            { title: 'Relojes', href: '#' },
        ],
    },
});
</script>

<template>
    <Head title="Relojes" />

    <div class="flex flex-col gap-4 p-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Relojes Biométricos</h1>
            <p class="text-sm text-muted-foreground">
                Los relojes se gestionan desde la vista detalle de cada Institución Educativa, en el tab "Relojes".
            </p>
        </div>

        <div class="rounded-md border p-8 text-center text-muted-foreground">
            <Clock class="mx-auto h-12 w-12 mb-3 text-muted-foreground/50" />
            <p class="text-sm">Para gestionar relojes, ingrese a una Institución Educativa y use el tab <strong>"Relojes"</strong>.</p>
        </div>
    </div>
</template>
