<template>
    <!-- Hero Section -->
    <div class="relative bg-white dark:bg-gray-900 overflow-hidden">
      <!-- Decorative background -->
      <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
            <span class="block">{{ $t(`static.${$route.name}.title`) }}</span>
          </h1>
          <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            {{ $t(`static.${$route.name}.subtitle`) }}
          </p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-900">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Last Updated -->
        <div class="flex justify-end mb-8">
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $t('static.lastUpdated') }}: {{ $t(`static.${$route.name}.lastUpdated`) }}
          </div>
        </div>
        
        <!-- Page Content -->
        <div class="prose prose-indigo dark:prose-invert max-w-none">
          <slot></slot>
        </div>
        
        <!-- Back to Top -->
        <div class="mt-12 text-center">
          <button 
            @click="scrollToTop"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ $t('static.backToTop') }}
            <svg class="ml-2 -mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
export default defineComponent({
  name: 'StaticPageLayout',
  methods: {
    scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }
  }
});
</script>

<style scoped>
.prose {
  color: inherit;
}

.prose :where(h2):not(:where([class~="not-prose"] *)) {
  @apply mt-10 mb-6 text-2xl font-bold text-gray-900 dark:text-white;
}

.prose :where(h3):not(:where([class~="not-prose"] *)) {
  @apply mt-8 mb-4 text-xl font-semibold text-gray-800 dark:text-gray-200;
}

.prose :where(p):not(:where([class~="not-prose"] *)) {
  @apply mt-4 text-gray-600 dark:text-gray-300 leading-relaxed;
}

.prose :where(ul):not(:where([class~="not-prose"] *)) {
  @apply mt-4 pl-5 list-disc text-gray-600 dark:text-gray-300 space-y-2;
}

.prose :where(a):not(:where([class~="not-prose"] *)) {
  @apply text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline;
}

.prose :where(blockquote):not(:where([class~="not-prose"] *)) {
  @apply border-l-4 border-indigo-200 dark:border-indigo-800 pl-4 italic text-gray-600 dark:text-gray-400;
}
</style>
