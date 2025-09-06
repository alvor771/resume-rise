<template>
  <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">{{ $t('profile.2fa.title') }}</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ $t('profile.2fa.subtitle') }}</p>
      </div>
      <button v-if="!enabled" @click="enable2FA" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ $t('profile.2fa.enable') }}
      </button>
      <button v-else @click="disable2FA" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        {{ $t('profile.2fa.disable') }}
      </button>
    </div>
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5">
      <div v-if="enabled" class="text-green-600 dark:text-green-400">{{ $t('profile.2fa.enabled') }}</div>
      <div v-else class="text-gray-400 dark:text-gray-500">{{ $t('profile.2fa.disabled') }}</div>
    </div>
  </div>
</template>
<script setup>
  import { ref } from 'vue';
  const enabled = ref(false);
  function enable2FA() {
    enabled.value = true;
    // TODO: handle enabling 2FA
  }
  function disable2FA() {
    enabled.value = false;
    // TODO: handle disabling 2FA
  }
</script>
