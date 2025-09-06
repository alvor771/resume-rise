<template>
  <div class="candidate-profile">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg max-w-4xl mx-auto">
      <!-- Header -->
      <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-600 to-indigo-800">
        <div class="flex items-center">
          <div class="flex-shrink-0 h-20 w-20 rounded-full bg-white p-1">
            <img 
              :src="candidate.photo || '/images/avatar-placeholder.svg'" 
              :alt="candidate.name"
              class="h-full w-full rounded-full object-cover"
            >
          </div>
          <div class="ml-4">
            <h1 class="text-2xl font-bold text-white">{{ candidate.name }}</h1>
            <p class="mt-1 text-indigo-100">{{ candidate.title }}</p>
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
              {{ candidate.summary || 'No summary provided.' }}
            </dd>
          </div>
          
          <!-- Experience -->
          <div v-if="candidate.experience?.length" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Experience</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 space-y-4">
              <div v-for="(exp, index) in candidate.experience" :key="index" class="border-l-2 border-indigo-200 pl-4">
                <h3 class="font-medium">{{ exp.position }}</h3>
                <p class="text-gray-600">{{ exp.company }} • {{ exp.duration }}</p>
                <p class="mt-1 text-gray-700">{{ exp.description }}</p>
              </div>
            </dd>
          </div>
          
          <!-- Skills -->
          <div v-if="candidate.skills?.length" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Skills</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="(skill, index) in candidate.skills" 
                  :key="index"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                >
                  {{ skill }}
                </span>
              </div>
            </dd>
          </div>
          
          <!-- Education -->
          <div v-if="candidate.education?.length" class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Education</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 space-y-4">
              <div v-for="(edu, index) in candidate.education" :key="index" class="border-l-2 border-indigo-200 pl-4">
                <h3 class="font-medium">{{ edu.degree }}</h3>
                <p class="text-gray-600">{{ edu.school }} • {{ edu.year }}</p>
              </div>
            </dd>
          </div>
          
          <!-- Contact -->
          <div v-if="candidate.contact" class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Contact</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
              <div class="space-y-1">
                <p v-if="candidate.contact.email" class="flex items-center">
                  <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                  {{ candidate.contact.email }}
                </p>
                <p v-if="candidate.contact.phone" class="flex items-center">
                  <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                  </svg>
                  {{ candidate.contact.phone }}
                </p>
                <p v-if="candidate.contact.location" class="flex items-center">
                  <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                  </svg>
                  {{ candidate.contact.location }}
                </p>
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
                Pass
              </button>
              <button 
                @click="like"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Like
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

const candidate = ref({
  id: route.params.id,
  name: '',
  photo: '',
  title: '',
  summary: '',
  experience: [],
  skills: [],
  education: [],
  contact: {}
});

const like = async () => {
  await cvSwapStore.likeItem(candidate.value, 'candidate');
  router.push('/cv-swap/matches');
};

const pass = () => {
  cvSwapStore.passItem(candidate.value.id, 'candidate');
  router.push('/cv-swap/discover');
};

// Fetch candidate data
onMounted(async () => {
  const fetchedCandidate = cvSwapStore.getCandidateById(candidate.value.id);
  if (fetchedCandidate) {
    candidate.value = { ...candidate.value, ...fetchedCandidate };
  } else {
    // TODO: Fetch from API if not in store
    console.error('Candidate not found');
  }
});
</script>
