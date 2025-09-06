<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          {{ $t('auth.reset.title') }}
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          {{ $t('auth.reset.remembered') }}
          <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            {{ $t('auth.reset.signIn') }}
          </router-link>
          {{ $t('auth.reset.or') }}
          <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            {{ $t('auth.reset.createAccount') }}
          </router-link>
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="onSubmit">
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="email" class="sr-only">{{ $t('auth.emailAddress') }}</label>
            <input
              id="email"
              v-model="email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-800 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              :placeholder="$t('auth.reset.emailPlaceholder')"
            >
          </div>
          <div v-if="showPasswordFields">
            <label for="password" class="sr-only">{{ $t('auth.reset.newPassword') }}</label>
            <input
              id="password"
              v-model="password"
              name="password"
              type="password"
              autocomplete="new-password"
              required
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-800 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              :placeholder="$t('auth.reset.newPasswordPlaceholder')"
            >
            <label for="confirm-password" class="sr-only">{{ $t('auth.reset.confirmPassword') }}</label>
            <input
              id="confirm-password"
              v-model="confirmPassword"
              name="confirm-password"
              type="password"
              autocomplete="new-password"
              required
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-800 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm mt-2"
              :placeholder="$t('auth.reset.confirmPasswordPlaceholder')"
            >
          </div>
        </div>
        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ showPasswordFields ? $t('auth.reset.changePassword') : $t('auth.reset.sendLink') }}
          </button>
        </div>
        <div v-if="message" class="mt-4 text-center text-green-600">{{ message }}</div>
        <div v-if="error" class="mt-4 text-center text-red-500">{{ error }}</div>
      </form>
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue';

const email = ref('');
const password = ref('');
const confirmPassword = ref('');
const message = ref('');
const error = ref('');
const showPasswordFields = ref(false);

function onSubmit() {
  error.value = '';
  message.value = '';
  if (!showPasswordFields.value) {
    // Имитация отправки ссылки
    message.value = 'Reset link sent to ' + email.value;
    showPasswordFields.value = true;
  } else {
    if (password.value !== confirmPassword.value) {
      error.value = 'Passwords do not match';
      return;
    }
    // Имитация смены пароля
    message.value = 'Password changed successfully!';
  }
}
</script>
