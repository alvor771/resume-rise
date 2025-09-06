<template>
  <div class="dashboard-grid-container">
    <grid-layout
      :layout="widgets"
      @update:layout="$emit('update:widgets', $event)"
      :col-num="12"
      :row-height="30"
      :is-draggable="editable"
      :is-resizable="editable"
      :vertical-compact="true"
      :margin="[16, 16]"
      :use-css-transforms="true"
      @layout-updated="handleLayoutChange"
    >
      <grid-item
        v-for="widget in widgets"
        :key="widget.id"
        :x="widget.x"
        :y="widget.y"
        :w="widget.w"
        :h="widget.h"
        :i="widget.i"
        :min-w="widget.minW || 2"
        :min-h="widget.minH || 2"
        :max-w="widget.maxW || 12"
        :max-h="widget.maxH || 12"
        :is-resizable="editable"
        :is-draggable="editable"
        class="widget-item"
        :class="{ 'widget-editing': editable }"
      >
        <component
          :is="getWidgetComponent(widget.type)"
          :widget="widget"
          :editable="editable"
          @update:settings="updateWidgetSettings(widget.id, $event)"
          @remove="removeWidget(widget.id)"
        />
        
        <button 
          v-if="editable"
          @click="removeWidget(widget.id)"
          class="widget-remove-btn"
          :title="$t('dashboard.actions.remove_widget')"
        >
          <i class="fas fa-times"></i>
        </button>
      </grid-item>
    </grid-layout>
    
    <div v-if="widgets.length === 0" class="empty-dashboard">
      <div class="empty-state">
        <i class="fas fa-inbox text-4xl mb-4 text-gray-400"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $t('dashboard.empty.title') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('dashboard.empty.description') }}
        </p>
        <button 
          v-if="editable"
          @click="$emit('add-first-widget')" 
          class="mt-4 btn btn-primary"
        >
          <i class="fas fa-plus mr-2"></i>
          {{ $t('dashboard.actions.add_first_widget') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { GridLayout, GridItem } from 'vue3-grid-layout';

export default {
  name: 'DashboardGrid',
  components: {
    GridLayout,
    GridItem
  },
  props: {
    widgets: {
      type: Array,
      required: true
    },
    editable: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update-widgets', 'add-first-widget'],
  setup(props, { emit }) {
    // Dynamically import widget components
    const getWidgetComponent = (widgetType) => {
      return defineAsyncComponent(() => 
        import(`@/components/dashboard/widgets/${widgetType}.vue`).catch(() => 
          import('@/components/dashboard/widgets/WidgetError.vue')
        )
      );
    };

    const handleLayoutChange = (newLayout) => {
      emit('update-widgets', newLayout);
    };

    const updateWidgetSettings = (widgetId, settings) => {
      const widgetIndex = props.widgets.findIndex(w => w.id === widgetId);
      if (widgetIndex === -1) return;
      
      // Create a deep copy of the widget to avoid reference issues
      const widget = JSON.parse(JSON.stringify(props.widgets[widgetIndex]));
      
      // Only update if settings actually changed
      const currentSettings = JSON.stringify(widget.settings || {});
      const newSettings = JSON.stringify({ ...(widget.settings || {}), ...settings });
      
      if (currentSettings !== newSettings) {
        widget.settings = JSON.parse(newSettings);
        const updatedWidgets = [...props.widgets];
        updatedWidgets[widgetIndex] = widget;
        emit('update-widgets', updatedWidgets);
      }
    };

    const removeWidget = (widgetId) => {
      const updatedWidgets = props.widgets.filter(widget => widget.id !== widgetId);
      emit('update-widgets', updatedWidgets);
    };

    return {
      getWidgetComponent,
      handleLayoutChange,
      updateWidgetSettings,
      removeWidget
    };
  }
};
</script>

<style scoped>
.dashboard-grid-container {
  @apply relative min-h-[500px] w-full;
}

.widget-item {
  @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-200;
}

.widget-item.widget-editing {
  @apply border-2 border-dashed border-blue-500 dark:border-blue-400;
}

.widget-remove-btn {
  @apply absolute top-1 right-1 w-6 h-6 flex items-center justify-center 
         rounded-full bg-red-500 text-white text-xs opacity-0 
         hover:bg-red-600 transition-all duration-200
         focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2;
  transform: translate(50%, -50%);
  z-index: 10;
}

.widget-item:hover .widget-remove-btn {
  @apply opacity-100;
}

.empty-dashboard {
  @apply flex items-center justify-center p-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg mt-4;
  min-height: 300px;
}

.empty-state {
  @apply text-center max-w-md mx-auto;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .dashboard-grid-container {
    @apply px-2;
  }
  
  .widget-item {
    @apply w-full !important;
    margin-right: 0 !important;
    margin-left: 0 !important;
  }
}
</style>
