<template>
  <div class="bg-white dark:bg-gray-900">
    <!-- Enhanced Header with Gradient -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 overflow-hidden">
      <!-- Decorative elements -->
      <div class="absolute inset-0 bg-grid-white/5 [mask-image:linear-gradient(to_bottom,transparent,white,transparent)]"></div>
      <div class="absolute -left-1/2 -top-1/2 w-[200%] h-[200%] bg-radial-gradient from-white/10 to-transparent rounded-full"></div>
      
      <div class="relative max-w-7xl mx-auto py-20 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
          <!-- Badge -->
          <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-white/10 text-white/90 backdrop-blur-sm mb-6">
            <svg class="-ml-1 mr-1.5 h-4 w-4 text-indigo-200" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            {{ $t('helpCenter.needMoreHelp') }}
          </div>
          
          <!-- Main heading -->
          <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl font-sans">
            {{ $t('helpCenter.title') }}
          </h1>
          
          <!-- Subtitle -->
          <p class="mt-4 max-w-3xl mx-auto text-xl text-indigo-100/90 leading-relaxed">
            {{ $t('helpCenter.subtitle') }}
          </p>
          
          <!-- Search box -->
          <div class="mt-10 max-w-2xl mx-auto bg-white dark:bg-gray-900/50 backdrop-blur-sm rounded-xl shadow-2xl p-1.5">
            <div class="flex">
              <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input 
                  v-model="searchQuery"
                  type="search" 
                  class="block w-full pl-12 pr-4 py-4 text-base text-gray-900 dark:text-white bg-white/80 dark:bg-gray-900/50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-inner transition-all duration-200 placeholder-gray-500 dark:placeholder-gray-400"
                  :placeholder="$t('helpCenter.searchPlaceholder')"
                  @input="performSearch"
                  autocomplete="off"
                  aria-label="Поиск в справке"
                >
                <button 
                  v-if="searchQuery"
                  @click="searchQuery = ''; searchResults = [];"
                  class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                  type="button"
                  aria-label="Очистить поиск"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button 
                @click="performSearch"
                class="ml-2 inline-flex items-center px-6 py-4 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
              >
                {{ $t('helpCenter.search') }}
              </button>
            </div>
            
            <!-- Suggested searches -->
            <div v-if="!searchQuery" class="px-4 pb-2 pt-1">
              <p class="text-xs text-indigo-100/80 text-left ml-1 mb-1">{{ $t('helpCenter.suggestedSearches') }}</p>
              <div class="flex flex-wrap gap-2">
                <button 
                  v-for="(suggestion, index) in suggestedSearches" 
                  :key="index"
                  @click="searchQuery = suggestion; performSearch()"
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-indigo-100 bg-white/10 hover:bg-white/20 transition-colors"
                >
                  {{ suggestion }}
                </button>
              </div>
            </div>
          </div>
          
          <!-- Popular categories -->
          <div class="mt-8">
            <p class="text-sm font-medium text-indigo-100/90 mb-3">{{ $t('helpCenter.popularCategories') }}</p>
            <div class="flex flex-wrap justify-center gap-3">
              <button 
                v-for="(category, index) in popularCategories" 
                :key="index"
                @click="activeCategory = category.id"
                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 transition-all duration-200 hover:shadow-lg"
              >
                <span>{{ category.name }}</span>
                <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs">
                  {{ category.count }}
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Wave divider (flipped) -->
      <div class="absolute bottom-0 left-0 right-0 h-12 overflow-hidden transform rotate-180">
        <svg class="absolute top-0 w-full h-12" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-white dark:fill-gray-900"></path>
        </svg>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-3">
          <button 
            @click="showFeedbackForm = true"
            class="w-full mb-6 flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
            </svg>
            {{ $t('feedback.title') }}
          </button>
          <nav class="space-y-1">
            <button 
              v-for="(category, index) in categories" 
              :key="index"
              @click="activeCategory = category.id"
              class="group rounded-md px-3 py-2 flex items-center justify-between text-sm font-medium w-full text-left"
              :class="{
                'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300': activeCategory === category.id,
                'text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-800': activeCategory !== category.id
              }"
            >
              <span>{{ category.name }}</span>
              <span 
                class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 text-xs font-medium px-2.5 py-0.5 rounded-full"
                :class="{ 'bg-indigo-200 dark:bg-indigo-800': activeCategory === category.id }"
              >
                {{ category.count }}
              </span>
            </button>
          </nav>
          
          <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">
              {{ $t('helpCenter.contactUs') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
              {{ $t('helpCenter.contactDescription') }}
            </p>
            <a 
              href="#" 
              class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300"
            >
              {{ $t('helpCenter.contactButton') }}
              <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="mt-12 lg:mt-0 lg:col-span-9">
          <!-- Search Results -->
          <div v-if="searchQuery" class="mb-12">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
              {{ $t('helpCenter.searchResults', { count: searchResults.length, query: searchQuery }) }}
            </h2>
            
            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center py-8">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
            </div>
            
            <!-- No Results -->
            <div v-else-if="searchResults.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Ничего не найдено</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Попробуйте изменить поисковый запрос или использовать другие ключевые слова.</p>
            </div>
            
            <!-- Search Results -->
            <div v-else class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
              <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li v-for="(article, index) in searchResults" :key="`search-${index}`" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                  <router-link :to="`/help/article/${article.id}`" class="block">
                    <div class="px-4 py-4 sm:px-6">
                      <div class="flex items-center justify-between">
                        <h3 class="text-base font-medium text-indigo-600 dark:text-indigo-400 truncate" v-html="highlightText(article.title)"></h3>
                        <div class="ml-2 flex-shrink-0 flex space-x-2">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                :class="{
                                  'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200': article.difficulty === 'beginner',
                                  'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200': article.difficulty === 'intermediate',
                                  'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200': article.difficulty === 'advanced'
                                }">
                            {{ $t(`helpCenter.difficulty.${article.difficulty}`) }}
                          </span>
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            {{ categoryMap[article.category] || article.category }}
                          </span>
                        </div>
                      </div>
                      <div class="mt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-300" v-html="highlightText(article.excerpt)"></p>
                      </div>
                      <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ $t('helpCenter.lastUpdated') }} {{ article.updatedAt }}</span>
                      </div>
                    </div>
                  </router-link>
                </li>
              </ul>
            </div>
          </div>

          <!-- Category Content -->
          <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
              {{ activeCategoryName }}
            </h2>
            
            <div class="space-y-8">
              <!-- Popular Articles -->
              <div v-if="popularArticles.length > 0">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                  {{ $t('helpCenter.popularArticles') }}
                </h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                  <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="(article, index) in popularArticles" :key="`popular-${index}`">
                      <a href="#" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                          <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <p class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                              {{ article.title }}
                            </p>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- All Articles -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                  {{ $t('helpCenter.allArticles') }}
                </h3>
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                  <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="(article, index) in categoryArticles" :key="`article-${index}`">
                      <a href="#" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                          <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                              {{ article.title }}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                              <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                :class="{
                                  'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': article.difficulty === 'beginner',
                                  'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': article.difficulty === 'intermediate',
                                  'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': article.difficulty === 'advanced'
                                }"
                              >
                                {{ $t(`helpCenter.difficulty.${article.difficulty}`) }}
                              </p>
                            </div>
                          </div>
                          <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                              <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                {{ article.excerpt }}
                              </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                              <span>{{ $t('helpCenter.lastUpdated') }} {{ article.updatedAt }}</span>
                            </div>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Feedback Form Modal -->
    <TransitionRoot as="template" :show="showFeedbackForm">
      <HeadlessDialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="showFeedbackForm = false">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
            <HeadlessDialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
              <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button type="button" class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none" @click="showFeedbackForm = false">
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                  <ChatBubbleLeftRightIcon class="h-6 w-6 text-indigo-600 dark:text-indigo-400" aria-hidden="true" />
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                  <HeadlessDialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ $t('feedback.title') }}
                  </HeadlessDialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ $t('feedback.subtitle') }}
                    </p>
                  </div>
                </div>
              </div>
              
              <form @submit.prevent="submitFeedback" class="mt-5 sm:flex sm:items-center">
                <div class="w-full space-y-4">
                  <!-- Success/Error Messages -->
                  <div v-if="feedbackStatus === 'success'" class="rounded-md bg-green-50 dark:bg-green-900/30 p-4">
                    <div class="flex">
                      <div class="flex-shrink-0">
                        <CheckCircleIcon class="h-5 w-5 text-green-400" aria-hidden="true" />
                      </div>
                      <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                          {{ $t('feedback.success') }}
                        </p>
                      </div>
                    </div>
                  </div>
                  
                  <div v-if="feedbackStatus === 'error'" class="rounded-md bg-red-50 dark:bg-red-900/30 p-4">
                    <div class="flex">
                      <div class="flex-shrink-0">
                        <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                      </div>
                      <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                          {{ $t('feedback.error') }}
                        </p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Form Fields -->
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('feedback.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      id="name"
                      v-model="feedbackForm.name"
                      :class="{
                        'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500': v$.feedbackForm.name.$error,
                        'border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white': !v$.feedbackForm.name.$error
                      }"
                      class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
                      :placeholder="$t('feedback.name')"
                    >
                    <p v-if="v$.feedbackForm.name.$error" class="mt-2 text-sm text-red-600 dark:text-red-400">
                      {{ v$.feedbackForm.name.$errors[0].$message }}
                    </p>
                  </div>
                  
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('feedback.email') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="email"
                      id="email"
                      v-model="feedbackForm.email"
                      :class="{
                        'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500': v$.feedbackForm.email.$error,
                        'border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white': !v$.feedbackForm.email.$error
                      }"
                      class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
                      :placeholder="$t('feedback.email')"
                    >
                    <p v-if="v$.feedbackForm.email.$error" class="mt-2 text-sm text-red-600 dark:text-red-400">
                      {{ v$.feedbackForm.email.$errors[0].$message }}
                    </p>
                  </div>
                  
                  <div>
                    <label for="topic" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('feedback.subject') }} <span class="text-red-500">*</span>
                    </label>
                    <select
                      id="topic"
                      v-model="feedbackForm.topic"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white"
                    >
                      <option v-for="(label, key) in $t('feedback.topics')" :key="key" :value="key">
                        {{ label }}
                      </option>
                    </select>
                  </div>
                  
                  <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                      {{ $t('feedback.message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea
                      id="message"
                      v-model="feedbackForm.message"
                      rows="4"
                      :class="{
                        'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500': v$.feedbackForm.message.$error,
                        'border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white': !v$.feedbackForm.message.$error
                      }"
                      class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
                      :placeholder="$t('feedback.message')"
                    ></textarea>
                    <p v-if="v$.feedbackForm.message.$error" class="mt-2 text-sm text-red-600 dark:text-red-400">
                      {{ v$.feedbackForm.message.$errors[0].$message }}
                    </p>
                  </div>
                </div>
              </form>
              
              <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                  type="button"
                  @click="submitFeedback"
                  :disabled="isSubmitting"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isSubmitting ? $t('common.sending') : $t('feedback.submit') }}
                </button>
                <button
                  type="button"
                  @click="showFeedbackForm = false"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                >
                  {{ $t('common.cancel') }}
                </button>
              </div>
            </div>
          </TransitionChild>
        </div>
      </HeadlessDialog>
    </TransitionRoot>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, email, minLength, maxLength } from '@vuelidate/validators';
