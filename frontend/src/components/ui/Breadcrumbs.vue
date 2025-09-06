<template>
  <nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
      <li v-for="(item, idx) in items" :key="idx" class="inline-flex items-center">
        <router-link v-if="item.to && idx < items.length - 1" :to="item.to" class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
          <slot name="icon" v-if="idx === 0"></slot>
          {{ item.label }}
        </router-link>
        <span v-else class="text-sm font-medium text-gray-900 dark:text-white">{{ item.label }}</span>
        <svg v-if="idx < items.length - 1" class="h-4 w-4 mx-2 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 018.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
      </li>
    </ol>
  </nav>
</template>

<script>
export default {
  name: 'UiBreadcrumbs',
  props: {
    items: { type: Array, default: () => [] }
  }
}
</script>

<style scoped>
</style>
