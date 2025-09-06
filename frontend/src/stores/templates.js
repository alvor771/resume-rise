import { defineStore } from 'pinia';
import axios from 'axios';

export const useTemplatesStore = defineStore('templates', {
  state: () => ({
    items: [],
    pagination: {
      current_page: 1,
      per_page: 12,
      total: 0,
      last_page: 1,
    },
    current: null,
    isLoading: false,
    error: null,
  }),

  actions: {
    async fetchList(params = {}) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/admin/templates', { params });
        const payload = response.data.data || response.data; // supports either {data: pagination} or direct pagination
        // Laravel paginate returns an object; data is at payload.data
        this.items = Array.isArray(payload) ? payload : (payload.data || []);
        if (!Array.isArray(payload)) {
          this.pagination = {
            current_page: payload.current_page,
            per_page: payload.per_page,
            total: payload.total,
            last_page: payload.last_page,
          };
        }
        return this.items;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch templates';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async fetchOne(id) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/admin/templates/${id}`);
        this.current = response.data.data || response.data;
        return this.current;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch template';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async create(payload) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.post('/api/admin/templates', payload);
        const created = response.data.data || response.data;
        this.items.unshift(created);
        return created;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create template';
        if (error.response?.data?.errors) throw error.response.data.errors;
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async update(id, payload) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.put(`/api/admin/templates/${id}`, payload);
        const updated = response.data.data || response.data;
        const idx = this.items.findIndex(t => t.id === id);
        if (idx !== -1) this.items[idx] = updated;
        if (this.current?.id === id) this.current = updated;
        return updated;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update template';
        if (error.response?.data?.errors) throw error.response.data.errors;
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async remove(id) {
      this.isLoading = true;
      this.error = null;
      try {
        await axios.delete(`/api/admin/templates/${id}`);
        this.items = this.items.filter(t => t.id !== id);
        if (this.current?.id === id) this.current = null;
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete template';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    clearCurrent() {
      this.current = null;
    },

    clearError() {
      this.error = null;
    }
  },

  getters: {
    getById: (state) => (id) => state.items.find(t => t.id === id),
    activeTemplates: (state) => state.items.filter(t => t.is_active),
    premiumTemplates: (state) => state.items.filter(t => t.is_premium),
  }
});
