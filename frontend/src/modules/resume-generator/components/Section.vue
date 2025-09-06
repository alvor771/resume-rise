<template>
  <div class="section">
    <h2 class="section-title">
      <slot name="title">{{ title }}</slot>
    </h2>
    <div class="section-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ResumeSection',
  props: {
    title: {
      type: String,
      default: ''
    }
  }
}
</script>

<!-- stylelint-disable -->
<style scoped>
.section {
  @apply mb-8;
}

.section-title {
  @apply text-xl font-semibold border-b border-gray-200 pb-1 mb-4 text-gray-800;
}

.section-content {
  @apply space-y-4;
}
</style>
