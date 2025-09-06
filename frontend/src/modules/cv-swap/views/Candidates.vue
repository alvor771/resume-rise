<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-white">Candidates</h1>
      <div class="relative">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search candidates..."
          class="pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent w-64"
        >
        <svg
          class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
      </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
      <select
        v-model="filters.status"
        class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-1.5 text-sm text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
      >
        <option value="">All Statuses</option>
        <option value="applied">Applied</option>
        <option value="reviewed">Reviewed</option>
        <option value="interviewed">Interviewed</option>
        <option value="hired">Hired</option>
        <option value="rejected">Rejected</option>
      </select>
      
      <select
        v-model="filters.job"
        class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-1.5 text-sm text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
      >
        <option value="">All Jobs</option>
        <option v-for="job in jobList" :key="job.id" :value="job.id">
          {{ job.title }}
        </option>
      </select>
      
      <button
        @click="resetFilters"
        class="text-sm text-gray-400 hover:text-white ml-2"
      >
        Reset filters
      </button>
    </div>

    <!-- Candidates List -->
    <div class="gradient-card rounded-xl border border-gray-700/50 overflow-hidden">
      <div class="divide-y divide-gray-700/50">
        <div 
          v-for="candidate in filteredCandidates" 
          :key="candidate.id"
          class="p-4 hover:bg-gray-700/30 transition-colors cursor-pointer"
          @click="viewCandidate(candidate.id)"
        >
          <div class="flex items-center space-x-4">
            <img
              :src="candidate.avatar"
              :alt="candidate.name"
              class="w-12 h-12 rounded-full object-cover"
            />
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-white truncate">
                {{ candidate.name }}
              </p>
              <p class="text-sm text-gray-400 truncate">
                {{ candidate.position }}
              </p>
            </div>
            <div class="text-right">
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="statusClasses[candidate.status]"
              >
                {{ formatStatus(candidate.status) }}
              </span>
              <p class="text-xs text-gray-400 mt-1">
                Applied {{ candidate.appliedDate }}
              </p>
            </div>
          </div>
        </div>
        
        <div v-if="filteredCandidates.length === 0" class="p-8 text-center">
          <p class="text-gray-400">No candidates found matching your criteria.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
// import { useRouter } from 'vue-router';

export default {
  name: 'CandidatesList',
  
  setup() {
    const searchQuery = ref('');
    
    const filters = ref({
      status: '',
      job: ''
    });
    
    // Mock data - in a real app, this would come from an API
    const candidates = ref([
      {
        id: 1,
        name: 'Alex Johnson',
        position: 'Senior Frontend Developer',
        status: 'reviewed',
        appliedDate: '2 days ago',
        avatar: 'https://randomuser.me/api/portraits/men/32.jpg'
      },
      {
        id: 2,
        name: 'Maria Garcia',
        position: 'UX/UI Designer',
        status: 'interviewed',
        appliedDate: '1 week ago',
        avatar: 'https://randomuser.me/api/portraits/women/44.jpg'
      },
      {
        id: 3,
        name: 'James Wilson',
        position: 'Backend Developer',
        status: 'applied',
        appliedDate: '3 days ago',
        avatar: 'https://randomuser.me/api/portraits/men/22.jpg'
      },
      {
        id: 4,
        name: 'Sarah Kim',
        position: 'Product Manager',
        status: 'hired',
        appliedDate: '2 weeks ago',
        avatar: 'https://randomuser.me/api/portraits/women/28.jpg'
      },
      {
        id: 5,
        name: 'David Chen',
        position: 'DevOps Engineer',
        status: 'rejected',
        appliedDate: '5 days ago',
        avatar: 'https://randomuser.me/api/portraits/men/45.jpg'
      }
    ]);
    
    const jobList = [
      { id: 1, title: 'Senior Frontend Developer' },
      { id: 2, title: 'UX/UI Designer' },
      { id: 3, title: 'Backend Developer' },
      { id: 4, title: 'Product Manager' },
      { id: 5, title: 'DevOps Engineer' }
    ];
    
    const statusClasses = {
      applied: 'bg-blue-100 text-blue-800',
      reviewed: 'bg-yellow-100 text-yellow-800',
      interviewed: 'bg-purple-100 text-purple-800',
      hired: 'bg-green-100 text-green-800',
      rejected: 'bg-red-100 text-red-800'
    };
    
    const formatStatus = (status) => {
      const statusMap = {
        applied: 'Applied',
        reviewed: 'In Review',
        interviewed: 'Interviewed',
        hired: 'Hired',
        rejected: 'Rejected'
      };
      return statusMap[status] || status;
    };
    
    const filteredCandidates = computed(() => {
      return candidates.value.filter(candidate => {
        // Filter by search query
        const matchesSearch = 
          candidate.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          candidate.position.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        // Filter by status
        const matchesStatus = !filters.value.status || candidate.status === filters.value.status;
        
        // Filter by job (in a real app, this would check the candidate's job application)
        const matchesJob = !filters.value.job || 
          candidate.position === jobList.find(j => j.id === parseInt(filters.value.job))?.title;
        
        return matchesSearch && matchesStatus && matchesJob;
      });
    });
    
    const resetFilters = () => {
      filters.value = {
        status: '',
        job: ''
      };
      searchQuery.value = '';
    };
    
    const viewCandidate = (id) => {
      // In a real app, this would navigate to the candidate's profile
      console.log('Viewing candidate:', id);
      // router.push({ name: 'CandidateProfile', params: { id } });
    };
    
    return {
      searchQuery,
      filters,
      jobList,
      filteredCandidates,
      statusClasses,
      formatStatus,
      resetFilters,
      viewCandidate
    };
  }
};
</script>
