<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Telegram icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M21 3L3 11l6 2 2 6 10-16z" />
    <path d="M9 13l8-6-6 8" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.6 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
