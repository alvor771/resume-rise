<template>
  <div class="p-4 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">My Profile</h1>
      <div class="flex gap-2">
        <button 
          @click="toggleEditMode"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          {{ isEditMode ? 'Preview' : 'Edit' }}
        </button>
        <button 
          @click="publishProfile"
          class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
        >
          Publish to Discover
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Left Column -->
      <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-center">
            <div class="relative w-32 h-32 mx-auto mb-4 overflow-hidden rounded-full bg-gray-200">
              <img 
                :src="user.photo || 'https://via.placeholder.com/150'" 
                alt="Profile"
                class="w-full h-full object-cover"
              >
              <button 
                v-if="isEditMode"
                class="absolute bottom-0 right-0 bg-blue-500 text-white p-1 rounded-full"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>
            </div>
            <h2 class="text-xl font-semibold">{{ user.name }}</h2>
            <p class="text-gray-600">{{ user.title }}</p>
          </div>

          <div class="mt-6 space-y-4">
            <div v-for="(field, key) in contactFields" :key="key">
              <label class="block text-sm text-gray-600">{{ field.label }}</label>
              <input 
                v-model="user[key]" 
                type="text" 
                class="w-full p-2 border rounded"
                :readonly="!isEditMode"
                :class="{'bg-gray-100': !isEditMode}"
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="md:col-span-2 space-y-6">
        <!-- About -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">About</h2>
          <textarea 
            v-model="user.about" 
            class="w-full p-3 border rounded h-32"
            :readonly="!isEditMode"
            :class="{'bg-gray-100': !isEditMode}"
          ></textarea>
        </div>

        <!-- Experience -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Experience</h2>
            <button v-if="isEditMode" class="text-blue-500">+ Add</button>
          </div>
          
          <div v-for="(exp, index) in user.experience" :key="index" class="mb-4 pb-4 border-b">
            <div class="font-medium">{{ exp.title }}</div>
            <div class="text-gray-600 text-sm">{{ exp.company }} • {{ exp.duration }}</div>
            <p class="mt-1 text-gray-700">{{ exp.description }}</p>
          </div>
        </div>

        <!-- Education -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Education</h2>
          <div v-for="(edu, index) in user.education" :key="index" class="mb-4">
            <div class="font-medium">{{ edu.degree }}</div>
            <div class="text-gray-600 text-sm">{{ edu.school }} • {{ edu.year }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'ProfilePage',
  setup() {
    const isEditMode = ref(true);
    
    const user = ref({
      name: 'John Doe',
      title: 'Senior Developer',
      email: 'john@example.com',
      phone: '+1 (555) 123-4567',
      location: 'San Francisco, CA',
      about: 'Passionate developer with 5+ years of experience...',
      experience: [
        {
          title: 'Senior Developer',
          company: 'Tech Corp',
          duration: '2020 - Present',
          description: 'Led a team of developers...'
        }
      ],
      education: [
        {
          degree: 'B.S. Computer Science',
          school: 'Stanford University',
          year: '2018'
        }
      ]
    });

    const contactFields = {
      email: { label: 'Email' },
      phone: { label: 'Phone' },
      location: { label: 'Location' }
    };

    const toggleEditMode = () => {
      isEditMode.value = !isEditMode.value;
    };

    const publishProfile = () => {
      // TODO: Implement publish functionality
      console.log('Publishing profile...');
    };

    return {
      user,
      isEditMode,
      contactFields,
      toggleEditMode,
      publishProfile
    };
  }
};
</script>
