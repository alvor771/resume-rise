<template>
  <div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
        {{ $t('user.profile.cv_info') }}
      </h3>
      
      <!-- CV Upload Section -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <h4 class="text-md font-medium text-gray-700 dark:text-gray-200">
            {{ $t('user.profile.upload_cv') }}
          </h4>
          <button
            @click="uploadCV"
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <PlusIcon class="-ml-0.5 mr-1.5 h-4 w-4" />
            {{ $t('common.upload') }}
          </button>
        </div>
        
        <!-- CV List -->
        <div v-if="cvs.length > 0" class="space-y-4">
          <div v-for="(cv, index) in cvs" :key="index" class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
            <div class="flex items-center">
              <DocumentTextIcon class="h-5 w-5 text-gray-400 mr-3" />
              <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ cv.name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ $t('common.uploaded') }}: {{ formatDate(cv.uploaded_at) }}
                </p>
              </div>
            </div>
            <div class="flex space-x-2">
              <button
                @click="downloadCV(cv.id)"
                class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                :title="$t('common.download')"
              >
                <ArrowDownTrayIcon class="h-5 w-5" />
              </button>
              <button
                @click="deleteCV(cv.id)"
                class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400"
                :title="$t('common.delete')"
              >
                <TrashIcon class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-8 border-2 border-dashed rounded-lg dark:border-gray-700">
          <DocumentTextIcon class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ $t('user.profile.no_cv_uploaded') }}
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $t('user.profile.upload_cv_description') }}
          </p>
          <div class="mt-6">
            <button
              @click="uploadCV"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
              {{ $t('user.profile.upload_cv') }}
            </button>
          </div>
        </div>
      </div>
      
      <!-- CV Preferences -->
      <div>
        <h4 class="text-md font-medium text-gray-700 dark:text-gray-200 mb-4">
          {{ $t('user.profile.cv_preferences') }}
        </h4>
        
        <div class="space-y-4">
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="show_contact_info"
                v-model="preferences.show_contact_info"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="show_contact_info" class="font-medium text-gray-700 dark:text-gray-300">
                {{ $t('user.profile.show_contact_info') }}
              </label>
              <p class="text-gray-500 dark:text-gray-400">
                {{ $t('user.profile.show_contact_info_description') }}
              </p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="show_education"
                v-model="preferences.show_education"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="show_education" class="font-medium text-gray-700 dark:text-gray-300">
                {{ $t('user.profile.show_education') }}
              </label>
              <p class="text-gray-500 dark:text-gray-400">
                {{ $t('user.profile.show_education_description') }}
              </p>
            </div>
          </div>
          
          <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <button
              @click="savePreferences"
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
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useToast } from 'vue-toastification';
import { 
  DocumentTextIcon, 
  ArrowDownTrayIcon, 
  TrashIcon, 
  PlusIcon, 
  CheckIcon 
} from '@heroicons/vue/24/outline';

export default {
  name: 'ProfileCV',
  components: {
    DocumentTextIcon,
    ArrowDownTrayIcon,
    TrashIcon,
    PlusIcon,
    CheckIcon
  },
  setup() {
    const { t } = useI18n();
    const toast = useToast();
    const isSaving = ref(false);
    
    // Mock data - replace with actual API calls
    const cvs = ref([
      { id: 1, name: 'My_Resume_2023.pdf', uploaded_at: '2023-06-15T10:30:00Z' },
      { id: 2, name: 'Frontend_Developer_CV.pdf', uploaded_at: '2023-05-20T14:45:00Z' }
    ]);
    
    const preferences = ref({
      show_contact_info: true,
      show_education: true
    });
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };
    
    const uploadCV = () => {
      // In a real app, implement file upload logic
      const input = document.createElement('input');
      input.type = 'file';
      input.accept = '.pdf,.doc,.docx';
      
      input.onchange = (e) => {
        const file = e.target.files[0];
        if (file) {
          // Simulate API call
          setTimeout(() => {
            cvs.value.unshift({
              id: Date.now(),
              name: file.name,
              uploaded_at: new Date().toISOString()
            });
            toast.success(t('user.profile.cv_uploaded'));
          }, 1000);
        }
      };
      
      input.click();
    };
    
    const downloadCV = (id) => {
      // In a real app, implement download logic
      const cv = cvs.value.find(cv => cv.id === id);
      if (cv) {
        toast.info(t('user.profile.downloading_cv', { name: cv.name }));
        // Actual download would go here
      }
    };
    
    const deleteCV = (id) => {
      // In a real app, implement delete logic with confirmation
      if (confirm(t('user.profile.confirm_delete_cv'))) {
        cvs.value = cvs.value.filter(cv => cv.id !== id);
        toast.success(t('user.profile.cv_deleted'));
      }
    };
    
    const savePreferences = async () => {
      try {
        isSaving.value = true;
        // In a real app, save preferences to API
        await new Promise(resolve => setTimeout(resolve, 1000));
        toast.success(t('user.profile.preferences_saved'));
      } catch (error) {
        console.error('Error saving preferences:', error);
        toast.error(t('common.error_occurred'));
      } finally {
        isSaving.value = false;
      }
    };
    
    // Load CV data and preferences
    onMounted(() => {
      // In a real app, fetch CVs and preferences from API
      console.log('ProfileCV component mounted');
    });
    
    return {
      cvs,
      preferences,
      isSaving,
      formatDate,
      uploadCV,
      downloadCV,
      deleteCV,
      savePreferences
    };
  }
};
</script>