import { TransitionRoot, TransitionChild } from '@headlessui/vue';
import { 
  XMarkIcon,
  ChatBubbleLeftRightIcon,
  CheckCircleIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline';

// Helper function to highlight search terms in text
const highlightSearchTerm = (text, term) => {
  if (!term) return text;
  const regex = new RegExp(`(${term})`, 'gi');
  return text.replace(regex, '<span class="bg-yellow-200 dark:bg-yellow-800">$1</span>');
};

// Debounce function to limit how often a function can run
const debounce = (func, delay) => {
  let timeoutId;
  return function(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func.apply(this, args), delay);
  };
};

export default {
  name: 'HelpCenterView',
  components: {
    // Dialog components are used via <HeadlessDialog> in template
    TransitionChild,
    TransitionRoot,
    XMarkIcon,
    ChatBubbleLeftRightIcon,
    CheckCircleIcon,
    XCircleIcon
  },
  setup() {
    const searchQuery = ref('');
    const activeCategory = ref('getting-started');
    const isLoading = ref(false);
    const searchResults = ref([]);
    const showFeedbackForm = ref(false);
    const isSubmitting = ref(false);
    const feedbackStatus = ref(null);
    
    // Feedback form data and validation
    const feedbackForm = ref({
      name: '',
      email: '',
      topic: 'general',
      message: ''
    });
    
    const rules = {
      feedbackForm: {
        name: { 
          required,
          minLength: minLength(2),
          maxLength: maxLength(100)
        },
        email: { 
          required, 
          email,
          maxLength: maxLength(255)
        },
        message: {
          required,
          minLength: minLength(10),
          maxLength: maxLength(2000)
        }
      }
    };
    
    const v$ = useVuelidate(rules, { feedbackForm });
    
    // Map category IDs to their names for display
    const categoryMap = {
      'getting-started': 'Начало работы',
      'account': 'Учетная запись',
      'templates': 'Шаблоны',
      'exporting': 'Экспорт',
      'troubleshooting': 'Устранение неполадок'
    };
    
    const categories = [
      { id: 'getting-started', name: 'Getting Started', count: 5 },
      { id: 'account', name: 'Account', count: 3 },
      { id: 'billing', name: 'Billing', count: 4 },
      { id: 'templates', name: 'Templates', count: 7 },
      { id: 'export', name: 'Export', count: 3 },
    ];
    
    // Popular categories for the header
    const popularCategories = computed(() => {
      return [
        { id: 'getting-started', name: 'Getting Started', count: 5 },
        { id: 'templates', name: 'Templates', count: 7 },
        { id: 'export', name: 'Export', count: 3 },
        { id: 'account', name: 'Account', count: 3 },
      ];
    });
    
    // Suggested search queries
    const suggestedSearches = computed(() => {
      return [
        'How to create a resume',
        'Export to PDF',
        'Change template',
        'Account settings'
      ];
    });
    
    const articles = [
      { id: 1, title: 'How to create your first resume', category: 'getting-started', readTime: '3 min', updated: '2 days ago' },
      { id: 2, title: 'Managing your account settings', category: 'account', readTime: '5 min', updated: '1 week ago' },
      { id: 3, title: 'Understanding billing cycles', category: 'billing', readTime: '4 min', updated: '3 days ago' },
      { id: 4, title: 'Choosing the right template', category: 'templates', readTime: '6 min', updated: '1 day ago' },
      { id: 5, title: 'Exporting your resume to PDF', category: 'export', readTime: '2 min', updated: '5 days ago' },
    ];
    
    const activeCategoryName = computed(() => {
      const category = categories.find(cat => cat.id === activeCategory.value);
      return category ? category.name : 'Центр поддержки';
    });
    
    const categoryArticles = computed(() => {
      return articles.filter(article => article.category === activeCategory.value);
    });
    
    const popularArticles = computed(() => {
      return articles.filter(article => article.popular && article.category === activeCategory.value);
    });
    
    // Enhanced search function with debouncing
    const performSearch = debounce(() => {
      if (!searchQuery.value.trim()) {
        searchResults.value = [];
        return;
      }
      
      isLoading.value = true;
      
      // Simulate API call delay
      setTimeout(() => {
        const query = searchQuery.value.toLowerCase().trim();
        const terms = query.split(' ').filter(term => term.length > 0);
        
        searchResults.value = articles.filter(article => {
          const searchText = `${article.title} ${article.readTime} ${categoryMap[article.category] || ''}`.toLowerCase();
          return terms.every(term => searchText.includes(term));
        });
        
        isLoading.value = false;
      }, 300);
    }, 300);
    
    // Watch for changes in search query
    watch(searchQuery, () => {
      if (searchQuery.value) {
        performSearch();
      } else {
        searchResults.value = [];
      }
    });
    
    // Watch for category changes to clear search
    watch(activeCategory, () => {
      searchQuery.value = '';
      searchResults.value = [];
    });
    
    // Highlight search terms in text
    const highlightText = (text) => {
      if (!searchQuery.value) return text;
      const escapedQuery = searchQuery.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      const regex = new RegExp(`(${escapedQuery})`, 'gi');
      return text.replace(regex, '<span class="bg-yellow-200 dark:bg-yellow-800">$1</span>');
    };
    
    return {
      // Search and navigation
      searchQuery,
      searchResults,
      categories,
      popularCategories,
      suggestedSearches,
      activeCategory,
      articles,
      activeCategoryName,
      categoryArticles,
      popularArticles,
      categoryMap,
      
      // Search functionality
      highlightSearchTerm,
      performSearch,
      isLoading,
      highlightText,
      
      // Feedback form
      showFeedbackForm,
      feedbackForm,
      v$,
      isSubmitting,
      feedbackStatus,
      
      // Methods
      async submitFeedback() {
        const isFormValid = await v$.value.$validate();
        
        if (!isFormValid) {
          return;
        }
        
        isSubmitting.value = true;
        feedbackStatus.value = null;
        
        try {
          // Simulate API call
          await new Promise(resolve => setTimeout(resolve, 1000));
          
          // Reset form on success
          feedbackForm.value = {
            name: '',
            email: '',
            topic: 'general',
            message: ''
          };
          v$.value.$reset();
          
          feedbackStatus.value = 'success';
          
          // Hide success message after 5 seconds
          setTimeout(() => {
            if (feedbackStatus.value === 'success') {
              showFeedbackForm.value = false;
              feedbackStatus.value = null;
            }
          }, 5000);
        } catch (error) {
          console.error('Error submitting feedback:', error);
          feedbackStatus.value = 'error';
        } finally {
          isSubmitting.value = false;
        }
      }
    };
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>