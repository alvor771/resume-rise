<script setup>
import { computed } from 'vue';
import BaseDashboardView from './BaseDashboardView.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Define tabs for admin dashboard
const tabs = computed(() => [
  { name: 'overview', label: 'dashboard.tabs.overview', icon: 'fas fa-tachometer-alt', route: 'AdminDashboard' },
  { name: 'users', label: 'dashboard.tabs.users', icon: 'fas fa-users', route: 'AdminUsers' },
  { name: 'system', label: 'dashboard.tabs.system', icon: 'fas fa-server', route: 'AdminSystem' },
  { name: 'analytics', label: 'dashboard.tabs.analytics', icon: 'fas fa-chart-line', route: 'AdminAnalytics' },
  { name: 'settings', label: 'dashboard.tabs.settings', icon: 'fas fa-cog', route: 'AdminSettings' }
]);

// Define default widgets for admin dashboard
const defaultWidgets = [
  { 
    id: 'w1', 
    type: 'userStats', 
    x: 0, 
    y: 0, 
    w: 6, 
    h: 4,
    settings: {}
  },
  { 
    id: 'w2', 
    type: 'systemHealth', 
    x: 6, 
    y: 0, 
    w: 6, 
    h: 4,
    settings: {}
  },
  { 
    id: 'w3', 
    type: 'quickActions', 
    x: 0, 
    y: 4, 
    w: 12, 
    h: 2,
    settings: {}
  },
  { 
    id: 'w4', 
    type: 'recentActivity', 
    x: 0, 
    y: 6, 
    w: 12, 
    h: 4,
    settings: {}
  }
];
</script>

<template>
  <BaseDashboardView 
    role="admin"
    :tabs="tabs"
    :default-widgets="defaultWidgets"
  >
    <template #header-actions>
      <button class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i>
        {{ t('dashboard.admin.add_widget') }}
      </button>
    </template>
    
    <!-- Additional admin-specific content can go here -->
  </BaseDashboardView>
</template>

<style scoped>
/* Admin-specific styles can be added here */
</style>
