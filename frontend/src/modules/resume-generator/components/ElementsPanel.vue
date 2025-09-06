<template>
  <div class="space-y-3">
    <h3 class="text-sm font-semibold text-gray-700">Elements</h3>
    <div class="grid grid-cols-2 gap-2">
      <button class="btn btn-outline" @click="addText">Text</button>
      <button class="btn btn-outline" @click="addRect">Rectangle</button>
      <label class="btn btn-outline cursor-pointer text-center">
        Image
        <input type="file" class="hidden" accept="image/*" @change="onPickImage" />
      </label>
    </div>
  </div>
</template>

<script setup>
import { useResumeStore } from '../store'

const resume = useResumeStore()

function addText() {
  resume.addElement({ type: 'text', width: 400, height: 60, props: { text: 'Double click to edit', fontSize: 24, fill: '#111827' } })
}
function addRect() {
  resume.addElement({ type: 'rect', width: 300, height: 100, props: { fill: '#E5E7EB', radius: 8 } })
}
function onPickImage(e) {
  const file = e.target.files?.[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = () => {
    resume.addElement({ type: 'image', width: 180, height: 180, props: { src: reader.result } })
  }
  reader.readAsDataURL(file)
}
</script>

<style scoped>
.btn { @apply px-3 py-2 rounded border text-sm; }
.btn-outline { @apply border-gray-300 text-gray-700 hover:bg-gray-50; }
</style>
