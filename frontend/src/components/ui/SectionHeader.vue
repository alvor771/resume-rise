<template>
  <div class="flex justify-between items-center mb-4">
    <div>
      <h2 :class="['text-xl font-semibold', titleClass]">
        {{ title }}
      </h2>
      <p v-if="subtitle" :class="['text-sm', subtitleClass]">
        {{ subtitle }}
      </p>
    </div>
    <div v-if="$slots.actions" class="flex items-center space-x-2">
      <slot name="actions"></slot>
    </div>
  </div>
</template>

<script>
const SectionHeader = {
  name: 'SectionHeader',
  props: {
    title: {
      type: String,
      required: true
    },
    subtitle: {
      type: String,
      default: ''
    },
    titleClass: {
      type: String,
      default: 'text-gray-800 dark:text-white'
    },
    subtitleClass: {
      type: String,
      default: 'text-gray-500 dark:text-gray-400'
    }
  }
};

export default SectionHeader;
</script>
