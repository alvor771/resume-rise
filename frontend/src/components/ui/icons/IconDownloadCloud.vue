<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    :stroke="color"
    :aria-label="ariaLabel || 'Cloud download icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
    vector-effect="non-scaling-stroke"
  >
    <path d="M7 18a4 4 0 1 1 .7-7.9A5.5 5.5 0 0 1 18.5 8 4.5 4.5 0 0 1 19 17.9" />
    <path d="M12 10v7" />
    <path d="M9.5 14.5L12 17l2.5-2.5" />
  </svg>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: { type: [Number, String], default: 24 },
  color: { type: String, default: 'currentColor' },
  stroke: { type: [Number, String], default: 1.8 },
  ariaLabel: { type: String, default: '' },
})

const computedSize = computed(() =>
  typeof props.size === 'number' ? `${props.size}` : props.size
)
</script>
