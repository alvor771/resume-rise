/**
 * Dashboard utility functions and constants
 */

// Default widget configurations
export const DEFAULT_WIDGET_CONFIGS = {
  // User statistics widget
  userStats: {
    type: 'userStats',
    title: 'User Statistics',
    description: 'Overview of user registrations and activity',
    category: 'analytics',
    icon: 'fas fa-users',
    roles: ['admin'],
    defaultSize: { w: 4, h: 3 },
    minSize: { w: 3, h: 2 },
    maxSize: { w: 8, h: 6 },
    refreshable: true,
    component: 'StatWidget',
    defaultSettings: {
      layout: 'grid',
      showIcons: true,
      showTrend: true
    }
  },
  
  // System health widget
  systemHealth: {
    type: 'systemHealth',
    title: 'System Health',
    description: 'Monitor server status and performance',
    category: 'system',
    icon: 'fas fa-heartbeat',
    roles: ['admin'],
    defaultSize: { w: 4, h: 3 },
    minSize: { w: 2, h: 2 },
    maxSize: { w: 6, h: 4 },
    refreshable: true,
    component: 'SystemHealthWidget'
  },
  
  // Applicant statistics widget
  applicantStats: {
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
    refreshable: true,
    component: 'StatWidget',
    defaultSettings: {
      layout: 'grid',
      showIcons: true,
      showTrend: true
    }
  },
  
  // Resume statistics widget
  resumeStats: {
    type: 'resumeStats',
    title: 'Resume Statistics',
    description: 'Track your resume performance',
    category: 'resume',
    icon: 'fas fa-file-alt',
    roles: ['candidate'],
    defaultSize: { w: 4, h: 3 },
    minSize: { w: 2, h: 2 },
    maxSize: { w: 6, h: 4 },
    refreshable: true,
    component: 'StatWidget',
    defaultSettings: {
      layout: 'grid',
      showIcons: true,
      showTrend: true
    }
  },
  
  // Quick actions widget
  quickActions: {
    type: 'quickActions',
    title: 'Quick Actions',
    description: 'Frequently used actions',
    category: 'general',
    icon: 'fas fa-bolt',
    roles: ['admin', 'hr', 'candidate'],
    defaultSize: { w: 12, h: 2 },
    minSize: { w: 6, h: 1 },
    maxSize: { w: 12, h: 3 },
    refreshable: false,
    component: 'QuickActionsWidget'
  },
  
  // Recent activity widget
  recentActivity: {
    type: 'recentActivity',
    title: 'Recent Activity',
    description: 'Latest system activities',
    category: 'general',
    icon: 'fas fa-history',
    roles: ['admin', 'hr', 'candidate'],
    defaultSize: { w: 12, h: 4 },
    minSize: { w: 6, h: 3 },
    maxSize: { w: 12, h: 8 },
    refreshable: true,
    component: 'ActivityWidget'
  }
};

/**
 * Get default layout for a specific role
 * @param {string} role - User role (admin, hr, candidate)
 * @returns {Array} Array of widget configurations
 */
export function getDefaultLayout(role) {
  const defaultLayouts = {
    admin: [
      createWidget('userStats', { x: 0, y: 0, w: 6, h: 4 }),
      createWidget('systemHealth', { x: 6, y: 0, w: 6, h: 4 }),
      createWidget('quickActions', { x: 0, y: 4, w: 12, h: 2 }),
      createWidget('recentActivity', { x: 0, y: 6, w: 12, h: 4 })
    ],
    hr: [
      createWidget('applicantStats', { x: 0, y: 0, w: 6, h: 4 }),
      createWidget('vacancyStatus', { x: 6, y: 0, w: 6, h: 4 }),
      createWidget('quickActions', { x: 0, y: 4, w: 12, h: 2 }),
      createWidget('recentActivity', { x: 0, y: 6, w: 12, h: 4 })
    ],
    candidate: [
      createWidget('resumeStats', { x: 0, y: 0, w: 4, h: 3 }),
      createWidget('jobMatches', { x: 4, y: 0, w: 8, h: 4 }),
      createWidget('applicationStatus', { x: 0, y: 3, w: 4, h: 3 }),
      createWidget('quickActions', { x: 0, y: 6, w: 12, h: 2 }),
      createWidget('recentActivity', { x: 0, y: 8, w: 12, h: 4 })
    ]
  };
  
  return defaultLayouts[role] || [];
}

