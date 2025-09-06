<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Conversion Funnel</h3>
      <div class="flex items-center space-x-4">
        <select 
          v-model="selectedPeriod"
          class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
          <option value="7">Last 7 days</option>
          <option value="30">Last 30 days</option>
          <option value="90">Last 90 days</option>
          <option value="365">Last year</option>
        </select>
      </div>
    </div>
    
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
    
    <div v-else>
      <template v-if="funnelData.length > 0">
        <div class="space-y-8">
        <!-- Funnel Visualization -->
        <div class="relative flex flex-col items-center">
          <template v-for="(step, index) in funnelData" :key="step.name">
            <div class="flex flex-col items-center w-full">
              <!-- Step Label -->
              <div class="flex items-center w-full max-w-xs md:max-w-md lg:max-w-2xl">
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-600"></div>
                <div class="px-4 py-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                  {{ step.name }}
                </div>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-600"></div>
              </div>
              
              <!-- Funnel Bar -->
              <div 
                class="mt-2 w-full max-w-xs md:max-w-md lg:max-w-2xl h-12 rounded-md flex items-center justify-between px-4 transition-all duration-500"
                :class="step.color || 'bg-blue-500'"
                :style="{ width: `${step.percentage}%` }"
              >
                <span class="text-white font-medium">{{ step.count.toLocaleString() }}</span>
                <span class="text-white text-opacity-80">{{ step.percentage }}%</span>
              </div>
              
              <!-- Drop Rate -->
              <div v-if="index < funnelData.length - 1" class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                {{ step.dropRate }}% drop off
                <span v-if="step.dropCount > 0" class="ml-1">
                  ({{ step.dropCount.toLocaleString() }} users)
                </span>
              </div>
              
              <!-- Connector Line -->
              <div v-if="index < funnelData.length - 1" class="h-8 w-0.5 bg-gray-200 dark:bg-gray-600"></div>
            </div>
          </template>
        </div>
      </div>
      
      <!-- Summary Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Total Visitors</p>
          <p class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ totalVisitors.toLocaleString() }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ new Date(Date.now() - (parseInt(selectedPeriod) * 24 * 60 * 60 * 1000)).toLocaleDateString() }} - {{ new Date().toLocaleDateString() }}
          </p>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Overall Conversion</p>
          <p class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ overallConversion }}%
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ funnelData[funnelData.length - 1]?.count?.toLocaleString() || 0 }} conversions
          </p>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
          <p class="text-sm text-gray-500 dark:text-gray-400">Avg. Drop Rate</p>
          <p class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ avgDropRate }}%
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            per step in the funnel
          </p>
        </div>
        </div>
      </template>
      <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
        No conversion data available for the selected period.
      </div>
      
      
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

// Prefix with underscore to indicate it's intentionally unused
const _props = defineProps({
  // In a real app, you would pass the actual data as props
  // data: {
  //   type: Object,
  //   required: true
  // }
});

const loading = ref(true);
const selectedPeriod = ref('30');

// Mock data - replace with actual API call
const fetchFunnelData = async () => {
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 800));
    
    // Mock data based on selected period
    const period = parseInt(selectedPeriod.value) || 30; // Default to 30 days if not set
    const baseCount = period * 100;
    const steps = [
      { name: 'Visited Site', count: baseCount },
      { name: 'Created Account', count: Math.round(baseCount * 0.65) },
      { name: 'Started Resume', count: Math.round(baseCount * 0.4) },
      { name: 'Completed Resume', count: Math.round(baseCount * 0.25) },
      { name: 'Downloaded/Shared', count: Math.round(baseCount * 0.15) }
    ];
    
    // Calculate percentages and drop rates
    return steps.map((step, index) => {
      const percentage = Math.round((step.count / steps[0].count) * 100);
      const prevCount = index > 0 ? steps[index - 1].count : step.count;
      const dropCount = prevCount - step.count;
      const dropRate = index > 0 ? Math.round((dropCount / prevCount) * 100) : 0;
      
      return {
        ...step,
        percentage,
        dropCount,
        dropRate,
        color: getStepColor(index).split(' ')[0]
      };
    });
  } catch (error) {
    console.error('Error fetching funnel data:', error);
    return [];
  } finally {
    loading.value = false;
  }
};

const funnelData = ref([
  { name: 'Visited Site', count: 0, percentage: 0, dropCount: 0, dropRate: 0, color: 'bg-blue-500' },
  { name: 'Created Account', count: 0, percentage: 0, dropCount: 0, dropRate: 0, color: 'bg-blue-500' },
  { name: 'Started Resume', count: 0, percentage: 0, dropCount: 0, dropRate: 0, color: 'bg-blue-500' },
  { name: 'Completed Resume', count: 0, percentage: 0, dropCount: 0, dropRate: 0, color: 'bg-blue-500' },
  { name: 'Downloaded/Shared', count: 0, percentage: 0, dropCount: 0, dropRate: 0, color: 'bg-blue-500' }
]);

// Computed properties
const totalVisitors = computed(() => {
  return funnelData.value[0]?.count || 0;
});

const overallConversion = computed(() => {
  if (!funnelData.value.length) return 0;
  const firstStep = funnelData.value[0]?.count || 0;
  const lastStep = funnelData.value[funnelData.value.length - 1]?.count || 0;
  return firstStep > 0 ? Math.round((lastStep / firstStep) * 100) : 0;
});

const avgDropRate = computed(() => {
  if (!funnelData.value.length || funnelData.value.length <= 1) return 0;
  const totalDropRates = funnelData.value.slice(0, -1).reduce((sum, step) => {
    return sum + (step.dropRate || 0);
  }, 0);
  return Math.round(totalDropRates / (funnelData.value.length - 1));
});

// Helper function to get step color
const getStepColor = (index) => {
  const colors = [
    'bg-blue-500',
    'bg-blue-400',
    'bg-blue-300',
    'bg-blue-200',
    'bg-blue-100'
  ];
  return colors[Math.min(index, colors.length - 1)];
};

// Watch for period changes
watch(selectedPeriod, async () => {
  loading.value = true;
  funnelData.value = await fetchFunnelData();
});

// Initial data load
onMounted(async () => {
  funnelData.value = await fetchFunnelData();
});
</script>

<style scoped>
.funnel-step {
  transition: all 0.3s ease;
}

.funnel-step:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
