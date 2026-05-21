<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const props = withDefaults(
    defineProps<{
        title?: string;
        description?: string;
    }>(),
    {},
);
</script>

<template>
    <div
        class="flex h-[100dvh] w-[100dvw] flex-col overflow-hidden bg-background font-sans md:flex-row"
    >
        <!-- Left column: sign-in form -->
        <section
            class="flex flex-1 items-center justify-center overflow-y-auto p-8"
        >
            <div class="w-full max-w-md">
                <div class="flex flex-col gap-6">
                    <div class="animate-element animate-delay-100 mb-2">
                        <Link :href="home()" class="inline-block">
                            <AppLogoIcon
                                class="size-10 fill-current text-foreground"
                            />
                        </Link>
                    </div>

                    <h1
                        class="animate-element animate-delay-100 text-4xl leading-tight font-semibold tracking-tighter md:text-5xl"
                    >
                        {{ title || 'Bienvenido' }}
                    </h1>
                    <p
                        class="animate-element animate-delay-200 text-muted-foreground"
                    >
                        {{
                            description ||
                            'Accede a tu cuenta y continúa tu jornada con nosotros'
                        }}
                    </p>

                    <div class="mt-2">
                        <slot />
                    </div>
                </div>
            </div>
        </section>

        <!-- Right column: background image + logo -->
        <section class="relative hidden flex-1 p-4 md:block">
            <!-- Background Image -->
            <div
                class="animate-slide-right animate-delay-300 absolute inset-4 overflow-hidden rounded-3xl bg-cover bg-center shadow-2xl"
                style="background-image: url('/img/fondo_1.jpeg')"
            >
                <!-- Gradient from white to transparent (light mode) / dark to transparent (dark mode) -->
                <div
                    class="absolute inset-0 bg-gradient-to-b from-white via-white/70 to-transparent dark:from-zinc-950 dark:via-zinc-950/80 dark:to-transparent"
                ></div>

                <!-- Logo overlay -->
                <div class="absolute inset-x-0 top-0 flex justify-center pt-8">
                    <img
                        src="/img/dre_logo.png"
                        alt="Logo DRE"
                        class="animate-element animate-delay-1000 z-10 w-full max-w-md px-6 drop-shadow-xl lg:max-w-lg xl:max-w-xl"
                    />
                </div>
            </div>
        </section>
    </div>
</template>
