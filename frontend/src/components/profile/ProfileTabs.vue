<template>
  <div class="mb-6">
    <nav class="flex space-x-4" aria-label="Tabs">
      <button v-for="tab in tabs" :key="tab.key" @click="$emit('update:tab', tab.key)" :class="[
        tab.key === modelValue
          ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200'
          : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200',
        'px-3 py-2 font-medium text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500'
      ]" :aria-current="tab.key === modelValue ? 'page' : undefined">
        {{ $t(tab.label) }}
      </button>
    </nav>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
defineProps({
  modelValue: String,
  tabs: Array
});
defineEmits(['update:tab']);
</script>
