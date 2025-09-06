<template>
  <div class="matches-view">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">Your Matches</h1>
      
      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
      </div>
      
      <!-- Empty State -->
      <div v-else-if="matches.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No matches yet</h3>
        <p class="mt-1 text-sm text-gray-500">Keep swiping to find potential matches!</p>
        <div class="mt-6">
          <router-link 
            to="/cv-swap/discover"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Start Swiping
          </router-link>
        </div>
      </div>
      
      <!-- Matches List -->
      <div v-else class="space-y-6">
        <div 
          v-for="match in matches" 
          :key="match.id" 
          class="bg-white shadow overflow-hidden sm:rounded-lg"
        >
          <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-12 w-12 rounded-full overflow-hidden bg-gray-200">
                <img 
                  :src="getMatchImage(match)" 
                  :alt="getMatchName(match)"
                  class="h-full w-full object-cover"
                >
              </div>
              <div class="ml-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ getMatchName(match) }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                  {{ getMatchDescription(match) }}
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ match.matchPercentage }}% Match
              </span>
              <router-link 
                :to="getMatchLink(match)"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                View Profile
              </router-link>
              <button 
                v-if="!match.contacted"
                @click="initiateContact(match)"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Contact
              </button>
              <span v-else class="text-xs text-green-600">
                Contacted
              </span>
            </div>
          </div>
          
          <!-- Match Details -->
          <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <h4 class="text-sm font-medium text-gray-500">Match Details</h4>
                <ul class="mt-2 space-y-1">
                  <li class="text-sm text-gray-900">
                    Matched on {{ formatDate(match.matchedAt) }}
                  </li>
                  <li class="text-sm text-gray-900">
                    Compatibility: {{ getCompatibilityText(match.matchPercentage) }}
                  </li>
                </ul>
              </div>
              <div>
                <h4 class="text-sm font-medium text-gray-500">Shared Interests</h4>
                <div class="mt-2 flex flex-wrap gap-1">
                  <span 
                    v-for="(interest, index) in getSharedInterests(match)" 
                    :key="index"
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"
                  >
                    {{ interest }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useCvSwapStore } from '@/modules/cv-swap/store';

const cvSwapStore = useCvSwapStore();

// Computed
const matches = computed(() => cvSwapStore.matches);
const isLoading = computed(() => cvSwapStore.isLoading);

// Methods
const getMatchImage = (match) => {
  return match.type === 'company' 
    ? (match.logo || '/images/company-placeholder.png')
    : (match.photo || '/images/avatar-placeholder.svg');
};

const getMatchName = (match) => {
  return match.type === 'company' ? match.name : match.title;
};

const getMatchDescription = (match) => {
  return match.type === 'company' 
    ? `${match.industry} • ${match.location || 'Location not specified'}`
    : `${match.currentPosition || 'Professional'} • ${match.experience || 'Experience not specified'}`;
};

const getMatchLink = (match) => {
  return match.type === 'company'
    ? `/cv-swap/company/${match.id}`
    : `/cv-swap/candidate/${match.id}`;
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getCompatibilityText = (percentage) => {
  if (percentage >= 90) return 'Excellent match!';
  if (percentage >= 75) return 'Very good match';
  if (percentage >= 60) return 'Good match';
  return 'Potential match';
};

const getSharedInterests = (match) => {
  // This would come from your matching algorithm
  return match.sharedInterests || ['Technology', 'Innovation', 'Teamwork'];
};

const initiateContact = (match) => {
  // TODO: Implement contact initiation
  console.log('Initiating contact with:', match);
  // This would typically open a chat or email composer
  match.contacted = true;
};

// Lifecycle
onMounted(() => {
  if (cvSwapStore.matches.length === 0) {
    cvSwapStore.fetchMatches();
  }
});
</script>
