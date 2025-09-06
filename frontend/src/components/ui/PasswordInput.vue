<template>
  <InputGroup :label="label" :for-id="id" :hint="hint" :error="error" :required="required">
    <BaseInput
      :id="id"
      :model-value="modelValue"
      @update:modelValue="$emit('update:modelValue', $event)"
      :placeholder="placeholder"
      :disabled="disabled"
      :success="success"
      :size="size"
      :max-length="maxLength"
      :show-char-count="showCharCount"
      autocomplete="new-password"
      type="password"
      :show-password-toggle="true"
    />
    <div v-if="showStrength && modelValue" class="mt-1 flex items-center gap-2">
      <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded">
        <div :class="barClass" class="h-1.5 rounded" :style="{ width: `${score * 25}%` }"></div>
      </div>
      <span class="text-xs" :class="textClass">{{ strengthLabel }}</span>
    </div>
  </InputGroup>
</template>

<script>
import { computed } from 'vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import InputGroup from '@/components/ui/InputGroup.vue';

function calcScore(pw = '') {
  let s = 0;
  if (pw.length >= 8) s++;
  if (/[A-Z]/.test(pw)) s++;
  if (/[a-z]/.test(pw)) s++;
  if (/[0-9]/.test(pw)) s++;
  if (/[^A-Za-z0-9]/.test(pw)) s++;
  return Math.min(4, s);
}

export default {
  name: 'PasswordInput',
  components: { BaseInput, InputGroup },
  props: {
    modelValue: { type: String, default: '' },
    id: { type: String, default: () => `password-${Math.random().toString(36).slice(2)}` },
    label: { type: String, default: 'Password' },
    placeholder: { type: String, default: '••••••••' },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    success: { type: Boolean, default: false },
    size: { type: String, default: 'md' },
    maxLength: { type: [Number, String], default: null },
    showCharCount: { type: Boolean, default: false },
    showStrength: { type: Boolean, default: true },
  },
  emits: ['update:modelValue'],
  setup(props) {
    const score = computed(() => calcScore(props.modelValue));
    const strengthLabel = computed(() => ['Very weak', 'Weak', 'Fair', 'Good', 'Strong'][score.value] || 'Very weak');
    const barClass = computed(() => [
      'bg-red-500',
      'bg-orange-500',
      'bg-yellow-500',
      'bg-lime-500',
      'bg-green-500',
    ][score.value] || 'bg-red-500');
    const textClass = computed(() => [
      'text-red-600',
      'text-orange-600',
      'text-yellow-600',
      'text-lime-600',
      'text-green-600',
    ][score.value] || 'text-red-600');
    return { score, strengthLabel, barClass, textClass };
  },
};
</script>

<style scoped>
</style>
