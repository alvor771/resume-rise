<template>
  <div class="resume-generator">
    <div class="">
      <h1 class="text-3xl font-bold mb-8">{{ $t('resume.generator.title') }}</h1>
      <router-view />
    </div>
  </div>
</template>

<script>
import { useResumeStore } from '../store'

export default {
  name: 'ResumeGenerator',
  beforeRouteEnter(to, from, next) {
    // Check if template is selected when required
    if (to.matched.some(record => record.meta.requiresTemplate)) {
      next(_vm => {
        const resumeStore = useResumeStore()
        if (!resumeStore.getCurrentTemplate) {
          next({ name: 'template-selection' })
          return
        }
      })
      return
    }
    next()
  }
}
</script>

<style scoped>
.resume-generator {
  min-height: calc(100vh - 4rem);
  background-color: #f9fafb;
}
</style>
