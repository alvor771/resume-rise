<template>
  <svg :width="computedSize" :height="computedSize" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" :stroke="color" :aria-label="ariaLabel || 'Warning icon'" role="img" stroke-linecap="round" stroke-linejoin="round" :stroke-width="stroke">
    <path d="M12 3l9 16a2 2 0 0 1-1.7 3H4.7A2 2 0 0 1 3 19L12 3z" :fill="isFilled ? color : isDuotone ? secondaryColor : 'none'" :fill-opacity="isDuotone ? 0.2 : isFilled ? 1 : 0" />
    <path d="M12 10v4" />
    <path d="M12 18h.01" />
  </svg>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ size: { type: [Number, String], default: 24 }, color: { type: String, default: 'currentColor' }, stroke: { type: [Number, String], default: 1.8 }, ariaLabel: { type: String, default: '' }, variant: { type: String, default: 'stroke' }, secondaryColor: { type: String, default: 'currentColor' } })
const computedSize = computed(() => (typeof props.size === 'number' ? `${props.size}` : props.size))
const isFilled = computed(() => props.variant === 'filled')
const isDuotone = computed(() => props.variant === 'duotone')
</script>
