<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import GlassInputWrapper from '@/components/GlassInputWrapper.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { store } from '@/routes/password/confirm';

defineOptions({
    layout: {
        title: 'Confirma tu contraseña',
        description:
            'Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.',
    },
});
</script>

<template>
    <Head title="Confirmar contraseña" />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="space-y-4">
            <!-- Password Field -->
            <div class="animate-element animate-delay-200">
                <Label
                    for="password"
                    class="ml-1 text-sm font-medium text-muted-foreground"
                    >Contraseña</Label
                >
                <GlassInputWrapper class="mt-1.5">
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        autofocus
                        placeholder="Ingresa tu contraseña"
                        class="h-12 border-0 bg-transparent px-4 shadow-none focus-visible:ring-0"
                    />
                </GlassInputWrapper>
                <InputError :message="errors.password" class="mt-2 ml-1" />
            </div>

            <!-- Confirm Button -->
            <Button
                type="submit"
                class="animate-element animate-delay-300 mt-4 h-12 w-full rounded-2xl bg-primary py-4 font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all duration-300 hover:bg-primary/90"
                :disabled="processing"
            >
                <template v-if="processing">
                    <span class="mr-2 animate-spin">◌</span>
                    Confirmando...
                </template>
                <template v-else> Confirmar Contraseña </template>
            </Button>
        </div>
    </Form>
</template>
