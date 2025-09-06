<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Behance icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <rect x="3" y="3" width="18" height="18" rx="2" />
    <path d="M7 9h3a2 2 0 1 1 0 4H7z" />
    <path d="M7 13h3.2A2.8 2.8 0 1 1 7 16H7z" />
    <path d="M14 10h5" />
    <path d="M14 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.6 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
