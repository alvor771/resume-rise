<script setup>
import { computed } from 'vue';
import BaseDashboardView from './BaseDashboardView.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Define tabs for HR dashboard
const tabs = computed(() => [
  { name: 'overview', label: 'dashboard.tabs.overview', icon: 'fas fa-tachometer-alt', route: 'HRDashboard' },
  { name: 'applicants', label: 'dashboard.tabs.applicants', icon: 'fas fa-user-tie', route: 'HRApplicants' },
  { name: 'vacancies', label: 'dashboard.tabs.vacancies', icon: 'fas fa-briefcase', route: 'HRVacancies' },
  { name: 'interviews', label: 'dashboard.tabs.interviews', icon: 'fas fa-calendar-alt', route: 'HRInterviews' },
  { name: 'reports', label: 'dashboard.tabs.reports', icon: 'fas fa-chart-pie', route: 'HRReports' }
]);

// Define default widgets for HR dashboard
const defaultWidgets = [
  { 
    id: 'w1', 
    type: 'applicantStats', 
    x: 0, 
    y: 0, 
    w: 6, 
    h: 4,
    settings: {}
  },
  { 
    id: 'w2', 
    type: 'vacancyStatus', 
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
    type: 'upcomingInterviews', 
    x: 0, 
    y: 6, 
    w: 8, 
    h: 4,
    settings: {}
  },
  { 
    id: 'w5', 
    type: 'hiringFunnel', 
    x: 8, 
    y: 6, 
    w: 4, 
    h: 4,
    settings: {}
  }
];
</script>

<template>
  <BaseDashboardView 
    role="hr"
    :tabs="tabs"
    :default-widgets="defaultWidgets"
  >
    <template #header-actions>
      <button class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i>
        {{ t('dashboard.hr.add_vacancy') }}
      </button>
    </template>
    
    <!-- Additional HR-specific content can go here -->
  </BaseDashboardView>
</template>

<style scoped>
/* HR-specific styles can be added here */
</style>
