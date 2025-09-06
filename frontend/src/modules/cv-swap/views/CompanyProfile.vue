<template>
  <div class="company-profile">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg max-w-4xl mx-auto">
      <!-- Header -->
      <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-20 w-20 rounded-full bg-white p-1">
            <img 
              :src="company.logo || '/images/company-placeholder.png'" 
              :alt="company.name"
              class="h-full w-full rounded-full object-contain"
            >
          </div>
          <div class="ml-4">
            <h1 class="text-2xl font-bold text-white">{{ company.name }}</h1>
            <p class="mt-1 text-blue-100">{{ company.industry }}</p>
          </div>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="border-t border-gray-200">
        <dl>
          <!-- About Section -->
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">About</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
              {{ company.about || 'No description provided.' }}
            </dd>
          </div>
          
          <!-- Location -->
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Location</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
              {{ company.location || 'Not specified' }}
            </dd>
          </div>
          
          <!-- Tech Stack -->
          <div v-if="company.techStack?.length" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Tech Stack</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="(tech, index) in company.techStack" 
                  :key="index"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                  {{ tech }}
                </span>
              </div>
            </dd>
          </div>
          
          <!-- Action Buttons -->
          <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between">
            <button 
              @click="$router.go(-1)"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Back
            </button>
            <div class="space-x-3">
              <button 
                @click="pass"
                class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
              >
                Not Interested
              </button>
              <button 
                @click="like"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                I'm Interested
              </button>
            </div>
          </div>
        </dl>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCvSwapStore } from '@/modules/cv-swap/store';

const route = useRoute();
const router = useRouter();
const cvSwapStore = useCvSwapStore();

const company = ref({
  id: route.params.id,
  name: '',
  logo: '',
  industry: '',
  about: '',
  location: '',
  techStack: []
});

const like = async () => {
  await cvSwapStore.likeItem(company.value, 'company');
  router.push('/cv-swap/matches');
};

const pass = () => {
  cvSwapStore.passItem(company.value.id, 'company');
  router.push('/cv-swap/discover');
};

// Fetch company data
onMounted(async () => {
  const fetchedCompany = cvSwapStore.getCompanyById(company.value.id);
  if (fetchedCompany) {
    company.value = { ...company.value, ...fetchedCompany };
  } else {
    // TODO: Fetch from API if not in store
    console.error('Company not found');
  }
});
</script>
