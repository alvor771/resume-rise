<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
            {{ $t('menu.title') }}
          </h2>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $t('menu.description') }}
          </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 [&>*:not(:first-child)]:ml-3">
          <button
            type="button"
            @click="openCreateModal(null)"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
            {{ $t('menu.buttons.addNew') }}
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="rounded-md bg-red-50 dark:bg-red-900/30 p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
              {{ $t('errors.loadingFailed') }}
            </h3>
            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
              <p>{{ error }}</p>
            </div>
            <div class="mt-4">
              <button
                type="button"
                @click="fetchMenuItems"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900/50 dark:text-red-200 dark:hover:bg-red-800"
              >
                <ArrowPathIcon class="-ml-0.5 mr-1.5 h-4 w-4" />
                {{ $t('common.retry') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!menuItems.length" class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
        <MenuAlt2Icon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
          {{ $t('menu.empty.title') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          {{ $t('menu.empty.description') }}
        </p>
        <div class="mt-6">
          <button
            type="button"
            @click="openCreateModal(null)"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
            {{ $t('menu.buttons.addFirstItem') }}
          </button>
        </div>
      </div>

      <!-- Menu Items List -->
      <div v-else class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
          <li v-for="(item) in menuItems" :key="item.id">
            <div class="px-4 py-4 sm:px-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center min-w-0">
                  <button
                    @click="toggleExpand(item)"
                    class="mr-3 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400"
                    :title="item._expanded ? $t('menu.buttons.collapse') : $t('menu.buttons.expand')"
                  >
                    <ChevronRightIcon
                      class="h-5 w-5 trans   transition-transform duration-200"
                      :class="{ 'rotate-90': item._expanded }"
                    />
                  </button>
                  <div class="flex items-center">
                    <component
                      :is="getIconComponent(item.icon)"
                      class="h-5 w-5 text-gray-400 mr-3"
                      aria-hidden="true"
                    />
                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                      {{ item.title }}
                    </p>
                    <span
                      v-if="!item.is_visible"
                      class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
                    >
                      {{ $t('common.hidden') }}
                    </span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0 flex space-x-3">
                  <button
                    @click="editItem(item)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                    :title="$t('common.edit')"
                  >
                    <PencilIcon class="h-5 w-5" />
                    <span class="sr-only">{{ $t('common.edit') }}</span>
                  </button>
                  <button
                    @click="confirmDelete(item)"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                    :title="$t('common.delete')"
                  >
                    <TrashIcon class="h-5 w-5" />
                    <span class="sr-only">{{ $t('common.delete') }}</span>
                  </button>
                </div>
              </div>
              
              <!-- Child Items -->
              <div v-if="item._expanded && item.children && item.children.length" class="mt-4 pl-8">
                <ul class="space-y-3">
                  <li v-for="child in item.children" :key="child.id" class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center">
                        <component
                          :is="getIconComponent(child.icon)"
                          class="h-4 w-4 text-gray-400 mr-2"
                          aria-hidden="true"
                        />
                        <span class="text-sm text-gray-900 dark:text-gray-200">
                          {{ child.title }}
                        </span>
                        <span
                          v-if="!child.is_visible"
                          class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200"
                        >
                          {{ $t('common.hidden') }}
                        </span>
                      </div>
                      <div class="flex space-x-2">
                        <button
                          @click="editItem(child)"
                          class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                          :title="$t('common.edit')"
                        >
                          <PencilIcon class="h-4 w-4" />
                          <span class="sr-only">{{ $t('common.edit') }}</span>
                        </button>
                        <button
                          @click="confirmDelete(child)"
                          class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                          :title="$t('common.delete')"
                        >
                          <TrashIcon class="h-4 w-4" />
                          <span class="sr-only">{{ $t('common.delete') }}</span>
                        </button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Form Modal -->
    <MenuFormModal
      v-if="showFormModal"
      :item="editingItem"
      :parent-id="editingParentId"
      @close="closeFormModal"
      @submit="handleSubmit"
    />

    <!-- Delete Confirmation -->
    <ConfirmationModal
      v-if="showDeleteModal"
      :is-open="showDeleteModal"
      :title="$t('menu.delete.title')"
      :message="$t('menu.delete.message', { title: itemToDelete?.title })"
      :confirm-text="$t('common.delete')"
      :cancel-text="$t('common.cancel')"
      :is-danger="true"
      @confirm="deleteItem"
      @cancel="cancelDelete"
      @update:is-open="val => showDeleteModal = val"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useMenuStore } from '@/stores/menu';
import { 
  PlusIcon,
  PencilIcon, 
  TrashIcon,
  MenuAlt2Icon,
  XCircleIcon,
  ArrowPathIcon,
  ChevronRightIcon
} from '@heroicons/vue/24/outline';
import MenuFormModal from '@/components/admin/menu/MenuFormModal.vue';
import ConfirmationModal from '@/components/ui/ConfirmationModal.vue';

export default {
  name: 'MenuEditor',
  components: {
    PlusIcon,
    PencilIcon,
    TrashIcon,
    MenuAlt2Icon,
    XCircleIcon,
    ArrowPathIcon,
    ChevronRightIcon,
    MenuFormModal,
    ConfirmationModal
  },
  setup() {
    const { t } = useI18n();
    const menuStore = useMenuStore();
    
    const menuItems = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const showFormModal = ref(false);
    const editingItem = ref(null);
    const editingParentId = ref(null);
    const showDeleteModal = ref(false);
    const itemToDelete = ref(null);
    const isSubmitting = ref(false);

    // Fetch menu items
    const fetchMenuItems = async () => {
      try {
        loading.value = true;
        error.value = null;
        await menuStore.fetchMenuItems();
        menuItems.value = prepareMenuItems(menuStore.menuItems);
      } catch (err) {
        console.error('Error fetching menu items:', err);
        error.value = t('errors.loadingFailed');
      } finally {
        loading.value = false;
      }
    };

    // Add _expanded property to each menu item
    const prepareMenuItems = (items) => {
      if (!items || !Array.isArray(items)) {
        console.warn('Expected an array of menu items but received:', items);
        return [];
      }
      return items.map(item => ({
        ...item,
        _expanded: false,
        children: item.children ? prepareMenuItems(item.children) : []
      }));
    };

    // Toggle item expanded state
    const toggleExpand = (item) => {
      // Toggle the expanded state for the clicked item
      item._expanded = !item._expanded;
      
      // If the item has children, we need to update the array to trigger reactivity
      if (item.children && item.children.length) {
        menuItems.value = [...menuItems.value];
      }
    };

    // Open create modal
    const openCreateModal = (parentId = null) => {
      editingItem.value = null;
      editingParentId.value = parentId;
      showFormModal.value = true;
    };

    // Open edit modal
    const editItem = (item) => {
      editingItem.value = { ...item };
      editingParentId.value = item.parent_id;
      showFormModal.value = true;
    };

    // Close form modal
    const closeFormModal = () => {
      showFormModal.value = false;
      editingItem.value = null;
      editingParentId.value = null;
    };

    // Handle form submission
    const handleSubmit = async (formData) => {
      try {
        isSubmitting.value = true;
        
        if (formData.id) {
          await menuStore.updateMenuItem(formData.id, formData);
        } else {
          await menuStore.createMenuItem({
            ...formData,
            parent_id: editingParentId.value
          });
        }
        
        await fetchMenuItems();
        closeFormModal();
      } catch (err) {
        console.error('Error saving menu item:', err);
        error.value = t('errors.saveFailed');
      } finally {
        isSubmitting.value = false;
      }
    };

    // Confirm delete
    const confirmDelete = (item) => {
      itemToDelete.value = item;
      showDeleteModal.value = true;
    };

    // Cancel delete
    const cancelDelete = () => {
      itemToDelete.value = null;
      showDeleteModal.value = false;
    };

    // Delete item
    const deleteItem = async () => {
      if (!itemToDelete.value) return;
      
      try {
        isSubmitting.value = true;
        await menuStore.deleteMenuItem(itemToDelete.value.id);
        await fetchMenuItems();
        cancelDelete();
      } catch (err) {
        console.error('Error deleting menu item:', err);
        error.value = t('errors.deleteFailed');
      } finally {
        isSubmitting.value = false;
      }
    };

    // Get icon component
    const getIconComponent = (iconName) => {
      if (!iconName) return 'span';
      try {
        return require(`@heroicons/vue/24/outline/${iconName}.js`).default;
      } catch (e) {
        return 'span';
      }
    };

    // Initialize
    onMounted(() => {
      fetchMenuItems();
    });

    return {
      // State
      menuItems,
      loading,
      error,
      showFormModal,
      editingItem,
      editingParentId,
      showDeleteModal,
      itemToDelete,
      isSubmitting,
      
      // Methods
      fetchMenuItems,
      toggleExpand,
      openCreateModal,
      editItem,
      closeFormModal,
      handleSubmit,
      confirmDelete,
      cancelDelete,
      deleteItem,
      getIconComponent,
      t
    };
  }
};
</script>
