<template>
  <div class="candidate-cv-editor">
    <div class="max-w-4xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">My CV Editor</h1>
      
      <form @submit.prevent="saveProfile" class="space-y-8">
        <!-- Basic Info -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium mb-4">Personal Information</h2>
          
          <!-- Photo Upload -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
            <div class="flex items-center">
              <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                <img 
                  v-if="form.photoPreview" 
                  :src="form.photoPreview" 
                  class="h-full w-full object-cover"
                >
                <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                  <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <input 
                  type="file" 
                  ref="photoInput"
                  accept="image/*"
                  class="hidden"
                  @change="handlePhotoUpload"
                >
                <button 
                  type="button"
                  @click="$refs.photoInput.click()"
                  class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                >
                  {{ form.photoPreview ? 'Change photo' : 'Add photo' }}
                </button>
              </div>
            </div>
          </div>
          
          <!-- Form Fields -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <input 
                type="text" 
                v-model="form.name"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Professional Title</label>
              <input 
                type="text" 
                v-model="form.title"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="e.g. Senior Software Engineer"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input 
                type="email" 
                v-model="form.email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
              <input 
                type="tel" 
                v-model="form.phone"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
            </div>
          </div>
          
          <!-- About -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Professional Summary</label>
            <textarea 
              v-model="form.about"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="A brief summary of your professional background and skills..."
            ></textarea>
          </div>
        </div>
        
        <!-- Experience -->
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium">Work Experience</h2>
            <button 
              type="button"
              @click="addExperience"
              class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
            >
              + Add Experience
            </button>
          </div>
          
          <div v-for="(exp, index) in form.experience" :key="index" class="mb-6 last:mb-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                <input 
                  type="text" 
                  v-model="exp.position"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                <input 
                  type="text" 
                  v-model="exp.company"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
              </div>
            </div>
            <div class="mt-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea 
                v-model="exp.description"
                rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="Describe your role and achievements..."
              ></textarea>
            </div>
            <div class="mt-2 flex justify-end">
              <button 
                type="button"
                @click="removeExperience(index)"
                class="text-sm text-red-600 hover:text-red-500"
              >
                Remove
              </button>
            </div>
          </div>
        </div>
        
        <!-- Skills -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium mb-4">Skills</h2>
          <div class="flex flex-wrap gap-2 mb-4">
            <span 
              v-for="(skill, index) in form.skills" 
              :key="index"
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800"
            >
              {{ skill }}
              <button 
                type="button"
                @click="removeSkill(index)"
                class="ml-1.5 inline-flex items-center justify-center h-4 w-4 rounded-full text-indigo-600 hover:bg-indigo-200"
              >
                ×
              </button>
            </span>
          </div>
          <div class="flex">
            <input 
              type="text" 
              v-model="newSkill"
              @keydown.enter.prevent="addSkill"
              class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md"
              placeholder="Add a skill"
            >
            <button 
              type="button"
              @click="addSkill"
              class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700"
            >
              Add
            </button>
          </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
          <button 
            type="submit"
            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700"
            :disabled="isSaving"
          >
            {{ isSaving ? 'Saving...' : 'Save Profile' }}
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
  name: 'CandidateCVEditor',
  
  setup() {
    const router = useRouter();
    
    const form = ref({
      name: '',
      title: '',
      email: '',
      phone: '',
      about: '',
      photo: null,
      photoPreview: null,
      experience: [],
      skills: []
    });
    
    const newSkill = ref('');
    const isSaving = ref(false);
    
    const handlePhotoUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        form.value.photo = file;
        form.value.photoPreview = URL.createObjectURL(file);
      }
    };
    
    const addExperience = () => {
      form.value.experience.push({
        position: '',
        company: '',
        description: ''
      });
    };
    
    const removeExperience = (index) => {
      form.value.experience.splice(index, 1);
    };
    
    const addSkill = () => {
      if (newSkill.value.trim() && !form.value.skills.includes(newSkill.value.trim())) {
        form.value.skills.push(newSkill.value.trim());
        newSkill.value = '';
      }
    };
    
    const removeSkill = (index) => {
      form.value.skills.splice(index, 1);
    };
    
    const saveProfile = async () => {
      isSaving.value = true;
      try {
        // TODO: Implement API call to save candidate profile
        console.log('Saving candidate profile:', form.value);
        await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
        router.push('/cv-swap');
      } catch (error) {
        console.error('Error saving candidate profile:', error);
      } finally {
        isSaving.value = false;
      }
    };
    
    // Initialize with one empty experience field
    addExperience();
    
    return {
      form,
      newSkill,
      isSaving,
      handlePhotoUpload,
      addExperience,
      removeExperience,
      addSkill,
      removeSkill,
      saveProfile
    };
  }
};
</script>
