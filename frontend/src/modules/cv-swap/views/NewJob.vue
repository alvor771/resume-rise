<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-white">New Job Posting</h1>
    </div>

    <div class="gradient-card p-6 rounded-xl border border-gray-700/50">
      <form @submit.prevent="submitJob" class="space-y-6">
        <!-- Job Title -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Job Title *</label>
          <input
            v-model="job.title"
            type="text"
            required
            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            placeholder="e.g. Senior Frontend Developer"
          >
        </div>

        <!-- Job Type -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Job Type *</label>
            <select
              v-model="job.type"
              required
              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            >
              <option value="">Select job type</option>
              <option value="full-time">Full-time</option>
              <option value="part-time">Part-time</option>
              <option value="contract">Contract</option>
              <option value="internship">Internship</option>
              <option value="temporary">Temporary</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Location *</label>
            <input
              v-model="job.location"
              type="text"
              required
              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              placeholder="e.g. Remote, New York, etc."
            >
          </div>
        </div>

        <!-- Job Description -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Job Description *</label>
          <textarea
            v-model="job.description"
            rows="6"
            required
            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            placeholder="Describe the job responsibilities, requirements, and benefits..."
          ></textarea>
        </div>

        <!-- Skills -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Required Skills</label>
          <div class="flex flex-wrap gap-2 mb-2">
            <span 
              v-for="(skill, index) in job.skills" 
              :key="index"
              class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300"
            >
              {{ skill }}
              <button 
                type="button" 
                @click="removeSkill(index)"
                class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-purple-500/30 hover:bg-purple-500/50"
              >
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </span>
          </div>
          <div class="flex">
            <input
              v-model="skillInput"
              type="text"
              class="flex-1 bg-gray-700 border border-gray-600 rounded-l-lg px-4 py-2 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
              placeholder="Add a skill and press Enter"
              @keydown.enter.prevent="addSkill"
            >
            <button 
              type="button"
              @click="addSkill"
              class="px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-r-lg transition-colors"
            >
              Add
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
          <button
            type="submit"
            class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Publishing...
            </span>
            <span v-else>Publish Job</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'NewJob',
  
  setup() {
    const router = useRouter();
    const isSubmitting = ref(false);
    const skillInput = ref('');
    
    const job = ref({
      title: '',
      type: '',
      location: '',
      description: '',
      skills: []
    });

    const addSkill = () => {
      const skill = skillInput.value.trim();
      if (skill && !job.value.skills.includes(skill)) {
        job.value.skills.push(skill);
        skillInput.value = '';
      }
    };

    const removeSkill = (index) => {
      job.value.skills.splice(index, 1);
    };

    const submitJob = async () => {
      if (isSubmitting.value) return;
      
      isSubmitting.value = true;
      
      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1500));
        console.log('Job submitted:', job.value);
        
        // Redirect to jobs list or show success message
        router.push({ name: 'CompanyDashboard' });
      } catch (error) {
        console.error('Error submitting job:', error);
      } finally {
        isSubmitting.value = false;
      }
    };

    return {
      job,
      skillInput,
      isSubmitting,
      addSkill,
      removeSkill,
      submitJob
    };
  }
};
</script>
