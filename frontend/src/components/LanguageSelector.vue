<template>
  <div class="relative">
    <button
      @click.stop="toggleDropdown"
      class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
      :aria-expanded="isOpen"
      :aria-haspopup="true"
      :title="$t('language.change')"
    >
      <i class="fas fa-globe text-gray-700 dark:text-gray-300"></i>
      <span class="hidden sm:inline text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ currentLanguage.name }}
      </span>
      <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
    </button>

    <!-- Dropdown menu -->
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        v-click-outside="closeDropdown"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="language-selector"
        @click.stop
      >
        <button
          v-for="lang in availableLanguages"
          :key="lang.code"
          @click.stop="changeLanguage(lang.code)"
          class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="{ 'bg-gray-100 dark:bg-gray-700': isCurrentLanguage(lang.code) }"
          role="menuitem"
        >
          <span class="truncate">
            <i :class="lang.flag + ' mr-2'" aria-hidden="true"></i>
            {{ lang.name }}
          </span>
          <i v-if="isCurrentLanguage(lang.code)" class="fas fa-check ml-auto text-indigo-600 dark:text-indigo-400"></i>
        </button>
      </div>
    </transition>
  </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useI18n } from 'vue-i18n';

// Available languages configuration
const LANGUAGES = [
  { code: 'en', name: 'English', flag: 'fi fi-gb' },
  { code: 'fr', name: 'Français', flag: 'fi fi-fr' },
  { code: 'es', name: 'Español', flag: 'fi fi-es' },
  { code: 'de', name: 'Deutsch', flag: 'fi fi-de' },
  { code: 'it', name: 'Italiano', flag: 'fi fi-it' },
  { code: 'pt', name: 'Português', flag: 'fi fi-pt' },
  { code: 'pl', name: 'Polski', flag: 'fi fi-pl' },
  { code: 'ru', name: 'Русский', flag: 'fi fi-ru' },
  { code: 'zh', name: '中文', flag: 'fi fi-cn' },
  { code: 'ja', name: '日本語', flag: 'fi fi-jp' },
  { code: 'ar', name: 'العربية', flag: 'fi fi-sa', rtl: true },
];

export default {
  name: 'LanguageSelector',
  
  directives: {
    'click-outside': {
      beforeMount(el, binding) {
        el.clickOutsideEvent = function(event) {
          // Don't close if clicking on the language selector button
          const button = el.previousElementSibling;
          if (button && button.contains(event.target)) {
            return;
          }
          
          if (!(el === event.target || el.contains(event.target))) {
            binding.value();
          }
        };
        document.addEventListener('click', el.clickOutsideEvent);
      },
      unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
      },
    },
  },

  setup() {
    const { locale } = useI18n();
    const isOpen = ref(false);
    const savedLanguage = ref(localStorage.getItem('userLanguage') || 'en');

    // Set initial language
    if (!localStorage.getItem('userLanguage') && navigator.language) {
      const browserLang = navigator.language.split('-')[0];
      const supportedLang = LANGUAGES.find(lang => lang.code === browserLang);
      if (supportedLang) {
        savedLanguage.value = browserLang;
        locale.value = browserLang;
      }
    } else {
      locale.value = savedLanguage.value;
    }

    // Set document direction based on language
    const updateDocumentDirection = (langCode) => {
      const lang = LANGUAGES.find(l => l.code === langCode);
      if (lang && lang.rtl) {
        document.documentElement.dir = 'rtl';
      } else {
        document.documentElement.dir = 'ltr';
      }
    };

    const toggleDropdown = () => {
      isOpen.value = !isOpen.value;
    };

    const closeDropdown = () => {
      isOpen.value = false;
    };

    const changeLanguage = (langCode) => {
      locale.value = langCode;
      savedLanguage.value = langCode;
      localStorage.setItem('userLanguage', langCode);
      updateDocumentDirection(langCode);
      closeDropdown();
      
      // Dispatch event that language was changed
      window.dispatchEvent(new CustomEvent('language-changed', { detail: langCode }));
    };

    const isCurrentLanguage = (langCode) => {
      return locale.value === langCode;
    };

    // Update document direction when component mounts
    onMounted(() => {
      updateDocumentDirection(locale.value);
      
      // Listen for language changes from other components
      const handleLanguageChange = (event) => {
        if (event.detail && event.detail !== locale.value) {
          changeLanguage(event.detail);
        }
      };
      
      window.addEventListener('language-changed', handleLanguageChange);
      
      // Cleanup
      onBeforeUnmount(() => {
        window.removeEventListener('language-changed', handleLanguageChange);
      });
    });

    const currentLanguage = computed(() => {
      return LANGUAGES.find(lang => lang.code === locale.value) || LANGUAGES[0];
    });

    const availableLanguages = computed(() => {
      return LANGUAGES;
    });

    return {
      isOpen,
      currentLanguage,
      availableLanguages,
      toggleDropdown,
      closeDropdown,
      changeLanguage,
      isCurrentLanguage,
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
  transform: translateY(-1px);
}

button:focus {
  outline: none;
}

/* Flag icon styles */
.fi {
  border-radius: 2px;
  box-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
  width: 1.2em;
  height: 0.9em;
  vertical-align: middle;
  margin-top: -2px;
}

/* RTL support */
[dir="rtl"] .rtl\:mr-2 {
  margin-left: 0.5rem;
  margin-right: 0 !important;
}
</style>
