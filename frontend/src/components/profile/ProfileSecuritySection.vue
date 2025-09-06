<template>
  <div class="space-y-8">
    <!-- Change Password -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
          {{ $t('profile.security.changePassword') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
          {{ $t('profile.security.passwordAdvice') }}
        </p>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5">
        <form class="space-y-4" @submit.prevent="onChangePassword">
          <PasswordInput v-model="form.current" :label="$t('profile.security.currentPassword')" required :disabled="loading" />
          <PasswordInput v-model="form.new" :label="$t('profile.security.newPassword')" required :disabled="loading" :show-strength="true" />
          <PasswordInput v-model="form.confirm" :label="$t('profile.security.confirmPassword')" required :disabled="loading" />
          <div class="flex justify-end">
            <Button :disabled="loading" :loading="loading" type="submit" variant="primary">
              {{ $t('profile.security.savePassword') }}
            </Button>
          </div>
          <div v-if="error" class="text-sm text-red-600 mt-2">{{ error }}</div>
          <div v-if="success" class="text-sm text-green-600 mt-2">{{ success }}</div>
        </form>
      </div>
    </div>
    <!-- 2FA, sessions, export, etc. будут добавлены далее -->
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useI18n } from 'vue-i18n';
import PasswordInput from '@/components/ui/PasswordInput.vue';
import Button from '@/components/ui/Button.vue';
const { t } = useI18n();
const form = ref({ current: '', new: '', confirm: '' });
const loading = ref(false);
const error = ref('');
const success = ref('');

async function onChangePassword() {
  try {
    error.value = '';
    success.value = '';
    if (form.value.new !== form.value.confirm) {
      error.value = t('profile.security.passwordsMismatch') || 'Passwords do not match';
      return;
    }
    loading.value = true;
    await axios.put('/api/user/change-password', {
      current_password: form.value.current,
      password: form.value.new,
      password_confirmation: form.value.confirm,
    });
    success.value = t('profile.security.passwordChanged') || 'Password changed';
    form.value.current = '';
    form.value.new = '';
    form.value.confirm = '';
  } catch (e) {
    error.value = e?.response?.data?.message || e?.message || 'Failed to change password';
  } finally {
    loading.value = false;
  }
}
</script>
