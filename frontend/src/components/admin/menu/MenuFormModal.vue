<template>
  <TransitionRoot appear :show="true" as="template">
    <HeadlessDialog as="div" class="relative z-50" @close="$emit('close')">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <component
                    :is="editing ? 'PencilSquareIcon' : 'PlusCircleIcon'"
                    class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mr-2"
                  />
                  <DialogTitle as="h3" class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ editing ? $t('menu.editMenuItem') : $t('menu.addMenuItem') }}
                  </DialogTitle>
                </div>
                <button
                  type="button"
                  class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none"
                  @click="$emit('close')"
                >
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>

              <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Title & Path Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Title -->
                  <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('form.title') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <DocumentTextIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <input
                        type="text"
                        id="title"
                        v-model="formData.title"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        :placeholder="$t('form.enterTitle')"
                        required
                      />
                    </div>
                  </div>
                  
                  <!-- Path -->
                  <div>
                    <label for="path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('form.path') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="flex rounded-md shadow-sm">
                      <span
                        v-if="parentPath"
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 dark:bg-gray-600 dark:border-gray-500 text-gray-700 dark:text-gray-200 text-sm"
                      >
                        {{ parentPath }}
                      </span>
                      <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <LinkIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <input
                          type="text"
                          id="path"
                          v-model="formData.path"
                          class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-r-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          :class="{ 'rounded-l-md': !parentPath }"
                          :placeholder="$t('form.enterPath')"
                          required
                        />
                      </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                      {{ $t('form.pathHelp') }}
                    </p>
                  </div>
                </div>
                
                <!-- Icon & Layout Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Icon -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('form.icon') }}
                    </label>
                    <div class="flex items-center space-x-2">
                      <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <Squares2X2Icon class="h-5 w-5 text-gray-400" />
                        </div>
                        <input
                          type="text"
                          id="icon"
                          v-model="formData.icon"
                          class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          :placeholder="$t('form.enterIconClass')"
                          @focus="showIconPicker = true"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3" v-if="formData.icon">
                          <i :class="formData.icon" class="h-5 w-5 text-gray-500 dark:text-gray-400"></i>
                        </div>
                      </div>
                      <button
                        type="button"
                        @click="showIconPicker = !showIconPicker"
                        class="inline-flex items-center p-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :title="$t('form.chooseIcon')"
                      >
                        <SquaresPlusIcon class="h-5 w-5" />
                      </button>
                    </div>
                    
                    <!-- Icon Picker -->
                    <div v-if="showIconPicker" class="mt-2 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 shadow-lg">
                      <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                          {{ $t('form.selectIcon') }}
                        </h4>
                        <div class="flex space-x-2">
                          <button
                            type="button"
                            @click="formData.icon = ''"
                            class="text-xs text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300"
                          >
                            {{ $t('common.clear') }}
                          </button>
                          <button
                            type="button"
                            @click="showIconPicker = false"
                            class="text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300"
                          >
                            <XMarkIcon class="h-5 w-5" />
                          </button>
                        </div>
                      </div>
                      <div class="grid grid-cols-6 gap-2 max-h-48 overflow-y-auto p-1">
                        <button
                          v-for="icon in commonIcons"
                          :key="icon"
                          type="button"
                          @click="selectIcon(icon)"
                          class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center"
                          :class="{ 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400': formData.icon === icon }"
                          :title="icon"
                        >
                          <i :class="icon" class="h-5 w-5"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Layout -->
                  <div>
                    <label for="layout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('form.layout') }}
                    </label>
                    <div class="relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <RectangleGroupIcon class="h-5 w-5 text-gray-400" />
                      </div>
                      <select
                        id="layout"
                        v-model="formData.layout"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      >
                        <option v-for="layout in availableLayouts" :key="layout.value" :value="layout.value">
                          {{ layout.label }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <!-- Visibility & Permissions Card -->
                <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                  <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                    <ShieldCheckIcon class="h-5 w-5 text-indigo-500 mr-2" />
                    {{ $t('form.accessControl') }}
                  </h4>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Visibility -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ $t('form.visibility') }}
                      </label>
                      <div class="space-y-3">
                        <BaseCheckbox
                          v-model="formData.is_visible"
                          :label="$t('form.visibleInMenu')"
                          name="is_visible"
                          :hint="$t('form.visibleInMenuHelp')"
                          class="mb-3"
                        />
                        
                        <BaseCheckbox
                          v-model="formData.is_guest"
                          :label="$t('form.visibleToGuestsOnly')"
                          name="is_guest"
                          :hint="$t('form.visibleToGuestsOnlyHelp')"
                          :disabled="formData.is_auth"
                          class="mb-3"
                        />
                        
                        <BaseCheckbox
                          v-model="formData.is_auth"
                          :label="$t('form.visibleToLoggedInOnly')"
                          name="is_auth"
                          :hint="$t('form.visibleToLoggedInOnlyHelp')"
                          :disabled="formData.is_guest"
                        />
                      </div>
                    </div>
                    
                    <!-- Permissions -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ $t('form.permissions') }}
                      </label>
                      <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-3 max-h-48 overflow-y-auto">
                        <div v-if="availablePermissions.length === 0" class="text-sm text-gray-500 dark:text-gray-400 italic">
                          {{ $t('form.noPermissionsAvailable') }}
                        </div>
                        <div v-else class="space-y-2">
                          <div v-for="permission in availablePermissions" :key="permission" class="mb-2">
                            <BaseCheckbox
                              :id="`permission-${permission}`"
                              v-model="formData.permissions"
                              :value="permission"
                              :label="permission"
                              :hint="$t(`permissions.${permission}`) || $t('permissions.noDescription')"
                              name="permissions"
                              type="checkbox"
                              class="mb-2"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                  <button
                    type="button"
                    @click="$emit('close')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ $t('common.cancel') }}
                  </button>
                  <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="loading"
                  >
                    <ArrowPathIcon v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" />
                    {{ editing ? $t('common.update') : $t('common.create') }}
                  </button>
                </div>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </HeadlessDialog>
  </TransitionRoot>
