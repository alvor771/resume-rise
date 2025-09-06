<template>
  <div class="p-4 max-w-7xl mx-auto">
    <div class="mb-8">
      <h1 class="text-2xl font-bold mb-2">CV Templates</h1>
      <p class="text-gray-600">Choose a template that best represents your professional style</p>
    </div>

    <!-- Search and Filters -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search templates..."
            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        
        <div class="flex gap-2">
          <select 
            v-model="filters.category"
            class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
          
          <select 
            v-model="filters.sortBy"
            class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="popular">Most Popular</option>
            <option value="newest">Newest</option>
            <option value="price-low">Price: Low to High</option>
            <option value="price-high">Price: High to Low</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Template Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div 
        v-for="template in filteredTemplates" 
        :key="template.id"
        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
      >
        <div class="relative">
          <img 
            :src="template.preview" 
            :alt="template.name"
            class="w-full h-48 object-cover cursor-pointer"
            @click="previewTemplate(template)"
          >
          <div v-if="template.isPremium" class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 text-xs font-bold px-2 py-1 rounded">
            PREMIUM
          </div>
        </div>
        
        <div class="p-4">
          <div class="flex justify-between items-start">
            <h3 class="font-semibold text-lg">{{ template.name }}</h3>
            <span class="text-gray-600">
              {{ template.isPremium ? `$${template.price}` : 'Free' }}
            </span>
          </div>
          
          <div class="flex items-center mt-2 text-sm text-gray-500">
            <span class="flex items-center">
              <svg class="h-4 w-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              {{ template.rating }}
            </span>
            <span class="mx-2">•</span>
            <span>{{ template.uses }}+ uses</span>
          </div>
          
          <div class="mt-4 flex gap-2">
            <button 
              @click="previewTemplate(template)"
              class="flex-1 py-2 px-3 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Preview
            </button>
            <button 
              @click="useTemplate(template)"
              class="flex-1 py-2 px-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              {{ template.isPremium ? 'Buy Now' : 'Use Template' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Preview Modal -->
    <div v-if="selectedTemplate" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start justify-between">
              <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ selectedTemplate.name }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                  {{ selectedTemplate.isPremium ? `$${selectedTemplate.price}` : 'Free Template' }}
                </p>
              </div>
              <button 
                @click="selectedTemplate = null"
                class="text-gray-400 hover:text-gray-500"
              >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <div class="mt-4">
              <img 
                :src="selectedTemplate.preview" 
                :alt="selectedTemplate.name"
                class="w-full border rounded-lg"
              >
            </div>
          </div>
          
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="useTemplate(selectedTemplate)"
            >
              {{ selectedTemplate.isPremium ? 'Buy Now' : 'Use This Template' }}
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="selectedTemplate = null"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  name: 'TemplatesPage',
  setup() {
    const searchQuery = ref('');
    const selectedTemplate = ref(null);
    
    const filters = ref({
      category: '',
      sortBy: 'popular'
    });
    
    const categories = [
      'Creative', 'Professional', 'Minimalist', 'Modern', 'Executive', 'Academic'
    ];
    
    const templates = [
      {
        id: 1,
        name: 'Modern Professional',
        category: 'Professional',
        preview: 'https://via.placeholder.com/400x500/f3f4f6/6b7280?text=Modern+Professional',
        isPremium: false,
        price: 0,
        rating: 4.8,
        uses: 12500
      },
      {
        id: 2,
        name: 'Creative Portfolio',
        category: 'Creative',
        preview: 'https://via.placeholder.com/400x500/e5e7eb/4b5563?text=Creative+Portfolio',
        isPremium: true,
        price: 9.99,
        rating: 4.9,
        uses: 8700
      },
      {
        id: 3,
        name: 'Minimal Resume',
        category: 'Minimalist',
        preview: 'https://via.placeholder.com/400x500/f9fafb/374151?text=Minimal+Resume',
        isPremium: false,
        price: 0,
        rating: 4.7,
        uses: 15600
      },
      {
        id: 4,
        name: 'Executive',
        category: 'Executive',
        preview: 'https://via.placeholder.com/400x500/f3f4f6/1f2937?text=Executive',
        isPremium: true,
        price: 14.99,
        rating: 4.6,
        uses: 5200
      },
      {
        id: 5,
        name: 'Academic CV',
        category: 'Academic',
        preview: 'https://via.placeholder.com/400x500/e5e7eb/111827?text=Academic+CV',
        isPremium: false,
        price: 0,
        rating: 4.5,
        uses: 9800
      },
      {
        id: 6,
        name: 'Modern Designer',
        category: 'Creative',
        preview: 'https://via.placeholder.com/400x500/f9fafb/4b5563?text=Modern+Designer',
        isPremium: true,
        price: 12.99,
        rating: 4.9,
        uses: 7300
      }
    ];
    
    const filteredTemplates = computed(() => {
      let result = [...templates];
      
      // Apply search
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(template => 
          template.name.toLowerCase().includes(query) || 
          template.category.toLowerCase().includes(query)
        );
      }
      
      // Apply category filter
      if (filters.value.category) {
        result = result.filter(template => 
          template.category === filters.value.category
        );
      }
      
      // Apply sorting
      switch (filters.value.sortBy) {
        case 'newest':
          result.reverse(); // In a real app, sort by creation date
          break;
        case 'price-low':
          result.sort((a, b) => a.price - b.price);
          break;
        case 'price-high':
          result.sort((a, b) => b.price - a.price);
          break;
        // 'popular' is the default sort (already sorted by popularity in the templates array)
      }
      
      return result;
    });
    
    const previewTemplate = (template) => {
      selectedTemplate.value = template;
    };
    
    const useTemplate = (template) => {
      if (template.isPremium) {
        // Redirect to checkout or show payment modal
        console.log('Redirecting to checkout for:', template.name);
      } else {
        // Apply the free template
        console.log('Applying template:', template.name);
        // In a real app, this would navigate to the CV editor with the selected template
      }
    };
    
    return {
      searchQuery,
      filters,
      categories,
      templates: filteredTemplates,
      selectedTemplate,
      previewTemplate,
      useTemplate
    };
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
