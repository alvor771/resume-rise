<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col items-center justify-center p-4 transition-colors duration-500 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div v-for="i in 20" :key="`dot-${i}`" 
           class="absolute rounded-full bg-purple-200 dark:bg-purple-900 opacity-30"
           :class="`dot-${i}`"
           :style="{
             width: `${Math.random() * 10 + 5}px`,
             height: `${Math.random() * 10 + 5}px`,
             left: `${Math.random() * 100}%`,
             top: `${Math.random() * 100}%`,
             animation: `float ${Math.random() * 15 + 10}s linear infinite`,
             animationDelay: `${Math.random() * 5}s`
           }">
      </div>
    </div>
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
    
    <div class="flex flex-col md:flex-row items-center justify-center gap-8 w-full max-w-6xl relative z-10">
      <!-- Candidate Card -->
      <div 
        ref="candidateCard"
        class="card candidate-card w-full max-w-sm bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transition-all duration-500 transform hover:scale-105 hover:shadow-2xl border-2 border-transparent hover:border-purple-400 dark:hover:border-purple-600 relative group"
        @mouseenter="hoverCard('candidate')"
        @mouseleave="resetCard('candidate')"
      >
      <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-indigo-600 opacity-0 group-hover:opacity-5 dark:group-hover:opacity-10 transition-opacity duration-500"></div>
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
        class="card company-card w-full max-w-sm bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-xl overflow-hidden transition-all duration-500 transform hover:scale-105 hover:shadow-2xl border-2 border-transparent hover:border-blue-400 relative group"
        @mouseenter="hoverCard('company')"
        @mouseleave="resetCard('company')"
      >
      <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
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
      :disabled="isMatching"
      class="mt-12 px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 relative overflow-hidden group"
    >
      <span class="relative z-10 flex items-center">
        <svg v-if="isMatching" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ isMatching ? 'Szukam...' : 'Znajdź dopasowanie' }}
      </span>
      <span class="absolute inset-0 bg-gradient-to-r from-purple-700 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
    </button>
    
    <!-- Match Result -->
    <transition
      enter-active-class="transition-opacity duration-1000"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
    >
      <div v-if="matchFound" class="mt-12 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl text-center transform transition-all duration-500" :class="{'scale-105': isPulsing}">
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
const isMatching = ref(false);
const darkMode = ref(false);
const isPulsing = ref(false);
let animationTimeline = null;
let hoverTimeline = null;

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

const hoverCard = (type) => {
  if (hoverTimeline) hoverTimeline.kill();
  
  const card = type === 'candidate' ? candidateCard.value : companyCard.value;
  hoverTimeline = gsap.timeline();
  
  hoverTimeline.to(card, {
    y: -10,
    duration: 0.3,
    ease: 'power2.out'
  });
};

const resetCard = (type) => {
  if (hoverTimeline) hoverTimeline.kill();
  
  const card = type === 'candidate' ? candidateCard.value : companyCard.value;
  gsap.to(card, {
    y: 0,
    duration: 0.3,
    ease: 'power2.out'
  });
};

const createMergeParticles = () => {
  const container = document.querySelector('.relative');
  if (!container) return;
  
  const rect = container.getBoundingClientRect();
  
  for (let i = 0; i < 20; i++) {
    const particle = document.createElement('div');
    const size = 4 + Math.random() * 6;
    const angle = Math.random() * Math.PI * 2;
    const distance = 20 + Math.random() * 80;
    const color = Math.random() > 0.5 ? '#8b5cf6' : '#3b82f6';
    
    particle.className = 'absolute rounded-full pointer-events-none';
    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    particle.style.background = color;
    particle.style.left = `${rect.width / 2}px`;
    particle.style.top = `${rect.height / 2}px`;
    particle.style.opacity = '0.8';
    
    container.appendChild(particle);
    
    gsap.to(particle, {
      x: Math.cos(angle) * distance,
      y: Math.sin(angle) * distance,
      opacity: 0,
      scale: 0.2,
      duration: 1.5,
      ease: 'power2.out',
      onComplete: () => particle.remove()
    });
  }
};

const createParticles = () => {
  const button = document.querySelector('button');
  if (!button) return;
  
  const rect = button.getBoundingClientRect();
  
  for (let i = 0; i < 10; i++) {
    const particle = document.createElement('div');
    particle.className = 'absolute w-2 h-2 bg-white rounded-full pointer-events-none';
    particle.style.left = `${rect.left + rect.width / 2}px`;
    particle.style.top = `${rect.top + rect.height / 2}px`;
    document.body.appendChild(particle);
    
    const angle = Math.random() * Math.PI * 2;
    const distance = 50 + Math.random() * 50;
    
    gsap.to(particle, {
      x: Math.cos(angle) * distance,
      y: Math.sin(angle) * distance,
      opacity: 0,
      scale: 0.5,
      duration: 1,
      ease: 'power2.out',
      onComplete: () => particle.remove()
    });
  }
};

const startPulseAnimation = () => {
  isPulsing.value = true;
  setTimeout(() => {
    isPulsing.value = false;
  }, 1000);
};

const startMatching = async () => {
  if (isMatching.value) return;
  
  isMatching.value = true;
  matchFound.value = false;
  
  // Reset any existing animations
  if (animationTimeline) {
    animationTimeline.kill();
  }
  
  // Create particles on button
  createParticles();
  
  // Animate cards
  animationTimeline = gsap.timeline({
    onComplete: () => {
      matchFound.value = true;
      isMatching.value = false;
      startPulseAnimation();
    }
  });

  // Initial movement to center
  animationTimeline.to(candidateCard.value, { 
    x: 50, 
    duration: 1, 
    ease: 'power2.inOut' 
  }, 0)
  .to(companyCard.value, { 
    x: -50, 
    duration: 1, 
    ease: 'power2.inOut' 
  }, 0)
  
  // Add slight bounce effect
  .to([candidateCard.value, companyCard.value], {
    y: -30,
    duration: 0.5,
    ease: 'back.out(1.7)'
  }, '>0.2')
  
  // Create merge effect
  .to([candidateCard.value, companyCard.value], {
    scale: 0.95,
    boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
    duration: 0.3,
    ease: 'power2.out'
  }, '>0.1')
  
  // Add glow effect
  .to([candidateCard.value, companyCard.value], {
    boxShadow: '0 0 30px 10px rgba(168, 85, 247, 0.6)',
    duration: 0.5,
    ease: 'power1.out',
    onStart: createMergeParticles
  }, '>0.1')
  
  // Final transformation
  .to([candidateCard.value, companyCard.value], {
    scale: 1.05,
    opacity: 0,
    duration: 0.5,
    ease: 'power2.in'
  }, '>0.3');
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
@keyframes float {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(5deg); }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

.glow {
  box-shadow: 0 0 15px 5px rgba(168, 85, 247, 0.5);
}

.card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
  transform: translateY(-5px);
}
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