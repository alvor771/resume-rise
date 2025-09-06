<template>
  <component
    :is="isLink ? 'router-link' : tag"
    :to="isLink ? to : undefined"
    :type="!isLink ? 'button' : undefined"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center rounded-md border font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2',
      'transition-colors duration-200',
      sizeClasses,
      variantClasses,
      fullWidth ? 'w-full' : '',
      disabled || loading ? 'opacity-70 cursor-not-allowed' : '',
      $attrs.class
    ]"
    v-bind="$attrs"
    @click="handleClick"
  >
    <span v-if="loading" class="flex items-center">
      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      {{ loadingText || 'Loading...' }}
    </span>
    <span v-else class="flex items-center">
      <slot></slot>
    </span>
  </component>
</template>

<script>
import { computed } from 'vue';

const BaseButton = {
  name: 'BaseButton',
  inheritAttrs: false,
  props: {
    variant: {
      type: String,
      default: 'primary',
      validator: (value) => [
        'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'outline-primary', 'outline-secondary', 'outline-success', 'outline-danger', 'outline-warning', 'outline-info', 'outline-light', 'outline-dark', 'ghost', 'link'
      ].includes(value)
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
    },
    loading: {
      type: Boolean,
      default: false
    },
    loadingText: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    },
    fullWidth: {
      type: Boolean,
      default: false
    },
    isLink: {
      type: Boolean,
      default: false
    },
    to: {
      type: [String, Object],
      default: ''
    },
    tag: {
      type: String,
      default: 'button',
      validator: (value) => ['button', 'a', 'router-link'].includes(value)
    }
  },
  emits: ['click'],
  setup(props, { emit }) {
    const sizeClasses = computed(() => {
      const sizes = {
        xs: 'px-2.5 py-1.5 text-xs',
        sm: 'px-3 py-2 text-sm',
        md: 'px-4 py-2 text-sm',
        lg: 'px-6 py-3 text-base',
        xl: 'px-8 py-4 text-lg'
      };
      return sizes[props.size] || sizes.md;
    });

    const variantClasses = computed(() => {
      const variants = {
        // Solid variants
        primary: 'border-transparent text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
        secondary: 'border-transparent text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-gray-500 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700',
        success: 'border-transparent text-white bg-green-600 hover:bg-green-700 focus:ring-green-500',
        danger: 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
        warning: 'border-transparent text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        info: 'border-transparent text-white bg-blue-500 hover:bg-blue-600 focus:ring-blue-500',
        light: 'border-transparent text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-gray-300',
        dark: 'border-transparent text-white bg-gray-800 hover:bg-gray-900 focus:ring-gray-500',
        
        // Outline variants
        'outline-primary': 'border-indigo-500 text-indigo-600 bg-transparent hover:bg-indigo-50 focus:ring-indigo-500 dark:text-indigo-400 dark:hover:bg-gray-700',
        'outline-secondary': 'border-gray-300 text-gray-700 bg-transparent hover:bg-gray-50 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700',
        'outline-success': 'border-green-500 text-green-600 bg-transparent hover:bg-green-50 focus:ring-green-500 dark:text-green-400 dark:hover:bg-gray-700',
        'outline-danger': 'border-red-500 text-red-600 bg-transparent hover:bg-red-50 focus:ring-red-500 dark:text-red-400 dark:hover:bg-gray-700',
        'outline-warning': 'border-yellow-500 text-yellow-600 bg-transparent hover:bg-yellow-50 focus:ring-yellow-500 dark:text-yellow-400 dark:hover:bg-gray-700',
        'outline-info': 'border-blue-500 text-blue-600 bg-transparent hover:bg-blue-50 focus:ring-blue-500 dark:text-blue-400 dark:hover:bg-gray-700',
        'outline-light': 'border-gray-200 text-gray-600 bg-transparent hover:bg-gray-50 focus:ring-gray-300 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700',
        'outline-dark': 'border-gray-800 text-gray-800 bg-transparent hover:bg-gray-100 focus:ring-gray-500 dark:border-gray-400 dark:text-gray-200 dark:hover:bg-gray-700',
        
        // Ghost variant
        ghost: 'border-transparent bg-transparent text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500 dark:text-indigo-400 dark:hover:bg-gray-700',
        
        // Link variant
        link: 'border-transparent bg-transparent text-indigo-600 hover:text-indigo-800 hover:underline focus:ring-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300'
      };
      return variants[props.variant] || variants.primary;
    });

    const handleClick = (event) => {
      if (!props.disabled && !props.loading) {
        emit('click', event);
      }
    };

    return {
      sizeClasses,
      variantClasses,
      handleClick
    };
  }
};

export default BaseButton;
</script>
