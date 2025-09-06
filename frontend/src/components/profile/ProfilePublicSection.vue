<template>
  <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">{{ $t('profile.public.title') }}</h3>
      <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ $t('profile.public.subtitle') }}</p>
    </div>
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5">
      <ToggleSwitch v-model="publicProfile" :label="$t('profile.public.label')" />
      <div v-if="publicProfile" class="mt-4 text-sm text-green-600 dark:text-green-400">{{ $t('profile.public.enabled') }}</div>
      <div v-else class="mt-4 text-sm text-gray-400 dark:text-gray-500">{{ $t('profile.public.disabled') }}</div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import ToggleSwitch from '@/components/ui/ToggleSwitch.vue';
const publicProfile = ref(false);
</script>
