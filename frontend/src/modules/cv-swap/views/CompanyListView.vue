<template>
  <div class="company-list">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Companies</h1>
        <div class="relative">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search companies..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
          >
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div v-if="isLoading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <div v-else-if="error" class="text-center py-12">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
          <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="mt-2 text-lg font-medium text-gray-900">Error loading companies</h3>
        <p class="mt-1 text-sm text-gray-500">{{ error }}</p>
        <button 
          @click="fetchCompanies"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
        >
          Try Again
        </button>
      </div>

      <div v-else>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="company in filteredCompanies" 
            :key="company.id"
            class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-300"
          >
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-white p-1 border border-gray-200">
                  <img 
                    :src="company.logo || '/images/company-placeholder.png'" 
                    :alt="company.name"
                    class="h-full w-full object-contain"
                  >
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">
                    <router-link 
                      :to="{ name: 'CompanyProfile', params: { id: company.id } }"
                      class="hover:text-blue-600"
                    >
                      {{ company.name }}
                    </router-link>
                  </h3>
                  <p class="text-sm text-gray-500">{{ company.industry }}</p>
                </div>
              </div>
              
              <p class="mt-3 text-sm text-gray-500 line-clamp-2">
                {{ company.about || 'No description available.' }}
              </p>
              
              <div class="mt-4 flex flex-wrap gap-2">
                <span 
                  v-for="(tech, index) in company.techStack?.slice(0, 3)" 
                  :key="index"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                  {{ tech }}
                </span>
                <span 
                  v-if="company.techStack?.length > 3"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                >
                  +{{ company.techStack.length - 3 }} more
                </span>
              </div>
              
              <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                  <span v-if="company.openPositions?.length" class="text-green-600 font-medium">
                    {{ company.openPositions.length }} open positions
                  </span>
                  <span v-else class="text-gray-400">
                    No open positions
                  </span>
                </div>
                <router-link 
                  :to="{ name: 'CompanyProfile', params: { id: company.id } }"
                  class="text-sm font-medium text-blue-600 hover:text-blue-500"
                >
                  View details →
                </router-link>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="filteredCompanies.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No companies found</h3>
          <p class="mt-1 text-sm text-gray-500">
            {{ searchQuery ? 'Try adjusting your search or filter to find what you\'re looking for.' : 'There are currently no companies listed.' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useCvSwapStore } from '../store';

export default {
  name: 'CompanyListView',
  
  setup() {
    const _cvSwapStore = useCvSwapStore(); // Prefix with underscore to indicate it's intentionally unused
    const companies = ref([]);
    const isLoading = ref(true);
    const error = ref(null);
    const searchQuery = ref('');
    
    const filteredCompanies = computed(() => {
      if (!searchQuery.value) return companies.value;
      
      const query = searchQuery.value.toLowerCase();
      return companies.value.filter(company => 
        company.name.toLowerCase().includes(query) ||
        company.industry?.toLowerCase().includes(query) ||
        company.about?.toLowerCase().includes(query) ||
        company.techStack?.some(tech => tech.toLowerCase().includes(query))
      );
    });
    
    const fetchCompanies = async () => {
      try {
        isLoading.value = true;
        error.value = null;
        
        // TODO: Replace with actual API call
        // companies.value = await cvSwapStore.fetchCompanies();
        
        // Mock data for now
        companies.value = [
          {
            id: '1',
            name: 'TechCorp',
            industry: 'Information Technology',
            logo: 'https://via.placeholder.com/150',
            about: 'A leading technology company specializing in innovative solutions for modern businesses.',
            techStack: ['JavaScript', 'Vue.js', 'Node.js', 'AWS', 'Docker'],
            openPositions: ['Frontend Developer', 'DevOps Engineer']
          },
          {
            id: '2',
            name: 'DesignHub',
            industry: 'Design & Creative',
            logo: 'https://via.placeholder.com/150',
            about: 'Creative design agency focused on delivering beautiful user experiences.',
            techStack: ['Figma', 'Sketch', 'Adobe XD', 'UI/UX Design'],
            openPositions: ['UI/UX Designer']
          },
          {
            id: '3',
            name: 'DataSystems',
            industry: 'Data Science & Analytics',
            logo: 'https://via.placeholder.com/150',
            about: 'Transforming data into actionable insights for businesses worldwide.',
            techStack: ['Python', 'R', 'SQL', 'Machine Learning', 'Big Data'],
            openPositions: ['Data Scientist', 'Data Engineer']
          }
        ];
        
      } catch (err) {
        console.error('Error fetching companies:', err);
        error.value = 'Failed to load companies. Please try again later.';
      } finally {
        isLoading.value = false;
      }
    };
    
    onMounted(() => {
      fetchCompanies();
    });
    
    return {
      companies,
      filteredCompanies,
      isLoading,
      error,
      searchQuery,
      fetchCompanies
    };
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2; /* Standard property for better compatibility */
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
