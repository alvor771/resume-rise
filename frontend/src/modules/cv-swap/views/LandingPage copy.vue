<template>
  <div class="min-h-screen bg-gray-900 text-gray-100 transition-colors duration-300">
    <!-- Theme Toggle -->
    <button 
      @click="toggleTheme"
      class="fixed top-4 right-4 p-2 rounded-full bg-gray-800 text-purple-400 hover:bg-gray-700 transition-all z-50"
      aria-label="Toggle theme"
    >
      <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
      </svg>
    </button>

    <!-- Hero Section -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
      <div class="text-center relative z-10">
        <h1 class="text-4xl tracking-tight font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-300 sm:text-5xl md:text-6xl">
          <span class="block">Find Your Perfect</span>
          <span class="block">Job Match</span>
        </h1>
        <p class="mt-3 max-w-md mx-auto text-base text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
          Connect with top employers or discover talented candidates with our innovative CV swapping platform.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <router-link 
            to="/app/discover" 
            class="px-8 py-4 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors"
          >
            Create Your Visual CV
          </router-link>
          <router-link 
            to="/app/discover?role=recruiter" 
            class="px-8 py-4 bg-white text-purple-600 border-2 border-purple-600 font-medium rounded-lg hover:bg-purple-50 transition-colors"
          >
            Find Candidates
          </router-link>
        </div>
      </div>
      
      <!-- Hero Image/Animation -->
      <div class="mt-16 relative">
        <div class="relative z-10 max-w-4xl mx-auto">
          <img 
            src="@/assets/images/hero-dashboard.svg" 
            alt="CV Swap Dashboard Preview" 
            class="rounded-xl shadow-2xl border border-gray-200"
          >
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-900/50 relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-900/20 to-transparent opacity-50"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
          <span class="inline-block px-4 py-1 text-sm font-semibold text-purple-300 bg-purple-900/30 rounded-full mb-4">
            Simple Process
          </span>
          <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-4">
            How It <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-300">Works</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Get started in minutes and discover your next career opportunity or top talent
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div 
            v-for="(feature, index) in features" 
            :key="index" 
            class="p-8 bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/10"
          >
            <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center mb-6 shadow-lg">
              <component :is="feature.icon" class="w-7 h-7 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">{{ feature.title }}</h3>
            <p class="text-gray-300">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            What Our Users Say
          </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(testimonial, index) in testimonials" :key="index" class="p-6 bg-white rounded-xl shadow-sm">
            <div class="flex items-center mb-4">
              <img :src="testimonial.avatar" :alt="testimonial.name" class="w-12 h-12 rounded-full mr-4">
              <div>
                <h4 class="font-semibold text-gray-900">{{ testimonial.name }}</h4>
                <p class="text-sm text-gray-500">{{ testimonial.role }}</p>
              </div>
            </div>
            <p class="text-gray-600 italic">"{{ testimonial.quote }}"</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-purple-900/80 to-purple-800/80 relative overflow-hidden">
      <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0VjIwYTQgNCAwIDEwLTggMHYxNGE0IDQgMCAxMDggMHptLTI4IDJjLTIuMjEgMC00IDEuNzkxLTQgNCAwIDIuMjA5IDEuNzkxIDQgNCA0czQtMS43OTEgNC00YzAtMi4yMDktMS43OS00LTQtNHptNDAgMGMtMi4yMSAwLTQgMS43OTEtNCA0IDAgMi4yMDkgMS43OSA0IDQgNHM0LTEuNzkxIDQtNGMwLTIuMjA5LTEuNzktNC00LTR6Ii8+PC9nPjwvZz48L3N2Zz4=')]"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <h2 class="text-4xl font-extrabold text-white sm:text-5xl mb-6">
          Ready to transform your <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-white">career search</span>?
        </h2>
        <p class="text-xl text-purple-100 mb-10 max-w-3xl mx-auto">
          Join thousands of professionals who found their dream job through CV Swap
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <router-link
            to="/register" 
            class="group relative px-8 py-4 bg-white text-purple-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-purple-500/30 flex items-center justify-center"
          >
            <span>Sign Up Free</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </router-link>
          <router-link
            to="/login" 
            class="px-8 py-4 border-2 border-white/80 text-white font-medium rounded-lg hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10 flex items-center justify-center"
          >
            <span>Log In</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { 
  UserIcon, 
  BriefcaseIcon, 
  BoltIcon, 
  SparklesIcon, 
  ChatBubbleLeftRightIcon, 
  HeartIcon 
} from '@heroicons/vue/24/outline';

export default {
  name: 'LandingPage',
  components: {
    UserIcon,
    BriefcaseIcon,
    BoltIcon,
    SparklesIcon,
    ChatBubbleLeftRightIcon,
    HeartIcon
  },
  mounted() {
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
      this.isDark = true;
    } else {
      document.documentElement.classList.remove('dark');
      this.isDark = false;
    }
  },
  methods: {
    toggleTheme() {
      this.isDark = !this.isDark;
      if (this.isDark) {
        localStorage.theme = 'dark';
        document.documentElement.classList.add('dark');
      } else {
        localStorage.theme = 'light';
        document.documentElement.classList.remove('dark');
      }
    }
  },
  data() {
    return {
      isDark: true,
      features: [
        {
          icon: 'LightningBoltIcon',
          title: 'Instant Matches',
          description: 'Our AI-powered matching system connects you with the most relevant opportunities in seconds.'
        },
        {
          icon: 'BriefcaseIcon',
          title: 'For Everyone',
          description: 'Whether you\'re a job seeker or employer, we have the tools you need to succeed.'
        },
        {
          icon: 'SparklesIcon',
          title: 'Visual CVs',
          description: 'Stand out with beautiful, interactive CVs that showcase your skills and experience.'
        },
        {
          icon: 'ChatAlt2Icon',
          title: 'Direct Messaging',
          description: 'Communicate directly with potential matches through our secure messaging system.'
        },
        {
          icon: 'HeartIcon',
          title: 'Smart Bookmarks',
          description: 'Save your favorite profiles and get notified when there are new matches.'
        },
        {
          icon: 'UserIcon',
          title: 'Profile Analytics',
          description: 'Track who viewed your profile and improve your visibility.'
        }
      ],
      testimonials: [
        {
          name: 'Alex Johnson',
          role: 'UX Designer at TechCorp',
          quote: 'Found my dream job within a week of joining! The matching is incredibly accurate.',
          avatar: 'https://randomuser.me/api/portraits/men/32.jpg'
        },
        {
          name: 'Sarah Williams',
          role: 'HR Director at InnovateX',
          quote: 'The quality of candidates we found here is unmatched. Highly recommended!',
          avatar: 'https://randomuser.me/api/portraits/women/44.jpg'
        },
        {
          name: 'Michael Chen',
          role: 'Senior Developer',
          quote: 'The visual CV builder is a game-changer. I got 3x more interviews than before!',
          avatar: 'https://randomuser.me/api/portraits/men/29.jpg'
        }
      ]
    };
  }
};
</script>
