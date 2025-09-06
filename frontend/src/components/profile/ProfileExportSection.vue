<template>
  <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">{{ $t('profile.export.title') }}</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ $t('profile.export.subtitle') }}</p>
      </div>
      <button @click="exportData" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ $t('profile.export.button') }}
      </button>
    </div>
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5">
      <div v-if="exported" class="text-green-600 dark:text-green-400">{{ $t('profile.export.success') }}</div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
const exported = ref(false);
function exportData() {
  exported.value = true;
  // TODO: handle data export (GDPR)
}
</script>
