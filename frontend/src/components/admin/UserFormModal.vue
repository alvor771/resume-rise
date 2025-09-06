<template>
  <Modal :show="show" @close="close">
    <template #title>
      {{ user.id ? 'Edit User' : 'Create New User' }}
    </template>

    <form @submit.prevent="handleSubmit">
      <div class="space-y-4">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Full Name <span class="text-red-500">*</span>
          </label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :disabled="isSubmitting"
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Email Address <span class="text-red-500">*</span>
          </label>
          <input
            id="email"
            v-model="formData.email"
            type="email"
            required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :disabled="isSubmitting || !!user.id"
            :class="{ 'bg-gray-100 dark:bg-gray-800': !!user.id }"
          />
          <p v-if="user.id" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Email address cannot be changed after user creation
          </p>
        </div>

        <!-- Role -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Role <span class="text-red-500">*</span>
          </label>
          <select
            id="role"
            v-model="formData.role"
            required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            :disabled="isSubmitting || (user.id && user.isCurrentUser)"
          >
            <option 
              v-for="role in filteredRoles" 
              :key="role.id" 
              :value="role.id"
              :disabled="role.id === 'super_admin' && !hasPermission(PERMISSIONS.SUPER_ADMIN)"
            >
              {{ role.name }}
              {{ role.id === 'super_admin' && !hasPermission(PERMISSIONS.SUPER_ADMIN) ? '(Not Allowed)' : '' }}
            </option>
          </select>
          <p v-if="user.id && user.isCurrentUser" class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">
            You cannot change your own role
          </p>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Status <span class="text-red-500">*</span>
          </label>
          <div class="mt-1 space-x-4">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="formData.status"
                value="active"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                :disabled="isSubmitting || (user.id && user.isCurrentUser)"
              >
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="formData.status"
                value="inactive"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                :disabled="isSubmitting || (user.id && user.isCurrentUser)"
              >
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactive</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="formData.status"
                value="suspended"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                :disabled="isSubmitting || (user.id && user.isCurrentUser)"
              >
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Suspended</span>
            </label>
          </div>
          <p v-if="user.id && user.isCurrentUser" class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">
            You cannot change your own status
          </p>
        </div>

        <!-- Password Fields (only for new users) -->
        <div v-if="!user.id">
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Password <span class="text-red-500">*</span>
            </label>
            <button
              type="button"
              @click="generatePassword"
              class="text-xs text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
              :disabled="isSubmitting"
            >
              Generate
            </button>
          </div>
          <div class="mt-1 relative rounded-md shadow-sm">
            <input
              id="password"
              v-model="formData.password"
              :type="showPassword ? 'text' : 'password'"
              required
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-10"
              :disabled="isSubmitting"
            />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
              <button
                type="button"
                @click="togglePasswordVisibility"
                class="text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300"
                :disabled="isSubmitting"
              >
                <svg
                  v-if="showPassword"
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                  />
                </svg>
                <svg
                  v-else
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  />
                </svg>
              </button>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Password must be at least 8 characters long
          </p>
        </div>

        <!-- Send Welcome Email -->
        <div v-if="!user.id" class="flex items-start">
          <div class="flex items-center h-5">
            <input
              id="send-welcome-email"
              v-model="formData.sendWelcomeEmail"
              type="checkbox"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
              :disabled="isSubmitting"
            />
          </div>
          <div class="ml-3 text-sm">
            <label for="send-welcome-email" class="font-medium text-gray-700 dark:text-gray-300">
              Send welcome email with account details
            </label>
            <p class="text-gray-500 dark:text-gray-400">
              The user will receive an email with instructions to set their password
            </p>
          </div>
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
          {{ isSubmitting ? 'Saving...' : (user.id ? 'Update User' : 'Create User') }}
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
import { useRBAC } from '@/composables/useRBAC';
import { PERMISSIONS } from '@/config/roles';
import Modal from '@/components/ui/Modal.vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  user: {
    type: Object,
    default: () => ({
      id: null,
      name: '',
      email: '',
      role: 'user',
      status: 'active',
      isCurrentUser: false,
    }),
  },
  roles: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close', 'save']);

const { hasPermission } = useRBAC();

const isSubmitting = ref(false);
const showPassword = ref(false);
const formData = ref({
  name: '',
  email: '',
  role: 'user',
  status: 'active',
  password: '',
  sendWelcomeEmail: true,
});

const isFormValid = computed(() => {
  return (
    formData.value.name.trim() !== '' &&
    formData.value.email.trim() !== '' &&
    formData.value.role &&
    formData.value.status &&
    (props.user.id || formData.value.password.length >= 8)
  );
});

const filteredRoles = computed(() => {
  return props.roles.filter(role => {
    // Only show super_admin role to super admins
    if (role.id === 'super_admin' && !hasPermission(PERMISSIONS.SUPER_ADMIN)) {
      return false;
    }
    return true;
  });
});

// Watch for user prop changes
watch(() => props.user, (newUser) => {
  formData.value = {
    name: newUser.name || '',
    email: newUser.email || '',
    role: newUser.role || 'user',
    status: newUser.status || 'active',
    password: '',
    sendWelcomeEmail: true,
  };
  
  // Reset password visibility when opening the modal
  if (props.show) {
    showPassword.value = false;
  }
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
    const userData = {
      ...formData.value,
      id: props.user.id || null,
    };
    
    // Don't send password if it's an edit and password wasn't changed
    if (props.user.id && !userData.password) {
      delete userData.password;
    }
    
    await emit('save', userData);
  } catch (error) {
    console.error('Error saving user:', error);
    // notificationStore.addNotification({
    //   type: 'error',
    //   title: 'Error',
    //   message: error.message || 'Failed to save user',
    // });
  } finally {
    isSubmitting.value = false;
  }
};

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const generatePassword = () => {
  const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]\\:;?><,./-=';
  let password = '';
  
  // Ensure at least one of each character type
  password += 'abcdefghijklmnopqrstuvwxyz'.charAt(Math.floor(Math.random() * 26));
  password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.charAt(Math.floor(Math.random() * 26));
  password += '0123456789'.charAt(Math.floor(Math.random() * 10));
  password += '!@#$%^&*()_+~`|}{[]\\:;?><,./-='.charAt(Math.floor(Math.random() * 28));
  
  // Fill the rest of the password
  for (let i = 4; i < 12; i++) {
    password += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  
  // Shuffle the password
  password = password.split('').sort(() => 0.5 - Math.random()).join('');
  
  formData.value.password = password;
  showPassword.value = true;
};
</script>
