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
      :autocomplete="autocomplete"
      :prefix-icon="IconSearch"
      type="search"
    />
  </InputGroup>
</template>

<script>
import BaseInput from '@/components/ui/BaseInput.vue';
import InputGroup from '@/components/ui/InputGroup.vue';
import IconSearch from '@/components/ui/icons/IconSearch.vue';

export default {
  name: 'SearchInput',
  components: { BaseInput, InputGroup },
  props: {
    modelValue: { type: [String, Number], default: '' },
    id: { type: String, default: () => `search-${Math.random().toString(36).slice(2)}` },
    label: { type: String, default: '' },
    placeholder: { type: String, default: 'Search…' },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    success: { type: Boolean, default: false },
    size: { type: String, default: 'md' },
    maxLength: { type: [Number, String], default: null },
    showCharCount: { type: Boolean, default: false },
    autocomplete: { type: String, default: 'off' },
  },
  emits: ['update:modelValue'],
  setup() {
    return { IconSearch };
  },
};
</script>

<style scoped>
</style>
