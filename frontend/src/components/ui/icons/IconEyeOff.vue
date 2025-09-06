<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Eye off icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M2 12s3.5-6 10-6c1.9 0 3.6.5 5 .1M22 12s-3.5 6-10 6c-1.9 0-3.6-.5-5-.1" />
    <path d="M9.9 9.9A3 3 0 0 0 12 15a3 3 0 0 0 3-3" />
    <path d="M3 3l18 18" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.8 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
