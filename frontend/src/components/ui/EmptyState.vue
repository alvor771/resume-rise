<template>
  <div class="text-center py-12">
    <div class="mx-auto h-12 w-12 rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-400">
      <slot name="icon">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h10M9 7H7a2 2 0 00-2 2v10" />
        </svg>
      </slot>
    </div>
    <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
      <slot name="title">Nothing here</slot>
    </h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
      <slot name="description">Try adjusting filters or add a new item.</slot>
    </p>
    <div class="mt-4">
      <slot name="action"></slot>
    </div>
  </div>
</template>

<script>
export default { name: 'EmptyState' }
</script>

<style scoped>
</style>
