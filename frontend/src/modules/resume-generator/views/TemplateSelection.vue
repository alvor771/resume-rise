<template>
    <div class="template-selection p-6">
      <h2 class="text-2xl font-bold mb-6">{{ $t('resume.chooseTemplate') }}</h2>
      
      <div v-if="isLoading" class="text-center py-8">
        <span class="text-gray-500">Loading templates...</span>
      </div>
  
      <div v-else-if="error" class="text-red-500 p-4 bg-red-50 rounded">
        {{ error }}
      </div>
  
      <div v-else-if="!templates || templates.length === 0" class="text-center py-8">
        <span class="text-gray-500">No templates available</span>
      </div>
  
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="template in templates" 
          :key="template.id"
          class="gradient-card rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow cursor-pointer text-white border border-gray-700"
          :data-template="template.id"
          @click="selectTemplate(template)"
        >
          <div class="h-48 bg-gray-800/50 flex items-center justify-center">
            <span class="text-gray-300">Preview: {{ template.name }}</span>
          </div>
          <div class="p-4">
            <h3 class="font-semibold text-lg text-white">{{ template.name }}</h3>
            <p class="text-gray-300 text-sm mt-2">{{ template.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { useResumeStore } from '../store'
  import { onMounted, ref } from 'vue'
  import { useRouter } from 'vue-router'
  
  export default {
    name: 'TemplateSelection',
    
    setup() {
      const resumeStore = useResumeStore()
      const router = useRouter()
      const isLoading = ref(true)
      const error = ref(null)
      const templates = ref([])

      // Curated local templates with predefined canvas
      const curated = [
        {
          id: 'local-classic',
          name: 'Classic',
          description: 'Clean typographic layout with clear sections.',
          sections: ['summary','experience','education','skills','languages','projects'],
          canvas: {
            elements: [
              { type: 'text', x: 80, y: 80, width: 800, height: 60, z: 1, props: { text: 'Your Name', fontSize: 42, fontFamily: 'Inter', fill: '#111827', align: 'left', fontStyle: 'bold' } },
              { type: 'text', x: 80, y: 130, width: 800, height: 30, z: 1, props: { text: 'Position / Title', fontSize: 18, fill: '#6B7280' } },
              { type: 'rect', x: 80, y: 170, width: 1080, height: 1, z: 0, props: { fill: '#E5E7EB' } },
              { type: 'text', x: 80, y: 200, width: 1080, height: 28, z: 1, props: { text: 'Summary', fontSize: 16, fill: '#111827', fontStyle: 'bold' } },
              { type: 'text', x: 80, y: 232, width: 1080, height: 80, z: 1, props: { text: 'Short intro about your experience and goals.', fontSize: 13, fill: '#374151' } },
              // Languages (RU/EN/PL) compact
              { type: 'text', x: 80, y: 340, width: 1080, height: 26, z: 1, props: { text: 'Languages / Языки / Języki', fontSize: 16, fill: '#111827', fontStyle: 'bold' } },
              { type: 'text', x: 80, y: 372, width: 200, height: 22, z: 1, props: { text: 'RU — Русский', fontSize: 13, fill: '#374151' } },
              { type: 'text', x: 80, y: 390, width: 200, height: 20, z: 1, props: { text: 'Level: Native', fontSize: 12, fill: '#6B7280' } },
              { type: 'text', x: 300, y: 372, width: 200, height: 22, z: 1, props: { text: 'EN — English', fontSize: 13, fill: '#374151' } },
              { type: 'text', x: 300, y: 390, width: 200, height: 20, z: 1, props: { text: 'Level: C1', fontSize: 12, fill: '#6B7280' } },
              { type: 'text', x: 520, y: 372, width: 200, height: 22, z: 1, props: { text: 'PL — Polski', fontSize: 13, fill: '#374151' } },
              { type: 'text', x: 520, y: 390, width: 200, height: 20, z: 1, props: { text: 'Level: B2', fontSize: 12, fill: '#6B7280' } },
            ]
          }
        },
        {
          id: 'local-modern',
          name: 'Modern',
          description: 'Bold header bar, accent colors and neat structure.',
          sections: ['summary','experience','education','skills','languages','projects','contacts'],
          canvas: {
            elements: [
              { type: 'rect', x: 0, y: 60, width: 1240, height: 120, z: 0, props: { fill: '#111827' } },
              { type: 'text', x: 80, y: 90, width: 800, height: 60, z: 1, props: { text: 'Your Name', fontSize: 40, fill: '#FFFFFF', fontStyle: 'bold' } },
              { type: 'text', x: 80, y: 150, width: 800, height: 30, z: 1, props: { text: 'Position / Title', fontSize: 18, fill: '#D1D5DB' } },
              { type: 'text', x: 820, y: 120, width: 340, height: 22, z: 1, props: { text: 'email@domain.com  ·  +1 555 555', fontSize: 12, fill: '#D1D5DB', align: 'right' } },
              // Languages bars
              { type: 'text', x: 80, y: 220, width: 1080, height: 26, z: 1, props: { text: 'Languages / Языки / Języki', fontSize: 16, fill: '#111827', fontStyle: 'bold' } },
              { type: 'text', x: 80, y: 252, width: 120, height: 20, z: 1, props: { text: 'Русский', fontSize: 12, fill: '#374151' } },
              { type: 'rect', x: 210, y: 256, width: 420, height: 8, z: 1, props: { fill: '#E5E7EB', radius: 4 } },
              { type: 'rect', x: 210, y: 256, width: 420, height: 8, z: 2, props: { fill: '#60A5FA', radius: 4 } },
              { type: 'text', x: 80, y: 286, width: 120, height: 20, z: 1, props: { text: 'English', fontSize: 12, fill: '#374151' } },
              { type: 'rect', x: 210, y: 290, width: 420, height: 8, z: 1, props: { fill: '#E5E7EB', radius: 4 } },
              { type: 'rect', x: 210, y: 290, width: 360, height: 8, z: 2, props: { fill: '#60A5FA', radius: 4 } },
              { type: 'text', x: 80, y: 320, width: 120, height: 20, z: 1, props: { text: 'Polski', fontSize: 12, fill: '#374151' } },
              { type: 'rect', x: 210, y: 324, width: 420, height: 8, z: 1, props: { fill: '#E5E7EB', radius: 4 } },
              { type: 'rect', x: 210, y: 324, width: 300, height: 8, z: 2, props: { fill: '#60A5FA', radius: 4 } },
            ]
          }
        },
        {
          id: 'local-sidebar',
          name: 'Sidebar',
          description: 'Left sidebar with skills and contacts, content on the right.',
          sections: ['summary','experience','education','skills','languages','contacts'],
          canvas: {
            elements: [
              { type: 'rect', x: 0, y: 0, width: 360, height: 1754, z: 0, props: { fill: '#F3F4F6' } },
              { type: 'text', x: 400, y: 80, width: 760, height: 60, z: 1, props: { text: 'Your Name', fontSize: 38, fill: '#111827', fontStyle: 'bold' } },
              { type: 'text', x: 400, y: 130, width: 760, height: 30, z: 1, props: { text: 'Position / Title', fontSize: 18, fill: '#6B7280' } },
              // Sidebar skills chips
              { type: 'rect', x: 40, y: 200, width: 280, height: 28, z: 1, props: { fill: '#EEF2FF', radius: 14 } },
              { type: 'text', x: 52, y: 206, width: 256, height: 16, z: 2, props: { text: 'JavaScript', fontSize: 12, fill: '#3730A3' } },
              { type: 'rect', x: 40, y: 236, width: 220, height: 28, z: 1, props: { fill: '#EEF2FF', radius: 14 } },
              { type: 'text', x: 52, y: 242, width: 196, height: 16, z: 2, props: { text: 'Vue', fontSize: 12, fill: '#3730A3' } },
              // Languages list
              { type: 'text', x: 40, y: 290, width: 280, height: 26, z: 1, props: { text: 'Languages / Языки / Języki', fontSize: 14, fill: '#111827', fontStyle: 'bold' } },
              { type: 'text', x: 40, y: 318, width: 280, height: 20, z: 1, props: { text: 'RU — Русский (Native)', fontSize: 12, fill: '#374151' } },
              { type: 'text', x: 40, y: 338, width: 280, height: 20, z: 1, props: { text: 'EN — English (C1)', fontSize: 12, fill: '#374151' } },
              { type: 'text', x: 40, y: 358, width: 280, height: 20, z: 1, props: { text: 'PL — Polski (B2)', fontSize: 12, fill: '#374151' } },
            ]
          }
        },
        {
          id: 'local-creative',
          name: 'Creative',
          description: 'Color accent and compact layout with languages highlighted.',
          sections: ['summary','experience','education','skills','languages','projects'],
          canvas: {
            elements: [
              { type: 'rect', x: 0, y: 0, width: 1240, height: 8, z: 0, props: { fill: '#3B82F6' } },
              { type: 'text', x: 80, y: 60, width: 800, height: 60, z: 1, props: { text: 'Your Name', fontSize: 40, fill: '#111827', fontStyle: 'bold' } },
              { type: 'text', x: 80, y: 110, width: 800, height: 30, z: 1, props: { text: 'Position / Title', fontSize: 18, fill: '#6B7280' } },
              // Languages compact row with subtle pills
              { type: 'rect', x: 80, y: 170, width: 240, height: 26, z: 1, props: { fill: '#DBEAFE', radius: 13 } },
              { type: 'text', x: 92, y: 176, width: 216, height: 16, z: 2, props: { text: 'RU · Native', fontSize: 12, fill: '#1E3A8A' } },
              { type: 'rect', x: 330, y: 170, width: 220, height: 26, z: 1, props: { fill: '#DBEAFE', radius: 13 } },
              { type: 'text', x: 342, y: 176, width: 196, height: 16, z: 2, props: { text: 'EN · C1', fontSize: 12, fill: '#1E3A8A' } },
              { type: 'rect', x: 570, y: 170, width: 220, height: 26, z: 1, props: { fill: '#DBEAFE', radius: 13 } },
              { type: 'text', x: 582, y: 176, width: 196, height: 16, z: 2, props: { text: 'PL · B2', fontSize: 12, fill: '#1E3A8A' } },
            ]
          }
        },
      ]
  
      onMounted(async () => {
        try {
          isLoading.value = true
          await resumeStore.fetchTemplates()
          templates.value = [...(resumeStore.templates || []), ...curated]
        } catch (err) {
          console.error('Failed to load templates:', err)
          error.value = 'Failed to load templates. Please try again later.'
          // still show curated templates even on error
          templates.value = [...curated]
        } finally {
          isLoading.value = false
        }
      })
  
      const selectTemplate = (template) => {
        resumeStore.setSelectedTemplate(template)
        router.push({ name: 'resume-editor' })
      }
  
      return {
        templates,
        isLoading,
        error,
        selectTemplate
      }
    }
  }
  </script>