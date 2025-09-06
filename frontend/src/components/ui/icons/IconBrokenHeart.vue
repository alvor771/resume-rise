<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    xmlns="http://www.w3.org/2000/svg"
    :aria-label="ariaLabel || 'Broken heart icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
    :stroke="color"
  >
    <!-- Main heart shape -->
    <path
      d="M20.8 7.6a5.5 5.5 0 0 0-7.8 0L12 8.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 22l7.8-5.6 1-1a5.5 5.5 0 0 0 0-7.8z"
      :fill="isFilled ? color : isDuotone ? secondaryColor : 'none'"
      :fill-opacity="isDuotone ? 0.2 : isFilled ? 1 : 0"
      :stroke="isFilled ? 'none' : undefined"
    />
    <!-- Crack -->
    <path d="M12 7.7l-1.8 3 2.6 1.7-2 3.2" fill="none" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: { type: [Number, String], default: 24 },
  color: { type: String, default: 'currentColor' },
  stroke: { type: [Number, String], default: 1.8 },
  ariaLabel: { type: String, default: '' },
  variant: { type: String, default: 'stroke' },
  secondaryColor: { type: String, default: 'currentColor' },
})

const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
const isFilled = computed(() => props.variant === 'filled')
const isDuotone = computed(() => props.variant === 'duotone')
</script>