/**
 * Create a widget configuration object
 * @param {string} type - Widget type
 * @param {Object} position - Position and size {x, y, w, h}
 * @param {Object} settings - Custom settings
 * @returns {Object} Widget configuration
 */
export function createWidget(type, position = {}, settings = {}) {
  const widgetConfig = DEFAULT_WIDGET_CONFIGS[type];
  if (!widgetConfig) {
    throw new Error(`Unknown widget type: ${type}`);
  }
  
  const id = `widget-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
  const { defaultSize, minSize, maxSize, defaultSettings = {} } = widgetConfig;
  
  return {
    id,
    type,
    title: widgetConfig.title,
    icon: widgetConfig.icon,
    x: position.x || 0,
    y: position.y || 0,
    w: position.w || defaultSize.w,
    h: position.h || defaultSize.h,
    minW: minSize.w,
    minH: minSize.h,
    maxW: maxSize.w,
    maxH: maxSize.h,
    settings: { ...defaultSettings, ...settings },
    refreshable: widgetConfig.refreshable,
    category: widgetConfig.category
  };
}

/**
 * Get widget configuration by type
 * @param {string} type - Widget type
 * @returns {Object} Widget configuration
 */
export function getWidgetConfig(type) {
  return DEFAULT_WIDGET_CONFIGS[type] || null;
}

/**
 * Get available widgets for a specific role and permissions
 * @param {string} role - User role
 * @param {Array} permissions - User permissions
 * @returns {Array} Available widgets
 */
export function getAvailableWidgets(role, permissions = []) {
  return Object.values(DEFAULT_WIDGET_CONFIGS).filter(widget => {
    // Check if widget is available for user's role
    const hasRole = widget.roles.includes(role);
    
    // Check if user has required permissions
    const hasPermissions = !widget.permissions || 
      widget.permissions.every(permission => permissions.includes(permission));
    
    return hasRole && hasPermissions;
  });
}

/**
 * Get widget component name by type
 * @param {string} type - Widget type
 * @returns {string} Component name
 */
export function getWidgetComponent(type) {
  const config = getWidgetConfig(type);
  return config?.component || 'BaseWidget';
}

/**
 * Load widget data from API
 * @param {string} type - Widget type
 * @param {Object} params - Query parameters
 * @returns {Promise} Promise with widget data
 */
export async function loadWidgetData(type, params = {}) {
  try {
    // TODO: Implement actual API call
    console.log(`Loading data for widget: ${type}`, params);
    
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Return mock data based on widget type
    return getMockWidgetData(type);
  } catch (error) {
    console.error(`Failed to load data for widget ${type}:`, error);
    throw error;
  }
}

/**
 * Generate mock data for widgets (for development)
 * @param {string} type - Widget type
 * @returns {Object} Mock data
 */
function getMockWidgetData(type) {
  const mockData = {
    userStats: {
      title: 'User Statistics',
      stats: [
        { label: 'Total Users', value: '2,345', icon: 'fas fa-users', trend: 12.5, trendDirection: 'up' },
        { label: 'New Today', value: '124', icon: 'fas fa-user-plus', trend: 5.2, trendDirection: 'up' },
        { label: 'Active Now', value: '489', icon: 'fas fa-user-check', trend: 2.1, trendDirection: 'down' },
        { label: 'Conversion', value: '3.2%', icon: 'fas fa-percentage', trend: 0.8, trendDirection: 'up' }
      ]
    },
    resumeStats: {
      title: 'Resume Statistics',
      stats: [
        { label: 'Total Resumes', value: '1,245', icon: 'fas fa-file-alt', trend: 8.7, trendDirection: 'up' },
        { label: 'Updated Today', value: '87', icon: 'fas fa-sync', trend: 2.3, trendDirection: 'up' },
        { label: 'Downloads', value: '324', icon: 'fas fa-download', trend: 1.2, trendDirection: 'down' },
        { label: 'Avg. Score', value: '87%', icon: 'fas fa-star', trend: 0.5, trendDirection: 'up' }
      ]
    },
    jobStats: {
      title: 'Job Statistics',
      stats: [
        { label: 'Total Jobs', value: '856', icon: 'fas fa-briefcase', trend: 15.3, trendDirection: 'up' },
        { label: 'New Today', value: '24', icon: 'fas fa-plus-circle', trend: 3.1, trendDirection: 'down' },
        { label: 'Applications', value: '1,234', icon: 'fas fa-paper-plane', trend: 8.9, trendDirection: 'up' },
        { label: 'Hire Rate', value: '4.2%', icon: 'fas fa-handshake', trend: 1.2, trendDirection: 'up' }
      ]
    },
    systemHealth: {
      title: 'System Health',
      status: 'operational',
      uptime: '99.98%',
      services: [
        { name: 'API Server', status: 'operational', responseTime: '142ms' },
        { name: 'Database', status: 'operational', responseTime: '28ms' },
        { name: 'Cache', status: 'operational', responseTime: '5ms' },
        { name: 'File Storage', status: 'degraded', responseTime: '356ms' },
        { name: 'Email Service', status: 'operational', responseTime: '42ms' }
      ]
    },
    recentActivity: {
      title: 'Recent Activity',
      items: [
        { id: 1, user: 'John Doe', action: 'created a new resume', time: '2 minutes ago', icon: 'fas fa-file-alt', type: 'create' },
        { id: 2, user: 'Jane Smith', action: 'applied for Senior Developer', time: '15 minutes ago', icon: 'fas fa-paper-plane', type: 'application' },
        { id: 3, user: 'System', action: 'scheduled maintenance', time: '1 hour ago', icon: 'fas fa-tools', type: 'system' },
        { id: 4, user: 'Alex Johnson', action: 'updated profile information', time: '2 hours ago', icon: 'fas fa-user-edit', type: 'update' },
        { id: 5, user: 'Maria Garcia', action: 'completed skill assessment', time: '3 hours ago', icon: 'fas fa-tasks', type: 'complete' }
      ]
    }
  };
  
  return mockData[type] || {};
}

/**
 * Save dashboard layout to storage
 * @param {string} role - User role
 * @param {Array} layout - Dashboard layout
 * @returns {Promise} Promise that resolves when layout is saved
 */
export async function saveDashboardLayout(role, layout) {
  try {
    // TODO: Implement actual API call to save layout
    console.log(`Saving layout for ${role}:`, layout);
    
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 300));
    
    // Save to localStorage as fallback
    localStorage.setItem(`dashboard:layout:${role}`, JSON.stringify(layout));
    
    return true;
  } catch (error) {
    console.error(`Failed to save layout for ${role}:`, error);
    throw error;
  }
}

/**
 * Load dashboard layout from storage
 * @param {string} role - User role
 * @returns {Promise<Array>} Promise that resolves with the layout
 */
export async function loadDashboardLayout(role) {
  try {
    // TODO: Implement actual API call to load layout
    console.log(`Loading layout for ${role}`);
    
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 300));
    
    // Try to load from localStorage as fallback
    const savedLayout = localStorage.getItem(`dashboard:layout:${role}`);
    
    if (savedLayout) {
      return JSON.parse(savedLayout);
    }
    
    // Return default layout if no saved layout exists
    return getDefaultLayout(role);
  } catch (error) {
    console.error(`Failed to load layout for ${role}:`, error);
    // Return default layout on error
    return getDefaultLayout(role);
  }
}
