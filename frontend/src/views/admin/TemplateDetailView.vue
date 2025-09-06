<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="flex items-center justify-between mb-6">
      <router-link 
        to="/admin/templates" 
        class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ $t('common.back') }}
      </router-link>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ $t('admin.templates.templateDetails') }}: {{ template.name }}
      </h1>
      <div class="w-8"></div> <!-- Spacer for flex alignment -->
    </div>

    <!-- Template Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div>
              <div class="text-xs text-gray-500 dark:text-gray-400">Key</div>
              <div class="flex items-center gap-2">
                <code class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs">{{ template.key }}</code>
                <button class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline" @click="copyKey">{{$t('common.copy') || 'Copy'}}</button>
              </div>
            </div>
          </div>
          <div class="flex space-x-3">
            <span 
              class="px-3 py-1 rounded-full text-xs font-medium"
              :class="[
                template.is_premium ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'inline-flex items-center'
              ]"
            >
              {{ template.is_premium ? $t('admin.templates.premium') : $t('admin.templates.free') }}
            </span>
            <span 
              class="px-3 py-1 rounded-full text-xs font-medium"
              :class="[
                template.is_public ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                'inline-flex items-center'
              ]"
            >
              {{ template.is_public ? $t('admin.templates.public') : $t('admin.templates.private') }}
            </span>
            <span 
              class="px-3 py-1 rounded-full text-xs font-medium"
              :class="[
                template.is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'inline-flex items-center'
              ]"
            >
              {{ template.is_active ? ($t('common.active') || 'Active') : ($t('common.inactive') || 'Inactive') }}
            </span>
          </div>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('admin.templates.created_by') }} {{ template.author?.name || 'System' }} • 
          {{ $t('admin.templates.created_at') }} {{ formatDate(template.created_at) }}
        </p>
        <div class="mt-3 flex items-center gap-3">
          <button @click="goEdit" type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            {{ $t('common.edit') }}
          </button>
          <button @click="confirmDelete" type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
            {{ $t('common.delete') }}
          </button>
        </div>
      </div>

      <div class="px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Template Preview -->
          <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700">
            <!-- Preview Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $t('admin.templates.preview') }}
              </h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $t('admin.templates.preview_description') }}
              </p>
            </div>
            
            <!-- CV Preview -->
            <div class="relative overflow-hidden bg-white p-4">
              <div class="relative mx-auto" style="max-width: 800px;">
                <img 
                  :src="template.thumbnail || '/images/cv-preview.svg'" 
                  :alt="template.name + ' ' + $t('admin.templates.preview')" 
                  class="w-full h-auto border border-gray-200 rounded shadow-sm"
                />
                
                <!-- Overlay with template name -->
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded">
                  <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-xl">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ template.name }}</h4>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                      {{ $t('admin.templates.version') }} {{ template.version }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Preview Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <div class="flex items-center">
                    <template v-for="i in 5" :key="i">
                      <svg 
                        class="h-5 w-5" 
                        :class="i <= template.average_rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-500'"
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </template>
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                      {{ template.average_rating?.toFixed(1) || $t('admin.templates.no_ratings') }}
                      <span class="text-gray-400 dark:text-gray-500">
                        ({{ template.ratings_count || 0 }} {{ $t('admin.templates.ratings') }})
                      </span>
                    </span>
                  </div>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                  <span class="font-medium">{{ template.usage_count || 0 }}</span> {{ $t('admin.templates.uses') }}
                </div>
              </div>
            </div>
          </div>

          <!-- Template Details -->
          <div>
            <!-- Category -->
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $t('admin.templates.category') }}
              </h3>
              <div class="mt-1">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                  {{ template.category?.name || $t('admin.templates.uncategorized') }}
                </span>
              </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $t('admin.templates.description') }}
              </h3>
              <p class="mt-1 text-gray-900 dark:text-gray-100">
                {{ template.description || $t('admin.templates.no_description') }}
              </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  {{ $t('admin.templates.usage_count') }}
                </p>
                <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                  {{ template.usage_count || 0 }}
                </p>
              </div>
              <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  {{ $t('admin.templates.purchase_count') }}
                </p>
                <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                  {{ template.purchase_count || 0 }}
                </p>
              </div>
            </div>

            <!-- Admin Controls -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ $t('admin.templates.controls') }}
              </h3>
              
              <div class="space-y-4">
                <!-- Toggle Active -->
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('common.active') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ $t('admin.templates.active_description') || 'When inactive, template is hidden from selection.' }}
                    </p>
                  </div>
                  <button
                    @click="toggleActive"
                    type="button"
                    :class="[
                      template.is_active ? 'bg-emerald-600' : 'bg-gray-200 dark:bg-gray-600',
                      'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500'
                    ]"
                    role="switch"
                    :aria-checked="template.is_active"
                  >
                    <span
                      :class="[
                        template.is_active ? 'translate-x-5' : 'translate-x-0',
                        'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
                      ]"
                    />
                  </button>
                </div>
                <!-- Toggle Public/Private -->
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('admin.templates.visibility') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ $t('admin.templates.visibility_description') }}
                    </p>
                  </div>
                  <button
                    @click="toggleVisibility"
                    type="button"
                    :class="[
                      template.is_public ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600',
                      'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                    ]"
                    role="switch"
                    :aria-checked="template.is_public"
                    :aria-labelledby="'visibility-toggle'"
                  >
                    <span
                      :class="[
                        template.is_public ? 'translate-x-5' : 'translate-x-0',
                        'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
                      ]"
                    />
                  </button>
                </div>

                <!-- Toggle Premium -->
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('admin.templates.premium_status') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ $t('admin.templates.premium_description') }}
                    </p>
                  </div>
                  <button
                    @click="togglePremium"
                    type="button"
                    :class="[
                      template.is_premium ? 'bg-yellow-500' : 'bg-gray-200 dark:bg-gray-600',
                      'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500'
                    ]"
                    role="switch"
                    :aria-checked="template.is_premium"
                    :aria-labelledby="'premium-toggle'"
                  >
                    <span
                      :class="[
                        template.is_premium ? 'translate-x-5' : 'translate-x-0',
                        'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
                      ]"
                    />
                  </button>
                </div>

                <!-- Price Input (visible only if premium) -->
                <div v-if="template.is_premium" class="mt-4">
                  <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('admin.templates.price') }}
                  </label>
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input
                      type="number"
                      name="price"
                      id="price"
                      v-model.number="template.price"
                      class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                      placeholder="0.00"
                      step="0.01"
                      min="0"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm" id="price-currency">
                        USD
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4">
                  <button
                    @click="saveChanges"
                    type="button"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ $t('common.save_changes') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Usage Statistics -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-8">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $t('admin.templates.usage_statistics') }}
        </h2>
      </div>
      <div class="px-6 py-4">
        <!-- Placeholder for charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg h-64 flex items-center justify-center">
            <p class="text-gray-500 dark:text-gray-400">
              {{ $t('admin.templates.usage_chart_placeholder') }}
            </p>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg h-64 flex items-center justify-center">
            <p class="text-gray-500 dark:text-gray-400">
              {{ $t('admin.templates.downloads_chart_placeholder') }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div v-if="showDeleteModal" class="fixed z-50 inset-0 overflow-y-auto" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeDeleteModal"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.64 5.64l12.72 12.72M4 6h16M6 6l1 14h10l1-14" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ $t('admin.templates.delete_confirm.title') || 'Delete template?' }}
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-300">
                  {{ $t('admin.templates.delete_confirm.message') || 'This action cannot be undone.' }}
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
            <button type="button" @click="performDelete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:col-start-2 sm:text-sm">
              {{ $t('common.delete') }}
            </button>
            <button type="button" @click="closeDeleteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-white sm:mt-0 sm:col-start-1 sm:text-sm">
              {{ $t('common.cancel') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useTemplatesStore } from '@/stores/templates';

export default {
  name: 'TemplateDetailView',
  
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { t } = useI18n();
    const store = useTemplatesStore();
    
    const template = ref({ id: Number(route.params.id) });
    const showDeleteModal = ref(false);

    // Format date for display
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    };

    // Toggle template visibility
    const toggleVisibility = () => {
      template.value.is_public = !template.value.is_public;
    };

    // Toggle premium status
    const togglePremium = () => {
      template.value.is_premium = !template.value.is_premium;
      if (!template.value.is_premium) template.value.price = 0;
      else if (template.value.price === 0) template.value.price = 9.99;
    };

    // Toggle active state
    const toggleActive = () => {
      template.value.is_active = !template.value.is_active;
    };

    // Save changes to the template
    const saveChanges = async () => {
      try {
        const payload = {
          name: template.value.name,
          description: template.value.description,
          category: template.value.category,
          version: template.value.version,
          thumbnail: template.value.thumbnail,
          content: template.value.content,
          is_public: template.value.is_public,
          is_premium: template.value.is_premium,
          price: template.value.price,
          is_active: template.value.is_active,
        };
        await store.update(template.value.id, payload);
        alert(t('admin.templates.saved_successfully'));
      } catch (error) {
        console.error('Error saving template:', error);
        alert(t('admin.templates.save_error'));
      }
    };

    // Actions
    const goEdit = () => {
      router.push({ name: 'AdminEditTemplate', params: { id: template.value.id } });
    };

    const confirmDelete = () => {
      showDeleteModal.value = true;
    };

    const closeDeleteModal = () => {
      showDeleteModal.value = false;
    };

    const performDelete = async () => {
      try {
        await store.remove(template.value.id);
        router.push({ name: 'AdminTemplates' });
      } finally {
        showDeleteModal.value = false;
      }
    };

    const copyKey = async () => {
      try {
        await navigator.clipboard.writeText(template.value.key || '');
        // optional toast could be here
      } catch (e) {
        // Fallback: log error so linter doesn't flag empty block
        console.error('Failed to copy key to clipboard', e);
      }
    };

    // Fetch template data when component mounts
    onMounted(async () => {
      try {
        const data = await store.fetchOne(route.params.id);
        template.value = data;
      } catch (e) {
        // If not found or unauthorized, go back
        router.push('/admin/templates');
      }
    });

    return {
      template,
      showDeleteModal,
      formatDate,
      toggleVisibility,
      togglePremium,
      toggleActive,
      saveChanges,
      goEdit,
      confirmDelete,
      closeDeleteModal,
      performDelete,
      copyKey,
    };
  }
};
</script>
