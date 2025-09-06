<template>
  <div class="space-y-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Role-Based Access Control</h1>
        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
          Manage user roles and permissions across the application
        </p>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
      <!-- Tabs -->
      <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex -mb-px">
          <button
            v-for="tab in tabs"
            :key="tab.name"
            @click="currentTab = tab.name"
            :class="[
              'px-6 py-4 text-sm font-medium',
              currentTab === tab.name
                ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
              'whitespace-nowrap border-b-2 font-medium text-sm'
            ]"
          >
            <component :is="tab.icon" class="h-5 w-5 inline-block mr-2" />
            {{ tab.name }}
          </button>
        </nav>
      </div>

      <!-- Tab Panels -->
      <div class="p-6">
        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mx-auto"></div>
          <p class="mt-3 text-gray-600 dark:text-gray-400">Loading RBAC data...</p>
        </div>

        <!-- Content -->
        <template v-else>
          <!-- Users Panel -->
          <div v-if="currentTab === 'Users'">
            <Suspense>
              <template #default>
                <UserManager 
                  :users="users" 
                  :roles="roles" 
                  :permissions="PERMISSIONS"
                  @refresh="loadData"
                />
              </template>
              <template #fallback>
                <div class="text-center py-8">
                  <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mx-auto"></div>
                  <p class="mt-2 text-gray-600 dark:text-gray-400">Loading User Manager...</p>
                </div>
              </template>
            </Suspense>
          </div>

          <!-- Roles Panel -->
          <div v-else-if="currentTab === 'Roles'">
            <Suspense>
              <template #default>
                <RoleManager 
                  :roles="roles" 
                  :permissions="permissions"
                  @refresh="loadData"
                />
              </template>
              <template #fallback>
                <div class="text-center py-8">
                  <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mx-auto"></div>
                  <p class="mt-2 text-gray-600 dark:text-gray-400">Loading Role Manager...</p>
                </div>
              </template>
            </Suspense>
          </div>

          <!-- Permissions Panel -->
          <div v-else-if="currentTab === 'Permissions'" class="space-y-4">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">System Permissions</h3>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Permission
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Description
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="perm in allPermissions" :key="perm.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        {{ perm.name }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ perm.description }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, defineAsyncComponent } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useRBAC } from '@/composables/useRBAC';
import { PERMISSIONS } from '@/config/roles';
import { 
  KeyIcon, 
  ShieldCheckIcon,
  UsersIcon
} from '@heroicons/vue/24/outline';
import rbacApi from '@/api/rbac';
import { useToast } from 'vue-toastification';

// Initialize toast
const toast = useToast();

// Data fetching functions
const fetchUsers = async () => {
  try {
    const response = await rbacApi.getUsersWithRoles();
    return response.data.map(user => ({
      ...user,
      isCurrentUser: user.id === useAuthStore().user?.id,
      lastActive: user.last_login_at ? new Date(user.last_login_at) : null
    }));
  } catch (error) {
    console.error('Error fetching users:', error);
    toast.error('Failed to load users');
    return [];
  }
};

const fetchRoles = async () => {
  try {
    const response = await rbacApi.getRoles();
    return response.data;
  } catch (error) {
    console.error('Error fetching roles:', error);
    toast.error('Failed to load roles');
    return [];
  }
};

const fetchPermissions = async () => {
  try {
    const response = await rbacApi.getPermissions();
    return response.data;
  } catch (error) {
    console.error('Error fetching permissions:', error);
    toast.error('Failed to load permissions');
    return [];
  }
};

export default {
  name: 'RBACManagement',
  
  components: {
    UserManager: defineAsyncComponent({
      loader: () => import('@/components/admin/UserManager.vue'),
      loading: { template: '<div>Loading User Manager...</div>' },
      error: { template: '<div>Error loading User Manager</div>' },
      delay: 200,
      timeout: 10000
    }),
    RoleManager: defineAsyncComponent({
      loader: () => import('@/components/admin/RoleManager.vue'),
      loading: { template: '<div>Loading Role Manager...</div>' },
      error: { template: '<div>Error loading Role Manager</div>' },
      delay: 200,
      timeout: 10000
    }),
    KeyIcon,
    ShieldCheckIcon,
    UsersIcon
  },
  
  setup() {
    const _router = useRouter();
    const { hasPermission, permissions: userPermissions } = useRBAC();
    
    // State
    const currentTab = ref('Users');
    const isLoading = ref(true);
    
    const users = ref([]);
    const roles = ref([]);
    const permissions = ref([]);
    
    // Tabs configuration - TEMPORARILY BYPASSING PERMISSION CHECKS FOR TESTING
    const tabs = computed(() => {
      console.log('User permissions:', userPermissions.value);
      // Temporarily show all tabs regardless of permissions
      const availableTabs = [
        { name: 'Users', icon: UsersIcon, permission: PERMISSIONS.VIEW_USERS },
        { name: 'Roles', icon: ShieldCheckIcon, permission: PERMISSIONS.VIEW_ROLES },
        { name: 'Permissions', icon: KeyIcon, permission: PERMISSIONS.VIEW_PERMISSIONS },
      ];
      console.log('Available tabs (bypassed permissions):', availableTabs.map(t => t.name));
      return availableTabs;
    });

    // Load all data
    const loadData = async () => {
      try {
        isLoading.value = true;
        const [usersData, rolesData, permissionsData] = await Promise.all([
          fetchUsers(),
          fetchRoles(),
          fetchPermissions()
        ]);
        
        users.value = usersData;
        roles.value = rolesData;
        permissions.value = permissionsData;
      } catch (error) {
        console.error('Error loading RBAC data:', error);
        toast.error('Failed to load RBAC data');
      } finally {
        isLoading.value = false;
      }
    };

    // Initial data load
    onMounted(async () => {
      await loadData();
      
      // TEMPORARILY DISABLED REDIRECTION FOR TESTING
      console.log('Skipping permission check redirection for testing');
      
      // Set first available tab as active if current tab is not available
      if (tabs.value.length > 0 && !tabs.value.some(tab => tab.name === currentTab.value)) {
        currentTab.value = tabs.value[0].name;
      }
    });

    // Computed
    const allPermissions = computed(() => {
      return Object.entries(PERMISSIONS).map(([key, value]) => ({
        id: value,
        name: key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '),
        description: `Allows ${value.split('_').join(' ').toLowerCase()}`
      }));
    });

    // Methods
    const getRolesWithPermission = (permissionId) => {
      return roles.value.filter(role => role.permissions.includes(permissionId));
    };
    
    const getRoleBadgeClass = (roleId) => {
      switch(roleId) {
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
    
    return {
      // State
      currentTab,
      users,
      roles,
      isLoading,
      tabs,
      
      // Computed
      allPermissions,
      permissions: PERMISSIONS,
      
      // Methods
      loadData,
      getRolesWithPermission,
      getRoleBadgeClass,
      hasPermission
    };
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
