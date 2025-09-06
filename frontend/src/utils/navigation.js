import { useAuthStore } from '@/stores/auth';

export function buildNavigationFromRoutes(routes, parentPath = '') {
  const authStore = useAuthStore();
  
  return routes
    .filter(route => route.meta?.navOrder) // Only include routes with navOrder
    .sort((a, b) => (a.meta.navOrder || 999) - (b.meta.navOrder || 999)) // Sort by navOrder
    .map(route => {
      const fullPath = parentPath ? `${parentPath}/${route.path}` : `/${route.path}`;
      
      // Check if user has permission to see this route
      const hasPermission = !route.meta.permissions || 
        route.meta.permissions.some(permission => 
          authStore.hasPermission(permission)
        );
      
      if (!hasPermission) return null;
      
      return {
        name: route.meta.title || route.name,
        path: route.path,
        icon: route.meta.icon || 'DocumentTextIcon',
        permissions: route.meta.permissions,
        children: route.children ? buildNavigationFromRoutes(route.children, fullPath) : []
      };
    })
    .filter(Boolean); // Remove null entries (routes without permissions)
}

export function getAdminNavigation() {
  return [
    {
      name: 'dashboard',
      path: '/admin/dashboard',
      icon: 'HomeIcon',
      navOrder: 1
    },
    {
      name: 'rbac',
      path: '/admin/rbac',
      icon: 'LockClosedIcon',
      navOrder: 2,
      permissions: ['manage_roles', 'manage_users']
    },
    {
      name: 'companies',
      path: '/admin/companies',
      icon: 'OfficeBuildingIcon',
      navOrder: 3,
      permissions: ['view_companies']
    },
    {
      name: 'vacancies',
      path: '/admin/vacancies',
      icon: 'BriefcaseIcon',
      navOrder: 4,
      permissions: ['view_vacancies']
    },
    {
      name: 'resumes',
      path: '/admin/resumes',
      icon: 'DocumentTextIcon',
      navOrder: 5,
      permissions: ['view_resumes']
    },
    {
      name: 'templates',
      path: '/admin/templates',
      icon: 'TemplateIcon',
      navOrder: 6,
      permissions: ['view_templates']
    },
    {
      name: 'settings',
      path: '/admin/settings',
      icon: 'Cog6ToothIcon',
      navOrder: 100,
      permissions: ['manage_settings']
    }
  ];
}
