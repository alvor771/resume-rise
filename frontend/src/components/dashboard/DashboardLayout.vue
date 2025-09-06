<template>
  <div class="dashboard-layout" :class="[theme, `dashboard-${role}`]">
    <div class="dashboard-header">
      <h1 class="text-2xl font-bold">{{ $t(`dashboard.${role}.title`) }}</h1>
      <div class="dashboard-actions">
        <button 
          @click="toggleEditMode" 
          class="btn btn-sm"
          :class="editMode ? 'btn-primary' : 'btn-outline'"
        >
          <span v-if="!editMode">
            <i class="fas fa-edit mr-1"></i> {{ $t('dashboard.actions.customize') }}
          </span>
          <span v-else>
            <i class="fas fa-check mr-1"></i> {{ $t('dashboard.actions.done') }}
          </span>
        </button>
        <button 
          v-if="editMode"
          @click="resetLayout"
          class="btn btn-sm btn-outline ml-2"
        >
          <i class="fas fa-undo mr-1"></i> {{ $t('dashboard.actions.reset') }}
        </button>
      </div>
    </div>
    
    <DashboardGrid 
      :widgets="widgets" 
      :editable="editMode"
      @update-widgets="handleWidgetsUpdate"
    />
    
    <WidgetMenu 
      v-if="editMode"
      :available-widgets="availableWidgets"
      @add-widget="addWidget"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '@/stores/auth';
import { useDashboardStore } from '@/stores/dashboard';
import DashboardGrid from './DashboardGrid.vue';
import WidgetMenu from './widgets/WidgetMenu.vue';

export default {
  name: 'DashboardLayout',
  components: {
    DashboardGrid,
    WidgetMenu
  },
  props: {
    role: {
      type: String,
      required: true,
      validator: value => ['admin', 'hr', 'candidate'].includes(value)
    }
  },
  setup(props) {
    const { t } = useI18n();
    const authStore = useAuthStore();
    const dashboardStore = useDashboardStore();
    
    const editMode = ref(false);
    const widgets = ref([]);
    
    const theme = computed(() => dashboardStore.theme);
    
    const availableWidgets = computed(() => {
      // Filter widgets based on user role and permissions
      return dashboardStore.availableWidgets.filter(widget => 
        widget.roles.includes(props.role) && 
        (!widget.permissions || widget.permissions.every(p => authStore.user.permissions.includes(p)))
      );
    });
    
    // Load saved layout or use default
    const loadLayout = async () => {
      try {
        await dashboardStore.loadDashboardLayout(props.role);
        widgets.value = [...dashboardStore.layout];
      } catch (error) {
        console.error('Failed to load dashboard layout:', error);
        // Fallback to default layout for the role
        widgets.value = dashboardStore.getDefaultLayout(props.role);
      }
    };
    
    const saveLayout = async () => {
      try {
        await dashboardStore.saveDashboardLayout(props.role, widgets.value);
      } catch (error) {
        console.error('Failed to save dashboard layout:', error);
      }
    };
    
    const toggleEditMode = () => {
      editMode.value = !editMode.value;
      if (!editMode.value) {
        saveLayout();
      }
    };
    
    const resetLayout = () => {
      if (confirm(t('dashboard.confirm_reset'))) {
        widgets.value = dashboardStore.getDefaultLayout(props.role);
        saveLayout();
      }
    };
    
    const addWidget = (widgetType) => {
      const widgetConfig = dashboardStore.getWidgetConfig(widgetType);
      if (widgetConfig) {
        const newWidget = {
          id: `widget-${Date.now()}`,
          type: widgetType,
          title: t(`widgets.${widgetType}.title`),
          position: { x: 0, y: 0 },
          size: widgetConfig.defaultSize || { w: 2, h: 2 },
          settings: { ...(widgetConfig.defaultSettings || {}) }
        };
        widgets.value.push(newWidget);
      }
    };
    
    const handleWidgetsUpdate = (updatedWidgets) => {
      // Only update if widgets actually changed
      if (JSON.stringify(widgets.value) !== JSON.stringify(updatedWidgets)) {
        widgets.value = JSON.parse(JSON.stringify(updatedWidgets));
        // Debounce the save operation to avoid too many saves in quick succession
        clearTimeout(window.dashboardSaveTimeout);
        window.dashboardSaveTimeout = setTimeout(() => {
          saveLayout();
        }, 500);
      }
    };
    
    onMounted(() => {
      loadLayout();
      // Set document title
      document.title = t(`dashboard.${props.role}.page_title`);
    });
    
    return {
      editMode,
      widgets,
      theme,
      availableWidgets,
      toggleEditMode,
      resetLayout,
      addWidget,
      handleWidgetsUpdate
    };
  }
};
</script>

<style scoped>
.dashboard-layout {
  @apply p-4 min-h-screen transition-colors duration-300;
}

.dashboard-header {
  @apply flex justify-between items-center mb-6;
}

.dashboard-actions {
  @apply flex items-center;
}

/* Theme-specific styles */
.dark .dashboard-layout {
  @apply bg-gray-900 text-gray-100;
}

.light .dashboard-layout {
  @apply bg-gray-50 text-gray-900;
}

/* Role-specific styles */
.dashboard-admin {
  --primary-color: #4f46e5;
  --secondary-color: #7c3aed;
}

.dashboard-hr {
  --primary-color: #3b82f6;
  --secondary-color: #60a5fa;
}

.dashboard-candidate {
  --primary-color: #10b981;
  --secondary-color: #34d399;
}
</style>
