<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import BaseWidget from './BaseWidget.vue';

const props = defineProps({
  widget: {
    type: Object,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: null
  },
  editable: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:settings', 'refresh']);

// Local state
const stats = ref([]);
const isLoading = ref(false);
const errorMessage = ref(null);

// Computed properties
const widgetSettings = computed(() => ({
  title: props.widget.settings?.title || props.widget.title,
  layout: props.widget.settings?.layout || 'grid',
  showIcons: props.widget.settings?.showIcons !== false,
  showTrend: props.widget.settings?.showTrend !== false,
  ...props.widget.settings
}));

// Fetch stats data
const fetchStats = async () => {
  if (isLoading.value) return;
  
  isLoading.value = true;
  errorMessage.value = null;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 800));
    
    // Mock data - in a real app, this would come from an API
    const mockData = {
      userStats: [
        { label: 'Total Users', value: '2,345', icon: 'fas fa-users', trend: 12.5, trendDirection: 'up' },
        { label: 'New Today', value: '124', icon: 'fas fa-user-plus', trend: 5.2, trendDirection: 'up' },
        { label: 'Active Now', value: '489', icon: 'fas fa-user-check', trend: 2.1, trendDirection: 'down' },
        { label: 'Conversion', value: '3.2%', icon: 'fas fa-percentage', trend: 0.8, trendDirection: 'up' }
      ],
      resumeStats: [
        { label: 'Total Resumes', value: '1,245', icon: 'fas fa-file-alt', trend: 8.7, trendDirection: 'up' },
        { label: 'Updated Today', value: '87', icon: 'fas fa-sync', trend: 2.3, trendDirection: 'up' },
        { label: 'Downloads', value: '324', icon: 'fas fa-download', trend: 1.2, trendDirection: 'down' },
        { label: 'Avg. Score', value: '87%', icon: 'fas fa-star', trend: 0.5, trendDirection: 'up' }
      ],
      jobStats: [
        { label: 'Total Jobs', value: '856', icon: 'fas fa-briefcase', trend: 15.3, trendDirection: 'up' },
        { label: 'New Today', value: '24', icon: 'fas fa-plus-circle', trend: 3.1, trendDirection: 'down' },
        { label: 'Applications', value: '1,234', icon: 'fas fa-paper-plane', trend: 8.9, trendDirection: 'up' },
        { label: 'Hire Rate', value: '4.2%', icon: 'fas fa-handshake', trend: 1.2, trendDirection: 'up' }
      ]
    };
    
    // Use appropriate data based on widget type or settings
    if (props.widget.type === 'userStats') {
      stats.value = mockData.userStats;
    } else if (props.widget.type === 'resumeStats') {
      stats.value = mockData.resumeStats;
    } else if (props.widget.type === 'jobStats') {
      stats.value = mockData.jobStats;
    } else {
      // Default to user stats
      stats.value = mockData.userStats;
    }
    
    // If widget has custom stats in settings, use those
    if (props.widget.settings?.customStats?.length) {
      stats.value = props.widget.settings.customStats;
    }
    
  } catch (error) {
    console.error('Failed to load stats:', error);
    errorMessage.value = 'Failed to load statistics. Please try again.';
  } finally {
    isLoading.value = false;
  }
};

// Handle refresh
const handleRefresh = () => {
  fetchStats();
  emit('refresh');
};

// Format trend value
const formatTrend = (value) => {
  return value > 0 ? `+${value}%` : `${value}%`;
};

// Initialize
onMounted(() => {
  fetchStats();
});

// Watch for settings changes
watch(() => props.widget.settings, () => {
  fetchStats();
}, { deep: true });
</script>

