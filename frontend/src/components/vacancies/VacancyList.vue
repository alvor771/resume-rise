<template>
  <div class="vacancy-list">
    <!-- Search and Filters -->
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <!-- View Toggle -->
      <div class="flex items-center space-x-2">
        <button
          @click="viewMode = 'grid'"
          :class="[
            'p-2 rounded-md',
            viewMode === 'grid' 
              ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/50 dark:text-primary-200' 
              : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700/50'
          ]"
          :title="$t('vacancies.view_grid')"
        > GRID
          <IconSquares2X2 class="h-5 w-5" />
        </button>
        <button
          @click="viewMode = 'list'"
          :class="[
            'p-2 rounded-md',
            viewMode === 'list' 
              ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/50 dark:text-primary-200' 
              : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700/50'
          ]"
          :title="$t('vacancies.view_list')"
        > LIST
          <IconListBullet class="h-5 w-5" />
        </button>
      </div>
      
      <div class="flex-1 flex items-center justify-end space-x-4">
        <SearchInput
          v-model="filters.search"
          :placeholder="$t('common.search')"
          class="w-full max-w-md"
          @update:modelValue="onSearch"
        />
        
        <BaseSelect
          v-model="filters.status"
          :options="statusOptions"
          :placeholder="$t('common.status')"
          class="w-40"
          clearable
          @update:modelValue="onStatusChange"
        />
        
        <BaseButton
          v-if="hasPermission('vacancies.create')"
          :to="{ name: 'vacancies.create', query: { company_id: companyId } }"
          variant="primary"
          size="sm"
          class="whitespace-nowrap"
        >
          <IconPlus class="h-4 w-4 mr-1.5" />
          {{ $t('vacancies.create') }}
        </BaseButton>
        
        <BaseButton
          variant="outline"
          size="sm"
          @click="resetFilters"
          class="whitespace-nowrap"
        >
          <IconX class="h-4 w-4 mr-1" />
          {{ $t('common.reset') }}
        </BaseButton>
      </div>
    </div>
    
    <!-- Empty State -->
    <EmptyState
      v-if="!loading && vacancies && vacancies.length === 0"
      :title="$t('vacancies.no_vacancies_found')"
      :description="$t('vacancies.no_vacancies_description')"
    >
      <template #actions>
        <BaseButton
          v-if="hasPermission('create_vacancy')"
          :to="{ name: 'vacancies.create', query: { company_id: props.companyId } }"
          variant="primary"
        >
          <IconPlus class="h-5 w-5 mr-1.5" />
          {{ $t('vacancies.createVacancy') }}
        </BaseButton>
      </template>
    </EmptyState>
    
    <!-- Loading State -->
    <div v-else-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg animate-pulse">
        <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-3"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-3/4 mb-2"></div>
        <div class="h-4 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
      </div>
    </div>
    
    <!-- Pagination Top -->
    <div v-if="vacancies && vacancies.length > 0" class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4">
      <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ $t('common.showing') }} 
        <span class="font-medium">{{ (pagination.currentPage - 1) * pagination.perPage + 1 }}</span>
        {{ $t('common.to') }}
        <span class="font-medium">
          {{ Math.min(pagination.currentPage * pagination.perPage, pagination.total) }}
        </span>
        {{ $t('common.of') }}
        <span class="font-medium">{{ pagination.total }}</span>
        {{ $t('common.results') }}
      </div>
      
      <div class="flex items-center space-x-2">
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $t('common.per_page') }}:</span>
        <BaseSelect
          v-model="filters.per_page"
          :options="perPageOptions"
          class="w-20"
          size="sm"
          @update:modelValue="onPerPageChange"
        />
      </div>
    </div>

    <!-- Vacancies List -->
    <div v-if="vacancies && vacancies.length > 0" :class="viewMode === 'grid' ? 'grid gap-6 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3' : 'space-y-4'">
      <div 
        v-for="vacancy in vacancies" 
        :key="vacancy.id"
        :class="vacancyCardClass"
      >
        <!-- Header with status -->
        <div class="p-4 sm:px-6 sm:pt-6 sm:pb-4 w-full" :class="viewMode === 'list' ? 'sm:w-1/3 border-r border-gray-100 dark:border-gray-700' : 'border-b border-gray-100 dark:border-gray-700'">
          <div class="flex flex-col h-full">
            <div class="flex-1">
              <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200 mb-2">
                <router-link 
                  :to="{ 
                    name: 'vacancies.view',
                    params: { 
                      id: vacancy.id,
                      companySlug: vacancy.company?.slug || 'company',
                      vacancySlug: vacancy.slug || vacancy.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')
                    },
                    query: {}
                  }"
                  class="hover:underline"
                >
                  {{ vacancy.title }}
                </router-link>
              </h3>
              
              <!-- Company info -->
              <div v-if="vacancy.company" class="mt-3">
                <div class="flex items-center space-x-3">
                  <div v-if="vacancy.company.logo" class="flex-shrink-0">
                    <img 
                      :src="vacancy.company.logo" 
                      :alt="vacancy.company.name"
                      class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600"
                    />
                  </div>
                  <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ vacancy.company.name }}
                    </h4>
                    <div v-if="vacancy.company.industry" class="text-xs text-gray-500 dark:text-gray-400">
                      {{ vacancy.company.industry }}
                    </div>
                    <div v-if="vacancy.company.location" class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {{ vacancy.company.location }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Status badge -->
            <span 
              class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium leading-4 whitespace-nowrap mt-3"
              :class="getStatusBadgeClass(vacancy.status)"
            >
              {{ getStatusText(vacancy.status) }}
            </span>
          </div>
        </div>
        
        <!-- Main content -->
        <div :class="cardContentClass">
          <div class="flex flex-col h-full">
            <!-- Salary and type - Row layout in list view -->
            <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    {{ $t('vacancies.salary') }}
                  </p>
                  <p class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ formatSalary(vacancy) }}
                  </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    {{ $t('vacancies.type') }}
                  </p>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ vacancy.type || $t('vacancies.type_not_specified') }}
                  </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    {{ $t('vacancies.location') }}
                  </p>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ vacancy.location || $t('common.remote') }}
                  </p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    {{ $t('vacancies.experience') }}
                  </p>
                  <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ vacancy.experience || $t('vacancies.experience_not_specified') }}
                  </p>
                </div>
              </div>
            </div>
            
            <!-- Tags and actions row -->
            <div class="mt-auto">
              <!-- Tags -->
              <div v-if="vacancy.tags && vacancy.tags.length > 0" class="mb-3">
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="tag in vacancy.tags.slice(0, 3)" 
                    :key="tag"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200"
                  >
                    {{ tag }}
                  </span>
                  <span 
                    v-if="vacancy.tags.length > 3" 
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400"
                  >
                    +{{ vacancy.tags.length - 3 }}
                  </span>
                </div>
              </div>
              
              <!-- Footer -->
              <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <IconCalendar class="h-4 w-4 mr-1.5 flex-shrink-0" />
                    <span>{{ formatDate(vacancy.created_at) }}</span>
                  </div>
                  
                  <div class="flex items-center space-x-4">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                      <IconEye class="h-4 w-4 mr-1.5 flex-shrink-0" />
                      <span>{{ vacancy.views || 0 }}</span>
                    </div>
                    
                    <router-link 
                      v-if="hasPermission('update_vacancy')" 
                      :to="{ name: 'vacancies.edit', params: { id: vacancy.id } }"
                      class="p-1.5 text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200"
                      :title="$t('common.edit')"
                    >
                      <IconPencil class="h-4 w-4" />
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Pagination -->
    <div v-if="pagination && pagination.total > pagination.perPage" class="mt-6 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
      <Pagination
        :current-page="pagination.currentPage"
        :total-items="pagination.total"
        :per-page="pagination.perPage"
        @page-changed="onPageChange"
      />
    </div>
  </div>
