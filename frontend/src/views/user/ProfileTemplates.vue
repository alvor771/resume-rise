<template>
  <div class="space-y-6">
    <!-- Available Templates -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.available_templates') }}
        </h3>
        <div class="relative">
          <select
            v-model="selectedCategory"
            class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md pl-3 pr-8 py-2 text-base text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          >
            <option value="all">{{ $t('common.all_categories') }}</option>
            <option value="modern">{{ $t('templates.categories.modern') }}</option>
            <option value="professional">{{ $t('templates.categories.professional') }}</option>
            <option value="creative">{{ $t('templates.categories.creative') }}</option>
            <option value="minimal">{{ $t('templates.categories.minimal') }}</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
            <ChevronDownIcon class="h-4 w-4" />
          </div>
        </div>
      </div>
      
      <!-- Template Grid -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="template in filteredTemplates" 
          :key="template.id"
          class="group relative bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200"
          :class="{ 'ring-2 ring-indigo-500': template.is_premium }"
        >
          <!-- Premium Badge -->
          <div v-if="template.is_premium" class="absolute top-2 right-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
              <StarIcon class="h-3 w-3 mr-1" />
              {{ $t('common.premium') }}
            </span>
          </div>
          
          <!-- Template Preview -->
          <div class="aspect-[1/1.4142] bg-gray-100 dark:bg-gray-600 overflow-hidden">
            <img 
              :src="template.thumbnail" 
              :alt="template.name"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
          </div>
          
          <!-- Template Info -->
          <div class="p-4">
            <div class="flex justify-between items-start">
              <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ template.name }}
                </h4>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                  {{ $t('templates.categories.' + template.category) }}
                </p>
              </div>
              <div class="flex space-x-2">
                <button
                  @click="previewTemplate(template.id)"
                  class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                  :title="$t('common.preview')"
                >
                  <EyeIcon class="h-4 w-4" />
                </button>
                <button
                  @click="useTemplate(template.id)"
                  class="p-1.5 text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 rounded-full hover:bg-indigo-50 dark:hover:bg-gray-600"
                  :title="$t('common.use_template')"
                  :disabled="template.is_premium && !hasPremiumAccess"
                  :class="{ 'opacity-50 cursor-not-allowed': template.is_premium && !hasPremiumAccess }"
                >
                  <CursorArrowRaysIcon class="h-4 w-4" />
                </button>
              </div>
            </div>
            
            <div v-if="template.is_premium && !hasPremiumAccess" class="mt-2">
              <button
                @click="upgradeToPremium"
                class="w-full inline-flex justify-center items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-100 dark:hover:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
              >
                <StarIcon class="-ml-0.5 mr-1.5 h-3.5 w-3.5" />
                {{ $t('user.profile.upgrade_to_premium') }}
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-if="filteredTemplates.length === 0" class="text-center py-12">
        <TemplateIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.no_templates_found') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('user.profile.try_different_filter') }}
        </p>
      </div>
    </div>
    
    <!-- My Templates -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.my_templates') }}
        </h3>
        <button
          @click="createCustomTemplate"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('user.profile.create_custom_template') }}
        </button>
      </div>
      
      <!-- My Templates Grid -->
      <div v-if="userTemplates.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="template in userTemplates" 
          :key="'user-' + template.id"
          class="group relative bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200"
        >
          <!-- Template Preview -->
          <div class="aspect-[1/1.4142] bg-gray-100 dark:bg-gray-600 overflow-hidden">
            <img 
              :src="template.thumbnail" 
              :alt="template.name"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
          </div>
          
          <!-- Template Info -->
          <div class="p-4">
            <div class="flex justify-between items-start">
              <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ template.name }}
                </h4>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                  {{ $t('user.profile.custom_template') }}
                </p>
              </div>
              <div class="flex space-x-2">
                <button
                  @click="editTemplate(template.id)"
                  class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                  :title="$t('common.edit')"
                >
                  <PencilIcon class="h-4 w-4" />
                </button>
                <button
                  @click="deleteTemplate(template.id)"
                  class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                  :title="$t('common.delete')"
                >
                  <TrashIcon class="h-4 w-4" />
                </button>
              </div>
            </div>
            
            <div class="mt-3 flex justify-between items-center">
              <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ $t('common.updated') }}: {{ formatDate(template.updated_at) }}
              </span>
              <button
                @click="useTemplate(template.id, true)"
                class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                {{ $t('common.use') }}
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <TemplateIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.no_custom_templates') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('user.profile.create_custom_template_description') }}
        </p>
        <div class="mt-6">
          <button
            @click="createCustomTemplate"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
            {{ $t('user.profile.create_custom_template') }}
          </button>
        </div>
      </div>
    </div>
    
    <!-- Template Preview Modal -->
    <TransitionRoot as="template" :show="isPreviewOpen">
      <HeadlessDialog as="div" class="fixed inset-0 z-50 overflow-y-auto" @close="isPreviewOpen = false">
        <div class="min-h-screen px-4 text-center">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <HeadlessDialogOverlay class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>

          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
              <div class="flex justify-between items-center mb-4">
                <HeadlessDialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                  {{ selectedTemplate ? selectedTemplate.name : '' }}
                </HeadlessDialogTitle>
                <button
                  type="button"
                  class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                  @click="isPreviewOpen = false"
                >
                  <span class="sr-only">{{ $t('common.close') }}</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>
              
              <div class="mt-2">
                <div class="aspect-[1/1.4142] bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                  <img 
                    v-if="selectedTemplate"
                    :src="selectedTemplate.preview" 
                    :alt="selectedTemplate.name"
                    class="w-full h-full object-contain"
                  />
                </div>
              </div>
              
              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click="isPreviewOpen = false"
                >
                  {{ $t('common.close') }}
                </button>
                <button
                  type="button"
                  class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click="useTemplate(selectedTemplate?.id)"
                  :disabled="selectedTemplate?.is_premium && !hasPremiumAccess"
                  :class="{ 'opacity-50 cursor-not-allowed': selectedTemplate?.is_premium && !hasPremiumAccess }"
                >
                  {{ $t('common.use_this_template') }}
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
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';
import { 
  Dialog as HeadlessDialog, 
  DialogOverlay as HeadlessDialogOverlay, 
  DialogTitle as HeadlessDialogTitle, 
  TransitionRoot, 
  TransitionChild 
} from '@headlessui/vue';
import { 
  TemplateIcon,
  StarIcon,
  EyeIcon,
  CursorArrowRaysIcon,
  PencilIcon,
  TrashIcon,
  PlusIcon,
  ChevronDownIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline';

export default {
  name: 'ProfileTemplates',
  components: {
    HeadlessDialog,
    HeadlessDialogOverlay,
    HeadlessDialogTitle,
    TransitionRoot,
    TransitionChild,
    TemplateIcon,
    StarIcon,
    EyeIcon,
    CursorArrowRaysIcon,
    PencilIcon,
    TrashIcon,
    PlusIcon,
    ChevronDownIcon,
    XMarkIcon
  },
  setup() {
    const { t } = useI18n();
    const toast = useToast();
    const router = useRouter();
    
    const selectedCategory = ref('all');
    const isPreviewOpen = ref(false);
    const selectedTemplate = ref(null);
    const hasPremiumAccess = ref(false); // In a real app, this would come from user's subscription
    
    // Mock data - replace with actual API calls
    const templates = ref([
      {
        id: 'modern-1',
        name: 'Modern Clean',
        category: 'modern',
        thumbnail: 'https://via.placeholder.com/300x424/f3f4f6/9ca3af?text=Modern+Clean',
        preview: 'https://via.placeholder.com/800x1131/f3f4f6/9ca3af?text=Modern+Clean+Preview',
        is_premium: false
      },
      {
        id: 'professional-1',
        name: 'Executive',
        category: 'professional',
        thumbnail: 'https://via.placeholder.com/300x424/e5e7eb/6b7280?text=Executive',
        preview: 'https://via.placeholder.com/800x1131/e5e7eb/6b7280?text=Executive+Preview',
        is_premium: false
      },
      {
        id: 'creative-1',
        name: 'Creative Portfolio',
        category: 'creative',
        thumbnail: 'https://via.placeholder.com/300x424/fef3c7/92400e?text=Creative+Portfolio',
        preview: 'https://via.placeholder.com/800x1131/fef3c7/92400e?text=Creative+Portfolio+Preview',
        is_premium: true
      },
      {
        id: 'minimal-1',
        name: 'Minimalist',
        category: 'minimal',
        thumbnail: 'https://via.placeholder.com/300x424/f9fafb/6b7280?text=Minimalist',
        preview: 'https://via.placeholder.com/800x1131/f9fafb/6b7280?text=Minimalist+Preview',
        is_premium: false
      },
      {
        id: 'modern-2',
        name: 'Modern Tech',
        category: 'modern',
        thumbnail: 'https://via.placeholder.com/300x424/e0f2fe/0369a1?text=Modern+Tech',
        preview: 'https://via.placeholder.com/800x1131/e0f2fe/0369a1?text=Modern+Tech+Preview',
        is_premium: true
      },
      {
        id: 'professional-2',
        name: 'Corporate',
        category: 'professional',
        thumbnail: 'https://via.placeholder.com/300x424/f3f4f6/6b7280?text=Corporate',
        preview: 'https://via.placeholder.com/800x1131/f3f4f6/6b7280?text=Corporate+Preview',
        is_premium: false
      }
    ]);
    
    const userTemplates = ref([
      {
        id: 'custom-1',
        name: 'My Custom Template',
        thumbnail: 'https://via.placeholder.com/300x424/dbeafe/1e40af?text=Custom+Template',
        updated_at: '2023-06-10T14:30:00Z'
      }
    ]);
    
    const filteredTemplates = computed(() => {
      if (selectedCategory.value === 'all') {
        return templates.value;
      }
      return templates.value.filter(template => template.category === selectedCategory.value);
    });
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };
    
    const previewTemplate = (templateId) => {
      const template = [...templates.value, ...userTemplates.value].find(t => t.id === templateId);
      if (template) {
        selectedTemplate.value = template;
        isPreviewOpen.value = true;
      }
    };
    
    const useTemplate = (templateId, isCustom = false) => {
      const template = isCustom 
        ? userTemplates.value.find(t => t.id === templateId)
        : templates.value.find(t => t.id === templateId);
      
      if (!template) return;
      
      if (template.is_premium && !hasPremiumAccess.value) {
        toast.warning(t('user.profile.premium_template_alert'));
        return;
      }
      
      // In a real app, this would navigate to the resume builder with the selected template
      router.push({ 
        name: 'ResumeBuilder', 
        query: { template: templateId }
      });
    };
    
    const createCustomTemplate = () => {
      // In a real app, this would navigate to the template editor
      router.push({ name: 'TemplateEditor' });
    };
    
    const editTemplate = (templateId) => {
      // In a real app, this would navigate to the template editor with the selected template
      router.push({ 
        name: 'TemplateEditor', 
        params: { id: templateId }
      });
    };
    
    const deleteTemplate = (templateId) => {
      if (confirm(t('user.profile.confirm_delete_template'))) {
        userTemplates.value = userTemplates.value.filter(t => t.id !== templateId);
        toast.success(t('user.profile.template_deleted'));
      }
    };
    
    const upgradeToPremium = () => {
      // In a real app, this would navigate to the subscription page
      router.push({ name: 'Pricing' });
    };
    
    return {
      selectedCategory,
      filteredTemplates,
      userTemplates,
      isPreviewOpen,
      selectedTemplate,
      hasPremiumAccess,
      formatDate,
      previewTemplate,
      useTemplate,
      createCustomTemplate,
      editTemplate,
      deleteTemplate,
      upgradeToPremium
    };
  }
};
</script>
