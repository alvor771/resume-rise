<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Success icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <circle cx="12" cy="12" r="9" :fill="isFilled ? color : isDuotone ? secondaryColor : 'none'" :fill-opacity="isDuotone ? 0.2 : isFilled ? 1 : 0" />
    <path d="M8.5 12.5l2.5 2.5 4.5-5" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.8 }, ariaLabel: { type: String, default: '' }, variant: { type: String, default: 'stroke' }, secondaryColor: { type: String, default: 'currentColor' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
const isFilled = computed(() => props.variant === 'filled')
const isDuotone = computed(() => props.variant === 'duotone')
</script>
