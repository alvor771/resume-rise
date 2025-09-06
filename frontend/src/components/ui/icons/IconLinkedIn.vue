<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'LinkedIn icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <rect x="3" y="3" width="18" height="18" rx="2" />
    <path d="M8 17v-6" />
    <circle cx="8" cy="8" r="1" />
    <path d="M12 17v-3a2 2 0 1 1 4 0v3" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.6 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
