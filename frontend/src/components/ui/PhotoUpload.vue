<template>
  <div class="w-full">
    <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <div 
      @dragover.prevent="isDragging = true"
      @dragleave="isDragging = false"
      @drop.prevent="handleDrop"
      @click="$refs.fileInput.click()"
      class="group relative w-full rounded-lg border-2 border-dashed cursor-pointer transition-colors flex flex-col items-center justify-center p-6 text-center"
      :class="[
        isDragging ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 
        modelValue ? 'border-transparent' : 'border-gray-300 dark:border-gray-600 hover:border-blue-400',
        error ? 'border-red-500' : ''
      ]"
      :style="{ height: height }"
    >
      <input 
        ref="fileInput"
        type="file" 
        class="hidden" 
        accept="image/*"
        @change="handleFileSelect"
      >
      
      <template v-if="!modelValue">
        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-300">
          <span class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
            {{ uploadText }}
          </span>
          <p class="pl-1">{{ dragDropText }}</p>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
          {{ fileTypesText }}
        </p>
      </template>
      
      <template v-else>
        <div class="relative w-full h-full flex items-center justify-center">
          <img :src="previewUrl" :alt="altText" class="max-h-full max-w-full object-contain rounded">
          <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity rounded">
            <button 
              type="button" 
              @click.stop="removePhoto"
              class="p-2 rounded-full bg-white text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              :title="removeText"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              <span class="sr-only">{{ removeText }}</span>
            </button>
          </div>
        </div>
      </template>
    </div>
    
    <p v-if="error" class="mt-1 text-sm text-red-600 dark:text-red-400">
      {{ error }}
    </p>
    <p v-else-if="hint" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
      {{ hint }}
    </p>
  </div>
</template>

<script>
import { ref, computed } from 'vue';

const PhotoUpload = {
  name: 'PhotoUpload',
  props: {
    modelValue: {
      type: [String, File],
      default: null
    },
    label: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: ''
    },
    hint: {
      type: String,
      default: ''
    },
    height: {
      type: String,
      default: '200px'
    },
    uploadText: {
      type: String,
      default: 'Upload a file'
    },
    dragDropText: {
      type: String,
      default: 'or drag and drop'
    },
    fileTypesText: {
      type: String,
      default: 'PNG, JPG, GIF up to 5MB'
    },
    removeText: {
      type: String,
      default: 'Remove photo'
    },
    altText: {
      type: String,
      default: 'Uploaded photo'
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const isDragging = ref(false);
    const fileInput = ref(null);
    
    const previewUrl = computed(() => {
      if (!props.modelValue) return '';
      if (typeof props.modelValue === 'string') return props.modelValue;
      return URL.createObjectURL(props.modelValue);
    });
    
    const handleDrop = (e) => {
      isDragging.value = false;
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        handleFile(file);
      }
    };
    
    const handleFileSelect = (e) => {
      const file = e.target.files[0];
      if (file) {
        handleFile(file);
      }
    };
    
    const handleFile = (file) => {
      // Check file size (5MB max)
      if (file.size > 5 * 1024 * 1024) {
        emit('update:modelValue', null);
        return;
      }
      
      // Check file type
      if (!file.type.match('image.*')) {
        emit('update:modelValue', null);
        return;
      }
      
      emit('update:modelValue', file);
    };
    
    const removePhoto = (e) => {
      e.stopPropagation();
      emit('update:modelValue', null);
      if (fileInput.value) {
        fileInput.value.value = '';
      }
    };
    
    return {
      isDragging,
      fileInput,
      previewUrl,
      handleDrop,
      handleFileSelect,
      removePhoto
    };
  }
};

export default PhotoUpload;
</script>
