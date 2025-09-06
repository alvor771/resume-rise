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
      <span 
        v-if="showCharCount && maxLength" 
        class="text-xs text-gray-500 dark:text-gray-400"
        :class="{
          'text-green-500': isUnderMaxLength,
          'text-amber-500': isNearMaxLength,
          'text-red-500': isOverMaxLength
        }"
      >
        {{ currentLength }}/{{ maxLength }}
      </span>
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
      <!-- Prefix Icon -->
      <div 
        v-if="$slots.prefix || prefixIcon" 
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
        :class="{
          'text-gray-400': !error && !success,
          'text-red-500': error,
          'text-green-500': success,
          'opacity-50': disabled
        }"
      >
        <slot name="prefix">
          <component :is="prefixIcon" v-if="prefixIcon" class="h-5 w-5" />
        </slot>
      </div>

      <!-- Input Field -->
      <input
        :id="id || null"
        ref="input"
        :type="showPassword ? 'text' : type"
        :value="modelValue"
        :placeholder="placeholderComputed"
        :disabled="disabled || loading"
        :required="required"
        :maxlength="maxLength || null"
        :autocomplete="autocomplete"
        :class="[
          'block w-full rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm transition-all duration-200',
          'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-opacity-50',
          'disabled:opacity-60 disabled:cursor-not-allowed',
          'placeholder-gray-400 dark:placeholder-gray-500',
          
          // Padding based on slots
          $slots.prefix || prefixIcon ? 'pl-10' : 'pl-3',
          ($slots.suffix || suffixIcon || showPasswordToggle) ? 'pr-10' : 'pr-3',
          
          // States
          error ? 'border-red-500 focus:ring-red-500/50' : 
          success ? 'border-green-500 focus:ring-green-500/50' : 
          'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500/50',
          
          // Sizes
          size === 'sm' ? 'py-1.5 text-sm' :
          size === 'lg' ? 'py-3 text-base' : 'py-2',
          
          // Disabled state
          disabled ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800'
        ]"
        :aria-invalid="!!error"
        :aria-describedby="error ? `${id}-error` : undefined"
        :aria-busy="loading"
        v-bind="$attrs"
        @input="onInput"
        @focus="isFocused = true"
        @blur="isFocused = false"
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

      <!-- Password Toggle -->
      <button
        v-else-if="type === 'password' && showPasswordToggle"
        type="button"
        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
        @click="showPassword = !showPassword"
        tabindex="-1"
      >
        <svg v-if="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        </svg>
        <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
      </button>

      <!-- Suffix Icon -->
      <div 
        v-else-if="$slots.suffix || suffixIcon" 
        class="absolute inset-y-0 right-0 pr-3 flex items-center"
        :class="{
          'text-gray-400': !error && !success,
          'text-red-500': error,
          'text-green-500': success,
          'opacity-50': disabled
        }"
      >
        <slot name="suffix">
          <component :is="suffixIcon" v-if="suffixIcon" class="h-5 w-5" />
        </slot>
      </div>
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

const BaseInput = {
  name: 'BaseInput',
  inheritAttrs: false,
  
  props: {
    modelValue: {
      type: [String, Number],
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
    type: {
      type: String,
      default: 'text',
      validator: (value) => 
        ['text', 'email', 'password', 'tel', 'url', 'number', 'search', 'date', 'time', 'datetime-local'].includes(value)
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
    maxLength: {
      type: [Number, String],
      default: null
    },
    showCharCount: {
      type: Boolean,
      default: false
    },
    showPasswordToggle: {
      type: Boolean,
      default: true
    },
    autocomplete: {
      type: String,
      default: 'off'
    },
    prefixIcon: {
      type: [String, Object],
      default: null
    },
    suffixIcon: {
      type: [String, Object],
      default: null
    }
  },
  
  emits: ['update:modelValue', 'focus', 'blur', 'keydown', 'keyup', 'keypress', 'enter'],
  
  setup(props, { emit }) {
    const input = ref(null);
    const isFocused = ref(false);
    const showPassword = ref(false);
    
    const placeholderComputed = computed(() => {
      const given = (props.placeholder || '').trim();
      if (given) return given;
      const cleaned = (props.label || '')
        .replace(/\*/g, '')
        .replace(/[:：]\s*$/, '')
        .trim();
      return cleaned;
    });
    
    const currentLength = computed(() => {
      return String(props.modelValue || '').length;
    });
    
    const isUnderMaxLength = computed(() => {
      if (!props.maxLength) return true;
      return currentLength.value < props.maxLength * 0.8;
    });
    
    const isNearMaxLength = computed(() => {
      if (!props.maxLength) return false;
      return currentLength.value >= props.maxLength * 0.8 && currentLength.value < props.maxLength;
    });
    
    const isOverMaxLength = computed(() => {
      if (!props.maxLength) return false;
      return currentLength.value > props.maxLength;
    });
    
    const onInput = (event) => {
      emit('update:modelValue', event.target.value);
    };
    
    const focus = () => {
      if (input.value) {
        input.value.focus();
      }
    };
    
    const blur = () => {
      if (input.value) {
        input.value.blur();
      }
    };
    
    onMounted(() => {
      // Auto-generate ID if not provided
      if (!props.id && props.label) {
        input.value.id = props.label.toLowerCase().replace(/\s+/g, '-');
      }
    });
    
    return {
      input,
      isFocused,
      showPassword,
      placeholderComputed,
      currentLength,
      isUnderMaxLength,
      isNearMaxLength,
      isOverMaxLength,
      onInput,
      focus,
      blur
    };
  }
};

export default BaseInput;
</script>

<style scoped>
/* Smooth transitions */
input {
  transition: all 0.2s ease-in-out;
}

/* Remove number input arrows */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  appearance: textfield;
  -moz-appearance: textfield;
}

/* Better focus ring for keyboard navigation */
:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
  border-radius: 0.25rem;
}
</style>