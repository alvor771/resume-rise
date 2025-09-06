import { defineStore } from "pinia";
import { ref, computed } from "vue";
import i18n from "@/i18n";

export const useLanguageStore = defineStore("language", () => {
  // State
  const currentLanguage = ref(
    localStorage.getItem("language") || i18n.global.locale.value
  );
  const showLanguageMenu = ref(false);

  // Available languages
  const availableLanguages = [
    { code: "en", name: "English" },
    { code: "ru", name: "Русский" },
    { code: "pl", name: "Polski" },
  ];

  // Getters
  const currentLanguageName = computed(() => {
    const lang = availableLanguages.find(
      (lang) => lang.code === currentLanguage.value
    );
    return lang ? lang.name : "English";
  });

  // Actions
  function setLanguage(langCode) {
    const lang = availableLanguages.find((lang) => lang.code === langCode);
    if (lang) {
      i18n.global.locale.value = langCode;
      currentLanguage.value = langCode;
      localStorage.setItem("language", langCode);
      document.documentElement.lang = langCode;
      // No need to reload the page, i18n handles the change reactively
    }
  }

  function toggleLanguageMenu() {
    showLanguageMenu.value = !showLanguageMenu.value;
  }

  function closeLanguageMenu() {
    showLanguageMenu.value = false;
  }

  // Initialize language on load
  function initializeLanguage() {
    const savedLang = localStorage.getItem("language");
    if (savedLang) {
      setLanguage(savedLang);
    } else {
      // Set default language based on browser settings
      const browserLang = navigator.language.split("-")[0];
      const supportedLangs = availableLanguages.map((lang) => lang.code);
      if (supportedLangs.includes(browserLang)) {
        setLanguage(browserLang);
      }
    }
  }

  return {
    initializeLanguage,

    // State
    currentLanguage,
    showLanguageMenu,

    // Getters
    currentLanguageName,
    availableLanguages: computed(() => availableLanguages),

    // Actions
    setLanguage,
    toggleLanguageMenu,
    closeLanguageMenu,
  };
});
