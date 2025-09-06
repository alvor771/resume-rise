<template>
  <div class="space-y-8">
    <!-- Основная информация профиля -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
        <div>
          <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
            {{ $t('profile.main.title') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('profile.main.subtitle') }}
          </p>
        </div>
        <Button v-if="!editMode" variant="primary" size="md" @click="editMode = true">
          {{ $t('profile.main.edit') }}
        </Button>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5">
        <form v-if="editMode" @submit.prevent="onSave" class="space-y-4 px-4">
          <!-- Имя -->
          <BaseInput v-model="form.name" :label="$t('profile.main.name')" required :loading="loading" :error="error" :prefix-icon="IconUser" />

          <!-- Email -->
          <div>
            <BaseInput v-model="form.email" type="email" :label="$t('profile.main.email')" required :loading="loading" :error="error" :prefix-icon="IconMail" />
            <div class="mt-1 flex items-center space-x-2">
              <span :class="form.emailVerified ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'">
                <svg v-if="form.emailVerified" class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg v-else class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414 1.414A9 9 0 105.636 18.364l1.414-1.414"/></svg>
                {{ form.emailVerified ? $t('profile.main.verified') : $t('profile.main.notVerified') }}
              </span>
              <Button v-if="!form.emailVerified" type="button" variant="link" size="xs" @click="onResendVerification">
                {{ $t('profile.main.resend') }}
              </Button>
            </div>
          </div>

          <!-- Телефон -->
          <BaseInput v-model="form.phone" type="tel" :label="$t('profile.main.phone')" :loading="loading" :prefix-icon="IconPhone" />

          <!-- Страна и город -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <BaseInput v-model="form.country" :label="$t('profile.main.country')" :loading="loading" :prefix-icon="IconMapPin" />
            <BaseInput v-model="form.city" :label="$t('profile.main.city')" :loading="loading" :prefix-icon="IconMapPin" />
          </div>

          <!-- Биография -->
          <BaseTextarea v-model="form.bio" :label="$t('profile.main.bio')" rows="3" :loading="loading" />

          <!-- Соцсети -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <BaseInput v-model="form.linkedin" type="url" label="LinkedIn" :loading="loading" :prefix-icon="IconLinkedIn" />
            <BaseInput v-model="form.telegram" type="text" label="Telegram" :loading="loading" :prefix-icon="IconLink" />
            <BaseInput v-model="form.website" type="url" :label="$t('profile.main.website')" :loading="loading" :prefix-icon="IconLink" />
          </div>

          <!-- Публичный профиль -->
          <ToggleSwitch v-model="form.publicProfile" :label="$t('profile.main.publicProfile')" />

          <div class="flex justify-end gap-2 mt-6">
            <Button type="button" variant="outline-secondary" @click="editMode = false">
              {{ $t('profile.main.cancel') }}
            </Button>
            <Button type="submit" variant="primary" :loading="loading">
              {{ $t('profile.main.save') }}
            </Button>
          </div>
          <div v-if="error" class="text-sm text-red-600 mt-2">{{ error }}</div>
          <div v-if="success" class="text-sm text-green-600 mt-2">{{ success }}</div>
        </form>
        <div v-else class="space-y-2">
          <div class="flex items-center space-x-4">
            <div class="font-bold text-gray-900 dark:text-white">{{ form.name }}</div>
            <span class="text-xs px-2 py-1 rounded bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">{{ form.role }}</span>
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">{{ form.email }}</div>
          <div v-if="form.phone" class="text-sm text-gray-500 dark:text-gray-400">{{ form.phone }}</div>
          <div v-if="form.country || form.city" class="text-sm text-gray-500 dark:text-gray-400">
            {{ [form.country, form.city].filter(Boolean).join(', ') }}
          </div>
          <div v-if="form.bio" class="text-sm text-gray-700 dark:text-gray-200 mt-2">{{ form.bio }}</div>
          <div class="flex space-x-2 mt-2">
            <a v-if="form.linkedin" :href="form.linkedin" target="_blank" rel="noopener" aria-label="LinkedIn"><svg class="h-5 w-5 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.8 0-5 2.2-5 5v14c0 2.8 2.2 5 5 5h14c2.8 0 5-2.2 5-5v-14c0-2.8-2.2-5-5-5zm-11 19h-3v-9h3v9zm-1.5-10.3c-1 0-1.7-.7-1.7-1.7s.7-1.7 1.7-1.7 1.7.7 1.7 1.7-.8 1.7-1.7 1.7zm14.5 10.3h-3v-4.7c0-1.1 0-2.5-1.5-2.5s-1.7 1.2-1.7 2.4v4.8h-3v-9h2.9v1.2h.1c.4-.7 1.4-1.5 2.8-1.5 3 0 3.6 2 3.6 4.6v4.7z"/></svg></a>
            <a v-if="form.telegram" :href="'https://t.me/' + form.telegram" target="_blank" rel="noopener" aria-label="Telegram"><svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9.04 16.96l-.39 3.42c.56 0 .8-.24 1.09-.53l2.62-2.49 5.44 3.98c1 .56 1.72.27 1.97-.92l3.58-16.84c.33-1.54-.56-2.14-1.56-1.76l-21.41 8.25c-1.46.56-1.44 1.36-.25 1.72l5.49 1.71 12.74-8.01c.6-.37 1.15-.16.7.21z"/></svg></a>
            <a v-if="form.website" :href="form.website" target="_blank" rel="noopener" aria-label="Website"><svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10-10-4.48-10-10 4.48-10 10-10zm0 18c4.41 0 8-3.59 8-8s-3.59-8-8-8-8 3.59-8 8 3.59 8 8 8zm0-14c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6 2.69-6 6-6z"/></svg></a>
          </div>
          <div class="mt-2 text-xs" :class="form.publicProfile ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500'">
            {{ form.publicProfile ? $t('profile.main.publicProfileOn') : $t('profile.main.publicProfileOff') }}
          </div>
        </div>
      </div>
    </div>
    <!-- TODO: добавить фото, дату регистрации, и другие секции -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseTextarea from '@/components/ui/BaseTextarea.vue';
