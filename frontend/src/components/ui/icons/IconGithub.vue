<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'GitHub icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M12 2a10 10 0 0 0-3.2 19.5c.5.1.7-.2.7-.5v-2c-3 .7-3.6-1.3-3.6-1.3a2.8 2.8 0 0 0-1.2-1.6c-1-.7.1-.7.1-.7a2.2 2.2 0 0 1 1.6 1.1 2.3 2.3 0 0 0 3.1.9 2.3 2.3 0 0 1 .7-1.4c-2.4-.3-4.8-1.2-4.8-5.3a4.2 4.2 0 0 1 1.1-2.9 3.9 3.9 0 0 1 .1-2.8s.9-.3 3 .9a10.3 10.3 0 0 1 5.4 0c2.1-1.2 3-.9 3-.9.3.9.2 1.9.1 2.8a4.2 4.2 0 0 1 1.1 2.9c0 4.1-2.5 5-4.9 5.3a2.6 2.6 0 0 1 .7 2v3c0 .3.2.6.7.5A10 10 0 0 0 12 2z" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.4 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
