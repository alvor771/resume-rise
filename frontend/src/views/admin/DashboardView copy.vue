<template>
  <div class="min-h-screen transition-colors duration-300 bg-gray-50 dark:bg-gray-900">
    <!-- Dark mode toggle -->
    <button 
      @click="toggleDarkMode"
      class="fixed bottom-6 right-6 z-50 p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110"
      :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    >
      <svg v-if="isDarkMode" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      <svg v-else class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
      </svg>
    </button>
    
    <!-- Notification Center -->
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full">
      <transition-group name="notification">
        <div 
          v-for="notification in notifications" 
          :key="notification.id"
          class="mb-2 p-4 rounded-lg shadow-lg transition-all duration-300 transform"
          :class="{
            'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
            'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
            'bg-blue-100 border-l-4 border-blue-500': notification.type === 'info',
            'translate-x-0': !notification.leaving,
            'translate-x-full': notification.leaving
          }"
        >
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <component 
                :is="notificationIcons[notification.type]" 
                class="h-5 w-5" 
                :class="{
                  'text-green-500': notification.type === 'success',
                  'text-red-500': notification.type === 'error',
                  'text-blue-500': notification.type === 'info'
                }"
              />
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
              <p class="text-sm text-gray-600">{{ notification.message }}</p>
            </div>
            <div class="ml-auto pl-3">
              <button 
                @click="removeNotification(notification.id)"
                class="text-gray-400 hover:text-gray-500 focus:outline-none"
              >
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </transition-group>
    </div>
    </div>
    <div v-if="error" class="rounded-md bg-red-50 p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <XCircleIcon class="h-5 w-5 text-red-400" />
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Error loading dashboard data</h3>
          <p class="mt-2 text-sm text-red-700">{{ error }}</p>
          <div class="mt-4">
            <button
              @click="refreshDashboard"
              class="rounded-md bg-red-50 text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
            >
              Retry
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-700 dark:to-purple-800 rounded-lg p-6 mb-6 shadow-lg">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="text-white">
          <div class="flex items-center">
            <h2 class="text-2xl font-bold">Welcome back, Admin! 👋</h2>
            <span class="ml-2 px-2 py-1 text-xs bg-white/20 rounded-full animate-pulse">Live</span>
          </div>
          <p class="mt-1 text-indigo-100">Here's what's happening with your application today</p>
          <div class="mt-3 flex items-center space-x-2 text-sm">
            <span class="flex items-center">
              <span class="w-2 h-2 rounded-full bg-green-400 mr-1 animate-pulse"></span>
              <span>System operational</span>
            </span>
            <span class="text-indigo-200">•</span>
            <span>{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
          </div>
        </div>
        <div class="mt-4 md:mt-0">
          <button 
            @click="refreshDashboard"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
          </button>
        </div>
      </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
      <!-- Welcome Banner -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-700 dark:to-purple-800 rounded-lg p-6 mb-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div class="text-white">
            <div class="flex items-center">
              <h2 class="text-2xl font-bold">Welcome back, Admin! 👋</h2>
              <span class="ml-2 px-2 py-1 text-xs bg-white/20 rounded-full animate-pulse">Live</span>
            </div>
            <p class="mt-1 text-indigo-100">Here's what's happening with your application today</p>
            <div class="mt-3 flex items-center space-x-2 text-sm">
              <span class="flex items-center">
                <span class="w-2 h-2 rounded-full bg-green-400 mr-1 animate-pulse"></span>
                <span>System operational</span>
              </span>
              <span class="text-indigo-200">•</span>
              <span>{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
            </div>
          </div>
          <div class="mt-4 md:mt-0">
            <button 
              @click="refreshDashboard"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Refresh
            </button>
          </div>
        </div>
      </div>

      <!-- Stats Overview -->
      <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Overview</h3>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <router-link
        v-for="(stat, index) in stats"
        :key="index"
        :to="stat.route"
        class="block p-5 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow duration-200"
        :class="{
          'border-t-4 border-indigo-500': index === 0,
          'border-t-4 border-blue-500': index === 1,
          'border-t-4 border-green-500': index === 2,
          'border-t-4 border-yellow-500': index === 3
        }"
      >
        <div class="flex items-center">
          <div 
            class="p-3 rounded-full text-white" 
            :class="{
              'bg-indigo-500': index === 0,
              'bg-blue-500': index === 1,
              'bg-green-500': index === 2,
              'bg-yellow-500': index === 3
            }"
          >
            <component :is="stat.icon" class="w-6 h-6" />
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300 truncate">
              {{ stat.title }}
            </p>
            <p class="text-2xl font-semibold text-gray-700 dark:text-white">
              {{ stat.value }}
              <span 
                v-if="stat.change" 
                class="text-sm ml-1" 
                :class="stat.changeType === 'increase' ? 'text-green-500' : 'text-red-500'"
              >
                {{ stat.change }}
                <svg 
                  v-if="stat.changeType === 'increase'"
                  class="w-4 h-4 inline" 
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <svg 
                  v-else
                  class="w-4 h-4 inline" 
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
              </span>
            </p>
          </div>
        </div>
      </router-link>
    </div>

    <!-- Charts and Activity -->
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
      <!-- Users Chart -->
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
              Users Overview
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Last 7 days</p>
          </div>
          <div class="flex items-center space-x-2">
            <select 
              v-model="selectedPeriod"
              @change="fetchChartData"
              class="mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
            >
              <option v-for="period in timePeriods" :key="period.value" :value="period.value">
                {{ period.label }}
              </option>
            </select>
            <button 
              @click="fetchChartData"
              class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
              :class="{'animate-spin': isLoading}"
              :disabled="isLoading"
            >
              <RefreshIcon class="h-5 w-5" />
            </button>
          </div>
        </div>
        <div class="h-64">
          <Line 
            v-if="!isLoading && chartData"
            :data="chartData"
            :options="chartOptions"
            class="h-full w-full"
          />
          <div v-else class="h-full flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-md">
            <div class="text-center">
              <div class="animate-pulse">
                <div class="h-12 w-12 mx-auto bg-gray-200 dark:bg-gray-600 rounded-full"></div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                  Loading chart data...
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Recent Activity -->
      <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            Recent Activity
          </h3>
          <router-link 
            to="/admin/activity-log" 
            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
          >
            View all
          </router-link>
        </div>
        <div class="space-y-4">
          <div 
            v-for="(activity, index) in recentActivities" 
            :key="index"
            class="flex items-start"
          >
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
              <component :is="activity.icon" class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ activity.title }}
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ activity.description }}
              </p>
              <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                {{ formatTimeAgo(activity.timestamp) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Quick Actions -->
    <!-- Activity Heatmap -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User Activity Heatmap</h3>
      <div class="overflow-x-auto">
        <div class="grid grid-flow-col grid-rows-7 gap-1">
          <div v-for="(week, weekIndex) in activityHeatmap" :key="weekIndex" class="grid grid-rows-7 gap-1">
            <div 
              v-for="(day, dayIndex) in week" 
              :key="`${weekIndex}-${dayIndex}`"
              class="w-4 h-4 rounded-sm"
              :class="{
                'bg-green-100 dark:bg-green-900': day.level === 0,
                'bg-green-300 dark:bg-green-700': day.level === 1,
                'bg-green-500 dark:bg-green-500': day.level === 2,
                'bg-green-700 dark:bg-green-400': day.level === 3,
                'bg-green-900 dark:bg-green-300': day.level === 4,
                'opacity-30': day.date > new Date()
              }"
              :title="day.date.toLocaleDateString() + ': ' + (day.count || 'No') + ' activities'"
            ></div>
          </div>
        </div>
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
          <span>Mon</span>
          <span>Wed</span>
          <span>Fri</span>
          <span>Sun</span>
        </div>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <div 
        v-for="(action, index) in quickActions" 
        :key="index"
        class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200"
      >
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
              <component :is="action.icon" class="h-6 w-6 text-white" />
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                {{ action.title }}
              </dt>
              <dd class="flex items-baseline">
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                  {{ action.value }}
                </p>
              </dd>
            </div>
          </div>
          <div class="mt-4">
            <button
              @click="handleQuickAction(action.action)"
              class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
            >
              {{ action.buttonText }}
              <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import WorldMap from '@/components/admin/WorldMap.vue';
import ConversionFunnel from '@/components/admin/ConversionFunnel.vue';
import TemplateAnalytics from '@/components/admin/TemplateAnalytics.vue';
import { 
  XCircleIcon, 
  ArrowUpIcon, 
  ArrowDownIcon, 
  RefreshIcon
} from '@heroicons/vue/24/outline';
import { Bar, Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement
} from 'chart.js';

// Register ChartJS components
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement
);

