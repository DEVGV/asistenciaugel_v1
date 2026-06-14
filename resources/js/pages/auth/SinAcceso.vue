<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ShieldX, LogOut } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        title: 'Sin acceso',
        description:
            'Tu cuenta no tiene acceso a ninguna institución educativa',
    },
});

function cerrarSesion() {
    router.post('/logout');
}
</script>

<template>
    <Head title="Sin Acceso" />

    <div class="flex flex-col items-center gap-6 text-center">
        <div
            class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400"
        >
            <ShieldX class="h-8 w-8" />
        </div>

        <div class="space-y-2">
            <h2 class="text-lg font-semibold">Sin acceso asignado</h2>
            <p class="max-w-sm text-sm text-muted-foreground">
                Tu cuenta de usuario no tiene perfiles ni instituciones
                educativas asignadas. Contacta al administrador de tu UGEL para
                que te asigne acceso.
            </p>
        </div>

        <Button variant="outline" @click="cerrarSesion">
            <LogOut class="mr-2 h-4 w-4" />
            Cerrar Sesión
        </Button>
    </div>
</template>
