<template>
  <div class="flex items-center justify-between gap-3">
    <div class="text-sm text-gray-600 dark:text-gray-300">
      {{ start + 1 }}-{{ end }} / {{ total }}
    </div>
    <div class="flex items-center gap-2">
      <button type="button" class="px-2 py-1 rounded border border-gray-300 dark:border-gray-600 text-sm disabled:opacity-50"
              :disabled="page <= 1" @click="go(page - 1)">
        Prev
      </button>
      <button type="button" class="px-2 py-1 rounded border border-gray-300 dark:border-gray-600 text-sm disabled:opacity-50"
              :disabled="end >= total" @click="go(page + 1)">
        Next
      </button>
      <select v-model.number="localPageSize" @change="emitChange(page, localPageSize)"
              class="ml-2 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm">
        <option :value="s" v-for="s in pageSizeOptions" :key="s">{{ s }}/page</option>
      </select>
    </div>
  </div>
</template>

<script>
import { computed, ref, watch } from 'vue';
export default {
  name: 'UiPagination',
  props: {
    page: { type: Number, default: 1 },
    pageSize: { type: Number, default: 10 },
    total: { type: Number, default: 0 },
    pageSizeOptions: { type: Array, default: () => [10, 20, 50, 100] }
  },
  emits: ['change'],
  setup(props, { emit }) {
    const start = computed(() => Math.min((props.page - 1) * props.pageSize, Math.max(props.total - 1, 0)));
    const end = computed(() => Math.min(start.value + props.pageSize, props.total));

    const localPageSize = ref(props.pageSize);
    watch(() => props.pageSize, (v) => { localPageSize.value = v; });

    const go = (p) => emitChange(p, localPageSize.value);
    const emitChange = (p, ps) => emit('change', { page: Math.max(1, p), pageSize: ps });

    return { start, end, go, localPageSize, emitChange };
  }
}
</script>

<style scoped>
</style>
