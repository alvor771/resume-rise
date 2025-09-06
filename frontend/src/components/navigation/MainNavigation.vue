<template>
  <!-- Modern Glass Navigation -->
  <header class="fixed top-0 left-0 right-0 z-50 w-full">
    <!-- Animated Gradient Border -->
    <div
      class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent"
    >
      <div
        class="absolute -top-px left-0 w-full h-px bg-gradient-to-r from-transparent via-indigo-500 to-transparent animate-shimmer"
      ></div>
    </div>

    <!-- Glass Background with Backdrop Blur -->
    <div
      class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-white/10"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex-shrink-0">
            <router-link
              to="/"
              class="flex items-center space-x-3 group"
              aria-label="Home"
            >
              <!-- Logo Variations -->
              <div class="w-10 h-10 group">
                <svg
                  width="100%"
                  height="100%"
                  viewBox="0 0 100 100"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <rect width="100" height="100" fill="transparent" />
                  <g stroke-linecap="round" stroke-width="6" fill="none">
                    <path d="M20 20 H60 V60 H20 Z" stroke="#D88CFF">
                      <animate
                        attributeName="stroke-dasharray"
                        from="0, 160"
                        to="160, 0"
                        dur="1.2s"
                        fill="freeze"
                      />
                    </path>
                    <path d="M40 40 H80 V80 H40 Z" stroke="#9747FF">
                      <animate
                        attributeName="stroke-dasharray"
                        from="0, 160"
                        to="160, 0"
                        begin="0.6s"
                        dur="1.2s"
                        fill="freeze"
                      />
                    </path>
                    <path d="M20 20 H60 V60 H20 Z" stroke="#AAACFF">
                      <animate
                        attributeName="stroke-dasharray"
                        from="160, 0"
                        to="0, 160"
                        dur="1.2s"
                        fill="freeze"
                      />
                    </path>
                  </g>
                  <text
                    x="50%"
                    y="50%"
                    text-anchor="middle"
                    fill="white"
                    font-size="24"
                    font-weight="bold"
                  >
                    R
                  </text>
                </svg>
              </div>
              <span
                class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600"
                >ResumeRise</span
              >
            </router-link>
          </div>

          <!-- Desktop Navigation -->
          <nav class="hidden md:flex items-center h-full">
            <div v-if="loading" class="flex items-center px-4">
              <ArrowPathIcon class="h-5 w-5 animate-spin text-gray-400" />
              <span class="ml-2 text-sm text-gray-500">Loading menu...</span>
            </div>

            <div v-else-if="error" class="text-sm text-red-500 px-4">
              {{ error }}
            </div>

            <div v-else class="flex items-center h-full">
              <template
                v-for="item in formatMenuItems(menuItems)"
                :key="item.id"
              >
                <!-- Single Menu Item -->
                <router-link
                  v-if="
                    (!item.children || item.children.length === 0) &&
                    isMenuItemVisible(item)
                  "
                  :to="item.path || '#'"
                  class="relative h-full flex items-center px-4 text-sm font-medium transition-all duration-200 group"
                  :class="{
                    'text-indigo-600 dark:text-indigo-400':
                      isActive(item) && !item.highlight,
                    'text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400':
                      !isActive(item) && !item.highlight,
                    'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 mx-1 px-3 py-1.5 rounded-lg':
                      item.highlight,
                    'shadow-lg': item.highlight,
                    'transform hover:scale-105 transition-transform':
                      item.highlight,
                  }"
                >
                  <span class="relative z-10 flex items-center">
                    <i v-if="item.icon" :class="['mr-2', item.icon]"></i>
                    {{ item.title }}
                  </span>
                  <span
                    v-if="!item.highlight"
                    class="absolute -bottom-px left-1/2 h-px bg-indigo-500 transition-all duration-300 transform -translate-x-1/2 scale-x-0 group-hover:scale-x-100 w-full"
                    :class="{ 'scale-x-100': isActive(item) }"
                    style="transform-origin: center"
                  ></span>
                </router-link>

                <!-- Dropdown Menu -->
                <div
                  v-else-if="
                    item.children &&
                    item.children.length > 0 &&
                    isMenuItemVisible(item)
                  "
                  class="relative group h-full flex items-center"
                  @mouseenter="openDropdown = item.id"
                  @mouseleave="openDropdown = null"
                >
                  <button
                    class="flex items-center h-full px-4 text-sm font-medium transition-colors"
                    :class="{
                      'text-indigo-600 dark:text-indigo-400': isActive(item),
                      'text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400':
                        !isActive(item),
                    }"
                  >
                    <i
                      v-if="item.icon"
                      :class="[item.icon, 'mr-2 h-4 w-4 flex-shrink-0']"
                      aria-hidden="true"
                    />
                    <span class="flex items-center">
                      {{ item.title }}
                      <svg
                        class="ml-1 w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7"
                        ></path>
                      </svg>
                    </span>
                  </button>

                  <!-- Dropdown Panel -->
                  <transition
                    enter-active-class="transition duration-150 ease-out transform"
                    enter-from-class="opacity-0 -translate-y-1"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-1"
                  >
                    <div
                      v-show="openDropdown === item.id"
                      class="absolute top-full left-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 border border-gray-100 dark:border-gray-700 py-1"
                    >
                      <router-link
                        v-for="child in formatMenuItems(item.children).filter(
                          isMenuItemVisible
                        )"
                        :key="child.id"
                        :to="child.path || '#'"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                        :class="{
                          'text-indigo-600 dark:text-indigo-400':
                            isActive(child),
                        }"
                        @click="openDropdown = null"
                      >
                        <i
                          v-if="child.icon"
                          :class="[child.icon, 'mr-2 h-4 w-4 flex-shrink-0 opacity-70']"
                          aria-hidden="true"
                        />
                        <div class="flex items-center">
                          {{ child.title }}
                        </div>
                      </router-link>
                    </div>
                  </transition>
                </div>
              </template>
            </div>
          </nav>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button
              @click="toggleMobileMenu"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none"
              aria-label="Main menu"
            >
              <svg
                class="h-6 w-6"
                :class="{ hidden: mobileMenuOpen, block: !mobileMenuOpen }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
              <svg
                class="h-6 w-6"
                :class="{ block: mobileMenuOpen, hidden: !mobileMenuOpen }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          <!-- Right side controls -->
          <div class="hidden md:flex items-center space-x-4">
            <!-- Theme Toggle -->
            <div class="relative group">
              <ThemeToggle />
              <div
                class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"
              >
                {{ $t("common.toggleTheme") }}
              </div>
            </div>

            <!-- Language Selector -->
            <div class="relative group">
              <LanguageSelector />
              <div
                class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs bg-gray-800 text-white rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"
              >
                {{ $t("common.changeLanguage") }}
              </div>
            </div>

            <!-- User Menu -->
            <div v-if="authStore.isAuthenticated" class="relative">
              <button
                @click="toggleUserMenu"
                class="flex items-center space-x-2 focus:outline-none group"
                :class="{ 'ring-2 ring-indigo-500 rounded-full': userMenuOpen }"
              >
                <div
                  class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-medium"
                >
                  {{ authStore.user?.name?.charAt(0) || "U" }}
                </div>
              </button>

              <!-- User Dropdown -->
              <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <div
                  v-show="userMenuOpen"
                  class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none py-1 z-50"
                  role="menu"
                  aria-orientation="vertical"
                  tabindex="-1"
                >
                  <router-link
                    to="/profile"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                    role="menuitem"
                    tabindex="-1"
                    @click="closeUserMenu"
                  >
                    Your Profile
                  </router-link>
                  <router-link
                    to="/settings"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                    role="menuitem"
                    tabindex="-1"
                    @click="closeUserMenu"
                  >
                    Settings
                  </router-link>
                  <div
                    class="border-t border-gray-100 dark:border-gray-700 my-1"
                  ></div>
                  <button
                    @click="authStore.logout()"
                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-700"
                    role="menuitem"
                    tabindex="-1"
                  >
                    {{ $t("auth.logout") }}
                  </button>
                </div>
              </transition>
            </div>

            <!-- Auth Buttons -->
            <div v-else class="flex items-center space-x-2">
              <router-link
                to="/login"
                class="px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400"
              >
                {{ $t("auth.login") }}
              </router-link>
              <router-link
                to="/register"
                class="px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors"
              >
                {{ $t("auth.register") }}
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

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
        class="md:hidden bg-white dark:bg-gray-800 shadow-lg"
      >
        <div class="px-2 pt-2 pb-3 space-y-1">
          <template v-for="item in formatMenuItems(menuItems)" :key="item.id">
            <router-link
              v-if="!item.children || item.children.length === 0"
              :to="item.path || '#'"
              class="flex items-center px-3 py-2 rounded-md text-base font-medium"
              :class="{
                'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300':
                  isActive(item),
                'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700':
                  !isActive(item),
              }"
              @click="mobileMenuOpen = false"
            >
              <i
                v-if="item.icon"
                :class="[item.icon, 'mr-3 h-5 w-5 flex-shrink-0']"
                aria-hidden="true"
              />
              <span>{{ item.title }}</span>
            </router-link>

            <div v-else>
              <button
                @click="toggleMobileSubmenu(item.id)"
                class="w-full flex justify-between items-center px-3 py-2 rounded-md text-base font-medium"
                :class="{
                  'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300':
                    isActive(item),
                  'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700':
                    !isActive(item),
                }"
              >
                <div class="flex items-center">
                  <i
                    v-if="item.icon"
                    :class="[item.icon, 'mr-3 h-5 w-5 flex-shrink-0']"
                    aria-hidden="true"
                  />
                  <span>{{ item.title }}</span>
                </div>
                <svg
                  class="ml-2 h-4 w-4 transition-transform duration-200"
                  :class="{
                    'rotate-180': openMobileSubmenu === item.id,
                  }"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  ></path>
                </svg>
              </button>

              <transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0 max-h-0"
                enter-to-class="opacity-100 max-h-32"
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100 max-h-32"
                leave-to-class="opacity-0 max-h-0"
              >
                <div
                  v-show="openMobileSubmenu === item.id"
                  class="pl-4 space-y-1 overflow-hidden"
                >
                  <router-link
                    v-for="child in item.children"
                    :key="child.id"
                    :to="child.path || '#'"
                    class="flex items-center pl-12 pr-3 py-2 text-sm font-medium"
                    :class="{
                      'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300':
                        isActive(child),
                      'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700':
                        !isActive(child),
                    }"
                    @click="mobileMenuOpen = false"
                  >
                    <i
                      v-if="child.icon"
                      :class="[child.icon, 'mr-3 h-4 w-4 flex-shrink-0 opacity-70']"
                      aria-hidden="true"
                    />
                    <span>{{ child.title }}</span>
                  </router-link>
                </div>
              </transition>
            </div>
          </template>

          <!-- Mobile Auth Buttons -->
          <div
            v-if="!authStore.isAuthenticated"
            class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-700 px-3 space-y-3"
          >
            <router-link
              to="/login"
              class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700"
              @click="mobileMenuOpen = false"
            >
              {{ $t("auth.login") }}
            </router-link>
            <router-link
              to="/register"
              class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
              @click="mobileMenuOpen = false"
            >
              {{ $t("auth.register") }}
            </router-link>
          </div>

          <!-- Mobile User Menu -->
          <div
            v-else
            class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700 px-4 space-y-3"
          >
            <div class="flex items-center">
              <div
                class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-medium"
              >
                {{ authStore.user?.name?.charAt(0) || "U" }}
              </div>
              <div class="ml-3">
                <div
                  class="text-base font-medium text-gray-800 dark:text-gray-200"
                >
                  {{ authStore.user?.name || "User" }}
                </div>
                <div
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  {{ authStore.user?.email || "" }}
                </div>
              </div>
            </div>
            <div class="space-y-1">
              <router-link
                to="/profile"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                @click="mobileMenuOpen = false"
              >
                {{ $t("auth.profile") }}
              </router-link>
              <router-link
                to="/settings"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                @click="mobileMenuOpen = false"
              >
                {{ $t("auth.settings") }}
              </router-link>
              <button
                @click="
                  authStore.logout();
                  mobileMenuOpen = false;
                "
                class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-700"
              >
                {{ $t("auth.logout") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script>
import { ref, onMounted, computed, watch } from "vue";
import { useThemeStore } from "@/stores/theme";
import { useAuthStore } from "@/stores/auth";
import { useMenuStore } from "@/stores/menu";
import ThemeToggle from "@/components/ThemeToggle.vue";
import LanguageSelector from "@/components/LanguageSelector.vue";
import { useRoute } from "vue-router";
import { ArrowPathIcon } from "@heroicons/vue/24/outline";
import { useI18n } from "vue-i18n";

export default {
  name: "MainNavigation",
  components: {
    ThemeToggle,
    LanguageSelector,
    ArrowPathIcon,
  },
  setup() {
    const route = useRoute();
    const themeStore = useThemeStore();
    const authStore = useAuthStore();
    const menuStore = useMenuStore();
    const { t } = useI18n();

    const mobileMenuOpen = ref(false);
    const userMenuOpen = ref(false);
    const openDropdown = ref(null);
    const openMobileSubmenu = ref(null);
    const loading = ref(true);
    const error = ref(null);

    const isDark = computed(() => themeStore.isDark);
    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const userPermissions = computed(() => authStore.user?.permissions || []);

    // Get filtered menu items based on authentication and permissions
    const menuItems = computed(() => {
      const items = menuStore.getFilteredMenuItems(
        isAuthenticated.value,
        userPermissions.value
      );
      return Array.isArray(items) ? items : [];
    });

    // Fetch menu items on component mount and when authentication state changes
    const fetchMenu = async () => {
      loading.value = true;
      error.value = null;
      try {
        await menuStore.fetchMenuItems();
      } catch (err) {
        console.error("Failed to load menu items:", err);
        error.value = "Failed to load navigation menu";
      } finally {
        loading.value = false;
      }
    };

    onMounted(fetchMenu);

    // Watch for authentication changes to refetch menu items
    watch(isAuthenticated, () => {
      fetchMenu();
    });

    const toggleMobileMenu = () => {
      mobileMenuOpen.value = !mobileMenuOpen.value;
      if (!mobileMenuOpen.value) {
        openMobileSubmenu.value = null;
      }
    };

    const toggleMobileSubmenu = (id) => {
      openMobileSubmenu.value = openMobileSubmenu.value === id ? null : id;
    };

    const toggleUserMenu = () => {
      userMenuOpen.value = !userMenuOpen.value;
    };

    const closeUserMenu = () => {
      userMenuOpen.value = false;
    };

    const isActive = (item) => {
      if (!item.path) return false;
      if (item.path === "/") {
        return route.path === "/";
      }
      return route.path.startsWith(item.path);
    };

    // Close mobile menu when route changes
    watch(
      () => route.path,
      () => {
        mobileMenuOpen.value = false;
        openMobileSubmenu.value = null;
      }
    );

    // Format menu items for display
    const formatMenuItems = (items) => {
      if (!items) return [];
      return items
        .filter((item) => item.is_visible !== false) // Filter out hidden items
        .map((item) => ({
          ...item,
          title: item.translation_key
            ? t(`menu.${item.translation_key}`)
            : item.title,
          children: item.children
            ? item.children
                .filter((child) => child.is_visible !== false)
                .map((child) => ({
                  ...child,
                  title: child.translation_key
                    ? t(`menu.${child.translation_key}`)
                    : child.title,
                }))
                .sort((a, b) => (a.order || 0) - (b.order || 0))
            : [],
        }))
        .sort((a, b) => (a.order || 0) - (b.order || 0)); // Sort by order
    };

    // Check if a menu item should be visible based on auth status
    const isMenuItemVisible = (item) => {
      if (item.is_auth && !isAuthenticated.value) {
        console.log("Hiding item - requires auth:", item.title);
        return false;
      }
      if (item.is_guest && isAuthenticated.value) {
        console.log("Hiding item - guest only:", item.title);
        return false;
      }
      if (item.permissions && item.permissions.length > 0) {
        const hasPermission = item.permissions.some((perm) =>
          userPermissions.value.includes(perm)
        );
        if (!hasPermission) {
          console.log("Hiding item - missing permission:", item.title);
          return false;
        }
      }
      return true;
    };

    return {
      isDark,
      menuItems: computed(() => formatMenuItems(menuItems.value)),
      loading,
      error,
      mobileMenuOpen,
      userMenuOpen,
      openDropdown,
      openMobileSubmenu,
      authStore,
      isMenuItemVisible,
      toggleMobileMenu,
      toggleMobileSubmenu,
      toggleUserMenu,
      closeUserMenu,
      isActive,
      formatMenuItems,
    };
  },
};
</script>

<style scoped>
.animate-shimmer {
  background-size: 200% 100%;
  animation: shimmer 2s infinite linear;
}

@keyframes shimmer {
  0% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

/* Smooth transitions for mobile menu */
.menu-enter-active,
.menu-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.menu-enter-from,
.menu-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
