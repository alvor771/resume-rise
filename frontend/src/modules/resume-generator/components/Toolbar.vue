<template>
  <div class="flex flex-wrap items-center gap-2">
    <button class="btn" @click="$emit('add-text')">Text</button>
    <button class="btn" @click="$emit('add-rect')">Rect</button>
    <label class="btn cursor-pointer">
      Image
      <input type="file" accept="image/*" class="hidden" @change="onPickImage" />
    </label>
    <span class="mx-2 w-px h-6 bg-gray-300" />
    <button class="btn" @click="$emit('bring-forward')">Bring ↑</button>
    <button class="btn" @click="$emit('send-backward')">Send ↓</button>
    <button class="btn" @click="$emit('delete')">Delete</button>
    <span class="mx-2 w-px h-6 bg-gray-300" />
    <button class="btn" @click="$emit('zoom-in')">Zoom +</button>
    <button class="btn" @click="$emit('zoom-reset')">100%</button>
    <button class="btn" @click="$emit('zoom-out')">Zoom -</button>
    <button class="btn" @click="$emit('toggle-grid')">Grid</button>
    <button class="btn" @click="$emit('toggle-snap')">Snap</button>
    <button class="btn" @click="$emit('toggle-guides')">Guides</button>
    <button class="btn" @click="$emit('cycle-snap-size')">SnapSize</button>
    <span class="text-xs text-gray-600">{{$attrs.snapSize ? $attrs.snapSize + 'px' : ''}}</span>
    <span class="mx-2 w-px h-6 bg-gray-300" />
    <button class="btn" @click="$emit('center-x')">Center X</button>
    <button class="btn" @click="$emit('center-y')">Center Y</button>
    <button class="btn" @click="$emit('duplicate')">Duplicate</button>
    <span class="mx-2 w-px h-6 bg-gray-300" />
    <button class="btn btn-primary" @click="$emit('preview')">Preview</button>
    <button class="btn btn-success" @click="$emit('export-pdf')">Export PDF</button>
  </div>
</template>

<script setup>
defineOptions({ name: 'ResumeToolbar' })
const emit = defineEmits(['add-text','add-rect','image','bring-forward','send-backward','delete','zoom-in','zoom-out','zoom-reset','toggle-grid','toggle-snap','toggle-guides','cycle-snap-size','center-x','center-y','duplicate','preview','export-pdf'])

function onPickImage(e) {
  const file = e.target.files?.[0]
  if (!file) return
  emit('image', file)
}
</script>

<style scoped>
.btn { @apply px-3 py-1.5 rounded border border-gray-300 text-sm hover:bg-gray-50; }
.btn-primary { @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700; }
.btn-success { @apply bg-green-600 text-white border-green-600 hover:bg-green-700; }
</style>
