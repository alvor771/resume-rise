<template>
  <div class="w-full">
    <div class="flex items-center justify-between mb-3 text-sm text-gray-600">
      <div>
        <span>Canvas: {{ canvas.width }}×{{ canvas.height }}</span>
        <span class="ml-3">Zoom: {{ (canvas.zoom * 100).toFixed(0) }}%</span>
      </div>
      <div class="space-x-2">
        <button class="btn btn-sm btn-outline" @click="setZoom(canvas.zoom - 0.1)">-</button>
        <button class="btn btn-sm btn-outline" @click="setZoom(1)">100%</button>
        <button class="btn btn-sm btn-outline" @click="setZoom(canvas.zoom + 0.1)">+</button>
        <button class="btn btn-sm btn-outline" @click="toggleGrid">Grid: {{ canvas.showGrid ? 'on' : 'off' }}</button>
      </div>
    </div>

    <div class="relative mx-auto bg-gray-200/60 p-6 rounded border border-gray-300 overflow-auto" style="max-height: 75vh">
      <div
        class="relative bg-white shadow"
        :style="stageStyle"
        ref="stageRef"
        id="resume-canvas-stage"
        @click.self="clearSelection"
      >
        <!-- A4 pages backgrounds -->
        <div
          v-for="p in pagesCount"
          :key="'pg-'+p"
          class="absolute bg-white rounded-sm shadow"
          :style="{ left: 0, top: ((p-1) * canvas.height) + 'px', width: canvas.width + 'px', height: canvas.height + 'px', boxShadow: '0 0 0 1px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.06)' }"
        />
        <!-- page separators -->
        <div v-for="p in (pagesCount - 1)" :key="'sep-'+p" class="absolute w-full border-t border-dashed border-gray-300"
             :style="{ top: (p * canvas.height) + 'px', left: 0 }" />
        <!-- optional grid -->
        <template v-if="canvas.showGrid">
          <div
            v-for="n in gridLines"
            :key="'v-'+n"
            class="absolute border-l border-gray-100"
            :style="{ left: (n * 40) + 'px', top: 0, height: (canvas.height * pagesCount) + 'px', transform: zoomTransform }"
          />
          <div
            v-for="n in gridRows"
            :key="'h-'+n"
            class="absolute border-t border-gray-100"
            :style="{ top: (n * 40) + 'px', left: 0, width: canvas.width + 'px', transform: zoomTransform }"
          />
        </template>

        <!-- elements -->
        <div
          v-for="el in orderedElements"
          :key="el.id"
          :class="elClass(el)"
          :style="elStyle(el)"
          @mousedown="onElMouseDown(el, $event)"
          @click.stop="onElementClick(el, $event)"
        >
          <component :is="elementComponent(el)" :el="el" />

          <div v-if="isSelected(el.id)" class="absolute inset-0 ring-2 ring-blue-500 pointer-events-none"></div>
          <!-- resize handles -->
          <template v-if="isSelected(el.id)">
            <!-- corners -->
            <div class="handle handle-tl" @mousedown.stop="startResize(el, 'tl', $event)"></div>
            <div class="handle handle-tr" @mousedown.stop="startResize(el, 'tr', $event)"></div>
            <div class="handle handle-bl" @mousedown.stop="startResize(el, 'bl', $event)"></div>
            <div class="handle handle-br" @mousedown.stop="startResize(el, 'br', $event)"></div>
            <!-- sides -->
            <div class="handle handle-t" @mousedown.stop="startResize(el, 't', $event)"></div>
            <div class="handle handle-b" @mousedown.stop="startResize(el, 'b', $event)"></div>
            <div class="handle handle-l" @mousedown.stop="startResize(el, 'l', $event)"></div>
            <div class="handle handle-r" @mousedown.stop="startResize(el, 'r', $event)"></div>
          </template>
        </div>

        <!-- alignment guides overlay -->
        <template v-if="canvas.guidesEnabled && (guides.v.length || guides.h.length)">
          <div v-for="(x, i) in guides.v" :key="'gv'+i" class="absolute bg-pink-500/80" :style="{ left: x + 'px', top: 0, width: '1px', height: canvas.height + 'px' }"></div>
          <div v-for="(y, i) in guides.h" :key="'gh'+i" class="absolute bg-pink-500/80" :style="{ top: y + 'px', left: 0, height: '1px', width: canvas.width + 'px' }"></div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, h, nextTick } from 'vue'