import ToggleSwitch from '@/components/ui/ToggleSwitch.vue';
import Button from '@/components/ui/Button.vue';
import IconUser from '@/components/ui/icons/IconUser.vue';
import IconMail from '@/components/ui/icons/IconMail.vue';
import IconPhone from '@/components/ui/icons/IconPhone.vue';
import IconMapPin from '@/components/ui/icons/IconMapPin.vue';
import IconLink from '@/components/ui/icons/IconLink.vue';
import IconLinkedIn from '@/components/ui/icons/IconLinkedIn.vue';

const editMode = ref(false);
const loading = ref(false);
const error = ref('');
const success = ref('');

const form = ref({
  id: null,
  name: '',
  email: '',
  emailVerified: false,
  phone: '',
  country: '',
  city: '',
  bio: '',
  linkedin: '',
  telegram: '',
  website: '',
  publicProfile: false,
  role: 'user',
});

const auth = useAuthStore();

function hydrateFrom(user) {
  if (!user) return;
  form.value.id = user.id ?? null;
  form.value.name = user.name ?? '';
  form.value.email = user.email ?? '';
  form.value.emailVerified = !!(user.email_verified_at);
  form.value.phone = user.phone ?? '';
  form.value.country = user.country ?? '';
  form.value.city = user.city ?? '';
  form.value.bio = user.bio ?? '';
  form.value.linkedin = user.linkedin ?? '';
  form.value.telegram = user.telegram ?? '';
  form.value.website = user.website ?? '';
  form.value.publicProfile = !!(user.public_profile);
  form.value.role = user.role ?? form.value.role;
}

async function loadCurrent() {
  try {
    loading.value = true;
    error.value = '';
    // Hydrate from store first for instant UI
    hydrateFrom(auth.user);
    // Then refresh from API
    const { data } = await axios.get('/api/user');
    hydrateFrom(data);
    // Sync to auth store for global state
    try { auth.user = data; } catch (e) {
      // Store may be readonly or not reactive here; safely ignore assignment failures
    }
  } catch (e) {
    error.value = e?.response?.data?.message || e?.message || 'Failed to load profile';
  } finally {
    loading.value = false;
  }
}

async function onSave() {
  try {
    loading.value = true;
    error.value = '';
    success.value = '';
    const payload = {
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      country: form.value.country,
      city: form.value.city,
      bio: form.value.bio,
      linkedin: form.value.linkedin,
      telegram: form.value.telegram,
      website: form.value.website,
      public_profile: form.value.publicProfile,
    };
    await axios.put('/api/user/profile', payload);
    // reflect backend response if needed
    // Refresh user from backend so UI shows DB state
    const { data } = await axios.get('/api/user');
    hydrateFrom(data);
    try { auth.user = data; } catch (e) {
      // Store may be readonly or not reactive here; safely ignore assignment failures
    }
    success.value = 'Saved';
    editMode.value = false;
  } catch (e) {
    error.value = e?.response?.data?.message || 'Failed to save';
  } finally {
    loading.value = false;
  }
}

function onResendVerification() {
  // Optional: implement when backend route is available
}

onMounted(loadCurrent);
</script>
