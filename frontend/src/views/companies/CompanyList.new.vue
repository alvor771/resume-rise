<template>
  <div class="company-list">
    <SectionHeader :title="$t('companies.title')" class="mb-6">
      <template #actions>
        <Button :to="{ name: 'companies.create' }" variant="primary" size="md">
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
              @update:modelValue="fetchCompanies"
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
            <tr v-else-if="!companies || companies.length === 0">
              <td :colspan="headers.length" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                {{ $t('common.no_data_available') }}
              </td>
            </tr>
            <tr 
              v-else 
              v-for="company in companies" 
              :key="company.id" 
              class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                    <span v-if="!company.logo" class="text-primary-600 dark:text-primary-300 font-medium">
                      {{ company.name.charAt(0).toUpperCase() }}
                    </span>
                    <img v-else :src="company.logo" :alt="company.name" class="h-10 w-10 rounded-full object-cover" />
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ company.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ company.industry }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 dark:text-white">{{ company.email }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ company.phone }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <BaseBadge :variant="company.status === 'active' ? 'success' : 'danger'">
                  {{ company.status }}
                </BaseBadge>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ company.employees_count }} {{ $t('companies.employees') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(company.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center space-x-2 justify-end">
                  <Button 
                    :to="{ name: 'companies.edit', params: { id: company.id } }" 
                    variant="ghost" 
                    size="sm"
                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-200"
                  >
                    <IconPencil class="h-4 w-4" />
                  </Button>
                  <Button 
                    @click="deleteItem(company)" 
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
  name: 'CompanyList',
  
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
        per_page: 10,
        page: 1,
      },
      headers: [
        { title: this.$t('companies.name'), key: 'name' },
        { title: this.$t('companies.contact'), key: 'contact' },
        { title: this.$t('common.status'), key: 'status' },
        { title: this.$t('companies.employees'), key: 'employees' },
        { title: this.$t('common.created_at'), key: 'created_at' },
        { title: this.$t('common.actions'), key: 'actions' },
      ],
      statusOptions: [
        { text: this.$t('common.active'), value: 'active' },
        { text: this.$t('common.inactive'), value: 'inactive' },
      ],
    };
  },

  computed: {
    ...mapState(useCompanyStore, [
      'companies',
      'loading',
      'total',
    ]),
  },

  created() {
    this.fetchCompanies();
  },

  methods: {
    ...mapActions(useCompanyStore, [
      'fetchCompanies',
      'deleteCompany',
    ]),

    formatDate(date) {
      return date ? format(new Date(date), 'PP') : '-';
    },

    handleSearch() {
      this.filters.page = 1;
      this.fetchCompanies(this.filters);
    },

    handlePageChange(page) {
      this.filters.page = page;
      this.fetchCompanies(this.filters);
    },

    async deleteItem(company) {
      if (confirm(this.$t('common.confirm_delete', { name: company.name }))) {
        await this.deleteCompany(company.id);
        this.fetchCompanies(this.filters);
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
