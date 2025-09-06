<template>
  <div class="space-y-6">
    <!-- Header with actions and view toggle -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-xl p-6 shadow-lg">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h2 class="text-2xl md:text-3xl font-bold text-white">{{ $t('user.templates.my_templates') }}</h2>
            <p class="text-indigo-100 mt-1">Create and manage your resume templates</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <!-- View Toggle -->
            <div class="inline-flex rounded-md shadow-sm" role="group">
              <button
                @click="viewMode = 'grid'"
                :class="[
                  'px-4 py-2 text-sm font-medium rounded-l-lg border',
                  viewMode === 'grid'
                    ? 'bg-white/20 text-white border-white/30 backdrop-blur-sm'
                    : 'bg-white/10 text-white/90 border-white/20 hover:bg-white/20'
                ]"
                :title="$t('common.grid_view')"
              >
                <ViewGridIcon class="h-5 w-5" />
              </button>
              <button
                @click="viewMode = 'list'"
                :class="[
                  'px-4 py-2 text-sm font-medium rounded-r-lg border',
                  viewMode === 'list'
                    ? 'bg-white/20 text-white border-white/30 backdrop-blur-sm'
                    : 'bg-white/10 text-white/90 border-white/20 hover:bg-white/20'
                ]"
                :title="$t('common.list_view')"
              >
                <ViewListIcon class="h-5 w-5" />
              </button>
            </div>
            
            <!-- Create New Template Button -->
            <router-link
              to="/template-builder"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200"
            >
              <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
              {{ $t('user.templates.create_new') }}
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col sm:flex-row gap-4">
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input
          v-model="searchQuery"
          type="text"
          :placeholder="$t('common.search_placeholder')"
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
        />
      </div>
      <select
        v-model="statusFilter"
        class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm rounded-md"
      >
        <option value="all">{{ $t('common.all_status') }}</option>
        <option value="published">{{ $t('common.published') }}</option>
        <option value="draft">{{ $t('common.draft') }}</option>
      </select>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="i in 3" :key="`skeleton-${i}`" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden animate-pulse">
        <div class="h-48 bg-gray-200 dark:bg-gray-700"></div>
        <div class="p-4">
          <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
          <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-4"></div>
          <div class="flex space-x-2">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-16"></div>
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-20"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Grid View -->
    <div v-else-if="filteredTemplates.length > 0 && viewMode === 'grid'" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div
        v-for="template in filteredTemplates"
        :key="template.id"
        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col h-full"
      >
        <!-- Template Preview Image -->
        <div class="relative h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
          <img 
            :src="template.previewImage || 'https://via.placeholder.com/400x300?text=Template+Preview'" 
            :alt="template.name"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
          />
          <!-- Status Badge -->
          <div class="absolute top-3 right-3 z-10">
            <span
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                template.status === 'published' 
                  ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                  : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
              ]"
            >
              {{ template.status === 'published' ? $t('common.published') : $t('common.draft') }}
            </span>
          </div>
          
          <!-- Premium Badge -->
          <div v-if="template.isPremium" class="absolute top-3 left-3 z-10">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
              {{ $t('common.premium') }}
            </span>
          </div>
          
          <!-- Quick Actions Overlay -->
          <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100">
            <button
              @click.stop="previewTemplate(template.id)"
              class="p-2 rounded-full bg-white text-indigo-600 hover:bg-indigo-50 transition-colors duration-200 shadow-sm"
              :title="$t('common.preview')"
            >
              <EyeIcon class="h-5 w-5" />
            </button>
            <button
              @click.stop="editTemplate(template.id)"
              class="p-2 rounded-full bg-white text-indigo-600 hover:bg-indigo-50 transition-colors duration-200 shadow-sm"
              :title="$t('common.edit')"
            >
              <PencilIcon class="h-5 w-5" />
            </button>
          </div>
        </div>
        
        <!-- Template Info -->
        <div class="p-4 flex-1 flex flex-col">
          <div class="flex justify-between items-start">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate pr-2">
              {{ template.name }}
            </h3>
            <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
              {{ template.price > 0 ? `$${template.price.toFixed(2)}` : $t('common.free') }}
            </span>
          </div>
          
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2 flex-1">
            {{ template.description || $t('user.templates.no_description') }}
          </p>
          
          <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div class="flex items-center space-x-2">
              <DocumentDownloadIcon class="h-4 w-4 text-gray-400" />
              <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ template.downloads }} {{ $t('user.templates.downloads') }}
              </span>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
              {{ formatDate(template.updatedAt) }}
            </div>
          </div>
        </div>
        
        <!-- Template Actions -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
          <button
            @click="duplicateTemplate(template.id)"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center"
          >
            <DocumentDuplicateIcon class="h-4 w-4 mr-1" />
            {{ $t('common.duplicate') }}
          </button>
          <div class="flex items-center space-x-2">
            <button
              @click="confirmDelete(template.id)"
              class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30"
              :title="$t('common.delete')"
            >
              <TrashIcon class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- List View -->
    <div v-else-if="filteredTemplates.length > 0 && viewMode === 'list'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        <li v-for="template in filteredTemplates" :key="`list-${template.id}`" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
          <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16 rounded-md overflow-hidden border border-gray-200 dark:border-gray-600">
                  <img 
                    :src="template.previewImage || 'https://via.placeholder.com/80?text=Template'" 
                    :alt="template.name"
                    class="h-full w-full object-cover"
                  />
                </div>
                <div>
                  <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-medium text-indigo-600 dark:text-indigo-400 truncate max-w-xs">
                      {{ template.name }}
                    </h3>
                    <span
                      v-if="template.isPremium"
                      class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-purple-500 to-indigo-600 text-white"
                    >
                      {{ $t('common.premium') }}
                    </span>
                  </div>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                    {{ template.description || $t('user.templates.no_description') }}
                  </p>
                  <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                    <span class="flex items-center">
                      <DocumentDownloadIcon class="h-3.5 w-3.5 mr-1" />
                      {{ template.downloads }} {{ $t('user.templates.downloads') }}
                    </span>
                    <span class="flex items-center">
                      <CalendarIcon class="h-3.5 w-3.5 mr-1" />
                      {{ formatDate(template.updatedAt) }}
                    </span>
                    <span
                      :class="[
                        'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                        template.status === 'published' 
                          ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                          : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                      ]"
                    >
                      {{ template.status === 'published' ? $t('common.published') : $t('common.draft') }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ template.price > 0 ? `$${template.price.toFixed(2)}` : $t('common.free') }}
                </span>
                <div class="flex items-center space-x-2">
                  <button
                    @click="previewTemplate(template.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1.5 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30"
                    :title="$t('common.preview')"
                  >
                    <EyeIcon class="h-5 w-5" />
                  </button>
                  <button
                    @click="editTemplate(template.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1.5 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30"
                    :title="$t('common.edit')"
                  >
                    <PencilIcon class="h-5 w-5" />
                  </button>
                  <button
                    @click="duplicateTemplate(template.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1.5 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30"
                    :title="$t('common.duplicate')"
                  >
                    <DocumentDuplicateIcon class="h-5 w-5" />
                  </button>
                  <button
                    @click="confirmDelete(template.id)"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1.5 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30"
                    :title="$t('common.delete')"
                  >
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center">
      <TemplateIcon class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $t('user.templates.no_templates') }}
      </h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        {{ $t('user.templates.get_started') }}
      </p>
      <div class="mt-6">
        <router-link
          to="/template-builder"
          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('user.templates.create_template') }}
        </router-link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot as="template" :show="showDeleteModal">
      <HeadlessDialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="showDeleteModal = false">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <HeadlessDialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                  <ExclamationIcon class="h-6 w-6 text-red-600 dark:text-red-400" aria-hidden="true" />
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <HeadlessDialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ $t('user.templates.delete_confirm.title') }}
                  </HeadlessDialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('user.templates.delete_confirm.message') }}
                    </p>
                    <p v-if="templateToDelete?.isPremium" class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400">
                      {{ $t('user.templates.delete_confirm.premium_warning') }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                  type="button"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                  @click="deleteTemplate"
                >
                  {{ $t('common.delete') }}
                </button>
                <button
                  type="button"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                  @click="showDeleteModal = false"
                >
                  {{ $t('common.cancel') }}
                </button>
              </div>
            </div>
          </TransitionChild>
        </div>
      </HeadlessDialog>
    </TransitionRoot>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { format } from 'date-fns';
import { 
  TemplateIcon, 
  EyeIcon, 
  PencilIcon, 
  DocumentDuplicateIcon,
  ViewGridIcon,
  ViewListIcon,
  MagnifyingGlassIcon,
  DocumentDownloadIcon,
  CalendarIcon,
  TrashIcon, 
  PlusIcon,
  ExclamationIcon 
} from '@heroicons/vue/24/outline';
import { TransitionRoot, TransitionChild } from '@headlessui/vue';

export default {
  name: 'UserTemplates',
  components: {
    TemplateIcon,
    EyeIcon,
    PencilIcon,
    DocumentDuplicateIcon,
    TrashIcon,
    PlusIcon,
    ExclamationIcon,
    // Dialog components are used via <HeadlessDialog> in template
    TransitionRoot,
    TransitionChild,
    ViewGridIcon,
    ViewListIcon,
    MagnifyingGlassIcon,
    DocumentDownloadIcon,
    CalendarIcon
  },
  setup() {
    const router = useRouter();
    
    // State
    const isLoading = ref(false);
    const viewMode = ref('grid'); // 'grid' or 'list'
    const searchQuery = ref('');
    const statusFilter = ref('all'); // 'all', 'published', 'draft'
    const showDeleteModal = ref(false);
    const templateToDelete = ref(null);
    
    // i18n - keep this for potential future use
    const { t } = useI18n();
    
    // Initialize with empty array to avoid undefined errors
    const templates = ref([]);
    
    // Mock data - in a real app, this would come from a store or API
    const mockTemplates = [
      {
        id: '1',
        name: 'Professional Resume',
        description: 'Clean and professional resume template suitable for all industries',
        updatedAt: '2023-05-15T14:30:00Z',
        status: 'published',
        isPremium: false,
        price: 0,
        downloads: 128,
        category: 'professional',
        previewImage: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
      },
      {
        id: '2',
        name: 'Creative CV',
        description: 'Modern and creative CV template for designers and creatives',
        updatedAt: '2023-04-22T09:15:00Z',
        status: 'published',
        isPremium: true,
        price: 9.99,
        downloads: 87,
        category: 'creative',
        previewImage: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
      },
      {
        id: '3',
        name: 'Executive CV',
        description: 'Elegant and professional CV template for executives and managers',
        status: 'draft',
        isPremium: true,
        downloads: 0,
        price: 12.99,
        category: 'executive',
        updatedAt: '2023-06-25T09:45:00Z',
        previewImage: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
      },
      {
        id: '4',
        name: 'Minimalist Resume',
        description: 'Simple and clean resume template with a focus on content',
        status: 'published',
        isPremium: false,
        downloads: 756,
        price: 0,
        category: 'minimal',
        updatedAt: '2023-06-10T16:20:00Z',
        previewImage: 'https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'
      }
    ];
    
    // Computed
    const filteredTemplates = computed(() => {
      // Always return an array, even if templates.value is not yet initialized
      const templatesList = Array.isArray(templates.value) ? templates.value : [];
      
      return templatesList.filter(template => {
        if (!template) return false;
        
        // Filter by search query
        const searchTerm = searchQuery.value ? searchQuery.value.toLowerCase() : '';
        const nameMatches = template.name && typeof template.name === 'string' && 
                          template.name.toLowerCase().includes(searchTerm);
        const descMatches = template.description && typeof template.description === 'string' && 
                           template.description.toLowerCase().includes(searchTerm);
        
        const matchesSearch = searchTerm === '' || nameMatches || descMatches;
        
        // Filter by status
        const matchesStatus = statusFilter.value === 'all' || 
                            (template.status && template.status === statusFilter.value);
        
        return matchesSearch && matchesStatus;
      });
    });
    
    // Methods
    const formatDate = (dateString) => {
      return format(new Date(dateString), 'MMM d, yyyy');
    };
    
    const previewTemplate = (id) => {
      // Implement preview functionality
      console.log('Preview template:', id);
      // In a real app, this would open a preview modal or navigate to a preview page
      // router.push(`/templates/${id}/preview`);
    };
    
    const editTemplate = (id) => {
      router.push(`/templates/${id}/edit`);
    };
    
    const duplicateTemplate = async (id) => {
      try {
        // In a real app, this would call an API to duplicate the template
        const templateToDuplicate = templates.value.find(t => t.id === id);
        if (!templateToDuplicate) return;
        
        const newTemplate = {
          ...templateToDuplicate,
          id: Math.max(...templates.value.map(t => t.id), 0) + 1,
          name: `${templateToDuplicate.name} (Copy)`,
          status: 'draft',
          downloads: 0,
          updatedAt: new Date().toISOString()
        };
        
        templates.value = [newTemplate, ...templates.value];
        
        // Show success message
        // You would typically use a toast notification here
        console.log('Template duplicated successfully');
      } catch (error) {
        console.error('Error duplicating template:', error);
        // Show error message
      }
    };
    
    const confirmDelete = (id) => {
      templateToDelete.value = templates.value.find(t => t.id === id);
      showDeleteModal.value = true;
    };
    
    const deleteTemplate = async () => {
      if (!templateToDelete.value) return;
      
      try {
        // In a real app, this would call an API to delete the template
        templates.value = templates.value.filter(t => t.id !== templateToDelete.value.id);
        
        // Show success message
        // You would typically use a toast notification here
        console.log('Template deleted successfully');
      } catch (error) {
        console.error('Error deleting template:', error);
        // Show error message
      } finally {
        showDeleteModal.value = false;
        templateToDelete.value = null;
      }
    };
    
    // Initial fetch
    onMounted(() => {
      // In a real app, this would be an API call
      // For now, we'll use the mock data after a small delay to simulate loading
      isLoading.value = true;
      
      setTimeout(() => {
        templates.value = [...mockTemplates];
        isLoading.value = false;
      }, 500);
    });

    return {
      // State
      isLoading,
      viewMode,
      searchQuery,
      statusFilter,
      filteredTemplates, // Expose the computed property directly
      showDeleteModal,
      templateToDelete,
      
      // Methods
      formatDate,
      previewTemplate,
      editTemplate,
      duplicateTemplate,
      confirmDelete,
      deleteTemplate,
      t // Expose t function to template
    };
  }
};
</script>
