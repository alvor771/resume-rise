<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 flex flex-col relative overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-1/2 -left-1/2 w-[200%] h-[200%] bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-violet-900/20 via-transparent to-transparent opacity-70"></div>
      <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-purple-500/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-indigo-500/5 rounded-full blur-3xl"></div>
    </div>
    
    <!-- App Navigation -->
    <AppNavigation />
    
    <!-- Page Content -->
    <main class="flex-grow relative z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page title and actions -->
        <div v-if="$slots.header" class="mb-8">
          <slot name="header"></slot>
        </div>
        
        <!-- Main content -->
        <div class="glass-card backdrop-blur-lg rounded-xl shadow-2xl overflow-hidden border border-white/10 hover:border-violet-400/30 transition-all duration-300 hover:shadow-violet-500/10">
          <div class="relative">
            <!-- Neon border effect on hover -->
            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-violet-500/0 via-violet-500/20 to-violet-500/0 opacity-0 hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            
            <router-view v-slot="{ Component }">
              <transition name="fade" mode="out-in">
                <component :is="Component" class="relative z-10" />
              </transition>
            </router-view>
          </div>
        </div>
      </div>
    </main>
    
    <!-- Footer -->
    <footer class="relative z-10 bg-gradient-to-t from-black/40 to-transparent backdrop-blur-sm border-t border-white/5 mt-12">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm bg-gradient-to-r from-violet-300 via-purple-300 to-pink-300 bg-clip-text text-transparent font-medium">
          &copy; {{ new Date().getFullYear() }} CV Swap. All rights reserved.
        </p>
      </div>
    </footer>
    
    <!-- Floating particles -->
    <div v-for="i in 12" :key="i" 
         class="absolute rounded-full bg-gradient-to-r from-violet-500/30 to-purple-500/30"
         :class="[
           'animate-float',
           'opacity-70',
           'blur-sm',
           'pointer-events-none',
           {
             'w-3 h-3': i % 3 === 0,
             'w-2 h-2': i % 3 === 1,
             'w-1 h-1': i % 3 === 2,
             'top-1/4': i % 4 === 0,
             'top-1/2': i % 4 === 1,
             'top-3/4': i % 4 === 2,
             'left-1/4': i % 3 === 0,
             'left-1/2': i % 3 === 1,
             'left-3/4': i % 3 === 2,
           }
         ]"
         :style="{
           'animation-delay': `${i * 2}s`,
           'animation-duration': `${10 + (i * 2)}s`
         }">
    </div>
  </div>
</template>

<script>
import AppNavigation from '@/modules/cv-swap/components/AppNavigation.vue';

export default {
  name: 'CvSwapLayout',
  components: {
    AppNavigation
  }
};
</script>

<style scoped>
/* Glass effect */
.glass-card {
  background: rgba(17, 24, 39, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 8px 32px 0 rgba(108, 99, 255, 0.1);
}

/* Animations */
@keyframes float {
  0%, 100% {
    transform: translateY(0) translateX(0) scale(1);
    opacity: 0.7;
  }
  50% {
    transform: translateY(-20px) translateX(10px) scale(1.2);
    opacity: 0.9;
  }
}

.animate-float {
  animation: float 8s ease-in-out infinite;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: rgba(167, 139, 250, 0.3);
  border-radius: 4px;
  transition: all 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(167, 139, 250, 0.5);
}
</style>
