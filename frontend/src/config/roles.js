// Define all available permissions in the system
export const PERMISSIONS = {
  // User management
  VIEW_USERS: 'view_users',
  CREATE_USERS: 'create_users',
  EDIT_USERS: 'edit_users',
  DELETE_USERS: 'delete_users',
  
  // Role management
  VIEW_ROLES: 'view_roles',
  CREATE_ROLES: 'create_roles',
  EDIT_ROLES: 'edit_roles',
  DELETE_ROLES: 'delete_roles',
  ASSIGN_ROLES: 'assign_roles',
  
  // Permission management
  VIEW_PERMISSIONS: 'view_permissions',
  EDIT_PERMISSIONS: 'edit_permissions',
  
  // Template management
  VIEW_TEMPLATES: 'view_templates',
  CREATE_TEMPLATES: 'create_templates',
  EDIT_TEMPLATES: 'edit_templates',
  DELETE_TEMPLATES: 'delete_templates',
  
  // Resume management
  VIEW_RESUMES: 'view_resumes',
  CREATE_RESUMES: 'create_resumes',
  EDIT_RESUMES: 'edit_resumes',
  DELETE_RESUMES: 'delete_resumes',
  
  // Analytics
  VIEW_ANALYTICS: 'view_analytics',
  EXPORT_ANALYTICS: 'export_analytics',
  
  // Settings
  MANAGE_SETTINGS: 'manage_settings',
  
  // Admin
  SUPER_ADMIN: 'super_admin',
};

// Define roles and their permissions
const ROLES = {
  SUPER_ADMIN: {
    name: 'Super Admin',
    slug: 'super-admin',
    description: 'Has full access to all features and settings',
    permissions: Object.values(PERMISSIONS),
  },
  ADMIN: {
    name: 'Administrator',
    slug: 'admin',
    description: 'Can manage users, templates, and view analytics',
    permissions: [
      // User management
      PERMISSIONS.VIEW_USERS,
      PERMISSIONS.CREATE_USERS,
      PERMISSIONS.EDIT_USERS,
      
      // Role management (view only)
      PERMISSIONS.VIEW_ROLES,
      
      // Template management
      PERMISSIONS.VIEW_TEMPLATES,
      PERMISSIONS.CREATE_TEMPLATES,
      PERMISSIONS.EDIT_TEMPLATES,
      
      // Resume management
      PERMISSIONS.VIEW_RESUMES,
      
      // Analytics
      PERMISSIONS.VIEW_ANALYTICS,
      PERMISSIONS.EXPORT_ANALYTICS,
    ],
  },
  EDITOR: {
    name: 'Editor',
    description: 'Can manage templates and view analytics',
    permissions: [
      PERMISSIONS.VIEW_TEMPLATES,
      PERMISSIONS.CREATE_TEMPLATES,
      PERMISSIONS.EDIT_TEMPLATES,
      PERMISSIONS.VIEW_ANALYTICS,
    ],
  },
  USER: {
    name: 'User',
    description: 'Can create and manage their own resumes',
    permissions: [
      PERMISSIONS.VIEW_RESUMES,
      PERMISSIONS.CREATE_RESUMES,
      PERMISSIONS.EDIT_RESUMES,
      PERMISSIONS.DELETE_RESUMES,
    ],
  },
};

export default ROLES;
