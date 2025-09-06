import api from '@/services/api';

const vacancyApi = {
  /**
   * Get all vacancies with optional filters
   * @param {Object} params - Query parameters for filtering, sorting, and pagination
   */
  getAll(params = {}) {
    return api.get('/admin/vacancies', { 
      params: {
        ...params,
        include: 'company', // Always include company data
      } 
    });
  },

  /**
   * Get a single vacancy by ID
   * @param {number|string} id - The ID of the vacancy to fetch
   */
  get(id) {
    return api.get(`/admin/vacancies/${id}`, {
      params: {
        include: 'company,applications', // Include related data
      },
    });
  },

  /**
   * Create a new vacancy
   * @param {Object} data - The vacancy data to create
   */
  create(data) {
    return api.post('/admin/vacancies', data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  },

  /**
   * Update an existing vacancy
   * @param {number|string} id - The ID of the vacancy to update
   * @param {Object} data - The updated vacancy data
   */
  update(id, data) {
    return api.put(`/admin/vacancies/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  },

  /**
   * Delete a vacancy
   * @param {number|string} id - The ID of the vacancy to delete
   */
  delete(id) {
    return api.delete(`/admin/vacancies/${id}`);
  },

  /**
   * Publish a vacancy
   * @param {number|string} id - The ID of the vacancy to publish
   */
  publish(id) {
    return api.post(`/admin/vacancies/${id}/publish`);
  },

  /**
   * Unpublish a vacancy
   * @param {number|string} id - The ID of the vacancy to unpublish
   */
  unpublish(id) {
    return api.post(`/admin/vacancies/${id}/unpublish`);
  },

  /**
   * Get statistics for vacancies
   */
  getStats() {
    return api.get('/admin/vacancies/stats');
  },

  /**
   * Get similar vacancies
   * @param {number|string} id - The ID of the vacancy to find similar for
   */
  getSimilar(id) {
    return api.get(`/admin/vacancies/${id}/similar`);
  },
};

export default vacancyApi;
