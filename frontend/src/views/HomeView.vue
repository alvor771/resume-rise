<template>
  <div
    class="bg-gradient-to-b from-white to-indigo-50 dark:from-gray-900 dark:to-gray-800 scroll-smooth"
  >
    <!-- Hero Section -->
    <section class="relative overflow-hidden pb-20 md:pb-20">
      <!-- Animated background elements -->
      <div class="absolute inset-0 z-0 overflow-hidden">
        <div
          class="absolute inset-0 bg-gradient-to-r from-indigo-900/50 to-purple-900/50 dark:from-indigo-900/70 dark:to-purple-900/70"
        ></div>
        <div
          class="absolute inset-0 bg-[url('@/assets/images/grid.svg')] opacity-10"
        ></div>
        <!-- Floating elements -->
        <div
          class="absolute top-1/4 left-1/4 w-64 h-64 bg-purple-500/10 rounded-full filter blur-3xl animate-float-slow"
        ></div>
        <div
          class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-indigo-500/10 rounded-full filter blur-3xl animate-float-medium"
        ></div>
        <div
          class="absolute top-2/3 right-1/3 w-40 h-40 bg-blue-500/10 rounded-full filter blur-3xl animate-float-fast"
        ></div>
      </div>

      <div
        class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 pb-40 md:pb-48"
      >
        <!-- Decorative elements -->
        <div
          class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"
        ></div>
        <div
          class="absolute bottom-0 left-1/2 -translate-x-1/2 w-3/4 h-1 bg-gradient-to-r from-transparent via-white/20 to-transparent"
        ></div>

        <div class="relative z-10 text-center">
          <!-- Animated text effect -->
          <div
            class="inline-block mb-6 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 animate-fade-in"
          >
            <span class="text-sm font-medium text-white/90 tracking-wider"
              >🚀 {{ $t("home.hero.badge") || "Создано с любовью" }}</span
            >
          </div>
          <h1
            class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight"
          >
            <span class="relative inline-block">
              <span
                class="absolute -inset-4 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-2xl -z-10 blur-xl opacity-70 group-hover:opacity-100 transition-opacity duration-300"
              ></span>
              <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-white via-indigo-100 to-indigo-300 relative z-10"
              >
                {{ $t("home.hero.title") }}
              </span>
            </span>
          </h1>
          <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-10 relative">
            <span class="relative z-10">{{ $t("home.hero.subtitle") }}</span>
            <span
              class="absolute -left-8 top-1/2 -translate-y-1/2 w-6 h-px bg-gradient-to-r from-transparent to-white/30"
            ></span>
            <span
              class="absolute -right-8 top-1/2 -translate-y-1/2 w-6 h-px bg-gradient-to-l from-transparent to-white/30"
            ></span>
          </p>
          <!-- Animated stats -->
          <div
            class="flex justify-center gap-8 mb-12 opacity-0 animate-fade-in-up [animation-delay:0.3s] [animation-fill-mode:forwards]"
          >
            <div class="text-center">
              <div class="text-4xl font-bold text-white mb-1">10K+</div>
              <div class="text-sm text-indigo-200/80">Пользователей</div>
            </div>
            <div class="h-12 w-px bg-white/20"></div>
            <div class="text-center">
              <div class="text-4xl font-bold text-white mb-1">50+</div>
              <div class="text-sm text-indigo-200/80">Шаблонов</div>
            </div>
            <div class="h-12 w-px bg-white/20"></div>
            <div class="text-center">
              <div class="text-4xl font-bold text-white mb-1">99.9%</div>
              <div class="text-sm text-indigo-200/80">Успеха</div>
            </div>
          </div>

          <div
            class="flex flex-col sm:flex-row justify-center gap-4 opacity-0 animate-fade-in-up [animation-delay:0.5s] [animation-fill-mode:forwards]"
          >
            <router-link
              to="/cv-generator"
              class="group relative px-8 py-4 backdrop-blur-sm bg-white/10 border border-white/20 rounded-xl shadow-lg hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden"
              @mouseenter="startProgress"
              @mouseleave="resetProgress"
            >
              <span
                class="relative z-10 text-white font-semibold tracking-wide"
                >{{ $t("home.hero.cta") }}</span
              >
              <!-- Glass highlight effect -->
              <span
                class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              ></span>
              <!-- Animated shine effect on hover -->
              <span
                class="absolute -left-full top-0 w-1/2 h-full bg-gradient-to-r from-transparent via-white/40 to-transparent transform -skew-x-12 transition-all duration-1000 ease-in-out group-hover:left-[150%]"
              ></span>
            </router-link>
            <router-link
              to="/templates"
              class="relative px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-1 text-center group overflow-hidden"
            >
              <span class="relative z-10">{{ $t("home.cta.secondary") }}</span>
              <span
                class="absolute inset-0 bg-white/5 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              ></span>
            </router-link>
          </div>
        </div>
      </div>

      <div class="relative -mt-12 md:-mt-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
          <!-- 3D Device Mockup Container -->
          <div class="relative">
            <!-- Device Frame -->
            <div class="relative mx-auto w-full max-w-4xl">
              <!-- Screen -->
              <div
                class="relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl md:rounded-2xl overflow-hidden shadow-2xl border-8 border-gray-800 dark:border-gray-700"
              >
                <!-- Screen Content -->
                <div class="relative h-0 pb-[56.25%] overflow-hidden">
                  <!-- Animated Resume Builder Interface -->
                  <div class="absolute inset-0 p-6 md:p-8 flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                      <div
                        class="w-3/4 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg animate-pulse"
                      ></div>
                      <div class="flex space-x-2">
                        <div
                          class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full"
                        ></div>
                        <div
                          class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full"
                        ></div>
                      </div>
                    </div>

                    <!-- Form Preview -->
                    <div
                      class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-grow"
                    >
                      <!-- Left Sidebar -->
                      <div class="space-y-4">
                        <div
                          class="h-12 bg-gray-100 dark:bg-gray-800 rounded-lg animate-pulse"
                          style="animation-delay: 0.2s"
                        ></div>
                        <div
                          class="h-24 bg-gray-100 dark:bg-gray-800 rounded-lg animate-pulse"
                          style="animation-delay: 0.3s"
                        ></div>
                        <div
                          class="h-16 bg-gray-100 dark:bg-gray-800 rounded-lg animate-pulse"
                          style="animation-delay: 0.4s"
                        ></div>
                      </div>

                      <!-- Main Content -->
                      <div class="md:col-span-2 space-y-4">
                        <div
                          class="h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg w-3/4 animate-pulse"
                          style="animation-delay: 0.2s"
                        ></div>
                        <div
                          class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"
                          style="animation-delay: 0.3s"
                        ></div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                          <div
                            class="h-16 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center"
                          >
                            <div class="text-center">
                              <div
                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mx-auto mb-2"
                              ></div>
                              <div
                                class="h-2 bg-gray-200 dark:bg-gray-700 rounded w-16 mx-auto"
                              ></div>
                            </div>
                          </div>
                          <div
                            class="h-16 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center"
                          >
                            <div class="text-center">
                              <div
                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mx-auto mb-2"
                              ></div>
                              <div
                                class="h-2 bg-gray-200 dark:bg-gray-700 rounded w-16 mx-auto"
                              ></div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="h-32 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg mt-4 flex items-center justify-center"
                        >
                          <div class="text-center p-4">
                            <div
                              class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mx-auto mb-3"
                            ></div>
                            <div
                              class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-32 mx-auto mb-2"
                            ></div>
                            <div
                              class="h-2 bg-gray-200 dark:bg-gray-700 rounded w-24 mx-auto"
                            ></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-6">
                      <div
                        class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden"
                      >
                        <div
                          class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-500 ease-out"
                          :style="{ width: `${progress}%` }"
                        ></div>
                      </div>
                      <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400"
                          >{{ progress }}% {{ $t("common.complete") }}</span
                        >
                        <span
                          class="text-xs text-indigo-500 dark:text-indigo-400 font-medium transition-colors duration-300"
                        >
                          {{ $t("common.next") }}: {{ $t("common.preview") }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Simple Bottom Bezel -->
              <div
                class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-[35%] h-[6px] bg-gray-800 dark:bg-gray-900 rounded-b-md"
              ></div>
            </div>

            <!-- Floating Elements -->
            <div
              class="absolute -top-8 -right-8 w-32 h-32 bg-purple-500/10 rounded-full filter blur-2xl opacity-70 animate-float-slow"
            ></div>
            <div
              class="absolute -bottom-12 -left-12 w-40 h-40 bg-indigo-500/10 rounded-full filter blur-2xl opacity-70 animate-float-medium"
            ></div>

            <!-- Decorative Elements -->
            <div
              class="absolute top-1/4 -left-8 w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl transform -rotate-12"
            ></div>
            <div
              class="absolute bottom-1/4 -right-8 w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl transform rotate-12"
            ></div>
          </div>

          <!-- Feature Highlights -->
          <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div
              class="p-4 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10"
            >
              <div
                class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mx-auto mb-3"
              >
                <svg
                  class="w-6 h-6 text-indigo-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                  ></path>
                </svg>
              </div>
              <h4 class="text-sm font-medium text-white/90">
                {{ $t("home.features.templates") }}
              </h4>
            </div>
            <div
              class="p-4 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10"
            >
              <div
                class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mx-auto mb-3"
              >
                <svg
                  class="w-6 h-6 text-indigo-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  ></path>
                </svg>
              </div>
              <h4 class="text-sm font-medium text-white/90">
                {{ $t("home.features.customization") }}
              </h4>
            </div>
            <div
              class="p-4 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10"
            >
              <div
                class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mx-auto mb-3"
              >
                <svg
                  class="w-6 h-6 text-indigo-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                  ></path>
                </svg>
              </div>
              <h4 class="text-sm font-medium text-white/90">
                {{ $t("home.features.tracking") }}
              </h4>
            </div>
            <div
              class="p-4 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10"
            >
              <div
                class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mx-auto mb-3"
              >
                <svg
                  class="w-6 h-6 text-indigo-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  ></path>
                </svg>
              </div>
              <h4 class="text-sm font-medium text-white/90">
                {{ $t("home.features.security") }}
              </h4>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white dark:bg-gray-900 relative overflow-hidden">
      <!-- Градиентные акценты -->
      <div
        class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl dark:bg-indigo-400/10"
      ></div>
      <div
        class="absolute -left-20 -bottom-20 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl dark:bg-purple-400/10"
      ></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
          <span
            class="inline-block px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold mb-4"
          >
            {{ $t("home.features.subtitle") }}
          </span>
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $t("home.features.title") }}
          </h2>
          <div class="relative inline-block">
            <div
              class="w-20 h-1 bg-indigo-200 dark:bg-indigo-900/50 rounded-full overflow-hidden"
            >
              <div
                class="h-full bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full"
              ></div>
            </div>
          </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8 relative">
          <div
            v-for="(feature, index) in features"
            :key="index"
            class="group relative p-6 rounded-2xl bg-white dark:bg-gray-800/50 backdrop-blur-sm border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-700/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg overflow-hidden"
          >
            <!-- Animated shine effect -->
            <div class="shine-overlay"></div>

            <!-- Gradient highlight on hover -->
            <div
              class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/30 dark:from-indigo-900/5 dark:to-purple-900/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>

            <!-- Animated border gradient -->
            <div
              class="absolute inset-0 rounded-2xl p-px bg-gradient-to-r from-indigo-400/0 via-indigo-400/30 to-purple-400/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
            ></div>

            <div class="relative z-10">
              <!-- Icon with gradient background -->
              <div
                class="w-14 h-14 mb-6 rounded-xl bg-gradient-to-br from-indigo-100 to-indigo-50 dark:from-indigo-900/30 dark:to-indigo-900/10 flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-300"
              >
                <div
                  class="w-12 h-12 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-lg flex items-center justify-center border border-gray-100 dark:border-gray-700/50 group-hover:shadow-inner transition-all duration-300"
                >
                  <component
                    :is="feature.icon"
                    class="w-6 h-6 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300"
                  />
                </div>
              </div>

              <!-- Title with gradient text -->
              <h3
                class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-500 dark:from-indigo-400 dark:to-purple-300 mb-3"
              >
                {{ $t(`home.features.${feature.key}.title`) }}
              </h3>

              <!-- Description text -->
              <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ $t(`home.features.${feature.key}.description`) }}
              </p>

              <!-- Animated read more link -->
              <div
                class="mt-4 flex items-center text-indigo-600 dark:text-indigo-400 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 font-medium text-sm transition-colors duration-300"
              >
                <span>{{ $t("common.learnMore") }}</span>
                <svg
                  class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform duration-300"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  ></path>
                </svg>
              </div>
            </div>

            <!-- Subtle background pattern -->
            <div
              class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-indigo-400/5 dark:bg-indigo-200/5 transform scale-0 group-hover:scale-100 transition-transform duration-500"
            ></div>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section
      class="relative py-20 overflow-hidden bg-gradient-to-br from-indigo-50/30 to-purple-50/20 dark:from-indigo-900/10 dark:to-purple-900/5"
    >
      <!-- Мягкие размытые круги -->
      <div class="absolute inset-0 overflow-hidden">
        <div
          class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-purple-200/30 dark:bg-purple-900/20 blur-3xl"
        ></div>
        <div
          class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-indigo-200/30 dark:bg-indigo-900/20 blur-3xl"
        ></div>
      </div>

      <!-- Тонкая сетка -->
      <div class="absolute inset-0 opacity-20 dark:opacity-10 bg-grid"></div>
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
          <span
            class="inline-block px-4 py-2 bg-indigo-100/80 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold mb-4 backdrop-blur-sm border border-white/20"
          >
            {{ $t("home.howItWorks.subtitle") }}
          </span>
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $t("home.howItWorks.title") }}
          </h2>
          <div
            class="w-20 h-1 bg-indigo-200 dark:bg-indigo-900/50 rounded-full mx-auto overflow-hidden"
          >
            <div
              class="h-full bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full"
            ></div>
          </div>
        </div>

        <div class="relative">
          <!-- Горизонтальная линия для десктопов -->
          <div
            class="hidden md:block absolute top-24 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-gray-200 to-transparent dark:via-gray-700 z-0"
          ></div>

          <div class="grid md:grid-cols-4 gap-8 relative z-10">
            <div
              v-for="(step, index) in steps"
              :key="index"
              class="group relative p-6 rounded-2xl backdrop-blur-sm bg-white/70 dark:bg-gray-800/50 border border-white/30 dark:border-gray-700/30 shadow-sm transition-all duration-300 hover:-translate-y-1 overflow-hidden hover:!border-indigo-500 hover:border-2"
            >
              <div class="flex flex-col items-center text-center h-full">
                <!-- Step number in a glass circle -->
                <div
                  class="w-16 h-16 mb-6 rounded-full backdrop-blur-sm bg-white/80 dark:bg-gray-800/80 border border-white/50 dark:border-gray-700/50 shadow-sm group-hover:shadow-md flex items-center justify-center text-2xl font-bold text-indigo-600 dark:text-indigo-400 transition-all duration-300 group-hover:scale-110 group-hover:bg-white/90 dark:group-hover:bg-gray-800/90"
                >
                  <span
                    class="group-hover:scale-110 transition-transform duration-300"
                    >{{ index + 1 }}</span
                  >
                </div>

                <!-- Step title with gradient effect on hover -->
                <h3
                  class="text-lg font-semibold text-gray-800 dark:text-white mb-3 group-hover:bg-clip-text group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-purple-500 transition-all duration-300"
                >
                  {{ $t(`home.howItWorks.steps.${step.key}.title`) }}
                </h3>

                <!-- Описание шага -->
                <p class="text-gray-600 dark:text-gray-400 text-sm flex-grow">
                  {{ $t(`home.howItWorks.steps.${step.key}.description`) }}
                </p>

                <!-- Иконка для мобильных -->
                <div class="mt-4 md:hidden">
                  <svg
                    v-if="index < steps.length - 1"
                    class="w-6 h-6 text-gray-300 dark:text-gray-600 mx-auto"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 14l-7 7m0 0l-7-7m7 7V3"
                    ></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $t("home.testimonials.title") }}
          </h2>
          <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            {{ $t("home.testimonials.subtitle") }}
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div
            v-for="(testimonial, index) in testimonials"
            :key="index"
            class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 border border-gray-100 dark:border-gray-700"
          >
            <div class="flex items-center mb-6">
              <div
                class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xl mr-4"
              >
                {{ testimonial.name.charAt(0) }}
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">
                  {{ testimonial.name }}
                </h4>
                <p class="text-indigo-600 dark:text-indigo-400 text-sm">
                  {{ testimonial.role }}
                </p>
              </div>
            </div>
            <p class="text-gray-600 dark:text-gray-400 italic">
              "{{ $t(`home.testimonials.${testimonial.key}.quote`) }}"
            </p>
            <div class="flex mt-4 text-yellow-400">
              <svg
                v-for="i in 5"
                :key="i"
                class="w-5 h-5"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Popular Templates Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $t("home.templates.title") }}
          </h2>
          <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            {{ $t("home.templates.subtitle") }}
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            v-for="(template, index) in templates"
            :key="index"
            class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-700/50 overflow-hidden"
          >
            <div
              class="h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
            >
              <DocumentTextIcon
                class="w-16 h-16 text-gray-400 group-hover:text-indigo-500 transition-colors duration-300"
              />
            </div>
            <div class="p-6">
              <h3
                class="text-xl font-semibold text-gray-900 dark:text-white mb-2"
              >
                {{ template.name }}
              </h3>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                {{ template.description }}
              </p>
              <div class="flex justify-between items-center">
                <span class="text-indigo-600 dark:text-indigo-400 font-medium">
                  {{ template.category }}
                </span>
                <router-link
                  :to="`/templates/${template.id}`"
                  class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium flex items-center"
                >
                  {{ $t("common.view") }}
                  <ArrowRightIcon class="w-4 h-4 ml-1" />
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section
      class="py-20 bg-gray-50 dark:bg-gray-800/30 relative overflow-hidden"
    >
      <div class="absolute inset-0 overflow-hidden">
        <div
          class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-purple-200/30 dark:bg-purple-900/20 blur-3xl"
        ></div>
        <div
          class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-indigo-200/30 dark:bg-indigo-900/20 blur-3xl"
        ></div>
      </div>
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $t("home.faq.title") }}
          </h2>
          <p class="text-xl text-gray-600 dark:text-gray-300">
            {{ $t("home.faq.subtitle") }}
          </p>
        </div>
        <div class="space-y-4">
          <div
            v-for="(item, index) in faqItems"
            :key="index"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden"
          >
            <button
              @click="toggleFaq(index)"
              class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none"
              :aria-expanded="activeFaq === index"
            >
              <span class="text-lg font-medium text-gray-900 dark:text-white">{{
                item.question
              }}</span>
              <ChevronDownIcon
                class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200"
                :class="{ 'rotate-180': activeFaq === index }"
              />
            </button>
            <div
              v-show="activeFaq === index"
              class="px-6 pb-4 pt-0 text-gray-600 dark:text-gray-300"
              v-html="item.answer"
            ></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
            {{ $t("home.benefits.title") }}
          </h2>
          <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            {{ $t("home.benefits.subtitle") }}
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            v-for="(benefit, index) in benefits"
            :key="index"
            class="text-center p-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-colors duration-300"
          >
            <div
              class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/20 rounded-xl flex items-center justify-center mx-auto mb-6"
            >
              <component
                :is="benefit.icon"
                class="w-8 h-8 text-indigo-600 dark:text-indigo-400"
              />
            </div>
            <h3
              class="text-xl font-semibold text-gray-900 dark:text-white mb-3"
            >
              {{ $t(`home.benefits.items.${benefit.key}.title`) }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
              {{ $t(`home.benefits.items.${benefit.key}.description`) }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section
      class="relative py-24 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 overflow-hidden"
    >
      <!-- Fade out effect at the bottom -->
      <div
        class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white dark:from-gray-900 to-transparent opacity-10 pointer-events-none"
      ></div>
      <!-- Decorative elements -->
      <div class="absolute inset-0 overflow-hidden">
        <div
          class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"
        ></div>
        <div
          class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-white/5 blur-3xl"
        ></div>
        <div class="absolute inset-0">
          <svg width="100%" height="100%" style="opacity: 0.1">
            <defs>
              <pattern
                id="grid-pattern"
                width="40"
                height="40"
                patternUnits="userSpaceOnUse"
              >
                <path
                  d="M 40 0 L 0 0 0 40"
                  fill="none"
                  stroke="white"
                  stroke-width="1"
                />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)" />
          </svg>
        </div>
      </div>

      <div class="relative max-w-5xl mx-auto px-6 text-center">
        <span
          class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm text-white/90 rounded-full text-sm font-medium mb-6"
        >
          {{ $t("home.ctaSection.badge", "Начните бесплатно") }}
        </span>
        <h2
          class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight"
        >
          {{ $t("home.ctaSection.title") }}
        </h2>
        <p
          class="text-xl text-white/80 mb-10 max-w-2xl mx-auto leading-relaxed"
        >
          {{ $t("home.ctaSection.subtitle") }}
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <router-link
            to="/register"
            class="group relative px-8 py-4 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl overflow-hidden"
          >
            <span class="relative z-10">{{ $t("home.cta.primary") }}</span>
            <div
              class="absolute inset-0 bg-gradient-to-r from-indigo-100 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>
          </router-link>
          <router-link
            to="/templates"
            class="group relative px-8 py-4 bg-transparent border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 hover:border-white/50 transition-all duration-300 transform hover:-translate-y-1"
          >
            <span class="relative z-10">{{ $t("home.cta.secondary") }}</span>
            <div
              class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>
          </router-link>
        </div>

        <!-- Trust badges -->
        <div
          class="mt-12 flex flex-wrap items-center justify-center gap-6 opacity-80"
        >
          <div class="flex items-center text-white/80 text-sm">
            <svg
              class="w-5 h-5 mr-2 text-green-400"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            {{ $t("home.cta.benefit1", "Без подписок") }}
          </div>
          <div class="flex items-center text-white/80 text-sm">
            <svg
              class="w-5 h-5 mr-2 text-green-400"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            {{ $t("home.cta.benefit2", "Безопасность данных") }}
          </div>
          <div class="flex items-center text-white/80 text-sm">
            <svg
              class="w-5 h-5 mr-2 text-green-400"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            {{ $t("home.cta.benefit3", "Доступно на всех устройствах") }}
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Keyframes */
@keyframes shine {
  0% {
    transform: translateX(-100%) skew(-12deg);
  }
  20% {
    transform: translateX(100%) skew(-12deg);
  }
  100% {
    transform: translateX(100%) skew(-12deg);
  }
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes float-slow {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-20px);
  }
}

