<template>
  <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-sm text-gray-500 dark:text-gray-400">{{ label }}</div>
        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ value }}</div>
        <div v-if="delta" :class="['mt-1 text-sm', delta > 0 ? 'text-green-600' : 'text-red-600']">
          {{ delta > 0 ? '+' : '' }}{{ delta }}%
        </div>
      </div>
      <div class="shrink-0">
        <slot name="icon"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatsCard',
  props: {
    label: { type: String, required: true },
    value: { type: [String, Number], required: true },
    delta: { type: Number, default: null }
  }
}
</script>

<style scoped>
</style>
