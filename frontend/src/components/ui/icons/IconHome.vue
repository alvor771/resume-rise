<template>
  <svg
    :width="computedSize"
    :height="computedSize"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    :stroke="color"
    :aria-label="ariaLabel || 'Home icon'"
    role="img"
    stroke-linecap="round"
    stroke-linejoin="round"
    :stroke-width="stroke"
    vector-effect="non-scaling-stroke"
  >
    <path d="M3 11.5L11.35 4.4a1 1 0 0 1 1.3 0L21 11.5" />
    <path d="M5 10.5V19a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8.5" />
    <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6" />
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
