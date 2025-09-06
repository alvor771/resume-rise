<template>
  <div class="relative group/theme">
    <button 
      @click="toggleTheme"
      class="p-2.5 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm hover:shadow-md transition-all duration-300 group-hover/theme:scale-105 hover:bg-white dark:hover:bg-gray-800 border border-gray-200 dark:border-gray-700"
      :title="isDark ? $t('theme.light') : $t('theme.dark')"
      aria-label="Toggle theme"
    >
      <div class="relative w-5 h-5 flex items-center justify-center overflow-visible">
        <!-- Sun -->
        <svg 
          class="w-5 h-5 text-amber-500 transition-all duration-500 ease-in-out absolute" 
          :class="{ 'opacity-0 rotate-90 scale-0': isDark, 'opacity-100 rotate-0 scale-100': !isDark }"
          fill="none" 
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="1.5"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
        </svg>
        
        <!-- Moon -->
        <svg 
          class="w-5 h-5 text-indigo-400 transition-all duration-500 ease-in-out absolute" 
          :class="{ 'opacity-0 -rotate-90 scale-0': !isDark, 'opacity-100 rotate-0 scale-100': isDark }"
          fill="none" 
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="1.5"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
        </svg>
        
        <!-- Glow effect -->
        <span 
          class="absolute inset-0 rounded-full bg-amber-100 dark:bg-indigo-500/20 opacity-0 group-hover/theme:opacity-100 transition-opacity duration-300"
          :class="{ 'group-hover/theme:animate-ping-slow': !isDark }"
        ></span>
      </div>
    </button>
    
    <!-- Tooltip -->
    <div 
      class="absolute -bottom-9 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/theme:opacity-100 transition-all duration-200 whitespace-nowrap pointer-events-none shadow-lg"
      :class="{ 'bg-gray-900': !isDark, 'bg-white text-gray-900': isDark }"
    >
      {{ isDark ? $t('theme.light') : $t('theme.dark') }}
      <svg 
        class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-1.5 text-gray-900 dark:text-white" 
        :class="{ 'text-gray-900': !isDark, 'text-white': isDark }"
        viewBox="0 0 12 6"
      >
        <path fill="currentColor" d="M6 0L0 6h12L6 0z"/>
      </svg>
    </div>
  </div>
</template>

<style scoped>
@keyframes ping-slow {
  0% {
    transform: scale(0.9);
    opacity: 0.7;
  }
  70% {
    transform: scale(1.2);
    opacity: 0;
  }
  100% {
    transform: scale(1.2);
    opacity: 0;
  }
}

.animate-ping-slow {
  animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>

<script setup>
import { computed } from 'vue';
import { useThemeStore } from '@/stores/theme';

const themeStore = useThemeStore();
const isDark = computed(() => themeStore.isDark);

const toggleTheme = () => {
  themeStore.toggleTheme();
};
</script>