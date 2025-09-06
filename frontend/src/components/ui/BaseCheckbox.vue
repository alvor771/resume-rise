<template>
  <div class="base-checkbox" :class="{ 'is-disabled': disabled, 'is-inline': inline }">
    <label class="checkbox-container">
      <input
        type="checkbox"
        :checked="modelValue"
        :disabled="disabled || loading"
        :required="required"
        :name="name"
        :value="value"
        v-bind="$attrs"
        @change="handleChange"
        @focus="isFocused = true"
        @blur="isFocused = false"
        class="checkbox-input"
        :class="inputClasses"
      />
      
      <span class="checkbox-custom" :class="customClasses">
        <transition name="fade" mode="out-in">
          <svg
            v-if="isChecked"
            class="check-icon"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M20 6L9 17L4 12"
              stroke="currentColor"
              stroke-width="3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
          <span v-else-if="indeterminate" class="indeterminate-line"></span>
        </transition>
      </span>
      
      <span v-if="$slots.default || label" class="label-text">
        <slot>{{ label }}</slot>
        <span v-if="required" class="required-indicator">*</span>
      </span>
    </label>
    
    <transition name="fade">
      <div v-if="(error || hint) && !hideDetails" class="hint-text" :class="{ 'error-text': error }">
        {{ error || hint }}
      </div>
    </transition>
    
    <transition name="fade">
      <div v-if="loading" class="loading-overlay">
        <div class="loading-spinner"></div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  modelValue: {
    type: [Boolean, Array],
    default: false
  },
  value: {
    type: [String, Number, Boolean],
    default: undefined
  },
  label: {
    type: String,
    default: ''
  },
  name: {
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
  error: {
    type: [String, Boolean],
    default: ''
  },
  hint: {
    type: String,
    default: ''
  },
  indeterminate: {
    type: Boolean,
    default: false
  },
  hideDetails: {
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
    validator: (value) => ['primary', 'secondary', 'success', 'error', 'warning', 'info'].includes(value)
  },
  inline: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue', 'change', 'focus', 'blur']);

const isFocused = ref(false);

const isChecked = computed(() => {
  if (Array.isArray(props.modelValue)) {
    return props.modelValue.includes(props.value);
  }
  return props.indeterminate ? false : props.modelValue;
});

const inputClasses = computed(() => ({
  [`size-${props.size}`]: true,
  'has-error': !!props.error,
  'is-focused': isFocused.value,
  'is-checked': isChecked.value,
  'is-indeterminate': props.indeterminate
}));

const customClasses = computed(() => ({
  [`color-${props.color}`]: true,
  'is-checked': isChecked.value,
  'is-indeterminate': props.indeterminate,
  'is-focused': isFocused.value,
  'has-error': !!props.error
}));

function handleChange(event) {
  let newValue;
  
  if (Array.isArray(props.modelValue)) {
    const set = new Set([...props.modelValue]);
    if (event.target.checked) {
      set.add(props.value);
    } else {
      set.delete(props.value);
    }
    newValue = Array.from(set);
  } else {
    newValue = event.target.checked;
  }
  
  emit('update:modelValue', newValue);
  emit('change', newValue, event);
}

// Focus and blur events are handled directly in the template
</script>

<style>
:root {
  --checkbox-bg: #fff;
  --checkbox-border: #d1d5db;
  --checkbox-text: #374151;
  --checkbox-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
  --checkbox-hover-bg: #f5f3ff;
  --checkbox-disabled-bg: #f5f3ff;
  --checkbox-checked-bg: #7c3aed;
  --checkbox-checked-border: #7c3aed;
}

.dark {
  --checkbox-bg: #111827;
  --checkbox-border: #4b5563;
  --checkbox-text: #e5e7eb;
  --checkbox-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
  --checkbox-hover-bg: #374151;
  --checkbox-disabled-bg: #374151;
  --checkbox-checked-bg: #7c3aed;
  --checkbox-checked-border: #8b5cf6;
}

</style>

<style scoped>
/* Base styles */
.base-checkbox {
  position: relative;
  display: inline-block;
  vertical-align: middle;
  margin-bottom: 8px;
}

.checkbox-container {
  display: inline-flex;
  align-items: center;
  position: relative;
  cursor: pointer;
  padding: 4px 0;
  user-select: none;
  gap: 8px;
}

.checkbox-input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
  margin: 0;
  padding: 0;
}

