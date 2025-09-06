<template>
  <div class="resume-preview">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-semibold">{{ $t('resume.preview') }}</h2>
      <div class="space-x-2">
        <button @click="$router.go(-1)" class="btn btn-secondary">{{ $t('common.back') }}</button>
        <button @click="exportPdf" class="btn btn-primary">{{ $t('resume.export_pdf') }}</button>
      </div>
    </div>

    <div class="relative bg-gray-200/60 p-6 rounded border border-gray-300 overflow-auto">
      <div
        ref="stageRef"
        id="resume-preview-stage"
        class="relative bg-white shadow mx-auto"
        :style="{ width: canvas.width + 'px', height: canvas.height + 'px' }"
      >
        <div
          v-for="el in orderedElements"
          :key="el.id"
          class="absolute"
          :style="elStyle(el)"
        >
          <component :is="elementComponent(el)" :el="el" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref, h } from 'vue'
import { useResumeStore } from '../store'

export default {
  name: 'ResumePreview',
  setup() {
    const store = useResumeStore()
    const canvas = computed(() => store.canvas)
    const orderedElements = computed(() => store.orderedElements)
    const stageRef = ref(null)

    function elStyle(el) {
      return {
        left: el.x + 'px',
        top: el.y + 'px',
        width: (el.width ?? 200) + 'px',
        height: (el.height ?? 50) + 'px',
        transform: `rotate(${el.rotation || 0}deg)`
      }
    }
    function elementComponent(el) {
      if (el.type === 'rect') return RectEl
      if (el.type === 'image') return ImageEl
      return TextEl
    }

    async function exportPdf() {
      const el = stageRef.value
      if (!el) return
      const html2pdf = (await import('html2pdf.js')).default
      const opt = {
        margin:       0,
        filename:     'resume.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'px', format: [canvas.value.width, canvas.value.height], orientation: 'portrait' }
      }
      await html2pdf().from(el).set(opt).save()
    }

    return { canvas: canvas.value, orderedElements, stageRef, elStyle, elementComponent, exportPdf }
  }
}

// lightweight renderers (same as CanvasBoard)
const TextEl = {
  props: { el: { type: Object, required: true } },
  setup(props) {
    const style = computed(() => ({
      width: '100%', height: '100%',
      color: props.el.props?.fill || '#111827',
      fontSize: (props.el.props?.fontSize || 18) + 'px',
      fontFamily: props.el.props?.fontFamily || 'Inter, system-ui, sans-serif',
      fontWeight: props.el.props?.fontStyle?.includes('bold') ? '700' : '400',
      textAlign: props.el.props?.align || 'left',
      whiteSpace: 'pre-wrap',
      lineHeight: '1.2',
    }))
    return () => h('div', { style: style.value }, props.el.props?.text || 'Text')
  }
}

const RectEl = {
  props: { el: { type: Object, required: true } },
  setup(props) {
    const style = computed(() => ({
      width: '100%', height: '100%',
      backgroundColor: props.el.props?.fill || '#E5E7EB',
      borderRadius: (props.el.props?.radius || 0) + 'px',
    }))
    return () => h('div', { style: style.value })
  }
}

const ImageEl = {
  props: { el: { type: Object, required: true } },
  setup(props) {
    const style = computed(() => ({
      width: '100%',
      height: '100%',
      objectFit: props.el.props?.objectFit || 'cover',
      borderRadius: (props.el.props?.radius ?? 0) + 'px',
    }))
    return () => h('img', { draggable: 'false', style: style.value, src: props.el.props?.src || '', alt: '' })
  }
}
</script>

<style scoped>
.btn { @apply px-3 py-1.5 rounded border border-gray-300 text-sm hover:bg-gray-50; }
.btn-primary { @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700; }
.btn-secondary { @apply bg-gray-100 text-gray-800 hover:bg-gray-200 border border-gray-300; }
</style>
