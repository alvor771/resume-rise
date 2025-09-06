<template>
  <div class="company-list">
    <SectionHeader :title="$t('companies.title')" class="mb-6">
      <template #actions>
        <BaseButton
          :to="{ name: 'companies.create' }"
          variant="primary"
          size="md"
          class="flex items-center gap-2"
        >
          <IconPlus class="w-5 h-5" />
          {{ $t("common.add") }}
        </BaseButton>
      </template>
    </SectionHeader>

    <BaseCard class="mb-6">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1">
            <SearchInput
              v-model="filters.search"
              :placeholder="$t('common.search_placeholder')"
              class="w-full"
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
    </BaseCard>

    <!-- Debug Info - Set showDebug to true to enable -->
    <div
      v-if="showDebug"
      class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg"
    >
      <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
        Debug Info (only visible in development):
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h4
            class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-1"
          >
            Store State:
          </h4>
          <pre
            class="text-xs text-blue-700 dark:text-blue-300 overflow-x-auto p-2 bg-blue-100 dark:bg-blue-900/30 rounded"
          >
            Loading: {{ loading }}
            Error: {{ error }}
            Companies Count: {{ companies?.length || 0 }}
          </pre>
        </div>
        <div>
          <h4
            class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-1"
          >
            Filters:
          </h4>
          <pre
            class="text-xs text-blue-700 dark:text-blue-300 overflow-x-auto p-2 bg-blue-100 dark:bg-blue-900/30 rounded"
            >{{ JSON.stringify(filters, null, 2) }}
          </pre>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div
      v-if="loading"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
    >
      <CompanyCardSkeleton v-for="i in 8" :key="`skeleton-${i}`" />
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
      <div class="flex items-center">
        <IconExclamationCircle class="w-5 h-5 text-red-500 mr-2" />
        <p class="text-red-700 dark:text-red-300">{{ error }}</p>
      </div>
      <button
        @click="fetchCompanies()"
        class="mt-2 px-3 py-1 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded"
      >
        {{ $t("common.retry") }}
      </button>
    </div>
    <!-- Companies Grid -->
    <div
      v-else-if="companies && companies.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
    >
      <div
        v-for="company in companies"
        :key="company.id"
        class="relative group"
      >
        <div
          class="absolute inset-0 bg-primary-500 rounded-lg shadow-lg transform group-hover:scale-105 transition-transform duration-300 opacity-0 group-hover:opacity-5"
        ></div>
        <div
          class="h-full flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden group/card"
        >
          <div class="p-6 flex-1 flex flex-col">
            <!-- Header with fixed height -->
            <div class="flex items-start justify-between mb-5 min-h-[4.5rem]">
              <div class="flex items-start space-x-4">
                <div
                  class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/50 dark:to-primary-800/30 flex items-center justify-center shadow-sm border border-primary-50 dark:border-primary-800/50 group-hover/card:shadow-md transition-shadow duration-300 relative"
                >
                  <span
                    class="text-2xl font-bold text-primary-600 dark:text-primary-300"
                  >
                    {{ company.name.charAt(0).toUpperCase() }}
                  </span>
                  <div
                    v-if="company.vacancies_count > 0"
                    class="absolute -top-2 -right-2"
                  >
                    <span
                      class="flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-white text-xs font-semibold"
                    >
                      {{
                        company.vacancies_count > 9
                          ? "9+"
                          : company.vacancies_count
                      }}
                    </span>
                  </div>
                </div>
                <div class="pt-1">
                  <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2"
                  >
                    {{ company.name }}
                  </h3>
                  <div class="flex items-center space-x-2 mt-1">
                    <p
                      class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[140px]"
                    >
                      {{ company.email }}
                    </p>
                    <span
                      v-if="company.vacancies_count > 0"
                      class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300"
                    >
                      {{ company.vacancies_count }}
                      {{
                        $t(
                          `vacancy.vacancies${
                            company.vacancies_count === 1 ? "" : "_plural"
                          }`
                        )
                      }}
                    </span>
                  </div>
                </div>
              </div>
              <span
                :class="[
                  'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm',
                  'transition-colors duration-200',
                  company.is_active
                    ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800/50'
                    : 'bg-gray-50 text-gray-600 dark:bg-gray-700/50 dark:text-gray-300 border border-gray-100 dark:border-gray-600/50',
                ]"
              >
                {{
                  company.is_active
                    ? $t("common.active")
                    : $t("common.inactive")
                }}
              </span>
            </div>

            <!-- Contact Info -->
            <div
              class="mt-3 space-y-3 text-sm border-t border-gray-100 dark:border-gray-700 pt-4"
            >
              <div
                class="flex items-center text-gray-600 dark:text-gray-300 group/item"
              >
                <div
                  class="p-1.5 mr-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 group-hover/item:bg-primary-50 dark:group-hover/item:bg-primary-900/20 transition-colors duration-200"
                >
                  <IconMapPin class="h-4 w-4" />
                </div>
                <span class="truncate">{{
                  companyLocation(company) || $t("common.not_specified")
                }}</span>
              </div>
              <div
                v-if="company.phone"
                class="flex items-center text-gray-600 dark:text-gray-300 group/item"
              >
                <div
                  class="p-1.5 mr-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 group-hover/item:bg-primary-50 dark:group-hover/item:bg-primary-900/20 transition-colors duration-200"
                >
                  <IconPhone class="h-4 w-4" />
                </div>
                <a
                  :href="`tel:${company.phone}`"
                  class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 truncate"
                >
                  {{ company.phone }}
                </a>
              </div>
              <div v-if="company.website" class="flex items-center space-x-2">
                <IconGlobeAlt class="w-4 h-4 text-gray-400 flex-shrink-0" />
                <a
                  :href="
                    company.website.startsWith('http')
                      ? company.website
                      : 'https://' + company.website
                  "
                  target="_blank"
                  rel="noopener noreferrer"
                  class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 truncate"
                  @click.stop
                >
                  {{ company.website.replace(/^https?:\/\//, "") }}
                </a>
              </div>
            </div>
          </div>

          <div
            class="bg-gray-50 dark:bg-gray-700/30 px-6 py-3 flex justify-between items-center border-t border-gray-100 dark:border-gray-700/50"
          >
            <div class="text-sm text-gray-500 dark:text-gray-400">
              {{ company.vacancies_count || 0 }}
              {{
                $t(
                  `vacancies.vacancy${
                    (company.vacancies_count || 0) === 1 ? "" : "_plural"
                  }`
                )
              }}
            </div>
            <div class="flex space-x-2">
              <router-link
                :to="{ name: 'companies.view', params: { id: company.id } }"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors"
              >
                <IconEye class="h-4 w-4 mr-1" />
                {{ $t("common.view") }}
              </router-link>
              <BaseButton
                v-if="hasPermission('edit_companies')"
                :to="{ name: 'companies.edit', params: { id: company.id } }"
                variant="ghost"
                size="sm"
                class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400"
              >
                <IconPencil class="h-4 w-4 mr-1" />
                {{ $t("common.edit") }}
              </BaseButton>
            </div>
          </div>
        </div>
      </div>
    </div>

    <EmptyState
      v-else-if="!loading && (!companies || companies.length === 0)"
      :title="$t('companies.no_companies_found')"
      :description="$t('companies.no_companies_found_description')"
      class="mt-12"
    >
      <template #icon>
        <IconBuildingOffice class="h-12 w-12 text-gray-400" />
      </template>
      <Button :to="{ name: 'companies.create' }" variant="primary" class="mt-4">
        <IconPlus class="h-5 w-5 mr-2" />
        {{ $t("companies.add_company") }}
      </Button>
    </EmptyState>

    <!-- Pagination -->
    <div
      v-if="pagination.lastPage > 1"
      class="mt-8 flex items-center justify-between"
    >
      <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ $t("common.showing") }}
        <span class="font-medium">{{
          (pagination.page - 1) * pagination.perPage + 1
        }}</span>
        {{ $t("common.to") }}
        <span class="font-medium">
          {{ Math.min(pagination.page * pagination.perPage, pagination.total) }}
        </span>
        {{ $t("common.of") }}
        <span class="font-medium">{{ pagination.total }}</span>
        {{ $t("common.results") }}
      </div>
      <Pagination
        v-model="pagination.page"
        :total-pages="pagination.lastPage"
        :total-items="pagination.total"
        :per-page="pagination.perPage"
        @update:modelValue="handlePageChange"
      />
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useCompanyStore } from "@/stores/company";
import { useAuthStore } from "@/stores/auth";
import {
  IconPlus,
  IconPencil,
  IconEye,
  IconMapPin,
  IconPhone,
  IconBuildingOffice,
} from "@heroicons/vue/24/outline";
import SearchInput from "@/components/ui/SearchInput.vue";
import BaseCard from "@/components/ui/BaseCard.vue";
import BaseSelect from "@/components/ui/BaseSelect.vue";
import SectionHeader from "@/components/layout/SectionHeader.vue";
import Pagination from "@/components/ui/Pagination.vue";
import EmptyState from "@/components/ui/EmptyState.vue";
import CompanyCardSkeleton from "@/components/skeletons/CompanyCardSkeleton.vue";
import BaseButton from "@/components/ui/Button.vue";

