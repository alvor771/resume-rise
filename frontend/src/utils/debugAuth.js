/**
 * Debug utility to check authentication state and permissions
 * Run this in browser console to check current user's permissions
 */

const debugAuth = {
  // Get current user from localStorage
  getUser() {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
  },
  
  // Get current token from localStorage
  getToken() {
    return localStorage.getItem('token');
  },
  
  // Check if user is authenticated
  isAuthenticated() {
    return !!this.getToken();
  },
  
  // Get user's permissions
  getPermissions() {
    const user = this.getUser();
    return user?.permissions || [];
  },
  
  // Check if user has specific permission
  hasPermission(permission) {
    return this.getPermissions().includes(permission);
  },
  
  // Log all auth info to console
  logAuthInfo() {
    const user = this.getUser();
    console.log('=== Auth Debug Info ===');
    console.log('Is authenticated:', this.isAuthenticated());
    console.log('User:', user);
    console.log('Permissions:', this.getPermissions());
    console.log('Token:', this.getToken() ? '***' + this.getToken().slice(-8) : 'None');
    console.log('========================');
  },
  
  // Check RBAC related permissions
  checkRBACPermissions() {
    const requiredPermissions = [
      'view_users',
      'view_roles',
      'view_permissions'
    ];
    
    console.log('=== RBAC Permissions Check ===');
    requiredPermissions.forEach(perm => {
      console.log(`Has ${perm}:`, this.hasPermission(perm) ? '✅' : '❌');
    });
    console.log('==============================');
  }
};

// Make it available in browser console
window.debugAuth = debugAuth;

// Auto-log auth info when loaded
console.log('Auth debugger loaded. Type `debugAuth.logAuthInfo()` to see auth details.');
console.log('Check RBAC permissions with: `debugAuth.checkRBACPermissions()`');
