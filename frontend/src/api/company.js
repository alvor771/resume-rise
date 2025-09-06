import api from '@/services/api';

export default {
  async getCompanies(params = {}) {
    try {
      const response = await api.get('/admin/companies', { params });
      console.log('Raw API Response:', response);
      return response;
    } catch (error) {
      console.error('Error in getCompanies:', error);
      throw error;
    }
  },
  getCompany(id) {
    return api.get(`/admin/companies/${id}`);
  },
  createCompany(data) {
    return api.post('/admin/companies', data);
  },
  updateCompany(id, data) {
    return api.put(`/admin/companies/${id}`, data);
  },
  deleteCompany(id) {
    return api.delete(`/admin/companies/${id}`);
  }
};
