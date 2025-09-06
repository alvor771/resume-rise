<template>
  <StaticPageLayout>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-3">
        <!-- Status Overview -->
        <section id="status-overview" class="mb-12">
          <div class="flex items-center justify-between mb-6">
            <h2>{{ $t('static.apiStatus.sections.overview.title') }}</h2>
            <div class="flex items-center">
              <span class="relative flex h-3 w-3 mr-2">
                <span 
                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"
                  :class="{
                    'bg-green-400': isAllOperational,
                    'bg-yellow-400': !isAllOperational && !isMajorOutage,
                    'bg-red-400': isMajorOutage
                  }"
                ></span>
                <span 
                  class="relative inline-flex rounded-full h-3 w-3"
                  :class="{
                    'bg-green-500': isAllOperational,
                    'bg-yellow-500': !isAllOperational && !isMajorOutage,
                    'bg-red-500': isMajorOutage
                  }"
                ></span>
              </span>
              <span class="text-sm font-medium">
                {{ currentStatus }}
              </span>
            </div>
          </div>
          
          <p class="mb-6">{{ $t('static.apiStatus.sections.overview.description') }}</p>
          
          <!-- Status Cards -->
          <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div 
              v-for="(service, index) in services" 
              :key="index"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
            >
              <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ service.name }}</h3>
                  <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': service.status === 'operational',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': service.status === 'degraded',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': service.status === 'outage'
                    }"
                  >
                    {{ getStatusText(service.status) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ service.description }}</p>
                
                <!-- Status History -->
                <div class="mt-4">
                  <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    {{ $t('static.apiStatus.statusHistory') }}
                  </h4>
                  <div class="flex space-x-1">
                    <div 
                      v-for="(day, dayIndex) in 14" 
                      :key="dayIndex"
                      class="h-8 flex-1 rounded-sm"
                      :class="{
                        'bg-green-500': getRandomStatus() === 'operational',
                        'bg-yellow-500': getRandomStatus() === 'degraded',
                        'bg-red-500': getRandomStatus() === 'outage'
                      }"
                      :title="`${getRandomDate(dayIndex)}: ${getStatusText(getRandomStatus())}`"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Last Updated -->
          <div class="text-sm text-gray-500 dark:text-gray-400 mb-8">
            {{ $t('static.apiStatus.lastUpdated') }}: {{ currentTime }}
          </div>
        </section>

        <!-- Incident History -->
        <section id="incident-history" class="mb-12">
          <h2 class="mb-6">{{ $t('static.apiStatus.sections.incidents.title') }}</h2>
          
          <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
              <div 
                v-for="(incident, index) in incidents" 
                :key="index"
                class="p-5"
              >
                <div class="flex justify-between items-start">
                  <div>
                    <h3 class="text-base font-medium text-gray-900 dark:text-white">{{ incident.title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                      {{ $t('static.apiStatus.impact') }}: 
                      <span class="font-medium" :class="{
                        'text-green-600 dark:text-green-400': incident.impact === 'none',
                        'text-yellow-600 dark:text-yellow-400': incident.impact === 'minor',
                        'text-orange-600 dark:text-orange-400': incident.impact === 'major',
                        'text-red-600 dark:text-red-400': incident.impact === 'critical'
                      }">
                        {{ formatImpact(incident.impact) }}
                      </span>
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm text-gray-900 dark:text-white">{{ formatDate(incident.date) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatTime(incident.date) }}</p>
                  </div>
                </div>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">{{ incident.description }}</p>
                <div class="mt-3 flex items-center text-sm text-indigo-600 dark:text-indigo-400">
                  <span>{{ $t('static.apiStatus.viewDetails') }}</span>
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </div>
              </div>
              
              <div v-if="incidents.length === 0" class="p-5 text-center">
                <p class="text-gray-500 dark:text-gray-400">{{ $t('static.apiStatus.noIncidents') }}</p>
              </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-800 px-5 py-3 border-t border-gray-200 dark:border-gray-700 text-right">
              <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                {{ $t('static.apiStatus.viewAllIncidents') }}
              </a>
            </div>
          </div>
        </section>

        <!-- API Documentation -->
        <section id="api-documentation" class="mb-12">
          <h2 class="mb-6">{{ $t('static.apiStatus.sections.documentation.title') }}</h2>
          <p class="mb-6">{{ $t('static.apiStatus.sections.documentation.description') }}</p>
          
          <div class="grid md:grid-cols-2 gap-6">
            <a 
              v-for="(doc, index) in apiDocumentation" 
              :key="index"
              :href="doc.link"
              class="group block p-5 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors"
            >
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900 p-3 rounded-md">
                  <component :is="doc.icon" class="w-6 h-6 text-indigo-600 dark:text-indigo-300" />
                </div>
                <div class="ml-4">
                  <h3 class="text-base font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                    {{ doc.title }}
                  </h3>
                  <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ doc.description }}</p>
                </div>
              </div>
            </a>
          </div>
        </section>

        <!-- System Metrics -->
        <section id="system-metrics" class="mb-12">
          <h2 class="mb-6">{{ $t('static.apiStatus.sections.metrics.title') }}</h2>
          
          <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <div class="grid md:grid-cols-3 gap-6 mb-6">
              <div v-for="(metric, index) in metrics" :key="index" class="text-center">
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ metric.value }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ metric.label }}</p>
              </div>
            </div>
            
            <!-- Response Time Chart (Placeholder) -->
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center">
              <p class="text-gray-500 dark:text-gray-400">{{ $t('static.apiStatus.responseTimeChart') }}</p>
            </div>
            
            <div class="mt-4 flex justify-between text-xs text-gray-500 dark:text-gray-400">
              <span>{{ $t('static.apiStatus.last24Hours') }}</span>
              <span>{{ $t('static.apiStatus.avgResponseTime', { time: '125ms' }) }}</span>
            </div>
          </div>
        </section>
      </div>

      <!-- Table of Contents -->
      <div class="hidden lg:block">
        <TableOfContents :items="tocItems" />
      </div>
    </div>
  </StaticPageLayout>
