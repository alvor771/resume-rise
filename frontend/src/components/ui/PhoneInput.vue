<template>
  <BaseInput
    v-bind="$attrs"
    :model-value="formattedValue"
    type="tel"
    :error="errorMessage || error"
    @update:model-value="handleInput"
    @blur="handleBlur"
  >
    <template #prefix>
      <span class="text-gray-500">+</span>
    </template>
  </BaseInput>
</template>

<script>
import { ref, computed, watch } from 'vue';
import BaseInput from './BaseInput.vue';

export default {
  name: 'PhoneInput',
  components: { BaseInput },
  inheritAttrs: false,
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    error: {
      type: String,
      default: ''
    },
    format: {
      type: String,
      default: '(##) ###-###-###',
      validator: (value) => {
        const digitCount = (value.match(/#/g) || []).length;
        return digitCount > 0 && digitCount <= 15;
      }
    },
    required: {
      type: Boolean,
      default: false
    },
    label: {
      type: String,
      default: 'Phone Number'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    hint: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue', 'blur'],
  setup(props, { emit }) {
    const touched = ref(false);
    const errorMessage = ref('');

    const formattedValue = computed(() => {
      if (!props.modelValue) return '';

      const numbers = props.modelValue.replace(/\D/g, '');
      let result = '';
      let numIndex = 0;

      for (let i = 0; i < props.format.length; i++) {
        if (numIndex >= numbers.length) break;
        if (props.format[i] === '#') {
          result += numbers[numIndex];
          numIndex++;
        } else {
          result += props.format[i];
        }
      }

      return result;
    });

    const validate = (value) => {
      if (props.required && !value) {
        return 'Phone number is required';
      }
      if (value && value.replace(/\D/g, '').length < 10) {
        return 'Phone number is too short';
      }
      return '';
    };

    const handleInput = (value) => {
      const numbers = value.replace(/\D/g, '');
      emit('update:modelValue', numbers);
      errorMessage.value = '';
    };

    const handleBlur = (e) => {
      touched.value = true;
      errorMessage.value = validate(props.modelValue);
      emit('blur', e);
    };

    watch(() => props.modelValue, (newVal) => {
      if (touched.value) {
        errorMessage.value = validate(newVal);
      }
    });

    return {
      formattedValue,
      errorMessage,
      handleInput,
      handleBlur
    };
  }
};
</script>
