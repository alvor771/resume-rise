<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-pulse">
    <div class="p-5">
      <div class="flex items-center space-x-3">
        <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700"></div>
        <div class="flex-1 space-y-2">
          <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
          <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
        </div>
      </div>
      
      <div class="mt-4 space-y-3">
        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-4/6"></div>
      </div>
      
      <div class="mt-6 flex justify-end space-x-2">
        <div class="h-8 w-20 bg-gray-200 dark:bg-gray-700 rounded-md"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CompanyCardSkeleton'
}
</script>