import { useResumeStore } from '../store'

const resume = useResumeStore()
const canvas = computed(() => resume.canvas)
const orderedElements = computed(() => resume.orderedElements)

const stageRef = ref(null)
const isShift = ref(false)
const guides = ref({ v: [], h: [] })

function clearGuides() { guides.value = { v: [], h: [] } }

// class helper for element wrapper
function elClass(el) {
  return el.type === 'text' ? 'absolute' : 'absolute select-none'
}

function isContentEditableTarget(evt) {
  const t = evt.target
  if (!t) return false
  if (t.getAttribute && t.getAttribute('contenteditable') === 'true') return true
  if (t.closest && t.closest('[contenteditable="true"]')) return true
  return false
}

function onElMouseDown(el, evt) {
  // If clicking into a contenteditable text element, allow caret placement and editing
  if (el.type === 'text' && isContentEditableTarget(evt)) {
    select(el.id)
    // stop propagation so canvas handlers don't start drag/deselect
    evt.stopPropagation()
    return
  }
  // Otherwise, start drag as before
  evt.stopPropagation()
  startDrag(el, evt)
}

function rectOf(el) {
  const x = el.x || 0, y = el.y || 0, w = el.width || 0, h = el.height || 0
  return { x, y, w, h, r: x + w, b: y + h, cx: x + w / 2, cy: y + h / 2 }
}

function computeAlignment(target, others, threshold = 5) {
  const t = rectOf(target)
  const vs = new Set(), hs = new Set()
  for (const el of others) {
    const o = rectOf(el)
    // vertical candidates: left, centerX, right
    const vPairs = [
      { t: t.x, o: o.x }, // left-left
      { t: t.cx, o: o.cx }, // center-center
      { t: t.r, o: o.r }, // right-right
      { t: t.x, o: o.cx }, // left-center
      { t: t.r, o: o.cx }, // right-center
      { t: t.cx, o: o.x }, // center-left
      { t: t.cx, o: o.r }, // center-right
    ]
    for (const { t: tv, o: ov } of vPairs) {
      if (Math.abs(tv - ov) <= threshold) { vs.add(ov) }
    }
    // horizontal candidates: top, centerY, bottom
    const hPairs = [
      { t: t.y, o: o.y },
      { t: t.cy, o: o.cy },
      { t: t.b, o: o.b },
      { t: t.y, o: o.cy },
      { t: t.b, o: o.cy },
      { t: t.cy, o: o.y },
      { t: t.cy, o: o.b },
    ]
    for (const { t: tv, o: ov } of hPairs) {
      if (Math.abs(tv - ov) <= threshold) { hs.add(ov) }
    }
  }
  // canvas edges/centers
  const cw = canvas.value.width, ch = canvas.value.height
  const canvasV = [0, cw / 2, cw] // left, centerX, right
  const canvasH = [0, ch / 2, ch] // top, centerY, bottom
  for (const cv of canvasV) {
    const candidates = [t.x, t.cx, t.r]
    if (candidates.some(v => Math.abs(v - cv) <= threshold)) vs.add(cv)
  }
  for (const cy of canvasH) {
    const candidates = [t.y, t.cy, t.b]
    if (candidates.some(v => Math.abs(v - cy) <= threshold)) hs.add(cy)
  }
  return { v: Array.from(vs), h: Array.from(hs) }
}

// Paging: compute number of A4 pages needed to fit all elements
const pagesCount = computed(() => {
  const h = canvas.value.height
  if (!h) return 1
  const maxY = resume.canvas.elements.length
    ? Math.max(...resume.canvas.elements.map(e => (e.y || 0) + (e.height || 0)))
    : 0
  return Math.max(1, Math.ceil((maxY + 40) / h)) // 40px bottom margin
})

