# Resume Generator Module

This module provides a complete solution for creating, editing, and exporting resumes in various formats.

## Features

- Create and edit resumes with a user-friendly interface
- Multiple resume templates
- Real-time preview
- Export to PDF and Word formats
- Multi-language support (English and Russian included)
- Responsive design that works on all devices

## Module Structure

```
resume-generator/
├── components/           # Reusable UI components
│   └── Section.vue       # Section component for resume sections
├── views/                # Main views
│   ├── ResumeEditor.vue  # Main editor view
│   ├── ResumePreview.vue # Resume preview view
│   └── TemplateSelection.vue # Template selection view
├── store/                # Pinia store for state management
│   └── index.js
├── locales/              # Localization files
│   └── index.js
├── routes.js             # Module routes
└── index.js              # Module entry point
```

## Installation

1. Import and register the module in your application's main file:

```javascript
import resumeGenerator from '@/modules/resume-generator'

// Register the module
app.use(resumeGenerator)
```

2. Add the route to your router configuration:

```javascript
import resumeRoutes from '@/modules/resume-generator/routes'

const router = createRouter({
  // ...
  routes: [
    // ...
    ...resumeRoutes
  ]
})
```

## Usage

1. Navigate to `/resume` to start creating a new resume
2. Select a template
3. Fill in your personal information, experience, education, and skills
4. Preview your resume
5. Export as PDF or Word document

## API Integration

The module expects the following API endpoints:

- `GET /api/resume/templates` - Get available templates
- `POST /api/resume/save` - Save resume data
- `GET /api/resume/export/{id}?format=pdf|docx` - Export resume
- `GET /api/resume/{id}` - Get resume by ID

## Styling

The module uses Tailwind CSS for styling. Customize the look by overriding the default styles in your application's CSS.

## Localization

Currently supported languages:
- English (en)
- Russian (ru)

To add a new language, add a new entry in the `locales/index.js` file.

## Dependencies

- Vue 3
- Pinia (for state management)
- Vue Router
- Axios (for API calls)
- Tailwind CSS (for styling)

## License

MIT
