<template>
  <div class="flex items-center" :class="{ 'opacity-60 cursor-not-allowed': disabled }">
    <span class="relative inline-flex items-center justify-center select-none" :class="outerSizeClass">
      <input
        :id="id || name + '-' + value"
        type="radio"
        :name="name"
        :value="value"
        :checked="isChecked"
        :disabled="disabled || loading"
        :required="required"
        :class="[
          'peer appearance-none absolute inset-0 rounded-full border transition-all duration-150',
          disabled ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed' : 'bg-white dark:bg-gray-800 cursor-pointer',
          error ? 'border-red-500' : success ? 'border-green-500' : 'border-gray-300 dark:border-gray-600',
        ]"
        v-bind="$attrs"
        @change="onChange"
        @focus="isFocused = true"
        @blur="isFocused = false"
      >
      <!-- Visual outer circle -->
      <span
        aria-hidden="true"
        :class="[
          'pointer-events-none inline-flex items-center justify-center rounded-full border transition-all duration-150',
          disabled ? 'bg-gray-100 dark:bg-gray-700' : (isChecked ? checkedBgClass : 'bg-white dark:bg-gray-800'),
          // Border color prioritizes checked/error/success/focus
          isChecked ? checkedBorderClass : (error ? 'border-red-500' : success ? 'border-green-500' : isFocused ? 'border-blue-500' : 'border-gray-300 dark:border-gray-600'),
          outerSizeClass
        ]"
      >
        <!-- Inner dot when checked -->
        <span v-if="isChecked" :class="['rounded-full transition-all duration-150', innerSizeClass, innerColorClass]"></span>
      </span>
    </span>
    <label 
      :for="id || name + '-' + value"
      class="ml-2 block text-sm font-medium"
      :class="{
        'text-gray-700 dark:text-gray-300': !disabled,
        'text-gray-400 dark:text-gray-500': disabled,
        'cursor-not-allowed': disabled,
        'cursor-pointer': !disabled
      }"
    >
      <slot>{{ label }}</slot>
    </label>
    
    <!-- Loading Spinner -->
    <div v-if="loading" class="ml-2">
      <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>
  </div>
  
  <!-- Helper Text -->
  <div v-if="(error || hint) && !hideDetails" class="mt-1.5 ml-6">
    <p 
      v-if="error" 
      class="text-sm text-red-600 dark:text-red-400 flex items-start"
    >
      <svg class="h-4 w-4 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
      </svg>
      {{ error }}
    </p>
    <p 
      v-else-if="hint" 
      class="text-xs text-gray-500 dark:text-gray-400 flex items-start"
    >
      <svg v-if="success" class="h-4 w-4 mr-1.5 mt-0.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
      {{ hint }}
    </p>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

const BaseRadio = {
  name: 'BaseRadio',
  inheritAttrs: false,
  
  props: {
    modelValue: {
      type: [String, Number, Boolean],
      default: ''
    },
    value: {
      type: [String, Number, Boolean],
      required: true
    },
    id: {
      type: String,
      default: ''
    },
    name: {
      type: String,
      required: true
    },
    label: {
      type: String,
      default: ''
    },
    error: {
      type: String,
      default: ''
    },
    hint: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    success: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    hideDetails: {
      type: Boolean,
      default: false
    }
  },
  
  emits: ['update:modelValue', 'change'],
  
  setup(props, { emit }) {
    const isFocused = ref(false);
    
    const isChecked = computed(() => {
      return props.modelValue === props.value;
    });
    
    const onChange = (event) => {
      emit('update:modelValue', props.value);
      emit('change', event);
    };
    const outerSizeClass = computed(() => props.size === 'sm' ? 'h-4 w-4' : props.size === 'lg' ? 'h-6 w-6' : 'h-5 w-5');
    const innerSizeClass = computed(() => props.size === 'sm' ? 'h-2.5 w-2.5' : props.size === 'lg' ? 'h-4 w-4' : 'h-3.5 w-3.5');

    const innerColorClass = computed(() => {
      if (!isChecked.value) return 'bg-transparent';
      if (props.error) return 'bg-red-600';
      if (props.success) return 'bg-green-600';
      return 'bg-blue-600';
    });
    const checkedBorderClass = computed(() => {
      if (props.error) return 'border-red-600';
      if (props.success) return 'border-green-600';
      return 'border-blue-600';
    });
    const checkedBgClass = computed(() => {
      if (props.error) return 'bg-red-50 dark:bg-red-900/20';
      if (props.success) return 'bg-green-50 dark:bg-green-900/20';
      return 'bg-blue-50 dark:bg-blue-900/20';
    });

    return {
      isFocused,
      isChecked,
      onChange,
      outerSizeClass,
      innerSizeClass,
      innerColorClass,
      checkedBorderClass,
      checkedBgClass
    };
  }
};

export default BaseRadio;
</script>
