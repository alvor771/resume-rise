<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Phone icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M4 5l3-2 3 4-2 2a10 10 0 0 0 8 8l2-2 4 3-2 3a3 3 0 0 1-2.7 1.3A17 17 0 0 1 3 7.7 3 3 0 0 1 4 5z" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.8 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