const stageStyle = computed(() => ({
  width: canvas.value.width + 'px',
  height: (canvas.value.height * pagesCount.value) + 'px',
  transform: `scale(${canvas.value.zoom})`,
  transformOrigin: 'top left',
}))

const zoomTransform = computed(() => `scale(${canvas.value.zoom})`)

const gridLines = computed(() => Math.floor(canvas.value.width / 40))
const gridRows = computed(() => Math.floor((canvas.value.height * pagesCount.value) / 40))

function elStyle(el) {
  return {
    left: el.x + 'px',
    top: el.y + 'px',
    width: (el.width ?? 200) + 'px',
    height: (el.height ?? 50) + 'px',
    transform: `rotate(${el.rotation || 0}deg)`,
  }
}

function elementComponent(el) {
  if (el.type === 'group') return GroupEl
  if (el.type === 'rect') return RectEl
  if (el.type === 'image') return ImageEl
  return TextEl
}

function isSelected(id) { return canvas.value.selectedElementId === id }
function select(id) { resume.selectElement(id) }
function onElementClick(el, evt) {
  if (isContentEditableTarget(evt)) return
  // Alt+Click on a child selects its parent group (if any)
  if (evt?.altKey && el.parentId) {
    resume.selectElement(el.parentId)
    return
  }
  select(el.id)
}
function clearSelection() { resume.selectElement(null) }
function setZoom(val) { resume.setZoom(val) }
function toggleGrid() { resume.toggleGrid() }

// basic drag
let dragging = null // { id, baseX, baseY, startX, startY, children?: [{id, baseX, baseY}] }
function startDrag(el, evt) {
  dragging = {
    id: el.id,
    baseX: el.x || 0,
    baseY: el.y || 0,
    startX: evt.clientX,
    startY: evt.clientY,
  }
  // Capture children baseline when dragging a group
  if (el.type === 'group' && Array.isArray(el.children)) {
    dragging.children = el.children
      .map(cid => resume.canvas.elements.find(e => e.id === cid))
      .filter(Boolean)
      .map(ch => ({ id: ch.id, baseX: ch.x || 0, baseY: ch.y || 0 }))
  }
  window.addEventListener('mousemove', onMove)
  window.addEventListener('mouseup', onUp, { once: true })
}
function onMove(evt) {
  if (!dragging) return
  const dx = (evt.clientX - dragging.startX) / canvas.value.zoom
  const dy = (evt.clientY - dragging.startY) / canvas.value.zoom
  let nextX = Math.max(0, dragging.baseX + dx)
  let nextY = Math.max(0, dragging.baseY + dy)
  // snap
  if (canvas.value.snapEnabled) {
    const s = canvas.value.snapSize || 8
    nextX = Math.round(nextX / s) * s
    nextY = Math.round(nextY / s) * s
  }
  // alignment guides vs other elements and canvas
  const self = resume.canvas.elements.find(e => e.id === dragging.id)
  let others = resume.canvas.elements.filter(e => e.id !== dragging.id)
  if (self?.type === 'group' && Array.isArray(self.children)) {
    const set = new Set(self.children)
    others = others.filter(e => !set.has(e.id))
  }
  const temp = { ...self, x: nextX, y: nextY }
  const threshold = canvas.value.guideThreshold || 5
  const g = canvas.value.guidesEnabled ? computeAlignment(temp, others, threshold) : { v: [], h: [] }
  guides.value = g
  // if aligning center/edges, subtly snap x/y to nearest guide by adjusting edge/center
  if (g.v.length) {
    // find nearest and map to x so that chosen edge/center equals guide
    const t = rectOf(temp)
    const nearest = g.v.reduce((a, b) => Math.abs((t.cx) - b) < Math.abs((t.cx) - a) ? b : a, g.v[0])
    // decide based on which is closer among x, cx, r
    const diffs = [
      { k: 'x', d: Math.abs(t.x - nearest) },
      { k: 'cx', d: Math.abs(t.cx - nearest) },
      { k: 'r', d: Math.abs(t.r - nearest) },
    ].sort((a,b)=>a.d-b.d)
    const best = diffs[0].k
    if (best === 'x') nextX += (nearest - t.x)
    else if (best === 'cx') nextX += (nearest - t.cx)
    else nextX += (nearest - t.r)
  }
  if (g.h.length) {
    const t = rectOf({ ...temp, x: nextX })
    const nearest = g.h.reduce((a, b) => Math.abs((t.cy) - b) < Math.abs((t.cy) - a) ? b : a, g.h[0])
    const diffs = [
      { k: 'y', d: Math.abs(t.y - nearest) },
      { k: 'cy', d: Math.abs(t.cy - nearest) },
      { k: 'b', d: Math.abs(t.b - nearest) },
    ].sort((a,b)=>a.d-b.d)
    const best = diffs[0].k
    if (best === 'y') nextY += (nearest - t.y)
    else if (best === 'cy') nextY += (nearest - t.cy)
    else nextY += (nearest - t.b)
  }
  resume.updateElement(dragging.id, { x: nextX, y: nextY })
  // Move children when dragging a group
  if (dragging.children) {
    const scaleX = 1
    const scaleY = 1
    dragging.children.forEach(ch => {
      const relX = (ch.baseX - dragging.baseX) * scaleX
      const relY = (ch.baseY - dragging.baseY) * scaleY
      const nx = nextX + relX
      const ny = nextY + relY
      resume.updateElement(ch.id, { x: Math.round(nx), y: Math.round(ny) })
    })
  }
}
function onUp() {
  dragging = null
  window.removeEventListener('mousemove', onMove)
  clearGuides()
}

