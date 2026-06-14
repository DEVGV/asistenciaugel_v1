<script setup lang="ts">
import { ref, onMounted } from 'vue';

const props = withDefaults(
    defineProps<{
        delay?: number;
        width?: number;
        height?: number;
        rotate?: number;
        gradient?: string;
        borderRadius?: number;
    }>(),
    {
        delay: 0,
        width: 400,
        height: 100,
        rotate: 0,
        gradient: 'from-white/[0.08]',
        borderRadius: 16,
    },
);

const isMounted = ref(false);

onMounted(() => {
    // Small delay to trigger the CSS transition
    setTimeout(() => {
        isMounted.value = true;
    }, 50);
});
</script>

<template>
    <div
        class="absolute transition-all duration-[2400ms] ease-[cubic-bezier(0.23,0.86,0.39,0.96)]"
        :style="{
            transform: isMounted
                ? `translateY(0) rotate(${props.rotate}deg)`
                : `translateY(-150px) rotate(${props.rotate - 15}deg)`,
            opacity: isMounted ? 1 : 0,
            transitionDelay: `${props.delay}s`,
            transitionProperty: 'transform, opacity',
        }"
    >
        <!-- Infinite Floating Wrapper -->
        <div
            class="animate-float"
            :style="{
                width: `${props.width}px`,
                height: `${props.height}px`,
            }"
        >
            <div
                class="absolute inset-0 bg-linear-to-r to-transparent shadow-[0_2px_16px_-2px_rgba(255,255,255,0.04)] ring-1 ring-white/[0.03] backdrop-blur-[1px] after:absolute after:inset-0 after:rounded-[inherit] after:bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.12),transparent_70%)] dark:ring-white/[0.02]"
                :class="props.gradient"
                :style="{ borderRadius: `${props.borderRadius}px` }"
            />
        </div>
    </div>
</template>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(15px);
    }
}

.animate-float {
    animation: float 12s ease-in-out infinite;
}
</style>
