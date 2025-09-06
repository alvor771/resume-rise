<template>
  <div class="space-y-1">
    <label 
      v-if="label" 
      :for="id" 
      class="block text-sm font-medium text-gray-700 dark:text-gray-300"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <div class="relative">
      <select
        :id="id"
        v-model="selectedValue"
        :class="[
          'block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pl-3',
          {
            'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:outline-none focus:ring-red-500': error,
            'border-green-300 text-green-900 placeholder-green-300 focus:border-green-500 focus:outline-none focus:ring-green-500': success && !error,
            'opacity-50 cursor-not-allowed': disabled,
            'pl-10': $slots.prefix || loading,
            'pr-10': $slots.suffix || showClearButton,
            'h-8 text-xs': size === 'sm',
            'h-10 text-sm': size === 'md',
            'h-12 text-base': size === 'lg'
          }
        ]"
        :disabled="disabled || loading"
        :required="required"
        v-bind="$attrs"
        @blur="onBlur"
        @focus="onFocus"
        @change="onChange"
      >
        <option v-if="placeholder" value="" disabled selected>
          {{ placeholder }}
        </option>
        <option 
          v-for="option in options" 
          :key="option.value" 
          :value="option.value"
          :disabled="option.disabled"
        >
          {{ option.label }}
        </option>
      </select>

      <!-- Loading Indicator -->
      <div 
        v-if="loading" 
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
      >
        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Success Indicator -->
      <div 
        v-else-if="success && !error" 
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
      >
        <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
      </div>

      <!-- Clear Button -->
      <button
        v-if="showClearButton && !disabled && !loading"
        type="button"
        class="absolute inset-y-0 right-0 pr-3 flex items-center"
        @click="clearSelection"
      >
        <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Suffix Icon -->
      <div 
        v-else-if="$slots.suffix" 
        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
      >
        <slot name="suffix"></slot>
      </div>
    </div>

    <!-- Error Message -->
    <p 
      v-if="error" 
      class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-start"
    >
      <svg class="h-4 w-4 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
      </svg>
      {{ error }}
    </p>

    <!-- Helper Text -->
    <p 
      v-else-if="hint" 
      class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-start"
    >
      <svg v-if="success" class="h-4 w-4 mr-1.5 mt-0.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
      {{ hint }}
    </p>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue';

export default {
  name: 'BaseSelect',
  inheritAttrs: false,
  
  props: {
    modelValue: {
      type: [String, Number, Boolean, Object, Array],
      default: ''
    },
    options: {
      type: Array,
      required: true,
      validator: (value) => {
        return value.every(option => 
          option && 
          typeof option === 'object' && 
          'value' in option && 
          'label' in option
        );
      }
    },
    label: {
      type: String,
      default: ''
    },
    id: {
      type: String,
      default: () => `select-${Math.random().toString(36).substr(2, 9)}`
    },
    placeholder: {
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
    clearable: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    }
  },

  emits: ['update:modelValue', 'blur', 'focus', 'change', 'clear'],

  setup(props, { emit }) {
    const isFocused = ref(false);
    const selectedValue = ref(props.modelValue);

    const showClearButton = computed(() => {
      return props.clearable && 
             !props.loading && 
             selectedValue.value !== '' && 
             selectedValue.value !== null && 
             selectedValue.value !== undefined;
    });

    const onBlur = (event) => {
      isFocused.value = false;
      emit('blur', event);
    };

    const onFocus = (event) => {
      isFocused.value = true;
      emit('focus', event);
    };

    const onChange = (event) => {
      emit('change', event.target.value);
      emit('update:modelValue', event.target.value);
    };

    const clearSelection = () => {
      selectedValue.value = '';
      emit('update:modelValue', '');
      emit('clear');
    };

    // Watch for changes in modelValue prop
    watch(() => props.modelValue, (newValue) => {
      selectedValue.value = newValue;
    });

    // Watch for changes in selectedValue
    watch(selectedValue, (newValue) => {
      emit('update:modelValue', newValue);
    });

    return {
      selectedValue,
      isFocused,
      showClearButton,
      onBlur,
      onFocus,
      onChange,
      clearSelection
    };
  }
};
</script>

<style scoped>
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}

.dark select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}

select:disabled {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  opacity: 0.7;
}
</style>
