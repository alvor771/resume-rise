<template>
  <div class="w-full space-y-1">
    <!-- Label -->
    <div class="flex items-center justify-between">
      <label 
        v-if="label" 
        :for="id || null"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-200"
      >
        {{ label }}
        <span v-if="required" class="text-red-500 ml-0.5">*</span>
      </label>
    </div>

    <!-- Input Container -->
    <div 
      class="relative rounded-lg transition-all duration-200"
      :class="{
        'ring-2 ring-offset-1': isFocused,
        'ring-blue-500': isFocused && !error && !success,
        'ring-red-500': isFocused && error,
        'ring-green-500': isFocused && success,
      }"
    >
      <!-- Calendar Icon -->
      <div 
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
        :class="{
          'text-gray-400': !isFocused && !error && !success,
          'text-blue-500': isFocused && !error && !success,
          'text-red-500': error,
          'text-green-500': success && !error
        }"
      >
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
        </svg>
      </div>

      <!-- Date Input -->
      <input
        ref="dateInput"
        type="date"
        :value="formattedDate"
        :min="minDate"
        :max="maxDate"
        :disabled="disabled || loading"
        :required="required"
        :class="[
          'block w-full rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm transition-all duration-200',
          'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-opacity-50',
          'disabled:opacity-60 disabled:cursor-not-allowed',
          'placeholder-gray-400 dark:placeholder-gray-500',
          'pl-10 pr-3 py-2',
          
          // States
          error ? 'border-red-500 focus:ring-red-500/50' : 
          success ? 'border-green-500 focus:ring-green-500/50' : 
          'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500/50',
          
          // Sizes
          size === 'sm' ? 'text-sm py-1.5' :
          size === 'lg' ? 'text-base py-3' : 'text-sm py-2',
          
          // Disabled state
          disabled ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800'
        ]"
        :aria-invalid="!!error"
        :aria-describedby="error ? `${id}-error` : undefined"
        :aria-busy="loading"
        v-bind="$attrs"
        @input="onInput"
        @focus="onFocus"
        @blur="onBlur"
        @keydown.enter="$emit('enter', $event)"
      />

      <!-- Loading Spinner -->
      <div 
        v-if="loading" 
        class="absolute inset-y-0 right-0 pr-3 flex items-center"
      >
        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Clear Button -->
      <button
        v-else-if="showClear && formattedDate"
        type="button"
        class="absolute inset-y-0 right-0 pr-3 flex items-center"
        :class="{
          'text-gray-400 hover:text-gray-500': !error && !success,
          'text-red-500 hover:text-red-600': error,
          'text-green-500 hover:text-green-600': success && !error
        }"
        @click="clearDate"
        :aria-label="clearLabel || 'Clear date'"
      >
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <!-- Helper Text -->
    <div v-if="error || hint || $slots.helper" class="mt-1.5">
      <p 
        v-if="error" 
        :id="`${id}-error`" 
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
      <slot name="helper"></slot>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';

const DatePicker = {
  name: 'DatePicker',
  inheritAttrs: false,
  
  props: {
    modelValue: {
      type: [String, Date],
      default: ''
    },
    id: {
      type: String,
      default: ''
    },
    label: {
      type: String,
      default: ''
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
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    minDate: {
      type: String,
      default: ''
    },
    maxDate: {
      type: String,
      default: ''
    },
    showClear: {
      type: Boolean,
      default: true
    },
    clearLabel: {
      type: String,
      default: ''
    },
    format: {
      type: String,
      default: 'YYYY-MM-DD',
      validator: (value) => ['YYYY-MM-DD', 'DD/MM/YYYY', 'MM/DD/YYYY'].includes(value)
    }
  },
  
  emits: ['update:modelValue', 'focus', 'blur', 'keydown', 'keyup', 'keypress', 'enter', 'clear'],
  
  setup(props, { emit }) {
    const dateInput = ref(null);
    const isFocused = ref(false);
    
    const formattedDate = computed(() => {
      if (!props.modelValue) return '';
      
      let date;
      if (props.modelValue instanceof Date) {
        date = props.modelValue;
      } else {
        // Check if the value is already in YYYY-MM-DD format
        if (/^\d{4}-\d{2}-\d{2}$/.test(props.modelValue)) {
          return props.modelValue;
        }
        date = new Date(props.modelValue);
      }
      
      // Format date as YYYY-MM-DD (browser's native date input format)
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      
      return `${year}-${month}-${day}`;
    });
    
    const onInput = (event) => {
      const value = event.target.value;
      emit('update:modelValue', value || null);
    };
    
    const onFocus = (event) => {
      isFocused.value = true;
      emit('focus', event);
    };
    
    const onBlur = (event) => {
      isFocused.value = false;
      emit('blur', event);
    };
    
    const clearDate = () => {
      emit('update:modelValue', null);
      emit('clear');
      if (dateInput.value) {
        dateInput.value.focus();
      }
    };
    
    const focus = () => {
      if (dateInput.value) {
        dateInput.value.focus();
      }
    };
    
    const blur = () => {
      if (dateInput.value) {
        dateInput.value.blur();
      }
    };
    
    onMounted(() => {
      // Auto-generate ID if not provided
      if (!props.id && props.label) {
        dateInput.value.id = props.label.toLowerCase().replace(/\s+/g, '-');
      }
    });
    
    return {
      dateInput,
      isFocused,
      formattedDate,
      onInput,
      onFocus,
      onBlur,
      clearDate,
      focus,
      blur
    };
  }
};

export default DatePicker;
</script>
