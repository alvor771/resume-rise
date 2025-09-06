import { defineStore } from "pinia";
import resumeService from "@/services/resumeService";

export const useResumeStore = defineStore("resume", {
  state: () => ({
    templates: [],
    selectedTemplate: null,
    resume: null,
    isLoading: false,
    error: null,
    // Canvas editor state
    canvas: {
      width: 1240, // A4 @ ~150dpi
      height: 1754,
      zoom: 1,
      showGrid: true,
      snapEnabled: true,
      snapSize: 8,
      guidesEnabled: true,
      guideThreshold: 5,
      elements: [], // [{ id, type, x, y, width, height, rotation, z, props }]
      selectedElementId: null,
    },
  }),

  getters: {
    getResume: (state) => state.resume,
    getTemplates: (state) => state.templates,
    getCurrentTemplate: (state) => state.selectedTemplate,
    getIsLoading: (state) => state.isLoading,
    selectedElement(state) {
      return state.canvas.elements.find((e) => e.id === state.canvas.selectedElementId) || null
    },
    orderedElements(state) {
      return [...state.canvas.elements].sort((a, b) => (a.z || 0) - (b.z || 0))
    },
  },

  actions: {
    async fetchTemplates() {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await resumeService.getTemplates();
        this.templates = response.templates;
      } catch (error) {
        console.error("Error fetching templates:", error);
        this.error = "Failed to fetch templates";
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    setSelectedTemplate(template) {
      this.selectedTemplate = template;
      // Initialize empty resume data structure based on template
      this.resume = {
        template: template.id,
        personalInfo: {},
        summary: "",
        experience: [],
        education: [],
        skills: [],
        languages: [],
        projects: template.sections.includes("projects") ? [] : undefined,
      };

      // Optionally seed canvas from template definition
      if (template.canvas && Array.isArray(template.canvas.elements)) {
        this.canvas.elements = template.canvas.elements.map((e, idx) => ({ ...e, id: e.id || crypto.randomUUID?.() || `el_${idx}` }))
      } else {
        // Minimal default layout
        this.canvas.elements = [
          {
            id: crypto.randomUUID?.() || 'title_1',
            type: 'text',
            x: 80,
            y: 80,
            width: 800,
            height: 60,
            rotation: 0,
            z: 1,
            props: { text: 'Your Name', fontSize: 42, fontFamily: 'Inter', fill: '#111827', align: 'left', fontStyle: 'bold' },
          },
        ]
      }
    },

    async saveResume() {
      this.isLoading = true;
      try {
        const response = await resumeService.saveResume(this.resume);
        return response.data;
      } catch (error) {
        this.error = error.message;
        throw error;
      } finally {
        this.isLoading = false;
      }
    },

    updatePersonalInfo(info) {
      this.resume.personalInfo = { ...this.resume.personalInfo, ...info };
    },

    addExperience(experience) {
      this.resume.experience.push(experience);
    },

    addEducation(education) {
      this.resume.education.push(education);
    },

    addSkill(skill) {
      this.resume.skills.push(skill);
    },

    addProject(project) {
      this.resume.projects.push(project);
    },

    // Canvas actions
    addElement(element) {
      const id = element.id || (crypto.randomUUID?.() || `el_${Date.now()}`)
      const z = element.z ?? (this.canvas.elements.length ? Math.max(...this.canvas.elements.map(e => e.z || 0)) + 1 : 1)
      this.canvas.elements.push({
        id,
        type: element.type || 'text',
        x: element.x ?? 100,
        y: element.y ?? 100,
        width: element.width ?? 200,
        height: element.height ?? 50,
        rotation: element.rotation ?? 0,
        z,
        props: element.props || {},
        // grouping
        parentId: element.parentId || null,
        children: element.type === 'group' ? (Array.isArray(element.children) ? [...element.children] : []) : undefined,
      })
      this.canvas.selectedElementId = id
      return id
    },

    updateElement(id, patch) {
      const idx = this.canvas.elements.findIndex(e => e.id === id)
      if (idx === -1) return
      this.canvas.elements[idx] = { ...this.canvas.elements[idx], ...patch, props: { ...this.canvas.elements[idx].props, ...(patch.props || {}) } }
    },

    removeElement(id) {
      const el = this.canvas.elements.find(e => e.id === id)
      if (!el) return
      // If removing a group, first ungroup its children
      if (el.type === 'group' && Array.isArray(el.children)) {
        el.children.forEach(cid => {
          const child = this.canvas.elements.find(e => e.id === cid)
          if (child) child.parentId = null
        })
      }
      // Remove the element itself
      this.canvas.elements = this.canvas.elements.filter(e => e.id !== id)
      if (this.canvas.selectedElementId === id) this.canvas.selectedElementId = null
    },

    selectElement(id) {
      this.canvas.selectedElementId = id
    },

    bringForward(id) {
      const el = this.canvas.elements.find(e => e.id === id); if (!el) return
      el.z = (el.z || 0) + 1
    },

    sendBackward(id) {
      const el = this.canvas.elements.find(e => e.id === id); if (!el) return
      el.z = (el.z || 0) - 1
    },

    setZoom(zoom) { this.canvas.zoom = Math.min(3, Math.max(0.2, zoom)) },
    toggleGrid() { this.canvas.showGrid = !this.canvas.showGrid },
    toggleSnap() { this.canvas.snapEnabled = !this.canvas.snapEnabled },
    setSnapSize(px) { const v = Number(px) || 8; this.canvas.snapSize = Math.max(2, Math.min(64, v)) },
    cycleSnapSize() {
      const options = [4, 8, 16]
      const idx = options.indexOf(this.canvas.snapSize)
      this.canvas.snapSize = options[(idx + 1) % options.length]
    },
    toggleGuides() { this.canvas.guidesEnabled = !this.canvas.guidesEnabled },
    setGuideThreshold(px) { const v = Number(px) || 5; this.canvas.guideThreshold = Math.max(1, Math.min(20, v)) },

    centerSelectedX() {
      const id = this.canvas.selectedElementId; if (!id) return
      const el = this.canvas.elements.find(e => e.id === id); if (!el) return
      const nextX = Math.max(0, Math.floor((this.canvas.width - (el.width || 0)) / 2))
      this.updateElement(id, { x: nextX })
    },
    centerSelectedY() {
      const id = this.canvas.selectedElementId; if (!id) return
      const el = this.canvas.elements.find(e => e.id === id); if (!el) return
      const nextY = Math.max(0, Math.floor((this.canvas.height - (el.height || 0)) / 2))
      this.updateElement(id, { y: nextY })
    },

    duplicateElement(id) {
      const src = this.canvas.elements.find(e => e.id === id); if (!src) return
      const clone = JSON.parse(JSON.stringify(src))
      clone.id = crypto.randomUUID?.() || `el_${Date.now()}`
      clone.x = Math.min(this.canvas.width - (clone.width || 0), (clone.x || 0) + 20)
      clone.y = Math.min(this.canvas.height - (clone.height || 0), (clone.y || 0) + 20)
      clone.z = (Math.max(0, ...this.canvas.elements.map(e => e.z || 0)) + 1)
      if (clone.type === 'group') {
        // Deep duplicate group with children
        const childMap = new Map()
        const baseChildren = (src.children || []).map(cid => this.canvas.elements.find(e => e.id === cid)).filter(Boolean)
        baseChildren.forEach(child => {
          const childClone = JSON.parse(JSON.stringify(child))
          childClone.id = crypto.randomUUID?.() || `el_${Date.now()}_${Math.floor(Math.random()*9999)}`
          childClone.x = Math.min(this.canvas.width - (childClone.width || 0), (childClone.x || 0) + 20)
          childClone.y = Math.min(this.canvas.height - (childClone.height || 0), (childClone.y || 0) + 20)
          childClone.z = (Math.max(0, ...this.canvas.elements.map(e => e.z || 0)) + 1)
          childClone.parentId = clone.id
          childMap.set(child.id, childClone.id)
          this.canvas.elements.push(childClone)
        })
        clone.children = baseChildren.map(ch => childMap.get(ch.id))
        this.canvas.elements.push(clone)
        this.canvas.selectedElementId = clone.id
      } else {
        this.canvas.elements.push(clone)
        this.canvas.selectedElementId = clone.id
      }
    },

    // Serialize/deserialize
    serializeCanvas() {
      return JSON.stringify({ width: this.canvas.width, height: this.canvas.height, elements: this.canvas.elements }, null, 2)
    },
    loadCanvas(json) {
      try {
        const data = typeof json === 'string' ? JSON.parse(json) : json
        this.canvas.width = data.width || this.canvas.width
        this.canvas.height = data.height || this.canvas.height
        this.canvas.elements = (data.elements || []).map((e, idx) => ({
          ...e,
          id: e.id || crypto.randomUUID?.() || `el_${idx}`,
          parentId: e.parentId || null,
          children: e.type === 'group' ? (Array.isArray(e.children) ? [...e.children] : []) : undefined,
        }))
      } catch (e) {
        console.error('Failed to load canvas JSON', e)
      }
    },

    // Grouping
    createGroup(childIds, props = {}) {
      const ids = (childIds || []).filter(Boolean)
      if (!ids.length) return null
      const children = this.canvas.elements.filter(e => ids.includes(e.id))
      if (!children.length) return null
      // compute bounding box
      const left = Math.min(...children.map(c => c.x || 0))
      const top = Math.min(...children.map(c => c.y || 0))
      const right = Math.max(...children.map(c => (c.x || 0) + (c.width || 0)))
      const bottom = Math.max(...children.map(c => (c.y || 0) + (c.height || 0)))
      const group = {
        id: crypto.randomUUID?.() || `grp_${Date.now()}`,
        type: 'group',
        x: left,
        y: top,
        width: Math.max(10, right - left),
        height: Math.max(10, bottom - top),
        rotation: 0,
        z: Math.max(0, ...this.canvas.elements.map(e => e.z || 0)) + 1,
        props: { label: props.label || 'Group' },
        children: ids.slice(),
        parentId: null,
      }
      this.canvas.elements.push(group)
      // attach parentId to children
      children.forEach(ch => { ch.parentId = group.id })
      this.canvas.selectedElementId = group.id
      return group.id
    },

    ungroup(groupId) {
      const group = this.canvas.elements.find(e => e.id === groupId && e.type === 'group')
      if (!group) return
      // detach children
      ;(group.children || []).forEach(cid => {
        const child = this.canvas.elements.find(e => e.id === cid)
        if (child) child.parentId = null
      })
      // remove group
      this.canvas.elements = this.canvas.elements.filter(e => e.id !== groupId)
      if (this.canvas.selectedElementId === groupId) this.canvas.selectedElementId = null
    },
  },
});
