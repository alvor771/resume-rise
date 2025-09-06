<template>
  <div class="h-screen bg-gray-50 dark:bg-gray-900 flex overflow-hidden">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0 h-full">
      <div
        class="flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full"
      >
        <!-- Logo -->
        <div
          class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-700 p-4"
        >
          <router-link to="/admin" class="flex items-center space-x-2">
            <span
              class="text-xl font-bold text-indigo-600 dark:text-indigo-400"
            >
              ResumeRise
            </span>
          </router-link>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
          <div v-for="(item, index) in navigation" :key="item.path || item.name">
                <!-- Menu item with sub-items -->
                <div v-if="item.children && item.children.length > 0">
              <button
                @click="toggleSubMenu(index)"
                class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-200"
                :class="{
                  'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-white': 
                    isActiveParent(item),
                  'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': 
                    !isActiveParent(item)
                }"
              >
                <div class="flex items-center">
                  <component
                    :is="item.icon"
                    class="mr-3 h-5 w-5 flex-shrink-0"
                    :class="{
                      'text-indigo-500': isActiveParent(item),
                      'text-gray-400 group-hover:text-gray-500 dark:text-gray-400': 
                        !isActiveParent(item)
                    }"
                  />
                  <span>{{ $t(`admin.nav.${item.name}`, item.name) }}</span>
                </div>
                <svg
                  class="h-4 w-4 transform transition-transform duration-200"
                  :class="{
                    'rotate-180': isSubMenuOpen(index),
                    'text-indigo-500': isActiveParent(item)
                  }"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              
              <!-- Sub-menu items -->
              <div 
                v-show="isSubMenuOpen(index)"
                class="mt-1 ml-8 space-y-1 overflow-hidden transition-all duration-200 ease-in-out"
              >
                <router-link
                  v-for="child in item.children"
                  :key="child.path"
                  :to="child.path"
                  class="group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150"
                  :class="{
                    'bg-indigo-100 text-indigo-700 dark:bg-gray-600 dark:text-white': 
                      $route.path === child.path,
                    'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600': 
                      $route.path !== child.path
                  }"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mr-3"></span>
                  {{ $t(`admin.nav.${child.name}`, child.name) }}
                  <span 
                    v-if="child.badge"
                    class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="{
                      'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100': 
                        $route.path === child.href,
                      'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200': 
                        $route.path !== child.href
                    }"
                  >
                    {{ child.badge }}
                  </span>
                </router-link>
              </div>
            </div>
            
            <!-- Regular menu item -->
            <router-link
              v-else
              :to="item.path"
              class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-200"
              :class="{
                'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-white':
                  $route.path.startsWith(item.path),
                'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700':
                  !$route.path.startsWith(item.path),
              }"
            >
              <component
                :is="item.icon"
                class="mr-3 h-5 w-5 flex-shrink-0"
                :class="{
                  'text-indigo-500': $route.path.startsWith(item.path),
                  'text-gray-400 group-hover:text-gray-500 dark:text-gray-400':
                    !$route.path.startsWith(item.path),
                }"
              />
              <span>{{ $t(`admin.nav.${item.name}`, item.name) }}</span>
              <span 
                v-if="item.badge"
                class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="{
                  'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100': 
                    $route.path.startsWith(item.path) || $route.path.startsWith(item.href || ''),
                  'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200': 
                    !$route.path.startsWith(item.path) && !$route.path.startsWith(item.href || '')
                }"
              >
                {{ item.badge }}
              </span>
            </router-link>
          </div>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img
                :src="
                  user.avatar ||
                  'https://placehold.co/100x100?text=' + userInitials
                "
                :alt="user.name"
                class="h-10 w-10 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-700"
              />
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-700 dark:text-white">
                {{ user.name }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ user.email }}
              </p>
            </div>
          </div>
          <div class="mt-3 space-y-1">
            <router-link
              to="/admin/profile"
              class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-md"
            >
              {{ $t("admin.profile") }}
            </router-link>
            <a
              href="#"
              class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-md"
              @click.prevent="logout"
            >
              {{ $t("auth.logout") }}
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden relative h-full">
      <!-- Top Navigation -->
      <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div
          class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8"
        >
          <!-- Mobile menu button -->
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <span class="sr-only">Open sidebar</span>
            <svg
              class="h-6 w-6"
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
          </button>

          <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ $route.meta.title ? $t($route.meta.title) : "Admin" }}
          </h1>

          <div class="flex items-center space-x-2">
            <!-- Language Switcher -->
            <div class="relative">
              <button
                @click.stop="toggleLanguageMenu"
                class="language-button flex items-center p-1.5 text-sm rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                :title="$t('common.language')"
              >
                <svg
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                  />
                </svg>
                <span class="ml-1">{{
                  languageStore.currentLanguage.toUpperCase()
                }}</span>
                <svg
                  class="ml-1 h-4 w-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>

              <!-- Language Dropdown -->
              <div
                v-if="languageStore.showLanguageMenu"
                class="language-menu absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                role="menu"
                aria-orientation="vertical"
                tabindex="-1"
              >
                <div class="py-1" role="none">
                  <button
                    v-for="lang in languageStore.availableLanguages"
                    :key="lang.code"
                    @click="setLanguage(lang.code)"
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                    :class="{
                      'bg-gray-100 dark:bg-gray-700':
                        languageStore.currentLanguage === lang.code,
                    }"
                    role="menuitem"
                    tabindex="-1"
                  >
                    <span class="w-6">{{ lang.code.toUpperCase() }}</span>
                    <span class="ml-2">{{ lang.name }}</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Theme Toggle -->
            <button
              @click="toggleTheme"
              class="p-1.5 rounded-full text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
              :title="
                isDark
                  ? $t('common.switch_to_light')
                  : $t('common.switch_to_dark')
              "
            >
              <span class="sr-only">{{
                isDark
                  ? $t("common.switch_to_light")
                  : $t("common.switch_to_dark")
              }}</span>
              <!-- Sun icon (light mode) -->
              <svg
                v-if="isDark"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                />
              </svg>
              <!-- Moon icon (dark mode) -->
              <svg
                v-else
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                />
              </svg>
            </button>

            <!-- Notifications -->
            <div class="relative">
              <button
                @click="toggleNotifications"
                class="relative p-1.5 rounded-full text-gray-400 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                :title="$t('common.notifications')"
              >
                <span class="sr-only">{{ $t("common.notifications") }}</span>
                <svg
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                  />
                </svg>
                <!-- Notification count badge -->
                <span
                  v-if="unreadNotifications.length > 0"
                  class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
                >
                  {{ unreadNotifications.length }}
                </span>
              </button>

              <!-- Notifications Dropdown -->
              <div
                v-if="showNotifications"
                class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                role="menu"
                aria-orientation="vertical"
                tabindex="-1"
              >
                <div class="py-1" role="none">
                  <div
                    class="px-4 py-3 border-b border-gray-200 dark:border-gray-700"
                  >
                    <h3
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      Notifications
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ unreadNotifications.length }} unread messages
                    </p>
                  </div>

                  <div
                    v-if="notifications.length === 0"
                    class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400"
                  >
                    No notifications
                  </div>

                  <div v-else class="max-h-80 overflow-y-auto">
                    <a
                      v-for="notification in notifications"
                      :key="notification.id"
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                      role="menuitem"
                      tabindex="-1"
                      @click.prevent="markAsRead(notification.id)"
                    >
                      <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                          <span
                            :class="[
                              'h-8 w-8 rounded-full flex items-center justify-center',
                              notification.type === 'success'
                                ? 'bg-green-100 text-green-600'
                                : notification.type === 'warning'
                                ? 'bg-yellow-100 text-yellow-600'
                                : notification.type === 'error'
                                ? 'bg-red-100 text-red-600'
                                : 'bg-blue-100 text-blue-600',
                            ]"
                          >
                            <svg
                              class="h-5 w-5"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                            >
                              <path
                                v-if="notification.type === 'success'"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                              />
                              <path
                                v-else-if="notification.type === 'warning'"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              />
                              <path
                                v-else-if="notification.type === 'error'"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              />
                              <path
                                v-else
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                              />
                            </svg>
                          </span>
                        </div>
                        <div class="flex-1">
                          <p
                            class="text-sm font-medium text-gray-900 dark:text-white"
                          >
                            {{ notification.title }}
                          </p>
                          <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ notification.message }}
                          </p>
                        </div>
                        <div class="flex-shrink-0">
                          <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ formatTime(notification.time) }}
                          </p>
                        </div>
                      </div>
                    </a>
                  </div>

                  <div class="border-t border-gray-200 dark:border-gray-700">
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                      @click.prevent="markAllAsRead"
                    >
                      Mark all as read
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Mobile sidebar (overlay) -->
      <div
        v-show="isMobileMenuOpen"
        class="fixed inset-0 z-40 md:hidden"
        @click="isMobileMenuOpen = false"
      >
        <div
          class="fixed inset-0 bg-gray-600 bg-opacity-75"
          aria-hidden="true"
        ></div>
        <div
          class="fixed inset-y-0 left-0 max-w-xs w-full bg-white dark:bg-gray-800 shadow-lg"
        >
          <div class="h-full overflow-y-auto">
            <div
              class="h-16 flex items-center justify-between px-4 border-b border-gray-200 dark:border-gray-700"
            >
              <span
                class="text-xl font-bold text-indigo-600 dark:text-indigo-400"
                >Menu</span
              >
              <button
                @click="isMobileMenuOpen = false"
                class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-white"
              >
                <span class="sr-only">Close sidebar</span>
                <svg
                  class="h-6 w-6"
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
            <nav class="px-2 py-4 space-y-1">
              <div v-for="(item, index) in navigation" :key="item.name">
                <!-- Menu item with sub-items -->
                <div v-if="item.children && filterChildren(item.children).length > 0">
                  <button
                    @click="toggleSubMenu(index)"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-base font-medium rounded-md"
                    :class="{
                      'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-white':
                        isActiveParent(item),
                      'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700':
                        !isActiveParent(item)
                    }"
                  >
                    <div class="flex items-center">
                      <component
                        :is="item.icon"
                        class="mr-3 h-6 w-6 flex-shrink-0"
                        :class="{
                          'text-indigo-500': isActiveParent(item),
                          'text-gray-400 group-hover:text-gray-500 dark:text-gray-300':
                            !isActiveParent(item)
                        }"
                      />
                      <span>{{ $t(`admin.nav.${item.name}`) }}</span>
                    </div>
                    <svg
                      class="h-5 w-5 transform transition-transform duration-200"
                      :class="{
                        'rotate-180': isSubMenuOpen(index),
                        'text-indigo-500': isActiveParent(item)
                      }"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      />
                    </svg>
                  </button>
                  
                  <!-- Sub-menu items -->
                  <div 
                    v-show="isSubMenuOpen(index)"
                    class="mt-1 ml-10 space-y-1 overflow-hidden transition-all duration-200 ease-in-out"
                  >
                    <router-link
                      v-for="child in filterChildren(item.children)"
                      :key="child.name"
                      :to="child.href"
                      class="group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150"
                      :class="{
                        'bg-indigo-100 text-indigo-700 dark:bg-gray-600 dark:text-white': 
                          $route.path === child.href,
                        'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600': 
                          $route.path !== child.href
                      }"
                      @click="isMobileMenuOpen = false"
                    >
                      <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mr-3"></span>
                      {{ $t(`admin.nav.${child.name}`) }}
                      <span 
                        v-if="child.badge"
                        class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100"
                      >
                        {{ child.badge }}
                      </span>
                    </router-link>
                  </div>
                </div>
                
                <!-- Regular menu item -->
                <router-link
                  v-else-if="!item.permissions || hasAnyPermission(item.permissions)"
                  :to="item.href"
                  class="group flex items-center px-3 py-2.5 text-base font-medium rounded-md"
                  :class="{
                    'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-white':
                      $route.path.startsWith(item.href),
                    'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700':
                      !$route.path.startsWith(item.href),
                  }"
                  @click="isMobileMenuOpen = false"
                >
                  <component
                    :is="item.icon"
                    class="mr-3 h-6 w-6 flex-shrink-0"
                    :class="{
                      'text-indigo-500': $route.path.startsWith(item.href),
                      'text-gray-400 group-hover:text-gray-500 dark:text-gray-300':
                        !$route.path.startsWith(item.href),
                    }"
                  />
                  <span class="truncate">{{ $t(`admin.nav.${item.name}`) }}</span>
                  <span 
                    v-if="item.badge"
                    class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100"
                  >
                    {{ item.badge }}
                  </span>
                </router-link>
              </div>
            </nav>
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <main
        class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 sm:p-6"
      >
        <div class="max-w-7xl mx-auto min-h-full">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useThemeStore } from "@/stores/theme";
