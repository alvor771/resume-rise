<template>
  <div class="media-manager">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ $t("media.media_library") }}
      </h1>
      <button 
        @click="() => { console.log('Test button clicked'); isUploadModalOpen = !isUploadModalOpen }"
        class="px-4 py-2 bg-red-500 text-white rounded mr-2"
      >
        Test Toggle
      </button>
      <button 
        @click="() => console.log('Test button clicked')"
        class="mr-2 px-4 py-2 bg-red-500 text-white rounded"
      >
        Test Button
      </button>
      <button
        @click="openUploadModal"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        <PlusIcon class="h-5 w-5 mr-2" />
        {{ $t("media.upload_files") }}
      </button>
    </div>

    <!-- File Upload Modal -->
    <BaseModal
      :is-open="isUploadModalOpen"
      @close="closeUploadModal"
      :title="$t('media.upload_files')"
      class="z-50"
    >
      <div class="space-y-4">
        <div
          class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
          @dragover.prevent="onDragOver"
          @dragleave.prevent="onDragLeave"
          @drop.prevent="onDrop"
          :class="{
            'border-indigo-500 bg-indigo-50 dark:bg-gray-800': isDragging,
          }"
        >
          <div class="space-y-1 text-center">
            <CloudUploadIcon
              class="mx-auto h-12 w-12 text-gray-400"
              :class="{ 'text-indigo-500': isDragging }"
            />
            <div class="flex text-sm text-gray-600 dark:text-gray-300">
              <label
                for="file-upload"
                class="relative cursor-pointer bg-white dark:bg-gray-900 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
              >
                <span>{{ $t("media.upload_a_file") }}</span>
                <input
                  id="file-upload"
                  name="file-upload"
                  type="file"
                  class="sr-only"
                  multiple
                  @change="onFileSelected"
                />
              </label>
              <p class="pl-1">{{ $t("media.or_drag_and_drop") }}</p>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
              {{ $t("media.supported_formats") }}
            </p>
          </div>
        </div>

        <!-- Progress Bar -->
        <div v-if="uploadProgress > 0 && uploadProgress < 100" class="pt-2">
          <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
            <div
              class="h-full bg-indigo-600 rounded-full transition-all duration-300 ease-in-out"
              :style="{ width: `${uploadProgress}%` }"
            ></div>
          </div>
          <p class="mt-1 text-sm text-gray-500 text-right">
            {{ uploadProgress }}%
          </p>
        </div>

        <!-- Error Message -->
        <div
          v-if="uploadError"
          class="mt-4 p-4 bg-red-50 dark:bg-red-900/30 rounded-md"
        >
          <div class="flex">
            <div class="flex-shrink-0">
              <ExclamationCircleIcon
                class="h-5 w-5 text-red-400"
                aria-hidden="true"
              />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                {{ $t("media.upload_failed") }}
              </h3>
              <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                <p>{{ uploadError }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <template #footer>
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            class="inline-flex justify-center rounded-md border border-gray-300 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            @click="closeUploadModal"
          >
            {{ $t("common.cancel") }}
          </button>
          <button
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
            @click="uploadFiles"
            :disabled="isUploading || selectedFiles.length === 0"
          >
            <SpinnerIcon v-if="isUploading" class="animate-spin -ml-1 mr-2 h-4 w-4" />
            {{ isUploading ? $t('media.uploading') : $t('media.upload') }}
          </button>
        </div>
      </template>
    </BaseModal>

    <!-- View Toggle -->
    <div class="flex justify-end mb-4">
      <div class="inline-flex rounded-md shadow-sm" role="group">
        <button
          @click="viewMode = 'grid'"
          :class="{'bg-gray-100 dark:bg-gray-700': viewMode === 'grid'}"
          class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
        </button>
        <button
          @click="viewMode = 'table'"
          :class="{'bg-gray-100 dark:bg-gray-700': viewMode === 'table'}"
          class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 4a1 1 0 00-2 0v12a1 1 0 102 0V4zm7 0a1 1 0 10-2 0v12a1 1 0 102 0V4zm7 0a1 1 0 10-2 0v12a1 1 0 102 0V4z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Media Container -->
    <!-- Selection Toolbar -->
    <SelectionToolbar 
      v-if="isSelectionMode"
      :selected-count="selectedMedia.size"
      @copy="handleToolbarAction('copy')"
      @rename="handleToolbarAction('rename')"
      @move="handleToolbarAction('move')"
      @delete="handleToolbarAction('delete')"
      @clear-selection="clearSelection"
      class="mb-4"
    />

    <div class="mt-2">
      <!-- Filters -->
      <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4"
      >
        <div class="relative w-full sm:w-64">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
          </div>
          <input
            v-model="searchQuery"
            type="text"
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-800 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            :placeholder="$t('common.search')"
          />
        </div>

        <div class="flex items-center space-x-2">
          <select
            v-model="mediaTypeFilter"
            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-800 dark:text-white"
          >
            <option value="all">{{ $t("media.all_media") }}</option>
            <option value="image">{{ $t("media.images") }}</option>
            <option value="video">{{ $t("media.videos") }}</option>
            <option value="document">{{ $t("media.documents") }}</option>
          </select>

          <button
            @click="refreshMedia"
            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
            :title="$t('common.refresh')"
          >
            <ArrowPathIcon class="h-5 w-5" />
          </button>
        </div>
      </div>

      <!-- Media Items -->
      <div v-if="isLoading" class="flex justify-center items-center py-12">
        <SpinnerIcon class="h-8 w-8 text-indigo-600 animate-spin" />
      </div>

      <div v-else-if="filteredMedia.length === 0" class="text-center py-12">
        <FolderOpenIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ $t("media.no_media") }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t("media.get_started_by_uploading") }}
        </p>
        <div class="mt-6">
          <button
            @click="openUploadModal"
            type="button"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ $t("media.upload_files") }}
          </button>
        </div>
      </div>

      <!-- Grid View -->
      <div v-if="viewMode === 'grid' && filteredMedia.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        <MediaItem
          v-for="item in paginatedMedia"
          :key="item.id"
          :item="item"
          :is-selected="isSelected(item.id)"
          @select="toggleSelect"
          @delete="deleteMedia"
        />
      </div>

      <!-- Table View -->
      <div v-else-if="viewMode === 'table' && filteredMedia.length > 0" class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                <BaseToggle
                  :model-value="allSelected"
                  @update:model-value="toggleSelectAll"
                  size="small"
                  color="primary"
                  class="bg-transparent shadow-none"
                />
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('media.name') }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('media.type') }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('media.size') }}
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ $t('media.uploaded') }}
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">{{ $t('common.actions') }}</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in paginatedMedia" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap">
                <BaseToggle
                  :model-value="isSelected(item.id)"
                  @update:model-value="(value) => toggleSelect(item.id)"
                  size="small"
                  color="primary"
                  class="bg-transparent shadow-none"
                />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img v-if="item.type && item.type.startsWith('image/')" class="h-10 w-10 rounded object-cover" :src="item.url" :alt="item.alt_text || ''">
                    <div v-else class="h-10 w-10 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                      <DocumentIcon class="h-6 w-6 text-gray-400" />
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs">
                      {{ item.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ formatFileSize(item.size) }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">{{ item.type || 'file' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">{{ formatFileSize(item.size) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(item.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2 justify-end">
                  <button @click="previewItem(item)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" :title="$t('common.preview')">
                    <EyeIcon class="h-5 w-5" />
                  </button>
                  <a :href="item.url" download :title="$t('common.download')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                    <ArrowDownTrayIcon class="h-5 w-5" />
                  </a>
                  <button @click="deleteMedia(item.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" :title="$t('common.delete')">
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav
        class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700"
        >
          {{ $t("common.previous") }}
        </button>
        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700"
        >
          {{ $t("common.next") }}
        </button>
      </nav>
      <div
        class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
      >
        <div>
          <p class="text-sm text-gray-700 dark:text-gray-300">
            {{ $t("common.showing") }}
            <span class="font-medium">{{
              (currentPage - 1) * perPage + 1
            }}</span>
            {{ $t("common.to") }}
            <span class="font-medium">
              {{ Math.min(currentPage * perPage, totalItems) }}
            </span>
            {{ $t("common.of") }}
            <span class="font-medium">{{ totalItems }}</span>
            {{ $t("common.results") }}
          </p>
        </div>
        <div>
          <nav
            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
          >
            <button
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <span class="sr-only">{{ $t("common.previous") }}</span>
              <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            </button>
            <template v-for="page in visiblePages" :key="page">
              <button
                v-if="page === '...'"
                disabled
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                ...
              </button>
              <button
                v-else
                @click="currentPage = page"
                :class="{
                  'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300':
                    currentPage === page,
                  'bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700':
                    currentPage !== page,
                }"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              >
                {{ page }}
              </button>
            </template>
            <button
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <span class="sr-only">{{ $t("common.next") }}</span>
              <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
            </button>
          </nav>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import SelectionToolbar from '@/components/media/SelectionToolbar.vue';
import { useToast } from "vue-toastification";
import {
  PlusIcon,
  CloudUploadIcon,
  ExclamationCircleIcon,
  MagnifyingGlassIcon,
  ArrowPathIcon,
  FolderOpenIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  DocumentIcon,
  EyeIcon,
  TrashIcon,
  ArrowDownTrayIcon,
} from "@heroicons/vue/24/outline";
import BaseModal from "@/components/ui/BaseModal.vue";
import MediaItem from "@/components/media/MediaItem.vue";
import SpinnerIcon from "@/components/icons/SpinnerIcon.vue";
import api from "@/services/api";
import BaseToggle from '@/components/ui/BaseToggle.vue';

const { t } = useI18n();
const toast = useToast();

// State
const media = ref([]);
const selectedMedia = ref(new Set());
// const selectAll = ref(false);
const isSelectionMode = ref(false);
const isUploadModalOpen = ref(false);
const isDragging = ref(false);
const selectedFiles = ref([]);
const uploadProgress = ref(0);
const isUploading = ref(false);
const uploadError = ref(null);
const isLoading = ref(false);
const searchQuery = ref("");
const viewMode = ref('grid'); // 'grid' or 'table'
const mediaTypeFilter = ref("all");
const currentPage = ref(1);
const perPage = 24;
const totalItems = ref(0);

// Computed
const filteredMedia = computed(() => {
  return media.value.filter((item) => {
    // Filter by search query
    const matchesSearch =
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.alt_text?.toLowerCase().includes(searchQuery.value.toLowerCase());

    // Filter by media type
    const matchesType =
      mediaTypeFilter.value === "all" ||
      (mediaTypeFilter.value === "image" && item.type.startsWith("image/")) ||
      (mediaTypeFilter.value === "video" && item.type.startsWith("video/")) ||
      (mediaTypeFilter.value === "document" &&
        !item.type.startsWith("image/") &&
        !item.type.startsWith("video/"));

    return matchesSearch && matchesType;
  });
});

const totalPages = computed(() => {
  return Math.ceil(totalItems.value / perPage);
});

const paginatedMedia = computed(() => {
  const startIndex = (currentPage.value - 1) * perPage;
  return filteredMedia.value.slice(startIndex, startIndex + perPage);
});

const visiblePages = computed(() => {
  const pages = [];
  const range = 2; // Number of pages to show before and after current page

  // Always show first page
  if (currentPage.value > range + 1) {
    pages.push(1);
    if (currentPage.value > range + 2) {
      pages.push("...");
    }
  }

  // Calculate range around current page
  const start = Math.max(2, currentPage.value - range);
  const end = Math.min(totalPages.value - 1, currentPage.value + range);

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }

  // Always show last page
  if (currentPage.value < totalPages.value - range) {
    if (currentPage.value < totalPages.value - range - 1) {
      pages.push("...");
    }
    pages.push(totalPages.value);
  }

  return pages;
});

// Methods
// Track if this is the initial load
let initialLoad = true;
const maxRetries = 3;
let retryCount = 0;

const fetchMedia = async (isRetry = false) => {
  if (isRetry) {
    console.log(`Retrying media fetch (attempt ${retryCount + 1}/${maxRetries})...`);
  } else {
    console.log('Fetching media with params:', {
      page: currentPage.value,
      per_page: perPage,
      search: searchQuery.value,
      type: mediaTypeFilter.value === "all" ? undefined : mediaTypeFilter.value,
    });
  }
  
  try {
    isLoading.value = true;
    
    // Only refresh CSRF token on initial load or retry
    if (initialLoad || isRetry) {
      try {
        await api.get('/sanctum/csrf-cookie');
      } catch (csrfError) {
        console.warn('CSRF token refresh failed, continuing anyway:', csrfError);
      }
    }
    
    // Use the full path including /api prefix
    const response = await api.get("/admin/media", {
      params: {
        page: currentPage.value,
        per_page: perPage,
        search: searchQuery.value,
        type: mediaTypeFilter.value === "all" ? undefined : mediaTypeFilter.value,
      },
      validateStatus: (status) => status < 500, // Don't throw for 4xx errors
    });

    if (response.status === 401) {
      // Unauthorized - redirect to login
      window.location.href = '/login';
      return;
    }
    
    if (response.status === 403) {
      // Forbidden - user doesn't have permission
      toast.error(t('errors.forbidden'));
      return;
    }
    
    if (response.status === 419) {
      // CSRF token mismatch - try to refresh and retry
      if (retryCount < maxRetries) {
        retryCount++;
        console.log(`CSRF token mismatch, retrying (${retryCount}/${maxRetries})...`);
        await new Promise(resolve => setTimeout(resolve, 1000 * retryCount)); // Exponential backoff
        return fetchMedia(true);
      }
    }
    
    if (response.status !== 200) {
      throw new Error(`API returned status ${response.status}`);
    }

    console.log('Media API response:', response.data);
    media.value = response.data.data || [];
    totalItems.value = response.data.meta?.total || 0;
    
    console.log('Media items loaded:', media.value.length);
    console.log('Total items:', totalItems.value);
  } catch (error) {
    console.error("Failed to fetch media:", error);
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      console.error('Response data:', error.response.data);
      console.error('Response status:', error.response.status);
      console.error('Response headers:', error.response.headers);
      
      if (error.response.status === 401) {
        toast.error(t('auth.unauthorized'));
        window.location.href = '/login';
      } else if (error.response.status === 403) {
        toast.error(t('errors.forbidden'));
      } else {
        toast.error(error.response.data.message || t("media.failed_to_load"));
      }
    } else if (error.request) {
      // The request was made but no response was received
      console.error('No response received:', error.request);
      toast.error(t('errors.network_error'));
    } else {
      // Something happened in setting up the request that triggered an Error
      console.error('Error:', error.message);
      toast.error(t("media.failed_to_load"));
    }
  } finally {
    isLoading.value = false;
  }
};

const refreshMedia = () => {
  currentPage.value = 1;
  fetchMedia();
};

const openUploadModal = () => {
  console.log('openUploadModal called');
  console.log('Current isUploadModalOpen before:', isUploadModalOpen.value);
  isUploadModalOpen.value = true;
  console.log('Current isUploadModalOpen after:', isUploadModalOpen.value);
  uploadError.value = null;
};

const closeUploadModal = () => {
  isUploadModalOpen.value = false;
  selectedFiles.value = [];
  uploadProgress.value = 0;
  uploadError.value = null;
};

const onDragOver = (e) => {
  e.preventDefault();
  isDragging.value = true;
};

const onDragLeave = () => {
  isDragging.value = false;
};

const onDrop = (e) => {
  e.preventDefault();
  isDragging.value = false;

  if (e.dataTransfer.files.length) {
    handleFiles(Array.from(e.dataTransfer.files));
  }
};

const onFileSelected = (e) => {
  if (e.target.files.length) {
    handleFiles(Array.from(e.target.files));
    // Reset the input so the same file can be selected again
    e.target.value = "";
  }
};

const handleFiles = (files) => {
  const validFiles = files.filter((file) => {
    // Basic file type validation
    const validTypes = [
      "image/jpeg",
      "image/png",
      "image/gif",
      "image/webp",
      "image/svg+xml",
      "application/pdf",
      "video/mp4",
    ];
    if (!validTypes.includes(file.type)) {
      toast.warning(t("media.invalid_file_type", { file: file.name }));
      return false;
    }

    // File size limit (10MB)
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
      toast.warning(t("media.file_too_large", { file: file.name }));
      return false;
    }

    return true;
  });

  selectedFiles.value = [...selectedFiles.value, ...validFiles];
};

