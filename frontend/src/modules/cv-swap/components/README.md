# CV-Swap Components

This directory contains reusable Vue components for the CV-Swap module.

## Components

### CompanyCard
Displays a company profile card with logo, name, industry, location, tech stack, and action buttons.

**Props:**
- `company` (Object, required): Company data object
- `showActions` (Boolean, default: true): Whether to show like/pass buttons
- `showMatchBadge` (Boolean, default: true): Whether to show the match percentage badge

**Events:**
- `like`: Emitted when the like button is clicked
- `pass`: Emitted when the pass button is clicked

### CandidateCard
Displays a candidate profile card with photo, name, title, location, experience, skills, and action buttons.

**Props:**
- `candidate` (Object, required): Candidate data object
- `showActions` (Boolean, default: true): Whether to show like/pass buttons
- `showMatchBadge` (Boolean, default: true): Whether to show the match percentage badge

**Events:**
- `like`: Emitted when the like button is clicked
- `pass`: Emitted when the pass button is clicked

### SwipePanel
A swipeable panel component that handles swipe gestures for the discovery feed.

**Props:**
- `items` (Array, default: []): Array of items to display in the swipe panel
- `isLoading` (Boolean, default: false): Whether the panel is in a loading state

**Events:**
- `swipe-left`: Emitted when an item is swiped left
- `swipe-right`: Emitted when an item is swiped right
- `refresh`: Emitted when the refresh button is clicked

### CultureMatch
Displays a compatibility percentage between a candidate and company with a visual progress bar.

**Props:**
- `matchPercentage` (Number, required): The match percentage (0-100)
- `matchFactors` (Array, default: []): Array of factors contributing to the match score
- `showDetails` (Boolean, default: true): Whether to show detailed match factors
- `showSummary` (Boolean, default: false): Whether to show a summary of the match

## Usage

```javascript
// Import individual components
import { CompanyCard, CandidateCard, SwipePanel, CultureMatch } from './components';

// Or use the plugin to register all components globally
import CvSwapComponents from './components';

app.use(CvSwapComponents);
```

## Styling

Components are styled using Tailwind CSS. Custom styles can be added using the `style` section in each component.

## Dependencies

- Vue 3
- Pinia (for state management)
- Tailwind CSS (for styling)
