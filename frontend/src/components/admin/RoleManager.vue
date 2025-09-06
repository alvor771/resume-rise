<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">Role Management</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Manage user roles and their permissions
      </p>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700">
      <div class="flex justify-between items-center">
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
          <input
            v-model="searchQuery"
            type="text"
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-800 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Search roles..."
          />
        </div>
        <button
          v-if="hasPermission(PERMISSIONS.CREATE_ROLES)"
          @click="openCreateRoleModal"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          New Role
        </button>
      </div>
    </div>

    <!-- Roles List -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Role
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Description
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Users
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="role in filteredRoles" :key="role.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                  <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ role.name }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ role.key }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900 dark:text-white">{{ role.description }}</div>
              <div class="mt-1 flex flex-wrap gap-1">
                <span 
                  v-for="permission in role.permissions.slice(0, 3)" 
                  :key="permission"
                  class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
                >
                  {{ permission }}
                </span>
                <span 
                  v-if="role.permissions.length > 3" 
                  class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
                >
                  +{{ role.permissions.length - 3 }} more
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 dark:text-white">{{ role.userCount || 0 }} users</div>
              <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ Math.floor(Math.random() * 100) }}% of total
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button
                v-if="hasPermission(permissions.EDIT_ROLES)"
                @click="editRole(role)"
                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-4"
              >
                Edit
              </button>
              <button
                v-if="hasPermission(permissions.DELETE_ROLES) && !role.isSystem"
                @click="confirmDelete(role)"
                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create/Edit Role Modal -->
    <RoleFormModal
      :show="showRoleModal"
      :role="editingRole"
      :available-permissions="availablePermissions"
      @close="closeRoleModal"
      @save="saveRole"
    />

    <!-- Delete Confirmation Modal -->
    <ConfirmationModal
      :show="showDeleteModal"
      title="Delete Role"
      :message="`Are you sure you want to delete the role '${roleToDelete?.name}'? This action cannot be undone.`"
      confirm-text="Delete"
      confirm-variant="danger"
      @confirm="deleteRole"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRBAC } from '@/composables/useRBAC';
import RoleFormModal from './RoleFormModal.vue';
import ConfirmationModal from '@/components/ui/ConfirmationModal.vue';

const props = defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
  permissions: {
    type: Object,
    required: true,
  },
});

// Destructure PERMISSIONS from props for easier access in template
const { permissions: PERMISSIONS } = props;

const emit = defineEmits(['refresh']);

const { hasPermission } = useRBAC();

const searchQuery = ref('');
const showRoleModal = ref(false);
const editingRole = ref(null);
const showDeleteModal = ref(false);
const roleToDelete = ref(null);

// Mock data - replace with actual API calls
const availablePermissions = [
  { id: 'users:read', name: 'View Users', description: 'Can view user accounts' },
  { id: 'users:create', name: 'Create Users', description: 'Can create new user accounts' },
  { id: 'users:edit', name: 'Edit Users', description: 'Can edit existing user accounts' },
  { id: 'users:delete', name: 'Delete Users', description: 'Can delete user accounts' },
  { id: 'roles:manage', name: 'Manage Roles', description: 'Can create, edit, and delete roles' },
  { id: 'templates:manage', name: 'Manage Templates', description: 'Can manage resume templates' },
  { id: 'analytics:view', name: 'View Analytics', description: 'Can view analytics and reports' },
  { id: 'settings:manage', name: 'Manage Settings', description: 'Can modify system settings' },
];

const filteredRoles = computed(() => {
  if (!searchQuery.value) return props.roles;
  const query = searchQuery.value.toLowerCase();
  return props.roles.filter(
    role =>
      role.name.toLowerCase().includes(query) ||
      role.key.toLowerCase().includes(query) ||
      role.description.toLowerCase().includes(query)
  );
});

const openCreateRoleModal = () => {
  editingRole.value = {
    name: '',
    key: '',
    description: '',
    permissions: [],
    isSystem: false,
  };
  showRoleModal.value = true;
};

const editRole = (role) => {
  editingRole.value = { ...role };
  showRoleModal.value = true;
};

const closeRoleModal = () => {
  showRoleModal.value = false;
  editingRole.value = null;
};

const saveRole = async (roleData) => {
  try {
    // In a real app, this would be an API call
    console.log('Saving role:', roleData);
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Emit event to refresh roles list
    emit('refresh');
    showRoleModal.value = false;
    
    // Show success notification
    // notificationStore.addNotification({
    //   type: 'success',
    //   title: 'Success',
    //   message: roleData.id ? 'Role updated successfully' : 'Role created successfully',
    // });
  } catch (error) {
    console.error('Error saving role:', error);
    // notificationStore.addNotification({
    //   type: 'error',
    //   title: 'Error',
    //   message: error.message || 'Failed to save role',
    // });
  }
};

const confirmDelete = (role) => {
  roleToDelete.value = role;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  roleToDelete.value = null;
};

const deleteRole = async () => {
  if (!roleToDelete.value) return;
  
  try {
    // In a real app, this would be an API call
    console.log('Deleting role:', roleToDelete.value.id);
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Emit event to refresh roles list
    emit('refresh');
    showDeleteModal.value = false;
    
    // Show success notification
    // notificationStore.addNotification({
    //   type: 'success',
    //   title: 'Success',
    //   message: 'Role deleted successfully',
    // });
  } catch (error) {
    console.error('Error deleting role:', error);
    // notificationStore.addNotification({
    //   type: 'error',
    //   title: 'Error',
    //   message: error.message || 'Failed to delete role',
    // });
  } finally {
    roleToDelete.value = null;
  }
};

onMounted(() => {
  // Load initial data if needed
});
</script>