.checkbox-custom {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 16px;
  height: 16px;
  border: 2px solid var(--checkbox-border);
  border-radius: 4px;
  background-color: #fff;
  transition: all 0.2s ease;
  flex-shrink: 0;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
}

/* Sizes */
.checkbox-custom.size-small {
  width: 16px;
  height: 16px;
}

.checkbox-custom.size-medium {
  width: 20px;
  height: 20px;
}

.checkbox-custom.size-large {
  width: 24px;
  height: 24px;
}

/* Colors */
.checkbox-custom.color-primary {
  border-color: var(--color-primary, #4f46e5);
  background-color: var(--color-primary-50, #eef2ff);
}

.checkbox-custom.color-secondary {
  border-color: var(--color-secondary, #6b7280);
  background-color: var(--color-secondary-50, #f9fafb);
}

.checkbox-custom.color-success {
  border-color: var(--color-success, #10b981);
  background-color: var(--color-success-50, #ecfdf5);
}

.checkbox-custom.color-error {
  border-color: var(--color-error, #ef4444);
  background-color: var(--color-error-50, #fef2f2);
}

.checkbox-custom.color-warning {
  border-color: var(--color-warning, #f59e0b);
  background-color: var(--color-warning-50, #fffbeb);
}

.checkbox-custom.color-info {
  border-color: var(--color-info, #7c3aed);
  background-color: var(--color-info-50, #f5f3ff);
}

/* States */
.checkbox-custom.is-checked {
  background-color: var(--checkbox-checked-bg);
  border-color: var(--checkbox-checked-border);
  transform: scale(1.03);
}

.checkbox-custom.is-checked .check-icon {
  color: white;
  display: block;
}

.checkbox-custom.is-indeterminate {
  background-color: var(--checkbox-checked-bg);
  border-color: var(--checkbox-checked-border);
}

.checkbox-custom.is-indeterminate .indeterminate-line {
  display: block;
  width: 12px;
  height: 2px;
  background-color: #fff;
  border-radius: 1px;
}

.checkbox-custom.is-focused {
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.3);
  border-color: #7c3aed;
  transform: scale(1.03);
}

.checkbox-custom.has-error {
  border-color: var(--color-error, #ef4444);
}

.checkbox-input:disabled + .checkbox-custom {
  opacity: 0.6;
  cursor: not-allowed;
  background-color: var(--checkbox-hover-bg);
}

.checkbox-input:checked + .checkbox-custom {
  background-color: var(--checkbox-checked-bg);
  border-color: var(--checkbox-checked-border);
  transform: scale(1.03);
}

/* Label text */
.label-text {
  margin-left: 0;
  font-size: 14px;
  line-height: 1.5;
  color: var(--checkbox-text);
  font-weight: 500;
}

.required-indicator {
  color: var(--color-error, #ef4444);
  margin-left: 2px;
}

/* Hint and error text */
.hint-text {
  font-size: 0.75rem;
  line-height: 1rem;
  margin-top: 4px;
  color: #718096;
}

.error-text {
  color: var(--color-error, #ef4444);
}

/* Loading state */
.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 4px;
}

.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid var(--checkbox-border);
  border-top-color: var(--checkbox-checked-bg);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Dark mode */
@media (prefers-color-scheme: dark) {
  .checkbox-custom {
    border-color: #4a5568;
    background-color: #2d3748;
  }

  .label-text {
    color: #e2e8f0;
  }

  .hint-text {
    color: #a0aec0;
  }

  .checkbox-input:disabled + .checkbox-custom {
    background-color: #2d3748;
    opacity: 0.5;
  }
}
</style>