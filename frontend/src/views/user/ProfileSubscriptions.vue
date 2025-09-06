<template>
  <div class="space-y-6">
    <!-- Current Plan -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
        {{ $t('user.profile.current_plan') }}
      </h3>
      
      <div class="bg-indigo-50 dark:bg-gray-700 rounded-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div class="flex-1">
            <h4 class="text-lg font-medium text-indigo-800 dark:text-indigo-200">
              {{ currentPlan.name }}
            </h4>
            <p class="mt-1 text-sm text-indigo-700 dark:text-indigo-300">
              {{ currentPlan.description }}
            </p>
            <div class="mt-2">
              <span class="text-2xl font-bold text-gray-900 dark:text-white">
                ${{ currentPlan.price }}
              </span>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                /{{ $t('common.' + currentPlan.billing_period) }}
              </span>
            </div>
          </div>
          <div class="mt-4 md:mt-0">
            <span 
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
              :class="{
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': currentPlan.is_active,
                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': !currentPlan.is_active
              }"
            >
              <CheckCircleIcon v-if="currentPlan.is_active" class="h-4 w-4 mr-1" />
              <ClockIcon v-else class="h-4 w-4 mr-1" />
              {{ currentPlan.is_active ? $t('user.profile.active') : $t('user.profile.pending') }}
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Billing History -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
        {{ $t('user.profile.billing_history') }}
      </h3>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.date') }}
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.description') }}
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('user.profile.amount') }}
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                {{ $t('common.status') }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(invoice.date) }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                {{ invoice.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                ${{ invoice.amount.toFixed(2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': invoice.status === 'paid',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': invoice.status === 'pending',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': invoice.status === 'failed'
                  }"
                >
                  {{ $t('user.profile.' + invoice_status) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { 
  CheckCircleIcon,
  ClockIcon
} from '@heroicons/vue/24/outline';

export default {
  name: 'ProfileSubscriptions',
  components: {
    CheckCircleIcon,
    ClockIcon
  },
  setup() {
    const { t } = useI18n();
    
    const currentPlan = ref({
      name: 'Premium',
      description: 'Full access to all features',
      price: '9.99',
      billing_period: 'month',
      is_active: true
    });
    
    const invoices = ref([
      {
        id: 1,
        date: '2023-07-01',
        description: 'Premium Subscription',
        amount: 9.99,
        status: 'paid'
      },
      {
        id: 2,
        date: '2023-06-01',
        description: 'Premium Subscription',
        amount: 9.99,
        status: 'paid'
      }
    ]);
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString();
    };
    
    return {
      currentPlan,
      invoices,
      formatDate
    };
  }
};
</script>
