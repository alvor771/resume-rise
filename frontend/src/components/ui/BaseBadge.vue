<template>
  <span :class="[
    'inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-medium',
    variantClasses[variant]
  ]">
    <slot name="icon"></slot>
    <slot></slot>
  </span>
</template>

<script>
/**
 * BaseBadge component
 * 
 * @param {string} variant - The variant of the badge (gray, primary, green, red, yellow, purple)
 */
const variantClasses = {
  gray: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
  primary: 'bg-blue-100 text-blue-800 dark:bg-blue-700/30 dark:text-blue-300',
  green: 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-300',
  red: 'bg-red-100 text-red-800 dark:bg-red-700/30 dark:text-red-300',
  yellow: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700/30 dark:text-yellow-300',
  purple: 'bg-purple-100 text-purple-800 dark:bg-purple-700/30 dark:text-purple-300'
};

export default {
  name: 'BaseBadge',
  props: {
    variant: { type: String, default: 'gray' }
  },
  data() {
    return { variantClasses };
  }
}
</script>

<style scoped>
</style>
