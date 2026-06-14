<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ElegantShape from '@/components/auth/ElegantShape.vue';
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
        <!-- Left column: sign-in form with Shape Hero backdrop -->
        <section
            class="relative flex flex-1 items-center justify-center overflow-hidden bg-slate-50/50 p-6 md:p-8 dark:bg-[#030303]"
        >
            <!-- Radial Backdrop Gradient & Blur -->
            <div
                class="pointer-events-none absolute inset-0 bg-linear-to-br from-indigo-500/[0.03] via-transparent to-rose-500/[0.03] blur-3xl"
            />

            <!-- Floating shapes background -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <!-- Tall rectangle - top left -->
                <ElegantShape
                    :borderRadius="24"
                    class="top-[-10%] left-[-15%]"
                    :delay="0.3"
                    gradient="from-indigo-500/[0.15] dark:from-indigo-500/[0.25]"
                    :height="500"
                    :rotate="-8"
                    :width="300"
                />

                <!-- Wide rectangle - bottom right -->
                <ElegantShape
                    :borderRadius="20"
                    class="right-[-20%] bottom-[-5%]"
                    :delay="0.5"
                    gradient="from-rose-500/[0.15] dark:from-rose-500/[0.25]"
                    :height="200"
                    :rotate="15"
                    :width="600"
                />

                <!-- Square - middle left -->
                <ElegantShape
                    :borderRadius="32"
                    class="top-[40%] left-[-10%]"
                    :delay="0.4"
                    gradient="from-violet-500/[0.15] dark:from-violet-500/[0.25]"
                    :height="250"
                    :rotate="24"
                    :width="250"
                />

                <!-- Small rectangle - top right -->
                <ElegantShape
                    :borderRadius="12"
                    class="top-[5%] right-[5%]"
                    :delay="0.6"
                    gradient="from-amber-500/[0.15] dark:from-amber-500/[0.25]"
                    :height="80"
                    :rotate="-20"
                    :width="200"
                />

                <!-- Medium rectangle - center right -->
                <ElegantShape
                    :borderRadius="16"
                    class="top-[50%] right-[-15%]"
                    :delay="0.7"
                    gradient="from-emerald-500/[0.15] dark:from-emerald-500/[0.25]"
                    :height="120"
                    :rotate="30"
                    :width="300"
                />

                <!-- Small square - bottom left -->
                <ElegantShape
                    :borderRadius="28"
                    class="bottom-[10%] left-[10%]"
                    :delay="0.2"
                    gradient="from-blue-500/[0.15] dark:from-blue-500/[0.25]"
                    :height="180"
                    :rotate="-25"
                    :width="180"
                />
            </div>

            <!-- Glassmorphic Card Container -->
            <div
                class="relative z-10 max-h-full w-full max-w-md overflow-y-auto rounded-3xl border border-white/20 bg-white/40 p-6 shadow-xl backdrop-blur-xl md:p-8 dark:border-zinc-800/30 dark:bg-zinc-950/40"
            >
                <div class="flex flex-col gap-6">
                    <div class="animate-element animate-delay-100 mb-1">
                        <Link :href="home()" class="inline-block">
                            <AppLogoIcon
                                class="size-10 fill-current text-foreground"
                            />
                        </Link>
                    </div>

                    <h1
                        class="animate-element animate-delay-100 text-3xl leading-tight font-semibold tracking-tighter md:text-4xl"
                    >
                        {{ title || 'Bienvenido' }}
                    </h1>
                    <p
                        class="animate-element animate-delay-200 text-sm text-muted-foreground"
                    >
                        {{
                            description ||
                            'Accede a tu cuenta y continúa tu jornada con nosotros'
                        }}
                    </p>

                    <div class="mt-1">
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

<style scoped>
.font-pacifico {
    font-family: 'Pacifico', cursive;
}
</style>