const uploadFiles = async () => {
  if (!selectedFiles.value.length) return;

  const formData = new FormData();
  selectedFiles.value.forEach((file, index) => {
    formData.append(`files[${index}]`, file);
  });

  try {
    isUploading.value = true;
    uploadError.value = null;

    await api.post("/admin/media", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      onUploadProgress: (progressEvent) => {
        if (progressEvent.total) {
          uploadProgress.value = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
        }
      },
    });

    toast.success(t("media.upload_success"));
    closeUploadModal();
    await fetchMedia();
  } catch (error) {
    console.error("Upload failed:", error);
    uploadError.value =
      error.response?.data?.message || t("media.upload_failed");
  } finally {
    isUploading.value = false;
  }
};

const isSelected = (id) => {
  return selectedMedia.value.has(id);
};

const deleteMedia = async (id) => {
  if (!confirm(t("media.confirm_delete"))) return;

  try {
    await api.delete(`/admin/media/${id}`);
    toast.success(t("media.delete_success"));
    await fetchMedia();
  } catch (error) {
    console.error("Delete failed:", error);
    toast.error(t("media.delete_failed"));
  }
};

// Format file size
const formatFileSize = (bytes) => {
  if (!bytes) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Preview item
const previewItem = (item) => {
  if (item.type && item.type.startsWith('image/')) {
    window.open(item.url, '_blank');
  } else {
    // For non-image files, trigger download
    const link = document.createElement('a');
    link.href = item.url;
    link.download = item.name;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
};

// Toggle select all
// const toggleSelectAll = (event) => {
//   if (event.target.checked) {
//     filteredMedia.value.forEach(item => {
//       selectedMedia.value.add(item.id);
//     });
//   } else {
//     selectedMedia.value.clear();
//   }
//   // Create a new Set to trigger reactivity
//   selectedMedia.value = new Set(selectedMedia.value);
// };

// Check if all items are selected
const allSelected = computed({
  get: () => {
    return filteredMedia.value.length > 0 && 
           filteredMedia.value.every(item => selectedMedia.value.has(item.id));
  },
  set: (value) => {
    if (value) {
      filteredMedia.value.forEach(item => selectedMedia.value.add(item.id));
    } else {
      filteredMedia.value.forEach(item => selectedMedia.value.delete(item.id));
    }
    updateSelectionMode();
  }
});

// Update selection mode based on selected items
const updateSelectionMode = () => {
  isSelectionMode.value = selectedMedia.value.size > 0;
};

// Toggle select all items
const toggleSelectAll = (value) => {
  if (value) {
    // Select all items on current page
    const newSelection = new Set(selectedMedia.value);
    paginatedMedia.value.forEach(item => {
      newSelection.add(item.id);
    });
    selectedMedia.value = newSelection;
  } else {
    // Clear selection
    selectedMedia.value.clear();
  }
  updateSelectionMode();
};

// Toggle selection for a single item
const toggleSelect = (id) => {
  const newSelection = new Set(selectedMedia.value);
  if (newSelection.has(id)) {
    newSelection.delete(id);
  } else {
    newSelection.add(id);
  }
  selectedMedia.value = newSelection;
  updateSelectionMode();
};

// Clear selection
const clearSelection = () => {
  selectedMedia.value.clear();
  updateSelectionMode();  
};

// Handle toolbar actions
const handleToolbarAction = (action) => {
  switch (action) {
    case 'delete':
      if (confirm(t('media.confirm_delete_multiple', { count: selectedMedia.value.size }))) {
        deleteSelectedMedia();
      }
      break;
    case 'copy':
      // Implement copy logic here
      toast.info(t('media.copy_selected', { count: selectedMedia.value.size }));
      break;
    case 'rename':
      // Implement rename logic here
      toast.info(t('media.rename_selected', { count: selectedMedia.value.size }));
      break;
    case 'move':
      // Implement move logic here
      toast.info(t('media.move_selected', { count: selectedMedia.value.size }));
      break;
  }
};

// Delete all selected media
const deleteSelectedMedia = async () => {
  try {
    const deletePromises = Array.from(selectedMedia.value).map(id => 
      api.delete(`/admin/media/${id}`).catch(e => {
        console.error(`Failed to delete media ${id}:`, e);
        return null;
      })
    );
    
    await Promise.all(deletePromises);
    
    toast.success(t('media.delete_multiple_success', { count: selectedMedia.value.size }));
    await fetchMedia();
    clearSelection();
  } catch (error) {
    console.error('Delete failed:', error);
    toast.error(t('media.delete_failed'));
  }
};

// Watchers
watch([searchQuery, mediaTypeFilter], () => {
  currentPage.value = 1;
  fetchMedia();
});

watch(currentPage, () => {
  fetchMedia();
  // Scroll to top of the page
  window.scrollTo({ top: 0, behavior: "smooth" });
});

// Test API connection
const testApiConnection = async () => {
  try {
    console.log('Testing API connection...');
    const response = await api.get('/sanctum/csrf-cookie');
    console.log('CSRF Token:', response.config.headers['X-XSRF-TOKEN']);
    
    const testResponse = await api.get('/user');
    console.log('User data:', testResponse.data);
    
    return true;
  } catch (error) {
    console.error('API connection test failed:', error);
    toast.error('Failed to connect to the API. Please check your connection and try again.');
    return false;
  }
};

// Lifecycle hooks
onMounted(async () => {
  // Always try to fetch media, but also check API connection for debugging
  fetchMedia();
  
  // Test API connection in the background for debugging
  const isConnected = await testApiConnection();
  if (!isConnected) {
    console.warn('API connection test failed, but continuing with media fetch...');
  }
});
</script>

<style scoped>
.media-manager {
  padding: 1rem;
}

@media (min-width: 640px) {
  .media-manager {
    padding: 1.5rem;
  }
}

.media-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

@media (min-width: 640px) {
  .media-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@media (min-width: 768px) {
  .media-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
}

@media (min-width: 1024px) {
  .media-grid {
    grid-template-columns: repeat(5, minmax(0, 1fr));
  }
}

@media (min-width: 1280px) {
  .media-grid {
    grid-template-columns: repeat(6, minmax(0, 1fr));
  }
}

.media-item {
  position: relative;
  border-radius: 0.375rem;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  transition-property: box-shadow, transform, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter, -webkit-backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

.dark .media-item {
  border-color: #374151;
}

.media-item:hover {
  --tw-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  --tw-shadow-colored: 0 4px 6px -1px var(--tw-shadow-color), 0 2px 4px -2px var(--tw-shadow-color);
  box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
}

.media-item.selected {
  --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
  --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
  box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
  --tw-ring-opacity: 1;
  --tw-ring-color: rgb(99 102 241 / var(--tw-ring-opacity));
  --tw-ring-offset-width: 2px;
}

.media-thumbnail {
  aspect-ratio: 1 / 1;
  background-color: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.dark .media-thumbnail {
  background-color: #1f2937;
}

.media-thumbnail img,
.media-thumbnail video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.media-actions {
  position: absolute;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  opacity: 0;
  transition-property: opacity;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.media-actions:hover {
  opacity: 1;
}

.media-info {
  padding: 0.5rem;
  font-size: 0.75rem;
  color: #6b7280;
}

.dark .media-info {
  color: #94a3b8;
}
</style>
