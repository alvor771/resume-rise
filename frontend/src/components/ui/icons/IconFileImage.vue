<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Image file icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M6 2h7l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" :fill="isFilled ? color : isDuotone ? secondaryColor : 'none'" :fill-opacity="isDuotone ? 0.15 : isFilled ? 1 : 0" />
    <path d="M13 2v6h6" />
    <circle cx="10" cy="11" r="1.2" />
    <path d="M5 20l5-5 3 3 4-4 2 2v4H5z" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.8 }, ariaLabel: { type: String, default: '' }, variant: { type: String, default: 'stroke' }, secondaryColor: { type: String, default: 'currentColor' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
const isFilled = computed(() => props.variant === 'filled')
const isDuotone = computed(() => props.variant === 'duotone')
</script>
