<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Activity Log</h1>
        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
          Track and monitor all system activities
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <div class="flex space-x-3">
          <select
            v-model="actionFilter"
            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white"
          >
            <option value="all">All Actions</option>
            <option v-for="action in actionTypes" :key="action" :value="action">
              {{ formatActionType(action) }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="mt-8 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Details</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="log in filteredLogs" :key="log.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="getActionBadgeClass(log.action)"
                >
                  {{ formatActionType(log.action) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full" :src="log.user.avatar" :alt="log.user.name">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ log.user.name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ log.user.role }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900 dark:text-white">{{ log.details }}</div>
                <div v-if="log.ipAddress" class="text-sm text-gray-500 dark:text-gray-400">{{ log.ipAddress }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ formatDateTime(log.timestamp) }}
                <div class="text-xs text-gray-400">{{ timeAgo(log.timestamp) }}</div>
              </td>
            </tr>
            <tr v-if="filteredLogs.length === 0">
              <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                No activity logs found
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  name: 'ActivityLogView',
  
  setup() {
    // Mock data - replace with API calls in production
    const mockLogs = [
      {
        id: '1',
        action: 'user_login',
        user: {
          id: '1',
          name: 'John Doe',
          email: 'john@example.com',
          role: 'admin',
          avatar: 'https://randomuser.me/api/portraits/men/1.jpg'
        },
        details: 'User logged in successfully',
        ipAddress: '192.168.1.1',
        timestamp: new Date(Date.now() - 5 * 60 * 1000).toISOString()
      },
      {
        id: '2',
        action: 'resume_create',
        user: {
          id: '2',
          name: 'Jane Smith',
          email: 'jane@example.com',
          role: 'user',
          avatar: 'https://randomuser.me/api/portraits/women/2.jpg'
        },
        details: 'Created a new resume: Senior Developer',
        ipAddress: '203.0.113.42',
        timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString()
      },
      {
        id: '3',
        action: 'template_update',
        user: {
          id: '1',
          name: 'John Doe',
          email: 'john@example.com',
          role: 'admin',
          avatar: 'https://randomuser.me/api/portraits/men/1.jpg'
        },
        details: 'Updated template: Professional',
        ipAddress: '192.168.1.1',
        timestamp: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString()
      },
      {
        id: '4',
        action: 'user_register',
        user: {
          id: '3',
          name: 'Bob Johnson',
          email: 'bob@example.com',
          role: 'user',
          avatar: 'https://randomuser.me/api/portraits/men/3.jpg'
        },
        details: 'New user registered',
        ipAddress: '198.51.100.3',
        timestamp: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString()
      }
    ];

    // State
    const logs = ref([...mockLogs]);
    const actionFilter = ref('all');

    // Computed properties
    const actionTypes = computed(() => {
      const types = new Set();
      logs.value.forEach(log => types.add(log.action));
      return Array.from(types).sort();
    });

    const filteredLogs = computed(() => {
      return logs.value.filter(log => 
        actionFilter.value === 'all' || log.action === actionFilter.value
      );
    });

    // Methods
    const formatActionType = (action) => {
      return action
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    };

    const getActionBadgeClass = (action) => {
      const classes = {
        user_login: 'bg-green-100 text-green-800',
        user_register: 'bg-blue-100 text-blue-800',
        user_update: 'bg-yellow-100 text-yellow-800',
        resume_create: 'bg-indigo-100 text-indigo-800',
        resume_update: 'bg-indigo-100 text-indigo-800',
        template_create: 'bg-teal-100 text-teal-800',
        template_update: 'bg-teal-100 text-teal-800'
      };
      
      return classes[action] || 'bg-gray-100 text-gray-800';
    };

    const formatDateTime = (dateString) => {
      const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      return new Date(dateString).toLocaleString(undefined, options);
    };

    const timeAgo = (dateString) => {
      const date = new Date(dateString);
      const now = new Date();
      const seconds = Math.floor((now - date) / 1000);
      
      const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
        second: 1
      };
      
      for (const [unit, secondsInUnit] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / secondsInUnit);
        if (interval >= 1) {
          return interval === 1 
            ? `${interval} ${unit} ago` 
            : `${interval} ${unit}s ago`;
        }
      }
      
      return 'Just now';
    };

    return {
      actionFilter,
      actionTypes,
      filteredLogs,
      formatActionType,
      getActionBadgeClass,
      formatDateTime,
      timeAgo
    };
  }
};
</script>
