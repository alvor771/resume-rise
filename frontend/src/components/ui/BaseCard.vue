<template>
  <div
    :class="[
      'rounded-xl border shadow-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 transition-colors',
      clickable ? 'hover:shadow-md cursor-pointer' : '',
      padded ? 'p-4 sm:p-6' : ''
    ]"
    @click="$emit('click')"
  >
    <div v-if="$slots.header || title || $slots.actions" class="flex items-start justify-between gap-3 mb-3">
      <div class="min-w-0">
        <h3 v-if="title" class="text-base font-semibold text-gray-900 dark:text-white truncate">{{ title }}</h3>
        <div v-if="subtitle" class="text-sm text-gray-500 dark:text-gray-400">{{ subtitle }}</div>
        <slot name="header"></slot>
      </div>
      <div class="shrink-0">
        <slot name="actions"></slot>
      </div>
    </div>

    <div class="min-w-0">
      <slot></slot>
    </div>

    <div v-if="$slots.footer" class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BaseCard',
  props: {
    title: { type: String, default: '' },
    subtitle: { type: String, default: '' },
    clickable: { type: Boolean, default: false },
    padded: { type: Boolean, default: true },
  },
  emits: ['click']
}
</script>

<style scoped>
</style>