onBeforeUnmount(() => {
  window.removeEventListener('mousemove', onMove)
  window.removeEventListener('mousemove', onResizeMove)
  window.removeEventListener('keydown', onKey)
  window.removeEventListener('keyup', onKeyUp)
})

onMounted(() => {
  window.addEventListener('keydown', onKey)
  window.addEventListener('keyup', onKeyUp)
})

function onKeyUp(e) {
  if (e.key === 'Shift') isShift.value = false
}

function onKey(e) {
  // track shift
  if (e.key === 'Shift') { isShift.value = true; return }

  const selId = canvas.value.selectedElementId
  const hasSel = !!selId
  const stepBase = canvas.value.snapEnabled ? (canvas.value.snapSize || 8) : 1
  const step = isShift.value ? stepBase * 5 : stepBase

  // shortcuts not tied to selection
  if (e.ctrlKey && !e.shiftKey && (e.key === 'g' || e.key === 'G')) { e.preventDefault(); toggleGrid(); return }
  if (e.ctrlKey && e.shiftKey && (e.key === 'g' || e.key === 'G')) { e.preventDefault(); resume.toggleSnap(); return }
  if (e.ctrlKey && !e.shiftKey && (e.key === 'e' || e.key === 'E')) { e.preventDefault(); resume.centerSelectedX(); return }
  if (e.ctrlKey && e.shiftKey && (e.key === 'e' || e.key === 'E')) { e.preventDefault(); resume.centerSelectedY(); return }

  if (!hasSel) return

  const el = resume.canvas.elements.find(x => x.id === selId)
  if (!el) return

  let handled = false
  let nx = el.x || 0
  let ny = el.y || 0
  if (e.key === 'ArrowLeft') { nx = Math.max(0, nx - step); handled = true }
  if (e.key === 'ArrowRight') { nx = Math.min(canvas.value.width - (el.width || 0), nx + step); handled = true }
  if (e.key === 'ArrowUp') { ny = Math.max(0, ny - step); handled = true }
  if (e.key === 'ArrowDown') { ny = Math.min(canvas.value.height - (el.height || 0), ny + step); handled = true }
  if (handled) {
    e.preventDefault()
    resume.updateElement(selId, { x: nx, y: ny })
    return
  }

  if (e.key === 'Delete') { e.preventDefault(); resume.removeElement(selId); return }
  if (e.ctrlKey && (e.key === 'd' || e.key === 'D')) { e.preventDefault(); resume.duplicateElement(selId); return }
}

