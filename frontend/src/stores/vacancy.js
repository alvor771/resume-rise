import { defineStore } from 'pinia';
import vacancyApi from '@/api/vacancy';

export const useVacancyStore = defineStore('vacancy', {
  state: () => ({
    vacancies: [],
    currentVacancy: null,
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
      published: 'all',
      company_id: null,
      employment_type: null,
      experience_level: null,
    },
  }),

  getters: {
    getVacancyById: (state) => (id) => {
      return state.vacancies.find(vacancy => vacancy.id === id) || null;
    },
    
    filteredVacancies: (state) => {
      return state.vacancies.filter(vacancy => {
        const matchesSearch = !state.filters.search || 
          vacancy.title.toLowerCase().includes(state.filters.search.toLowerCase()) ||
          vacancy.description?.toLowerCase().includes(state.filters.search.toLowerCase());
        
        const matchesStatus = state.filters.status === 'all' || 
          (state.filters.status === 'active' && vacancy.is_active) ||
          (state.filters.status === 'inactive' && !vacancy.is_active);
        
        const matchesPublished = state.filters.published === 'all' || 
          (state.filters.published === 'published' && vacancy.is_published) ||
          (state.filters.published === 'draft' && !vacancy.is_published);
        
        const matchesCompany = !state.filters.company_id || 
          vacancy.company_id === state.filters.company_id;
          
        const matchesEmploymentType = !state.filters.employment_type || 
          vacancy.employment_type === state.filters.employment_type;
          
        const matchesExperienceLevel = !state.filters.experience_level || 
          vacancy.experience_level === state.filters.experience_level;
        
        return matchesSearch && matchesStatus && matchesPublished && 
               matchesCompany && matchesEmploymentType && matchesExperienceLevel;
      });
    },
  },

  actions: {
    async fetchVacancies(params = {}) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await vacancyApi.getAll({
          page: this.pagination.page,
          per_page: this.pagination.perPage,
          search: this.filters.search,
          status: this.filters.status === 'all' ? null : this.filters.status,
          published: this.filters.published === 'all' ? null : this.filters.published,
          company_id: this.filters.company_id,
          employment_type: this.filters.employment_type,
          experience_level: this.filters.experience_level,
          ...params,
        });
        
        this.vacancies = response.data.data;
        this.pagination = {
          page: response.data.current_page,
          perPage: parseInt(response.data.per_page),
          total: response.data.total,
          lastPage: response.data.last_page,
        };
      } catch (error) {
        this.error = error.response?.data?.message || error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchVacancy(id) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await vacancyApi.get(id);
        this.currentVacancy = response.data.data;
        return this.currentVacancy;
      } catch (error) {
        this.error = error.response?.data?.message || error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createVacancy(vacancyData) {
      this.loading = true;
      this.error = null;
      
      // Format the data before sending
      const formattedData = {
        ...vacancyData,
        salary_min: vacancyData.salary_min ? parseFloat(vacancyData.salary_min) : null,
        salary_max: vacancyData.salary_max ? parseFloat(vacancyData.salary_max) : null,
        requirements: Array.isArray(vacancyData.requirements) ? 
          vacancyData.requirements : 
          (vacancyData.requirements || '').split('\n').filter(Boolean),
        benefits: Array.isArray(vacancyData.benefits) ? 
          vacancyData.benefits : 
          (vacancyData.benefits || '').split('\n').filter(Boolean),
        is_remote: !!vacancyData.is_remote,
        is_active: vacancyData.is_active !== false,
        is_published: !!vacancyData.is_published,
      };
      
      try {
        const response = await vacancyApi.create(formattedData);
        this.vacancies.unshift(response.data.data);
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || error.message;
        if (error.response?.data?.errors) {
          throw error.response.data.errors;
        }
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateVacancy({ id, ...data }) {
      this.loading = true;
      this.error = null;
      
      // Format the data before sending
      const formattedData = {
        ...data,
        salary_min: data.salary_min ? parseFloat(data.salary_min) : null,
        salary_max: data.salary_max ? parseFloat(data.salary_max) : null,
        requirements: Array.isArray(data.requirements) ? 
          data.requirements : 
          (data.requirements || '').split('\n').filter(Boolean),
        benefits: Array.isArray(data.benefits) ? 
          data.benefits : 
          (data.benefits || '').split('\n').filter(Boolean),
        is_remote: !!data.is_remote,
        is_active: data.is_active !== false,
        is_published: !!data.is_published,
      };
      
      try {
        const response = await vacancyApi.update(id, formattedData);
        const index = this.vacancies.findIndex(v => v.id === id);
        if (index !== -1) {
          this.vacancies[index] = response.data.data;
        }
        if (this.currentVacancy?.id === id) {
          this.currentVacancy = response.data.data;
        }
        return response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || error.message;
        if (error.response?.data?.errors) {
          throw error.response.data.errors;
        }
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteVacancy(id) {
      this.loading = true;
      this.error = null;
      
      try {
        await vacancyApi.deleteVacancy(id);
        this.vacancies = this.vacancies.filter(vacancy => vacancy.id !== id);
        if (this.currentVacancy?.id === id) {
          this.currentVacancy = null;
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete vacancy';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async publishVacancy(id) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await vacancyApi.publishVacancy(id);
        const index = this.vacancies.findIndex(v => v.id === id);
        if (index !== -1) {
          this.vacancies[index] = response.data;
        }
        if (this.currentVacancy?.id === id) {
          this.currentVacancy = response.data;
        }
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to publish vacancy';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async unpublishVacancy(id) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await vacancyApi.unpublishVacancy(id);
        const index = this.vacancies.findIndex(v => v.id === id);
        if (index !== -1) {
          this.vacancies[index] = response.data;
        }
        if (this.currentVacancy?.id === id) {
          this.currentVacancy = response.data;
        }
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to unpublish vacancy';
        throw error;
      } finally {
        this.loading = false;
      }
    },

    setFilters(filters) {
      this.filters = { ...this.filters, ...filters };
      this.pagination.page = 1; // Reset to first page when filters change
    },

    setPagination(pagination) {
      this.pagination = { ...this.pagination, ...pagination };
    },
  },
});
