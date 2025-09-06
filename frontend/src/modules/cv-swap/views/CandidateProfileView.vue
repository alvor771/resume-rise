<template>
  <div class="candidate-profile">
    <div v-if="isLoading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mx-auto"></div>
    </div>
    
    <div v-else-if="error" class="text-center py-12">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h3 class="mt-2 text-lg font-medium text-gray-900">Error loading profile</h3>
      <button @click="fetchCandidate" class="mt-4 text-sm text-indigo-600 hover:text-indigo-500">
        Try Again
      </button>
    </div>
    
    <div v-else-if="candidate" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <!-- Header -->
      <div class="px-4 py-5 sm:px-6 bg-indigo-600 text-white">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-16 w-16 rounded-full bg-white p-1">
            <img 
              :src="candidate.photo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(candidate.name) + '&background=4f46e5&color=fff'" 
              :alt="candidate.name"
              class="h-full w-full rounded-full object-cover"
            >
          </div>
          <div class="ml-4">
            <h1 class="text-2xl font-bold">{{ candidate.name }}</h1>
            <p class="text-indigo-100">{{ candidate.title || 'Professional' }}</p>
          </div>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="px-4 py-5 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column -->
          <div class="lg:col-span-2 space-y-6">
            <!-- About -->
            <div>
              <h2 class="text-lg font-medium text-gray-900">About</h2>
              <p class="mt-2 text-gray-600">{{ candidate.about || 'No information provided.' }}</p>
            </div>
            
            <!-- Experience -->
            <div v-if="candidate.experience?.length">
              <h2 class="text-lg font-medium text-gray-900">Experience</h2>
              <div class="mt-4 space-y-4">
                <div v-for="(exp, i) in candidate.experience" :key="i" class="border-l-2 border-indigo-200 pl-4">
                  <h3 class="font-medium">{{ exp.position }}</h3>
                  <p class="text-sm text-gray-600">{{ exp.company }} • {{ exp.duration }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Skills -->
            <div v-if="candidate.skills?.length">
              <h3 class="text-lg font-medium text-gray-900">Skills</h3>
              <div class="mt-2 flex flex-wrap gap-2">
                <span 
                  v-for="(skill, i) in candidate.skills" 
                  :key="i"
                  class="px-3 py-1 text-sm bg-indigo-100 text-indigo-800 rounded-full"
                >
                  {{ skill }}
                </span>
              </div>
            </div>
            
            <!-- Contact -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h3 class="font-medium text-gray-900">Contact</h3>
              <div class="mt-2 space-y-2">
                <div v-if="candidate.email" class="flex items-center text-sm">
                  <span class="text-gray-500 w-16">Email:</span>
                  <a :href="'mailto:' + candidate.email" class="text-indigo-600 hover:underline">
                    {{ candidate.email }}
                  </a>
                </div>
                <div v-if="candidate.phone" class="flex items-center text-sm">
                  <span class="text-gray-500 w-16">Phone:</span>
                  <a :href="'tel:' + candidate.phone" class="hover:underline">
                    {{ candidate.phone }}
                  </a>
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
import { useRoute } from 'vue-router';
import { useCvSwapStore } from '../store';

export default {
  name: 'CandidateProfileView',
  
  setup() {
    const route = useRoute();
    const _cvSwapStore = useCvSwapStore(); // Prefix with underscore to indicate it's intentionally unused
    
    const candidate = ref(null);
    const isLoading = ref(true);
    const error = ref(null);
    
    const fetchCandidate = async () => {
      try {
        isLoading.value = true;
        error.value = null;
        
        // Mock data - replace with actual API call
        candidate.value = {
          id: route.params.id,
          name: 'Alex Johnson',
          title: 'Senior Software Engineer',
          photo: 'https://randomuser.me/api/portraits/men/32.jpg',
          about: 'Experienced software engineer with 8+ years in web development.',
          email: 'alex.johnson@example.com',
          phone: '+1 (555) 123-4567',
          skills: ['JavaScript', 'Vue.js', 'React', 'Node.js', 'AWS'],
          experience: [
            {
              position: 'Senior Software Engineer',
              company: 'TechCorp',
              duration: '2020 - Present'
            },
            {
              position: 'Software Engineer',
              company: 'WebSolutions',
              duration: '2017 - 2020'
            }
          ]
        };
      } catch (err) {
        console.error('Error fetching candidate:', err);
        error.value = 'Failed to load candidate profile';
      } finally {
        isLoading.value = false;
      }
    };
    
    onMounted(() => {
      fetchCandidate();
    });
    
    return {
      candidate,
      isLoading,
      error,
      fetchCandidate
    };
  }
};
</script>
