<template>
  <div class="space-y-6">
    <!-- Resume List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.my_resumes') }}
        </h3>
        <router-link
          :to="{ name: 'ResumeBuilder' }"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('user.profile.create_resume') }}
        </router-link>
      </div>
      
      <!-- Resume Grid -->
      <div v-if="resumes.length > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div 
          v-for="resume in resumes" 
          :key="resume.id"
          class="relative rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden hover:shadow-md transition-shadow duration-200"
        >
          <div class="p-4">
            <div class="flex items-start justify-between">
              <div>
                <h4 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                  {{ resume.title }}
                </h4>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                  {{ $t('common.updated') }}: {{ formatDate(resume.updated_at) }}
                </p>
              </div>
              <div class="flex space-x-1">
                <button
                  @click="editResume(resume.id)"
                  class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                  :title="$t('common.edit')"
                >
                  <PencilIcon class="h-4 w-4" />
                </button>
                <button
                  @click="duplicateResume(resume.id)"
                  class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                  :title="$t('common.duplicate')"
                >
                  <DocumentDuplicateIcon class="h-4 w-4" />
                </button>
                <button
                  @click="deleteResume(resume.id)"
                  class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                  :title="$t('common.delete')"
                >
                  <TrashIcon class="h-4 w-4" />
                </button>
              </div>
            </div>
            
            <div class="mt-4 flex items-center justify-between">
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-800': resume.is_public,
                  'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': !resume.is_public
                }"
              >
                {{ resume.is_public ? $t('common.public') : $t('common.private') }}
              </span>
              <div class="flex space-x-2">
                <button
                  @click="downloadResume(resume.id, 'pdf')"
                  class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  PDF
                </button>
                <button
                  @click="downloadResume(resume.id, 'docx')"
                  class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  DOCX
                </button>
              </div>
            </div>
          </div>
          
          <!-- Resume Preview -->
          <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-4">
            <div class="aspect-[1/1.4142] bg-white shadow-sm rounded overflow-hidden">
              <!-- Placeholder for resume preview -->
              <div class="h-full flex items-center justify-center text-gray-400">
                <DocumentTextIcon class="h-12 w-12" />
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <DocumentTextIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.no_resumes') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('user.profile.get_started_by_creating_resume') }}
        </p>
        <div class="mt-6">
          <router-link
            :to="{ name: 'ResumeBuilder' }"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
            {{ $t('user.profile.create_resume') }}
          </router-link>
        </div>
      </div>
    </div>
    
    <!-- Resume Settings -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
        {{ $t('user.profile.resume_settings') }}
      </h3>
      
      <div class="space-y-6">
        <div>
          <label for="default_template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $t('user.profile.default_template') }}
          </label>
          <select
            id="default_template"
            v-model="settings.default_template"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="modern">{{ $t('templates.modern') }}</option>
            <option value="professional">{{ $t('templates.professional') }}</option>
            <option value="creative">{{ $t('templates.creative') }}</option>
            <option value="minimal">{{ $t('templates.minimal') }}</option>
          </select>
        </div>
        
        <div>
          <label for="default_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $t('user.profile.default_format') }}
          </label>
          <select
            id="default_format"
            v-model="settings.default_format"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="a4">A4 ({{ $t('common.portrait') }})</option>
            <option value="a4-landscape">A4 ({{ $t('common.landscape') }})</option>
            <option value="letter">US Letter ({{ $t('common.portrait') }})</option>
            <option value="letter-landscape">US Letter ({{ $t('common.landscape') }})</option>
          </select>
        </div>
        
        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input
              id="auto_save"
              v-model="settings.auto_save"
              type="checkbox"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
            />
          </div>
          <div class="ml-3 text-sm">
            <label for="auto_save" class="font-medium text-gray-700 dark:text-gray-300">
              {{ $t('user.profile.auto_save') }}
            </label>
            <p class="text-gray-500 dark:text-gray-400">
              {{ $t('user.profile.auto_save_description') }}
            </p>
          </div>
        </div>
        
        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
          <button
            @click="saveSettings"
            :disabled="isSaving"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="isSaving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <CheckIcon v-else class="-ml-1 mr-2 h-4 w-4" />
            {{ isSaving ? $t('common.saving') : $t('common.save_changes') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';
import { 
  DocumentTextIcon, 
  PencilIcon, 
  TrashIcon, 
  PlusIcon, 
  CheckIcon,
  DocumentDuplicateIcon
} from '@heroicons/vue/24/outline';

export default {
  name: 'ProfileResumes',
  components: {
    DocumentTextIcon,
    PencilIcon,
    TrashIcon,
    PlusIcon,
    CheckIcon,
    DocumentDuplicateIcon
  },
  setup() {
    const { t } = useI18n();
    const toast = useToast();
    const router = useRouter();
    const isSaving = ref(false);
    
    // Mock data - replace with actual API calls
    const resumes = ref([
      {
        id: 1,
        title: 'Software Engineer Resume',
        updated_at: '2023-06-15T10:30:00Z',
        is_public: true,
        template: 'modern'
      },
      {
        id: 2,
        title: 'Frontend Developer',
        updated_at: '2023-05-20T14:45:00Z',
        is_public: false,
        template: 'professional'
      },
      {
        id: 3,
        title: 'UX/UI Designer',
        updated_at: '2023-04-10T09:15:00Z',
        is_public: true,
        template: 'creative'
      }
    ]);
    
    const settings = ref({
      default_template: 'modern',
      default_format: 'a4',
      auto_save: true
    });
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };
    
    const editResume = (id) => {
      router.push({ name: 'ResumeBuilder', params: { id } });
    };
    
    const duplicateResume = (id) => {
      const resume = resumes.value.find(r => r.id === id);
      if (resume) {
        const newResume = {
          ...resume,
          id: Date.now(),
          title: `${resume.title} (${t('common.copy')})`,
          updated_at: new Date().toISOString()
        };
        resumes.value.unshift(newResume);
        toast.success(t('user.profile.resume_duplicated'));
      }
    };
    
    const deleteResume = (id) => {
      if (confirm(t('user.profile.confirm_delete_resume'))) {
        resumes.value = resumes.value.filter(r => r.id !== id);
        toast.success(t('user.profile.resume_deleted'));
      }
    };
    
    const downloadResume = (id, format) => {
      const resume = resumes.value.find(r => r.id === id);
      if (resume) {
        toast.info(t('user.profile.downloading_resume', { 
          title: resume.title,
          format: format.toUpperCase() 
        }));
        // Actual download would go here
      }
    };
    
    const saveSettings = async () => {
      try {
        isSaving.value = true;
        // In a real app, save settings to API
        await new Promise(resolve => setTimeout(resolve, 1000));
        toast.success(t('user.profile.settings_saved'));
      } catch (error) {
        console.error('Error saving settings:', error);
        toast.error(t('common.error_occurred'));
      } finally {
        isSaving.value = false;
      }
    };
    
    // Load resumes and settings
    onMounted(() => {
      // In a real app, fetch resumes and settings from API
      console.log('ProfileResumes component mounted');
    });
    
    return {
      resumes,
      settings,
      isSaving,
      formatDate,
      editResume,
      duplicateResume,
      deleteResume,
      downloadResume,
      saveSettings
    };
  }
};
</script>
