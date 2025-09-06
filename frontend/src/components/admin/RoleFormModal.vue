<template>
  <Modal :show="show" @close="close">
    <template #title>
      {{ role.id ? 'Edit Role' : 'Create New Role' }}
    </template>

    <form @submit.prevent="handleSubmit">
      <div class="space-y-4">
        <!-- Role Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Role Name <span class="text-red-500">*</span>
          </label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :disabled="isSubmitting || role.isSystem"
          />
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            A human-readable name for the role
          </p>
        </div>

        <!-- Role Key -->
        <div v-if="!role.id">
          <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Role Key <span class="text-red-500">*</span>
          </label>
          <input
            id="key"
            v-model="formData.key"
            type="text"
            required
            pattern="[a-z0-9_]+$"
            title="Only lowercase letters, numbers, and underscores are allowed"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :disabled="isSubmitting || role.isSystem"
          />
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            A unique key for the role (lowercase, underscores, no spaces)
          </p>
        </div>
        <div v-else>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Role Key
          </label>
          <div class="mt-1 text-sm text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 p-2 rounded">
            {{ role.key }}
          </div>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Description
          </label>
          <textarea
            id="description"
            v-model="formData.description"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :disabled="isSubmitting || role.isSystem"
          ></textarea>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            A brief description of what this role is for
          </p>
        </div>

        <!-- Permissions -->
        <div>
          <div class="flex items-center justify-between">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Permissions
            </label>
            <button
              type="button"
              class="text-xs text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
              @click="toggleSelectAll"
              v-if="!role.isSystem"
            >
              {{ selectAllText }}
            </button>
          </div>
          
          <div class="mt-2 space-y-2 max-h-60 overflow-y-auto p-2 border border-gray-200 dark:border-gray-600 rounded-md">
            <div v-for="permission in availablePermissions" :key="permission.id" class="flex items-start">
              <div class="flex items-center h-5">
                <input
                  :id="`permission-${permission.id}`"
                  v-model="formData.permissions"
                  type="checkbox"
                  :value="permission.id"
                  :disabled="isSubmitting || role.isSystem"
                  class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                />
              </div>
              <div class="ml-3 text-sm">
                <label :for="`permission-${permission.id}`" class="font-medium text-gray-700 dark:text-gray-300">
                  {{ permission.name }}
                </label>
                <p class="text-gray-500 dark:text-gray-400">
                  {{ permission.description }}
                </p>
              </div>
            </div>
          </div>
          
          <p v-if="role.isSystem" class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
            System roles have predefined permissions that cannot be modified.
          </p>
        </div>
      </div>

      <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
        <button
          type="submit"
          class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="isSubmitting || !isFormValid"
        >
          <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ isSubmitting ? 'Saving...' : 'Save Role' }}
        </button>
        <button
          type="button"
          class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm"
          @click="close"
          :disabled="isSubmitting"
        >
          Cancel
        </button>
      </div>
    </form>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import Modal from '@/components/ui/Modal.vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  role: {
    type: Object,
    default: () => ({
      id: null,
      name: '',
      key: '',
      description: '',
      permissions: [],
      isSystem: false,
    }),
  },
  availablePermissions: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close', 'save']);

const isSubmitting = ref(false);
const formData = ref({
  name: '',
  key: '',
  description: '',
  permissions: [],
  isSystem: false,
});

const isFormValid = computed(() => {
  return (
    formData.value.name.trim() !== '' &&
    formData.value.key.trim() !== '' &&
    /^[a-z0-9_]+$/.test(formData.value.key)
  );
});

const selectAllText = computed(() => {
  if (formData.value.permissions.length === props.availablePermissions.length) {
    return 'Deselect All';
  } else if (formData.value.permissions.length > 0) {
    return 'Select All';
  }
  return 'Select All';
});

// Watch for role prop changes
watch(() => props.role, (newRole) => {
  formData.value = {
    name: newRole.name || '',
    key: newRole.key || '',
    description: newRole.description || '',
    permissions: [...(newRole.permissions || [])],
    isSystem: newRole.isSystem || false,
  };
}, { immediate: true });

const close = () => {
  if (!isSubmitting.value) {
    emit('close');
  }
};

const handleSubmit = async () => {
  if (!isFormValid.value || isSubmitting.value) return;
  
  isSubmitting.value = true;
  
  try {
    const roleData = {
      ...formData.value,
      id: props.role.id || null,
    };
    
    await emit('save', roleData);
  } catch (error) {
    console.error('Error saving role:', error);
  } finally {
    isSubmitting.value = false;
  }
};

const toggleSelectAll = () => {
  if (formData.value.permissions.length === props.availablePermissions.length) {
    // Deselect all
    formData.value.permissions = [];
  } else {
    // Select all
    formData.value.permissions = props.availablePermissions.map(p => p.id);
  }
};
</script>