export default {
  name: 'DashboardView',
  components: {
    XCircleIcon,
    Bar,
    Line,
    ArrowUpIcon,
    ArrowDownIcon,
    RefreshIcon,
    WorldMap,
    ConversionFunnel,
    TemplateAnalytics
  },
  setup() {
    const router = useRouter();
    const isLoading = ref(true);
    const error = ref(null);
    const selectedPeriod = ref('week');
    const chartData = ref(null);
    const isDarkMode = ref(localStorage.getItem('darkMode') === 'true');
    const notifications = ref([]);
    let notificationId = 0;
    let socket = null;
    
    // Notification icons
    const notificationIcons = {
      success: 'CheckCircleIcon',
      error: 'XCircleIcon',
      info: 'InformationCircleIcon'
    };
    
    // Toggle dark mode
    const toggleDarkMode = () => {
      isDarkMode.value = !isDarkMode.value;
      localStorage.setItem('darkMode', isDarkMode.value);
      if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    };
    
    // Initialize dark mode
    onMounted(() => {
      if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
      }
    });
    
    // Activity heatmap data (mock data - replace with real data)
    const activityHeatmap = computed(() => {
      const weeks = 12;
      const daysPerWeek = 7;
      const today = new Date();
      const startDate = new Date(today);
      startDate.setDate(today.getDate() - (weeks * daysPerWeek));
      
      const heatmap = [];
      for (let week = 0; week < weeks; week++) {
        const weekData = [];
        for (let day = 0; day < daysPerWeek; day++) {
          const currentDate = new Date(startDate);
          currentDate.setDate(startDate.getDate() + (week * daysPerWeek + day));
          
          // Generate random activity level (0-4)
          const level = Math.floor(Math.random() * 5);
          
          weekData.push({
            date: new Date(currentDate),
            count: level === 0 ? 0 : Math.floor(Math.random() * 100) + 1,
            level: level
          });
        }
        heatmap.push(weekData);
      }
      return heatmap;
    });
    
    // Format time ago function is already defined elsewhere in the file

    // Template for the chart tooltip
    const getOrCreateTooltip = (chart) => {
      let tooltipEl = chart.canvas.parentNode.querySelector('div');

      if (!tooltipEl) {
        tooltipEl = document.createElement('div');
        tooltipEl.style.background = 'rgba(0, 0, 0, 0.7)';
        tooltipEl.style.borderRadius = '3px';
        tooltipEl.style.color = 'white';
        tooltipEl.style.opacity = 1;
        tooltipEl.style.pointerEvents = 'none';
        tooltipEl.style.position = 'absolute';
        tooltipEl.style.transform = 'translate(-50%, 0)';
        tooltipEl.style.transition = 'all .1s ease';

        const table = document.createElement('table');
        table.style.margin = '0px';

        tooltipEl.appendChild(table);
        chart.canvas.parentNode.appendChild(tooltipEl);
      }

      return tooltipEl;
    };
    
    // Add notification
    const addNotification = (type, title, message, duration = 5000) => {
      const id = notificationId++;
      const notification = {
        id,
        type,
        title,
        message,
        leaving: false
      };
      
      notifications.value.push(notification);
      
      // Auto-remove notification
      if (duration > 0) {
        setTimeout(() => {
          removeNotification(id);
        }, duration);
      }
      
      return id;
    };
    
    // Remove notification
    const removeNotification = (id) => {
      const index = notifications.value.findIndex(n => n.id === id);
      if (index !== -1) {
        // Trigger leave animation
        notifications.value[index].leaving = true;
        
        // Remove after animation completes
        setTimeout(() => {
          notifications.value = notifications.value.filter(n => n.id !== id);
        }, 300);
      }
    };
    
    // Simulate real-time notifications
    const simulateRealtimeUpdates = () => {
      const notificationTypes = [
        { 
          type: 'info', 
          title: 'New user registered', 
          message: 'A new user just signed up on the platform.' 
        },
        { 
          type: 'success', 
          title: 'Resume generated', 
          message: 'A user successfully generated a new resume.' 
        },
        { 
          type: 'info', 
          title: 'Template updated', 
          message: 'A template was recently updated by an admin.' 
        }
      ];
      
      // Initial notification
      setTimeout(() => {
        addNotification('info', 'Dashboard updated', 'Your dashboard has been updated with the latest data.');
      }, 2000);
      
      // Random notifications
      setInterval(() => {
        if (Math.random() > 0.7) { // 30% chance of notification
          const notif = notificationTypes[Math.floor(Math.random() * notificationTypes.length)];
          addNotification(notif.type, notif.title, notif.message);
        }
      }, 30000); // Check every 30 seconds
    };
    
    // Initialize WebSocket connection (mock implementation)
    const initWebSocket = () => {
      // In a real app, you would connect to your WebSocket server here
      // socket = new WebSocket('wss://your-api-url/ws');
      // socket.onmessage = (event) => {
      //   const data = JSON.parse(event.data);
      //   handleWebSocketMessage(data);
      // };
      
      // For demo purposes, we'll simulate WebSocket messages
      simulateRealtimeUpdates();
    };
    
    // Handle WebSocket messages
    const handleWebSocketMessage = (data) => {
      // Handle different types of WebSocket messages
      switch (data.type) {
        case 'stats_update':
          // Update stats with new data
          break;
        case 'new_activity':
          // Add new activity
          break;
        case 'notification':
          addNotification(data.notification.type, data.notification.title, data.notification.message);
          break;
      }
    };
    
    // Time periods for filtering
    const timePeriods = [
      { value: 'day', label: 'Today' },
      { value: 'week', label: 'This Week' },
      { value: 'month', label: 'This Month' },
      { value: 'year', label: 'This Year' },
      { value: 'all', label: 'All Time' }
    ];
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: '#6B7280', // gray-500
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          backgroundColor: '#1F2937', // gray-800
          titleColor: '#F9FAFB', // gray-50
          bodyColor: '#E5E7EB', // gray-200
          borderColor: '#4B5563', // gray-600
          borderWidth: 1,
          padding: 12,
          cornerRadius: 6,
          displayColors: true,
          mode: 'index',
          intersect: false
        }
      },
      scales: {
        x: {
          grid: {
            display: false
          },
          ticks: {
            color: '#6B7280' // gray-500
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            color: '#E5E7EB', // gray-200
            borderDash: [2, 4]
          },
          ticks: {
            color: '#6B7280', // gray-500
            stepSize: 1
          }
        }
      },
      elements: {
        line: {
          tension: 0.4,
          borderWidth: 2
        },
        point: {
          radius: 4,
          hoverRadius: 6,
          borderWidth: 2,
          hoverBorderWidth: 2
        }
      },
      interaction: {
        mode: 'nearest',
        intersect: false
      }
    };
    
    // Format time ago
    const formatTimeAgo = (date) => {
      const seconds = Math.floor((new Date() - new Date(date)) / 1000);
      
      let interval = Math.floor(seconds / 31536000);
      if (interval >= 1) return interval + ' years ago';
      
      interval = Math.floor(seconds / 2592000);
      if (interval >= 1) return interval + ' months ago';
      
      interval = Math.floor(seconds / 86400);
      if (interval >= 1) return interval + ' days ago';
      
      interval = Math.floor(seconds / 3600);
      if (interval >= 1) return interval + ' hours ago';
      
      interval = Math.floor(seconds / 60);
      if (interval >= 1) return interval + ' minutes ago';
      
      return 'just now';
    };
    
    // Handle quick actions
    const handleQuickAction = (action) => {
      switch (action) {
        case 'addUser':
          router.push('/admin/users/new');
          break;
        case 'createTemplate':
          router.push('/admin/templates/new');
          break;
        case 'settings':
          router.push('/admin/settings');
          break;
      }
    };
    
    // API Service
    const api = {
      async getDashboardStats() {
        try {
          const response = await fetch('/api/admin/dashboard/stats');
          if (!response.ok) throw new Error('Failed to fetch dashboard stats');
          return await response.json();
        } catch (e) {
          console.error('Error fetching dashboard stats:', e);
          throw e;
        }
      },
      async getRecentActivities() {
        try {
          const response = await fetch('/api/admin/activities/recent');
          if (!response.ok) throw new Error('Failed to fetch recent activities');
          return await response.json();
        } catch (e) {
          console.error('Error fetching activities:', e);
          return [];
        }
      }
    };
    
    // Template analytics data
    const templateAnalytics = ref([
      {
        id: 1,
        name: 'Professional',
        category: 'Modern',
        usage: 45,
        completions: 1245,
        avgTime: 12,
        icon: 'BriefcaseIcon',
        bgColor: 'bg-blue-500',
        progressColor: 'bg-blue-500'
      },
      {
        id: 2,
        name: 'Creative',
        category: 'Modern',
        usage: 32,
        completions: 876,
        avgTime: 18,
        icon: 'LightBulbIcon',
        bgColor: 'bg-purple-500',
        progressColor: 'bg-purple-500'
      },
      {
        id: 3,
        name: 'Minimal',
        category: 'Simple',
        usage: 23,
        completions: 632,
        avgTime: 8,
        icon: 'DocumentTextIcon',
        bgColor: 'bg-green-500',
        progressColor: 'bg-green-500'
      },
      {
        id: 4,
        name: 'Executive',
        category: 'Classic',
        usage: 28,
        completions: 765,
        avgTime: 15,
        icon: 'UserGroupIcon',
        bgColor: 'bg-indigo-500',
        progressColor: 'bg-indigo-500'
      },
      {
        id: 5,
        name: 'Academic',
        category: 'Formal',
        usage: 19,
        completions: 521,
        avgTime: 20,
        icon: 'AcademicCapIcon',
        bgColor: 'bg-yellow-500',
        progressColor: 'bg-yellow-500'
      },
      {
        id: 6,
        name: 'Developer',
        category: 'Modern',
        usage: 37,
        completions: 1012,
        avgTime: 14,
        icon: 'CodeBracketIcon',
        bgColor: 'bg-red-500',
        progressColor: 'bg-red-500'
      }
    ]);

    // Stats data
    const stats = ref([
      {
        title: 'Total Users',
        value: '0',
        change: '0%',
        changeType: '',
        icon: 'UserGroupIcon',
        route: '/admin/users',
        loading: true
      },
      {
        title: 'Active Resumes',
        value: '0',
        change: '0%',
        changeType: '',
        icon: 'DocumentTextIcon',
        route: '/admin/resumes',
        loading: true
      },
      {
        title: 'Templates',
        value: '0',
        change: '0%',
        changeType: '',
        icon: 'TemplateIcon',
        route: '/admin/templates',
        loading: true
      },
      {
        title: 'This Month Signups',
        value: '0',
        change: '0%',
        changeType: '',
        icon: 'UserAddIcon',
        route: '/admin/users?filter=recent',
        loading: true
      }
    ]);

    // Recent activities
    const recentActivities = ref([
      {
        title: 'New user registered',
        description: 'John Doe created an account',
        icon: 'UserAddIcon',
        timestamp: new Date(Date.now() - 1000 * 60 * 5) // 5 minutes ago
      },
      {
        title: 'Resume created',
        description: 'Jane Smith created a new resume',
        icon: 'DocumentAddIcon',
        timestamp: new Date(Date.now() - 1000 * 60 * 30) // 30 minutes ago
      },
      {
        title: 'Template updated',
        description: 'Modern template was updated',
        icon: 'TemplateIcon',
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 2) // 2 hours ago
      },
      {
        title: 'System maintenance',
        description: 'Scheduled maintenance completed',
        icon: 'CogIcon',
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24) // 1 day ago
      }
    ]);
    
    // Quick actions
    const quickActions = ref([
      {
        title: 'Add New User',
        value: 'Create',
        icon: 'UserAddIcon',
        buttonText: 'Add User',
        action: 'addUser'
      },
      {
        title: 'Create Template',
        value: 'Design',
        icon: 'TemplateIcon',
        buttonText: 'New Template',
        action: 'createTemplate'
      },
      {
        title: 'System Settings',
        value: 'Configure',
        icon: 'CogIcon',
        buttonText: 'Go to Settings',
        action: 'settings'
      }
    ]);
    
    
    // Refresh dashboard data
    const refreshDashboard = async () => {
      isLoading.value = true;
      error.value = null;
      
      try {
        // Fetch stats
        const statsData = await api.getDashboardStats();
        
        // Update stats with real data
        stats.value = stats.value.map((stat, index) => {
          const statData = statsData[index] || {};
          return {
            ...stat,
            value: statData.value?.toLocaleString() || '0',
            change: statData.change ? `${statData.change}%` : '0%',
            changeType: statData.change > 0 ? 'increase' : statData.change < 0 ? 'decrease' : '',
            loading: false
          };
        });
        
        // Fetch recent activities
        const activities = await api.getRecentActivities();
        if (activities.length > 0) {
          recentActivities.value = activities.map(activity => ({
            ...activity,
            timestamp: new Date(activity.timestamp)
          }));
        }
        
      } catch (err) {
        console.error('Error refreshing dashboard:', err);
        error.value = err.message || 'Failed to load dashboard data. Please try again.';
      } finally {
        isLoading.value = false;
      }
    };
    
    // Fetch chart data
    const fetchChartData = async () => {
      try {
        isLoading.value = true;
        // Simulate API call - replace with actual API call
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Mock data - replace with actual data from API
        const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const newUsers = [12, 19, 3, 5, 2, 3, 9];
        const activeUsers = [8, 15, 12, 10, 7, 5, 11];
        
        chartData.value = {
          labels,
          datasets: [
            {
              label: 'New Users',
              data: newUsers,
              borderColor: '#4F46E5', // indigo-600
              backgroundColor: 'rgba(79, 70, 229, 0.1)',
              fill: true,
              tension: 0.3
            },
            {
              label: 'Active Users',
              data: activeUsers,
              borderColor: '#10B981', // emerald-500
              backgroundColor: 'rgba(16, 185, 129, 0.1)',
              fill: true,
              tension: 0.3
            }
          ]
        };
      } catch (err) {
        console.error('Error fetching chart data:', err);
        error.value = 'Failed to load chart data';
      } finally {
        isLoading.value = false;
      }
    };
    
    // Initial data load
    onMounted(async () => {
      try {
        // Initialize WebSocket connection
        initWebSocket();
        
        // Load initial data
        await Promise.all([
          refreshDashboard(),
          fetchChartData()
        ]);
        
        // Welcome notification
        addNotification('success', 'Dashboard Loaded', 'Your admin dashboard is ready!', 5000);
        
        // Set up auto-refresh every 5 minutes
        const refreshInterval = setInterval(() => {
          refreshDashboard();
          fetchChartData();
        }, 5 * 60 * 1000);
        
        // Clean up interval on component unmount
        onUnmounted(() => {
          clearInterval(refreshInterval);
          if (socket) {
            socket.close();
          }
        });
      } catch (err) {
        console.error('Error initializing dashboard:', err);
        error.value = 'Failed to initialize dashboard';
        addNotification('error', 'Initialization Error', 'Failed to load dashboard data. Please refresh the page.');
      }
    });
    
    return {
      stats,
      recentActivities,
      quickActions,
      selectedPeriod,
      timePeriods,
      isLoading,
      error,
      chartData,
      chartOptions,
      formatTimeAgo,
      handleQuickAction,
      refreshDashboard,
      fetchChartData,
      isDarkMode,
      toggleDarkMode,
      notifications,
      removeNotification,
      notificationIcons,
      activityHeatmap
    };
  }
};
</script>