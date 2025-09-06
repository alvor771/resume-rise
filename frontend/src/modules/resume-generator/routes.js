import ResumeGenerator from './views/ResumeGenerator.vue'
import TemplateSelection from './views/TemplateSelection.vue'
import ResumeEditor from './views/ResumeEditor.vue'
import ResumePreview from './views/ResumePreview.vue'

export default [
  {
    path: '/resume',
    name: 'resume-generator',
    component: ResumeGenerator,
    children: [
      {
        path: '',
        name: 'template-selection',
        component: TemplateSelection
      },
      {
        path: 'editor',
        name: 'resume-editor',
        component: ResumeEditor,
        meta: { requiresTemplate: true }
      },
      {
        path: 'preview',
        name: 'resume-preview',
        component: ResumePreview,
        meta: { requiresResume: true }
      }
    ]
  }
]
