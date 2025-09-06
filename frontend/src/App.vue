<template>
  <component :is="layout">
    <router-view v-slot="{ Component: RouteComponent, route }">
      <transition name="fade" mode="out-in">
        <component :is="RouteComponent" :key="route.path" />
      </transition>
    </router-view>
  </component>
</template>

<script>
import { computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { useThemeStore } from "./stores/theme";
import { useI18n } from "vue-i18n";
import MainLayout from "@/layouts/MainLayout.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import CvSwapLayout from "@/modules/cv-swap/layouts/CvSwapLayout.vue";

export default {
  name: "App",
  components: {
    MainLayout,
    AdminLayout,
    CvSwapLayout,
  },
  setup() {
    const route = useRoute();
    const themeStore = useThemeStore();
    const { t, locale } = useI18n();

    // Determine which layout to use based on route
    const layout = computed(() => {
      if (route.path.startsWith('/admin')) {
        return 'AdminLayout';
      } else if (route.path.startsWith('/cv-swap')) {
        return 'CvSwapLayout';
      }
      // Default to MainLayout for all other routes
      return 'MainLayout';
    });

    // Apply theme and language on mount
    onMounted(() => {
      // Apply theme
      if (themeStore.isDark) {
        document.documentElement.classList.add("dark");
      } else {
        document.documentElement.classList.remove("dark");
      }

      // Set HTML lang attribute
      document.documentElement.lang = locale.value;
    });

    // Watch for language changes
    watch(
      () => locale.value,
      (newLang) => {
        document.documentElement.lang = newLang;
      }
    );

    return {
      t,
      layout,
    };
  },
};
</script>

<style>
/* Page transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background-color: #f3f4f6; /* bg-gray-100 */
}

.dark ::-webkit-scrollbar-track {
  background-color: #1f2937; /* dark:bg-gray-800 */
}

::-webkit-scrollbar-thumb {
  background-color: #d1d5db; /* bg-gray-300 */
  border-radius: 9999px; /* rounded-full */
}

.dark ::-webkit-scrollbar-thumb {
  background-color: #4b5563; /* dark:bg-gray-600 */
}

::-webkit-scrollbar-thumb:hover {
  background-color: #9ca3af; /* bg-gray-400 */
}

.dark ::-webkit-scrollbar-thumb:hover {
  background-color: #6b7280; /* dark:bg-gray-500 */
}
</style>
