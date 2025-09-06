<template>
  <div class="space-y-8">
    <!-- Current Plan -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
          {{ $t('user.subscriptions.current_plan') }}
        </h3>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ $t('user.subscriptions.plan') }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
              {{ currentPlan.name }}
            </dd>
          </div>
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ $t('user.subscriptions.price') }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
              ${{ currentPlan.price }}/{{ $t('user.subscriptions.month') }}
            </dd>
          </div>
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ $t('user.subscriptions.status') }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
              <span :class="statusBadgeClass">
                {{ $t(`user.subscriptions.status_${currentPlan.status}`) }}
              </span>
              <span class="ml-2 text-gray-500 dark:text-gray-400">
                {{ $t('user.subscriptions.renews_on', { date: formatDate(currentPlan.renewalDate) }) }}
              </span>
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Billing History -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
          {{ $t('user.subscriptions.billing_history') }}
        </h3>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.subscriptions.date') }}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.subscriptions.description') }}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.subscriptions.amount') }}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.subscriptions.status') }}
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="invoice in invoices" :key="invoice.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ formatDate(invoice.date) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                  {{ invoice.description }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  ${{ invoice.amount }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="statusBadgeClass">
                    {{ $t(`user.subscriptions.status_${invoice.status}`) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { format } from 'date-fns';

export default {
  name: 'UserSubscriptions',
  setup() {
    useI18n();
    
    const currentPlan = ref({
      id: 'pro_monthly',
      name: 'Pro Plan',
      price: 9.99,
      status: 'active',
      renewalDate: '2023-12-31T23:59:59Z',
    });

    const invoices = ref([
      {
        id: 'inv_1KXy1XKZvE7Hq3QdX0YJkLmN',
        date: '2023-06-01T10:30:00Z',
        description: 'Pro Plan - Monthly',
        amount: 9.99,
        status: 'paid',
      },
      {
        id: 'inv_1KWx1XKZvE7Hq3QdX0YJkLmN',
        date: '2023-05-01T10:30:00Z',
        description: 'Pro Plan - Monthly',
        amount: 9.99,
        status: 'paid',
      },
    ]);

    const statusBadgeClass = computed(() => {
      const status = currentPlan.value.status;
      return {
        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium': true,
        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': status === 'active' || status === 'paid',
        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': status === 'pending',
        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': status === 'canceled' || status === 'failed',
      };
    });

    const formatDate = (dateString) => {
      return format(new Date(dateString), 'MMM d, yyyy');
    };

    return {
      currentPlan,
      invoices,
      statusBadgeClass,
      formatDate,
    };
  },
};
</script>
