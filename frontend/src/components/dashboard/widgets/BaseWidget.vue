<template>
  <div 
    class="widget" 
    :class="[
      `widget-${widget.type}`,
      { 'widget-loading': loading, 'widget-error': error }
    ]"
  >
    <!-- Widget Header -->
    <div class="widget-header" @dblclick="toggleEditMode">
      <div class="widget-title">
        <i v-if="widget.icon" :class="[widget.icon, 'mr-2']"></i>
        <h3>{{ title }}</h3>
      </div>
      
      <div class="widget-actions">
        <button 
          v-if="editable"
          @click.stop="toggleSettings"
          class="widget-action-btn"
          :title="$t('dashboard.actions.settings')"
        >
          <i class="fas fa-cog"></i>
        </button>
        
        <button 
          v-if="widget.refreshable !== false"
          @click.stop="refreshWidget"
          class="widget-action-btn"
          :title="$t('dashboard.actions.refresh')"
          :disabled="loading"
        >
          <i class="fas fa-sync" :class="{ 'fa-spin': loading }"></i>
        </button>
      </div>
    </div>
    
    <!-- Widget Content -->
    <div class="widget-content">
      <!-- Loading State -->
      <div v-if="loading" class="widget-loading-state">
        <i class="fas fa-spinner fa-spin text-primary-500"></i>
        <span class="mt-2 text-sm text-gray-500">{{ $t('common.loading') }}...</span>
      </div>
      
      <!-- Error State -->
      <div v-else-if="error" class="widget-error-state">
        <i class="fas fa-exclamation-triangle text-red-500"></i>
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </p>
        <button 
          @click="refreshWidget" 
          class="mt-2 text-xs text-primary-500 hover:underline"
        >
          {{ $t('common.retry') }}
        </button>
      </div>
      
      <!-- Content Slot -->
      <slot v-else></slot>
    </div>
    
    <!-- Widget Settings -->
    <div v-if="showSettings" class="widget-settings">
      <div class="widget-settings-header">
        <h4>{{ $t('dashboard.widget_settings') }}</h4>
        <button @click="closeSettings" class="text-gray-400 hover:text-gray-500">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="widget-settings-body">
        <slot name="settings">
          <div class="setting-group">
            <label class="setting-label">
              {{ $t('dashboard.widget_title') }}
            </label>
            <input 
              v-model="localTitle" 
              type="text" 
              class="setting-input"
              @keyup.enter="saveSettings"
            >
          </div>
        </slot>
      </div>
      
      <div class="widget-settings-footer">
        <button 
          @click="saveSettings" 
          class="btn btn-sm btn-primary"
        >
          {{ $t('common.save') }}
        </button>
        <button 
          @click="closeSettings" 
          class="btn btn-sm btn-outline ml-2"
        >
          {{ $t('common.cancel') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue';
import { useI18n } from 'vue-i18n';

export default {
  name: 'BaseWidget',
  props: {
    widget: {
      type: Object,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    error: {
      type: [String, null],
      default: null
    },
    editable: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:settings', 'refresh'],
  setup(props, { emit }) {
    const { t } = useI18n();
    
    const showSettings = ref(false);
    const localTitle = ref(props.widget.title);
    const localSettings = ref({ ...(props.widget.settings || {}) });
    
    const title = computed(() => {
      return props.widget.settings?.title || localTitle.value || t(`widgets.${props.widget.type}.title`);
    });
    
    const toggleEditMode = () => {
      if (props.editable) {
        showSettings.value = !showSettings.value;
      }
    };
    
    const toggleSettings = () => {
      showSettings.value = !showSettings.value;
    };
    
    const closeSettings = () => {
      showSettings.value = false;
      // Reset local settings when closing without saving
      localTitle.value = props.widget.settings?.title || props.widget.title;
      localSettings.value = { ...(props.widget.settings || {}) };
    };
    
    const saveSettings = () => {
      try {
        // Clear any pending updates
        if (updateTimeout) {
          clearTimeout(updateTimeout);
          updateTimeout = null;
        }
        
        const settings = {
          ...localSettings.value,
          title: localTitle.value
        };
        
        // Only emit if settings actually changed
        if (JSON.stringify(settings) !== JSON.stringify(props.widget.settings || {})) {
          emit('update:settings', settings);
        }
        
        showSettings.value = false;
      } catch (error) {
        console.error('Error saving widget settings:', error);
      }
    };
    
    const refreshWidget = () => {
      emit('refresh');
    };
    
    // Watch for changes in widget settings with deep comparison
    watch(
      () => props.widget.settings,
      (newSettings) => {
        if (!newSettings) return;
        
        const newSettingsStr = JSON.stringify(newSettings);
        const currentSettingsStr = JSON.stringify(localSettings.value);
        
        // Only update if settings actually changed
        if (newSettingsStr !== currentSettingsStr) {
          localSettings.value = JSON.parse(newSettingsStr);
          
          // Update title if it exists in settings and is different
          if ('title' in newSettings && newSettings.title !== localTitle.value) {
            localTitle.value = newSettings.title;
          }
        }
      },
      { deep: true, immediate: true }
    );
    
    // Debounce settings updates to prevent rapid fire
    let updateTimeout = null;
    
    return {
      showSettings,
      localTitle,
      title,
      toggleEditMode,
      toggleSettings,
      closeSettings,
      saveSettings,
      refreshWidget
    };
  }
};
</script>

<style scoped>
.widget {
  @apply h-full flex flex-col bg-white dark:bg-gray-800 rounded-lg 
         border border-gray-200 dark:border-gray-700 overflow-hidden
         transition-all duration-200;
}

.widget-header {
  @apply px-4 py-3 border-b border-gray-200 dark:border-gray-700 
         bg-gray-50 dark:bg-gray-700 flex items-center justify-between;
}

.widget-title {
  @apply flex items-center text-sm font-medium text-gray-700 dark:text-gray-200;
}

.widget-actions {
  @apply flex items-center space-x-1;
}

.widget-action-btn {
  @apply w-6 h-6 flex items-center justify-center rounded-md 
         text-gray-500 hover:text-gray-700 dark:text-gray-400 
         dark:hover:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 
         transition-colors duration-200 focus:outline-none focus:ring-2 
         focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-1;
}

.widget-content {
  @apply flex-1 p-4 overflow-auto;
  min-height: 100px;
}

/* Loading State */
.widget-loading-state {
  @apply h-full flex flex-col items-center justify-center text-gray-500;
}

/* Error State */
.widget-error-state {
  @apply h-full flex flex-col items-center justify-center text-center p-4;
}

/* Settings Panel */
.widget-settings {
  @apply absolute inset-0 z-10 bg-white dark:bg-gray-800 p-4 
         border border-blue-500 dark:border-blue-400 rounded-lg shadow-lg flex flex-col;
}

.widget-settings-header {
  @apply flex items-center justify-between mb-4 pb-2 border-b 
         border-gray-200 dark:border-gray-700;
}

.widget-settings-body {
  @apply flex-1 overflow-y-auto py-2;
}

.setting-group {
  @apply mb-4;
}

.setting-label {
  @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
}

.setting-input {
  @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 
         rounded-md shadow-sm focus:outline-none focus:ring-2 
         focus:ring-blue-500 focus:border-transparent 
         bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
         transition duration-200 ease-in-out;
}

.widget-settings-footer {
  @apply flex justify-end pt-4 mt-auto border-t border-gray-200 dark:border-gray-700;
}

/* Widget Types */
.widget-stat {
  @apply bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 
         dark:to-blue-900/10 border-blue-200 dark:border-blue-800;
}

.widget-chart {
  @apply bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 
         dark:to-purple-900/10 border-purple-200 dark:border-purple-800;
}

.widget-list {
  @apply bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 
         dark:to-green-900/10 border-green-200 dark:border-green-800;
}

/* Responsive Adjustments */
@media (max-width: 640px) {
  .widget-header {
    @apply px-3 py-2;
  }
  
  .widget-content {
    @apply p-3;
  }
  
  .widget-title h3 {
    @apply text-sm;
  }
}
</style>
