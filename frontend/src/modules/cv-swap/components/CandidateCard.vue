<template>
  <div class="candidate-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Candidate Header -->
    <div class="relative h-32 bg-gradient-to-r from-indigo-600 to-indigo-800">
      <!-- Candidate Photo -->
      <div class="absolute -bottom-6 left-4 h-16 w-16 rounded-full bg-white p-1 shadow-md">
        <img 
          :src="candidate.photo || '/images/avatar-placeholder.svg'" 
          :alt="candidate.name"
          class="h-full w-full rounded-full object-cover"
        >
      </div>
      
      <!-- Match Badge -->
      <div v-if="showMatchBadge" class="absolute top-2 right-2">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
          {{ candidate.matchPercentage }}% Match
        </span>
      </div>
    </div>
    
    <!-- Candidate Info -->
    <div class="pt-8 px-4 pb-4">
      <h3 class="text-lg font-semibold text-gray-900">{{ candidate.name }}</h3>
      <p class="text-sm text-gray-600">{{ candidate.title }}</p>
      
      <!-- Location & Experience -->
      <div class="mt-2 space-y-1 text-sm text-gray-500">
        <div class="flex items-center">
          <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          {{ candidate.location || 'Location not specified' }}
        </div>
        <div class="flex items-center">
          <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 12h.01M16 12h.01M12 8h.01M8 8h.01" />
          </svg>
          {{ candidate.experience || 'Experience not specified' }}
        </div>
      </div>
      
      <!-- Skills -->
      <div v-if="candidate.skills?.length" class="mt-3">
        <div class="flex flex-wrap gap-1">
          <span 
            v-for="(skill, index) in candidate.skills.slice(0, 5)" 
            :key="index"
            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800"
          >
            {{ skill }}
          </span>
          <span v-if="candidate.skills.length > 5" class="text-xs text-gray-500 self-center">
            +{{ candidate.skills.length - 5 }} more
          </span>
        </div>
      </div>
      
      <!-- Summary -->
      <div class="mt-3">
        <p class="text-sm text-gray-600 line-clamp-3">
          {{ candidate.summary || 'No summary provided.' }}
        </p>
      </div>
      
      <!-- Action Buttons -->
      <div class="mt-4 flex justify-between items-center">
        <button 
          v-if="showActions"
          @click="$emit('pass')"
          class="p-2 text-gray-400 hover:text-red-500 rounded-full hover:bg-gray-100"
          title="Not interested"
        >
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        
        <div v-else class="w-6"></div>
        
        <router-link 
          :to="`/cv-swap/candidate/${candidate.id}`"
          class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
        >
          View Profile
        </router-link>
        
        <button 
          v-if="showActions"
          @click="$emit('like')"
          class="p-2 text-green-500 hover:text-green-600 rounded-full hover:bg-green-50"
          title="I'm interested"
        >
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </button>
        
        <div v-else class="w-6"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
  candidate: {
    type: Object,
    required: true
  },
  showActions: {
    type: Boolean,
    default: true
  },
  showMatchBadge: {
    type: Boolean,
    default: true
  }
});

defineEmits(['like', 'pass']);
</script>
