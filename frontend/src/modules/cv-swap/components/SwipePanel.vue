<template>
  <div class="swipe-panel relative w-full max-w-md mx-auto" ref="swipeContainer">
    <!-- Loading State -->
    <div v-if="isLoading" class="h-96 flex items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>
    
    <!-- Empty State -->
    <div v-else-if="!currentItem" class="h-96 flex flex-col items-center justify-center text-center p-6">
      <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="mt-4 text-lg font-medium text-gray-900">No more items</h3>
      <p class="mt-1 text-sm text-gray-500">Check back later for new listings</p>
      <button 
        @click="$emit('refresh')"
        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        Refresh
      </button>
    </div>
    
    <!-- Swipeable Item -->
    <div 
      v-else
      class="absolute top-0 left-0 right-0 bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300"
      :class="{
        'cursor-grab active:cursor-grabbing': !isAnimating,
        'transition-transform': isAnimating
      }"
      :style="{
        transform: `translate(${offsetX}px, ${offsetY}px) rotate(${rotation}deg)`,
        opacity: itemOpacity,
        zIndex: 100
      }"
      @mousedown="startDrag"
      @touchstart.passive="startDrag"
    >
      <!-- Dynamic slot for the item content -->
      <slot :item="currentItem"></slot>
      
      <!-- Decision Buttons (for mobile) -->
      <div class="md:hidden flex justify-around p-4 border-t border-gray-200 bg-gray-50">
        <button 
          @click="handleSwipe('left')"
          class="p-3 rounded-full bg-white shadow-md text-red-500 hover:bg-red-50"
          :disabled="isAnimating"
        >
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        
        <button 
          @click="handleSwipe('right')"
          class="p-3 rounded-full bg-white shadow-md text-green-500 hover:bg-green-50"
          :disabled="isAnimating"
        >
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Next Item Preview (peek) -->
    <div 
      v-if="nextItem && !isLoading"
      class="absolute top-2 left-2 right-2 bottom-2 bg-white rounded-xl shadow-md opacity-75 overflow-hidden"
      style="z-index: 90;"
    >
      <slot name="next" :item="nextItem"></slot>
    </div>
    
    <!-- Instructions -->
    <div v-if="showInstructions" class="mt-8 text-center text-sm text-gray-500">
      <p class="hidden md:block">Swipe left to pass, right to like</p>
      <p class="md:hidden">Tap the buttons below to make your choice</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onUnmounted, watch } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  },
  isLoading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['swipe-left', 'swipe-right', 'refresh']);

// State
const swipeContainer = ref(null);
const isDragging = ref(false);
const startX = ref(0);
const startY = ref(0);
const offsetX = ref(0);
const offsetY = ref(0);
const rotation = ref(0);
const isAnimating = ref(false);
const showInstructions = ref(true);

// Computed
const currentItem = computed(() => props.items[0]);
const nextItem = computed(() => props.items[1]);

const itemOpacity = computed(() => {
  if (!isDragging.value) return 1;
  
  const distance = Math.abs(offsetX.value);
  const maxDistance = 200; // Distance at which opacity starts decreasing
  const minOpacity = 0.5;
  
  if (distance < maxDistance) return 1;
  return Math.max(minOpacity, 1 - (distance - maxDistance) / 100);
});

// Methods
const startDrag = (e) => {
  if (isAnimating.value) return;
  
  isDragging.value = true;
  const clientX = e.clientX || e.touches[0].clientX;
  const clientY = e.clientY || e.touches[0].clientY;
  
  startX.value = clientX - offsetX.value;
  startY.value = clientY - offsetY.value;
  
  document.addEventListener('mousemove', onDrag);
  document.addEventListener('touchmove', onDrag, { passive: false });
  document.addEventListener('mouseup', endDrag);
  document.addEventListener('touchend', endDrag);
  
  // Hide instructions after first interaction
  if (showInstructions.value) {
    showInstructions.value = false;
  }
};

const onDrag = (e) => {
  if (!isDragging.value) return;
  e.preventDefault();
  
  const clientX = e.clientX || e.touches[0].clientX;
  const clientY = e.clientY || e.touches[0].clientY;
  
  offsetX.value = clientX - startX.value;
  offsetY.value = clientY - startY.value;
  
  // Add rotation based on horizontal movement
  rotation.value = offsetX.value * 0.1;
};

const endDrag = () => {
  if (!isDragging.value) return;
  
  document.removeEventListener('mousemove', onDrag);
  document.removeEventListener('touchmove', onDrag);
  document.removeEventListener('mouseup', endDrag);
  document.removeEventListener('touchend', endDrag);
  
  const threshold = 80; // Minimum distance to trigger a swipe
  
  if (Math.abs(offsetX.value) > threshold) {
    // Swipe left or right
    const direction = offsetX.value > 0 ? 'right' : 'left';
    handleSwipe(direction);
  } else {
    // Return to center
    resetPosition();
  }
  
  isDragging.value = false;
};

const handleSwipe = (direction) => {
  if (isAnimating.value || !currentItem.value) return;
  
  isAnimating.value = true;
  const containerWidth = swipeContainer.value?.offsetWidth || window.innerWidth;
  
  // Animate off screen
  const targetX = direction === 'right' ? containerWidth * 1.5 : -containerWidth * 1.5;
  offsetX.value = targetX;
  rotation.value = direction === 'right' ? 30 : -30;
  
  // Emit event after animation
  setTimeout(() => {
    if (direction === 'right') {
      emit('swipe-right', currentItem.value);
    } else {
      emit('swipe-left', currentItem.value);
    }
    
    // Reset for next item
    resetPosition();
    isAnimating.value = false;
  }, 300);
};

const resetPosition = () => {
  offsetX.value = 0;
  offsetY.value = 0;
  rotation.value = 0;
};

// Cleanup event listeners
onUnmounted(() => {
  document.removeEventListener('mousemove', onDrag);
  document.removeEventListener('touchmove', onDrag);
  document.removeEventListener('mouseup', endDrag);
  document.removeEventListener('touchend', endDrag);  
});

// Watch for items change to handle auto-swipe if needed
watch(() => props.items, (newItems) => {
  // Auto-swipe if the current item is already in the matches
  // This is just an example - adjust based on your needs
  if (newItems.length > 0 && newItems[0].autoSwipe) {
    setTimeout(() => {
      handleSwipe('right');
    }, 500);
  }
}, { immediate: true });
</script>

<style scoped>
/* Smooth transitions for the swiping effect */
.swipe-panel {
  touch-action: pan-y;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
}

/* Prevent text selection during drag */
[draggable="true"] {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Disable default touch actions on the swipable element */
.swipe-panel > div {
  touch-action: none;
}

/* Improve button tap targets on mobile */
button {
  -webkit-tap-highlight-color: transparent;
}
</style>
