<template>
  <div class="w-full">
    <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <div 
      class="flex flex-wrap items-center gap-2 p-2 min-h-12 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 focus-within:border-indigo-500"
      :class="{
        'border-red-500': error,
        'bg-gray-100 dark:bg-gray-700': disabled
      }"
      @click="focusInput"
    >
      <!-- Chips -->
      <span 
        v-for="(item, index) in modelValue" 
        :key="index"
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
        :class="[
          chipVariant === 'primary' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100' :
          chipVariant === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' :
          chipVariant === 'danger' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' :
          chipVariant === 'warning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100' :
          'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
          disabled ? 'opacity-70' : ''
        ]"
      >
        {{ item }}
        <button 
          v-if="!disabled"
          type="button" 
          class="flex-shrink-0 ml-1.5 inline-flex text-current focus:outline-none"
          @click.stop="removeItem(index)"
          :aria-label="`Remove ${item}`"
        >
          <span class="sr-only">Remove {{ item }}</span>
          <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </span>
      
      <!-- Input -->
      <input
        ref="inputRef"
        type="text"
        v-model="inputValue"
        :placeholder="placeholder"
        :disabled="disabled || (maxItems && modelValue.length >= maxItems)"
        :maxlength="maxLength"
        :class="[
          'flex-1 min-w-[100px] bg-transparent border-0 p-0 focus:ring-0 sm:text-sm',
          'text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500',
          'focus:outline-none',
          disabled ? 'cursor-not-allowed' : ''
        ]"
        @keydown.enter.prevent="addItem"
        @keydown.backspace="handleBackspace"
        @blur="handleBlur"
        @keydown.space.enter.prevent
      />
    </div>
    
    <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">
      {{ error }}
    </p>
    <p v-else-if="hint" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
      {{ hint }}
    </p>
    <p v-if="maxItems" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
      {{ modelValue.length }}/{{ maxItems }} items
    </p>
  </div>
</template>

<script>
import { ref, nextTick } from 'vue';

const ChipInput = {
  name: 'ChipInput',
  props: {
    modelValue: {
      type: Array,
      default: () => []
    },
    label: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Type and press Enter...'
    },
    maxItems: {
      type: Number,
      default: null
    },
    maxLength: {
      type: Number,
      default: 50
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
    allowDuplicates: {
      type: Boolean,
      default: false
    },
    chipVariant: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'success', 'danger', 'warning', 'default'].includes(value)
    },
    trim: {
      type: Boolean,
      default: true
    }
  },
  emits: ['update:modelValue', 'add', 'remove'],
  setup(props, { emit }) {
    const inputRef = ref(null);
    const inputValue = ref('');
    
    const focusInput = () => {
      if (inputRef.value) {
        inputRef.value.focus();
      }
    };
    
    const addItem = () => {
      if (!inputValue.value || (props.maxItems && props.modelValue.length >= props.maxItems)) {
        return;
      }
      
      let value = props.trim ? inputValue.value.trim() : inputValue.value;
      
      if (!value) {
        return;
      }
      
      // Check for duplicates if not allowed
      if (!props.allowDuplicates && props.modelValue.includes(value)) {
        inputValue.value = '';
        return;
      }
      
      const newItems = [...props.modelValue, value];
      emit('update:modelValue', newItems);
      emit('add', value);
      
      inputValue.value = '';
      
      // Focus input after adding
      nextTick(() => {
        focusInput();
      });
    };
    
    const removeItem = (index) => {
      if (props.disabled) {
        return;
      }
      
      const newItems = [...props.modelValue];
      const removedItem = newItems.splice(index, 1)[0];
      emit('update:modelValue', newItems);
      emit('remove', removedItem, index);
      
      nextTick(() => {
        focusInput();
      });
    };
    
    const handleBackspace = (event) => {
      if (event.target.value === '' && props.modelValue.length > 0) {
        removeItem(props.modelValue.length - 1);
      }
    };
    
    const handleBlur = () => {
      if (inputValue.value) {
        addItem();
      }
    };
    
    return {
      inputRef,
      inputValue,
      focusInput,
      addItem,
      removeItem,
      handleBackspace,
      handleBlur
    };
  }
};

export default ChipInput;
</script>
