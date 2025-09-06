<template>
  <div class="widget-menu">
    <button 
      @click="isOpen = !isOpen" 
      class="widget-menu-toggle"
      :class="{ 'widget-menu-open': isOpen }"
      :title="$t('dashboard.actions.add_widget')"
    >
      <i class="fas fa-plus"></i>
      <span class="sr-only">{{ $t('dashboard.actions.add_widget') }}</span>
    </button>
    
    <transition name="widget-menu">
      <div v-if="isOpen" class="widget-menu-dropdown">
        <div class="widget-menu-header">
          <h3 class="text-sm font-medium text-gray-900 dark:text-white">
            {{ $t('dashboard.add_widget') }}
          </h3>
          <button 
            @click="isOpen = false" 
            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
            :title="$t('common.close')"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="widget-search">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="$t('dashboard.search_widgets')"
            class="widget-search-input"
          >
          <i class="fas fa-search widget-search-icon"></i>
        </div>
        
        <div class="widget-categories">
          <button
            v-for="category in categories"
            :key="category"
            @click="activeCategory = category"
            class="widget-category"
            :class="{ 'widget-category-active': activeCategory === category }"
          >
            {{ $t(`widgets.categories.${category}`) }}
          </button>
        </div>
        
        <div class="widget-list">
          <div 
            v-for="widget in filteredWidgets"
            :key="widget.type"
            class="widget-item"
            draggable="true"
            @dragstart="onDragStart($event, widget)"
            @click="addWidget(widget.type)"
          >
            <div class="widget-icon">
              <i :class="widget.icon || 'fas fa-cube'"></i>
            </div>
            <div class="widget-details">
              <h4 class="widget-title">
                {{ $t(`widgets.${widget.type}.title`) }}
              </h4>
              <p class="widget-description">
                {{ $t(`widgets.${widget.type}.description`) }}
              </p>
            </div>
          </div>
          
          <div v-if="filteredWidgets.length === 0" class="no-widgets">
            <i class="fas fa-search text-gray-400 text-2xl mb-2"></i>
            <p class="text-gray-500 dark:text-gray-400">
              {{ $t('dashboard.no_widgets_found') }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useI18n } from 'vue-i18n';

export default {
  name: 'WidgetMenu',
  props: {
    availableWidgets: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  emits: ['add-widget'],
  setup(props, { emit }) {
    const { t } = useI18n();
    const isOpen = ref(false);
    const searchQuery = ref('');
    const activeCategory = ref('all');
    
    // Get unique categories from available widgets
    const categories = computed(() => {
      const cats = new Set(['all']);
      props.availableWidgets.forEach(widget => {
        if (widget.category) {
          cats.add(widget.category);
        }
      });
      return Array.from(cats);
    });
    
    // Filter widgets based on search and category
    const filteredWidgets = computed(() => {
      return props.availableWidgets.filter(widget => {
        const matchesSearch = 
          t(`widgets.${widget.type}.title`).toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          t(`widgets.${widget.type}.description`).toLowerCase().includes(searchQuery.value.toLowerCase());
          
        const matchesCategory = 
          activeCategory.value === 'all' || 
          widget.category === activeCategory.value;
          
        return matchesSearch && matchesCategory;
      });
    });
    
    const addWidget = (widgetType) => {
      emit('add-widget', widgetType);
      isOpen.value = false;
      searchQuery.value = '';
    };
    
    const onDragStart = (event, widget) => {
      event.dataTransfer.setData('widget-type', widget.type);
      event.dataTransfer.effectAllowed = 'copy';
    };
    
    // Close menu when clicking outside
    const handleClickOutside = (event) => {
      const menu = event.target.closest('.widget-menu');
      if (!menu) {
        isOpen.value = false;
      }
    };
    
    // Add event listener for outside clicks
    onMounted(() => {
      document.addEventListener('click', handleClickOutside);
    });
    
    onBeforeUnmount(() => {
      document.removeEventListener('click', handleClickOutside);
    });
    
    return {
      isOpen,
      searchQuery,
      activeCategory,
      categories,
      filteredWidgets,
      addWidget,
      onDragStart
    };
  }
};
</script>

<style scoped>
.widget-menu {
  @apply fixed right-6 bottom-6 z-40;
}

.widget-menu-toggle {
  @apply w-14 h-14 rounded-full bg-blue-600 text-white 
         flex items-center justify-center shadow-lg
         hover:bg-blue-700 focus:outline-none focus:ring-2 
         focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200;
}

.widget-menu-toggle.widget-menu-open {
  @apply transform rotate-45 bg-red-500 hover:bg-red-600;
}

.widget-menu-dropdown {
  @apply absolute right-0 bottom-16 mb-2 w-80 bg-white dark:bg-gray-800 
         rounded-lg shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700;
  max-height: 80vh;
  display: flex;
  flex-direction: column;
}

.widget-menu-header {
  @apply px-4 py-3 border-b border-gray-200 dark:border-gray-700 
         flex items-center justify-between bg-gray-50 dark:bg-gray-800;
}

.widget-search {
  @apply relative px-4 py-2 border-b border-gray-200 dark:border-gray-700;
}

.widget-search-input {
  @apply w-full pl-8 pr-3 py-2 text-sm rounded-md border border-gray-300 
         dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 
         dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
         focus:border-blue-500 transition-colors;
}

.widget-search-icon {
  @apply absolute left-6 top-1/2 transform -translate-y-1/2 text-gray-400;
}

.widget-categories {
  @apply flex overflow-x-auto px-2 py-2 border-b border-gray-200 dark:border-gray-700 
         bg-gray-50 dark:bg-gray-800;
  scrollbar-width: thin;
  scrollbar-color: #d1d5db transparent;
  scrollbar-width: thin;
}

.widget-category {
  @apply px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap mx-1
         bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200
         hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors
         focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.widget-category-active {
  @apply bg-blue-600 text-white hover:bg-blue-700;
}

.widget-list {
  @apply flex-1 overflow-y-auto p-2;
}

.widget-item {
  @apply flex items-start p-3 rounded-lg cursor-pointer
         hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors
         border border-transparent hover:border-gray-200 dark:hover:border-gray-600
         mb-2 last:mb-0;
  user-select: none;
}

.widget-icon {
  @apply flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900 
         text-blue-600 dark:text-blue-300 flex items-center justify-center 
         mt-0.5 mr-3;
}

.widget-details {
  @apply flex-1 min-w-0;
}

.widget-title {
  @apply text-sm font-medium text-gray-900 dark:text-white truncate;
}

.widget-description {
  @apply text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2;
}

.no-widgets {
  @apply flex flex-col items-center justify-center py-8 text-center px-4;
}

/* Transitions */
.widget-menu-enter-active,
.widget-menu-leave-active {
  transition: all 0.2s ease;
}

.widget-menu-enter-from,
.widget-menu-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Scrollbar styles */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  @apply bg-transparent;
}

::-webkit-scrollbar-thumb {
  @apply bg-gray-300 dark:bg-gray-600 rounded-full;
}

::-webkit-scrollbar-thumb:hover {
  @apply bg-gray-400 dark:bg-gray-500;
}
</style>
