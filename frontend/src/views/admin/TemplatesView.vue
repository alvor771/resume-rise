<template>
  <div class="relative">
    <!-- Preview Modal -->
    <div v-if="previewTemplateData" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="previewTemplateData = null"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
          <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                  {{ previewTemplateData.name }} - {{ $t('admin.templates.actions.preview') }}
                </h3>
                <div class="mt-2">
                  <img :src="previewTemplateData.preview" :alt="previewTemplateData.name" class="w-full h-auto border border-gray-200 dark:border-gray-700 rounded">
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" @click="previewTemplateData = null" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
              {{ $t('common.close') }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $t('admin.nav.templates') }}</h1>
        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $t('admin.templates.manage_templates') }}</p>
      </div>
      <div class="mt-4 md:mt-0">
        <router-link 
          to="/admin/templates/new"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          New Template
        </router-link>
      </div>
    </div>

    <!-- Template Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <!-- Add New Template Card -->
      <div class="h-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-all duration-200 cursor-pointer flex flex-col items-center justify-center min-h-[400px]">
        <div class="p-4 rounded-full bg-indigo-100 dark:bg-indigo-900/50 mb-4">
          <svg class="h-10 w-10 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $t('admin.templates.new_template') }}</h3>
        <p class="mt-2 text-gray-500 dark:text-gray-400">
          {{ $t('admin.templates.create_new') }}
        </p>
      </div>
      
      <!-- Template Cards -->
      <div 
        v-for="template in templates" 
        :key="template.id"
        class="group bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-all duration-200 flex flex-col h-full hover:ring-2 hover:ring-indigo-500"
      >
        <router-link 
          :to="{ name: 'AdminTemplateDetail', params: { id: template.id } }"
          class="block flex-grow"
        >
          <div class="relative h-64 overflow-hidden group">
            <div class="absolute inset-0 transition-transform duration-300 group-hover:scale-105">
              <img 
                :src="template.thumbnail" 
                :alt="template.name" 
                class="w-full h-full object-cover"
                :class="template.bgColor"
              >
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
              <div class="text-white text-sm font-medium">
                {{ template.description }}
              </div>
            </div>
            <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-semibold rounded-full shadow-sm" 
                  :class="[getStatusBadgeClass(template.is_active ? 'active' : 'inactive'), 'backdrop-blur-sm bg-white/30 dark:bg-black/30']">
              {{ $t(`admin.templates.status.${(template.is_active ? 'active' : 'inactive')}`) }}
            </span>
          </div>
          <div class="p-5">
            <div class="flex justify-between items-start mb-2">
              <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ template.name }}</h3>
              <span class="text-xs font-medium px-2 py-1 rounded" :class="[template.bgColor, template.textColor]">
                v{{ template.version }}
              </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">{{ template.description }}</p>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ template.usage_count || 0 }} {{ $t('admin.templates.uses') }}
              </span>
            </div>
          </div>
        </router-link>
        
        <!-- Action Buttons -->
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-700/50 backdrop-blur-sm">
          <div class="flex justify-between items-center">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
              {{ $t('admin.templates.last_updated') }}: {{ formatDate(template.updated_at) }}
            </span>
            <div class="flex space-x-1">
              <button 
                @click.stop="previewTemplate(template)" 
                :title="$t('admin.templates.actions.preview')" 
                class="p-1.5 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-600 transition-colors duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
              <button 
                @click.stop="editTemplate(template.id)" 
                :title="$t('admin.templates.actions.edit')" 
                class="p-1.5 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-600 transition-colors duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button 
                @click.stop="duplicateTemplate(template.id)" 
                :title="$t('admin.templates.actions.duplicate')" 
                class="p-1.5 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-600 transition-colors duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
              </button>
              <button 
                @click.stop="confirmDeleteTemplate(template)" 
                :title="$t('admin.templates.actions.delete')" 
                class="p-1.5 rounded-full text-red-500 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ $t('admin.templates.delete_confirm.title') }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-300">
                  {{ $t('admin.templates.delete_confirm.message') }} <span class="font-semibold">{{ templateToDelete?.name }}</span>?
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button type="button" @click="closeDeleteModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
              {{ $t('admin.templates.delete_confirm.cancel') }}
            </button>
            <button type="button" @click="deleteTemplate" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:col-start-1 sm:text-sm">
              {{ $t('admin.templates.delete_confirm.confirm') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useTemplatesStore } from '@/stores/templates';

export default {
  name: 'TemplatesView',
  setup() {
    const router = useRouter();
    const { t } = useI18n();
    const store = useTemplatesStore();
    
    // Format date for display
    const formatDate = (dateString) => {
      if (!dateString) return '';
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      const locale = localStorage.getItem('i18n_locale') || 'en';
      return new Date(dateString).toLocaleDateString(locale, options);
    };
    
    const showDeleteModal = ref(false);
    const templateToDelete = ref(null);
    const previewTemplateData = ref(null);
    
    const templates = computed(() => store.items);
    
    // Get badge class based on status
    const getStatusBadgeClass = (status) => {
      switch (status.toLowerCase()) {
        case 'active':
          return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'inactive':
          return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'draft':
          return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        default:
          return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
      }
    };
    
    // Edit template
    const editTemplate = (id) => {
      router.push({ name: 'AdminEditTemplate', params: { id } });
    };
    
    // Preview template
    const previewTemplate = (template) => {
      previewTemplateData.value = { ...template };
    };

    // Duplicate template
    const duplicateTemplate = (id) => {
      // TODO: implement duplicate API if needed
      console.log('Duplicate template', id);
    };
    
    // Confirm delete template
    const confirmDeleteTemplate = (template) => {
      templateToDelete.value = template;
      showDeleteModal.value = true;
    };
    

    
    // Close modal function
    const closeDeleteModal = () => {
      showDeleteModal.value = false;
      templateToDelete.value = null;
    };
    
    // Get category badge class (for future use)
    const getCategoryBadgeClass = (category) => {
      const categories = {
        'professional': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'creative': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'minimal': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'modern': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200'
      };
      return categories[category?.toLowerCase()] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    };
    
    // Delete template
    const deleteTemplate = async () => {
      if (!templateToDelete.value) return;
      try {
        await store.remove(templateToDelete.value.id);
      } finally {
        closeDeleteModal();
      }
    };
    
    // Use the t function to avoid unused variable warning
    const pageTitle = t('admin.nav.templates');
    document.title = `${pageTitle} | ResumeRise Admin`;

    onMounted(() => {
      store.fetchList({ per_page: 24 }).catch(() => {});
    });
    
    return {
      templates,
      showDeleteModal,
      templateToDelete,
      previewTemplateData,
      previewTemplate,
      confirmDeleteTemplate,
      deleteTemplate,
      closeDeleteModal,
      editTemplate,
      duplicateTemplate,
      getStatusBadgeClass,
      getCategoryBadgeClass,
      formatDate,
      t
    };
  }
};
</script>
