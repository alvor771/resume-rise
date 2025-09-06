<template>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-2xl font-semibold mb-6">Icons Showcase</h1>

    <div class="mb-4 flex items-center gap-4">
      <label class="text-sm text-gray-600">Size</label>
      <input
        type="range"
        min="16"
        max="64"
        v-model.number="size"
        class="w-48"
      />
      <span class="text-sm text-gray-700">{{ size }}px</span>

      <label class="ml-6 text-sm text-gray-600">Stroke</label>
      <input
        type="range"
        min="1"
        max="3"
        step="0.2"
        v-model.number="stroke"
        class="w-40"
      />
      <span class="text-sm text-gray-700">{{ stroke }}</span>

      <label class="ml-6 text-sm text-gray-600">Color</label>
      <input type="color" class="w-12 h-12 flex" v-model="color" />
      <span class="text-sm text-gray-700">{{ color }}</span>

      <label class="ml-6 text-sm text-gray-600">Secondary</label>
      <input type="color" class="w-12 h-12 flex" v-model="secondaryColor" />
      <span class="text-sm text-gray-700">{{ secondaryColor }}</span>

      <label class="ml-6 text-sm text-gray-600">Variant</label>
      <select v-model="variant" class="border rounded px-2 py-1 text-sm bg-white dark:bg-gray-900 dark:border-gray-700">
        <option value="stroke">stroke</option>
        <option value="filled">filled</option>
        <option value="duotone">duotone</option>
      </select>
      <span class="text-sm text-gray-700">{{ variant }}</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
      <div
        v-for="item in iconItems"
        :key="item.name"
        class="group rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex flex-col items-center gap-2 hover:shadow-sm transition-shadow bg-white dark:bg-gray-900"
      >
        <component
          :is="item.component"
          :size="size"
          :stroke="stroke"
          :color="color"
          :variant="variant"
          :secondary-color="secondaryColor"
          :aria-label="item.name + ' icon'"
        />
        <div class="text-xs text-gray-600 dark:text-gray-300 mt-1 text-center">
          {{ item.name }}
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import * as Icons from '@/components/ui/icons'
import { computed, ref } from 'vue'

const size = ref(28)
const stroke = ref(1.8)
const color = ref('#111827')
const secondaryColor = ref('#111827')
const variant = ref('stroke')

// Build list from named exports (exclude the helper 'icons' map)
const iconItems = computed(() =>
  Object.entries(Icons)
    .filter(([key]) => key !== 'icons')
    .map(([name, component]) => ({ name, component }))
    .sort((a, b) => a.name.localeCompare(b.name))
)
</script>

<style scoped>
</style>
