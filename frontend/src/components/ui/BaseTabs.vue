<template>
  <div class="w-full">
    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 mb-4">
      <button
        v-for="(tab, idx) in tabs"
        :key="tab.key || idx"
        type="button"
        :class="[
          'px-3 py-2 text-sm font-medium rounded-t-md -mb-px',
          modelValue === (tab.key || idx)
            ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400'
            : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'
        ]"
        @click="$emit('update:modelValue', tab.key || idx)"
      >
        <span class="inline-flex items-center gap-2">
          <component :is="tab.icon" v-if="tab.icon" class="h-4 w-4" />
          {{ tab.label }}
        </span>
      </button>
    </div>

    <div>
      <slot :name="`panel:${modelValue}`"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BaseTabs',
  props: {
    modelValue: { type: [String, Number], default: 0 },
    tabs: { type: Array, default: () => [] }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
</style>
