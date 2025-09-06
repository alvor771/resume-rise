<template>
  <div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
    </div>
    
    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 p-4">
      <p class="text-sm text-red-700">{{ error }}</p>
    </div>
    
    <!-- Profile Content -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Header -->
      <div class="px-6 py-4 bg-gray-50 border-b">
        <div class="flex items-center">
          <img 
            :src="profile.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(profile.name) + '&background=random'" 
            :alt="profile.name"
            class="w-20 h-20 rounded-full mr-4"
          >
          <div>
            <h1 class="text-2xl font-bold">{{ profile.name }}</h1>
            <p class="text-gray-600">{{ profile.title }}</p>
          </div>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="p-6">
        <!-- About -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-2">About</h2>
          <p class="text-gray-700">{{ profile.about || 'No information provided.' }}</p>
        </section>
        
        <!-- Experience -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-2">Experience</h2>
          <div v-if="profile.experience?.length">
            <div v-for="(exp, i) in profile.experience" :key="i" class="mb-4">
              <h3 class="font-medium">{{ exp.title }}</h3>
              <p class="text-gray-600">{{ exp.company }} • {{ exp.duration }}</p>
            </div>
          </div>
          <p v-else class="text-gray-500">No experience listed.</p>
        </section>
        
        <!-- Skills -->
        <section>
          <h2 class="text-lg font-semibold mb-2">Skills</h2>
          <div v-if="profile.skills?.length" class="flex flex-wrap gap-2">
            <span 
              v-for="(skill, i) in profile.skills" 
              :key="i"
              class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"
            >
              {{ skill }}
            </span>
          </div>
          <p v-else class="text-gray-500">No skills listed.</p>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'PublicProfileView',
  
  setup() {
    const loading = ref(true);
    const error = ref(null);
    const profile = ref({
      name: 'Loading...',
      title: '',
      about: '',
      experience: [],
      skills: []
    });

    // Simulate API call
    onMounted(() => {
      setTimeout(() => {
        try {
          // Mock data
          profile.value = {
            name: 'Alex Johnson',
            title: 'Senior Frontend Developer',
            about: 'Passionate frontend developer with 5+ years of experience.',
            experience: [
              { title: 'Senior Developer', company: 'TechCorp', duration: '2020-Present' },
              { title: 'Developer', company: 'WebSolutions', duration: '2018-2020' }
            ],
            skills: ['Vue.js', 'React', 'JavaScript', 'TypeScript', 'CSS3']
          };
        } catch (err) {
          error.value = 'Failed to load profile';
        } finally {
          loading.value = false;
        }
      }, 800);
    });

    return { loading, error, profile };
  }
};
</script>