</template>

<script>
import { ref, reactive, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { Dialog as HeadlessDialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { 
  XMarkIcon,
  DocumentTextIcon,
  LinkIcon,
  Squares2X2Icon,
  RectangleGroupIcon,
  ShieldCheckIcon,
  PencilSquareIcon,
  PlusCircleIcon,
  SquaresPlusIcon,
  ArrowPathIcon
} from '@heroicons/vue/24/outline';
import BaseCheckbox from '@/components/ui/BaseCheckbox.vue';

export default {
  name: 'MenuFormModal',
  components: {
    HeadlessDialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    XMarkIcon,
    DocumentTextIcon,
    LinkIcon,
    Squares2X2Icon,
    RectangleGroupIcon,
    ShieldCheckIcon,
    PencilSquareIcon,
    PlusCircleIcon,
    SquaresPlusIcon,
    ArrowPathIcon,
    BaseCheckbox
  },
  props: {
    item: {
      type: Object,
      default: () => ({
        title: '',
        path: '',
        icon: '',
        layout: 'default',
        is_visible: true,
        is_guest: false,
        is_auth: false,
        permissions: []
      })
    },
    parentId: {
      type: [Number, String],
      default: null
    },
    parentPath: {
      type: String,
      default: ''
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  emits: ['submit', 'close'],
  setup(props, { emit }) {
    const { t } = useI18n();
    
    const showIconPicker = ref(false);
    const editing = ref(!!props.item?.id);
    
    const formData = reactive({
      title: props.item?.title || '',
      path: props.item?.path || '',
      icon: props.item?.icon || '',
      layout: props.item?.layout || 'default',
      is_visible: props.item?.is_visible !== false,
      is_guest: props.item?.is_guest || false,
      is_auth: props.item?.is_auth || false,
      permissions: Array.isArray(props.item?.permissions) ? [...props.item.permissions] : []
    });
    
    const availableLayouts = [
      { value: 'default', label: t('layouts.default') },
      { value: 'auth', label: t('layouts.auth') },
      { value: 'admin', label: t('layouts.admin') },
      { value: 'public', label: t('layouts.public') }
    ];
    
    const availablePermissions = [
      'view_dashboard',
      'manage_users',
      'manage_roles',
      'manage_permissions',
      'manage_menu',
      'view_reports'
    ];
    
    const commonIcons = [
      'fas fa-home',
      'fas fa-user',
      'fas fa-cog',
      'fas fa-chart-bar',
      'fas fa-file-alt',
      'fas fa-envelope',
      'fas fa-bell',
      'fas fa-calendar',
      'fas fa-users',
      'fas fa-tag',
      'fas fa-shopping-cart',
      'fas fa-box',
      'fas fa-credit-card',
      'fas fa-truck',
      'fas fa-chart-pie',
      'fas fa-cog',
      'fas fa-lock',
      'fas fa-question-circle',
      'fas fa-info-circle',
      'fas fa-exclamation-triangle'
    ];
    
    const selectIcon = (icon) => {
      formData.icon = icon;
      showIconPicker.value = false;
    };
    
    const handleSubmit = () => {
      // Create a new object with all form data
      const data = { 
        ...formData,
        // Ensure boolean fields are properly converted
        is_visible: Boolean(formData.is_visible),
        is_guest: Boolean(formData.is_guest),
        is_auth: Boolean(formData.is_auth)
      };
      
      // Add parent_id if it exists
      if (props.parentId) {
        data.parent_id = props.parentId;
      }
      
      // Emit the submit event with the prepared data
      emit('submit', data);
    };
    
    // Watch for changes in is_guest and is_auth to ensure mutual exclusivity
    watch(() => formData.is_guest, (newVal) => {
      if (newVal) {
        formData.is_auth = false;
      }
    });
    
    watch(() => formData.is_auth, (newVal) => {
      if (newVal) {
        formData.is_guest = false;
      }
    });
    
    return {
      formData,
      showIconPicker,
      availableLayouts,
      availablePermissions,
      commonIcons,
      selectIcon,
      handleSubmit,
      editing
    };
  }
};
</script>