</template>

<script>
import { defineComponent, ref, computed, onMounted, onUnmounted } from 'vue';
import StaticPageLayout from '@/layouts/StaticPageLayout.vue';
import TableOfContents from '@/components/static/TableOfContents.vue';

export default defineComponent({
  name: 'APIStatusView',
  components: {
    StaticPageLayout,
    TableOfContents
  },
  setup() {
    const currentTime = ref('');
    
    // Update current time every minute
    const updateTime = () => {
      const now = new Date();
      currentTime.value = now.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZoneName: 'short'
      });
    };
    
    // Initial time set
    updateTime();
    
    // Set up interval to update time
    let timeInterval;
    onMounted(() => {
      timeInterval = setInterval(updateTime, 60000);
    });
    
    // Clean up interval on component unmount
    onUnmounted(() => {
      if (timeInterval) clearInterval(timeInterval);
    });
    
    // Sample data
    const services = [
      {
        name: 'API',
        description: 'Core API service for all requests',
        status: 'operational'
      },
      {
        name: 'Authentication',
        description: 'User authentication and authorization',
        status: 'operational'
      },
      {
        name: 'Database',
        description: 'Primary database cluster',
        status: 'operational'
      },
      {
        name: 'File Storage',
        description: 'File upload and storage service',
        status: 'degraded'
      },
      {
        name: 'Email Service',
        description: 'Transactional and notification emails',
        status: 'operational'
      },
      {
        name: 'Background Jobs',
        description: 'Asynchronous job processing',
        status: 'operational'
      }
    ];
    
    const incidents = [
      {
        title: 'Increased latency in file uploads',
        description: 'We are currently experiencing higher than normal latency for file uploads. Our team is investigating the issue.',
        impact: 'minor',
        date: new Date(Date.now() - 1000 * 60 * 60 * 2) // 2 hours ago
      },
      {
        title: 'Scheduled maintenance completed',
        description: 'The scheduled maintenance has been completed and all systems are operational.',
        impact: 'none',
        date: new Date(Date.now() - 1000 * 60 * 60 * 24) // 1 day ago
      }
    ];
    
    const apiDocumentation = [
      {
        title: 'API Reference',
        description: 'Complete reference for all API endpoints and parameters',
        link: '/documentation/api-reference',
        icon: 'DocumentTextIcon'
      },
      {
        title: 'Authentication',
        description: 'Learn how to authenticate with our API',
        link: '/documentation/authentication',
        icon: 'LockClosedIcon'
      },
      {
        title: 'Rate Limiting',
        description: 'Understand our rate limiting policies',
        link: '/documentation/rate-limiting',
        icon: 'ClockIcon'
      },
      {
        title: 'Webhooks',
        description: 'Set up and manage webhook integrations',
        link: '/documentation/webhooks',
        icon: 'LinkIcon'
      }
    ];
    
    const metrics = [
      { value: '99.98%', label: 'Uptime (30 days)' },
      { value: '125ms', label: 'Avg. Response Time' },
      { value: '0.002%', label: 'Error Rate' }
    ];
    
    // Computed properties
    const isAllOperational = computed(() => {
      return services.every(service => service.status === 'operational');
    });
    
    const isMajorOutage = computed(() => {
      return services.some(service => service.status === 'outage');
    });
    
    const currentStatus = computed(() => {
      if (isAllOperational.value) return 'All Systems Operational';
      if (isMajorOutage.value) return 'Major Outage';
      return 'Partial Outage';
    });
    
    // Helper methods
    const getStatusText = (status) => {
      const statusMap = {
        'operational': 'Operational',
        'degraded': 'Degraded',
        'outage': 'Outage'
      };
      return statusMap[status] || status;
    };
    
    const formatImpact = (impact) => {
      const impactMap = {
        'none': 'No Impact',
        'minor': 'Minor Impact',
        'major': 'Major Impact',
        'critical': 'Critical Impact'
      };
      return impactMap[impact] || impact;
    };
    
    const formatDate = (date) => {
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
      });
    };
    
    const formatTime = (date) => {
      return date.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true
      });
    };
    
    const getRandomStatus = () => {
      const statuses = ['operational', 'operational', 'operational', 'degraded', 'outage'];
      return statuses[Math.floor(Math.random() * statuses.length)];
    };
    
    const getRandomDate = (daysAgo) => {
      const date = new Date();
      date.setDate(date.getDate() - daysAgo);
      return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    };
    
    // Table of contents
    const tocItems = [
      { id: 'status-overview', text: 'Status Overview' },
      { id: 'incident-history', text: 'Incident History' },
      { id: 'api-documentation', text: 'API Documentation' },
      { id: 'system-metrics', text: 'System Metrics' }
    ];
    
    return {
      services,
      incidents,
      apiDocumentation,
      metrics,
      currentTime,
      isAllOperational,
      isMajorOutage,
      currentStatus,
      tocItems,
      getStatusText,
      formatImpact,
      formatDate,
      formatTime,
      getRandomStatus,
      getRandomDate
    };
  }
});
</script>
