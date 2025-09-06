<template>
  <div class="company-profile">
    <div v-if="isLoading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
    </div>
    
    <div v-else-if="error" class="text-center py-12">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h3 class="mt-2 text-lg font-medium text-gray-900">Error loading profile</h3>
      <p class="mt-1 text-sm text-gray-500">{{ error }}</p>
      <button 
        @click="fetchCompany"
        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
      >
        Try Again
      </button>
    </div>
    
    <div v-else-if="company" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <!-- Header -->
      <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-16 w-16 rounded-full bg-white p-1 shadow-md">
            <img 
              :src="company.logo || '/images/company-placeholder.png'" 
              :alt="company.name"
              class="h-full w-full object-contain"
            >
          </div>
          <div class="ml-4">
            <h1 class="text-2xl font-bold">{{ company.name }}</h1>
            <p class="mt-1 text-blue-100">{{ company.tagline || 'No tagline provided' }}</p>
          </div>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="px-4 py-5 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column -->
          <div class="lg:col-span-2 space-y-6">
            <!-- About Section -->
            <div>
              <h2 class="text-lg font-medium text-gray-900">About</h2>
              <p class="mt-2 text-gray-600">{{ company.about || 'No company description provided.' }}</p>
            </div>
            
            <!-- Tech Stack -->
            <div v-if="company.techStack?.length">
              <h2 class="text-lg font-medium text-gray-900">Tech Stack</h2>
              <div class="mt-2 flex flex-wrap gap-2">
                <span 
                  v-for="(tech, index) in company.techStack" 
                  :key="index"
                  class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full"
                >
                  {{ tech }}
                </span>
              </div>
            </div>
          </div>
          
          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Company Details -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="font-medium text-gray-900">Company Details</h3>
              <dl class="mt-2 space-y-3">
                <div v-if="company.industry" class="flex items-start">
                  <dt class="text-sm text-gray-500 w-24">Industry</dt>
                  <dd class="text-sm text-gray-900">{{ company.industry }}</dd>
                </div>
                <div v-if="company.size" class="flex items-start">
                  <dt class="text-sm text-gray-500 w-24">Size</dt>
                  <dd class="text-sm text-gray-900">{{ company.size }} employees</dd>
                </div>
                <div v-if="company.founded" class="flex items-start">
                  <dt class="text-sm text-gray-500 w-24">Founded</dt>
                  <dd class="text-sm text-gray-900">{{ company.founded }}</dd>
                </div>
                <div v-if="company.website" class="flex items-start">
                  <dt class="text-sm text-gray-500 w-24">Website</dt>
                  <dd class="text-sm">
                    <a :href="company.website" target="_blank" class="text-blue-600 hover:underline">
                      {{ company.website }}
                    </a>
                  </dd>
                </div>
              </dl>
            </div>
            
            <!-- Contact Button -->
            <button 
              v-if="!isCurrentUserCompany"
              @click="handleContact"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Contact Company
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useCvSwapStore } from '../store';

export default {
  name: 'CompanyProfileView',
  
  setup() {
    const route = useRoute();
    const _cvSwapStore = useCvSwapStore(); // Prefix with underscore to indicate it's intentionally unused
    
    const company = ref(null);
    const isLoading = ref(true);
    const error = ref(null);
    const isCurrentUserCompany = ref(false);
    
    const fetchCompany = async () => {
      try {
        isLoading.value = true;
        error.value = null;
        // Replace with actual API call
        // company.value = await cvSwapStore.fetchCompany(route.params.id);
        
        // Mock data for now
        company.value = {
          id: route.params.id,
          name: 'TechCorp',
          tagline: 'Innovating the future',
          logo: 'https://via.placeholder.com/150',
          about: 'A leading technology company specializing in innovative solutions for modern businesses. We are committed to excellence and continuous improvement.',
          industry: 'Information Technology',
          size: '201-500',
          founded: '2010',
          website: 'https://techcorp.example.com',
          techStack: ['JavaScript', 'Vue.js', 'Node.js', 'AWS', 'Docker']
        };
        
        // Check if this is the current user's company
        // isCurrentUserCompany.value = await cvSwapStore.isCurrentUserCompany(route.params.id);
      } catch (err) {
        console.error('Error fetching company:', err);
        error.value = 'Failed to load company profile. Please try again later.';
      } finally {
        isLoading.value = false;
      }
    };
    
    const handleContact = () => {
      // TODO: Implement contact functionality
      console.log('Contacting company:', company.value.name);
      // This would typically open a contact form or chat
    };
    
    onMounted(() => {
      fetchCompany();
    });
    
    return {
      company,
      isLoading,
      error,
      isCurrentUserCompany,
      fetchCompany,
      handleContact
    };
  }
};
</script>

<style scoped>
.company-profile {
  max-width: 1200px;
  margin: 0 auto;
}
</style>
