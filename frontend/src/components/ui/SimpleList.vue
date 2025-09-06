<template>
  <ul :class="['divide-y divide-gray-200 dark:divide-gray-700', condensed ? 'space-y-0' : 'space-y-2']">
    <li v-for="(item, idx) in items" :key="itemKey(item, idx)" class="py-3 flex items-start gap-3">
      <div v-if="$slots.leading" class="shrink-0">
        <slot name="leading" :item="item" :index="idx" />
      </div>
      <div class="min-w-0 flex-1">
        <div class="flex items-center justify-between gap-2">
          <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
            <slot name="title" :item="item" :index="idx">{{ item.title }}</slot>
          </div>
          <div v-if="$slots.trailing" class="shrink-0">
            <slot name="trailing" :item="item" :index="idx" />
          </div>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-300">
          <slot name="subtitle" :item="item" :index="idx">{{ item.subtitle }}</slot>
        </div>
        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400" v-if="$slots.description">
          <slot name="description" :item="item" :index="idx" />
        </div>
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  name: 'SimpleList',
  props: {
    items: { type: Array, default: () => [] },
    itemKeyField: { type: String, default: '' },
    condensed: { type: Boolean, default: false },
  },
  setup(props) {
    const itemKey = (item, idx) => props.itemKeyField ? item[props.itemKeyField] ?? idx : idx;
    return { itemKey };
  }
}
</script>

<style scoped>
</style>
