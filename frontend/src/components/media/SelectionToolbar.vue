<template>
  <Transition
    enter-active-class="transition-opacity duration-150"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div 
      v-if="selectedCount > 0"
      class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white dark:bg-gray-800 shadow-lg rounded-lg px-4 py-2 flex items-center space-x-2 z-50 border border-gray-200 dark:border-gray-700"
    >
      <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $t('media.selected_items', { count: selectedCount }) }}
      </div>
      
      <div class="h-6 border-l border-gray-200 dark:border-gray-700 mx-2"></div>
      
      <button
        v-for="action in actions"
        :key="action.name"
        @click="$emit(action.event)"
        :class="[
          'inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md',
          action.danger 
            ? 'text-red-700 bg-red-100 hover:bg-red-200 dark:text-red-100 dark:bg-red-800 dark:hover:bg-red-700' 
            : 'text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600'
        ]"
        :title="action.title"
      >
        <component :is="action.icon" class="h-4 w-4 mr-1" />
        {{ action.label }}
      </button>
      
      <button
        @click="$emit('clear-selection')"
        class="ml-2 p-1 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
        :title="$t('common.clear_selection')"
      >
        <XMarkIcon class="h-4 w-4" />
      </button>
    </div>
  </Transition>
</template>

<script setup>
import { XMarkIcon, DocumentDuplicateIcon, PencilIcon, FolderIcon, TrashIcon } from '@heroicons/vue/24/outline';

const _props = defineProps({
  selectedCount: {
    type: Number,
    required: true,
    default: 0
  }
});

const _emit = defineEmits([
  'copy',
  'rename',
  'move',
  'delete',
  'clear-selection'
]);

const actions = [
  {
    name: 'copy',
    label: 'Копировать',
    event: 'copy',
    icon: DocumentDuplicateIcon
  },
  {
    name: 'rename',
    label: 'Переименовать',
    event: 'rename',
    icon: PencilIcon
  },
  {
    name: 'move',
    label: 'Переместить',
    event: 'move',
    icon: FolderIcon
  },
  {
    name: 'delete',
    label: 'Удалить',
    event: 'delete',
    icon: TrashIcon,
    danger: true
  }
];
</script>
