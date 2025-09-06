<template>
  <div class="base-toggle" :class="{ 'is-disabled': disabled, 'has-error': hasError }">
    <label class="toggle-container">
      <input
        type="checkbox"
        class="toggle-input"
        :checked="modelValue"
        :disabled="disabled || loading"
        @change="handleChange"
        @focus="isFocused = true"
        @blur="isFocused = false"
      />
      <span 
        class="toggle-track" 
        :class="{
          'is-checked': modelValue,
          'is-focused': isFocused,
          'has-error': hasError,
          'is-loading': loading,
          [`size-${size}`]: size,
          [`color-${color}`]: color
        }"
      >
        <span class="toggle-thumb">
          <span v-if="loading" class="toggle-loading"></span>
          <span v-else class="toggle-icon">
            <slot name="icon">
              <svg v-if="modelValue" width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <svg v-else width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </slot>
          </span>
        </span>
      </span>
      <span v-if="label || $slots.default" class="toggle-label">
        <slot>{{ label }}</slot>
        <span v-if="required" class="required-indicator">*</span>
      </span>
    </label>
    
    <div v-if="hint || hasError" class="hint-text" :class="{ 'error-text': hasError }">
      {{ hasError ? errorMessage : hint }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  label: {
    type: String,
    default: ''
  },
  hint: {
    type: String,
    default: ''
  },
  errorMessage: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  size: {
    type: String,
    default: 'medium',
    validator: (value) => ['small', 'medium', 'large'].includes(value)
  },
  color: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'success', 'warning', 'error', 'info'].includes(value)
  }
});

const emit = defineEmits(['update:modelValue', 'change']);
const isFocused = ref(false);

const hasError = computed(() => !!props.errorMessage);

function handleChange(event) {
  emit('update:modelValue', event.target.checked);
  emit('change', event.target.checked, event);
}
</script>

<style>
:root {
  --toggle-bg: #e5e7eb;
  --toggle-bg-checked: #7c3aed;
  --toggle-border: #d1d5db;
  --toggle-thumb: #ffffff;
  --toggle-text: #374151;
  --toggle-hover: #d1d5db;
  --toggle-focus: rgba(124, 58, 237, 0.3);
  --toggle-disabled: #9ca3af;
  --toggle-error: #ef4444;
}

.dark {
  --toggle-bg: #4b5563;
  --toggle-bg-checked: #8b5cf6;
  --toggle-border: #4b5563;
  --toggle-thumb: #f3f4f6;
  --toggle-text: #e5e7eb;
  --toggle-hover: #6b7280;
  --toggle-focus: rgba(139, 92, 246, 0.3);
  --toggle-disabled: #4b5563;
  --toggle-error: #f87171;
}
</style>

<style scoped>
.base-toggle {
  display: inline-block;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  line-height: 1.5;
}

.toggle-container {
  display: inline-flex;
  align-items: center;
  position: relative;
  cursor: pointer;
  user-select: none;
  gap: 8px;
  margin: 0;
  padding: 4px 0;
}

.toggle-input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
  margin: 0;
  padding: 0;
}

.toggle-track {
  position: relative;
  display: inline-flex;
  align-items: center;
  width: 40px;
  height: 20px;
  background-color: var(--toggle-bg);
  border: 1px solid var(--toggle-border);
  border-radius: 12px;
  transition: all 0.2s ease;
  flex-shrink: 0;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}

.toggle-thumb {
  position: absolute;
  left: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 16px;
  height: 16px;
  background-color: var(--toggle-thumb);
  border-radius: 50%;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  transition: all 0.2s ease;
}

/* Checked state */
.toggle-track.is-checked {
  background-color: var(--toggle-bg-checked);
  border-color: var(--toggle-bg-checked);
}

.toggle-track.is-checked .toggle-thumb {
  transform: translateX(20px);
}

/* Focus state */
.toggle-track.is-focused {
  box-shadow: 0 0 0 3px var(--toggle-focus);
}

/* Hover state */
.toggle-container:hover .toggle-track:not(.is-checked) {
  background-color: var(--toggle-hover);
  border-color: var(--toggle-hover);
}

/* Error state */
.toggle-track.has-error {
  border-color: var(--toggle-error);
}

.toggle-track.has-error.is-checked {
  background-color: var(--toggle-error);
  border-color: var(--toggle-error);
}

/* Disabled state */
.toggle-input:disabled + .toggle-track {
  opacity: 0.6;
  cursor: not-allowed;
  background-color: var(--toggle-disabled);
}

/* Loading state */
.toggle-loading {
  width: 12px;
  height: 12px;
  border: 2px solid var(--toggle-bg);
  border-top-color: var(--toggle-bg-checked);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.toggle-track.is-checked .toggle-loading {
  border-color: var(--toggle-bg-checked);
  border-top-color: var(--toggle-thumb);
}

/* Sizes */
.toggle-track.size-small {
  width: 32px;
  height: 16px;
}

.toggle-track.size-small .toggle-thumb {
  width: 12px;
  height: 12px;
}

.toggle-track.size-small.is-checked .toggle-thumb {
  transform: translateX(16px);
}

.toggle-track.size-large {
  width: 48px;
  height: 24px;
}

.toggle-track.size-large .toggle-thumb {
  width: 20px;
  height: 20px;
}

.toggle-track.size-large.is-checked .toggle-thumb {
  transform: translateX(24px);
}

/* Label */
.toggle-label {
  font-size: 14px;
  color: var(--toggle-text);
  font-weight: 500;
  line-height: 1.5;
}

.required-indicator {
  color: var(--toggle-error);
  margin-left: 2px;
}

/* Hint and error text */
.hint-text {
  margin-top: 4px;
  font-size: 12px;
  line-height: 1.4;
  color: var(--toggle-text);
  opacity: 0.8;
}

.error-text {
  color: var(--toggle-error);
  opacity: 1;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
