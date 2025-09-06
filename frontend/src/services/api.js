import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

// Helper function to safely get environment variables
const getEnv = (key, defaultValue) => {
  try {
    return import.meta.env[key] || defaultValue;
  } catch (error) {
    console.warn(`Failed to access environment variable ${key}:`, error);
    return defaultValue;
  }
};

// API Configuration
const API_CONFIG = {
  baseURL: getEnv('VITE_API_URL', 'http://localhost:8000/api'),
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-Socket-ID': window.Echo?.socketId?.() || ''
  },
  withCredentials: true,
  withXSRFToken: true
};

// Log the API configuration for debugging
console.log('API Configuration:', API_CONFIG);

// Create axios instance with default config
const api = axios.create(API_CONFIG);

// Add a request interceptor to include auth token
api.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();
    const token = authStore.token;
    
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Add a response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Handle 401 Unauthorized errors
    if (error.response && error.response.status === 401) {
      const authStore = useAuthStore();
      authStore.logout();
      // Redirect to login page
      window.location.href = '/login';
    }
    
    // Handle 419 CSRF token mismatch
    if (error.response && error.response.status === 419) {
      // Refresh CSRF token and retry the request
      return axios.get('/sanctum/csrf-cookie').then(() => {
        return api(error.config);
      });
    }
    
    return Promise.reject(error);
  }
);

export default api;
