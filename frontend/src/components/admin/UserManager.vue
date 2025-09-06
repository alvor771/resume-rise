<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Management</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Manage user accounts and their permissions
          </p>
        </div>
        <div class="mt-4 sm:mt-0">
          <div class="flex space-x-3">
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
                placeholder="Search users..."
                @keyup.enter="searchUsers"
              />
            </div>
            <button
              v-if="hasPermission(PERMISSIONS.CREATE_USERS)"
              @click="openCreateUserModal"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              New User
            </button>
          </div>
        </div>
      </div>
      
      <!-- Filters -->
      <div class="mt-4 flex flex-wrap items-center gap-3">
        <div class="flex-shrink-0">
          <label for="role-filter" class="sr-only">Filter by role</label>
          <select
            id="role-filter"
            v-model="filters.role"
            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            @change="applyFilters"
          >
            <option value="">All Roles</option>
            <option v-for="role in availableRoles" :key="role.id" :value="role.id">
              {{ role.name }}
            </option>
          </select>
        </div>
        
        <div class="flex-shrink-0">
          <label for="status-filter" class="sr-only">Filter by status</label>
          <select
            id="status-filter"
            v-model="filters.status"
            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
            @change="applyFilters"
          >
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
        
        <button
          v-if="isFiltered"
          @click="resetFilters"
          class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Clear Filters
        </button>
      </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              User
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Role
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Last Active
            </th>
            <th scope="col" class="relative px-6 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-if="isLoading">
            <td colspan="5" class="px-6 py-8 text-center">
              <div class="flex justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
              </div>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Loading users...</p>
            </td>
          </tr>
          <tr v-else-if="filteredUsers.length === 0">
            <td colspan="5" class="px-6 py-8 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ isFiltered ? 'Try adjusting your filters' : 'Get started by creating a new user' }}
              </p>
              <div class="mt-6">
                <button
                  v-if="hasPermission(PERMISSIONS.CREATE_USERS)"
                  type="button"
                  @click="openCreateUserModal"
                  class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  New User
                </button>
              </div>
            </td>
          </tr>
          <tr v-for="user in paginatedUsers" v-else :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <img class="h-10 w-10 rounded-full" :src="user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=4f46e5&color=fff'" :alt="user.name">
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ user.name }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ user.email }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="getRoleBadgeClass(user.role)"
                >
                  {{ getRoleName(user.role) }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span 
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="getStatusBadgeClass(user.status)"
              >
                {{ user.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ formatLastActive(user.lastActive) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <button
                  v-if="hasPermission(permissions.EDIT_USERS)"
                  @click="editUser(user)"
                  class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                  title="Edit user"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button
                  v-if="hasPermission(permissions.DELETE_USERS) && !user.isCurrentUser"
                  @click="confirmDeleteUser(user)"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                  title="Delete user"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="!isLoading && filteredUsers.length > 0" class="bg-white dark:bg-gray-800 px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
      <div class="flex-1 flex justify-between sm:hidden">
        <button
          @click="currentPage = Math.max(1, currentPage - 1)"
          :disabled="currentPage === 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
        >
          Previous
        </button>
        <button
          @click="currentPage = Math.min(totalPages, currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
        >
          Next
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700 dark:text-gray-300">
            Showing <span class="font-medium">{{ (currentPage - 1) * pageSize + 1 }}</span> to 
            <span class="font-medium">{{ Math.min(currentPage * pageSize, filteredUsers.length) }}</span> of 
            <span class="font-medium">{{ filteredUsers.length }}</span> results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button
              @click="currentPage = Math.max(1, currentPage - 1)"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="sr-only">Previous</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
            
            <template v-for="page in visiblePages" :key="page">
              <button
                v-if="page === '...'"
                disabled
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                ...
              </button>
              <button
                v-else
                @click="currentPage = page"
                :class="{
                  'z-10 bg-indigo-50 dark:bg-indigo-900 border-indigo-500 text-indigo-600 dark:text-indigo-200': currentPage === page,
                  'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600': currentPage !== page
                }"
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              >
                {{ page }}
              </button>
            </template>
            
            <button
              @click="currentPage = Math.min(totalPages, currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="sr-only">Next</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- User Form Modal -->
    <UserFormModal
      :show="showUserModal"
      :user="editingUser"
      :roles="availableRoles"
      @close="closeUserModal"
      @save="saveUser"
    />

    <!-- Delete Confirmation Modal -->
    <ConfirmationModal
      :show="showDeleteModal"
      title="Delete User"
      :message="`Are you sure you want to delete the user '${userToDelete?.name}'? This action cannot be undone.`"
      confirm-text="Delete User"
      confirm-variant="danger"
      @confirm="deleteUser"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRBAC } from '@/composables/useRBAC';
import UserFormModal from './UserFormModal.vue';
import ConfirmationModal from '@/components/ui/ConfirmationModal.vue';

const props = defineProps({
  users: {
    type: Array,
    default: () => [],
  },
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

// State
const searchQuery = ref('');
const currentPage = ref(1);
const pageSize = ref(10);
const showUserModal = ref(false);
const editingUser = ref(null);
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const isLoading = ref(false);

// Filters
const filters = ref({
  role: '',
  status: '',
});

// Computed properties
const filteredUsers = computed(() => {
  return props.users.filter(user => {
    // Search filter
    const matchesSearch = 
      !searchQuery.value ||
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
    
    // Role filter
    const matchesRole = !filters.value.role || user.role === filters.value.role;
    
    // Status filter
    const matchesStatus = !filters.value.status || user.status === filters.value.status;
    
    return matchesSearch && matchesRole && matchesStatus;
  });
});

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return filteredUsers.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredUsers.value.length / pageSize.value);
});

