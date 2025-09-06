import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import i18n from './i18n';
import './assets/tailwind.css';

// Import modules
import resumeGenerator from '@/modules/resume-generator';
import cvSwapModule from '@/modules/cv-swap';
import dashboardModule from '@/modules/dashboard';

// Import debug utilities in development
if (process.env.NODE_ENV === 'development') {
  import('@/utils/debugAuth').catch(console.error);
}

// Create the app
const app = createApp(App);
const pinia = createPinia();

// Initialize language store before other plugins
import { useLanguageStore } from './stores/language';
const languageStore = useLanguageStore(pinia);
languageStore.initializeLanguage();

// Use Pinia before other plugins
app.use(pinia);

// Use router and i18n
app.use(router);
app.use(i18n);

// Register modules
app.use(resumeGenerator);
app.use(cvSwapModule);
app.use(dashboardModule);

// Directives
app.directive('click-outside', {
  beforeMount(el, binding) {
    el.clickOutsideEvent = function(event) {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
});

app.mount('#app');
