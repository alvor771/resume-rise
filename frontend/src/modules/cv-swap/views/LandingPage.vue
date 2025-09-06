<template>
  <div
    class="min-h-screen bg-gray-900 text-gray-100 transition-colors duration-300 overflow-x-hidden"
  >
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div
        class="absolute -top-96 -left-96 w-[800px] h-[800px] bg-purple-900/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob"
      ></div>
      <div
        class="absolute -top-48 -right-48 w-[600px] h-[600px] bg-indigo-900/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"
      ></div>
      <div
        class="absolute bottom-1/4 -left-48 w-[500px] h-[500px] bg-blue-900/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"
      ></div>
    </div>

    <!-- Theme Toggle -->
    <button
      @click="toggleTheme"
      class="fixed top-4 right-4 p-3 rounded-full bg-gray-800/80 backdrop-blur-sm text-purple-400 hover:bg-gray-700/80 transition-all z-50 group shadow-lg"
      aria-label="Toggle theme"
    >
      <div class="relative w-6 h-6">
        <svg
          v-if="isDark"
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 absolute inset-0 transition-transform duration-500 group-hover:rotate-12"
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
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 absolute inset-0 transition-transform duration-500 group-hover:rotate-12"
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
      </div>
    </button>

    <!-- Navigation -->
    <nav class="relative z-50 py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <span
            class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-purple-300 bg-clip-text text-transparent"
            >CV<span class="text-white">Swap</span></span
          >
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <a
            href="#features"
            class="text-gray-300 hover:text-white transition-colors"
            >Features</a
          >
          <a
            href="#how-it-works"
            class="text-gray-300 hover:text-white transition-colors"
            >How It Works</a
          >
          <a
            href="#testimonials"
            class="text-gray-300 hover:text-white transition-colors"
            >Testimonials</a
          >
          <a
            href="#pricing"
            class="text-gray-300 hover:text-white transition-colors"
            >Pricing</a
          >
          <a
            href="#faq"
            class="text-gray-300 hover:text-white transition-colors"
            >FAQ</a
          >
        </div>
        <div class="flex items-center space-x-4">
          <router-link
            to="/login"
            class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
            >Log in</router-link
          >
          <router-link
            to="/register"
            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-medium hover:opacity-90 transition-all transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-purple-500/30"
          >
            Get Started
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
      <div class="text-center relative z-10">
        <div
          class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-purple-900/30 text-purple-300 mb-6 border border-purple-800/50 animate-fade-in"
        >
          <span class="relative flex h-2 w-2 mr-2">
            <span
              class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"
            ></span>
            <span
              class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"
            ></span>
          </span>
          Trusted by 10,000+ professionals
        </div>

        <h1
          class="text-4xl tracking-tight font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-purple-200 sm:text-5xl md:text-6xl lg:text-7xl animate-fade-in-up"
        >
          <span class="block">Find Your Perfect</span>
          <span class="block"
            >Career
            <span class="relative"
              >Match
              <svg
                class="absolute -bottom-2 left-0 w-full h-3 text-purple-500/50"
                viewBox="0 0 200 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M0 15 Q50 0 100 15 T200 15"
                  stroke="currentColor"
                  fill="none"
                  stroke-width="3"
                  stroke-linecap="round"
                  stroke-dasharray="0,0,0,200"
                  class="animate-draw"
                />
              </svg> </span
          ></span>
        </h1>

        <p
          class="mt-6 max-w-2xl mx-auto text-lg text-gray-300 sm:text-xl md:mt-8 md:text-2xl animate-fade-in-up animation-delay-100"
        >
          Connect with top employers or discover talented candidates with our
          innovative CV swapping platform.
        </p>

        <div
          class="mt-10 flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up animation-delay-200"
        >
          <router-link
            to="/app/discover"
            class="group relative px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:opacity-90 transition-all transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-purple-500/30 flex items-center justify-center"
          >
            <span>Create Your Visual CV</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform duration-200"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </router-link>
          <router-link
            to="/app/discover?role=recruiter"
            class="px-8 py-4 bg-white/5 border border-white/10 text-white font-medium rounded-lg hover:bg-white/10 transition-all transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/5 flex items-center justify-center"
          >
            <span>Find Candidates</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="ml-2 h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z"
              />
            </svg>
          </router-link>
        </div>

        <div
          class="mt-16 flex items-center justify-center space-x-2 text-sm text-gray-400 animate-fade-in-up animation-delay-300"
        >
          <div class="flex -space-x-2">
            <img
              class="w-8 h-8 rounded-full border-2 border-gray-800"
              src="https://randomuser.me/api/portraits/women/44.jpg"
              alt="User 1"
            />
            <img
              class="w-8 h-8 rounded-full border-2 border-gray-800"
              src="https://randomuser.me/api/portraits/men/32.jpg"
              alt="User 2"
            />
            <img
              class="w-8 h-8 rounded-full border-2 border-gray-800"
              src="https://randomuser.me/api/portraits/women/68.jpg"
              alt="User 3"
            />
          </div>
          <span
            >Join
            <span class="font-semibold text-purple-300">10,000+</span>
            professionals who found their dream job</span
          >
        </div>
      </div>

      <!-- Hero Image/Animation -->
      <div class="mt-24 relative animate-float-slow">
        <div
          class="relative z-10 max-w-5xl mx-auto bg-gradient-to-br from-purple-900/30 to-indigo-900/30 backdrop-blur-sm rounded-3xl p-1 shadow-2xl shadow-purple-900/20"
        >
          <div
            class="rounded-2xl overflow-hidden border border-white/10 bg-gradient-to-br from-gray-900 to-gray-800"
          >
            <img
              src="@/assets/images/hero-dashboard.svg"
              alt="CV Swap Dashboard Preview"
              class="w-full h-auto"
              loading="lazy"
            />
          </div>
        </div>

        <!-- Floating elements -->
        <div
          class="absolute -top-10 -left-10 w-20 h-20 bg-purple-600/30 rounded-full filter blur-xl animate-pulse"
        ></div>
        <div
          class="absolute -bottom-5 -right-5 w-32 h-32 bg-indigo-600/20 rounded-full filter blur-xl animate-pulse animation-delay-2000"
        ></div>

        <!-- Decorative elements -->
        <div
          class="absolute -top-16 right-1/4 w-12 h-12 bg-yellow-400/10 rounded-full flex items-center justify-center text-yellow-400/30"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
            />
          </svg>
        </div>
        <div
          class="absolute bottom-10 left-1/4 w-8 h-8 bg-green-400/10 rounded-full flex items-center justify-center text-green-400/30"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section
      id="stats"
      class="py-16 bg-gradient-to-b from-gray-900 to-gray-900/95 relative overflow-hidden"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div
            v-for="(stat, index) in stats"
            :key="index"
            class="gradient-card p-6 rounded-xl border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/10"
          >
            <div
              class="text-4xl font-bold text-purple-400 mb-2"
              v-text="stat.value"
            ></div>
            <div class="text-sm font-medium text-gray-400">
              {{ stat.label }}
            </div>
          </div>
        </div>
      </div>

      <!-- Decorative elements -->
      <div
        class="absolute -top-20 -right-20 w-40 h-40 bg-purple-600/10 rounded-full filter blur-3xl"
      ></div>
      <div
        class="absolute -bottom-10 -left-10 w-60 h-60 bg-indigo-600/10 rounded-full filter blur-3xl"
      ></div>
    </section>

    <!-- Features Section -->
    <section
      id="features"
      class="py-20 bg-gray-900/50 relative overflow-hidden"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
          <span
            class="inline-block px-4 py-1 text-sm font-semibold text-purple-300 bg-purple-900/30 rounded-full mb-4 border border-purple-800/50"
          >
            Powerful Features
          </span>
          <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-4">
            Everything You Need to
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-300"
              >Succeed</span
            >
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our platform is packed with powerful tools to help you find the
            perfect match
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="(feature, index) in features"
            :key="index"
            class="group p-8 bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/10"
          >
            <div
              class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform"
            >
              <component :is="feature.icon" class="w-7 h-7 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">
              {{ feature.title }}
            </h3>
            <p class="text-gray-300">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section
      id="how-it-works"
      class="py-20 bg-gray-900/80 relative overflow-hidden"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
          <span
            class="inline-block px-4 py-1 text-sm font-semibold text-purple-300 bg-purple-900/30 rounded-full mb-4 border border-purple-800/50"
          >
            Simple Process
          </span>
          <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-4">
            How It
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-300"
              >Works</span
            >
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Get started in minutes and discover your next career opportunity or
            top talent
          </p>
        </div>

        <div class="relative">
          <!-- Timeline -->
          <div
            class="hidden md:block absolute left-1/2 top-0 h-full w-0.5 bg-gradient-to-b from-purple-500/30 to-transparent"
          ></div>

          <!-- Steps -->
          <div class="space-y-16 md:space-y-24">
            <div
              v-for="(step, index) in steps"
              :key="index"
              class="relative flex flex-col md:flex-row items-center md:items-start gap-8 group"
            >
              <!-- Step number -->
              <div
                class="flex-shrink-0 w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-purple-800 flex items-center justify-center text-white text-xl font-bold shadow-lg border-4 border-gray-900 relative z-10"
              >
                {{ index + 1 }}
              </div>

              <!-- Step content -->
              <div
                class="flex-1 md:pt-2 text-center md:text-left"
                :class="
                  index % 2 === 0 ? 'md:pr-16' : 'md:pl-16 md:order-first'
                "
              >
                <h3 class="text-2xl font-bold text-white mb-3">
                  {{ step.title }}
                </h3>
                <p class="text-gray-300">{{ step.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Decorative elements -->
      <div
        class="absolute -top-20 left-1/4 w-40 h-40 bg-purple-600/10 rounded-full filter blur-3xl"
      ></div>
      <div
        class="absolute bottom-0 right-1/4 w-60 h-60 bg-indigo-600/10 rounded-full filter blur-3xl"
      ></div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-900/50 relative overflow-hidden">
      <div
        class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-900/20 to-transparent opacity-50"
      ></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
          <span
            class="inline-block px-4 py-1 text-sm font-semibold text-purple-300 bg-purple-900/30 rounded-full mb-4"
          >
            Simple Process
          </span>
          <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-4">
            How It
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-300"
              >Works</span
            >
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Get started in minutes and discover your next career opportunity or
            top talent
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="(feature, index) in features"
            :key="index"
            class="p-8 bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/10"
          >
            <div
              class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center mb-6 shadow-lg"
            >
              <component :is="feature.icon" class="w-7 h-7 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">
              {{ feature.title }}
            </h3>
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
          <div
            v-for="(testimonial, index) in testimonials"
            :key="index"
            class="p-6 bg-white rounded-xl shadow-sm"
          >
            <div class="flex items-center mb-4">
              <img
                :src="testimonial.avatar"
                :alt="testimonial.name"
                class="w-12 h-12 rounded-full mr-4"
              />
              <div>
                <h4 class="font-semibold text-gray-900">
                  {{ testimonial.name }}
                </h4>
                <p class="text-sm text-gray-500">{{ testimonial.role }}</p>
              </div>
            </div>
            <p class="text-gray-600 italic">"{{ testimonial.quote }}"</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section
      class="py-20 bg-gradient-to-r from-purple-900/80 to-purple-800/80 relative overflow-hidden"
    >
      <div
        class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0VjIwYTQgNCAwIDEwLTggMHYxNGE0IDQgMCAxMDggMHptLTI4IDJjLTIuMjEgMC00IDEuNzkxLTQgNCAwIDIuMjA5IDEuNzkxIDQgNCA0czQtMS43OTEgNC00YzAtMi4yMDktMS43OS00LTQtNHptNDAgMGMtMi4yMSAwLTQgMS43OTEtNCA0IDAgMi4yMDkgMS43OSA0IDQgNHM0LTEuNzkxIDQtNGMwLTIuMjA5LTEuNzktNC00LTR6Ii8+PC9nPjwvZz48L3N2Zz4=')]"
      ></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <h2 class="text-4xl font-extrabold text-white sm:text-5xl mb-6">
          Ready to transform your
          <span
            class="text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-white"
            >career search</span
          >?
        </h2>
        <p class="text-xl text-purple-100 mb-10 max-w-3xl mx-auto">
          Join thousands of professionals who found their dream job through CV
          Swap
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <router-link
            to="/register"
            class="group relative px-8 py-4 bg-white text-purple-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-purple-500/30 flex items-center justify-center"
          >
            <span>Sign Up Free</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform duration-200"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </router-link>
          <router-link
            to="/login"
            class="px-8 py-4 border-2 border-white/80 text-white font-medium rounded-lg hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10 flex items-center justify-center"
          >
            <span>Log In</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="ml-2 h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                clip-rule="evenodd"
              />
            </svg>
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import {
  UserCircleIcon,
  BriefcaseIcon,
  ChatBubbleLeftRightIcon,
  ChartBarIcon,
  DocumentTextIcon,
  BellIcon,
  ShieldCheckIcon,
  RocketLaunchIcon,
  UserPlusIcon,
  MagnifyingGlassIcon,
  HandThumbUpIcon,
} from "@heroicons/vue/24/outline";

export default {
  name: "LandingPage",
  components: {
    UserCircleIcon,
    BriefcaseIcon,
    ChatBubbleLeftRightIcon,
    ChartBarIcon,
    DocumentTextIcon,
    BellIcon,
    ShieldCheckIcon,
    RocketLaunchIcon,
    UserPlusIcon,
    MagnifyingGlassIcon,
    HandThumbUpIcon,
  },
  data() {
    return {
      darkMode: "class",
      stats: [
        { value: "10K+", label: "Active Users" },
        { value: "5K+", label: "Jobs Posted" },
        { value: "95%", label: "Success Rate" },
        { value: "24/7", label: "Support" },
      ],
      features: [
        {
          icon: "DocumentTextIcon",
          title: "Visual CV Builder",
          description:
            "Create stunning, professional CVs with our easy-to-use builder and customizable templates.",
        },
        {
          icon: "MagnifyingGlassIcon",
          title: "Smart Matching",
          description:
            "Our advanced algorithm connects you with the most relevant job opportunities or candidates.",
        },
        {
          icon: "ChatBubbleLeftRightIcon",
          title: "Direct Messaging",
          description:
            "Communicate directly with potential employers or candidates in real-time.",
        },
        {
          icon: "ChartBarIcon",
          title: "Analytics Dashboard",
          description:
            "Track your profile views, application status, and engagement metrics.",
        },
        {
          icon: "BellIcon",
          title: "Job Alerts",
          description:
            "Get instant notifications for new job postings that match your profile.",
        },
        {
          icon: "ShieldCheckIcon",
          title: "Privacy Controls",
          description:
            "Control who can see your profile and personal information with granular privacy settings.",
        },
      ],
      steps: [
        {
          title: "Create Your Profile",
          description:
            "Sign up and build your professional profile with your skills, experience, and career goals.",
        },
        {
          title: "Upload Your CV",
          description:
            "Easily import your existing CV or create a new one with our intuitive builder.",
        },
        {
          title: "Set Your Preferences",
          description:
            "Tell us what you're looking for in your next role or the type of candidates you want to hire.",
        },
        {
          title: "Start Matching",
          description:
            "Browse through curated matches and connect with potential employers or candidates.",
        },
      ],
    };
  },
  mounted() {
    // Check for saved theme preference or use system preference
    if (
      localStorage.theme === "dark" ||
      (!("theme" in localStorage) &&
        window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
      this.darkMode = true;
      document.documentElement.classList.add("dark");
    } else {
      this.darkMode = false;
      document.documentElement.classList.remove("dark");
    }

    // Add scroll reveal animations
    this.setupScrollAnimations();
  },
  methods: {
    toggleTheme() {
      this.darkMode = !this.darkMode;
      if (this.darkMode) {
        localStorage.theme = "dark";
        document.documentElement.classList.add("dark");
      } else {
        localStorage.theme = "light";
        document.documentElement.classList.remove("dark");
      }
    },

    setupScrollAnimations() {
      const animateOnScroll = () => {
        // Your animation logic
      };

      // Initial check
      animateOnScroll();

      // Listen for scroll events
      window.addEventListener("scroll", animateOnScroll);

      // Store the handler reference for cleanup
      this.scrollHandler = animateOnScroll;
    },
  },
  beforeUnmount() {
    // Cleanup
    if (this.scrollHandler) {
      window.removeEventListener("scroll", this.scrollHandler);
    }
  },

  scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: "smooth" });
    }
  },
};
</script>

<style scoped>
  .gradient-card {
    @apply bg-gradient-to-br from-gray-900/80 to-gray-800/90;
    background: linear-gradient(145deg, hsl(240 10% 7% / 0.8), hsl(240 10% 10% / 0.9));
  }
</style>