// resize logic
let resizing = null // { id, baseX, baseY, baseW, baseH, startX, startY, dir, children?: [{id, baseX, baseY, baseW, baseH}] }
function startResize(el, dir, evt) {
  // cancel drag if any
  if (dragging) {
    window.removeEventListener('mousemove', onMove)
    dragging = null
  }
  resizing = {
    id: el.id,
    baseX: el.x || 0,
    baseY: el.y || 0,
    baseW: el.width || 0,
    baseH: el.height || 0,
    startX: evt.clientX,
    startY: evt.clientY,
    dir,
  }
  // Capture children baseline when resizing a group
  if (el.type === 'group' && Array.isArray(el.children)) {
    resizing.children = el.children
      .map(cid => resume.canvas.elements.find(e => e.id === cid))
      .filter(Boolean)
      .map(ch => ({ id: ch.id, baseX: ch.x || 0, baseY: ch.y || 0, baseW: ch.width || 0, baseH: ch.height || 0 }))
  }
  window.addEventListener('mousemove', onResizeMove)
  window.addEventListener('mouseup', endResize, { once: true })
}
function onResizeMove(evt) {
  if (!resizing) return
  const minW = 20, minH = 20
  const dx = (evt.clientX - resizing.startX) / canvas.value.zoom
  const dy = (evt.clientY - resizing.startY) / canvas.value.zoom

  let x = resizing.baseX
  let y = resizing.baseY
  let w = resizing.baseW
  let h = resizing.baseH

  switch (resizing.dir) {
    case 'br':
      w = Math.max(minW, resizing.baseW + dx)
      h = Math.max(minH, resizing.baseH + dy)
      break
    case 'bl':
      w = Math.max(minW, resizing.baseW - dx)
      h = Math.max(minH, resizing.baseH + dy)
      x = resizing.baseX + (resizing.baseW - w)
      break
    case 'tr':
      w = Math.max(minW, resizing.baseW + dx)
      h = Math.max(minH, resizing.baseH - dy)
      y = resizing.baseY + (resizing.baseH - h)
      break
    case 'tl':
      w = Math.max(minW, resizing.baseW - dx)
      h = Math.max(minH, resizing.baseH - dy)
      x = resizing.baseX + (resizing.baseW - w)
      y = resizing.baseY + (resizing.baseH - h)
      break
    case 'r':
      w = Math.max(minW, resizing.baseW + dx)
      break
    case 'l':
      w = Math.max(minW, resizing.baseW - dx)
      x = resizing.baseX + (resizing.baseW - w)
      break
    case 'b':
      h = Math.max(minH, resizing.baseH + dy)
      break
    case 't':
      h = Math.max(minH, resizing.baseH - dy)
      y = resizing.baseY + (resizing.baseH - h)
      break
  }

  // Shift to keep aspect ratio
  if (isShift.value) {
    const ratio = resizing.baseW / Math.max(1, resizing.baseH)
    // Adjust w/h depending on dominant change
    if (['l','r'].includes(resizing.dir)) {
      h = Math.max(minH, Math.round(w / ratio))
      if (['t','tl','tr'].includes(resizing.dir)) y = resizing.baseY + (resizing.baseH - h)
    } else if (['t','b'].includes(resizing.dir)) {
      w = Math.max(minW, Math.round(h * ratio))
      if (['l','tl','bl'].includes(resizing.dir)) x = resizing.baseX + (resizing.baseW - w)
    } else {
      // corner
      const dw = Math.abs(w - resizing.baseW)
      const dh = Math.abs(h - resizing.baseH)
      if (dw > dh) {
        h = Math.max(minH, Math.round(w / ratio))
        if (['t','tl','tr'].includes(resizing.dir)) y = resizing.baseY + (resizing.baseH - h)
      } else {
        w = Math.max(minW, Math.round(h * ratio))
        if (['l','tl','bl'].includes(resizing.dir)) x = resizing.baseX + (resizing.baseW - w)
      }
    }
  }

  // snap size
  if (canvas.value.snapEnabled) {
    const s = canvas.value.snapSize || 8
    x = Math.round(x / s) * s
    y = Math.round(y / s) * s
    w = Math.max(minW, Math.round(w / s) * s)
    h = Math.max(minH, Math.round(h / s) * s)
  }

  // alignment guides during resize (align moving edges)
  const self = resume.canvas.elements.find(e => e.id === resizing.id)
  let others = resume.canvas.elements.filter(e => e.id !== resizing.id)
  if (self?.type === 'group' && Array.isArray(self.children)) {
    const set = new Set(self.children)
    others = others.filter(e => !set.has(e.id))
  }
  const temp = { ...self, x, y, width: w, height: h }
  const threshold = canvas.value.guideThreshold || 5
  const g = canvas.value.guidesEnabled ? computeAlignment(temp, others, threshold) : { v: [], h: [] }
  guides.value = g
  // For vertical alignment, prioritize matching the edge being dragged
  if (g.v.length) {
    const t = rectOf(temp)
    const nearest = g.v.reduce((a, b) => Math.abs((t.cx) - b) < Math.abs((t.cx) - a) ? b : a, g.v[0])
    if (['l','tl','bl'].includes(resizing.dir)) x += (nearest - t.x)
    else if (['r','tr','br'].includes(resizing.dir)) x += (nearest - t.r)
    else x += (nearest - t.cx)
  }
  if (g.h.length) {
    const t = rectOf({ ...temp, x })
    const nearest = g.h.reduce((a, b) => Math.abs((t.cy) - b) < Math.abs((t.cy) - a) ? b : a, g.h[0])
    if (['t','tl','tr'].includes(resizing.dir)) y += (nearest - t.y)
    else if (['b','bl','br'].includes(resizing.dir)) y += (nearest - t.b)
    else y += (nearest - t.cy)
  }

  // constrain within canvas
  x = Math.max(0, Math.min(x, canvas.value.width - w))
  y = Math.max(0, Math.min(y, canvas.value.height - h))

  const target = resume.canvas.elements.find(e => e.id === resizing.id)
  if (target && target.type === 'group' && Array.isArray(resizing.children) && resizing.baseW && resizing.baseH) {
    const scaleX = w / resizing.baseW
    const scaleY = h / resizing.baseH
    // update group first
    resume.updateElement(resizing.id, { x, y, width: w, height: h })
    // scale children relative to group's top-left
    resizing.children.forEach(ch => {
      const relX = (ch.baseX - resizing.baseX) * scaleX
      const relY = (ch.baseY - resizing.baseY) * scaleY
      const nx = x + relX
      const ny = y + relY
      const nw = Math.max(1, ch.baseW * scaleX)
      const nh = Math.max(1, ch.baseH * scaleY)
      resume.updateElement(ch.id, { x: Math.round(nx), y: Math.round(ny), width: Math.round(nw), height: Math.round(nh) })
    })
  } else {
    resume.updateElement(resizing.id, { x, y, width: w, height: h })
  }
}
function endResize() {
  resizing = null
  window.removeEventListener('mousemove', onResizeMove)
  clearGuides()
}