const visiblePages = computed(() => {
  const pages = [];
  const maxVisible = 5;
  let startPage = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let endPage = Math.min(totalPages.value, startPage + maxVisible - 1);
  
  if (endPage - startPage + 1 < maxVisible) {
    startPage = Math.max(1, endPage - maxVisible + 1);
  }
  
  if (startPage > 1) {
    pages.push(1);
    if (startPage > 2) {
      pages.push('...');
    }
  }
  
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  
  if (endPage < totalPages.value) {
    if (endPage < totalPages.value - 1) {
      pages.push('...');
    }
    pages.push(totalPages.value);
  }
  
  return pages;
});

const isFiltered = computed(() => {
  return filters.value.role || filters.value.status || searchQuery.value;
});

const availableRoles = computed(() => {
  return props.roles.filter(role => role.id !== 'super_admin' || hasPermission('super_admin'));
});

// Methods
const formatLastActive = (date) => {
  if (!date) return 'Never';
  
  const now = new Date();
  const lastActive = new Date(date);
  const diffInDays = Math.floor((now - lastActive) / (1000 * 60 * 60 * 24));
  
  if (diffInDays === 0) {
    return 'Today';
  } else if (diffInDays === 1) {
    return 'Yesterday';
  } else if (diffInDays < 7) {
    return `${diffInDays} days ago`;
  } else if (diffInDays < 30) {
    const weeks = Math.floor(diffInDays / 7);
    return `${weeks} ${weeks === 1 ? 'week' : 'weeks'} ago`;
  } else if (diffInDays < 365) {
    const months = Math.floor(diffInDays / 30);
    return `${months} ${months === 1 ? 'month' : 'months'} ago`;
  } else {
    const years = Math.floor(diffInDays / 365);
    return `${years} ${years === 1 ? 'year' : 'years'} ago`;
  }
};

const getRoleName = (roleId) => {
  const role = props.roles.find(r => r.id === roleId);
  return role ? role.name : roleId;
};

const getRoleBadgeClass = (roleId) => {
  const role = props.roles.find(r => r.id === roleId);
  if (!role) return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
  
  switch(role.id) {
    case 'super_admin':
      return 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200';
    case 'admin':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
    case 'editor':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
    case 'user':
      return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    default:
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
  }
};

const getStatusBadgeClass = (status) => {
  switch(status) {
    case 'active':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
    case 'inactive':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
    case 'suspended':
      return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
  }
};

const searchUsers = () => {
  currentPage.value = 1;
  // In a real app, you would trigger an API call here
};

const applyFilters = () => {
  currentPage.value = 1;
  // In a real app, you would trigger an API call here
};

const resetFilters = () => {
  searchQuery.value = '';
  filters.value = {
    role: '',
    status: '',
  };
  currentPage.value = 1;
};

const openCreateUserModal = () => {
  editingUser.value = {
    name: '',
    email: '',
    role: 'user',
    status: 'active',
  };
  showUserModal.value = true;
};

const editUser = (user) => {
  editingUser.value = { ...user };
  showUserModal.value = true;
};

const closeUserModal = () => {
  showUserModal.value = false;
  editingUser.value = null;
};

const saveUser = async (userData) => {
  try {
    isLoading.value = true;
    // In a real app, this would be an API call
    console.log('Saving user:', userData);
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Emit event to refresh users list
    emit('refresh');
    showUserModal.value = false;
    
    // Show success notification
    // notificationStore.addNotification({
    //   type: 'success',
    //   title: 'Success',
    //   message: userData.id ? 'User updated successfully' : 'User created successfully',
    // });
  } catch (error) {
    console.error('Error saving user:', error);
    // notificationStore.addNotification({
    //   type: 'error',
    //   title: 'Error',
    //   message: error.message || 'Failed to save user',
    // });
  } finally {
    isLoading.value = false;
  }
};

const confirmDeleteUser = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

const deleteUser = async () => {
  if (!userToDelete.value) return;
  
  try {
    isLoading.value = true;
    // In a real app, this would be an API call
    console.log('Deleting user:', userToDelete.value.id);
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Emit event to refresh users list
    emit('refresh');
    showDeleteModal.value = false;
    
    // Show success notification
    // notificationStore.addNotification({
    //   type: 'success',
    //   title: 'Success',
    //   message: 'User deleted successfully',
    // });
  } catch (error) {
    console.error('Error deleting user:', error);
    // notificationStore.addNotification({
    //   type: 'error',
    //   title: 'Error',
    //   message: error.message || 'Failed to delete user',
    // });
  } finally {
    isLoading.value = false;
    userToDelete.value = null;
  }
};

// Watch for changes to filters
watch([() => filters.value.role, () => filters.value.status], () => {
  applyFilters();
});

// Watch for changes to current page and reset scroll to top
watch(currentPage, () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Initial data load
onMounted(() => {
  // Load initial data if needed
});
</script>
