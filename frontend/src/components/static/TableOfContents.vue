<template>
  <div class="sticky top-24">
    <div class="mb-4 text-sm font-medium text-gray-900 dark:text-white">
      {{ $t('static.onThisPage') }}
    </div>
    <nav class="space-y-2">
      <a
        v-for="(item, index) in items"
        :key="index"
        :href="`#${item.id}`"
        class="block text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
        :class="{
          'text-indigo-600 dark:text-indigo-400 font-medium': activeId === item.id,
          'pl-2 border-l-2 border-indigo-500': activeId === item.id,
          'pl-4': activeId !== item.id
        }"
        @click="setActiveId(item.id)"
      >
        {{ item.text }}
      </a>
    </nav>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
  name: 'TableOfContents',
  props: {
    items: {
      type: Array,
      required: true,
      validator: (items) => {
        return items.every(item => item.id && item.text);
      }
    }
  },
  setup(props) {
    const activeId = ref('');
    const observer = ref(null);

    const setActiveId = (id) => {
      activeId.value = id;
    };

    const handleIntersection = (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          activeId.value = entry.target.id;
        }
      });
    };

    onMounted(() => {
      const options = {
        root: null,
        rootMargin: '0px 0px -80% 0px',
        threshold: 0.1
      };

      observer.value = new IntersectionObserver(handleIntersection, options);
      
      // Observe all headings
      props.items.forEach(item => {
        const element = document.getElementById(item.id);
        if (element) {
          observer.value.observe(element);
        }
      });
    });

    onUnmounted(() => {
      if (observer.value) {
        observer.value.disconnect();
      }
    });

    return {
      activeId,
      setActiveId
    };
  }
};
</script>
