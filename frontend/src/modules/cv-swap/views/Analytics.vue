<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-white">Analytics Dashboard</h1>
        <p class="text-gray-400 text-sm">Track your hiring performance and metrics</p>
      </div>
      
      <div class="flex items-center space-x-3">
        <select 
          v-model="selectedTimeframe"
          class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-1.5 text-sm text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
        >
          <option v-for="timeframe in timeframes" :key="timeframe.value" :value="timeframe.value">
            {{ timeframe.label }}
          </option>
        </select>
        
        <button 
          @click="exportAnalytics"
          class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg flex items-center"
        >
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Export
        </button>
      </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div 
        v-for="stat in stats" 
        :key="stat.label"
        class="gradient-card p-5 rounded-xl border border-gray-700/50"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-400">{{ stat.label }}</p>
            <p class="text-2xl font-bold text-white mt-1">{{ stat.value }}</p>
          </div>
          <div 
            class="p-3 rounded-lg"
            :class="stat.trend > 0 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'"
          >
            <svg 
              class="w-5 h-5" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
              :class="{ 'rotate-180': stat.trend < 0 }"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
          </div>
        </div>
        <p class="text-xs mt-2" :class="stat.trend > 0 ? 'text-green-400' : 'text-red-400'">
          {{ Math.abs(stat.trend) }}% {{ stat.trend > 0 ? 'increase' : 'decrease' }} from last period
        </p>
      </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Applications Chart -->
      <div class="lg:col-span-2 gradient-card p-5 rounded-xl border border-gray-700/50">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-white">Applications Overview</h3>
          <div class="flex space-x-2">
            <button 
              v-for="tab in applicationTabs" 
              :key="tab"
              @click="activeApplicationTab = tab"
              class="px-3 py-1 text-sm rounded-lg transition-colors"
              :class="activeApplicationTab === tab 
                ? 'bg-purple-600 text-white' 
                : 'text-gray-400 hover:bg-gray-700/50'"
            >
              {{ tab }}
            </button>
          </div>
        </div>
        <div class="h-64">
          <!-- Chart would be rendered here -->
          <div class="w-full h-full flex items-center justify-center bg-gray-800/30 rounded-lg">
            <p class="text-gray-500">Applications chart for {{ activeApplicationTab.toLowerCase() }} would be displayed here</p>
          </div>
        </div>
      </div>
      
      <!-- Pipeline Status -->
      <div class="gradient-card p-5 rounded-xl border border-gray-700/50">
        <h3 class="text-lg font-semibold text-white mb-4">Pipeline Status</h3>
        <div class="space-y-4">
          <div v-for="stage in pipelineStages" :key="stage.label" class="space-y-1.5">
            <div class="flex justify-between text-sm">
              <span class="text-gray-400">{{ stage.label }}</span>
              <span class="text-white font-medium">{{ stage.count }}</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2">
              <div 
                class="h-full rounded-full" 
                :class="stage.color"
                :style="{ width: (stage.count / pipelineStages[0].count * 100) + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Sources -->
      <div class="gradient-card p-5 rounded-xl border border-gray-700/50">
        <h3 class="text-lg font-semibold text-white mb-4">Candidate Sources</h3>
        <div class="h-64">
          <!-- Chart would be rendered here -->
          <div class="w-full h-full flex items-center justify-center bg-gray-800/30 rounded-lg">
            <p class="text-gray-500">Pie chart of candidate sources would be displayed here</p>
          </div>
        </div>
      </div>
      
      <!-- Recent Activity -->
      <div class="gradient-card p-5 rounded-xl border border-gray-700/50">
        <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
        <div class="space-y-4">
          <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="w-8 h-8 rounded-full flex items-center justify-center" :class="activity.bgColor">
                <component :is="activity.icon" class="w-4 h-4 text-white" />
              </div>
            </div>
            <div class="ml-3">
              <p class="text-sm text-white">
                <span class="font-medium">{{ activity.user }}</span> {{ activity.action }}
                <span class="font-medium">{{ activity.target }}</span>
              </p>
              <p class="text-xs text-gray-400">{{ activity.time }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { 
  UserGroupIcon, 
  DocumentCheckIcon, 
  ClockIcon, 
  StarIcon,
  UserPlusIcon,
  DocumentTextIcon,
  ChatBubbleLeftRightIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline';

export default {
  name: 'AnalyticsDashboard',
  components: {
    UserGroupIcon,
    DocumentCheckIcon,
    ClockIcon,
    StarIcon,
    UserPlusIcon,
    DocumentTextIcon,
    ChatBubbleLeftRightIcon,
    XMarkIcon
  },
  
  setup() {
    const selectedTimeframe = ref('month');
    const activeApplicationTab = ref('Weekly');
    
    const timeframes = [
      { value: 'week', label: 'Last 7 days' },
      { value: 'month', label: 'This month' },
      { value: 'quarter', label: 'This quarter' },
      { value: 'year', label: 'This year' }
    ];
    
    const applicationTabs = ['Daily', 'Weekly', 'Monthly'];
    
    const stats = ref([
      { label: 'Total Applications', value: '1,248', trend: 12.5 },
      { label: 'Interview Rate', value: '24%', trend: 5.2 },
      { label: 'Hire Rate', value: '8.5%', trend: -2.3 },
      { label: 'Avg. Time to Hire', value: '14 days', trend: -8.7 }
    ]);
    
    const pipelineStages = ref([
      { label: 'Applied', count: 1248, color: 'bg-blue-500' },
      { label: 'Screening', count: 845, color: 'bg-purple-500' },
      { label: 'Interview', count: 523, color: 'bg-yellow-500' },
      { label: 'Offer', count: 124, color: 'bg-green-500' },
      { label: 'Hired', count: 89, color: 'bg-green-700' }
    ]);
    
    const recentActivities = ref([
      {
        id: 1,
        user: 'Sarah Wilson',
        action: 'added a new candidate',
        target: 'Alex Johnson',
        time: '10 minutes ago',
        icon: 'UserPlusIcon',
        bgColor: 'bg-blue-500'
      },
      {
        id: 2,
        user: 'Michael Brown',
        action: 'rejected application from',
        target: 'Emily Davis',
        time: '1 hour ago',
        icon: 'XMarkIcon',
        bgColor: 'bg-red-500'
      },
      {
        id: 3,
        user: 'David Lee',
        action: 'scheduled an interview with',
        target: 'Robert Taylor',
        time: '2 hours ago',
        icon: 'ChatBubbleLeftRightIcon',
        bgColor: 'bg-yellow-500'
      },
      {
        id: 4,
        user: 'Lisa Wong',
        action: 'moved',
        target: 'Jennifer Kim',
        time: '3 hours ago',
        icon: 'DocumentTextIcon',
        bgColor: 'bg-green-500',
        actionSuffix: 'to the next stage'
      }
    ]);
    
    const exportAnalytics = () => {
      console.log('Exporting analytics data...');
      // In a real app, this would trigger a file download
    };
    
    return {
      selectedTimeframe,
      activeApplicationTab,
      timeframes,
      applicationTabs,
      stats,
      pipelineStages,
      recentActivities,
      exportAnalytics
    };
  }
};
</script>
