import { useAuthStore } from '@/stores/auth';
import { useRBAC } from '@/composables/useRBAC';
import { PERMISSIONS } from '@/config/roles';

export function setupRBACGuard(router) {
  router.beforeEach((to, from, next) => {
    // Skip auth check for public routes
    if (to.meta.public) {
      return next();
    }

    const authStore = useAuthStore();
    const { hasPermission, hasAnyPermission, hasRole, hasAnyRole } = useRBAC();

    // Check if user is authenticated
    if (!authStore.isAuthenticated) {
      return next({ name: 'login', query: { redirect: to.fullPath } });
    }

    // Check route permissions
    const requiredPermissions = to.meta.permissions || [];
    const anyPermissions = to.meta.anyPermissions || [];
    const requiredRole = to.meta.role;
    const anyRoles = to.meta.roles || [];

    // Check required permissions
    if (requiredPermissions.length > 0) {
      const hasAll = requiredPermissions.every(perm => hasPermission(perm));
      if (!hasAll) {
        return next({ name: 'unauthorized' });
      }
    }

    // Check any permissions
    if (anyPermissions.length > 0) {
      const hasAny = anyPermissions.some(perm => hasPermission(perm));
      if (!hasAny) {
        return next({ name: 'unauthorized' });
      }
    }

    // Check required role
    if (requiredRole && !hasRole(requiredRole)) {
      return next({ name: 'unauthorized' });
    }

    // Check any roles
    if (anyRoles.length > 0) {
      const hasAny = anyRoles.some(role => hasRole(role));
      if (!hasAny) {
        return next({ name: 'unauthorized' });
      }
    }

    next();
  });
}

// Helper function to check all permissions
function hasAllPermissions(permissions) {
  const { hasPermission } = useRBAC();
  return permissions.every(permission => hasPermission(permission));
}

// Helper function to check any permission
function hasAnyPermission(permissions) {
  const { hasPermission } = useRBAC();
  return permissions.some(permission => hasPermission(permission));
}
