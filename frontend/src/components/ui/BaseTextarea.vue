<template>
  <div class="w-full space-y-1">
    <!-- Label and Character Counter -->
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
        v-if="showCharacterCount && maxLength" 
        class="text-xs"
        :class="{
          'text-green-500': isUnderMaxLength,
          'text-amber-500': isNearMaxLength,
          'text-red-500': isOverMaxLength,
          'text-gray-500 dark:text-gray-400': !isNearMaxLength && !isOverMaxLength
        }"
      >
        {{ characterCount }}/{{ maxLength }}
      </span>
    </div>

    <!-- Textarea Container -->
    <div 
      class="relative rounded-lg transition-all duration-200"
      :class="{
        'ring-2 ring-offset-1': isFocused,
        'ring-blue-500': isFocused && !error && !success,
        'ring-red-500': isFocused && error,
        'ring-green-500': isFocused && success,
      }"
    >
      <!-- Textarea -->
      <textarea
        :id="id || null"
        ref="textarea"
        :value="modelValue"
        :placeholder="placeholderComputed"
        :rows="rows"
        :disabled="disabled || loading"
        :required="required"
        :maxlength="maxLength || null"
        :class="[
          'block w-full rounded-lg border bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm transition-all duration-200',
          'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-opacity-50',
          'disabled:opacity-60 disabled:cursor-not-allowed',
          'placeholder-gray-400 dark:placeholder-gray-500',
          'px-3 py-2',
          
          // States
          error ? 'border-red-500 focus:ring-red-500/50' : 
          success ? 'border-green-500 focus:ring-green-500/50' : 
          'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500/50',
          
          // Sizes
          size === 'sm' ? 'text-sm' :
          size === 'lg' ? 'text-base' : 'text-sm',
          
          // Resize
          resize === 'none' ? 'resize-none' :
          resize === 'both' ? 'resize' :
          resize === 'horizontal' ? 'resize-x' : 'resize-y',
          
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
      ></textarea>

      <!-- Loading Spinner -->
      <div 
        v-if="loading" 
        class="absolute bottom-2 right-2 p-1"
      >
        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
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

const BaseTextarea = {
  name: 'BaseTextarea',
  inheritAttrs: false,
  
  props: {
    modelValue: {
      type: String,
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
    rows: {
      type: [Number, String],
      default: 3
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
    showCharacterCount: {
      type: Boolean,
      default: false
    },
    resize: {
      type: String,
      default: 'vertical',
      validator: (value) => ['none', 'both', 'horizontal', 'vertical'].includes(value)
    }
  },
  
  emits: ['update:modelValue', 'focus', 'blur', 'keydown', 'keyup', 'keypress', 'enter'],
  
  setup(props, { emit }) {
    const textarea = ref(null);
    const isFocused = ref(false);
    
    const placeholderComputed = computed(() => {
      const given = (props.placeholder || '').trim();
      if (given) return given;
      const cleaned = (props.label || '')
        .replace(/\*/g, '')
        .replace(/[:：]\s*$/, '')
        .trim();
      return cleaned;
    });
    
    const characterCount = computed(() => {
      return String(props.modelValue || '').length;
    });
    
    const isUnderMaxLength = computed(() => {
      if (!props.maxLength) return true;
      return characterCount.value < props.maxLength * 0.8;
    });
    
    const isNearMaxLength = computed(() => {
      if (!props.maxLength) return false;
      return characterCount.value >= props.maxLength * 0.8 && characterCount.value < props.maxLength;
    });
    
    const isOverMaxLength = computed(() => {
      if (!props.maxLength) return false;
      return characterCount.value > props.maxLength;
    });
    
    const onInput = (event) => {
      emit('update:modelValue', event.target.value);
    };
    
    const focus = () => {
      if (textarea.value) {
        textarea.value.focus();
      }
    };
    
    const blur = () => {
      if (textarea.value) {
        textarea.value.blur();
      }
    };
    
    onMounted(() => {
      // Auto-generate ID if not provided
      if (!props.id && props.label) {
        textarea.value.id = props.label.toLowerCase().replace(/\s+/g, '-');
      }
    });
    
    return {
      textarea,
      isFocused,
      placeholderComputed,
      characterCount,
      isUnderMaxLength,
      isNearMaxLength,
      isOverMaxLength,
      onInput,
      focus,
      blur
    };
  }
};

export default BaseTextarea;
</script>
