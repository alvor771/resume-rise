<template>
  <div :class="wrapperClass">
    <div v-if="variant === 'text'" class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
    <div v-else-if="variant === 'circle'" class="rounded-full bg-gray-200 dark:bg-gray-700 animate-pulse" :style="{ width: size+'px', height: size+'px' }"></div>
    <div v-else-if="variant === 'rect'" class="bg-gray-200 dark:bg-gray-700 rounded animate-pulse" :style="{ width: width+'px', height: height+'px' }"></div>
    <div v-else-if="variant === 'lines'" class="space-y-2 w-full">
      <div v-for="i in lines" :key="i" class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse" :class="i === lines ? 'w-2/3' : 'w-full'"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UiSkeleton',
  props: {
    variant: { type: String, default: 'text' },
    size: { type: Number, default: 40 },
    width: { type: Number, default: 200 },
    height: { type: Number, default: 120 },
    lines: { type: Number, default: 3 },
    block: { type: Boolean, default: false }
  },
  computed: {
    wrapperClass() {
      return this.block ? 'block' : 'inline-block';
    }
  }
}
</script>

<style scoped>
</style>
