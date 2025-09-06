<template>
  <div class="company-cv-editor">
    <div class="max-w-4xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">Company CV Editor</h1>
      
      <form @submit.prevent="saveProfile" class="space-y-8">
        <!-- Basic Info -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium mb-4">Basic Information</h2>
          
          <!-- Logo Upload -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
            <div class="flex items-center">
              <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                <img 
                  v-if="form.logoPreview" 
                  :src="form.logoPreview" 
                  class="h-full w-full object-cover"
                >
                <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                  <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <input 
                  type="file" 
                  ref="logoInput"
                  accept="image/*"
                  class="hidden"
                  @change="handleLogoUpload"
                >
                <button 
                  type="button"
                  @click="$refs.logoInput.click()"
                  class="text-sm font-medium text-blue-600 hover:text-blue-500"
                >
                  Change logo
                </button>
              </div>
            </div>
          </div>
          
          <!-- Form Fields -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
              <input 
                type="text" 
                v-model="form.name"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
              <input 
                type="text" 
                v-model="form.industry"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
              <input 
                type="text" 
                v-model="form.location"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
              <input 
                type="url" 
                v-model="form.website"
                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                placeholder="https://"
              >
            </div>
          </div>
          
          <!-- About -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">About</label>
            <textarea 
              v-model="form.about"
              rows="3"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Tell us about your company..."
            ></textarea>
          </div>
        </div>
        
        <!-- Tech Stack -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium mb-4">Tech Stack</h2>
          <div class="flex flex-wrap gap-2 mb-4">
            <span 
              v-for="(tech, index) in form.techStack" 
              :key="index"
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
            >
              {{ tech }}
              <button 
                type="button"
                @click="removeTech(index)"
                class="ml-1.5 inline-flex items-center justify-center h-4 w-4 rounded-full text-blue-600 hover:bg-blue-200"
              >
                ×
              </button>
            </span>
          </div>
          <div class="flex">
            <input 
              type="text" 
              v-model="newTech"
              @keydown.enter.prevent="addTech"
              class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md"
              placeholder="Add technology"
            >
            <button 
              type="button"
              @click="addTech"
              class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700"
            >
              Add
            </button>
          </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end">
          <button 
            type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700"
            :disabled="isSaving"
          >
            {{ isSaving ? 'Saving...' : 'Save Company Profile' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  industry: '',
  location: '',
  website: '',
  about: '',
  logo: null,
  logoPreview: null,
  techStack: []
});

const newTech = ref('');
const isSaving = ref(false);

const handleLogoUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.logo = file;
    form.value.logoPreview = URL.createObjectURL(file);
  }
};

const addTech = () => {
  if (newTech.value.trim() && !form.value.techStack.includes(newTech.value.trim())) {
    form.value.techStack.push(newTech.value.trim());
    newTech.value = '';
  }
};

const removeTech = (index) => {
  form.value.techStack.splice(index, 1);
};

const saveProfile = async () => {
  isSaving.value = true;
  try {
    // TODO: Implement API call to save company profile
    console.log('Saving company profile:', form.value);
    await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
    router.push('/cv-swap');
  } catch (error) {
    console.error('Error saving company profile:', error);
  } finally {
    isSaving.value = false;
  }
};
</script>