@keyframes float-medium {
  0%,
  100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-15px);
  }
}

/* Animation classes */
.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-slow {
  animation: float-slow 8s ease-in-out infinite;
}

.animate-float-medium {
  animation: float-medium 7s ease-in-out infinite;
  animation-delay: 0.5s;
}

.shine-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 1rem;
  overflow: hidden;
}

.shine-overlay::after {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  right: -50%;
  bottom: -50%;
  background: linear-gradient(
    to bottom right,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 0) 35%,
    rgba(255, 255, 255, 0.4) 45%,
    rgba(255, 255, 255, 0.7) 50%,
    rgba(255, 255, 255, 0.4) 55%,
    rgba(255, 255, 255, 0) 65%,
    rgba(255, 255, 255, 0) 100%
  );
  transform: rotateZ(45deg) translate(-50%, -50%);
  transform-origin: 0 0;
  animation: shine 6s infinite;
  opacity: 0;
  transition: opacity 0.6s ease;
}

.group:hover .shine-overlay::after {
  opacity: 0.9;
  animation: shine 6s infinite;
}

@keyframes shine {
  0% {
    transform: rotateZ(45deg) translate(-50%, -50%) translateX(-100%)
      translateY(-100%);
  }
  20% {
    transform: rotateZ(45deg) translate(-50%, -50%) translateX(100%)
      translateY(100%);
  }
  100% {
    transform: rotateZ(45deg) translate(-50%, -50%) translateX(100%)
      translateY(100%);
  }
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out forwards;
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

/* Keyframes for floating animation */
@keyframes float-slow {
  0%,
  100% {
    transform: translateY(0) translateX(0);
  }
  50% {
    transform: translateY(-20px) translateX(10px);
  }
}

@keyframes float-medium {
  0%,
  100% {
    transform: translateY(0) translateX(0);
  }
  50% {
    transform: translateY(-15px) translateX(-15px);
  }
}

@keyframes float-fast {
  0%,
  100% {
    transform: translateY(0) translateX(0);
  }
  50% {
    transform: translateY(-10px) translateX(10px);
  }
}

.animate-float-slow {
  animation: float-slow 8s ease-in-out infinite;
}

.animate-float-medium {
  animation: float-medium 10s ease-in-out infinite;
  animation-delay: 1s;
}

.animate-float-fast {
  animation: float-fast 12s ease-in-out infinite;
  animation-delay: 0.5s;
}

/* Hover effects */
.feature-card {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  transform: translateY(0);
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.feature-card::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    135deg,
    rgba(224, 231, 255, 0.3) 0%,
    rgba(233, 213, 255, 0.3) 100%
  );
  opacity: 0;
  transition: opacity 0.3s ease;
}

