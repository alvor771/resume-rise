<template>
  <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">{{ $t('profile.sessions.title') }}</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ $t('profile.sessions.subtitle') }}</p>
      </div>
      <button @click="logoutAll" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        {{ $t('profile.sessions.logoutAll') }}
      </button>
    </div>
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5">
      <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        <li v-for="session in sessions" :key="session.device + '-' + session.ip + '-' + session.lastActive" class="py-4 flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21l3-1.5L15 21l-.75-4M9 7V3.75A2.25 2.25 0 0111.25 1.5h1.5A2.25 2.25 0 0115 3.75V7"/></svg>
            <div>
              <div class="font-medium text-gray-900 dark:text-white">{{ session.device }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ session.ip }} &bull; {{ session.location }}</div>
              <div class="text-xs text-gray-400 dark:text-gray-500">{{ $t('profile.sessions.lastActive') }}: {{ session.lastActive }}</div>
            </div>
          </div>
          <button v-if="!session.current" @click="logoutSession(session)" class="text-xs text-red-600 hover:underline dark:text-red-400">{{ $t('profile.sessions.logout') }}</button>
          <span v-else class="text-xs text-green-600 dark:text-green-400">{{ $t('profile.sessions.current') }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
const sessions = ref([
  { device: 'Chrome (Windows)', ip: '192.168.1.2', location: 'Warsaw, Poland', lastActive: '2025-07-13 21:50', current: true },
  { device: 'Safari (iPhone)', ip: '192.168.1.3', location: 'Krakow, Poland', lastActive: '2025-07-12 18:30', current: false },
]);
function logoutAll() {
  // TODO: handle logout all
}
function logoutSession() {
  // TODO: handle logout single session
}
</script>