export default {
  name: "CompanyList",

  components: {
    IconPlus,
    IconPencil,
    IconEye,
    IconMapPin,
    IconPhone,
    IconBuildingOffice,
    BaseButton,
    SearchInput,
    BaseCard,
    BaseSelect,
    SectionHeader,
    Pagination,
    EmptyState,
    CompanyCardSkeleton,
  },

  data() {
    return {
      showDebug: false, // Set to true to show debug info
      statusOptions: [
        { text: this.$t("common.all"), value: "all" },
        { text: this.$t("common.active"), value: "active" },
        { text: this.$t("common.inactive"), value: "inactive" },
      ],
      searchTimeout: null,
    };
  },

  computed: {
    ...mapState(useCompanyStore, {
      storeLoading: "loading",
      storeError: "error",
      storePagination: "pagination",
      storeFilters: "filters",
      storeCompanies: "companies",
    }),
    loading() {
      return this.storeLoading;
    },
    error() {
      return this.storeError;
    },
    pagination() {
      return this.storePagination;
    },
    filters: {
      get() {
        return this.storeFilters;
      },
      set(value) {
        this.setFilters(value);
      },
    },
    companies() {
      // Get companies from the store
      const companies = Array.isArray(this.storeCompanies)
        ? this.storeCompanies
        : [];
      console.log("Computed companies:", companies);
      return companies;
    },
  },

  async created() {
    try {
      console.log("Initializing CompanyList...");

      // Reset filters to ensure clean state
      const initialFilters = {
        search: "",
        status: "all",
        ...this.$route.query,
      };

      this.setFilters(initialFilters);
      console.log("Initial filters set:", initialFilters);

      // Fetch companies with the initial filters
      console.log("Fetching companies with filters:", initialFilters);
      await this.fetchCompanies({
        ...initialFilters,
        page: 1,
        per_page: 12,
        force: true, // Force refetch to ensure fresh data
      });

      console.log("Store companies after fetch:", this.storeCompanies);
      console.log("Computed companies:", this.companies);
    } catch (error) {
      console.error("Error initializing CompanyList:", error);
      this.$toast.error(this.$t("common.errors.loading_error"));
    }
  },

  methods: {
    ...mapActions(useCompanyStore, [
      "fetchCompanies",
      "setFilters",
      "setPagination",
    ]),

    hasPermission(permission) {
      const authStore = useAuthStore();
      return authStore.hasPermission(permission);
    },

    companyLocation(company) {
      const location = [];
      if (company.city) location.push(company.city);
      if (company.country) location.push(company.country);
      return location.length > 0
        ? location.join(", ")
        : this.$t("common.notSpecified");
    },

    handleSearch() {
      // Debounce search
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }

      this.searchTimeout = setTimeout(() => {
        this.setFilters({ search: this.filters.search });
        this.fetchCompanies();
      }, 500);
    },

    handlePageChange(page) {
      this.setPagination({ page });
      this.fetchCompanies();
    },
  },
};
</script>

<style scoped>
.company-list {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1.5rem;
}

/* Animation for card hover */
@keyframes float {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-5px);
  }
  100% {
    transform: translateY(0px);
  }
}

.group:hover .group-hover\:animate-float {
  animation: float 1.5s ease-in-out infinite;
}
</style>
