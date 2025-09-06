<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          {{ $t('auth.login.title') }}
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          {{ $t('auth.login.noAccount') }}
          <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            {{ $t('auth.login.signUp') }}
          </router-link>
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <!-- Error message -->
        <div v-if="error" class="rounded-md bg-red-50 dark:bg-red-900/30 p-4 mb-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800 dark:text-red-200">{{ error }}</h3>
            </div>
          </div>
        </div>
        
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="email-address" class="sr-only">{{ $t('auth.emailAddress') }}</label>
            <input
              id="email-address"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              :disabled="loading"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-800 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              :class="{ 'opacity-75': loading }"
              :placeholder="$t('auth.login.email')"
            >
          </div>
          <div>
            <label for="password" class="sr-only">{{ $t('auth.password') }}</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              :disabled="loading"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-800 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              :class="{ 'opacity-75': loading }"
              :placeholder="$t('auth.login.password')"
            >
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember-me"
              v-model="form.remember"
              name="remember-me"
              type="checkbox"
              :disabled="loading"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700"
              :class="{ 'opacity-75': loading }"
            >
            <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
              {{ $t('auth.login.rememberMe') }}
            </label>
          </div>

          <div class="text-sm">
            <router-link 
              to="/forgot-password" 
              class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
              :class="{ 'opacity-50 pointer-events-none': loading }"
            >
              {{ $t('auth.login.forgotPassword') }}
            </router-link>
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
            :disabled="loading"
            :class="{
              'opacity-75 cursor-not-allowed': loading,
              'hover:bg-indigo-700': !loading
            }"
          >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="h-5 w-5 text-indigo-300 group-hover:text-white transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v5.586L5.707 5.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L11 8.586V3a1 1 0 00-1-1z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M5 12a1 1 0 00-1 1v4a1 1 0 001 1h10a1 1 0 001-1v-4a1 1 0 10-2 0v3H6v-3a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </span>
            <span class="flex items-center">
              <span v-if="!loading">{{ $t('auth.login.submit') }}</span>
              <span v-else>{{ $t('common.loading') }}</span>
            </span>
          </button>
        </div>
      </form>
      
      <div class="mt-6 text-center text-sm">
        <p class="text-gray-600 dark:text-gray-400">
          {{ $t('auth.login.dontHaveAccount') }}
          <router-link 
            to="/register" 
            class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
            :class="{ 'opacity-50 pointer-events-none': loading }"
          >
            {{ $t('auth.login.signUp') }}
          </router-link>
        </p>
      </div>
      
      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400">
              {{ $t('auth.login.orContinueWith') }}
            </span>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
          <div>
            <a 
              href="/api/auth/google/redirect" 
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
              :class="{ 'opacity-50 pointer-events-none': loading }"
            >
              <span class="sr-only">{{ $t('auth.login.signInWithGoogle') }}</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z" />
              </svg>
              <span class="ml-2 hidden sm:inline">Google</span>
            </a>
          </div>

          <div>
            <a 
              href="/api/auth/github/redirect" 
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
              :class="{ 'opacity-50 pointer-events-none': loading }"
            >
              <span class="sr-only">{{ $t('auth.login.signInWithGithub') }}</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 7.073c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.14 20.115 22 16.379 22 12.017 22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
              </svg>
              <span class="ml-2 hidden sm:inline">GitHub</span>
            </a>
          </div>
        </div>
        
        <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
          {{ $t('auth.login.agreeToTerms') }}
          <router-link to="/terms" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            {{ $t('common.terms') }}
          </router-link>
          {{ $t('common.and') }}
          <router-link to="/privacy" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            {{ $t('common.privacyPolicy') }}
          </router-link>.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';
import { useHead } from '@vueuse/head';

export default {
  name: 'LoginView',
  setup() {
    const router = useRouter();
    const route = useRoute();
    const authStore = useAuthStore();
    const toast = useToast();
    const { t } = useI18n();
    
    // Set page title and meta tags
    useHead({
      title: t('auth.login.pageTitle') + ' | ' + t('app.name'),
      meta: [
        { name: 'description', content: t('auth.login.metaDescription') },
        { property: 'og:title', content: t('auth.login.pageTitle') },
        { property: 'og:description', content: t('auth.login.metaDescription') },
        { property: 'og:type', content: 'website' },
        { name: 'robots', content: 'noindex, nofollow' }
      ]
    });
    
    const form = ref({
      email: '',
      password: '',
      remember: false
    });
    
    const loading = ref(false);
    const error = ref('');
    
    // Auto-focus email field on component mount
    onMounted(() => {
      const emailInput = document.getElementById('email-address');
      if (emailInput) {
        emailInput.focus();
      }
      
      // Check for redirect messages
      if (route.query.registered) {
        toast.success(t('auth.login.registrationSuccess'));
      } else if (route.query.sessionExpired) {
        toast.warning(t('auth.login.sessionExpired'));
      } else if (route.query.loggedOut) {
        toast.info(t('auth.login.loggedOut'));
      }
    });

    const handleLogin = async () => {
      if (loading.value) return;
      
      loading.value = true;
      error.value = '';
      
      // Basic client-side validation
      if (!form.value.email || !form.value.password) {
        error.value = t('auth.validation.requiredFields');
        loading.value = false;
        return;
      }
      
      try {
        await authStore.login({
          email: form.value.email.trim(),
          password: form.value.password,
          remember: form.value.remember
        });
        
        toast.success(t('auth.login.success'));
        
        // Redirect to the intended URL or dashboard
        const redirectPath = route.query.redirect || '/dashboard';
        await router.push(redirectPath);
      } catch (err) {
        console.error('Login error:', err);
        error.value = err.response?.data?.message || err.message || t('auth.login.error');
        toast.error(error.value);
      } finally {
        loading.value = false;
      }
    };
    
    return {
      form,
      loading,
      error,
      handleLogin,
      t // Expose the translation function to the template
    };
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
