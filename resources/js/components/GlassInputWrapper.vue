<script setup lang="ts">
import { cn } from '@/lib/utils';

defineProps<{
    class?: string;
}>();
</script>

<template>
    <div
        :class="
            cn(
                'rounded-2xl border border-border bg-foreground/5 backdrop-blur-md transition-all duration-300',
                'focus-within:border-primary/50 focus-within:bg-primary/5 focus-within:ring-4 focus-within:ring-primary/10',
                $props.class,
            )
        "
    >
        <slot />
    </div>
</template>
