<template>
  <div class="max-w-5xl mx-auto py-8 px-4">
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
    </div>
    
    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 p-4">
      <p class="text-sm text-red-700">{{ error }}</p>
    </div>
    
    <!-- Company Profile -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Company Header -->
      <div class="h-48 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/40 to-transparent">
          <div class="flex items-end">
            <img 
              :src="company.logo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(company.name) + '&background=random'" 
              :alt="company.name"
              class="w-20 h-20 rounded-lg bg-white p-1 shadow-md"
            >
            <div class="ml-6 text-white">
              <h1 class="text-3xl font-bold">{{ company.name }}</h1>
              <p class="text-lg">{{ company.tagline }}</p>
              <div class="flex items-center mt-2 space-x-4">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  {{ company.location }}
                </span>
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 16h.01M16 16h.01M8 16h.01" />
                  </svg>
                  {{ company.industry }}
                </span>
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  {{ company.size }}+ employees
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="p-6">
        <!-- About Section -->
        <section class="mb-8">
          <h2 class="text-xl font-semibold mb-4">About Us</h2>
          <div class="prose max-w-none">
            <p>{{ company.about || 'No company description available.' }}</p>
          </div>
        </section>
        
        <!-- Open Positions -->
        <section class="mb-8">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Open Positions</h2>
            <span class="text-sm text-gray-500">{{ company.openPositions?.length || 0 }} positions</span>
          </div>
          
          <div v-if="company.openPositions?.length" class="space-y-4">
            <div 
              v-for="position in company.openPositions" 
              :key="position.id"
              class="border rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-lg">{{ position.title }}</h3>
                  <div class="flex items-center text-sm text-gray-600 mt-1">
                    <span>{{ position.type }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ position.location }}</span>
                    <span class="mx-2">•</span>
                    <span class="text-green-600 font-medium">{{ position.salary }}</span>
                  </div>
                </div>
                <button 
                  @click="applyForPosition(position.id)"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm"
                >
                  Apply Now
                </button>
              </div>
              <div class="mt-3 text-sm text-gray-700">
                {{ position.description.substring(0, 150) }}...
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-500">
            <p>No open positions at the moment.</p>
            <p class="text-sm mt-2">Check back later for new opportunities!</p>
          </div>
        </section>
        
        <!-- Company Details -->
        <section>
          <h2 class="text-xl font-semibold mb-4">Company Details</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="font-medium text-gray-700 mb-2">Website</h3>
              <a 
                v-if="company.website" 
                :href="company.website" 
                target="_blank" 
                rel="noopener noreferrer"
                class="text-blue-600 hover:underline"
              >
                {{ company.website.replace(/^https?:\/\//, '') }}
              </a>
              <p v-else class="text-gray-500">Not provided</p>
              
              <h3 class="font-medium text-gray-700 mt-4 mb-2">Founded</h3>
              <p>{{ company.founded || 'Not provided' }}</p>
              
              <h3 class="font-medium text-gray-700 mt-4 mb-2">Company Size</h3>
              <p>{{ company.size ? company.size + ' employees' : 'Not provided' }}</p>
            </div>
            <div>
              <h3 class="font-medium text-gray-700 mb-2">Industry</h3>
              <p>{{ company.industry || 'Not provided' }}</p>
              
              <h3 class="font-medium text-gray-700 mt-4 mb-2">Specialties</h3>
              <div v-if="company.specialties?.length" class="flex flex-wrap gap-2">
                <span 
                  v-for="(specialty, index) in company.specialties" 
                  :key="index"
                  class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm"
                >
                  {{ specialty }}
                </span>
              </div>
              <p v-else class="text-gray-500">Not specified</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'PublicCompanyView',
  
  setup() {
    const loading = ref(true);
    const error = ref(null);
    const company = ref({
      name: 'Loading...',
      tagline: '',
      about: '',
      location: '',
      industry: '',
      size: '',
      logo: '',
      website: '',
      founded: '',
      specialties: [],
      openPositions: []
    });

    // Simulate API call
    onMounted(() => {
      setTimeout(() => {
        try {
          // Mock data
          company.value = {
            name: 'Tech Innovations Inc.',
            tagline: 'Building the future, one line of code at a time',
            about: 'Tech Innovations Inc. is a leading technology company specializing in AI and machine learning solutions. We help businesses transform their operations with cutting-edge technology and innovative thinking.',
            location: 'San Francisco, CA',
            industry: 'Information Technology & Services',
            size: '250',
            logo: 'https://via.placeholder.com/150',
            website: 'https://techinnovations.example.com',
            founded: '2015',
            specialties: ['Artificial Intelligence', 'Machine Learning', 'Cloud Computing', 'Data Analytics'],
            openPositions: [
              {
                id: 1,
                title: 'Senior Frontend Developer',
                type: 'Full-time',
                location: 'Remote',
                salary: '$120,000 - $150,000',
                description: 'We are looking for an experienced Frontend Developer to join our team. You will be responsible for building user interfaces and implementing features using modern JavaScript frameworks.'
              },
              {
                id: 2,
                title: 'UX/UI Designer',
                type: 'Full-time',
                location: 'San Francisco, CA',
                salary: '$90,000 - $120,000',
                description: 'Join our design team to create beautiful and intuitive user experiences for our products. You will work closely with product managers and developers to bring designs to life.'
              }
            ]
          };
        } catch (err) {
          error.value = 'Failed to load company profile';
        } finally {
          loading.value = false;
        }
      }, 800);
    });

    const applyForPosition = (positionId) => {
      // In a real app, this would navigate to an application form
      console.log(`Applying for position ${positionId}`);
      alert('Application functionality coming soon!');
    };

    return { loading, error, company, applyForPosition };
  }
};
</script>

<style scoped>
.prose {
  color: #374151;
  line-height: 1.625;
}
.prose p {
  margin-top: 0.75em;
  margin-bottom: 0.75em;
}
</style>
