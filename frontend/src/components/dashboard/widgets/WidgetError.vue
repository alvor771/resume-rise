<template>
  <div class="widget-error">
    <div class="error-content">
      <div class="error-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="error-details">
        <h4 class="error-title">{{ title || $t('widget.error.title') }}</h4>
        <p class="error-message">{{ message || $t('widget.error.message') }}</p>
        <button v-if="showRetry" @click="handleRetry" class="retry-button">
          <i class="fas fa-sync-alt mr-2"></i>
          {{ $t('widget.error.retry') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WidgetError',
  props: {
    title: {
      type: String,
      default: ''
    },
    message: {
      type: String,
      default: ''
    },
    showRetry: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    handleRetry() {
      this.$emit('retry');
    }
  }
};
</script>

<style scoped>
.widget-error {
  @apply h-full w-full flex items-center justify-center p-4 text-center;
  background-color: #fef2f2;
  color: #b91c1c;
}

.error-content {
  @apply flex flex-col items-center;
}

.error-icon {
  @apply text-4xl mb-3;
}

.error-title {
  @apply font-semibold text-lg mb-1;
}

.error-message {
  @apply text-sm mb-3;
  color: #7f1d1d;
}

.retry-button {
  @apply px-3 py-1.5 text-sm rounded-md flex items-center;
  background-color: #fecaca;
  color: #7f1d1d;
  transition: all 0.2s ease;
}

.retry-button:hover {
  background-color: #fca5a5;
}

.retry-button:focus {
  @apply outline-none ring-2 ring-red-300 ring-offset-1;
}
</style>
