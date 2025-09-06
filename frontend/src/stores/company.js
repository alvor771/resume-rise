import { defineStore } from 'pinia';
import companyApi from '@/api/company';
import { useAuthStore } from './auth';

export const useCompanyStore = defineStore('company', {
  state: () => ({
    companies: [],
    currentCompany: null,
    loading: false,
    error: null,
    pagination: {
      page: 1,
      perPage: 10,
      total: 0,
      lastPage: 1,
    },
    filters: {
      search: '',
      status: 'all',
    },
  }),

  getters: {
    getCompanyById: (state) => (id) => {
      return state.companies.find(company => company.id === id) || null;
    },
    
    filteredCompanies: (state) => {
      return state.companies.filter(company => {
        const matchesSearch = !state.filters.search || 
          company.name.toLowerCase().includes(state.filters.search.toLowerCase()) ||
          company.email?.toLowerCase().includes(state.filters.search.toLowerCase());
        
        const matchesStatus = state.filters.status === 'all' || 
          (state.filters.status === 'active' && company.is_active) ||
          (state.filters.status === 'inactive' && !company.is_active);
        
        return matchesSearch && matchesStatus;
      });
    },
  },

  actions: {
    /**
     * Fetch a paginated list of companies
     * @param {Object} params - Query parameters
     * @returns {Promise} - API response
     */
    async fetchCompanies(params = {}) {
      this.loading = true;
      this.error = null;
      
      try {
        // Ensure pagination parameters are numbers
        const page = parseInt(params.page || this.pagination.page) || 1;
        const perPage = parseInt(params.per_page || this.pagination.perPage) || 10;
        
        console.log('Fetching companies with params:', {
          page,
          per_page: perPage,
          ...this.filters,
          ...params,
        });
        
        const response = await companyApi.getCompanies({
          page,
          per_page: perPage,
          ...this.filters,
          ...params,
        });
        
        console.log('API Response:', response);
        
        if (!response) {
          console.error('No response received from API');
          throw new Error('No response received from server');
        }
        
        let companies = [];
        let paginationData = {};
        
        // Handle Laravel pagination format
        if (response.data && Array.isArray(response.data.data)) {
          companies = response.data.data;
          paginationData = {
            page: response.data.current_page || page,
            perPage: response.data.per_page || perPage,
            total: response.data.total || 0,
            lastPage: response.data.last_page || 1,
          };
        } 
        // Handle direct array response
        else if (Array.isArray(response.data)) {
          companies = response.data;
          paginationData = {
            page: 1,
            perPage: companies.length,
            total: companies.length,
            lastPage: 1,
          };
        }
        // Handle direct object response
        else if (response.data && typeof response.data === 'object') {
          companies = [response.data];
          paginationData = {
            page: 1,
            perPage: 1,
            total: 1,
            lastPage: 1,
          };
        }
        
        console.log('Processed companies data:', companies);
        
        // Update store state
        this.companies = companies;
        this.setPagination(paginationData);
        
        return { data: companies, pagination: paginationData };
      } catch (error) {
        console.error('Error in fetchCompanies:', error);
        this.error = error.response?.data?.message || error.message || 'Failed to fetch companies';
        this.companies = [];
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchCompany(id) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await companyApi.getCompany(id);
        this.currentCompany = response.data;
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch company';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createCompany(companyData) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await companyApi.createCompany(companyData);
        this.companies.unshift(response.data);
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create company';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateCompany({ id, ...data }) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await companyApi.updateCompany(id, data);
        const index = this.companies.findIndex(c => c.id === id);
        if (index !== -1) {
          this.companies[index] = response.data;
        }
        if (this.currentCompany?.id === id) {
          this.currentCompany = response.data;
        }
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update company';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteCompany(id) {
      this.loading = true;
      this.error = null;
      
      try {
        await companyApi.deleteCompany(id);
        this.companies = this.companies.filter(company => company.id !== id);
        if (this.currentCompany?.id === id) {
          this.currentCompany = null;
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete company';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    setFilters(filters) {
      this.filters = { ...this.filters, ...filters };
      this.pagination.page = 1; // Reset to first page when filters change
    },

    /**
     * Update pagination state
     * @param {Object} pagination - Pagination object with page, perPage, total, lastPage
     */
    setPagination(pagination) {
      this.pagination = {
        ...this.pagination,
        page: typeof pagination.page !== 'undefined' ? parseInt(pagination.page) || 1 : this.pagination.page,
        perPage: typeof pagination.perPage !== 'undefined' ? parseInt(pagination.perPage) || 10 : this.pagination.perPage,
        total: typeof pagination.total !== 'undefined' ? parseInt(pagination.total) || 0 : this.pagination.total,
        lastPage: typeof pagination.lastPage !== 'undefined' ? parseInt(pagination.lastPage) || 1 : this.pagination.lastPage,
      };
    },
    
    /**
     * Reset company store state
     */
    reset() {
      this.companies = [];
      this.currentCompany = null;
      this.loading = false;
      this.error = null;
      this.pagination = {
        page: 1,
        perPage: 10,
        total: 0,
        lastPage: 1,
      };
      this.filters = {
        search: '',
        status: 'all',
      };
    },
    
    /**
     * Check if user has permission for company actions
     * @param {string} permission - Permission to check
     * @returns {boolean} - Whether user has permission
     */
    hasPermission(permission) {
      const authStore = useAuthStore();
      return authStore.hasPermission(permission);
    },
    
    /**
     * Get company by ID from store or fetch from API if not found
     * @param {number|string} id - Company ID
     * @returns {Promise<Object>} - Company data
     */
    async fetchCompanyById(id) {
      const company = this.companies.find(c => c.id == id);
      if (company) return company;
      
      return await this.fetchCompany(id);
    },
    
    /**
     * Clear current company data
     */
    clearCurrentCompany() {
      this.currentCompany = null;
    },
  },
});
