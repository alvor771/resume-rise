<template>
  <nav class="backdrop-blur-lg bg-violet-900/70 border-b border-white/10 fixed top-0 left-0 right-0 z-50 mb-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link 
            to="/" 
            class="text-xl font-bold bg-gradient-to-r from-violet-300 to-pink-300 bg-clip-text text-transparent flex items-center"
          >
            <svg class="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="none">
              <path d="M9 13H15M9 17H15M9 9H15M5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21Z" 
                    stroke="url(#gradient1)" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"/>
              <defs>
                <linearGradient id="gradient1" x1="3" y1="12" x2="21" y2="12" gradientUnits="userSpaceOnUse">
                  <stop stop-color="#A78BFA"/>
                  <stop offset="1" stop-color="#F472B6"/>
                </linearGradient>
              </defs>
            </svg>
            CV Swap
          </router-link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden sm:flex space-x-1 items-center">
          <router-link 
            v-for="link in navLinks" 
            :key="link.to"
            :to="link.to"
            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
            :class="isActive(link.to) 
              ? 'text-white bg-violet-800/50' 
              : 'text-violet-200 hover:bg-violet-800/30 hover:text-white'"
          >
            {{ link.label }}
          </router-link>
        </div>

        <!-- User Menu -->
        <div class="flex items-center">
          <button 
            @click="toggleUserMenu"
            class="p-1 rounded-full hover:bg-violet-800/50 transition-colors"
          >
            <img 
              :src="user.avatar" 
              :alt="user.name"
              class="h-8 w-8 rounded-full border-2 border-violet-400/50"
            >
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

export default {
  name: 'AppNavigation',
  setup() {
    const route = useRoute()
    const isMobileMenuOpen = ref(false)

    const user = {
      name: 'User Name',
      avatar: 'https://ui-avatars.com/api/?name=User&background=7c3aed&color=fff'
    }

    const navLinks = [
      { to: '/discover', label: 'Discover' },
      { to: '/templates', label: 'Templates' },
      { to: '/profile', label: 'Profile' }
    ]

    const isActive = (path) => {
      return route.path.startsWith(path)
    }

    const toggleUserMenu = () => {
      // Toggle user menu logic
    }

    return {
      user,
      navLinks,
      isMobileMenuOpen,
      isActive,
      toggleUserMenu
    }
  }
}
</script>