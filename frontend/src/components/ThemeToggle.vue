<template>
  <button
    @click="toggleTheme"
    class="p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
    :title="isDark ? $t('theme.switchToLight') : $t('theme.switchToDark')"
    :aria-label="isDark ? $t('theme.switchToLight') : $t('theme.switchToDark')"
  >
    <!-- Moon icon (dark mode) -->
    <svg
      v-if="isDark"
      class="w-5 h-5 text-yellow-300"
      fill="none"
      stroke="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
      ></path>
    </svg>
    
    <!-- Sun icon (light mode) -->
    <svg
      v-else
      class="w-5 h-5 text-gray-700"
      fill="none"
      stroke="currentColor"
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
      ></path>
    </svg>
  </button>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'ThemeToggle',
  setup() {
    const isDark = ref(false);

    // Check for saved user preference or use system preference
    const getThemePreference = () => {
      if (typeof window === 'undefined') return 'light';
      
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) return savedTheme;
      
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    // Apply theme class to the document
    const applyTheme = (theme) => {
      if (typeof document === 'undefined') return;
      
      const root = document.documentElement;
      
      if (theme === 'dark') {
        root.classList.add('dark');
        root.setAttribute('data-theme', 'dark');
      } else {
        root.classList.remove('dark');
        root.setAttribute('data-theme', 'light');
      }
      
      // Save preference to localStorage
      localStorage.setItem('theme', theme);
      isDark.value = theme === 'dark';
    };

    // Toggle between light and dark theme
    const toggleTheme = () => {
      const newTheme = isDark.value ? 'light' : 'dark';
      applyTheme(newTheme);
    };

    // Initialize theme on component mount
    onMounted(() => {
      const theme = getThemePreference();
      applyTheme(theme);
      
      // Watch for system theme changes
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
      const handleChange = (e) => {
        // Only apply system theme if no manual preference is set
        if (!localStorage.getItem('theme')) {
          applyTheme(e.matches ? 'dark' : 'light');
        }
      };
      
      // Add event listener for system theme changes
      if (mediaQuery.addEventListener) {
        mediaQuery.addEventListener('change', handleChange);
      } else {
        // For older Safari
        mediaQuery.addListener(handleChange);
      }
      
      // Cleanup
      return () => {
        if (mediaQuery.removeEventListener) {
          mediaQuery.removeEventListener('change', handleChange);
        } else {
          mediaQuery.removeListener(handleChange);
        }
      };
    });

    return {
      isDark,
      toggleTheme,
    };
  },
};
</script>

<style scoped>
/* Add any custom styles here */
button {
  transition: all 0.2s ease-in-out;
}

button:hover {
  transform: scale(1.1);
}

button:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
}
</style>
