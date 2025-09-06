<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    :stroke="color"
    :aria-label="ariaLabel || 'Edit icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
    vector-effect="non-scaling-stroke"
  >
    <path d="M13.5 5.5l5 5" />
    <path d="M4 20l5.5-1.1a2 2 0 0 0 1.1-.6l8-8a2 2 0 0 0 0-2.8l-1.1-1.1a2 2 0 0 0-2.8 0l-8 8a2 2 0 0 0-.6 1.1L4 20z" />
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
