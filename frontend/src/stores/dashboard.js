import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useAuthStore } from './auth';

export const useDashboardStore = defineStore('dashboard', () => {
  const authStore = useAuthStore();
  
  // Theme state
  const theme = ref(localStorage.getItem('dashboard:theme') || 'light');
  
  // Available widgets configuration
  const availableWidgets = ref([
    // Admin widgets
    {
      type: 'userStats',
      title: 'User Statistics',
      description: 'Overview of user registrations and activity',
      category: 'analytics',
      icon: 'fas fa-users',
      roles: ['admin'],
      defaultSize: { w: 4, h: 3 },
      defaultPosition: { x: 0, y: 0 },
      minSize: { w: 3, h: 2 },
      maxSize: { w: 12, h: 6 },
      refreshable: true,
      defaultSettings: {
        showChart: true,
        timeRange: 'week',
        displayMode: 'chart'
      }
    },
    {
      type: 'systemHealth',
      title: 'System Health',
      description: 'Monitor server status and performance',
      category: 'system',
      icon: 'fas fa-heartbeat',
      roles: ['admin'],
      defaultSize: { w: 4, h: 3 },
      minSize: { w: 2, h: 2 },
      maxSize: { w: 6, h: 4 },
      refreshable: true
    },
    
    // HR widgets
    {
      type: 'applicantStats',
      title: 'Applicant Statistics',
      description: 'Track job applications and candidate pipeline',
      category: 'recruitment',
      icon: 'fas fa-user-tie',
      roles: ['hr', 'admin'],
      permissions: ['view_applicants'],
      defaultSize: { w: 6, h: 4 },
      minSize: { w: 4, h: 3 },
      maxSize: { w: 12, h: 6 },
      refreshable: true
    },
    {
      type: 'vacancyStatus',
      title: 'Open Vacancies',
      description: 'View and manage job openings',
      category: 'recruitment',
      icon: 'fas fa-briefcase',
      roles: ['hr', 'admin'],
      permissions: ['view_vacancies'],
      defaultSize: { w: 6, h: 4 },
      minSize: { w: 4, h: 3 },
      maxSize: { w: 12, h: 6 },
      refreshable: true
    },
    
    // Candidate widgets
    {
      type: 'resumeStats',
      title: 'Resume Stats',
      description: 'Track your resume performance',
      category: 'resume',
      icon: 'fas fa-file-alt',
      roles: ['candidate'],
      defaultSize: { w: 4, h: 3 },
      minSize: { w: 2, h: 2 },
      maxSize: { w: 6, h: 4 },
      refreshable: true
    },
    {
      type: 'jobMatches',
      title: 'Job Matches',
      description: 'Jobs that match your profile',
      category: 'jobs',
      icon: 'fas fa-bullseye',
      roles: ['candidate'],
      defaultSize: { w: 8, h: 4 },
      minSize: { w: 4, h: 3 },
      maxSize: { w: 12, h: 6 },
      refreshable: true
    },
    {
      type: 'applicationStatus',
      title: 'Applications',
      description: 'Track your job applications',
      category: 'jobs',
      icon: 'fas fa-clipboard-check',
      roles: ['candidate'],
      defaultSize: { w: 4, h: 3 },
      minSize: { w: 2, h: 2 },
      maxSize: { w: 6, h: 4 },
      refreshable: true
    },
    {
      type: 'quickActions',
      title: 'Quick Actions',
      description: 'Frequently used actions',
      category: 'general',
      icon: 'fas fa-bolt',
      roles: ['admin', 'hr', 'candidate'],
      defaultSize: { w: 12, h: 2 },
      minSize: { w: 6, h: 1 },
      maxSize: { w: 12, h: 3 },
      refreshable: false
    },
    {
      type: 'recentActivity',
      title: 'Recent Activity',
      description: 'Latest system activities',
      category: 'general',
      icon: 'fas fa-history',
      roles: ['admin', 'hr', 'candidate'],
      defaultSize: { w: 12, h: 4 },
      minSize: { w: 6, h: 3 },
      maxSize: { w: 12, h: 8 },
      refreshable: true
    }
  ]);
  
  // User dashboard layouts
  const userLayouts = ref({});
  
  // Current widget data
  const widgetData = ref({});
  
  // Loading states
  const loading = ref({
    layout: false,
    widgets: {}
  });
  
  // Error states
  const errors = ref({
    layout: null,
    widgets: {}
  });
  
  // Computed properties
  const userRole = computed(() => {
    return authStore.user?.role || 'candidate';
  });
  
  const userPermissions = computed(() => {
    return authStore.user?.permissions || [];
  });
  
  // Get default layout for a specific role
  const getDefaultLayout = (role) => {
    const defaultLayouts = {
      admin: [
        { ...getWidgetConfig('userStats'), id: 'w1', x: 0, y: 0, w: 6, h: 4 },
        { ...getWidgetConfig('systemHealth'), id: 'w2', x: 6, y: 0, w: 6, h: 4 },
        { ...getWidgetConfig('quickActions'), id: 'w3', x: 0, y: 4, w: 12, h: 2 },
        { ...getWidgetConfig('recentActivity'), id: 'w4', x: 0, y: 6, w: 12, h: 4 }
      ],
      hr: [
        { ...getWidgetConfig('applicantStats'), id: 'w1', x: 0, y: 0, w: 6, h: 4 },
        { ...getWidgetConfig('vacancyStatus'), id: 'w2', x: 6, y: 0, w: 6, h: 4 },
        { ...getWidgetConfig('quickActions'), id: 'w3', x: 0, y: 4, w: 12, h: 2 },
        { ...getWidgetConfig('recentActivity'), id: 'w4', x: 0, y: 6, w: 12, h: 4 }
      ],
      candidate: [
        { ...getWidgetConfig('resumeStats'), id: 'w1', x: 0, y: 0, w: 4, h: 3 },
        { ...getWidgetConfig('jobMatches'), id: 'w2', x: 4, y: 0, w: 8, h: 4 },
        { ...getWidgetConfig('applicationStatus'), id: 'w3', x: 0, y: 3, w: 4, h: 3 },
        { ...getWidgetConfig('quickActions'), id: 'w4', x: 0, y: 6, w: 12, h: 2 },
        { ...getWidgetConfig('recentActivity'), id: 'w5', x: 0, y: 8, w: 12, h: 4 }
      ]
    };
    
    return defaultLayouts[role] || [];
  };
  
  // Get widget configuration by type
  const getWidgetConfig = (widgetType) => {
    return availableWidgets.value.find(w => w.type === widgetType) || null;
  };
  
  // Get widgets available for current user
  const userWidgets = computed(() => {
    return availableWidgets.value.filter(widget => 
      widget.roles.includes(userRole.value) && 
      (!widget.permissions || widget.permissions.every(p => userPermissions.value.includes(p)))
    );
  });
  
  // Get current user's layout
  const layout = computed(() => {
    return userLayouts.value[userRole.value] || getDefaultLayout(userRole.value);
  });
  
  // Toggle theme between light and dark
  const toggleTheme = () => {
    theme.value = theme.value === 'light' ? 'dark' : 'light';
    localStorage.setItem('dashboard:theme', theme.value);
    applyTheme();
  };
  
  // Apply theme to document
  const applyTheme = () => {
    if (theme.value === 'dark') {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  };
  
  // Load dashboard layout from API
  const loadDashboardLayout = async (role) => {
    if (!authStore.isAuthenticated) {
      return getDefaultLayout(role);
    }
    
    loading.value.layout = true;
    errors.value.layout = null;
    
    try {
      // TODO: Implement API call to fetch user's saved layout
      // const response = await api.get(`/api/dashboard/layout/${role}`);
      // userLayouts.value[role] = response.data.layout || getDefaultLayout(role);
      
      // For now, return default layout
      return getDefaultLayout(role);
    } catch (error) {
      console.error('Failed to load dashboard layout:', error);
      errors.value.layout = 'Failed to load dashboard layout. Using default layout.';
      return getDefaultLayout(role);
    } finally {
      loading.value.layout = false;
    }
  };
  
  // Save dashboard layout to API
  const saveDashboardLayout = async (role, layout) => {
    if (!authStore.isAuthenticated) {
      return;
    }
    
    try {
      // TODO: Implement API call to save user's layout
      // await api.put(`/api/dashboard/layout/${role}`, { layout });
      userLayouts.value[role] = layout;
    } catch (error) {
      console.error('Failed to save dashboard layout:', error);
      throw error;
    }
  };
  
  // Load widget data
  const loadWidgetData = async (widgetType, _params = {}) => {
    if (!widgetType) return null;
    
    loading.value.widgets[widgetType] = true;
    errors.value.widgets[widgetType] = null;
    
    try {
      // TODO: Implement API calls for widget data
      // const response = await api.get(`/api/dashboard/widgets/${widgetType}`, { params });
      // widgetData.value[widgetType] = response.data;
      
      // Mock data for now
      return getMockWidgetData(widgetType);
    } catch (error) {
      console.error(`Failed to load widget data for ${widgetType}:`, error);
      errors.value.widgets[widgetType] = `Failed to load ${widgetType} data.`;
      return null;
    } finally {
      loading.value.widgets[widgetType] = false;
    }
  };
  
  // Get widget data
  const getWidgetData = (widgetType) => {
    return widgetData.value[widgetType] || null;
  };
  
  // Generate mock data for widgets (for development)
  const getMockWidgetData = (widgetType) => {
    const mockData = {
      userStats: {
        totalUsers: 1245,
        newUsers: 42,
        activeUsers: 789,
        chartData: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [
            {
              label: 'New Users',
              data: [65, 59, 80, 81, 56, 55],
              borderColor: 'rgb(99, 102, 241)',
              backgroundColor: 'rgba(99, 102, 241, 0.2)',
              tension: 0.3,
              fill: true
            },
            {
              label: 'Active Users',
              data: [28, 48, 40, 19, 86, 27],
              borderColor: 'rgb(168, 85, 247)',
              backgroundColor: 'rgba(168, 85, 247, 0.2)',
              tension: 0.3,
              fill: true
            }
          ]
        }
      },
      // Add more mock data for other widget types
    };
    
    return mockData[widgetType] || {};
  };
  
  // Initialize store
  const init = () => {
    applyTheme();
    // Load initial data
    if (authStore.isAuthenticated) {
      loadDashboardLayout(userRole.value);
    }
  };
  
  // Watch for authentication changes
  const unwatch = authStore.$onAction(({ name, after }) => {
    if (name === 'setUser' || name === 'logout') {
      after(() => {
        if (authStore.isAuthenticated) {
          loadDashboardLayout(userRole.value);
        } else {
          // Reset layouts on logout
          userLayouts.value = {};
          widgetData.value = {};
        }
      });
    }
  });
  
  // Initialize on store creation
  init();
  
  // Cleanup on unmount
  const cleanup = () => {
    unwatch();
  };
  
  return {
    // State
    theme,
    availableWidgets,
    userLayouts,
    widgetData,
    loading,
    errors,
    
    // Getters
    userRole,
    userWidgets,
    layout,
    
    // Actions
    getWidgetConfig,
    getDefaultLayout,
    toggleTheme,
    loadDashboardLayout,
    saveDashboardLayout,
    loadWidgetData,
    getWidgetData,
    
    // Lifecycle
    cleanup
  };
});