.dark .feature-card::before {
  background: linear-gradient(
    135deg,
    rgba(67, 56, 202, 0.1) 0%,
    rgba(124, 58, 237, 0.1) 100%
  );
}

.feature-card:hover::before {
  opacity: 1;
}

/* Стили компонента */
.bg-grid {
  background-image: linear-gradient(
      to right,
      rgb(99 102 241 / 0.1) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgb(99 102 241 / 0.1) 1px, transparent 1px);
  background-size: 40px 40px;
  background-position: center center;
}

.dark .bg-grid {
  background-image: linear-gradient(
      to right,
      rgb(129 140 248 / 0.1) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgb(129 140 248 / 0.1) 1px, transparent 1px);
}
</style>

<script>
import { defineComponent, ref } from "vue";
import GradientBorder from "@/components/ui/GradientBorder.vue";
import {
  DocumentTextIcon,
  ArrowRightIcon,
  ChevronDownIcon,
  // Иконки для преимуществ
  ShieldCheckIcon,
  ClockIcon,
  DeviceMobileIcon,
  CloudArrowDownIcon,
  EyeIcon,
  PencilSquareIcon,
  // Иконки для фич
  RectangleGroupIcon,
  BoltIcon,
  UserGroupIcon,
  ChartBarIcon,
  DevicePhoneMobileIcon,
} from "@heroicons/vue/24/outline";

