<template>
  <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50">
      <div class="relative w-full">
        <div class="relative z-10">
          <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
              <!-- Logo -->
              <router-link to="/" class="flex items-center space-x-2">
                <span class="font-bold text-indigo-600 dark:text-indigo-400">
                  <img 
                    src="@/assets/icons/cv-document.svg" 
                    alt="CV Document" 
                    class="w-12 h-auto"
                  />
                </span>
                <span class="font-bold text-indigo-600 dark:text-indigo-400">
                  {{ $t('app.name') }}
                </span>
              </router-link>
              
              <!-- Navigation -->
              <nav class="hidden md:flex space-x-8">
                <router-link 
                  v-for="link in navLinks" 
                  :key="link.path"
                  :to="link.path"
                  class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                  active-class="text-indigo-600 dark:text-indigo-400 font-medium"
                >
                  {{ $t(link.name) }}
                </router-link>
              </nav>
              
              <!-- Right Side Controls -->
              <div class="flex items-center space-x-4">
                <!-- Theme Toggle -->
                <button 
                  @click="toggleTheme"
                  class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                  :title="isDark ? $t('theme.light') : $t('theme.dark')"
                >
                  <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"></path>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                  </svg>
                </button>
                
                <!-- Language Selector -->
                <div class="relative">
                  <button 
                    @click.stop="toggleLanguageMenu"
                    class="flex items-center space-x-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700"
                    :title="$t('language.change')"
                  >
                    <span class="w-5 h-5 flex items-center justify-center text-sm font-medium">
                      {{ currentLanguage.toUpperCase() }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </button>
                  
                  <!-- Language Dropdown -->
                  <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                  >
                    <div 
                      v-if="showLanguageMenu" 
                      class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50"
                      v-click-outside="closeLanguageMenu"
                      @click.stop
                    >
                      <button
                        v-for="lang in availableLanguages"
                        :key="lang.code"
                        @click.stop="setLanguage(lang.code)"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center space-x-2"
                        :class="{ 'bg-gray-100 dark:bg-gray-700': currentLanguage === lang.code }"
                      >
                        <span class="w-4">{{ lang.flag }}</span>
                        <span>{{ lang.name }}</span>
                      </button>
                    </div>
                  </transition>
                </div>
                
                <!-- Authentication Buttons -->
                <div v-if="!$auth.isLoggedIn" class="hidden md:flex items-center space-x-2">
                  <router-link 
                    to="/login" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                  >
                    {{ $t('auth.login') }}
                  </router-link>
                  <router-link 
                    to="/register" 
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                  >
                    {{ $t('auth.register') }}
                  </router-link>
                </div>
                
                <!-- User Menu -->
                <div v-else class="relative">
                  <button 
                    @click="toggleUserMenu"
                    class="flex items-center space-x-2 focus:outline-none"
                  >
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                      <span class="text-indigo-600 dark:text-indigo-300 font-medium">
                        {{ $auth.user?.name?.charAt(0).toUpperCase() || 'U' }}
                      </span>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                      {{ $auth.user?.name || 'User' }}
                    </span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </button>
                  
                  <!-- Dropdown Menu -->
                  <div 
                    v-if="userMenuOpen" 
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50"
                    v-click-outside="closeUserMenu"
                  >
                    <router-link 
                      to="/dashboard" 
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                      @click="closeUserMenu"
                    >
                      {{ $t('nav.dashboard') }}
                    </router-link>
                    <router-link 
                      to="/profile" 
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                      @click="closeUserMenu"
                    >
                      {{ $t('nav.profile') }}
                    </router-link>
                    <button 
                      @click="logout"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      {{ $t('auth.logout') }}
                    </button>
                  </div>
                </div>
                
                <!-- Mobile menu button -->
                <button 
                  @click.stop="toggleMobileMenu" 
                  class="md:hidden p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none"
                  :aria-expanded="mobileMenuOpen"
                >
                  <span class="sr-only">Open main menu</span>
                  <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                  </svg>
                  <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    
    <!-- Mobile Menu -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div 
        v-if="mobileMenuOpen" 
        class="md:hidden fixed inset-x-0 top-16 bg-white dark:bg-gray-800 shadow-lg z-40"
        v-click-outside="closeMobileMenu"
        @click.stop
      >
        <div class="px-2 pt-2 pb-3 space-y-1">
          <router-link 
            v-for="link in navLinks" 
            :key="'mobile-' + link.path"
            :to="link.path"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700"
            @click="closeMobileMenu"
          >
            {{ $t(link.name) }}
          </router-link>
          
          <div v-if="!$auth.isLoggedIn" class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="mt-3 space-y-1">
              <router-link 
                to="/login" 
                class="block w-full px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400"
                @click="closeMobileMenu"
              >
                {{ $t('auth.login') }}
              </router-link>
              <router-link 
                to="/register" 
                class="block w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md text-center"
                @click="closeMobileMenu"
              >
                {{ $t('auth.register') }}
              </router-link>
            </div>
          </div>
          
          <div v-else class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-4 py-3">
              <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                <span class="text-indigo-600 dark:text-indigo-300 font-medium">
                  {{ $auth.user?.name?.charAt(0).toUpperCase() || 'U' }}
                </span>
              </div>
              <div class="ml-3">
                <div class="text-base font-medium text-gray-800 dark:text-white">
                  {{ $auth.user?.name || 'User' }}
                </div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  {{ $auth.user?.email || '' }}
                </div>
              </div>
            </div>
            <div class="mt-3 space-y-1">
              <router-link 
                to="/dashboard" 
                class="block px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                @click="closeMobileMenu"
              >
                {{ $t('nav.dashboard') }}
              </router-link>
              <router-link 
                to="/profile" 
                class="block px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                @click="closeMobileMenu"
              >
                {{ $t('nav.profile') }}
              </router-link>
              <button 
                @click="logout"
                class="block w-full text-left px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700"
              >
                {{ $t('auth.logout') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
    
    <!-- Main Content -->
    <main class="flex-1 pt-16">
      <slot></slot>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
      <div class="container mx-auto px-4 py-12">
        <div class="md:flex md:justify-between">
          <div class="mb-8 md:mb-0">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $t('app.name') }}</h3>
            <p class="text-gray-600 dark:text-gray-300 max-w-xs">{{ $t('app.description') }}</p>
          </div>
          
          <div class="grid grid-cols-2 gap-8 sm:grid-cols-3">
            <div>
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                {{ $t('footer.navigation') }}
              </h3>
              <ul class="space-y-2">
                <li v-for="link in navLinks" :key="'footer-' + link.path">
                  <router-link 
                    :to="link.path" 
                    class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm"
                    @click="mobileMenuOpen = false"
                  >
                    {{ $t(link.name) }}
                  </router-link>
                </li>
              </ul>
            </div>
            
            <div>
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                {{ $t('footer.legal') }}
              </h3>
              <ul class="space-y-2">
                <li>
                  <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm">
                    {{ $t('footer.privacy') }}
                  </a>
                </li>
                <li>
                  <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm">
                    {{ $t('footer.terms') }}
                  </a>
                </li>
                <li>
                  <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm">
                    {{ $t('footer.cookies') }}
                  </a>
                </li>
              </ul>
            </div>
            
            <div>
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">
                {{ $t('footer.contact') }}
              </h3>
              <ul class="space-y-2">
                <li>
                  <a 
                    href="mailto:support@resumerise.com" 
                    class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm"
                  >
                    support@resumerise.com
                  </a>
                </li>
                <li>
                  <a 
                    href="tel:+1234567890" 
                    class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm"
                  >
                    +1 (234) 567-890
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        
        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
          <p class="text-center text-gray-500 dark:text-gray-400 text-sm">
            &copy; {{ new Date().getFullYear() }} {{ $t('app.name') }}. {{ $t('footer.rights') }}.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRouter } from 'vue-router';
