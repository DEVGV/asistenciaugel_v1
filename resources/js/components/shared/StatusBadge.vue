<script setup lang="ts">
import { Badge } from '@/components/ui/badge';

defineProps<{
    active: boolean;
}>();
</script>

<template>
    <Badge
        v-if="active"
        variant="outline"
        class="rounded-md border-green-200 bg-green-50 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-green-700 uppercase dark:border-green-800 dark:bg-green-950/30 dark:text-green-400"
    >
        Activo
    </Badge>
    <Badge
        v-else
        variant="outline"
        class="rounded-md border-red-200 bg-red-50 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-red-700 uppercase dark:border-red-800 dark:bg-red-950/30 dark:text-red-400"
    >
        Inactivo
    </Badge>
</template>
