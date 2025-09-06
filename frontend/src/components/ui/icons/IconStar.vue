<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    xmlns="http://www.w3.org/2000/svg"
    :fill="isFilled ? color : isDuotone ? secondaryColor : 'none'"
    :stroke="isFilled ? 'none' : color"
    :aria-label="ariaLabel || 'Star icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
  >
    <path
      d="M12 3l2.9 5.9 6.5.9-4.7 4.6 1.1 6.4L12 18.7 6.2 20.8l1.1-6.4L2.6 9.8l6.5-.9L12 3z"
      :fill-opacity="isDuotone ? 0.2 : isFilled ? 1 : 0"
    />
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
