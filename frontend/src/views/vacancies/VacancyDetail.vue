<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Back button -->
      <router-link 
        v-if="!loading"
        :to="{ name: route.name === 'vacancies.view.legacy' ? 'vacancies.list' : 'companies.view', params: { id: vacancy?.company?.id } }" 
        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 mb-6"
      >
        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ $t('common.back_to_vacancies') }}
      </router-link>

      <!-- Loading state -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>

      <!-- Vacancy content -->
      <div v-else-if="vacancy" class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
      <!-- Header -->
      <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ vacancy.title }}</h1>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
              <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <BuildingOfficeIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                {{ vacancy.company?.name || 'N/A' }}
              </div>
              <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <MapPinIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                {{ vacancy.location || $t('vacancies.remote') }}
              </div>
              <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <CurrencyDollarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                {{ formatSalary(vacancy.salary) }}
              </div>
            </div>
          </div>
          <div class="mt-4 sm:mt-0">
            <span 
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
              :class="{
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': vacancy.status === 'active',
                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': vacancy.status === 'closed',
                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': vacancy.status === 'draft'
              }"
            >
              {{ $t(`vacancies.status.${vacancy.status}`) }}
            </span>
          </div>
        </div>
      </div>

      <div class="px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main content -->
          <div class="lg:col-span-2">
            <!-- Description -->
            <div class="mb-8">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $t('vacancies.description') }}</h2>
              <div class="prose dark:prose-invert max-w-none" v-html="vacancy.description"></div>
            </div>

            <!-- Requirements -->
            <div class="mb-8" v-if="vacancy.requirements">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $t('vacancies.requirements') }}</h2>
              <div class="prose dark:prose-invert max-w-none" v-html="vacancy.requirements"></div>
            </div>

            <!-- Responsibilities -->
            <div class="mb-8" v-if="vacancy.responsibilities">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $t('vacancies.responsibilities') }}</h2>
              <div class="prose dark:prose-invert max-w-none" v-html="vacancy.responsibilities"></div>
            </div>

            <!-- Benefits -->
            <div v-if="vacancy.benefits && vacancy.benefits.length > 0">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $t('vacancies.benefits') }}</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div 
                  v-for="(benefit, index) in vacancy.benefits" 
                  :key="index"
                  class="flex items-start"
                >
                  <CheckCircleIcon class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                  <span class="text-gray-700 dark:text-gray-300">{{ benefit }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $t('vacancies.job_details') }}</h3>
              
              <div class="space-y-4">
                <!-- Employment Type -->
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $t('vacancies.employment_type') }}</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ formatEmploymentType(vacancy.employment_type) }}
                  </dd>
                </div>

                <!-- Experience -->
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $t('vacancies.experience') }}</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ formatExperience(vacancy.experience) }}
                  </dd>
                </div>

                <!-- Skills -->
                <div v-if="vacancy.skills && vacancy.skills.length > 0">
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ $t('vacancies.skills') }}</dt>
                  <dd class="mt-1">
                    <div class="flex flex-wrap gap-2">
                      <span 
                        v-for="(skill, index) in vacancy.skills" 
                        :key="index"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
                      >
                        {{ skill }}
                      </span>
                    </div>
                  </dd>
                </div>

                <!-- Published At -->
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $t('vacancies.published_at') }}</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ formatDate(vacancy.published_at) }}
                  </dd>
                </div>

                <!-- Expires At -->
                <div v-if="vacancy.expires_at">
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $t('vacancies.expires_at') }}</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ formatDate(vacancy.expires_at) }}
                  </dd>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-600 mt-4">
                  <router-link
                    :to="{
                      name: 'vacancies.edit',
                      params: { 
                        id: vacancy.id,
                        companySlug: vacancy.company?.slug || 'company',
                        vacancySlug: vacancy.slug || 'vacancy'
                      }
                    }"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ $t('common.edit') }}
                  </router-link>
                  
                  <button
                    type="button"
                    @click="copyVacancyLink"
                    class="mt-2 w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600"
                  >
                    {{ $t('common.copy_link') }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Company Info -->
            <div v-if="vacancy.company" class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
              <div class="p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <img 
                      v-if="vacancy.company.logo" 
                      :src="vacancy.company.logo" 
                      :alt="vacancy.company.name"
                      class="h-16 w-16 rounded-full object-cover"
                    >
                    <div v-else class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <BuildingOffice2Icon class="h-8 w-8 text-gray-400" />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                      <router-link 
                        :to="{ name: 'companies.view', params: { id: vacancy.company.id } }"
                        class="hover:text-indigo-600 dark:hover:text-indigo-400"
                      >
                        {{ vacancy.company.name }}
                      </router-link>
                    </h3>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      {{ vacancy.company.industry }}
                    </div>
                  </div>
                </div>
                <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                  {{ vacancy.company.description }}
                </div>
                <div class="mt-4">
                  <router-link
                    :to="{ name: 'companies.view', params: { id: vacancy.company.id } }"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                  >
                    {{ $t('common.view_company') }}
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import {
  BuildingOfficeIcon,
  MapPinIcon,
  CurrencyDollarIcon,
  CheckCircleIcon,
  BuildingOffice2Icon,
} from '@heroicons/vue/24/outline';
import { useToast } from 'vue-toastification';
import api from '@/services/api';

export default {
  name: 'VacancyDetail',
  
  components: {
    BuildingOfficeIcon,
    MapPinIcon,
    CurrencyDollarIcon,
    CheckCircleIcon,
    BuildingOffice2Icon,
  },

  setup() {
    const route = useRoute();
    const router = useRouter();
    const { t } = useI18n();
    const toast = useToast();
    
    const vacancy = ref(null);
    const loading = ref(true);

    const fetchVacancy = async () => {
      try {
        const vacancyId = route.params.id.split('-').pop(); // Get ID from URL if it's in format 'slug-id'
        const response = await api.get(`/api/vacancies/${vacancyId}`);
        vacancy.value = response.data.data;
        
        // Update URL to new format if accessed via legacy URL
        if (route.name === 'vacancies.view.legacy' && vacancy.value) {
          const newPath = `/admin/${vacancy.value.company?.slug || 'company'}/vacancies/${vacancy.value.slug || 'vacancy'}-${vacancy.value.id}`;
          window.history.replaceState({}, '', newPath);
        }
      } catch (error) {
        console.error('Error fetching vacancy:', error);
        toast.error(t('errors.failed_to_load_vacancy'));
        router.push({ name: 'vacancies.list' });
      } finally {
        loading.value = false;
      }
    };

    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      return new Date(dateString).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    };

    const formatSalary = (salary) => {
      if (!salary) return t('vacancies.salary_negotiable');
      
      if (salary.min && salary.max) {
        return `${salary.min.toLocaleString()} - ${salary.max.toLocaleString()} ${salary.currency || ''}`.trim();
      } else if (salary.min) {
        return `${t('vacancies.from')} ${salary.min.toLocaleString()} ${salary.currency || ''}`.trim();
      } else if (salary.max) {
        return `${t('vacancies.up_to')} ${salary.max.toLocaleString()} ${salary.currency || ''}`.trim();
      }
      
      return t('vacancies.salary_negotiable');
    };

    const formatEmploymentType = (type) => {
      if (!type) return 'N/A';
      return t(`vacancies.employment_types.${type}`);
    };

    const formatExperience = (experience) => {
      if (!experience) return 'N/A';
      return t(`vacancies.experience_levels.${experience}`);
    };

    const copyVacancyLink = () => {
      const url = window.location.href;
      navigator.clipboard.writeText(url).then(() => {
        toast.success(t('common.link_copied'));
      }).catch(() => {
        toast.error(t('errors.failed_to_copy_link'));
      });
    };

    onMounted(() => {
      fetchVacancy();
    });

    return {
      vacancy,
      loading,
      formatDate,
      formatSalary,
      formatEmploymentType,
      formatExperience,
      copyVacancyLink
    };
  }
};
</script>

<style scoped>
.prose {
  @apply text-gray-700 dark:text-gray-300;
}

.prose :where(h1, h2, h3, h4, h5, h6) {
  @apply text-gray-900 dark:text-white;
}

.prose :where(a) {
  @apply text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300;
}

.prose :where(ul > li) {
  @apply list-disc ml-5;
}

.prose :where(ol > li) {
  @apply list-decimal ml-5;
}
</style>
