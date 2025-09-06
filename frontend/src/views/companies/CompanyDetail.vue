<template>
  <div class="company-detail">
    <div class="flex items-center justify-between mb-6">
      <router-link 
        :to="{ name: 'companies.list' }"
        class="inline-flex items-center text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 transition-colors"
      >
        <IconArrowLeft class="h-5 w-5 mr-2" />
        {{ $t('common.back_to_list') }}
      </router-link>
      
      <div class="flex space-x-3">
        <BaseButton
          :to="{ name: 'companies.edit', params: { id: company.id } }"
          variant="outline"
          size="sm"
        >
          <IconPencil class="h-4 w-4 mr-1" />
          {{ $t('common.edit') }}
        </BaseButton>
      </div>
    </div>
    
    <BaseCard class="mb-6">
      <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
          <!-- Company Logo/Initial -->
          <div class="flex-shrink-0 mb-4 md:mb-0">
            <div v-if="loading" class="h-20 w-20 rounded-xl bg-gray-200 dark:bg-gray-700 animate-pulse"></div>
            <div v-else class="h-20 w-20 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/50 dark:to-primary-800/30 flex items-center justify-center shadow-sm border border-primary-50 dark:border-primary-800/50">
              <span class="text-3xl font-bold text-primary-600 dark:text-primary-300">
                {{ company?.name?.charAt(0)?.toUpperCase() || '?' }}
              </span>
            </div>
          </div>
          
          <!-- Company Details -->
          <div class="flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div>
                <h1 v-if="loading" class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-48 animate-pulse"></h1>
                <h1 v-else class="text-2xl font-bold text-gray-900 dark:text-white">{{ company?.name || '' }}</h1>
                <span 
                  :class="[
                    'inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold mt-1',
                    company.is_active 
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                      : 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300'
                  ]"
                >
                  {{ company.is_active ? $t('common.active') : $t('common.inactive') }}
                </span>
              </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Contact Information -->
              <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                  {{ $t('common.contact_information') }}
                </h3>
                <dl class="space-y-3">
                  <div v-if="company.email" class="flex items-start">
                    <dt class="w-24 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('common.email') }}
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                      <a :href="`mailto:${company.email}`" class="hover:text-primary-600 dark:hover:text-primary-400">
                        {{ company.email }}
                      </a>
                    </dd>
                  </div>
                  
                  <div v-if="company.phone" class="flex items-start">
                    <dt class="w-24 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('common.phone') }}
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                      <a :href="`tel:${company.phone}`" class="hover:text-primary-600 dark:hover:text-primary-400">
                        {{ company.phone }}
                      </a>
                    </dd>
                  </div>
                  
                  <div v-if="company.website" class="flex items-start">
                    <dt class="w-24 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('common.website') }}
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                      <a 
                        :href="company.website.startsWith('http') ? company.website : 'https://' + company.website"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="hover:text-primary-600 dark:hover:text-primary-400"
                      >
                        {{ company.website.replace(/^https?:\/\//, '') }}
                      </a>
                    </dd>
                  </div>
                </dl>
              </div>
              
              <!-- Address Information -->
              <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                  {{ $t('common.address') }}
                </h3>
                <div class="space-y-3">
                  <div v-if="company.address" class="flex items-start">
                    <dt class="w-24 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('common.address') }}
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                      {{ company.address }}
                    </dd>
                  </div>
                  
                  <div v-if="company.city || company.country" class="flex items-start">
                    <dt class="w-24 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('common.location') }}
                    </dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                      {{ companyLocation(company) }}
                    </dd>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Description -->
            <div v-if="company.description" class="mt-6">
              <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                {{ $t('common.about') }}
              </h3>
              <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-300">
                {{ company.description }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </BaseCard>
    
    <!-- Vacancies Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $t('vacancies.title') }}
        </h2>
        <router-link 
          :to="{ name: 'vacancies.create', query: { company_id: company.id } }"
          class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300"
        >
          {{ $t('vacancies.createVacancy') }}
        </router-link>
      </div>
      
      <VacancyList :company-id="company.id" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useCompanyStore } from '@/stores/company';
import { IconArrowLeft, IconPencil } from '@heroicons/vue/24/outline';
import BaseCard from '@/components/ui/BaseCard.vue';
import BaseButton from '@/components/ui/Button.vue';
import VacancyList from '@/components/vacancies/VacancyList.vue';

export default {
  name: 'CompanyDetail',
  
  components: {
    IconArrowLeft,
    IconPencil,
    BaseCard,
    BaseButton,
    VacancyList
  },
  
  setup() {
    const route = useRoute();
    const { t } = useI18n();
    const companyStore = useCompanyStore();
    const loading = ref(true);
    const company = ref({
      id: null,
      name: '',
      email: '',
      phone: '',
      website: '',
      description: '',
      address: '',
      city: '',
      country: '',
      is_active: true,
      vacancies_count: 0
    });
    
    const companyLocation = (company) => {
      const location = [];
      if (company.city) location.push(company.city);
      if (company.country) location.push(company.country);
      return location.length > 0 ? location.join(', ') : t('common.not_specified');
    };
    
    const fetchCompany = async () => {
      try {
        loading.value = true;
        const companyData = await companyStore.fetchCompany(route.params.id);
        company.value = companyData;
      } catch (error) {
        console.error('Error fetching company:', error);
      } finally {
        loading.value = false;
      }
    };
    
    onMounted(() => {
      fetchCompany();
    });
    
    return {
      company,
      loading,
      companyLocation
    };
  }
};
</script>
