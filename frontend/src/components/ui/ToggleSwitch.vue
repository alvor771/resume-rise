<template>
  <div class="w-full">
    <div class="flex items-center justify-between mb-1">
      <label v-if="label" :for="id" class="text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ label }}
      </label>
      <span v-if="hint && !error" class="text-xs text-gray-500 dark:text-gray-400">{{ hint }}</span>
    </div>

    <button
      :id="id"
      type="button"
      role="switch"
      :aria-checked="modelValue"
      :class="[
        'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2',
        modelValue ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600',
        disabled ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer'
      ]"
      :disabled="disabled"
      @click="toggle"
    >
      <span
        :class="[
          'inline-block h-5 w-5 transform rounded-full bg-white transition-transform',
          modelValue ? 'translate-x-5' : 'translate-x-1'
        ]"
      />
    </button>

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
  name: 'ToggleSwitch',
  props: {
    modelValue: { type: Boolean, default: false },
    id: { type: String, default: () => `switch-${Math.random().toString(36).slice(2)}` },
    label: { type: String, default: '' },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
  },
  emits: ['update:modelValue', 'change'],
  setup(props, { emit }) {
    const toggle = () => {
      if (props.disabled) return;
      emit('update:modelValue', !props.modelValue);
      emit('change', !props.modelValue);
    };
    return { toggle };
  },
};
</script>

<style scoped>
</style>
