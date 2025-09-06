<template>
  <div class="w-full">
    <div v-if="$slots.toolbar" class="mb-3">
      <slot name="toolbar" />
    </div>

    <div class="relative overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900/20">
          <tr>
            <th v-for="(col, idx) in columns" :key="idx" scope="col"
                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 select-none"
            >
              <button v-if="col.sortable" type="button" class="inline-flex items-center gap-1" @click="toggleSort(col.key)">
                <span>{{ col.label }}</span>
                <svg v-if="sortBy === col.key && sortDir === 'asc'" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3 12l7-8 7 8H3z"/></svg>
                <svg v-else-if="sortBy === col.key && sortDir === 'desc'" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17 8l-7 8-7-8h14z"/></svg>
                <svg v-else class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path d="M3 7h14M3 13h14"/></svg>
              </button>
              <span v-else>{{ col.label }}</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-if="loading">
            <td :colspan="columns.length" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
              <slot name="loading">Loading...</slot>
            </td>
          </tr>
          <tr v-else-if="!items.length">
            <td :colspan="columns.length" class="px-4 py-10">
              <slot name="empty">
                <div class="text-center text-sm text-gray-500 dark:text-gray-400">No data</div>
              </slot>
            </td>
          </tr>
          <tr v-else v-for="(row, rIdx) in pagedItems" :key="rowKey(row, rIdx)" class="hover:bg-gray-50/60 dark:hover:bg-gray-900/20">
            <td v-for="(col, cIdx) in columns" :key="cIdx" class="px-4 py-3 text-sm text-gray-800 dark:text-gray-100 align-top">
              <slot :name="`cell:${col.key}`" :row="row">{{ row[col.key] }}</slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="paginated" class="mt-3">
      <Pagination :page="page" :page-size="pageSize" :total="items.length" @change="onPageChange" />
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
import Pagination from '@/components/ui/Pagination.vue';

export default {
  name: 'DataTable',
  components: { Pagination },
  props: {
    items: { type: Array, default: () => [] },
    columns: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    sortBy: { type: String, default: '' },
    sortDir: { type: String, default: 'asc' },
    page: { type: Number, default: 1 },
    pageSize: { type: Number, default: 10 },
    paginated: { type: Boolean, default: true },
    rowKeyField: { type: String, default: '' },
  },
  emits: ['update:sortBy', 'update:sortDir', 'update:page', 'update:pageSize'],
  setup(props, { emit }) {
    const sortedItems = computed(() => {
      if (!props.sortBy) return props.items;
      const sorted = [...props.items].sort((a,b) => {
        const av = a[props.sortBy];
        const bv = b[props.sortBy];
        if (av == null) return -1;
        if (bv == null) return 1;
        if (av === bv) return 0;
        return av > bv ? 1 : -1;
      });
      return props.sortDir === 'desc' ? sorted.reverse() : sorted;
    });

    const pagedItems = computed(() => {
      if (!props.paginated) return sortedItems.value;
      const start = (props.page - 1) * props.pageSize;
      return sortedItems.value.slice(start, start + props.pageSize);
    });

    const toggleSort = (key) => {
      if (props.sortBy !== key) {
        emit('update:sortBy', key);
        emit('update:sortDir', 'asc');
      } else {
        emit('update:sortDir', props.sortDir === 'asc' ? 'desc' : 'asc');
      }
    };

    const rowKey = (row, idx) => props.rowKeyField ? row[props.rowKeyField] ?? idx : idx;

    const onPageChange = (payload) => {
      emit('update:page', payload.page);
      if (payload.pageSize) emit('update:pageSize', payload.pageSize);
    };

    return { sortedItems, pagedItems, toggleSort, rowKey, onPageChange };
  }
}
</script>

<style scoped>
</style>
