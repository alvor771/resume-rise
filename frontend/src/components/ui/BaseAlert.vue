<template>
  <div :class="[
    'rounded-md p-3 flex gap-2',
    variantClasses[variant]
  ]">
    <div class="shrink-0">
      <slot name="icon">
        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
      </slot>
    </div>
    <div class="min-w-0 flex-1">
      <h4 v-if="title" class="text-sm font-semibold">{{ title }}</h4>
      <div class="text-sm"><slot /></div>
    </div>
    <button v-if="closable" class="text-current/70 hover:text-current" @click="$emit('close')">
      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
  </div>
</template>

<script>
const variantClasses = {
  info: 'bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
  success: 'bg-green-50 text-green-800 dark:bg-green-900/30 dark:text-green-200',
  warning: 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
  danger: 'bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-200'
};

export default {
  name: 'BaseAlert',
  props: {
    variant: { type: String, default: 'info' },
    title: { type: String, default: '' },
    closable: { type: Boolean, default: false }
  },
  data() { return { variantClasses }; }
}
</script>

<style scoped>
</style>
