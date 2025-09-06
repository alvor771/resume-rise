<script setup>
import { onMounted, onUnmounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useDashboardStore } from '@/stores/dashboard';
import DashboardLayout from '@/components/dashboard/DashboardLayout.vue';
import { useAuthStore } from '@/stores/auth';

const props = defineProps({
  role: {
    type: String,
    required: true,
    validator: (value) => ['admin', 'hr', 'candidate'].includes(value)
  }
});

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const dashboardStore = useDashboardStore();
const authStore = useAuthStore();

// Check if user has permission to access this dashboard
const hasPermission = computed(() => {
  if (!authStore.isAuthenticated) return false;
  
  // Admin can access any dashboard
  if (authStore.user.role === 'admin') return true;
  
  // Users can only access their own role's dashboard
  return authStore.user.role === props.role;
});

// Redirect to login if not authenticated
const checkAuth = () => {
  if (!authStore.isAuthenticated) {
    router.push({ name: 'Login', query: { redirect: route.fullPath } });
    return false;
  }
  
  if (!hasPermission.value) {
    // Redirect to the dashboard for the user's role
    const userRole = authStore.user.role || 'candidate';
    router.push({ name: `${userRole.charAt(0).toUpperCase() + userRole.slice(1)}Dashboard` });
    return false;
  }
  
  return true;
};

// Initialize dashboard
const initDashboard = async () => {
  if (!checkAuth()) return;
  
  try {
    await dashboardStore.loadDashboardLayout(props.role);
    // Load initial widget data
    // dashboardStore.loadWidgetData('userStats');
    // dashboardStore.loadWidgetData('recentActivity');
    // ... load other initial widgets
  } catch (error) {
    console.error('Failed to initialize dashboard:', error);
  }
};

// Handle window resize for responsive layout
const handleResize = () => {
  // Update responsive breakpoints if needed
};

// Set document title based on dashboard type
const setDocumentTitle = () => {
  const titles = {
    admin: t('dashboard.admin.title'),
    hr: t('dashboard.hr.title'),
    candidate: t('dashboard.candidate.title')
  };
  
  document.title = `${titles[props.role]} | ${t('app.name')}`;
};

// Lifecycle hooks
onMounted(() => {
  initDashboard();
  setDocumentTitle();
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});

// Watch for route changes
watch(
  () => route.fullPath,
  () => {
    if (route.name && route.name.endsWith('Dashboard')) {
      initDashboard();
      setDocumentTitle();
    }
  }
);

// Watch for authentication changes
watch(
  () => authStore.isAuthenticated,
  (isAuthenticated) => {
    if (isAuthenticated) {
      initDashboard();
    } else if (route.meta.requiresAuth) {
      router.push({ name: 'Login', query: { redirect: route.fullPath } });
    }
  }
);
</script>

<template>
  <div class="dashboard-view" :class="`dashboard-${role}`">
    <!-- Loading state -->
    <div v-if="dashboardStore.loading.layout" class="loading-overlay">
      <div class="loading-spinner"></div>
      <p>{{ t('common.loading') }}...</p>
    </div>
    
    <!-- Error state -->
    <div v-else-if="dashboardStore.errors.layout" class="error-message">
      <i class="fas fa-exclamation-triangle"></i>
      <p>{{ dashboardStore.errors.layout }}</p>
      <button @click="initDashboard" class="btn btn-sm btn-outline mt-2">
        {{ t('common.retry') }}
      </button>
    </div>
    
    <!-- Main content -->
    <template v-else>
      <!-- Dashboard header -->
      <header class="dashboard-header">
        <div class="header-content">
          <h1 class="text-2xl font-bold">
            {{ t(`dashboard.${role}.welcome`, { name: authStore.user?.name || '' }) }}
          </h1>
          <div class="header-actions">
            <button 
              @click="dashboardStore.toggleTheme" 
              class="theme-toggle"
              :title="t('dashboard.toggle_theme')"
            >
              <i class="fas" :class="dashboardStore.theme === 'dark' ? 'fa-sun' : 'fa-moon'"></i>
            </button>
            <div class="dropdown">
              <button class="dropdown-toggle">
                <i class="fas fa-cog"></i>
              </button>
              <div class="dropdown-menu">
                <button class="dropdown-item">
                  <i class="fas fa-columns mr-2"></i>
                  {{ t('dashboard.customize_layout') }}
                </button>
                <button class="dropdown-item">
                  <i class="fas fa-palette mr-2"></i>
                  {{ t('dashboard.customize_theme') }}
                </button>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item">
                  <i class="fas fa-sync-alt mr-2"></i>
                  {{ t('dashboard.reset_layout') }}
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="dashboard-tabs">
          <router-link 
            v-for="tab in tabs"
            :key="tab.name"
            :to="{ name: tab.route }"
            class="tab-item"
            :class="{ 'active': $route.name === tab.route }"
          >
            <i :class="tab.icon" class="mr-2"></i>
            {{ t(tab.label) }}
          </router-link>
        </div>
      </header>
      
      <!-- Dashboard content -->
      <main class="dashboard-content">
        <DashboardLayout :role="role" />
      </main>
    </template>
  </div>
</template>

<style scoped>
.dashboard-view {
  @apply min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200;
}

/* Loading overlay */
.loading-overlay {
  @apply fixed inset-0 flex flex-col items-center justify-center bg-white dark:bg-gray-900 bg-opacity-90 z-50;
}

.loading-spinner {
  @apply w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4;
}

/* Error message */
.error-message {
  @apply max-w-md mx-auto p-6 text-center text-red-600 dark:text-red-400;
}

/* Header */
.dashboard-header {
  @apply bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700;
}

.header-content {
  @apply px-6 py-4 flex items-center justify-between;
}

.header-actions {
  @apply flex items-center space-x-2;
}

.theme-toggle {
  @apply w-10 h-10 rounded-full flex items-center justify-center 
         text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
         hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors;
}

/* Tabs */
.dashboard-tabs {
  @apply flex border-b border-gray-200 dark:border-gray-700 px-6 -mb-px;
}

.tab-item {
  @apply px-4 py-3 text-sm font-medium border-b-2 border-transparent
         text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300
         hover:border-gray-300 dark:hover:border-gray-500 transition-colors
         flex items-center;
}

.tab-item.active {
  @apply text-blue-600 dark:text-blue-400 border-blue-500 dark:border-blue-500;
}

/* Dashboard content */
.dashboard-content {
  @apply p-4 md:p-6;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .header-content {
    @apply px-4 py-3;
  }
  
  .dashboard-tabs {
    @apply px-4 overflow-x-auto flex-nowrap -mx-4;
    scrollbar-width: none;
  }
  
  .dashboard-tabs::-webkit-scrollbar {
    display: none;
  }
  
  .tab-item {
    @apply whitespace-nowrap;
  }
  
  .dashboard-content {
    @apply p-2;
  }
}

/* Dark mode overrides */
.dark .dashboard-header {
  @apply bg-gray-800;
}

.dark .tab-item {
  @apply text-gray-400 hover:text-gray-300;
}

.dark .tab-item.active {
  @apply text-blue-400 border-blue-500;
}
</style>