import { useThemeStore } from '@/stores/theme';
import { useLanguageStore } from '@/stores/language';
import { useAuthStore } from '@/stores/auth';

export default {
  name: 'MainLayout',
  setup() {
    const { t, locale } = useI18n();
    const router = useRouter();
    const themeStore = useThemeStore();
    const languageStore = useLanguageStore();
    const authStore = useAuthStore();
    
    const mobileMenuOpen = ref(false);
    const showLanguageMenu = ref(false);
    const userMenuOpen = ref(false);
    
    const navLinks = [
      { name: 'nav.home', path: '/' },
      { name: 'nav.resumeBuilder', path: '/resume-builder' },
      { name: 'nav.cvGenerator', path: '/cv-generator' },
      { name: 'nav.templateBuilder', path: '/template-builder' },
      { name: 'nav.pricing', path: '/pricing' },
      { name: 'nav.helpCenter', path: '/help-center' },
    ];
    
    // Available languages
    const availableLanguages = [
      { code: 'en', name: 'English', flag: '🇬🇧' },
      { code: 'ru', name: 'Русский', flag: '🇷🇺' },
      { code: 'pl', name: 'Polski', flag: '🇵🇱' }
    ];
    
    // Toggle mobile menu
    const toggleMobileMenu = () => {
      mobileMenuOpen.value = !mobileMenuOpen.value;
    };
    
    // Close mobile menu
    const closeMobileMenu = () => {
      mobileMenuOpen.value = false;
    };
    
    // Toggle language menu
    const toggleLanguageMenu = () => {
      showLanguageMenu.value = !showLanguageMenu.value;
    };
    
    // Close language menu
    const closeLanguageMenu = () => {
      showLanguageMenu.value = false;
    };
    
    // Set language
    const setLanguage = (lang) => {
      languageStore.setLanguage(lang);
      closeLanguageMenu();
    };
    
    // Toggle user menu
    const toggleUserMenu = () => {
      userMenuOpen.value = !userMenuOpen.value;
    };
    
    // Close user menu
    const closeUserMenu = () => {
      userMenuOpen.value = false;
    };
    
    // Logout
    const logout = async () => {
      try {
        await authStore.logout();
        closeUserMenu();
        await router.push('/');
      } catch (error) {
        console.error('Logout failed:', error);
      }
    };
    
    // Toggle theme
    const toggleTheme = () => {
      themeStore.toggleTheme();
    };
    
    // Initialize theme and language when component mounts
    onMounted(async () => {
      themeStore.initializeTheme();
      languageStore.initializeLanguage();
      
      // Set HTML lang attribute
      document.documentElement.lang = locale.value;
      
      // Check auth status
      await authStore.checkAuth();
    });
    
    // Watch for language changes
    watch(() => languageStore.currentLanguage, (newLang) => {
      locale.value = newLang;
      document.documentElement.lang = newLang;
    });
    
    // Computed properties
    const currentLanguage = computed(() => languageStore.currentLanguage);
    const isDark = computed(() => themeStore.isDark);
    
    return {
      // State
      mobileMenuOpen,
      showLanguageMenu,
      userMenuOpen,
      navLinks,
      availableLanguages,
      
      // Computed
      currentLanguage,
      isDark,
      
      // Methods
      t,
      toggleMobileMenu,
      closeMobileMenu,
      toggleLanguageMenu,
      closeLanguageMenu,
      setLanguage,
      toggleUserMenu,
      closeUserMenu,
      logout,
      toggleTheme,
      
      // Store
      $auth: authStore
    };
  }
};
</script>

<style scoped>
/* Эффект стекла с градиентной обводкой */
header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 50;
  background: linear-gradient(
    to right,
    rgba(255, 255, 255, 0.7) 0%,
    rgba(255, 255, 255, 0.4) 100%
  );
  background-image: url(@/assets/images/grid.svg) .1;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-bottom: 1px solid rgb(0, 247, 255);
  transition: all 0.3s ease;
}

.dark header {
  background: linear-gradient(
    to right,
    rgba(17, 24, 39, 0.8) 0%,
    rgba(17, 24, 39, 0.6) 100%
  );
  border-color: rgb(111, 0, 255);
}

/* Анимация блеска при наведении */
header::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  transition: 0.5s;
  pointer-events: none;
}

header:hover::before {
  left: 100%;
}

/* Стили для мобильного меню */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateY(-100%);
  opacity: 0;
}

/* Плавный скролл */
html {
  scroll-behavior: smooth;
}

/* Адаптация для мобильных устройств */
@media (max-width: 768px) {
  header {
    background: rgba(255, 255, 255, 0.95) !important;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
  }
  
  .dark header {
    background: rgba(17, 24, 39, 0.95) !important;
  }
}
</style>