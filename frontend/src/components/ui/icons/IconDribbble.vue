<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Dribbble icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <circle cx="12" cy="12" r="9" />
    <path d="M3 12h18" />
    <path d="M6 5c4 4 7 9 9 16" />
    <path d="M18 5c-4 4-7 9-9 16" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.6 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
