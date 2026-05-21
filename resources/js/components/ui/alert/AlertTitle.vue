<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div data-slot="alert-title" :class="cn('grow tracking-tight', props.class)">
    <slot />
  </div>
</template>
