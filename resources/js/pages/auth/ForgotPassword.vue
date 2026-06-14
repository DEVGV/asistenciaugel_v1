<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import GlassInputWrapper from '@/components/GlassInputWrapper.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Recuperar contraseña',
        description:
            'Ingresa tu correo para recibir un enlace de restablecimiento',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="¿Olvidaste tu contraseña?" />

    <div
        v-if="status"
        class="animate-element mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form v-bind="email.form()" v-slot="{ errors, processing }">
            <div class="animate-element animate-delay-200">
                <Label
                    for="login"
                    class="ml-1 text-sm font-medium text-muted-foreground"
                    >N° de Documento</Label
                >
                <GlassInputWrapper class="mt-1.5">
                    <Input
                        id="login"
                        type="text"
                        name="login"
                        autocomplete="off"
                        autofocus
                        placeholder="Ingresa tu número de documento"
                        class="h-12 border-0 bg-transparent px-4 shadow-none focus-visible:ring-0"
                    />
                </GlassInputWrapper>
                <InputError :message="errors.login" class="mt-2 ml-1" />
            </div>

            <div class="mt-8">
                <Button
                    class="animate-element animate-delay-300 h-12 w-full rounded-2xl bg-primary py-4 font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all duration-300 hover:bg-primary/90"
                    :disabled="processing"
                >
                    <template v-if="processing">
                        <span class="mr-2 animate-spin">◌</span>
                        Enviando enlace...
                    </template>
                    <template v-else>
                        Enviar enlace de restablecimiento
                    </template>
                </Button>
            </div>
        </Form>

        <div
            class="animate-element animate-delay-400 space-x-1 text-center text-sm text-muted-foreground"
        >
            <span>O, vuelve a</span>
            <TextLink
                :href="login()"
                class="font-medium text-primary transition-colors hover:underline"
                >iniciar sesión</TextLink
            >
        </div>
    </div>
</template>
