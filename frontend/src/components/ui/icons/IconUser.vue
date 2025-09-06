<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    :stroke="color"
    :aria-label="ariaLabel || 'User icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
    vector-effect="non-scaling-stroke"
  >
    <circle cx="12" cy="8" r="4" />
    <path d="M4.5 20a7.5 7.5 0 0 1 15 0" />
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
