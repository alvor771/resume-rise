import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { PERMISSIONS } from '@/config/roles';

export function useRBAC() {
  const authStore = useAuthStore();
  
  // Get current user's permissions from the auth store
  const userPermissions = computed(() => {
    return authStore.user?.permissions || [];
  });

  // Check if user has a specific permission
  const hasPermission = (permission) => {
    if (!permission) return true; // If no permission required, allow access
    return userPermissions.value.includes(permission);
  };

  // Check if user has any of the given permissions
  const hasAnyPermission = (permissions) => {
    if (!permissions || !permissions.length) return true;
    return permissions.some(permission => hasPermission(permission));
  };

  // Check if user has all of the given permissions
  const hasAllPermissions = (permissions) => {
    if (!permissions || !permissions.length) return true;
    return permissions.every(permission => hasPermission(permission));
  };

  // Check if user has a specific role
  const hasRole = (role) => {
    return authStore.user?.role === role;
  };

  // Check if user has any of the given roles
  const hasAnyRole = (roles) => {
    if (!roles || !roles.length) return true;
    return roles.some(role => hasRole(role));
  };

  // Expose the permission constants
  const permissions = PERMISSIONS;

  return {
    hasPermission,
    hasAnyPermission,
    hasAllPermissions,
    hasRole,
    hasAnyRole,
    permissions,
    userPermissions,
  };
}
