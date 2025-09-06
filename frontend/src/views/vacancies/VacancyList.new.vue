<template>
  <div class="vacancy-list">
    <SectionHeader :title="$t('vacancies.title')" class="mb-6">
      <template #actions>
        <Button :to="{ name: 'vacancies.create' }" variant="primary" size="md">
          <IconPlus class="w-5 h-5" /> {{ $t('common.add') }}
        </Button>
      </template>
    </SectionHeader>

    <BaseCard class="mb-6">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <SearchInput
              v-model="filters.search"
              :placeholder="$t('common.search_placeholder')"
              @update:modelValue="handleSearch"
            />
          </div>
          <div class="w-full md:w-64">
            <BaseSelect
              v-model="filters.status"
              :options="statusOptions"
              :label="$t('common.status')"
              clearable
              @update:modelValue="fetchVacancies"
            />
          </div>
          <div class="w-full md:w-64">
            <BaseSelect
              v-model="filters.company_id"
              :options="companyOptions"
              :label="$t('vacancies.company')"
              clearable
              @update:modelValue="fetchVacancies"
            />
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th 
                v-for="header in headers" 
                :key="header.key" 
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
              >
                {{ header.title }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-if="loading">
              <td :colspan="headers.length" class="px-6 py-4 text-center">
                <div class="flex justify-center">
                  <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
                </div>
              </td>
            </tr>
            <tr v-else-if="!vacancies || vacancies.length === 0">
              <td :colspan="headers.length" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                {{ $t('common.no_data_available') }}
              </td>
            </tr>
            <tr 
              v-else 
              v-for="vacancy in vacancies" 
              :key="vacancy.id" 
              class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                    <span class="text-primary-600 dark:text-primary-300 font-medium">
                      {{ vacancy.title.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ vacancy.title }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ vacancy.company?.name || 'N/A' }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">
                  {{ vacancy.location || $t('common.remote') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  {{ vacancy.type || $t('common.full_time') }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <BaseBadge :variant="getSalaryBadgeVariant(vacancy.salary)">
                  {{ formatSalary(vacancy.salary) }}
                </BaseBadge>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <BaseBadge :variant="vacancy.status === 'active' ? 'success' : 'danger'">
                  {{ $t(`vacancies.status.${vacancy.status}`) }}
                </BaseBadge>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(vacancy.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center space-x-2 justify-end">
                  <Button 
                    :to="{ name: 'vacancies.edit', params: { id: vacancy.id } }" 
                    variant="ghost" 
                    size="sm"
                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-200"
                  >
                    <IconPencil class="h-4 w-4" />
                  </Button>
                  <Button 
                    @click="deleteItem(vacancy)" 
                    variant="ghost" 
                    size="sm"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200"
                  >
                    <IconTrash class="h-4 w-4" />
                  </Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="total > filters.per_page" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        <Pagination
          :current-page="filters.page"
          :total-items="total"
          :per-page="filters.per_page"
          @page-changed="handlePageChange"
        />
      </div>
    </BaseCard>
  </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useVacancyStore } from '@/stores/vacancy';
import { useCompanyStore } from '@/stores/company';
import { format } from 'date-fns';

// Icons
import IconPlus from '@/components/ui/icons/IconPlus.vue';
import IconPencil from '@/components/ui/icons/IconPencil.vue';
import IconTrash from '@/components/ui/icons/IconTrash.vue';

// UI Components
import SectionHeader from '@/components/ui/SectionHeader.vue';
import Button from '@/components/ui/Button.vue';
import SearchInput from '@/components/ui/SearchInput.vue';
import BaseSelect from '@/components/ui/BaseSelect.vue';
import BaseCard from '@/components/ui/BaseCard.vue';
import BaseBadge from '@/components/ui/BaseBadge.vue';
import Pagination from '@/components/ui/Pagination.vue';

export default {
  name: 'VacancyList',
  
  components: {
    SectionHeader,
    Button,
    SearchInput,
    BaseSelect,
    BaseCard,
    BaseBadge,
    Pagination,
    IconPlus,
    IconPencil,
    IconTrash,
  },
  
  data() {
    return {
      filters: {
        search: '',
        status: '',
        company_id: '',
        per_page: 10,
        page: 1,
      },
      headers: [
        { title: this.$t('vacancies.position'), key: 'position' },
        { title: this.$t('vacancies.details'), key: 'details' },
        { title: this.$t('vacancies.salary'), key: 'salary' },
        { title: this.$t('common.status'), key: 'status' },
        { title: this.$t('common.created_at'), key: 'created_at' },
        { title: this.$t('common.actions'), key: 'actions' },
      ],
      statusOptions: [
        { text: this.$t('vacancies.status.active'), value: 'active' },
        { text: this.$t('vacancies.status.draft'), value: 'draft' },
        { text: this.$t('vacancies.status.closed'), value: 'closed' },
      ],
      companyOptions: [],
    };
  },

  computed: {
    ...mapState(useVacancyStore, [
      'vacancies',
      'loading',
      'total',
    ]),
  },

  async created() {
    await this.fetchCompanies();
    this.fetchVacancies();
  },

  methods: {
    ...mapActions(useVacancyStore, [
      'fetchVacancies',
      'deleteVacancy',
    ]),
    ...mapActions(useCompanyStore, [
      'fetchCompanies',
    ]),

    formatDate(date) {
      return date ? format(new Date(date), 'PP') : '-';
    },

    formatSalary(salary) {
      if (!salary) return this.$t('common.negotiable');
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0,
      }).format(salary);
    },

    getSalaryBadgeVariant(salary) {
      if (!salary) return 'default';
      if (salary > 100000) return 'success';
      if (salary > 50000) return 'primary';
      return 'warning';
    },

    handleSearch() {
      this.filters.page = 1;
      this.fetchVacancies(this.filters);
    },

    handlePageChange(page) {
      this.filters.page = page;
      this.fetchVacancies(this.filters);
    },

    async deleteItem(vacancy) {
      if (confirm(this.$t('common.confirm_delete', { name: vacancy.title }))) {
        await this.deleteVacancy(vacancy.id);
        this.fetchVacancies(this.filters);
      }
    },
  },
};
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
