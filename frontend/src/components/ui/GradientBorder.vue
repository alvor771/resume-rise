<template>
  <div class="gradient-hover-container relative group h-full">
    <!-- Простая обводка -->
    <div class="absolute inset-0 rounded-xl border-2 border-gray-200 dark:border-gray-700 group-hover:border-indigo-400 dark:group-hover:border-indigo-500 transition-colors duration-300 pointer-events-none"></div>
    
    <!-- Контент -->
    <div class="relative bg-white dark:bg-gray-800 p-8 rounded-xl transition-all duration-300 z-10 h-full group-hover:bg-white/95 dark:group-hover:bg-gray-800/95">
      <slot></slot>
    </div>
  </div>
</template>

<style scoped>
.gradient-hover-container {
  /* Простые стили контейнера */
  transition: transform 0.3s ease;
}

.gradient-hover-container:hover {
  transform: translateY(-2px);
}
</style>
