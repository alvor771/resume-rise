<template>
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200 w-full">
    <!-- Main Navigation Component -->
    <MainNavigation :nav-links="navLinks" />
    
    <!-- Main Content -->
    <main class="flex-1 pt-16 w-full">
      <!-- Dynamic content will be rendered here -->
      <router-view v-slot="{ Component }">
        <transition
          enter-active-class="transition-opacity duration-200"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition-opacity duration-200"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <component :is="Component" class="w-full" />
        </transition>
      </router-view>
    </main>
    
    <!-- Wave Divider with Gradient -->
    <div class="relative h-12 -mb-px overflow-hidden">
      <svg class="absolute top-0 w-full h-12" viewBox="0 0 1200 120" preserveAspectRatio="none" style="transform: rotate(180deg);">
        <defs>
          <linearGradient id="waveGradient" x1="100%" y1="0%" x2="0%" y2="0%">
            <stop offset="0%" stop-color="#4f46e5" />
            <stop offset="100%" stop-color="#9333ea" />
          </linearGradient>
          <linearGradient id="waveGradientDark" x1="100%" y1="0%" x2="0%" y2="0%">
            <stop offset="0%" stop-color="#3730a3" />
            <stop offset="100%" stop-color="#6b21a8" />
          </linearGradient>
        </defs>
        <path 
          d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" 
          class="fill-[url(#waveGradient)] dark:fill-[url(#waveGradientDark)]"
        ></path>
      </svg>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 text-white pt-12 pb-8 relative">
      <!-- Decorative elements -->
      <div class="absolute inset-0 bg-grid-white/5 [mask-image:linear-gradient(to_bottom,transparent,white,transparent)]"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main footer content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          <!-- Logo and description -->
          <div class="space-y-4">
            <router-link to="/" class="flex items-center space-x-2 group" aria-label="Home">
              <div class="relative">
                <!-- Logo container with gradient border -->
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-0.5">
                  <div class="w-full h-full rounded-lg bg-white/5 backdrop-blur-sm flex items-center justify-center">
                    <!-- Document icon with gradient fill -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                      <path d="M9 12H15M9 8H15M9 16H12M5 21H19C19.5523 21 20 20.5523 20 20V6.8C20 6.32261 19.7893 5.86477 19.4142 5.53483L14.5858 1.33483C14.2107 1.00489 13.702 0.839905 13.2 0.839905L6 0.839844C5.44772 0.839844 5 1.28756 5 1.83984V21Z" stroke="url(#logoGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      <defs>
                        <linearGradient id="logoGradient" x1="5" y1="1" x2="20" y2="21" gradientUnits="userSpaceOnUse">
                          <stop stop-color="#4F46E5"/>
                          <stop offset="1" stop-color="#7C3AED"/>
                        </linearGradient>
                      </defs>
                    </svg>
                  </div>
                </div>
                <!-- Animated pulse dot -->
                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-white/10 dark:border-gray-900/10 animate-pulse"></span>
              </div>
              
              <!-- App name with gradient text -->
              <div class="flex flex-col">
                <span class="text-xl font-bold bg-gradient-to-r from-white to-white/90 bg-clip-text text-transparent">
                  {{ $t('app.name') }}
                </span>
                <span class="text-[10px] font-medium text-white/70 -mt-1">
                  {{ $t('app.tagline') }}
                </span>
              </div>
            </router-link>
            <p class="text-indigo-100 text-sm leading-relaxed">
              {{ $t('app.tagline') }}
            </p>
            <div class="flex space-x-4 pt-2">
              <a href="#" class="text-white/80 hover:text-white transition-colors">
                <span class="sr-only">Twitter</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                </svg>
              </a>
              <a href="#" class="text-white/80 hover:text-white transition-colors">
                <span class="sr-only">GitHub</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                </svg>
              </a>
              <a href="#" class="text-white/80 hover:text-white transition-colors">
                <span class="sr-only">LinkedIn</span>
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="text-sm font-semibold text-white/90 uppercase tracking-wider mb-4">
              {{ $t('footer.quickLinks') }}
            </h3>
            <ul class="space-y-2">
              <li v-for="link in footerLinks" :key="link.path">
                <router-link 
                  :to="link.path"
                  class="text-indigo-100 hover:text-white transition-colors text-sm"
                >
                  {{ $t(link.name) }}
                </router-link>
              </li>
            </ul>
          </div>

          <!-- Resources -->
          <div>
            <h3 class="text-sm font-semibold text-white/90 uppercase tracking-wider mb-4">
              {{ $t('footer.resources') }}
            </h3>
            <ul class="space-y-2">
              <li>
                <a href="#" class="text-indigo-100 hover:text-white transition-colors text-sm">
                  {{ $t('footer.blog') }}
                </a>
              </li>
              <li>
                <a href="#" class="text-indigo-100 hover:text-white transition-colors text-sm">
                  {{ $t('footer.documentation') }}
                </a>
              </li>
              <li>
                <a href="#" class="text-indigo-100 hover:text-white transition-colors text-sm">
                  {{ $t('footer.guides') }}
                </a>
              </li>
              <li>
                <a href="#" class="text-indigo-100 hover:text-white transition-colors text-sm">
                  {{ $t('footer.apiStatus') }}
                </a>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="text-sm font-semibold text-white/90 uppercase tracking-wider mb-4">
              {{ $t('footer.contact') }}
            </h3>
            <ul class="space-y-2">
              <li class="flex items-start">
                <svg class="h-5 w-5 text-indigo-200 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <a href="mailto:support@resumerise.com" class="text-indigo-100 hover:text-white transition-colors text-sm">
                  support@resumerise.com
                </a>
              </li>
              <li class="flex items-start">
                <svg class="h-5 w-5 text-indigo-200 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-indigo-100 text-sm">
                  123 Resume Street<br>
                  San Francisco, CA 94107
                </span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Bottom bar -->
        <div class="border-t border-indigo-500/30 pt-8">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-indigo-100 text-sm">
              {{ $t('footer.copyright', { year: new Date().getFullYear() }) }}
            </p>
            
            <div class="flex items-center space-x-2 mt-4 md:mt-0">
              <span class="text-indigo-200 text-sm">{{ $t('footer.language') }}:</span>
              <div class="flex space-x-1">
                <button 
                  v-for="locale in availableLocales" 
                  :key="locale.code"
                  @click="changeLocale(locale.code)"
                  class="px-2 py-1 text-xs rounded-md transition-colors"
                  :class="{
                    'bg-white/20 text-white': $i18n.locale === locale.code,
                    'text-indigo-100 hover:bg-white/10': $i18n.locale !== locale.code
                  }"
                >
                  {{ locale.name }}
                </button>
              </div>
            </div>
            
            <div class="flex items-center space-x-6 mt-4 md:mt-0">
              <a href="#" class="text-indigo-100 hover:text-white text-sm transition-colors">
                {{ $t('footer.privacy') }}
              </a>
              <a href="#" class="text-indigo-100 hover:text-white text-sm transition-colors">
                {{ $t('footer.terms') }}
              </a>
              <a href="#" class="text-indigo-100 hover:text-white text-sm transition-colors">
                {{ $t('footer.cookies') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useThemeStore } from '@/stores/theme';
import { useLanguageStore } from '@/stores/language';
import MainNavigation from '@/components/navigation/MainNavigation.vue';

export default {
  name: 'MainLayout',
  components: {
    MainNavigation
  },
  setup() {
    const { locale } = useI18n();
    const themeStore = useThemeStore();
    const languageStore = useLanguageStore();
    
    const footerLinks = [
      { name: 'footer.terms', path: '/terms' },
      { name: 'footer.privacy', path: '/privacy' },
      { name: 'footer.cookies', path: '/cookies' },
      { name: 'footer.contact', path: '/contact' },
    ];
    
    const navLinks = [
      { name: 'nav.home', path: '/' },
      { name: 'nav.resumeBuilder', path: '/resume-builder' },
      { name: 'nav.cvGenerator', path: '/cv-generator' },
      { name: 'nav.templateBuilder', path: '/template-builder' },
      { name: 'nav.pricing', path: '/pricing' },
      { name: 'nav.helpCenter', path: '/help-center' },
    ];
    
    // Initialize theme and language when component mounts
    onMounted(() => {
      themeStore.initializeTheme();
      languageStore.initializeLanguage();
      
      // Set HTML lang attribute
      document.documentElement.lang = locale.value;
    });
    
    // Watch for language changes
    watch(() => languageStore.currentLanguage, (newLang) => {
      locale.value = newLang;
      document.documentElement.lang = newLang;
    });
    
    return {
      footerLinks,
      navLinks
    };
  }
};
</script>

<style scoped>
/* Animation for floating elements */
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-4px); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Smooth scrolling for the entire app */
:global(html) {
  scroll-behavior: smooth;
}
</style>