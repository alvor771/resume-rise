<template>
  <div class="company-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Company Header -->
    <div class="relative h-32 bg-gradient-to-r from-blue-600 to-blue-800">
      <!-- Company Logo -->
      <div class="absolute -bottom-6 left-4 h-16 w-16 rounded-full bg-white p-1 shadow-md">
        <img 
          :src="company.logo || '/images/company-placeholder.png'" 
          :alt="company.name"
          class="h-full w-full rounded-full object-cover"
        >
      </div>
      
      <!-- Match Badge -->
      <div v-if="showMatchBadge" class="absolute top-2 right-2">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
          {{ company.matchPercentage }}% Match
        </span>
      </div>
    </div>
    
    <!-- Company Info -->
    <div class="pt-8 px-4 pb-4">
      <h3 class="text-lg font-semibold text-gray-900 truncate">{{ company.name }}</h3>
      <p class="text-sm text-gray-600">{{ company.industry }}</p>
      
      <!-- Location -->
      <div class="mt-2 flex items-center text-sm text-gray-500">
        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        {{ company.location || 'Location not specified' }}
      </div>
      
      <!-- Tech Stack -->
      <div v-if="company.techStack?.length" class="mt-3">
        <div class="flex flex-wrap gap-1">
          <span 
            v-for="(tech, index) in company.techStack.slice(0, 3)" 
            :key="index"
            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"
          >
            {{ tech }}
          </span>
          <span v-if="company.techStack.length > 3" class="text-xs text-gray-500 self-center">
            +{{ company.techStack.length - 3 }} more
          </span>
        </div>
      </div>
      
      <!-- About Preview -->
      <div class="mt-3">
        <p class="text-sm text-gray-600 line-clamp-3">
          {{ company.about || 'No description provided.' }}
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
          :to="`/cv-swap/company/${company.id}`"
          class="text-sm font-medium text-blue-600 hover:text-blue-800"
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
  company: {
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
