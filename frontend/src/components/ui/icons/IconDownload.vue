<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    :stroke="color"
    :aria-label="ariaLabel || 'Download icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
    vector-effect="non-scaling-stroke"
  >
    <path d="M12 3v11" />
    <path d="M8.5 11.5L12 15l3.5-3.5" />
    <path d="M4 20h16" />
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