export default defineComponent({
  name: "HomeView",
  components: {
    GradientBorder,
  },
  setup() {
    // Состояние для аккордеона FAQ
    const activeFaq = ref(null);
    const progress = ref(0);
    let progressInterval = null;

    const toggleFaq = (index) => {
      activeFaq.value = activeFaq.value === index ? null : index;
    };

    const startProgress = () => {
      let current = 0;
      clearInterval(progressInterval);

      progressInterval = setInterval(() => {
        current += 5;
        if (current <= 100) {
          progress.value = current;
        } else {
          clearInterval(progressInterval);
        }
      }, 50);
    };

    const resetProgress = () => {
      clearInterval(progressInterval);
      progress.value = 0;
    };

    // Данные для секции шаблонов
    const templates = [
      {
        id: "professional",
        name: "Professional",
        description: "Чистый и современный дизайн для любого уровня опыта",
        category: "Универсальный",
      },
      {
        id: "creative",
        name: "Creative",
        description: "Яркий дизайн для творческих профессий",
        category: "Креативный",
      },
      {
        id: "minimal",
        name: "Minimal",
        description: "Минималистичный и элегантный стиль",
        category: "Минимализм",
      },
    ];

    // Данные для FAQ
    const faqItems = [
      {
        question: "Как начать создавать резюме?",
        answer:
          "Просто зарегистрируйтесь, выберите шаблон и начните заполнять информацию. Наш конструктор подскажет вам на каждом этапе.",
      },
      {
        question: "Можно ли изменить шаблон после создания?",
        answer:
          "Да, вы можете в любой момент изменить шаблон, и все ваши данные будут автоматически перенесены в новый дизайн.",
      },
      {
        question: "Доступно ли резюме оффлайн?",
        answer:
          "После сохранения вы можете скачать резюме в формате PDF и использовать его оффлайн.",
      },
      {
        question: "Есть ли мобильное приложение?",
        answer:
          "Наш сервис полностью адаптивен и отлично работает на мобильных устройствах через браузер.",
      },
    ];

    // Данные для секции преимуществ
    const benefits = [
      { key: "professional", icon: ShieldCheckIcon },
      { key: "easy", icon: ClockIcon },
      { key: "mobile", icon: DeviceMobileIcon },
      { key: "download", icon: CloudArrowDownIcon },
      { key: "preview", icon: EyeIcon },
      { key: "customizable", icon: PencilSquareIcon },
    ];

    const features = [
      {
        key: "templates",
        icon: RectangleGroupIcon,
      },
      {
        key: "easyToUse",
        icon: BoltIcon,
      },
      {
        key: "professional",
        icon: DocumentTextIcon,
      },
      {
        key: "collaboration",
        icon: UserGroupIcon,
      },
      {
        key: "analytics",
        icon: ChartBarIcon,
      },
      {
        key: "mobileFriendly",
        icon: DevicePhoneMobileIcon,
      },
    ];

    const steps = [
      { key: "signup" },
      { key: "selectTemplate" },
      { key: "customize" },
      { key: "download" },
    ];

    const testimonials = [
      {
        key: "alex",
        name: "Alex Johnson",
        role: "UX Designer",
      },
      {
        key: "sarah",
        name: "Sarah Miller",
        role: "Marketing Manager",
      },
      {
        key: "david",
        name: "David Kim",
        role: "Software Engineer",
      },
    ];

    return {
      progress,
      startProgress,
      resetProgress,
      activeFaq,
      toggleFaq,
      templates,
      faqItems,
      benefits,
      features,
      steps,
      testimonials,
      // Иконки
      DocumentTextIcon,
      ArrowRightIcon,
      ChevronDownIcon,
      ShieldCheckIcon,
      ClockIcon,
      DeviceMobileIcon,
      CloudArrowDownIcon,
      EyeIcon,
      PencilSquareIcon,
    };
  },
});
</script>
