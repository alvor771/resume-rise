<template>
  <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4 sm:px-0">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ isEditMode ? 'Edit Template' : 'Create New Template' }}
          </h2>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ isEditMode ? 'Update template details' : 'Add a new resume template to the system' }}
          </p>
        </div>
        <div>
          <router-link
            to="/admin/templates"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Back to Templates
          </router-link>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form @submit.prevent="handleSubmit">
          <div class="px-4 py-5 sm:p-6 space-y-6">
            <!-- Template Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Template Name <span class="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="name"
                v-model="formData.name"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                :class="{ 'border-red-300': errors.name }"
              />
              <p v-if="errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ errors.name }}
              </p>
            </div>

            <!-- Template Key -->
            <div>
              <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Template Key <span class="text-red-500">*</span>
                <span class="text-xs text-gray-500 ml-1">(lowercase, no spaces, use underscores)</span>
              </label>
              <input
                type="text"
                id="key"
                v-model="formData.key"
                :disabled="isEditMode"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                :class="{ 'border-red-300': errors.key, 'bg-gray-100 dark:bg-gray-600': isEditMode }"
              />
              <p v-if="errors.key" class="mt-2 text-sm text-red-600 dark:text-red-400">
                {{ errors.key }}
              </p>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Description
              </label>
              <div class="mt-1">
                <textarea
                  id="description"
                  v-model="formData.description"
                  rows="3"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                ></textarea>
              </div>
              <p class="mt-2 text-sm text-gray-500">
                A brief description of this template
              </p>
            </div>

            <!-- Template Category -->
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              <div class="sm:col-span-3">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Category
                </label>
                <select
                  id="category"
                  v-model="formData.category"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white"
                >
                  <option value="professional">Professional</option>
                  <option value="creative">Creative</option>
                  <option value="modern">Modern</option>
                  <option value="minimalist">Minimalist</option>
                  <option value="executive">Executive</option>
                </select>
              </div>

              <!-- Version -->
              <div class="sm:col-span-3">
                <label for="version" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Version
                </label>
                <input
                  type="text"
                  id="version"
                  v-model="formData.version"
                  class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                />
              </div>
            </div>

            <!-- Thumbnail -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Thumbnail
              </label>
              <div class="mt-1 flex items-center">
                <span class="h-24 w-24 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-700">
                  <img
                    v-if="formData.thumbnail"
                    :src="formData.thumbnail"
                    :alt="formData.name"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="h-full w-full flex items-center justify-center bg-gray-200 dark:bg-gray-600">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </span>
                <button
                  type="button"
                  class="ml-5 bg-white dark:bg-gray-700 py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Change
                </button>
              </div>
            </div>

            <!-- Template Status -->
            <div class="pt-2">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="is_active"
                    v-model="formData.isActive"
                    type="checkbox"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700"
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">
                    Active
                  </label>
                  <p class="text-gray-500 dark:text-gray-400">
                    Inactive templates won't be available for new resumes
                  </p>
                </div>
              </div>
            </div>

            <!-- Template Content -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
              <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Template Content</h3>
                <button
                  type="button"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click="showTemplateEditor = true"
                >
                  <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit Template
                </button>
              </div>
              <div class="mt-2 bg-gray-50 dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                <pre class="text-xs text-gray-700 dark:text-gray-300 overflow-auto max-h-60">{{ formData.content || 'No template content available' }}</pre>
              </div>
            </div>
          </div>

          <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 rounded-b-lg">
            <button
              type="button"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
              @click="resetForm"
            >
              Reset
            </button>
            <button
              type="submit"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :disabled="isSubmitting"
            >
              <svg
                v-if="isSubmitting"
                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              {{ isEditMode ? 'Update Template' : 'Create Template' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Template Editor Modal -->
    <div v-if="showTemplateEditor" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showTemplateEditor = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
          <div>
            <div class="flex justify-between items-center">
              <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                Template Editor
              </h3>
              <button
                type="button"
                class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200"
                @click="showTemplateEditor = false"
              >
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="mt-4">
              <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                <textarea
                  v-model="formData.content"
                  class="w-full h-96 font-mono text-sm text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Enter your template HTML/CSS/JS here..."
                ></textarea>
              </div>
              <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <p>Use the editor above to modify the template. You can use HTML, CSS, and JavaScript.</p>
                <p class="mt-1">Available template variables: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">user</code>, <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">resume</code>, <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">sections</code></p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6">
            <button
              type="button"
              class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
              @click="showTemplateEditor = false"
            >
              Save and Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTemplatesStore } from '@/stores/templates';
import { useI18n } from 'vue-i18n';

export default {
  name: 'TemplateForm',
  
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { t } = useI18n();
    const store = useTemplatesStore();
    
    const isEditMode = computed(() => route.name === 'AdminEditTemplate');
    const templateId = computed(() => route.params.id);
    
    // Form data with default values
    const formData = ref({
      name: '',
      key: '',
      description: '',
      category: 'professional',
      version: '1.0.0',
      thumbnail: '',
      content: '<div class="resume">\n  <h1>{{ resume.title }}</h1>\n  <div class="personal-info">\n    <p>{{ user.firstName }} {{ user.lastName }}</p>\n    <p>{{ user.email }}</p>\n    <p>{{ user.phone }}</p>\n  </div>\n  \n  <div class="sections">\n    <div v-for="section in sections" :key="section.id" class="section">\n      <h2>{{ section.title }}</h2>\n      <div v-html="section.content"></div>\n    </div>\n  </div>\n</div>',
      isActive: true
    });
    
    const errors = ref({});
    const isSubmitting = ref(false);
    const showTemplateEditor = ref(false);
    
    // Load template data if in edit mode
    onMounted(async () => {
      if (isEditMode.value && templateId.value) {
        try {
          const data = await store.fetchOne(templateId.value);
          formData.value = {
            name: data.name || '',
            key: data.key || '',
            description: data.description || '',
            category: data.category || 'professional',
            version: data.version || '1.0.0',
            thumbnail: data.thumbnail || '',
            content: data.content || formData.value.content,
            isActive: !!data.is_active,
          };
        } catch (error) {
          console.error('Error loading template:', error);
          alert(t ? t('admin.templates.load_error') : 'Failed to load template data. Please try again.');
          router.push({ name: 'AdminTemplates' });
        }
      }
    });
    
    const validateForm = () => {
      const newErrors = {};
      
      if (!formData.value.name.trim()) {
        newErrors.name = 'Template name is required';
      }
      
      if (!formData.value.key) {
        newErrors.key = 'Template key is required';
      } else if (!/^[a-z0-9_]+$/.test(formData.value.key)) {
        newErrors.key = 'Template key can only contain lowercase letters, numbers, and underscores';
      }
      
      errors.value = newErrors;
      return Object.keys(newErrors).length === 0;
    };
    
    const handleSubmit = async () => {
      if (!validateForm()) {
        return;
      }
      
      isSubmitting.value = true;
      
      try {
        const payload = {
          name: formData.value.name,
          key: formData.value.key,
          description: formData.value.description,
          category: formData.value.category,
          version: formData.value.version,
          thumbnail: formData.value.thumbnail,
          content: formData.value.content,
          is_active: !!formData.value.isActive,
        };
        if (isEditMode.value) {
          await store.update(templateId.value, payload);
        } else {
          await store.create(payload);
        }
        alert(t ? t('admin.templates.saved_successfully') : `Template ${isEditMode.value ? 'updated' : 'created'} successfully!`);
        router.push({ name: 'AdminTemplates' });
      } catch (error) {
        console.error('Error saving template:', error);
        alert(t ? t('admin.templates.save_error') : `Failed to ${isEditMode.value ? 'update' : 'create'} template. Please try again.`);
      } finally {
        isSubmitting.value = false;
      }
    };
    
    const resetForm = () => {
      if (isEditMode.value) {
        // Reload the form data
        router.go(0);
      } else {
        // Reset to default values
        formData.value = {
          name: '',
          key: '',
          description: '',
          category: 'professional',
          version: '1.0.0',
          thumbnail: '',
          content: formData.value.content, // Keep the current content
          isActive: true
        };
        errors.value = {};
      }
    };
    
    return {
      formData,
      errors,
      isSubmitting,
      isEditMode,
      showTemplateEditor,
      handleSubmit,
      resetForm
    };
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
