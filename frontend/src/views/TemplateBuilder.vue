<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-12 gap-8">
        <!-- Left Sidebar - Components -->
        <div class="col-span-3">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 h-full">
            <h2 class="text-lg font-semibold mb-4">Components</h2>
            <div class="space-y-2">
              <button 
                v-for="component in components" 
                :key="component.id"
                @click="addComponent(component)"
                class="w-full flex items-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              >
                <component :is="component.icon" class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" />
                {{ component.name }}
              </button>
            </div>
          </div>
        </div>

        <!-- Main Canvas -->
        <div class="col-span-6">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 h-full">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-semibold">Template Builder</h2>
              <button 
                @click="previewMode = !previewMode"
                class="px-3 py-1 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600 transition-colors"
              >
                {{ previewMode ? 'Edit' : 'Preview' }}
              </button>
            </div>
            <div 
              ref="canvas"
              class="w-full h-[600px] bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden"
              :class="{ 'cursor-move': !previewMode }"
              @click="selectComponent"
              @dragover.prevent
              @drop="handleDrop"
            >
              <div 
                v-for="(item, index) in templateItems"
                :key="index"
                :ref="el => { if (el) item.element = el }"
                class="absolute"
                :style="{
                  top: item.position.y + 'px',
                  left: item.position.x + 'px',
                  width: item.size.width + 'px',
                  height: item.size.height + 'px',
                  zIndex: item.zIndex
                }"
                @mousedown="startDrag(item, $event)"
                @mouseup="endDrag(item)"
                @mousemove="dragging && drag(item, $event)"
              >
                <component 
                  :is="item.type"
                  v-bind="item.props"
                  :class="{ 'ring-2 ring-indigo-500': selectedComponent === index && !previewMode }"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Right Sidebar - Properties -->
        <div class="col-span-3">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 h-full">
            <h2 class="text-lg font-semibold mb-4">Properties</h2>
            <div v-if="selectedComponent !== null">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium mb-1">Position</label>
                  <div class="flex space-x-2">
                    <input 
                      type="number" 
                      v-model="templateItems[selectedComponent].position.x"
                      class="w-24 p-1 rounded border"
                    >
                    <input 
                      type="number" 
                      v-model="templateItems[selectedComponent].position.y"
                      class="w-24 p-1 rounded border"
                    >
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Size</label>
                  <div class="flex space-x-2">
                    <input 
                      type="number" 
                      v-model="templateItems[selectedComponent].size.width"
                      class="w-24 p-1 rounded border"
                    >
                    <input 
                      type="number" 
                      v-model="templateItems[selectedComponent].size.height"
                      class="w-24 p-1 rounded border"
                    >
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Z-Index</label>
                  <input 
                    type="number" 
                    v-model="templateItems[selectedComponent].zIndex"
                    class="w-24 p-1 rounded border"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Content</label>
                  <textarea 
                    v-model="templateItems[selectedComponent].props.content"
                    class="w-full p-2 rounded border"
                    rows="3"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { v4 as uuidv4 } from 'uuid'

// Components
const components = [
  {
    id: 'text',
    name: 'Text',
    icon: 'TextIcon',
    component: 'TextComponent'
  },
  {
    id: 'image',
    name: 'Image',
    icon: 'ImageIcon',
    component: 'ImageComponent'
  },
  {
    id: 'header',
    name: 'Header',
    icon: 'HeaderIcon',
    component: 'HeaderComponent'
  },
  {
    id: 'section',
    name: 'Section',
    icon: 'SectionIcon',
    component: 'SectionComponent'
  },
  {
    id: 'list',
    name: 'List',
    icon: 'ListIcon',
    component: 'ListComponent'
  },
  {
    id: 'divider',
    name: 'Divider',
    icon: 'DividerIcon',
    component: 'DividerComponent'
  }
]

// Template state
const templateItems = ref([])
const selectedComponent = ref(null)
const dragging = ref(false)
const dragStart = ref({ x: 0, y: 0 })
const previewMode = ref(false)


// Methods
const addComponent = (component) => {
  const newComponent = {
    id: uuidv4(),
    type: component.component,
    position: { x: 50, y: 50 },
    size: { width: 200, height: 100 },
    zIndex: 1,
    props: {
      content: '',
      ...component.defaultProps
    }
  }
  templateItems.value.push(newComponent)
  selectedComponent.value = templateItems.value.length - 1
}

const startDrag = (item, event) => {
  if (previewMode.value) return
  dragging.value = true
  dragStart.value = {
    x: event.clientX - item.position.x,
    y: event.clientY - item.position.y
  }
}

const drag = (item, event) => {
  if (!dragging.value) return
  item.position.x = event.clientX - dragStart.value.x
  item.position.y = event.clientY - dragStart.value.y
}

const endDrag = () => {
  dragging.value = false
}

const selectComponent = (event) => {
  if (previewMode.value) return
  const element = event.target
  const item = templateItems.value.find(item => item.element === element)
  if (item) {
    selectedComponent.value = templateItems.value.indexOf(item)
  } else {
    selectedComponent.value = null
  }
}

const handleDrop = (event) => {
  if (previewMode.value) return
  const data = event.dataTransfer.getData('text/plain')
  const component = components.find(c => c.id === data)
  if (component) {
    addComponent(component)
    const lastItem = templateItems.value[templateItems.value.length - 1]
    lastItem.position.x = event.clientX - 50
    lastItem.position.y = event.clientY - 50
  }
}

// Initialize
onMounted(() => {
  // Load saved template if exists
  const savedTemplate = localStorage.getItem('template')
  if (savedTemplate) {
    templateItems.value = JSON.parse(savedTemplate)
  }
})

// Save template on changes
watch(templateItems, (newVal) => {
  localStorage.setItem('template', JSON.stringify(newVal))
}, { deep: true })

</script>