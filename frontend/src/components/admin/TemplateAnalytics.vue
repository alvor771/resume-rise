<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">Template Analytics</h3>
      <div class="flex items-center space-x-4">
        <select 
          v-model="selectedPeriod"
          class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
          <option value="7">Last 7 days</option>
          <option value="30">Last 30 days</option>
          <option value="90">Last 90 days</option>
        </select>
      </div>
    </div>
    
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="template in templates" 
        :key="template.id" 
        class="border rounded-lg p-4 hover:shadow-md transition-shadow"
        :class="{ 'border-blue-200 dark:border-blue-800': template.isPopular }"
      >
        <div class="flex items-start justify-between">
          <div>
            <h4 class="font-medium text-gray-900 dark:text-white">{{ template.name }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ template.category }}</p>
          </div>
          <div class="p-2 rounded-full" :class="template.bgColor">
            <component :is="template.icon" class="h-5 w-5 text-white" />
          </div>
        </div>
        
        <div class="mt-4">
          <div class="flex items-center justify-between text-sm mb-1">
            <span class="text-gray-500 dark:text-gray-400">Usage</span>
            <span class="font-medium">{{ template.usage }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div 
              class="h-2 rounded-full transition-all duration-500" 
              :class="template.progressColor"
              :style="{ width: `${template.usage}%` }"
            ></div>
          </div>
          
          <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div>
              <p class="text-gray-500 dark:text-gray-400">Completions</p>
              <p class="font-medium">{{ template.completions.toLocaleString() }}</p>
            </div>
            <div>
              <p class="text-gray-500 dark:text-gray-400">Avg. Time</p>
              <p class="font-medium">{{ template.avgTime }} min</p>
            </div>
            <div>
              <p class="text-gray-500 dark:text-gray-400">Success Rate</p>
              <p class="font-medium">{{ template.successRate }}%</p>
            </div>
            <div>
              <p class="text-gray-500 dark:text-gray-400">Trend</p>
              <div class="flex items-center">
                <span 
                  class="mr-1"
                  :class="template.trend > 0 ? 'text-green-500' : 'text-red-500'"
                >
                  {{ template.trend > 0 ? '↑' : '↓' }}
                </span>
                <span :class="template.trend > 0 ? 'text-green-500' : 'text-red-500'">
                  {{ Math.abs(template.trend) }}%
                </span>
              </div>
            </div>
          </div>
          
          <div v-if="template.tags && template.tags.length > 0" class="mt-3 flex flex-wrap gap-1">
            <span 
              v-for="tag in template.tags" 
              :key="tag"
              class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
              :class="getTagClasses(tag)"
            >
              {{ tag }}
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-6 flex justify-end">
      <button 
        @click="exportAnalytics"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export Data
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { 
  BriefcaseIcon, 
  LightBulbIcon, 
  DocumentTextIcon, 
  UserGroupIcon, 
  AcademicCapIcon, 
  CodeBracketIcon 
} from '@heroicons/vue/24/outline';

// Prefix with underscore to indicate it's intentionally unused
const _props = defineProps({
  // In a real app, you would pass the actual data as props
  // data: {
  //   type: Array,
  //   required: true
  // }
});

const loading = ref(true);
const selectedPeriod = ref('30');
const templates = ref([]);

// Fetch template analytics data
const fetchTemplateAnalytics = async () => {
  try {
    loading.value = true;
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 800));
    
    // Mock data
    return [
      {
        id: 1,
        name: 'Professional',
        category: 'Modern',
        usage: 45,
        completions: 1245,
        avgTime: 12,
        successRate: 92,
        trend: 5.2,
        icon: BriefcaseIcon,
        bgColor: 'bg-blue-500',
        progressColor: 'bg-blue-500',
        isPopular: true,
        tags: ['Popular', 'Featured']
      },
      {
        id: 2,
        name: 'Creative',
        category: 'Modern',
        usage: 32,
        completions: 876,
        avgTime: 18,
        successRate: 88,
        trend: 2.1,
        icon: LightBulbIcon,
        bgColor: 'bg-purple-500',
        progressColor: 'bg-purple-500',
        tags: ['New']
      },
      {
        id: 3,
        name: 'Minimal',
        category: 'Simple',
        usage: 23,
        completions: 632,
        avgTime: 8,
        successRate: 95,
        trend: 3.7,
        icon: DocumentTextIcon,
        bgColor: 'bg-green-500',
        progressColor: 'bg-green-500',
        tags: ['Fast']
      },
      {
        id: 4,
        name: 'Executive',
        category: 'Classic',
        usage: 28,
        completions: 765,
        avgTime: 15,
        successRate: 90,
        trend: -1.2,
        icon: UserGroupIcon,
        bgColor: 'bg-indigo-500',
        progressColor: 'bg-indigo-500',
        tags: []
      },
      {
        id: 5,
        name: 'Academic',
        category: 'Formal',
        usage: 19,
        completions: 521,
        avgTime: 20,
        successRate: 85,
        trend: 0.5,
        icon: AcademicCapIcon,
        bgColor: 'bg-yellow-500',
        progressColor: 'bg-yellow-500',
        tags: ['Formal']
      },
      {
        id: 6,
        name: 'Developer',
        category: 'Modern',
        usage: 37,
        completions: 1012,
        avgTime: 14,
        successRate: 94,
        trend: 7.3,
        icon: CodeBracketIcon,
        bgColor: 'bg-red-500',
        progressColor: 'bg-red-500',
        isPopular: true,
        tags: ['Popular', 'Tech']
      }
    ];
  } catch (error) {
    console.error('Error fetching template analytics:', error);
    return [];
  } finally {
    loading.value = false;
  }
};

// Get tag classes based on tag type
const getTagClasses = (tag) => {
  const tagClasses = {
    'Popular': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    'Featured': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    'New': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    'Fast': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'Formal': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
    'Tech': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200'
  };
  
  return tagClasses[tag] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
};

// Export analytics data
const exportAnalytics = () => {
  // In a real app, this would trigger a file download
  console.log('Exporting template analytics data...');
  // You would typically use a library like xlsx or csv-export here
  alert('Export functionality would be implemented here');
};

// Watch for period changes
watch(selectedPeriod, async () => {
  templates.value = await fetchTemplateAnalytics();
});

// Initial data load
onMounted(async () => {
  templates.value = await fetchTemplateAnalytics();
});
</script>

<style scoped>
.template-card {
  transition: all 0.2s ease-in-out;
}

.template-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
