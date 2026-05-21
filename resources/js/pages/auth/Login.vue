<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import GlassInputWrapper from '@/components/GlassInputWrapper.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Bienvenido de nuevo',
        description: 'Accede a tu cuenta y continúa tu jornada con nosotros',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Iniciar sesión" />

    <div
        v-if="status"
        class="animate-element mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="space-y-5">
            <!-- Email Field -->
            <div class="animate-element animate-delay-300">
                <Label
                    for="email"
                    class="ml-1 text-sm font-medium text-muted-foreground"
                    >Correo Electrónico</Label
                >
                <GlassInputWrapper class="mt-1.5">
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="nombre@ejemplo.com"
                        class="h-12 border-0 bg-transparent px-4 shadow-none focus-visible:ring-0"
                    />
                </GlassInputWrapper>
                <InputError :message="errors.email" class="mt-2 ml-1" />
            </div>

            <!-- Password Field -->
            <div class="animate-element animate-delay-400">
                <div class="ml-1 flex items-center justify-between">
                    <Label
                        for="password"
                        class="text-sm font-medium text-muted-foreground"
                        >Contraseña</Label
                    >
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs text-primary transition-colors hover:underline"
                        :tabindex="5"
                    >
                        ¿Olvidaste tu contraseña?
                    </TextLink>
                </div>
                <GlassInputWrapper class="mt-1.5">
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Ingresa tu contraseña"
                        class="h-12 border-0 bg-transparent px-4 shadow-none focus-visible:ring-0"
                    />
                </GlassInputWrapper>
                <InputError :message="errors.password" class="mt-2 ml-1" />
            </div>

            <!-- Remember Me & Actions -->
            <div
                class="animate-element animate-delay-500 flex items-center justify-between px-1 text-sm"
            >
                <Label
                    for="remember"
                    class="flex cursor-pointer items-center gap-3"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="h-5 w-5 rounded-lg"
                    />
                    <span class="text-foreground/80"
                        >Mantener sesión iniciada</span
                    >
                </Label>
            </div>

            <!-- Sign In Button -->
            <Button
                type="submit"
                class="animate-element animate-delay-600 h-12 w-full rounded-2xl bg-primary py-4 font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all duration-300 hover:bg-primary/90"
                :tabindex="4"
                :disabled="processing"
            >
                <template v-if="processing">
                    <span class="mr-2 animate-spin">◌</span>
                    Iniciando sesión...
                </template>
                <template v-else> Iniciar Sesión </template>
            </Button>
        </div>
    </Form>
</template>
