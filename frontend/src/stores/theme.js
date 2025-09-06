import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useThemeStore = defineStore('theme', () => {
  // State
  const darkMode = ref(false);
  
  // Initialize theme from localStorage or system preference
  const initializeTheme = () => {
    const savedTheme = localStorage.getItem('darkMode');
    if (savedTheme !== null) {
      darkMode.value = savedTheme === 'true';
    } else {
      darkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    updateDocumentClass();
  };
  
  // Update document class based on theme
  const updateDocumentClass = () => {
    if (darkMode.value) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  };

  // Getters
  const isDark = computed(() => darkMode.value);

  // Actions
  function toggleTheme() {
    darkMode.value = !darkMode.value;
    localStorage.setItem('darkMode', darkMode.value);
    updateDocumentClass();
  }

  // Initialize theme when store is created
  initializeTheme();

  return {
    // State
    darkMode,
    
    // Getters
    isDark,
    
    // Actions
    toggleTheme,
    initializeTheme
  };
});