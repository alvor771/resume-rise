import api from '@/services/api';

/**
 * Get all settings
 * @returns {Promise<Object>} Settings grouped by category
 */
export const getSettings = async () => {
  try {
    const response = await api.get('/admin/settings');
    return response.data;
  } catch (error) {
    console.error('Error fetching settings:', error);
    throw error;
  }
};

/**
 * Update settings
 * @param {Object} settings - Settings to update in format { key: value }
 * @returns {Promise<Object>} Response data
 */
export const updateSettings = async (settings) => {
  try {
    // Convert settings object to array of {key, value} objects
    const settingsArray = Object.entries(settings).map(([key, value]) => ({
      key,
      value: value === undefined ? '' : value
    }));

    const response = await api.put('/api/admin/settings', {
      settings: settingsArray
    });
    
    return response.data;
  } catch (error) {
    console.error('Error updating settings:', error);
    throw error;
  }
};

/**
 * Clear application cache
 * @returns {Promise<Object>} Response data
 */
export const clearCache = async () => {
  try {
    const response = await api.post('/admin/settings', { settings: { clear_cache: true } });
    return response.data;
  } catch (error) {
    console.error('Error clearing cache:', error);
    throw error;
  }
};

/**
 * Reset all settings to defaults
 * @returns {Promise<Object>} Response data
 */
export const resetSettings = async () => {
  try {
    const response = await api.post('/admin/settings/reset');
    return response.data;
  } catch (error) {
    console.error('Error resetting settings:', error);
    throw error;
  }
};

export default {
  getSettings,
  updateSettings,
  clearCache,
  resetSettings
};
