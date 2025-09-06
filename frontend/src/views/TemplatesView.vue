<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col md:flex-row gap-8">
        <!-- Template Selection Sidebar -->
        <div class="w-full md:w-64 flex-shrink-0">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sticky top-24">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
              {{ $t('templateBuilder.categories') }}
            </h2>
            <ul class="space-y-2">
              <li v-for="category in categories" :key="category.id">
                <button 
                  @click="activeCategory = category.id"
                  class="w-full text-left px-3 py-2 rounded-md text-sm font-medium transition-colors"
                  :class="{
                    'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300': activeCategory === category.id,
                    'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': activeCategory !== category.id
                  }"
                >
                  {{ category.name }}
                  <span class="float-right bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-200 text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ category.count }}
                  </span>
                </button>
              </li>
            </ul>
            
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
              <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                {{ $t('templateBuilder.filters') }}
              </h3>
              <div class="space-y-2">
                <div v-for="filter in filters" :key="filter.id" class="flex items-center">
                  <input 
                    :id="`filter-${filter.id}`"
                    v-model="filter.active"
                    type="checkbox" 
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                  >
                  <label :for="`filter-${filter.id}`" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    {{ filter.label }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Template Grid -->
        <div class="flex-1">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ $t('templateBuilder.title') }}
            </h1>
            <div class="relative w-64">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input 
                v-model="searchQuery"
                type="text" 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                :placeholder="$t('templateBuilder.searchPlaceholder')"
              >
            </div>
          </div>
          
          <div v-if="filteredTemplates.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div 
              v-for="template in filteredTemplates" 
              :key="template.id"
              class="group bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1"
            >
              <!-- Card Header with Category Badge -->
              <div class="relative">
                <div class="absolute top-3 right-3 z-10">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium capitalize"
                    :class="{
                      'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200': template.category === 'modern',
                      'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200': template.category === 'creative',
                      'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200': template.category === 'minimal',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200': template.category === 'professional',
                      'bg-violet-100 text-violet-800 dark:bg-violet-900/50 dark:text-violet-200': template.category === 'executive'
                    }"
                  >
                    {{ template.category }}
                  </span>
                </div>
                
                <!-- Template Image with Hover Effect -->
                <div class="relative pt-[120%] overflow-hidden">
                  <img 
                    :src="template.thumbnail" 
                    :alt="template.name"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                  >
                  <!-- Hover Overlay -->
                  <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                    <div class="w-full space-y-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                      <h3 class="text-white font-bold text-xl">{{ template.name }}</h3>
                      <div class="flex items-center space-x-2 text-gray-200 text-sm">
                        <span class="flex items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                          </svg>
                          {{ template.pages }} {{ template.pages > 1 ? 'Pages' : 'Page' }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ template.lastUpdated || 'Recently updated' }}
                        </span>
                      </div>
                      <div class="flex space-x-2 pt-2">
                        <button 
                          @click.stop="previewTemplate(template)"
                          class="flex-1 flex items-center justify-center px-4 py-2 bg-white/20 text-white text-sm font-medium rounded-lg backdrop-blur-sm hover:bg-white/30 transition-colors"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          {{ $t('templateBuilder.buttons.preview') }}
                        </button>
                        <button 
                          @click.stop="useTemplate(template)"
                          class="flex-1 flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                          </svg>
                          {{ $t('templateBuilder.buttons.useTemplate') }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Card Footer -->
              <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <div class="flex -space-x-2">
                      <div v-for="(color, index) in template.colors" :key="index" 
                        class="h-4 w-4 rounded-full border-2 border-white dark:border-gray-800" 
                        :style="{ backgroundColor: color }"
                        :title="'Color: ' + color"
                      ></div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ template.colors.length }} colors</span>
                  </div>
                  <div class="flex items-center">
                    <span v-if="template.rating" class="flex items-center text-amber-500 text-sm mr-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      {{ template.rating }}
                    </span>
                    <span 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300': template.premium,
                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': !template.premium
                      }"
                    >
                      {{ template.premium ? $t('templateBuilder.premium') : $t('templateBuilder.free') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
              {{ $t('templateBuilder.noTemplates') }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              {{ $t('templateBuilder.tryDifferentFilters') }}
            </p>
          </div>
          
          <!-- Pagination -->
          <div v-if="filteredTemplates.length > 0" class="mt-8 flex items-center justify-between">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                  {{ $t('templateBuilder.showing') }}
                  <span class="font-medium">1</span>
                  {{ $t('templateBuilder.to') }}
                  <span class="font-medium">{{ Math.min(9, filteredTemplates.length) }}</span>
                  {{ $t('templateBuilder.of') }}
                  <span class="font-medium">{{ filteredTemplates.length }}</span>
                  {{ $t('templateBuilder.results') }}
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button 
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                    :disabled="currentPage === 1"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                    @click="currentPage--"
                  >
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <button 
                    v-for="page in totalPages" 
                    :key="page"
                    @click="currentPage = page"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium"
                    :class="{
                      'z-10 bg-indigo-50 dark:bg-indigo-900/50 border-indigo-500 text-indigo-600 dark:text-indigo-300': currentPage === page,
                      'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700': currentPage !== page
                    }"
                  >
                    {{ page }}
                  </button>
                  <button 
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                    :disabled="currentPage === totalPages"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                    @click="currentPage++"
                  >
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Template Preview Modal -->
    <div v-if="selectedTemplate" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="selectedTemplate = null"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
          <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <div class="flex justify-between items-start">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                    {{ selectedTemplate.name }}
                  </h3>
                  <button 
                    @click="selectedTemplate = null"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                  >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                <div class="mt-4">
                  <img :src="selectedTemplate.preview" :alt="selectedTemplate.name" class="w-full h-auto border border-gray-200 dark:border-gray-700">
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="useTemplate(selectedTemplate)"
            >
              {{ $t('templateBuilder.buttons.useThisTemplate') }}
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="selectedTemplate = null"
            >
              {{ $t('templateBuilder.buttons.cancel') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'TemplateBuilderView',
  setup() {
    const router = useRouter();
    const activeCategory = ref('all');
    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = 9;
    const selectedTemplate = ref(null);
    
    const categories = [
      { id: 'all', name: 'All Templates', count: 12 },
      { id: 'modern', name: 'Modern', count: 5 },
      { id: 'professional', name: 'Professional', count: 4 },
      { id: 'creative', name: 'Creative', count: 3 },
      { id: 'minimal', name: 'Minimal', count: 4 },
      { id: 'executive', name: 'Executive', count: 2 },
    ];
    
    const filters = [
      { id: 'free', label: 'Free Templates', active: false },
      { id: 'premium', label: 'Premium Templates', active: false },
      { id: 'one-page', label: 'One Page', active: false },
      { id: 'multi-page', label: 'Multi Page', active: false },
    ];
    
    const templates = [
      {
        id: 1,
        name: 'Modern Professional',
        category: 'modern',
        premium: false,
        pages: 1,
        rating: 4.8,
        lastUpdated: '2 weeks ago',
        colors: ['#3b82f6', '#93c5fd', '#1e40af', '#dbeafe'],
        thumbnail: 'https://placehold.co/400x500/3b82f6/FFFFFF?text=Modern+Professional&font=montserrat',
        preview: 'https://placehold.co/1200x1600/3b82f6/FFFFFF?text=Modern+Professional+Preview&font=montserrat'
      },
      {
        id: 2,
        name: 'Creative Portfolio',
        category: 'creative',
        premium: true,
        pages: 2,
        rating: 4.9,
        lastUpdated: '1 week ago',
        colors: ['#8b5cf6', '#c4b5fd', '#5b21b6', '#ede9fe'],
        thumbnail: 'https://placehold.co/400x500/8b5cf6/FFFFFF?text=Creative+Portfolio&font=montserrat',
        preview: 'https://placehold.co/1200x1600/8b5cf6/FFFFFF?text=Creative+Portfolio+Preview&font=montserrat'
      },
      {
        id: 3,
        name: 'Minimalist CV',
        category: 'minimal',
        premium: false,
        pages: 1,
        rating: 4.7,
        lastUpdated: '3 days ago',
        colors: ['#10b981', '#6ee7b7', '#047857', '#d1fae5'],
        thumbnail: 'https://placehold.co/400x500/10b981/FFFFFF?text=Minimalist+CV&font=montserrat',
        preview: 'https://placehold.co/1200x1600/10b981/FFFFFF?text=Minimalist+CV+Preview&font=montserrat'
      },
      {
        id: 4,
        name: 'Executive Resume',
        category: 'executive',
        premium: true,
        pages: 2,
        rating: 4.9,
        lastUpdated: '5 days ago',
        colors: ['#6366f1', '#a5b4fc', '#4f46e5', '#e0e7ff'],
        thumbnail: 'https://placehold.co/400x500/6366f1/FFFFFF?text=Executive+Resume&font=montserrat',
        preview: 'https://placehold.co/1200x1600/6366f1/FFFFFF?text=Executive+Resume+Preview&font=montserrat'
      },
      {
        id: 5,
        name: 'Modern Designer',
        category: 'modern',
        premium: false,
        pages: 1,
        rating: 4.6,
        lastUpdated: '1 day ago',
        colors: ['#ec4899', '#f9a8d4', '#be185d', '#fce7f3'],
        thumbnail: 'https://placehold.co/400x500/ec4899/FFFFFF?text=Modern+Designer&font=montserrat',
        preview: 'https://placehold.co/1200x1600/ec4899/FFFFFF?text=Modern+Designer+Preview&font=montserrat'
      },
      {
        id: 6,
        name: 'Professional Blue',
        category: 'professional',
        premium: false,
        pages: 2,
        rating: 4.5,
        lastUpdated: '1 month ago',
        colors: ['#2563eb', '#93c5fd', '#1e40af', '#dbeafe'],
        thumbnail: 'https://placehold.co/400x500/2563eb/FFFFFF?text=Professional+Blue&font=montserrat',
        preview: 'https://placehold.co/1200x1600/2563eb/FFFFFF?text=Professional+Blue+Preview&font=montserrat'
      },
      {
        id: 7,
        name: 'Creative Modern',
        category: 'creative',
        premium: true,
        pages: 1,
        rating: 4.9,
        lastUpdated: '2 days ago',
        colors: ['#d946ef', '#f0abfc', '#86198f', '#f5d0fe'],
        thumbnail: 'https://placehold.co/400x500/d946ef/FFFFFF?text=Creative+Modern&font=montserrat',
        preview: 'https://placehold.co/1200x1600/d946ef/FFFFFF?text=Creative+Modern+Preview&font=montserrat'
      },
      {
        id: 8,
        name: 'Minimal Professional',
        category: 'minimal',
        premium: false,
        pages: 1,
        rating: 4.7,
        lastUpdated: '1 week ago',
        colors: ['#14b8a6', '#5eead4', '#0d9488', '#ccfbf1'],
        thumbnail: 'https://placehold.co/400x500/14b8a6/FFFFFF?text=Minimal+Professional&font=montserrat',
        preview: 'https://placehold.co/1200x1600/14b8a6/FFFFFF?text=Minimal+Professional+Preview&font=montserrat'
      },
      {
        id: 9,
        name: 'Executive Dark',
        category: 'executive',
        premium: true,
        pages: 2,
        rating: 5.0,
        lastUpdated: 'Just updated',
        colors: ['#4f46e5', '#818cf8', '#3730a3', '#e0e7ff'],
        thumbnail: 'https://placehold.co/400x500/4f46e5/FFFFFF?text=Executive+Dark&font=montserrat',
        preview: 'https://placehold.co/1200x1600/4f46e5/FFFFFF?text=Executive+Dark+Preview&font=montserrat'
      }
    ];
    
    const filteredTemplates = computed(() => {
      let result = [...templates];
      
      // Filter by category
      if (activeCategory.value !== 'all') {
        result = result.filter(template => template.category === activeCategory.value);
      }
      
      // Filter by search query
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(template => 
          template.name.toLowerCase().includes(query) || 
          template.category.toLowerCase().includes(query)
        );
      }
      
      // Apply filters
      const activeFilters = filters.filter(f => f.active).map(f => f.id);
      if (activeFilters.length > 0) {
        result = result.filter(template => {
          return activeFilters.some(filter => {
            if (filter === 'free') return !template.premium;
            if (filter === 'premium') return template.premium;
            if (filter === 'one-page') return template.pages === 1;
            if (filter === 'multi-page') return template.pages > 1;
            return true;
          });
        });
      }
      
      return result;
    });
    
    const totalPages = computed(() => {
      return Math.ceil(filteredTemplates.value.length / itemsPerPage);
    });
    
    const paginatedTemplates = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return filteredTemplates.value.slice(start, end);
    });
    
    const previewTemplate = (template) => {
      selectedTemplate.value = template;
    };
    
    const useTemplate = (template) => {
      // In a real app, this would redirect to the CV generator with the selected template
      router.push({ name: 'CVGenerator', query: { template: template.id } });
    };
    
    return {
      categories,
      filters,
      activeCategory,
      searchQuery,
      currentPage,
      totalPages,
      filteredTemplates: paginatedTemplates,
      selectedTemplate,
      previewTemplate,
      useTemplate
    };
  }
};
</script>
