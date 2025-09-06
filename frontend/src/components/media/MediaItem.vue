<template>
  <div
    class="media-item group"
    :class="{ 'ring-2 ring-indigo-500 ring-offset-2': isSelected }"
    @click="$emit('select', item.id)"
  >
    <div class="relative">
      <!-- Thumbnail -->
      <div class="media-thumbnail">
        <img
          v-if="isImage"
          :src="item.url"
          :alt="item.alt_text || ''"
          class="w-full h-full object-cover"
          loading="lazy"
        />
        <div
          v-else-if="isVideo"
          class="flex items-center justify-center w-full h-full bg-gray-100 dark:bg-gray-800"
        >
          <video
            :src="item.url"
            class="max-w-full max-h-full"
            preload="metadata"
          ></video>
        </div>
        <div
          v-else
          class="flex flex-col items-center justify-center w-full h-full p-4 text-center bg-gray-100 dark:bg-gray-800"
        >
          <DocumentIcon class="h-12 w-12 text-gray-400 mb-2" />
          <span class="text-xs text-gray-500 dark:text-gray-400 truncate w-full">
            {{ item.name }}
          </span>
        </div>
      </div>

      <!-- Selection Toggle -->
      <div 
        class="absolute top-2 left-2 z-10 transition-opacity duration-200 opacity-0 group-hover:opacity-100"
        :class="{ 'opacity-100': isSelected }"
        @click.stop="$emit('select', item.id)"
      >
        <BaseToggle
          :modelValue="isSelected"
          size="small"
          color="primary"
          @update:modelValue="$emit('select', item.id)"
          class="bg-white/90 dark:bg-gray-700/90 rounded-full p-0.5 shadow-md backdrop-blur-sm"
        />
      </div>

      <!-- Hover Actions -->
      <div class="media-actions">
        <button
          class="p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition-colors"
          :title="$t('common.preview')"
          @click.stop="previewMedia"
        >
          <EyeIcon class="h-5 w-5" />
        </button>
        <a
          :href="item.url"
          target="_blank"
          rel="noopener noreferrer"
          class="p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition-colors"
          :title="$t('common.download')"
          @click.stop
        >
          <ArrowDownTrayIcon class="h-5 w-5" />
        </a>
        <button
          class="p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition-colors"
          :title="$t('common.delete')"
          @click.stop="confirmDelete"
        >
          <TrashIcon class="h-5 w-5" />
        </button>
      </div>
    </div>

    <!-- Media Info -->
    <div class="p-2 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
      <div class="truncate text-sm font-medium text-gray-900 dark:text-white">
        {{ item.name }}
      </div>
      <div class="flex justify-between items-center mt-1">
        <span class="text-xs text-gray-500 dark:text-gray-400">
          {{ formatFileSize(item.size) }}
        </span>
        <span class="text-xs text-gray-500 dark:text-gray-400">
          {{ formatDate(item.created_at) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
// import { useToast } from 'vue-toastification';
import { DocumentIcon } from '@heroicons/vue/24/outline';
import { TrashIcon, EyeIcon } from '@heroicons/vue/24/solid';
import BaseToggle from '@/components/ui/BaseToggle.vue';
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  isSelected: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['select', 'delete']);
const { t } = useI18n();
// const toast = useToast();

const isImage = computed(() => props.item.type?.startsWith('image/'));
const isVideo = computed(() => props.item.type?.startsWith('video/'));

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  }).format(date);
};

const previewMedia = () => {
  // In a real app, you might want to open a modal with a larger preview
  window.open(props.item.url, '_blank');
};

const confirmDelete = () => {
  if (confirm(t('media.confirm_delete'))) {
    emit('delete', props.item.id);
  }
};
</script>

<style scoped>
.media-item {
  @apply relative rounded-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200 cursor-pointer;
}

.media-thumbnail {
  @apply aspect-square bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden;
}

.media-actions {
  @apply absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center space-x-2;
}
</style>
