<script setup>
import { computed } from 'vue';
import BaseDashboardView from './BaseDashboardView.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Define tabs for candidate dashboard
const tabs = computed(() => [
  { name: 'overview', label: 'dashboard.tabs.overview', icon: 'fas fa-home', route: 'CandidateDashboard' },
  { name: 'resume', label: 'dashboard.tabs.my_resume', icon: 'fas fa-file-alt', route: 'MyResume' },
  { name: 'jobs', label: 'dashboard.tabs.job_matches', icon: 'fas fa-briefcase', route: 'JobMatches' },
  { name: 'applications', label: 'dashboard.tabs.my_applications', icon: 'fas fa-clipboard-check', route: 'MyApplications' },
  { name: 'profile', label: 'dashboard.tabs.my_profile', icon: 'fas fa-user', route: 'MyProfile' }
]);

// Define default widgets for candidate dashboard
const defaultWidgets = [
  { 
    id: 'w1', 
    type: 'resumeStats', 
    x: 0, 
    y: 0, 
    w: 4, 
    h: 3,
    settings: {}
  },
  { 
    id: 'w2', 
    type: 'jobMatches', 
    x: 4, 
    y: 0, 
    w: 8, 
    h: 4,
    settings: {}
  },
  { 
    id: 'w3', 
    type: 'applicationStatus', 
    x: 0, 
    y: 3, 
    w: 4, 
    h: 3,
    settings: {}
  },
  { 
    id: 'w4', 
    type: 'quickActions', 
    x: 0, 
    y: 6, 
    w: 12, 
    h: 2,
    settings: {}
  },
  { 
    id: 'w5', 
    type: 'recentActivity', 
    x: 0, 
    y: 8, 
    w: 8, 
    h: 4,
    settings: {}
  },
  { 
    id: 'w6', 
    type: 'profileCompleteness', 
    x: 8, 
    y: 8, 
    w: 4, 
    h: 4,
    settings: {}
  }
];
</script>

<template>
  <BaseDashboardView 
    role="candidate"
    :tabs="tabs"
    :default-widgets="defaultWidgets"
  >
    <template #header-actions>
      <button class="btn btn-primary btn-sm">
        <i class="fas fa-upload mr-1"></i>
        {{ t('dashboard.candidate.upload_resume') }}
      </button>
    </template>
    
    <!-- Additional candidate-specific content can go here -->
  </BaseDashboardView>
</template>

<style scoped>
/* Candidate-specific styles can be added here */
</style>
