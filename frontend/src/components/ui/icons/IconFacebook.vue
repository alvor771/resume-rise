<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Facebook icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <rect x="3" y="3" width="18" height="18" rx="2" />
    <path d="M13 10h2V7h-2a3 3 0 0 0-3 3v2H8v3h2v5h3v-5h2.3l.7-3H13v-2a1 1 0 0 1 1-1z" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.6 }, ariaLabel: { type: String, default: '' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
</script>
