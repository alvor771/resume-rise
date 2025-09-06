<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'DOC file icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M6 2h7l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" :fill="isFilled ? color : isDuotone ? secondaryColor : 'none'" :fill-opacity="isDuotone ? 0.15 : isFilled ? 1 : 0" />
    <path d="M13 2v6h6" />
    <path d="M8 20v-8h2.5a2.5 2.5 0 1 1 0 5H8" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.8 }, ariaLabel: { type: String, default: '' }, variant: { type: String, default: 'stroke' }, secondaryColor: { type: String, default: 'currentColor' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
const isFilled = computed(() => props.variant === 'filled')
const isDuotone = computed(() => props.variant === 'duotone')
</script>