// sub components for elements
const TextEl = {
  props: { el: { type: Object, required: true } },
  emits: ['update'],
  setup(props) {
    const store = useResumeStore()
    const editing = ref(false)
    const elRef = ref(null)
    const style = computed(() => ({
      width: '100%', height: '100%',
      color: props.el.props?.fill || '#111827',
      fontSize: (props.el.props?.fontSize || 18) + 'px',
      fontFamily: props.el.props?.fontFamily || 'Inter, system-ui, sans-serif',
      fontWeight: props.el.props?.fontStyle?.includes('bold') ? '700' : '400',
      textAlign: props.el.props?.align || 'left',
      whiteSpace: 'pre-wrap',
      lineHeight: '1.2',
      outline: 'none',
      cursor: 'text',
      userSelect: 'text',
    }))
    const onFocus = () => { editing.value = true }
    const onBlur = (e) => {
      editing.value = false
      const txt = e.target.innerText
      if (txt !== (props.el.props?.text || '')) {
        store.updateElement(props.el.id, { props: { text: txt } })
      }
    }
    const enterEdit = async () => {
      if (editing.value) return
      editing.value = true
      await nextTick()
      const node = elRef.value
      if (node && node.focus) {
        node.focus()
        const range = document.createRange()
        range.selectNodeContents(node)
        range.collapse(false)
        const sel = window.getSelection()
        sel.removeAllRanges()
        sel.addRange(range)
      }
    }
    const onKeydown = (e) => {
      if (!editing.value) return
      // prevent canvas shortcuts while editing
      e.stopPropagation()
      if (e.key === 'Escape') {
        // cancel editing: blur triggers save
        e.preventDefault()
        elRef.value && elRef.value.blur()
      }
    }
    return () => h('div', {
      contenteditable: editing.value ? 'true' : 'false',
      style: style.value,
      draggable: false,
      spellcheck: 'false',
      ref: elRef,
      onMousedown: async (e) => {
        // Enter edit mode only on Left Ctrl + Left Click
        if (!editing.value) {
          if (e.button === 0 && e.ctrlKey) {
            e.stopPropagation()
            e.preventDefault()
            await enterEdit()
            return
          }
          // not editing and no ctrl: allow propagation for drag/select
          return
        }
        // while editing, don't let canvas drag/selection run
        e.stopPropagation()
      },
      onClick: (e) => { if (editing.value) e.stopPropagation() },
      onKeydown,
      onFocus,
      onBlur,
    }, editing.value ? undefined : (props.el.props?.text || 'Text'))
  }
}

