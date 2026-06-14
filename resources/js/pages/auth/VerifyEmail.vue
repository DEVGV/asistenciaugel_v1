<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { logout } from '@/routes';

const send = {
    form: () => ({
        action: '/email/verification-notification',
        method: 'post' as const,
    }),
};

defineOptions({
    layout: {
        title: 'Verifica tu correo',
        description:
            'Por favor, verifica tu dirección de correo haciendo clic en el enlace que acabamos de enviarte.',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Verificación de correo" />

    <div
        v-if="status === 'verification-link-sent'"
        class="animate-element mb-6 text-center text-sm font-medium text-green-600"
    >
        Se ha enviado un nuevo enlace de verificación a la dirección de correo
        que proporcionaste.
    </div>

    <Form
        v-bind="send.form()"
        class="flex flex-col gap-6"
        v-slot="{ processing }"
    >
        <div class="space-y-4">
            <Button
                type="submit"
                class="animate-element animate-delay-200 h-12 w-full rounded-2xl bg-primary py-4 font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all duration-300 hover:bg-primary/90"
                :disabled="processing"
            >
                <template v-if="processing">
                    <span class="mr-2 animate-spin">◌</span>
                    Reenviando...
                </template>
                <template v-else> Reenviar correo de verificación </template>
            </Button>

            <TextLink
                :href="logout()"
                as="button"
                class="animate-element animate-delay-300 mx-auto block text-sm text-muted-foreground transition-colors hover:text-primary"
            >
                Cerrar sesión
            </TextLink>
        </div>
    </Form>
</template>
