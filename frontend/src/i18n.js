import { createI18n } from "vue-i18n";
// Import base translations
import en from "./locales/en.json";
import ru from "./locales/ru.json";
import pl from "./locales/pl.json";

// Import admin translations
import adminEn from "./locales/admin/en.json";
import adminRu from "./locales/admin/ru.json";
import adminPl from "./locales/admin/pl.json";

// Import module translations
import resumeGenerator from "@/modules/resume-generator/locales";
import cvSwapTranslations from "@/modules/cv-swap/locales";
// Merge translations
const messages = {
  en: {
    ...en,
    admin: adminEn,
    resume: resumeGenerator.en,
    cvSwap: cvSwapTranslations.en.cvSwap,
  },
  ru: {
    ...ru,
    admin: adminRu,
    resume: resumeGenerator.ru,
    cvSwap: cvSwapTranslations.ru.cvSwap,
  },
  pl: {
    ...pl,
    admin: adminPl,
    resume: resumeGenerator.pl,
    cvSwap: cvSwapTranslations.pl.cvSwap, // Fallback to English for Polish
  },
};

// Get the saved language from localStorage or use browser language
const savedLanguage = localStorage.getItem("language");
const browserLanguage = navigator.language.split("-")[0];
const defaultLanguage = ["en", "ru", "pl"].includes(browserLanguage)
  ? browserLanguage
  : "en";

const i18n = createI18n({
  legacy: false,
  locale: savedLanguage || defaultLanguage,
  fallbackLocale: "en",
  messages,
  // Enable auto locale detection
  sync: true,
  // Enable locale fallback
  silentFallbackWarn: true,
  // Warn when translation is missing
  missingWarn: false,
  // Enable pluralization
  pluralizationRules: {
    ru: function (choice, choicesLength) {
      if (choice === 0) {
        return 0;
      }

      const teen = choice > 10 && choice < 20;
      const endsWithOne = choice % 10 === 1;

      if (!teen && endsWithOne) {
        return 1;
      }

      if (!teen && choice % 10 >= 2 && choice % 10 <= 4) {
        return 2;
      }

      return choicesLength < 4 ? 2 : 3;
    },
    // Add similar rules for Polish if needed
  },
});

export default i18n;
