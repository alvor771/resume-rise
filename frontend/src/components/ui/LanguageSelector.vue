<template>
  <div class="relative group/language">
    <button 
      @click.stop="toggleMenu"
      class="flex items-center space-x-1 p-2 rounded-full bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 shadow-sm hover:shadow-md transition-all duration-300 group-hover/language:scale-105"
      :title="$t('language.change')"
    >
      <div class="relative w-5 h-5 flex items-center justify-center">
        <div class="absolute inset-0 rounded-full border-2 border-indigo-100 dark:border-indigo-900 group-hover/language:border-indigo-200 dark:group-hover/language:border-indigo-800 transition-colors"></div>
        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
          {{ currentLanguage.toUpperCase() }}
        </span>
      </div>
      <svg class="w-3 h-3 text-gray-500 dark:text-gray-400 group-hover/language:text-indigo-500 dark:group-hover/language:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </button>
    
    <!-- Animated Language Dropdown -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform opacity-0 translate-y-1"
      enter-to-class="transform opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform opacity-100 translate-y-0"
      leave-to-class="transform opacity-0 translate-y-1"
    >
      <div 
        v-if="isOpen" 
        v-click-outside="closeMenu"
        class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden z-50 border border-gray-100 dark:border-gray-700"
        @click.stop
      >
        <div class="px-2 py-1.5 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700">
          <div class="text-xs font-medium text-indigo-600 dark:text-indigo-400 px-2 py-1">
            {{ $t('language.select') }}
          </div>
        </div>
        <div class="py-1">
          <button
            v-for="lang in availableLanguages"
            :key="lang.code"
            @click.stop="selectLanguage(lang.code)"
            class="w-full px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700/50 flex items-center space-x-3 group/item transition-colors duration-200"
            :class="{ 'bg-indigo-50 dark:bg-gray-700/70': currentLanguage === lang.code }"
          >
            <span class="text-base">{{ lang.flag }}</span>
            <span class="flex-1 text-left">{{ lang.name }}</span>
            <span 
              class="w-1.5 h-1.5 rounded-full bg-indigo-500 opacity-0 group-hover/item:opacity-100 transition-opacity duration-200"
              :class="{ 'opacity-100': currentLanguage === lang.code }"
            ></span>
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();
const isOpen = ref(false);

const availableLanguages = [
  { code: 'en', name: 'English', flag: '🇬🇧' },
  { code: 'ru', name: 'Русский', flag: '🇷🇺' },
  { code: 'pl', name: 'Polski', flag: '🇵🇱' },
];

const currentLanguage = computed(() => locale.value);

const toggleMenu = () => {
  isOpen.value = !isOpen.value;
};

const closeMenu = () => {
  isOpen.value = false;
};

const selectLanguage = (langCode) => {
  locale.value = langCode;
  localStorage.setItem('userLanguage', langCode);
  closeMenu();
};
</script>
