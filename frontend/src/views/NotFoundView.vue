<template>
  <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900">
    <main class="flex-grow flex flex-col justify-center max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 h-full">
      <div class="flex flex-col justify-center h-full">
        <div class="text-center">
          <div class="relative mx-auto w-full max-w-md px-4 sm:max-w-3xl sm:px-6 lg:px-0">
            <!-- Custom 404 SVG Illustration -->
            <svg viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg" class="w-full">
              <!-- Message bubble -->
              <g class="transition-colors duration-300">
                <rect x="260" y="170" width="280" height="180" rx="8" class="text-gray-50 dark:text-gray-800/90" fill="currentColor" stroke="currentColor" stroke-width="1" />
                <polygon points="380,350 400,370 420,350" class="text-gray-50 dark:text-gray-800/90" fill="currentColor" stroke="currentColor" stroke-width="1" />
                
                <!-- Paper fold shadow -->
                <polygon points="520,170 520,200 490,170" class="text-gray-200/80 dark:text-gray-700/80" fill="currentColor" />
                
                <!-- 404 Text -->
                <text x="400" y="250" font-size="72" text-anchor="middle" font-weight="bold" class="text-indigo-600 dark:text-indigo-300 font-inter" fill="currentColor">404</text>
                
                <!-- Text lines -->
                <rect x="300" y="280" width="200" height="3" rx="1.5" class="text-gray-200 dark:text-gray-600/80" fill="currentColor" />
                <rect x="310" y="295" width="180" height="3" rx="1.5" class="text-gray-200 dark:text-gray-600/80" fill="currentColor" />
                <rect x="320" y="310" width="160" height="3" rx="1.5" class="text-gray-200 dark:text-gray-600/80" fill="currentColor" />
              </g>

              <!-- Floating documents with animations -->
              <g class="animate-float" style="animation-duration: 6s;">
                <rect x="100" y="100" width="40" height="60" rx="4" class="text-indigo-100/80 dark:text-indigo-900/70" fill="currentColor" transform="rotate(-20 100 100)" stroke="currentColor" stroke-width="1" />
                <line x1="110" y1="125" x2="130" y2="125" class="text-indigo-500 dark:text-indigo-300/80" stroke="currentColor" stroke-width="2" transform="rotate(-20 100 100)" />
              </g>
              
              <g class="animate-float" style="animation-duration: 7s; animation-delay: 0.5s;">
                <rect x="640" y="120" width="40" height="60" rx="4" class="text-indigo-100/80 dark:text-indigo-900/70" fill="currentColor" transform="rotate(15 640 120)" stroke="currentColor" stroke-width="1" />
                <line x1="650" y1="145" x2="670" y2="145" class="text-indigo-500 dark:text-indigo-300/80" stroke="currentColor" stroke-width="2" transform="rotate(15 640 120)" />
              </g>
              
              <g class="animate-float" style="animation-duration: 8s; animation-delay: 1s;">
                <rect x="550" y="300" width="40" height="60" rx="4" class="text-indigo-100/80 dark:text-indigo-900/70" fill="currentColor" transform="rotate(-12 550 300)" stroke="currentColor" stroke-width="1" />
                <line x1="560" y1="325" x2="580" y2="325" class="text-indigo-500 dark:text-indigo-300/80" stroke="currentColor" stroke-width="2" transform="rotate(-12 550 300)" />
              </g>

              <!-- Magnifying glass with pulse animation -->
              <g class="animate-pulse" style="animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">
                <circle cx="630" cy="100" r="20" class="text-indigo-500 dark:text-indigo-300" stroke-width="2" fill="none" stroke="currentColor" />
                <line x1="643" y1="113" x2="660" y2="130" class="text-indigo-500 dark:text-indigo-300" stroke-width="2" stroke-linecap="round" stroke="currentColor" />
              </g>
            </svg>

          </div>
          
          <h1 class="mt-6 text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight sm:text-5xl">
            {{ $t('notFound.title') }}
          </h1>
          <p class="mt-3 text-base text-gray-500 dark:text-gray-400 max-w-md mx-auto">
            {{ $t('notFound.subtitle') }}
          </p>
          <div class="mt-6">
            <router-link 
              to="/" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150"
            >
              {{ $t('notFound.goBackHome') }}
              <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </router-link>
          </div>
        </div>
      </div>
    </main>
    <footer class="flex-shrink-0 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex justify-center space-x-4">
        <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
          {{ $t('notFound.contactSupport') }}
        </a>
        <span class="inline-block border-l border-gray-300 dark:border-gray-700" aria-hidden="true"></span>
        <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
          {{ $t('notFound.status') }}
        </a>
        <span class="inline-block border-l border-gray-300 dark:border-gray-700" aria-hidden="true"></span>
        <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
          {{ $t('notFound.twitter') }}
        </a>
      </nav>
    </footer>
  </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'NotFoundView',
  setup() {
    return {};
  }
});
</script>

<style scoped>
@keyframes float {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-10px) rotate(2deg);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
  will-change: transform;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Dark mode overrides */
.dark .text-indigo-300 {
  --tw-text-opacity: 1;
  color: rgb(165 180 252 / var(--tw-text-opacity));
}

.dark .text-indigo-900\/70 {
  color: rgba(49, 46, 129, 0.7);
}

.dark .text-indigo-300\/80 {
  color: rgba(165, 180, 252, 0.8);
}

/* Improve dark mode contrast */
.dark .dark\:text-gray-800\/90 {
  color: rgba(31, 41, 55, 0.9);
}

/* Smooth transitions */
* {
  transition: fill 0.2s ease, stroke 0.2s ease, opacity 0.2s ease;
}
</style>