</template>

<script>
import { ref, watch, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';
import { useVacancyStore } from '@/stores/vacancy';
import { useAuthStore } from '@/stores/auth';
import { 
  IconPlus, 
  IconX, 
  IconPencil,
  IconSquares2X2,
  IconListBullet 
} from '@heroicons/vue/24/outline';
import SearchInput from '@/components/ui/SearchInput.vue';
import BaseButton from '@/components/ui/Button.vue';
import BaseSelect from '@/components/ui/BaseSelect.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import Pagination from '@/components/ui/Pagination.vue';

export default {
  name: 'VacancyList',
  
  components: {
    IconPlus,
    IconX,
    IconPencil,
    IconSquares2X2,
    IconListBullet,
    SearchInput,
    BaseButton,
    BaseSelect,
    EmptyState,
    Pagination
  },
  
  props: {
    companyId: {
      type: [String, Number],
      required: true
    }
  },
  
  setup(props) {
    const { t } = useI18n();
    const authStore = useAuthStore();
    const vacancyStore = useVacancyStore();
    const route = useRoute();
    // Router is kept in case it's needed for future functionality
    const _router = useRouter();

    // View mode state (grid or list)
    const viewMode = ref(localStorage.getItem('vacancyViewMode') || 'grid');
    
    // Watch for view mode changes
    watch(viewMode, (newMode) => {
      localStorage.setItem('vacancyViewMode', newMode);
    });

    // Use store's loading state directly
    const loading = computed(() => vacancyStore.loading);
    const vacancies = computed(() => vacancyStore.vacancies);
    const filters = ref({
      search: route.query.search || '',
      status: route.query.status || 'all',
      per_page: route.query.per_page ? parseInt(route.query.per_page) : 9,
    });
    
    const pagination = ref({
      currentPage: 1,
      perPage: 9,
      lastPage: 1,
      total: 0
    });
    
    // Computed class for the vacancy card based on view mode
    const vacancyCardClass = computed(() => ({
      'flex flex-col h-full': viewMode.value === 'grid',
      'flex flex-col md:flex-row': viewMode.value === 'list',
      'bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden': true
    }));

    // Computed class for the card content based on view mode
    const cardContentClass = computed(() => ({
      'flex-1 p-4 sm:p-6': true,
      'md:border-l': viewMode.value === 'list',
      'border-t': viewMode.value === 'grid',
      'border-gray-100 dark:border-gray-700': true,
      'flex flex-col': viewMode.value === 'list'
    }));

    const statusOptions = [
      { value: 'draft', label: t('vacancies.statuses.draft') },
      { value: 'published', label: t('vacancies.statuses.published') },
      { value: 'closed', label: t('vacancies.statuses.closed') },
      { value: 'archived', label: t('vacancies.statuses.archived') }
    ];
    
    const perPageOptions = [
      { value: 9, label: '9' },
      { value: 24, label: '24' },
      { value: 48, label: '48' },
      { value: 72, label: '72' },
      { value: 96, label: '96' },
      { value: 120, label: '120' }
    ];
    
    const hasPermission = (permission) => {
      return authStore.hasPermission(permission);
    };
    
    const formatSalary = (vacancy) => {
      if (!vacancy.salary_min && !vacancy.salary_max) {
        return t('common.negotiable');
      }
      
      const salaryMin = parseInt(vacancy.salary_min);
      const salaryMax = parseInt(vacancy.salary_max);
      
      if (salaryMin && salaryMax) {
        return `${salaryMin.toLocaleString()} - ${salaryMax.toLocaleString()} ${vacancy.currency || ''}`.trim();
      }
      
      return salaryMin 
        ? `${t('common.from')} ${salaryMin.toLocaleString()} ${vacancy.currency || ''}`.trim()
        : `${t('common.up_to')} ${salaryMax.toLocaleString()} ${vacancy.currency || ''}`.trim();
    };
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString(undefined, { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
      });
    };
    
    const getStatusBadgeClass = (status) => {
      const statusClasses = {
        draft: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        published: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        closed: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        archived: 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300'
      };
      
      return statusClasses[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300';
    };
    
    const getStatusText = (status) => {
      return t(`vacancies.statuses.${status}`) || status;
    };
    
    const resetFilters = () => {
      filters.value = {
        search: '',
        status: '',
        page: 1,
        per_page: 9
      };
      
      fetchVacancies();
    };
    
    const onPageChange = (page) => {
      filters.value.page = page;
      // Scroll to top when changing pages
      window.scrollTo({ top: 0, behavior: 'smooth' });
      fetchVacancies();
    };
    
    const onPerPageChange = () => {
      // Reset to first page when changing items per page
      filters.value.page = 1;
      fetchVacancies();
    };
    
    const fetchVacancies = async () => {
      try {
        const params = {
          ...filters.value,
          company_id: props.companyId,
          page: filters.value.page || 1,
          per_page: filters.value.per_page || 9,
          with: 'company', // Ensure we're getting company data
          paginate: true   // Ensure pagination is enabled
        };
        
        await vacancyStore.fetchVacancies(params);
        
        // Ensure pagination data is properly set
        if (vacancyStore.pagination) {
          pagination.value = {
            currentPage: vacancyStore.pagination.page || 1,
            perPage: vacancyStore.pagination.perPage || 9,
            total: vacancyStore.pagination.total || 0,
            lastPage: vacancyStore.pagination.lastPage || 1
          };
        }
      } catch (error) {
        console.error('Error fetching vacancies:', error);
      } finally {
        loading.value = false;
      }
    };
    
    // Initialize component
    onMounted(() => {
      fetchVacancies();
    });
    
    // Watch for companyId changes
    watch(() => props.companyId, (newCompanyId) => {
      if (newCompanyId) {
        fetchVacancies();
      }
    });
    
    return {
      props,
      loading,
      vacancies,
      filters,
      pagination,
      statusOptions,
      viewMode,
      vacancyCardClass,
      cardContentClass,
      hasPermission,
      formatSalary,
      formatDate,
      getStatusBadgeClass,
      getStatusText,
      resetFilters,
      onPageChange,
      onPerPageChange,
      fetchVacancies,
      perPageOptions
    };
  }
};
</script>
