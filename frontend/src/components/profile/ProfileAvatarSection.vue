<template>
  <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex items-center space-x-4">
      <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
        <img v-if="avatarUrl" :src="avatarUrl" alt="Avatar" class="h-full w-full object-cover" />
        <svg v-else class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
      </div>
      <div>
        <div class="font-medium text-gray-900 dark:text-white mb-1">{{ $t('profile.avatar.title') }}</div>
        <div class="flex items-center space-x-2">
          <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />
          <button type="button" @click="triggerFile" :disabled="loading" class="inline-flex px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
            {{ $t('profile.avatar.change') }}
          </button>
          <button v-if="avatarUrl" type="button" @click="removeAvatar" :disabled="loading" class="inline-flex px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
            {{ $t('profile.avatar.remove') }}
          </button>
          <button v-if="pendingFile" type="button" @click="uploadAvatar" :disabled="loading" class="inline-flex px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
            {{ loading ? $t('common.saving') : $t('common.save') }}
          </button>
        </div>
        <div v-if="error" class="mt-1 text-xs text-red-500">{{ error }}</div>
        <div v-if="success" class="mt-1 text-xs text-green-600">{{ success }}</div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const auth = useAuthStore();
const avatarUrl = ref('');
const error = ref('');
const success = ref('');
const loading = ref(false);
const fileInput = ref(null);
const pendingFile = ref(null);
function triggerFile() {
  fileInput.value.click();
}
function onFileChange(e) {
  const file = e.target.files[0];
  if (!file) return;
  if (!file.type.startsWith('image/')) {
    error.value = t('profile.avatar.errorType');
    return;
  }
  if (file.size > 2 * 1024 * 1024) {
    error.value = t('profile.avatar.errorSize');
    return;
  }
  error.value = '';
  success.value = '';
  pendingFile.value = file;
  const reader = new FileReader();
  reader.onload = (ev) => {
    avatarUrl.value = ev.target.result;
  };
  reader.readAsDataURL(file);
}
function removeAvatar() {
  avatarUrl.value = '';
  pendingFile.value = null;
}

async function uploadAvatar() {
  if (!pendingFile.value) return;
  try {
    loading.value = true;
    error.value = '';
    success.value = '';
    // Ensure Authorization header exists
    if (!axios.defaults.headers.common['Authorization']) {
      const token = localStorage.getItem('token');
      if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
    const fd = new FormData();
    fd.append('avatar', pendingFile.value);
    // Some backends expect _method when handling multipart for PUT
    fd.append('_method', 'PUT');
    await axios.post('/api/user/profile', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    // Refresh user and update avatar
    const { data } = await axios.get('/api/user');
    try { auth.user = data; } catch (e) {
      // Store may be readonly or not reactive here; safely ignore assignment failures
    }
    // Common avatar fields
    avatarUrl.value = data.avatar_url || data.avatar || '';
    success.value = t('common.saved');
    pendingFile.value = null;
  } catch (e) {
    error.value = e?.response?.data?.message || e?.message || 'Upload failed';
  } finally {
    loading.value = false;
  }
}

onMounted(async () => {
  // Initialize avatar from store quickly
  const u = auth.user;
  if (u) {
    avatarUrl.value = u.avatar_url || u.avatar || '';
  }
  // Ensure Authorization header exists for any future calls
  if (!axios.defaults.headers.common['Authorization']) {
    const token = localStorage.getItem('token');
    if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  }
});
</script>