<template>
  <BaseWidget
    :widget="widget"
    :loading="loading || isLoading"
    :error="error || errorMessage"
    :editable="editable"
    @update:settings="(settings) => $emit('update:settings', settings)"
    @refresh="handleRefresh"
  >
    <div 
      class="stat-widget"
      :class="`layout-${widgetSettings.layout}`"
    >
      <div 
        v-for="(stat, index) in stats" 
        :key="index"
        class="stat-item"
      >
        <div class="stat-content">
          <div v-if="widgetSettings.showIcons && stat.icon" class="stat-icon">
            <i :class="stat.icon"></i>
          </div>
          <div class="stat-details">
            <div class="stat-value">{{ stat.value }}</div>
            <div class="stat-label">{{ stat.label }}</div>
          </div>
          <div 
            v-if="widgetSettings.showTrend && stat.trend !== undefined" 
            class="stat-trend"
            :class="`trend-${stat.trendDirection || 'neutral'}`"
          >
            <i 
              class="trend-icon"
              :class="{
                'fas fa-arrow-up': stat.trendDirection === 'up',
                'fas fa-arrow-down': stat.trendDirection === 'down',
                'fas fa-minus': !stat.trendDirection || stat.trendDirection === 'neutral'
              }"
            ></i>
            <span class="trend-value">{{ formatTrend(stat.trend) }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Settings Panel -->
    <template #settings>
      <div class="setting-group">
        <label class="setting-label">
          {{ $t('dashboard.widget_settings.title') }}
        </label>
        <input 
          v-model="widgetSettings.title" 
          type="text" 
          class="setting-input"
          :placeholder="$t('dashboard.widget_settings.title_placeholder')"
        >
      </div>
      
      <div class="setting-group">
        <label class="setting-label">
          {{ $t('dashboard.widget_settings.layout') }}
        </label>
        <div class="flex space-x-2">
          <button
            v-for="layout in ['grid', 'list']"
            :key="layout"
            class="btn btn-sm"
            :class="widgetSettings.layout === layout ? 'btn-primary' : 'btn-outline'"
            @click="widgetSettings.layout = layout"
          >
            {{ $t(`dashboard.widget_settings.layouts.${layout}`) }}
          </button>
        </div>
      </div>
      
      <div class="setting-group">
        <label class="setting-label flex items-center">
          <input 
            v-model="widgetSettings.showIcons" 
            type="checkbox" 
            class="form-checkbox h-4 w-4 text-primary-600"
          >
          <span class="ml-2">{{ $t('dashboard.widget_settings.show_icons') }}</span>
        </label>
      </div>
      
      <div class="setting-group">
        <label class="setting-label flex items-center">
          <input 
            v-model="widgetSettings.showTrend" 
            type="checkbox" 
            class="form-checkbox h-4 w-4 text-primary-600"
          >
          <span class="ml-2">{{ $t('dashboard.widget_settings.show_trend') }}</span>
        </label>
      </div>
    </template>
  </BaseWidget>
</template>

<style scoped>
/* Base styles */
.stat-widget {
  @apply h-full w-full;
}

/* Grid layout (default) */
.stat-widget.layout-grid {
  @apply grid gap-4;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
}

/* List layout */
.stat-widget.layout-list {
  @apply flex flex-col gap-2;
}

.stat-widget.layout-list .stat-item {
  @apply mb-0;
}

.stat-widget.layout-list .stat-content {
  @apply flex-row justify-between items-center;
  @apply px-4 py-3;
}

/* Stat item */
.stat-item {
  @apply mb-2 last:mb-0;
}

.stat-content {
  @apply bg-white dark:bg-gray-800 rounded-lg p-4 h-full flex flex-col;
  @apply border border-gray-200 dark:border-gray-700;
  transition: all 0.2s ease;
}

.stat-widget.layout-grid .stat-content {
  @apply h-full;
}

/* Stat icon */
.stat-icon {
  @apply w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 
         text-blue-600 dark:text-blue-300 flex items-center 
         justify-center text-lg mb-3;
}

.stat-widget.layout-list .stat-icon {
  @apply mb-0 mr-3;
  order: -1;
}

/* Stat details */
.stat-details {
  @apply flex-1;
}

.stat-value {
  @apply text-2xl font-bold text-gray-900 dark:text-white;
}

.stat-label {
  @apply text-sm text-gray-500 dark:text-gray-400 mt-1;
}

/* Trend indicator */
.stat-trend {
  @apply inline-flex items-center text-xs font-medium mt-2;
}

.trend-up {
  @apply text-green-600 dark:text-green-400;
}

.trend-down {
  @apply text-red-600 dark:text-red-400;
}

.trend-neutral {
  @apply text-gray-500 dark:text-gray-400;
}

.trend-icon {
  @apply mr-1;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .stat-widget.layout-grid {
    grid-template-columns: 1fr 1fr;
  }
  
  .stat-content {
    @apply p-3;
  }
  
  .stat-value {
    @apply text-xl;
  }
  
  .stat-label {
    @apply text-xs;
  }
}

/* Dark mode overrides */
.dark .stat-content {
  @apply bg-gray-800 border-gray-700;
}

.dark .stat-value {
  @apply text-white;
}

.dark .stat-label {
  @apply text-gray-400;
}
</style>
