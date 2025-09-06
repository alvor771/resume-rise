<template>
  <div class="w-full">
    <div class="flex items-center justify-between mb-1">
      <label v-if="label" :for="forId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ label }} <span v-if="required" class="text-red-500">*</span>
      </label>
      <span v-if="hint && !error" class="text-xs text-gray-500 dark:text-gray-400">{{ hint }}</span>
    </div>

    <div>
      <slot />
    </div>

    <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-start">
      <svg class="h-4 w-4 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
      </svg>
      {{ error }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'InputGroup',
  props: {
    label: { type: String, default: '' },
    forId: { type: String, default: '' },
    required: { type: Boolean, default: false },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
  },
};
</script>

<style scoped>
</style>