import { useLanguageStore } from "@/stores/language";
import { useAuthStore } from "@/stores/auth";
import { useRBAC } from "@/composables/useRBAC";
import { getAdminNavigation } from "@/utils/navigation";

// Icons
import {
  BellIcon,
  XMarkIcon,
  SunIcon,
  MoonIcon,
  ChevronDownIcon,
  Cog6ToothIcon,
  ClockIcon,
  MenuIcon,
  HomeIcon,
  UserGroupIcon,
  DocumentTextIcon,
  PhotoIcon,
  ChartBarIcon,
  LockClosedIcon,
} from "@heroicons/vue/24/outline";

export default {
  name: "AdminLayout",
  components: {
    // Icons
    BellIcon,
    XMarkIcon,
    SunIcon,
    MoonIcon,
    ChevronDownIcon,
    Cog6ToothIcon,
    ClockIcon,
    MenuIcon,
    HomeIcon,
    UserGroupIcon,
    DocumentTextIcon,
    PhotoIcon,
    ChartBarIcon,
    LockClosedIcon,
  },
  setup() {
    const router = useRouter();
    const route = useRoute();
    const themeStore = useThemeStore();
    const languageStore = useLanguageStore();
    const authStore = useAuthStore();
    const rbac = useRBAC();
    const isMobileMenuOpen = ref(false);
    const openSubMenus = ref([]);
    const showNotifications = ref(false);
    
    // Initialize language and theme stores
    languageStore.initializeLanguage();
    themeStore.initializeTheme();
    
    // Get user data from auth store
    const user = computed(() => authStore.user || {});
    const userInitials = computed(() => {
      if (!user.value.name) return 'UU';
      return user.value.name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
    });

    // Toggle submenu
    const toggleSubMenu = (index) => {
      const currentIndex = openSubMenus.value.indexOf(index);
      if (currentIndex === -1) {
        openSubMenus.value.push(index);
      } else {
        openSubMenus.value.splice(currentIndex, 1);
      }
    };

    // Check if submenu is open
    const isSubMenuOpen = (index) => {
      return openSubMenus.value.includes(index);
    };

    // Check if parent menu item is active
    const isActiveParent = (item) => {
      if (!item.children) return false;
      return item.children.some((child) => route.path.startsWith(child.path || child.href || ''));
    };

    // Filter children based on permissions
    const filterChildren = (children) => {
      if (!children) return [];
      return children.filter((child) => !child.permissions || rbac.hasAnyPermission(child.permissions));
    };

    // Get filtered children for rendering
    const getFilteredChildren = (children) => {
      if (!children) return [];
      return children.filter(child => !child.permissions || hasAnyPermission(child.permissions));
    };

    // Check if user has any of the required permissions
    const hasAnyPermission = (permissions) => {
      if (!permissions) return true;
      return rbac.hasAnyPermission(permissions);
    };

    // Navigation items with submenus
    const navigation = computed(() => {
      const navItems = getAdminNavigation();
      
      // Add any additional items that aren't in routes
      const additionalItems = [
        {
          name: 'media',
          path: '/admin/media',
          icon: 'PhotoIcon',
          navOrder: 7,
          permissions: ['upload_media']
        },
        {
          name: 'reports',
          path: '/admin/reports',
          icon: 'ChartBarIcon',
          navOrder: 8,
          permissions: ['view_reports']
        }
      ];
      
      return [...navItems, ...additionalItems]
        .sort((a, b) => (a.navOrder || 999) - (b.navOrder || 999));
    });

    // Sample notifications data
    const notifications = ref([
      {
        id: 1,
        title: "New User Registration",
        message: "A new user has registered on the platform.",
        type: "success",
        time: new Date("2025-07-13T13:00:00"),
        read: false,
      },
      {
        id: 2,
        title: "System Update Available",
        message: "A new system update is available for installation.",
        type: "warning",
        time: new Date("2025-07-13T12:00:00"),
        read: true,
      },
      {
        id: 3,
        title: "Database Backup Failed",
        message: "The scheduled database backup failed to complete.",
        type: "error",
        time: new Date("2025-07-13T11:00:00"),
        read: false,
      },
    ]);

    // Expose the required functions to template
    // Toggle theme between light and dark
    const toggleTheme = () => {
      themeStore.toggleTheme();
    };

    // Check if dark mode is enabled
    const isDark = computed(() => themeStore.theme === 'dark');

    // Toggle language menu
    const toggleLanguageMenu = () => {
      languageStore.toggleLanguageMenu();
    };

    // Set language
    const setLanguage = (lang) => {
      languageStore.setLanguage(lang);
      // Close the language menu after selection
      languageStore.toggleLanguageMenu(false);
    };

    // Logout function
    const logout = async () => {
      try {
        await authStore.logout();
        router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error);
      }
    };

    return {
      router,
      themeStore,
      languageStore,
      user,
      userInitials,
      isMobileMenuOpen,
      openSubMenus,
      navigation,
      filterChildren,
      getFilteredChildren,
      hasAnyPermission,
      toggleSubMenu,
      isSubMenuOpen,
      isActiveParent,
      showNotifications,
      notifications,
      isDark,
      toggleTheme,
      toggleLanguageMenu,
      setLanguage,
      logout,
      unreadNotifications: computed(() => notifications.value.filter(n => !n.read)),
      formatTime: (time) => {
        return new Date(time).toLocaleTimeString([], {
          hour: '2-digit',
          minute: '2-digit',
        });
      },
      toggleNotifications: () => {
        showNotifications.value = !showNotifications.value;
      },
      markAsRead: (id) => {
        const notification = notifications.value.find(n => n.id === id);
        if (notification) notification.read = true;
      },
      markAllAsRead: () => {
        notifications.value.forEach(n => n.read = true);
      },
      removeNotification: (id) => {
        const index = notifications.value.findIndex(n => n.id === id);
        if (index !== -1) notifications.value.splice(index, 1);
      }
    };
  }
};
</script>
