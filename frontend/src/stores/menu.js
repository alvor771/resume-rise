import { defineStore } from 'pinia';
import { ref, computed, watch } from 'vue';
import axios from 'axios';

export const useMenuStore = defineStore('menu', () => {
  // State
  const menuItems = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const selectedMenuItem = ref(null);

  // Getters
  const getMenuItems = computed(() => menuItems.value);
  const getLoading = computed(() => loading.value);
  const getError = computed(() => error.value);
  const getSelectedMenuItem = computed(() => selectedMenuItem.value);

  watch(menuItems, (newVal) => {
    console.log('Menu items:', newVal);
  }, { immediate: true, deep: true });

  // Actions
  const fetchMenuItems = async () => {
    loading.value = true;
    error.value = null;
    
    try {
      const apiRoute = 'http://localhost:8000/api/menu';
      const response = await axios.get(apiRoute);
      console.log('API Response:', response.data); // Debug log
      // Handle both array and single object responses
      const items = Array.isArray(response.data) ? response.data : [response.data];
      console.log('Processed items:', items); // Debug log
      menuItems.value = items;
    } catch (err) {
      console.error('Error fetching menu items:', err);
      error.value = err.response?.data?.message || 'Failed to load menu items';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const createMenuItem = async (menuItemData) => {
    loading.value = true;
    error.value = null;
    
    try {
      const response = await axios.post('http://localhost:8000/api/menu', menuItemData);
      await fetchMenuItems(); // Refresh the menu items
      return response.data;
    } catch (err) {
      console.error('Error creating menu item:', err);
      error.value = err.response?.data?.message || 'Failed to create menu item';
      throw err;
    } finally {
      loading.value = false;
    }
  };
  
  
  const updateMenuItem = async (id, menuItemData) => {
    loading.value = true;
    error.value = null;
    
    try {
      const response = await axios.put(`http://192.168.1.21:8000/api/menu/${id}`, menuItemData);
      
      // Force refresh the menu items to avoid any state inconsistencies
      await fetchMenuItems();
      return response.data;
    } catch (err) {
      console.error('Error updating menu item:', err);
      error.value = err.response?.data?.message || 'Failed to update menu item';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const deleteMenuItem = async (id) => {
    loading.value = true;
    error.value = null;
    
    try {
      await axios.delete(`http://192.168.1.21:8000/api/menu/${id}`);
      // Force refresh the menu items to ensure consistency
      await fetchMenuItems();
    } catch (err) {
      console.error('Error deleting menu item:', err);
      error.value = err.response?.data?.message || 'Failed to delete menu item';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const reorderMenuItems = async (items) => {
    loading.value = true;
    error.value = null;
    
    try {
      await axios.post('http://192.168.1.21:8000/api/menu/reorder', { items });
      await fetchMenuItems(); // Refresh the menu items
    } catch (err) {
      console.error('Error reordering menu items:', err);
      error.value = err.response?.data?.message || 'Failed to reorder menu items';
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const setSelectedMenuItem = (menuItem) => {
    selectedMenuItem.value = menuItem;
  };

  // Get menu items filtered by authentication status and permissions
  const getFilteredMenuItems = computed(() => {
    return (isAuthenticated, userPermissions = []) => {
      const filterItems = (items) => {
        return items.filter(item => {
          // Check visibility based on auth status
          if (isAuthenticated && item.is_guest) return false;
          if (!isAuthenticated && item.is_auth) return false;
          
          // Check permissions if any are required
          if (item.permissions && item.permissions.length > 0) {
            const hasPermission = item.permissions.some(permission => 
              userPermissions.includes(permission)
            );
            if (!hasPermission) return false;
          }
          
          // Recursively filter children
          if (item.children && item.children.length > 0) {
            item.children = filterItems(item.children);
          }
          
          return item.is_visible !== false; // Default to visible if not specified
        });
      };
      
      return filterItems([...menuItems.value]);
    };
  });

  // Get layout for a specific route
  const getLayoutForRoute = (routePath) => {
    const findInItems = (items, path) => {
      for (const item of items) {
        if (item.path === path) {
          return item.layout || 'default';
        }
        
        if (item.children && item.children.length > 0) {
          const childLayout = findInItems(item.children, path);
          if (childLayout) return childLayout;
        }
      }
      return 'default'; // Default layout if no match found
    };
    
    return findInItems(menuItems.value, routePath);
  };

  return {
    // State
    menuItems,
    loading,
    error,
    selectedMenuItem,
    
    // Getters
    getMenuItems,
    getLoading,
    getError,
    getSelectedMenuItem,
    getFilteredMenuItems,
    getLayoutForRoute,
    
    // Actions
    fetchMenuItems,
    createMenuItem,
    updateMenuItem,
    deleteMenuItem,
    reorderMenuItems,
    setSelectedMenuItem
  };
});
