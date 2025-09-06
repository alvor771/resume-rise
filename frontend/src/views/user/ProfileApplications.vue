<template>
  <div class="space-y-6">
    <!-- Applications Overview -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
        {{ $t('user.profile.my_applications') }}
      </h3>
      
      <!-- Applications List -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.position') }}
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.company') }}
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.status') }}
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.applied') }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="app in applications" :key="app.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ app.position }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  {{ app.type }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">
                  {{ app.company }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  {{ app.location }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="getStatusClass(app.status)"
                >
                  {{ $t('user.profile.' + app.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(app.appliedDate) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Empty State -->
      <div v-if="applications.length === 0" class="text-center py-12">
        <BriefcaseIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ $t('user.profile.no_applications') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('user.profile.get_started_by_adding_application') }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { BriefcaseIcon } from '@heroicons/vue/24/outline';

export default {
  name: 'ProfileApplications',
  components: {
    BriefcaseIcon
  },
  setup() {
    const { t } = useI18n();
    
    const applications = ref([
      {
        id: 1,
        position: 'Senior Frontend Developer',
        company: 'TechCorp',
        location: 'San Francisco, CA',
        type: 'Full-time',
        appliedDate: '2023-07-15',
        status: 'interview'
      },
      {
        id: 2,
        position: 'UI/UX Designer',
        company: 'DesignHub',
        location: 'Remote',
        type: 'Contract',
        appliedDate: '2023-07-10',
        status: 'applied'
      },
      {
        id: 3,
        position: 'Product Manager',
        company: 'ProductLabs',
        location: 'New York, NY',
        type: 'Full-time',
        appliedDate: '2023-07-05',
        status: 'offer'
      }
    ]);
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };
    
    const getStatusClass = (status) => {
      const classes = {
        'applied': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'interview': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'offer': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'rejected': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
      };
      return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    };
    
    return {
      applications,
      formatDate,
      getStatusClass
    };
  }
};
</script>
