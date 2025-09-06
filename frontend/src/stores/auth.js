import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(JSON.parse(localStorage.getItem('user')));
  const token = ref(localStorage.getItem('token'));
  const isAuthenticated = ref(!!token.value);
  const loading = ref(false);
  const error = ref(null);
  const router = useRouter();

  

  // Set default auth header if token exists
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
  }

  // Getters
  const currentUser = computed(() => user.value);
  const isLoggedIn = computed(() => isAuthenticated.value);
  const isLoading = computed(() => loading.value);
  const getError = computed(() => error.value);

  // Actions
  const setAuth = (userData, authToken) => {
    user.value = userData;
    token.value = authToken;
    isAuthenticated.value = true;
    
    // Store user data and token in localStorage
    localStorage.setItem('user', JSON.stringify(userData));
    localStorage.setItem('token', authToken);
    
    // Set default auth header
    axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
  };

  const clearAuth = () => {
    user.value = null;
    token.value = null;
    isAuthenticated.value = false;
    
    // Remove from localStorage
    localStorage.removeItem('user');
    localStorage.removeItem('token');
    
    // Remove auth header
    delete axios.defaults.headers.common['Authorization'];
  };

  const updateProfile = async (formData) => {
    const response = await axios.post('http://localhost:8000/api/user/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    user.value = response.data;
    return response;
  };

  const login = async (credentials) => {
    try {
      loading.value = true;
      error.value = null;
      
      const response = await axios.post('http://localhost:8000/api/login', {
        email: credentials.email,
        password: credentials.password,
        device_name: 'web-browser'
      });
      
      const { user: userData, access_token } = response.data;
      
      // Ensure user data has permissions array
      if (!userData.permissions) {
        userData.permissions = [];
      }
      
      setAuth(userData, access_token);
      console.log('User after login:', user.value);
      
      return userData;
    } catch (err) {
      const errorMessage = err.response?.data?.message || 'Login failed';
      error.value = errorMessage;
      throw new Error(errorMessage);
    } finally {
      loading.value = false;
    }
  };

  const register = async (userData) => {
    try {
      loading.value = true;
      error.value = null;
      
      const response = await axios.post('http://localhost:8000/api/register', {
        name: userData.name,
        email: userData.email,
        password: userData.password,
        password_confirmation: userData.password
      });
      
      const { user: registeredUser, access_token } = response.data;
      setAuth(registeredUser, access_token);
      
      return registeredUser;
    } catch (err) {
      const errorMessage = err.response?.data?.message || 'Registration failed';
      error.value = errorMessage;
      throw new Error(errorMessage);
    } finally {
      loading.value = false;
    }
  };

  const logout = async () => {
    try {
      await axios.post('http://localhost:8000/api/logout');
    } catch (err) {
      console.error('Error during logout:', err);
    } finally {
      clearAuth();
      router.push('/login');
    }
  };

  const checkAuth = async () => {
    if (!token.value) return false;
    
    try {
      const response = await axios.get('http://localhost:8000/api/user');
      
      // Ensure user data has permissions array
      if (!response.data.permissions) {
        response.data.permissions = [];
      }
      
      user.value = response.data;
      isAuthenticated.value = true;
      console.log('User after checkAuth:', user.value);
      return true;
    } catch (err) {
      console.error('Auth check failed:', err);
      clearAuth();
      return false;
    }
  };

  // Check if user has a specific permission
  const hasPermission = (permission) => {
    if (!user.value || !user.value.permissions) return false;
    return user.value.permissions.includes(permission);
  };

  return {
    // State
    user,
    isAuthenticated,
    loading,
    error,
    
    // Getters
    currentUser,
    isLoggedIn,
    isLoading,
    getError,
    
    // Methods
    hasPermission,
    
    // Actions
    updateProfile,
    login,
    register,
    logout,
    checkAuth,
    setAuth,
    clearAuth
  };
});
