<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { alertVariants, type AlertVariants } from '.'
import { Button } from '@/components/ui/button'
import { X } from 'lucide-vue-next'

const props = withDefaults(defineProps<{
  class?: HTMLAttributes['class']
  variant?: AlertVariants['variant']
  size?: AlertVariants['size']
  icon?: AlertVariants['icon']
  appearance?: AlertVariants['appearance']
  close?: boolean
}>(), {
  variant: 'secondary',
  size: 'md',
  appearance: 'solid',
  close: false,
})

const emit = defineEmits<{
  (e: 'close'): void
}>()
</script>

<template>
  <div
    data-slot="alert"
    role="alert"
    :class="cn(alertVariants({ variant, size, icon, appearance }), props.class)"
  >
    <slot />
    <Button
      v-if="close"
      size="sm"
      variant="ghost"
      @click="emit('close')"
      aria-label="Dismiss"
      data-slot="alert-close"
      :class="cn('group shrink-0 size-6 p-0 h-auto')"
    >
      <X class="opacity-60 group-hover:opacity-100 size-4" />
    </Button>
  </div>
</template>
