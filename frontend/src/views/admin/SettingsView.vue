<template>
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
      <div class="flex space-x-3">
        <AppButton
          variant="outline"
          @click="resetForm"
          :disabled="isLoading"
          class="inline-flex items-center"
        >
          <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ isLoading ? 'Loading...' : 'Reset' }}
        </AppButton>
        <AppButton
          @click="saveSettings"
          :disabled="isLoading"
          class="inline-flex items-center"
        >
          <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ isLoading ? 'Saving...' : 'Save Changes' }}
        </AppButton>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 text-center">
      <div class="animate-pulse flex flex-col items-center">
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-4"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
      </div>
    </div>

    <!-- Settings Groups -->
    <template v-else>
      <div v-for="(group, groupKey) in filteredSettingsGroups" :key="groupKey" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ titleCase(groupKey.replace(/_/g, ' ')) }}
            <span v-if="isGroupModified(groupKey)" class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
              Unsaved Changes
            </span>
          </h3>
          <p v-if="group._description" class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ group._description }}
          </p>
        </div>

        <div class="px-4 py-5 sm:p-0">
          <dl class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Loop through each setting in the group -->
            <div 
              v-for="[key, setting] in filteredSettings(group)" 
              :key="key"
              class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
              :class="{ 'bg-gray-50/50 dark:bg-gray-700/30': isModified(key) }"
            >
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400" v-if="setting.type !== 'boolean'">
                {{ setting?.label || titleCase(key.replace(/_/g, ' ')) }}
                <span v-if="isModified(key)" class="ml-1 text-xs text-yellow-600 dark:text-yellow-400">(modified)</span>
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2" :class="{ 'flex items-center justify-between': setting.type === 'boolean' }">
                <!-- Boolean Toggle -->
                <div v-if="setting.type === 'boolean'" class="flex items-center justify-between w-full">
                  <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ setting?.label || titleCase(key.replace(/_/g, ' ')) }}
                    <span v-if="isModified(key)" class="ml-1 text-xs text-yellow-600 dark:text-yellow-400">(modified)</span>
                  </label>
                  <ToggleSwitch
                    v-model="settings[key]"
                    :disabled="isLoading"
                    class="ml-4"
                  />
                </div>
                
                <!-- Text Input -->
                <BaseInput
                  v-else-if="!['select', 'password', 'textarea'].includes(setting.type)"
                  v-model="settings[key]"
                  :type="setting.type || 'text'"
                  :label="setting.label || ''"
                  :placeholder="setting.placeholder || ''"
                  :required="setting.required"
                  :disabled="isLoading"
                  :error="setting.error"
                  class="w-full"
                />
                
                <!-- Textarea -->
                <BaseTextarea
                  v-else-if="setting.type === 'textarea'"
                  v-model="settings[key]"
                  :label="setting.label || ''"
                  :placeholder="setting.placeholder || ''"
                  :rows="setting.rows || 3"
                  :required="setting.required"
                  :disabled="isLoading"
                  :error="setting.error"
                  class="w-full"
                />
                
                
                <!-- Select -->
                <select
                  v-else-if="setting.type === 'select' && setting.options"
                  v-model="settings[key]"
                  :disabled="isLoading"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-50"
                >
                  <option v-for="(optionLabel, optionValue) in setting.options" :key="optionValue" :value="optionValue">
                    {{ optionLabel }}
                  </option>
                </select>
                
                <!-- Password Input (masked) -->
                <div v-else-if="setting.type == 'password'" class="relative rounded-md shadow-sm">
                  ---- password ----
                  <PasswordInput
                    :id="key"
                    v-model="settings[key]"
                    :disabled="isLoading"
                    :error="setting.error"
                    :required="setting.required"
                    :placeholder="setting.placeholder || ''"
                    class="w-full"
                  />
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                </div>
                
                <p v-if="setting.help" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                  {{ setting.help }}
                </p>
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Danger Zone -->
      <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-red-200 dark:border-red-900/50">
          <h3 class="text-lg leading-6 font-medium text-red-700 dark:text-red-300">Danger Zone</h3>
          <p class="mt-1 max-w-2xl text-sm text-red-600 dark:text-red-400">
            These actions are irreversible. Please be careful.
          </p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="border border-red-200 dark:border-red-900/50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Clear Application Cache</h4>
              <p class="mt-1 text-xs text-red-700 dark:text-red-400">
                Clear all cached data. This may improve performance if you're experiencing issues.
              </p>
              <div class="mt-4">
                <button
                  @click="clearCache"
                  :disabled="isLoading"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isLoading ? 'Clearing...' : 'Clear Cache' }}
                </button>
              </div>
            </div>
            
            <div class="border border-red-200 dark:border-red-900/50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Reset All Settings</h4>
              <p class="mt-1 text-xs text-red-700 dark:text-red-400">
                Reset all settings to their default values. This cannot be undone.
              </p>
              <div class="mt-4">
                <button
                  @click="confirmResetSettings"
                  :disabled="isLoading"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isLoading ? 'Resetting...' : 'Reset Settings' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

  
