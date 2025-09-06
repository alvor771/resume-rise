import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
  state: () => ({
    users: [],
    currentUser: null,
    isLoading: false,
    error: null
  }),

  actions: {
    async fetchUsers(params = {}) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/admin/users', { params });
        this.users = response.data.data || [];
        return this.users;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch users';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async fetchUser(userId) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/admin/users/${userId}`);
        this.currentUser = response.data.data;
        return this.currentUser;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch user';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async createUser(userData) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.post('/api/admin/users', userData);
        const newUser = response.data.data;
        this.users.push(newUser);
        return newUser;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create user';
        if (error.response?.data?.errors) {
          throw error.response.data.errors;
        }
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async updateUser(userId, userData) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.put(`/api/admin/users/${userId}`, userData);
        const updatedUser = response.data.data;
        
        // Update in users array if exists
        const index = this.users.findIndex(u => u.id === userId);
        if (index !== -1) {
          this.users[index] = updatedUser;
        }
        
        // Update current user if it's the one being updated
        if (this.currentUser?.id === userId) {
          this.currentUser = updatedUser;
        }
        
        return updatedUser;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update user';
        if (error.response?.data?.errors) {
          throw error.response.data.errors;
        }
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    async deleteUser(userId) {
      this.isLoading = true;
      this.error = null;
      try {
        await axios.delete(`/api/admin/users/${userId}`);
        
        // Remove from users array
        this.users = this.users.filter(user => user.id !== userId);
        
        // Clear current user if it's the one being deleted
        if (this.currentUser?.id === userId) {
          this.currentUser = null;
        }
        
        return true;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete user';
        throw error;
      } finally {
        this.isLoading = false;
      }
    },
    
    clearCurrentUser() {
      this.currentUser = null;
    },
    
    clearError() {
      this.error = null;
    }
  },
  
  getters: {
    getUserById: (state) => (userId) => {
      return state.users.find(user => user.id === userId);
    },
    
    getUsersByRole: (state) => (role) => {
      return state.users.filter(user => user.role === role);
    },
    
    getActiveUsers: (state) => {
      return state.users.filter(user => user.is_active);
    }
  }
});
