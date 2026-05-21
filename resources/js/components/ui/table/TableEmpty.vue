<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
  colspan?: number
}>()
</script>

<template>
  <tr data-slot="table-empty">
    <td
      :colspan="colspan"
      :class="cn('text-muted-foreground p-4 text-center text-sm', props.class)"
    >
      <slot />
    </td>
  </tr>
</template>