const RectEl = {
  props: { el: { type: Object, required: true } },
  setup(props) {
    const style = computed(() => ({
      width: '100%', height: '100%',
      backgroundColor: props.el.props?.fill || '#E5E7EB',
      borderRadius: (props.el.props?.radius || 0) + 'px',
      cursor: 'move',
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
      pointerEvents: 'none',
      borderRadius: (props.el.props?.radius ?? 0) + 'px',
    }))
    return () => h('img', { draggable: 'false', style: style.value, src: props.el.props?.src || '', alt: '' })
  }
}

const GroupEl = {
  props: { el: { type: Object, required: true } },
  setup() {
    const style = computed(() => ({
      width: '100%', height: '100%',
      border: '1px dashed rgba(59,130,246,0.5)',
      backgroundColor: 'transparent',
      pointerEvents: 'none',
    }))
    return () => h('div', { style: style.value })
  }
}
</script>

<style scoped>
.btn { @apply px-3 py-1 border rounded; }
.btn-outline { @apply border-gray-300 text-gray-700 hover:bg-gray-50; }
.btn-sm { @apply text-xs; }
/* resize handles */
.handle { @apply absolute w-3 h-3 bg-white border border-blue-500 rounded-sm; }
.handle-tl { @apply -top-1.5 -left-1.5 cursor-nwse-resize; }
.handle-tr { @apply -top-1.5 -right-1.5 cursor-nesw-resize; }
.handle-bl { @apply -bottom-1.5 -left-1.5 cursor-nesw-resize; }
.handle-br { @apply -bottom-1.5 -right-1.5 cursor-nwse-resize; }
.handle-t { @apply -top-1.5 left-1/2 -translate-x-1/2 cursor-ns-resize; }
.handle-b { @apply -bottom-1.5 left-1/2 -translate-x-1/2 cursor-ns-resize; }
.handle-l { @apply -left-1.5 top-1/2 -translate-y-1/2 cursor-ew-resize; }
.handle-r { @apply -right-1.5 top-1/2 -translate-y-1/2 cursor-ew-resize; }
</style>
