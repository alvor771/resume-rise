<template>
  <div class="discover-view container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-8">Discover</h1>
    
    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6">
      <button 
        v-for="tab in tabs" 
        :key="tab.id"
        @click="setActiveTab(tab.id)"
        class="px-4 py-2 font-medium text-sm rounded-t-lg mr-2"
        :class="{
          'bg-blue-100 text-blue-700': activeTab === tab.id,
          'text-gray-500 hover:text-gray-700': activeTab !== tab.id
        }"
      >
        {{ tab.name }}
      </button>
    </div>
    
    <!-- Swipe Panel -->
    <SwipePanel 
      :items="activeItems"
      :is-loading="isLoading"
      @swipe-left="handleSwipe('left')"
      @swipe-right="handleSwipe('right')"
      @refresh="fetchItems"
    >
      <template #default="{ item }">
        <CompanyCard 
          v-if="activeTab === 'companies'" 
          :company="item"
          :show-actions="false"
          class="w-full"
        />
        <CandidateCard 
          v-else 
          :candidate="item"
          :show-actions="false"
          class="w-full"
        />
      </template>
      
      <template #next="{ item }">
        <div class="h-full opacity-50">
          <CompanyCard 
            v-if="activeTab === 'companies'" 
            :company="item"
            :show-actions="false"
            :show-match-badge="false"
            class="h-full"
          />
          <CandidateCard 
            v-else 
            :candidate="item"
            :show-actions="false"
            :show-match-badge="false"
            class="h-full"
          />
        </div>
      </template>
    </SwipePanel>
    
    <!-- Action Buttons (for desktop) -->
    <div v-if="!isLoading && activeItems.length > 0" class="hidden md:flex justify-center gap-8 mt-8">
      <button 
        @click="handleSwipe('left')"
        class="p-4 rounded-full bg-white shadow-lg text-red-500 hover:bg-red-50 transition-colors"
        :disabled="isAnimating"
      >
        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <button 
        @click="handleSwipe('right')"
        class="p-4 rounded-full bg-white shadow-lg text-green-500 hover:bg-green-50 transition-colors"
        :disabled="isAnimating"
      >
        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useCvSwapStore } from '../store';
import { SwipePanel, CompanyCard, CandidateCard } from '../components';

const store = useCvSwapStore();
const activeTab = ref('companies');
const isAnimating = ref(false);

const tabs = [
  { id: 'companies', name: 'Companies' },
  { id: 'candidates', name: 'Candidates' }
];

const activeItems = computed(() => {
  return activeTab.value === 'companies' ? store.companies : store.candidates;
});

const isLoading = computed(() => {
  return activeTab.value === 'companies' ? store.isLoadingCompanies : store.isLoadingCandidates;
});

const setActiveTab = (tabId) => {
  activeTab.value = tabId;
  fetchItems();
};

const fetchItems = () => {
  if (activeTab.value === 'companies') {
    store.fetchCompanies();
  } else {
    store.fetchCandidates();
  }
};

const handleSwipe = async (direction) => {
  if (isAnimating.value || activeItems.value.length === 0) return;
  
  isAnimating.value = true;
  const currentItem = activeItems.value[0];
  
  if (direction === 'right') {
    // Like action
    if (activeTab.value === 'companies') {
      await store.likeCompany(currentItem.id);
    } else {
      await store.likeCandidate(currentItem.id);
    }
  } else {
    // Pass action
    if (activeTab.value === 'companies') {
      await store.passCompany(currentItem.id);
    } else {
      await store.passCandidate(currentItem.id);
    }
  }
  
  // Remove the swiped item from the local array
  if (activeTab.value === 'companies') {
    store.companies = store.companies.slice(1);
  } else {
    store.candidates = store.candidates.slice(1);
  }
  
  isAnimating.value = false;
};

// Fetch initial data
onMounted(() => {
  fetchItems();
});
</script>

<style scoped>
.discover-view {
  min-height: calc(100vh - 8rem);
  padding-bottom: 4rem;
}
</style>
