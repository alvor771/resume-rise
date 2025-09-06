<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          {{ $t('auth.forgot.title') }}
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          {{ $t('auth.forgot.remembered') }}
          <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            {{ $t('auth.forgot.signIn') }}
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
              :placeholder="$t('auth.forgot.emailPlaceholder')"
            >
          </div>
        </div>
        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="isLoading"
          >
            <span v-if="!isLoading">{{ $t('auth.forgot.sendResetLink') }}</span>
            <span v-else>{{ $t('common.sending') }}...</span>
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
import { useI18n } from 'vue-i18n';
import { useRouter } from 'vue-router';

const { t } = useI18n();
const router = useRouter();

const email = ref('');
const message = ref('');
const error = ref('');
const isLoading = ref(false);

async function onSubmit() {
  error.value = '';
  message.value = '';
  
  if (!email.value) {
    error.value = t('auth.validation.emailRequired');
    return;
  }

  isLoading.value = true;
  
  try {
    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    message.value = t('auth.forgot.resetLinkSent');
    
    // Optional: Redirect to login after a delay
    setTimeout(() => {
      router.push('/login');
    }, 3000);
  } catch (err) {
    error.value = err.message || t('common.errors.somethingWentWrong');
  } finally {
    isLoading.value = false;
  }
}
</script>
