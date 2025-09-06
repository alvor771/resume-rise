<template>
  <div class="cv-builder-view">
    <div class="container mx-auto py-8 px-4">
      <h1 class="text-3xl font-bold mb-8">CV Builder</h1>
      <CVBuilder />
    </div>
  </div>
</template>

<script setup>
import { useHead } from '@vueuse/head';
import CVBuilder from '@/modules/cv-builder/CVBuilder.vue';

useHead({
  title: 'CV Builder - ResumeRise',
  meta: [
    { name: 'description', content: 'Build your professional CV with our easy-to-use CV Builder' }
  ]
});
</script>

<style scoped>
.cv-builder-view {
  min-height: calc(100vh - 200px);
  background-color: #f8fafc;
}
</style>
