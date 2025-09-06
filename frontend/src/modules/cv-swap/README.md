# CV-Swap Module

A module for the ResumeRise platform that facilitates the exchange of CVs between candidates and employers through a Tinder-like swiping interface.

## Features

- Company CV Generator
- Candidate CV Integration
- Swipe-based Discovery Feed
- Culture Match Percentage
- Mutual Portfolio View
- Messaging System (Post-Match)

## Directory Structure

```
cv-swap/
├── components/           # Reusable UI components
├── locales/              # Internationalization files
├── store/                # Pinia store for state management
├── views/                # Page components
│   ├── DiscoverView.vue  # Discovery feed for swiping
│   ├── CompanyProfile.vue # Company CV view
│   ├── CandidateProfile.vue # Candidate CV view
│   ├── MatchesView.vue   # List of matches
│   └── CompanyCVEditor.vue # Company CV editor
├── routes.js            # Module routes
└── index.js             # Module entry point
```

## Setup

1. Register the module in the main application
2. Configure routes in the main router
3. Set up required API endpoints in the backend

## Dependencies

- Vue 3
- Pinia
- Framer Motion (for swipe animations)
- Axios (for API calls)
