<template>
  <div class="space-y-4">
    <h3 class="text-sm font-semibold text-gray-700">Inspector</h3>

    <div v-if="!selected" class="text-gray-500 text-sm">Select an element to edit its properties.</div>

    <div v-else class="space-y-3">
      <div class="text-xs text-gray-500">ID: {{ selected.id }} • Type: {{ selected.type }}</div>

      <div class="grid grid-cols-2 gap-2">
        <label class="block text-xs text-gray-600">X
          <input class="form-input" type="number" v-model.number="local.x" />
        </label>
        <label class="block text-xs text-gray-600">Y
          <input class="form-input" type="number" v-model.number="local.y" />
        </label>
        <label class="block text-xs text-gray-600">W
          <input class="form-input" type="number" v-model.number="local.width" />
        </label>
        <label class="block text-xs text-gray-600">H
          <input class="form-input" type="number" v-model.number="local.height" />
        </label>
        <label class="block text-xs text-gray-600">Rot
          <input class="form-input" type="number" v-model.number="local.rotation" />
        </label>
      </div>

      <template v-if="selected.type === 'text'">
        <label class="block text-xs text-gray-600">Text
          <textarea class="form-textarea" rows="3" v-model="local.props.text"></textarea>
        </label>
        <div class="grid grid-cols-2 gap-2">
          <label class="block text-xs text-gray-600">Font Size
            <input class="form-input" type="number" v-model.number="local.props.fontSize" />
          </label>
          <label class="block text-xs text-gray-600">Color
            <input class="form-input" type="color" v-model="local.props.fill" />
          </label>
        </div>
        <label class="block text-xs text-gray-600">Align
          <select class="form-select" v-model="local.props.align">
            <option value="left">left</option>
            <option value="center">center</option>
            <option value="right">right</option>
          </select>
        </label>
      </template>

      <template v-else-if="selected.type === 'rect'">
        <div class="grid grid-cols-2 gap-2">
          <label class="block text-xs text-gray-600">Fill
            <input class="form-input" type="color" v-model="local.props.fill" />
          </label>
          <label class="block text-xs text-gray-600">Radius
            <input class="form-input" type="number" v-model.number="local.props.radius" />
          </label>
        </div>
      </template>

      <template v-else-if="selected.type === 'image'">
        <label class="block text-xs text-gray-600">Source URL
          <input class="form-input" type="text" v-model="local.props.src" />
        </label>
        <label class="block text-xs text-gray-600">Object Fit
          <select class="form-select" v-model="local.props.objectFit">
            <option value="cover">cover</option>
            <option value="contain">contain</option>
            <option value="fill">fill</option>
          </select>
        </label>
      </template>

      <div class="flex gap-2 pt-2">
        <button class="btn btn-outline" @click="apply">Apply</button>
        <button class="btn btn-outline" @click="remove">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive, watch } from 'vue'
import { useResumeStore } from '../store'

const store = useResumeStore()
const selected = computed(() => store.selectedElement)

const local = reactive({ x: 0, y: 0, width: 0, height: 0, rotation: 0, props: {} })

watch(selected, (el) => {
  if (!el) return
  Object.assign(local, {
    x: el.x ?? 0,
    y: el.y ?? 0,
    width: el.width ?? 0,
    height: el.height ?? 0,
    rotation: el.rotation ?? 0,
    props: { ...el.props }
  })
}, { immediate: true })

function apply() {
  if (!selected.value) return
  store.updateElement(selected.value.id, { x: local.x, y: local.y, width: local.width, height: local.height, rotation: local.rotation, props: { ...local.props } })
}
function remove() {
  if (!selected.value) return
  store.removeElement(selected.value.id)
}
</script>

<style scoped>
.form-input { @apply w-full h-8 px-2 border rounded; }
.form-select { @apply w-full h-8 px-2 border rounded bg-white; }
.form-textarea { @apply w-full px-2 py-1 border rounded; }
.btn { @apply px-3 py-1 rounded border text-sm; }
.btn-outline { @apply border-gray-300 text-gray-700 hover:bg-gray-50; }
</style>
