<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="alert-content"
    :class="cn('space-y-2 [&_[data-slot=alert-title]]:font-semibold', props.class)"
  >
    <slot />
  </div>
</template>
