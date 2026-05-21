<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img
        src="/img/dre_icon.png"
        alt="Logo Icon"
        :class="className"
        v-bind="$attrs"
    />
</template>
