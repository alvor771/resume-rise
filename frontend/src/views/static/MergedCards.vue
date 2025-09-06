<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col items-center justify-center p-4 transition-colors duration-300">
    <div class="absolute top-4 right-4">
      <button 
        @click="toggleDarkMode"
        class="p-2 rounded-full bg-white dark:bg-gray-800 shadow-lg"
        :aria-label="darkMode ? 'Przełącz na jasny motyw' : 'Przełącz na ciemny motyw'"
      >
        <svg v-if="darkMode" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg v-else class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      </button>
    </div>
    <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-12">Znajdź swoją idealną parę</h1>
    
    <div class="flex flex-col md:flex-row items-center justify-center gap-8 w-full max-w-6xl">
      <!-- Candidate Card -->
      <div 
        ref="candidateCard"
        class="card candidate-card w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-500 transform hover:scale-105 hover:shadow-2xl"
      >
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-indigo-600 opacity-90"></div>
        <div class="relative z-10 p-6 h-full flex flex-col">
          <div class="flex items-center justify-between mb-6">
            <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-sm text-white rounded-full">Kandydat</span>
          </div>
          
          <div class="mt-4">
            <h2 class="text-2xl font-bold text-white">Jan Kowalski</h2>
            <p class="text-purple-100 mt-1">Programista Frontend</p>
            
            <div class="mt-6 space-y-3">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 18h.01" />
                </svg>
                <span class="text-purple-100 text-sm">3+ lata doświadczenia</span>
              </div>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-purple-100 text-sm">Warszawa, Polska</span>
              </div>
            </div>
          </div>
          
          <div class="mt-6 pt-4 border-t border-purple-400/30">
            <div class="flex flex-wrap gap-2">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-xs text-white rounded-full">Vue.js</span>
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-xs text-white rounded-full">TypeScript</span>
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-xs text-white rounded-full">Tailwind</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- VS Badge -->
      <div class="relative my-8 md:my-0">
        <div class="w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center">
          <span class="text-2xl font-bold text-purple-600">VS</span>
        </div>
      </div>
      
      <!-- Company Card -->
      <div 
        ref="companyCard"
        class="card company-card w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-500 transform hover:scale-105 hover:shadow-2xl"
      >
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-600 opacity-90"></div>
        <div class="relative z-10 p-6 h-full flex flex-col">
          <div class="flex items-center justify-between mb-6">
            <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-sm text-white rounded-full">Firma</span>
          </div>
          
          <div class="mt-4">
            <div class="flex items-center mb-2">
              <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                <span class="text-blue-600 font-bold text-lg">TC</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-white">TechPol</h2>
                <p class="text-blue-100 text-sm">Rozwiązania IT dla biznesu</p>
              </div>
            </div>
            
            <div class="mt-6 space-y-3">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M12 18h.01" />
                </svg>
                <span class="text-blue-100 text-sm">Średnia firma (50-200 osób)</span>
              </div>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-200 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-blue-100 text-sm">Warszawa, biuro w centrum</span>
              </div>
            </div>
          </div>
          
          <div class="mt-6 pt-4 border-t border-blue-400/30">
            <p class="text-blue-100 text-sm mb-3">Szukamy do zespołu:</p>
            <div class="flex flex-wrap gap-2">
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-xs text-white rounded-full">Frontend</span>
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-xs text-white rounded-full">Vue.js</span>
              <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-xs text-white rounded-full">TypeScript</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Match Button -->
    <button 
      @click="startMatching"
      class="mt-12 px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-medium rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2"
    >
      Znajdź dopasowanie
    </button>
    
    <!-- Match Result -->
    <transition
      enter-active-class="transition-opacity duration-1000"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
    >
      <div v-if="matchFound" class="mt-12 p-6 bg-white rounded-2xl shadow-xl text-center">
        <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 mb-4">
          Znaleziono dopasowanie! 🎉
        </div>
        <p class="text-gray-600 dark:text-gray-300">Jan Kowalski idealnie pasuje na stanowisko Programisty Frontend w firmie TechPol!</p>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { gsap } from 'gsap';

const candidateCard = ref(null);
const companyCard = ref(null);
const matchFound = ref(false);
const darkMode = ref(false);

const updateTheme = () => {
  if (darkMode.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
};

const toggleDarkMode = () => {
  darkMode.value = !darkMode.value;
  updateTheme();
};

// Check for saved user preference or use system preference
if (typeof window !== 'undefined') {
  const savedTheme = localStorage.getItem('theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  darkMode.value = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);
  updateTheme();
}

// Watch for system theme changes
if (typeof window !== 'undefined') {
  const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
  darkModeMediaQuery.addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      darkMode.value = e.matches;
      updateTheme();
    }
  });
}

const startMatching = () => {
  matchFound.value = false;
  
  // Reset positions
  gsap.set([candidateCard.value, companyCard.value], { 
    x: 0, 
    y: 0,
    scale: 1,
    rotate: 0
  });
  
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
  
  // Initial animation
  tl.to([candidateCard.value, companyCard.value], {
    y: -20,
    scale: 1.05,
    duration: 0.8,
    yoyo: true,
    repeat: 1,
    ease: 'sine.inOut'
  })
  .add(() => {
    // Cards approach each other
    gsap.to(candidateCard.value, {
      x: 80,
      rotate: -5,
      duration: 1,
      ease: 'back.out(1.5)'
    });
    
    gsap.to(companyCard.value, {
      x: -80,
      rotate: 5,
      duration: 1,
      ease: 'back.out(1.5)'
    });
  }, '>0.5')
  .to([candidateCard.value, companyCard.value], {
    scale: 1.1,
    boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
    duration: 0.5,
    onComplete: () => {
      matchFound.value = true;
      
      // Bounce effect when matched
      gsap.to([candidateCard.value, companyCard.value], {
        y: -30,
        duration: 0.3,
        yoyo: true,
        repeat: 1,
        ease: 'power2.inOut'
      });
    }
  });
};

// Initial animation on mount
onMounted(() => {
  gsap.set([candidateCard.value, companyCard.value], { 
    y: 50, 
    opacity: 0 
  });
  
  gsap.to([candidateCard.value, companyCard.value], {
    y: 0,
    opacity: 1,
    duration: 1,
    stagger: 0.2,
    ease: 'back.out(1.5)'
  });
});
</script>

<style scoped>
.card {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  height: 380px;
  backdrop-filter: blur(10px);
}

.card::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
  transform: rotate(30deg);
  transition: all 0.6s ease;
}

.card:hover::before {
  transform: rotate(0deg) translateY(20%);
}

.candidate-card:hover {
  box-shadow: 0 20px 25px -5px rgba(124, 58, 237, 0.2), 0 10px 10px -5px rgba(124, 58, 237, 0.1);
}

.company-card:hover {
  box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.2), 0 10px 10px -5px rgba(59, 130, 246, 0.1);
}
</style>