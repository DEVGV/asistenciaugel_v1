<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import {
    CheckCircle2,
    AlertCircle,
    Info,
    AlertTriangle,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import {
    Alert,
    AlertContent,
    AlertDescription,
    AlertTitle,
    AlertIcon,
} from '@/components/ui/alert';

const page = usePage();
const flash = computed(() => (page.props.flash as any) || {});

const alertConfig = ref<{
    type: any;
    title: string;
    message: string;
    icon: any;
} | null>(null);
let timeoutId: any = null;

// Watch para cambios en las props de flash y mostrar la alerta flotante
watch(
    flash,
    (newFlash) => {
        if (newFlash.success) {
            showToast('success', '¡Éxito!', newFlash.success, CheckCircle2);
        } else if (newFlash.error) {
            showToast('destructive', 'Error', newFlash.error, AlertCircle);
        } else if (newFlash.warning) {
            showToast(
                'warning',
                'Advertencia',
                newFlash.warning,
                AlertTriangle,
            );
        } else if (newFlash.info) {
            showToast('info', 'Información', newFlash.info, Info);
        }
    },
    { deep: true, immediate: true },
);

function showToast(type: any, title: string, message: string, icon: any) {
    alertConfig.value = { type, title, message, icon };

    // Limpiar timeout anterior si existe
    if (timeoutId) {
        clearTimeout(timeoutId);
    }

    // Auto-ocultar después de 4 segundos
    timeoutId = setTimeout(() => {
        closeAlert();
    }, 4000);
}

function closeAlert() {
    alertConfig.value = null;

    if (page.props.flash) {
        (page.props.flash as any) = {};
    }
}
</script>

<template>
    <div
        class="pointer-events-none fixed top-4 right-4 z-[100] flex w-full max-w-[400px] flex-col gap-2"
    >
        <transition
            enter-active-class="transition duration-300 ease-out transform"
            enter-from-class="translate-x-full opacity-0"
            enter-to-class="translate-x-0 opacity-100"
            leave-active-class="transition duration-200 ease-in transform"
            leave-from-class="translate-x-0 opacity-100"
            leave-to-class="translate-x-full opacity-0"
        >
            <Alert
                v-if="alertConfig"
                :variant="alertConfig.type"
                appearance="light"
                size="md"
                close
                @close="closeAlert"
                class="pointer-events-auto shadow-lg"
            >
                <AlertIcon>
                    <component :is="alertConfig.icon" />
                </AlertIcon>
                <AlertContent>
                    <AlertTitle>{{ alertConfig.title }}</AlertTitle>
                    <AlertDescription>{{
                        alertConfig.message
                    }}</AlertDescription>
                </AlertContent>
            </Alert>
        </transition>
    </div>
</template>