<script>
import { ref, onMounted, computed } from 'vue';
import { useToast } from 'vue-toastification';
import * as settingsApi from '@/api/settings';
import AppButton from '@/components/ui/Button.vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseTextarea from '@/components/ui/BaseTextarea.vue';
import ToggleSwitch from '@/components/ui/ToggleSwitch.vue';
import PasswordInput from '@/components/ui/PasswordInput.vue';

export default {
  name: 'SettingsView',
  components: {
    AppButton,
    BaseInput,
    BaseTextarea,
    ToggleSwitch,
    PasswordInput
  },
  setup() {
    const toast = useToast();
    const isLoading = ref(false);
    
    // Timezones list
    const timezones = [
      'UTC',
      'America/New_York',
      'America/Chicago',
      'America/Denver',
      'America/Los_Angeles',
      'Europe/London',
      'Europe/Paris',
      'Asia/Tokyo',
      'Asia/Shanghai',
      'Australia/Sydney'
    ];
    
    // Settings state
    const settings = ref({});
    const originalSettings = ref({});
    const settingsGroups = ref({});
    
    // Computed property for filtered settings
    const filteredSettingsGroups = computed(() => {
      const result = {};
      Object.entries(settingsGroups.value).forEach(([groupKey, group]) => {
        if (!group) return;
        
        const filteredGroup = {};
        Object.entries(group).forEach(([key, setting]) => {
          if (!key.startsWith('_') && setting) {
            filteredGroup[key] = typeof setting === 'object' ? setting : { value: setting };
          }
        });
        
        if (Object.keys(filteredGroup).length > 0) {
          result[groupKey] = filteredGroup;
        }
      });
      return result;
    });
    
    // Computed property for filtered settings in a group
    const filteredSettings = computed(() => (group) => {
      if (!group) return [];
      return Object.entries(group)
        .filter(([key]) => !key.startsWith('_'))
        .map(([key, setting]) => [key, typeof setting === 'object' ? setting : { value: setting }]);
    });
    
    // Load settings from API
    const loadSettings = async () => {
      try {
        isLoading.value = true;
        const response = await settingsApi.getSettings();
        
        // Flatten the settings for the form
        const flattened = {};
        Object.values(response).forEach(group => {
          Object.entries(group).forEach(([key, value]) => {
            flattened[key] = value;
          });
        });
        
        settings.value = flattened;
        originalSettings.value = JSON.parse(JSON.stringify(flattened));
        settingsGroups.value = response;
      } catch (error) {
        console.error('Failed to load settings:', error);
        toast.error('Failed to load settings');
      } finally {
        isLoading.value = false;
      }
    };
    
    // Save settings to API
    const saveSettings = async () => {
      try {
        isLoading.value = true;
        await settingsApi.updateSettings(settings.value);
        toast.success('Settings saved successfully');
        await loadSettings();
      } catch (error) {
        console.error('Failed to save settings:', error);
        toast.error('Failed to save settings');
      } finally {
        isLoading.value = false;
      }
    };
    
    // Reset form to last saved settings
    const resetForm = () => {
      settings.value = JSON.parse(JSON.stringify(originalSettings.value));
      toast.info('Form reset to last saved settings');
    };
    
    // Reset all settings to defaults
    const resetAllSettings = async () => {
      if (!confirm('Are you sure you want to reset all settings to their default values? This cannot be undone.')) {
        return;
      }
      
      try {
        isLoading.value = true;
        await settingsApi.resetSettings();
        toast.success('All settings have been reset to defaults');
        await loadSettings();
      } catch (error) {
        console.error('Failed to reset settings:', error);
        toast.error('Failed to reset settings');
      } finally {
        isLoading.value = false;
      }
    };
    
    // Clear application cache
    const clearCache = async () => {
      try {
        isLoading.value = true;
        await settingsApi.clearCache();
        toast.success('Application cache cleared successfully');
      } catch (error) {
        console.error('Failed to clear cache:', error);
        toast.error('Failed to clear cache');
      } finally {
        isLoading.value = false;
      }
    };
    
    // Alias for danger zone button
    const confirmResetSettings = resetAllSettings;
    
    // Load settings when component mounts
    onMounted(() => {
      loadSettings();
    });
    
    // Check if settings have been modified
    const isModified = (key) => {
      return JSON.stringify(settings.value[key]) !== JSON.stringify(originalSettings.value[key]);
    };
    
    // Get setting value by key with a default fallback
    const getSetting = (key, defaultValue = '') => {
      return settings.value[key] !== undefined ? settings.value[key] : defaultValue;
    };
    
    // Check if any setting in a group is modified
    const isGroupModified = (groupKey) => {
      if (!settingsGroups.value[groupKey]) return false;
      return Object.keys(settingsGroups.value[groupKey]).some(key => isModified(key));
    };
    
    return {
      timezones,
      settings,
      settingsGroups,
      isLoading,
      saveSettings,
      resetForm,
      confirmResetSettings,
      clearCache,
      isModified,
      isGroupModified,
      getSetting,
      titleCase: (str) => str?.replace(/\b\w/g, char => char.toUpperCase()) || '',
      filteredSettings,
      filteredSettingsGroups
    };
  }
};
</script>
