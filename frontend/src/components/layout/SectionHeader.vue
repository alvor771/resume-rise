<template>
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="min-w-0">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
        {{ title }}
      </h1>
      <p v-if="description" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        {{ description }}
      </p>
    </div>
    
    <div v-if="$slots.actions" class="flex-shrink-0">
      <div class="flex flex-wrap gap-2">
        <slot name="actions"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SectionHeader',
  
  props: {
    title: {
      type: String,
      required: true
    },
    description: {
      type: String,
      default: ''
    }
  }
}
</script>
