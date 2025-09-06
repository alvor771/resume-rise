<template>
  <div class="relative inline-block text-left" data-dropdown-root @keydown.escape.prevent.stop="open = false">
    <div @click="toggle">
      <slot name="trigger"></slot>
    </div>
    <transition name="fade-scale">
      <div v-if="open" class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-40">
        <div class="py-1">
          <slot />
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
export default {
  name: 'BaseDropdown',
  setup() {
    const open = ref(false);
    const toggle = () => open.value = !open.value;

    const onDocClick = (e) => {
      if (!e.target.closest('[data-dropdown-root]')) open.value = false;
    };

    onMounted(() => document.addEventListener('click', onDocClick));
    onBeforeUnmount(() => document.removeEventListener('click', onDocClick));

    return { open, toggle };
  }
}
</script>

<style scoped>
.fade-scale-enter-active, .fade-scale-leave-active { transition: all .12s ease; }
.fade-scale-enter-from, .fade-scale-leave-to { opacity: 0; transform: scale(0.98); }
</style>
