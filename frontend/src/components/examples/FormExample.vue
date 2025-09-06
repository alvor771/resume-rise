<template>
  <div class="max-w-md mx-auto p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Form Example</h2>
    
    <!-- Radio Group Example -->
    <div class="space-y-2">
      <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Gender</p>
      <div class="space-y-2">
        <BaseRadio
          v-model="form.gender"
          name="gender"
          value="male"
          label="Male"
          :error="errors.gender"
        />
        <BaseRadio
          v-model="form.gender"
          name="gender"
          value="female"
          label="Female"
        />
        <BaseRadio
          v-model="form.gender"
          name="gender"
          value="other"
          label="Other"
        />
      </div>
    </div>

    <!-- Checkbox Single Example -->
    <div class="space-y-2">
      <BaseCheckbox
        v-model="form.terms"
        name="terms"
        label="I agree to the terms and conditions"
        :required="true"
        :error="errors.terms"
        hint="You must agree to the terms to continue"
      />
    </div>

    <!-- Checkbox Group Example -->
    <div class="space-y-2">
      <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Interests</p>
      <div class="space-y-2">
        <BaseCheckbox
          v-for="interest in interests"
          :key="interest.value"
          v-model="form.interests"
          :value="interest.value"
          :label="interest.label"
          name="interests"
          :array-value="true"
          :error="errors.interests"
        />
      </div>
    </div>

    <!-- Loading State Example -->
    <div class="space-y-2">
      <BaseCheckbox
        v-model="form.newsletter"
        name="newsletter"
        label="Subscribe to newsletter"
        :loading="isSubscribing"
        @change="toggleNewsletter"
      />
    </div>

    <!-- Form Actions -->
    <div class="pt-4">
      <button
        type="button"
        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        @click="submitForm"
      >
        Submit
      </button>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue';
import BaseRadio from '@/components/ui/BaseRadio.vue';
import BaseCheckbox from '@/components/ui/BaseCheckbox.vue';

export default {
  name: 'FormExample',
  components: {
    BaseRadio,
    BaseCheckbox
  },
  setup() {
    const form = reactive({
      gender: 'male',
      terms: false,
      interests: [],
      newsletter: false
    });

    const errors = reactive({
      gender: '',
      terms: '',
      interests: ''
    });

    const isSubscribing = ref(false);

    const interests = [
      { value: 'sports', label: 'Sports' },
      { value: 'music', label: 'Music' },
      { value: 'reading', label: 'Reading' },
      { value: 'travel', label: 'Travel' }
    ];

    const toggleNewsletter = async () => {
      isSubscribing.value = true;
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 1000));
      isSubscribing.value = false;
    };

    const validateForm = () => {
      let isValid = true;
      
      // Reset errors
      Object.keys(errors).forEach(key => {
        errors[key] = '';
      });

      // Validate gender
      if (!form.gender) {
        errors.gender = 'Please select a gender';
        isValid = false;
      }

      // Validate terms
      if (!form.terms) {
        errors.terms = 'You must accept the terms and conditions';
        isValid = false;
      }

      // Validate interests (at least one required)
      if (form.interests.length === 0) {
        errors.interests = 'Please select at least one interest';
        isValid = false;
      }

      return isValid;
    };

    const submitForm = () => {
      if (validateForm()) {
        console.log('Form submitted:', form);
        // Submit form logic here
      } else {
        console.log('Form validation failed');
      }
    };

    return {
      form,
      errors,
      interests,
      isSubscribing,
      toggleNewsletter,
      submitForm
    };
  }
};
</script>
