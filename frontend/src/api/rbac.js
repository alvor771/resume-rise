import api from '@/services/api';

export default {
  // Roles
  getRoles() {
    return api.get('/admin/roles');
  },
  getRole(id) {
    return api.get(`/admin/roles/${id}`);
  },
  createRole(data) {
    return api.post('/admin/roles', data);
  },
  updateRole(id, data) {
    return api.put(`/admin/roles/${id}`, data);
  },
  deleteRole(id) {
    return api.delete(`/admin/roles/${id}`);
  },
  getRolePermissions(roleId) {
    return api.get(`/admin/roles/${roleId}/permissions`);
  },

  // Permissions
  getPermissions() {
    return api.get('/admin/permissions');
  },
  getPermission(id) {
    return api.get(`/admin/permissions/${id}`);
  },
  createPermission(data) {
    return api.post('/admin/permissions', data);
  },
  updatePermission(id, data) {
    return api.put(`/admin/permissions/${id}`, data);
  },
  deletePermission(id) {
    return api.delete(`/admin/permissions/${id}`);
  },

  // Users
  getUsersWithRoles() {
    return api.get('/admin/users/with-roles');
  },
  getUserRoles(userId) {
    return api.get(`/admin/users/${userId}/roles`);
  },
  assignUserRoles(userId, roleIds) {
    return api.post(`/admin/users/${userId}/roles`, { role_ids: roleIds });
  },
  removeUserRole(userId, roleId) {
    return api.delete(`/admin/users/${userId}/roles/${roleId}`);
  }
};
