// import CvSwapLayout from './layouts/CvSwapLayout.vue';

const routes = [
  // Landing Page (Public)
  {
    path: '/',
    name: 'LandingPage',
    component: () => import('@/modules/cv-swap/views/LandingPage.vue'),
    meta: { 
      title: 'CV Swap - Find Your Perfect Match',
      requiresAuth: false
    }
  },
  
  // Main App (Protected)
  {
    path: '/cv-swap',
    meta: { 
      title: 'CV Swap',
      requiresAuth: false
    },
    children: [
      // Default redirect to discover
      {
        path: 'landing',
        name: 'LandingPage',
        component: () => import('@/modules/cv-swap/views/LandingPage.vue'),
        meta: { 
          title: 'CV Swap - Find Your Perfect Match',
          requiresAuth: false
        }
      },
      
      // Discovery Feed
      {
        path: 'discover',
        name: 'DiscoverPage',
        component: () => import('@/modules/cv-swap/views/DiscoverPage.vue'),
        meta: { 
          title: 'Discover - CV Swap',
          requiresAuth: true
        }
      },
      
      // Profile
      {
        path: 'profile',
        name: 'UserProfile',
        component: () => import('@/modules/cv-swap/views/ProfilePage.vue'),
        meta: { 
          title: 'My Profile - CV Swap',
          requiresAuth: true
        }
      },
      
      // Template Marketplace
      {
        path: 'templates',
        name: 'TemplateMarketplace',
        component: () => import('@/modules/cv-swap/views/TemplatesPage.vue'),
        meta: { 
          title: 'Templates - CV Swap',
          requiresAuth: true
        }
      },
      
      // Company Dashboard
      {
        path: 'company-dashboard',
        name: 'CompanyDashboard',
        component: () => import('@/modules/cv-swap/views/CompanyDashboard.vue'),
        meta: { 
          title: 'Company Dashboard - CV Swap',
          requiresAuth: true,
          requiresCompany: true
        },
        children: [
          {
            path: 'new-job',
            name: 'NewJob',
            component: () => import('@/modules/cv-swap/views/NewJob.vue'),
            meta: { 
              title: 'New Job - CV Swap',
              requiresAuth: true,
              requiresCompany: true
            }
          },
          {
            path: 'candidates',
            name: 'CandidatesList',
            component: () => import('@/modules/cv-swap/views/Candidates.vue'),
            meta: { 
              title: 'Candidates - CV Swap',
              requiresAuth: true,
              requiresCompany: true
            }
          },
          {
            path: 'schedule',
            name: 'Schedule',
            component: () => import('@/modules/cv-swap/views/Schedule.vue'),
            meta: { 
              title: 'Schedule - CV Swap',
              requiresAuth: true,
              requiresCompany: true
            }
          },
          {
            path: 'analytics',
            name: 'Analytics',
            component: () => import('@/modules/cv-swap/views/Analytics.vue'),
            meta: { 
              title: 'Analytics - CV Swap',
              requiresAuth: true,
              requiresCompany: true
            }
          }
        ]
      },
      
      // Messages
      {
        path: 'messages',
        name: 'MessagesPage',
        component: () => import('@/modules/cv-swap/views/MessagesPage.vue'),
        meta: { 
          title: 'Messages - CV Swap',
          requiresAuth: true
        }
      },
      
      // Candidate Profile View (Public)
      {
        path: 'candidate/:id',
        name: 'PublicCandidateProfile',
        component: () => import('@/modules/cv-swap/views/PublicProfileView.vue'),
        meta: { 
          title: 'Candidate Profile',
          requiresAuth: false
        },
        props: true
      },
      
      // Company Profile View (Public)
      {
        path: 'company/:id',
        name: 'PublicCompanyProfile',
        component: () => import('@/modules/cv-swap/views/PublicCompanyView.vue'),
        meta: { 
          title: 'Company Profile',
          requiresAuth: false
        },
        props: true
      }
    ]
  },
  
  // 404 - Keep this last
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/views/NotFoundView.vue'),
    meta: { title: 'Page Not Found' }
  }
];

export default routes;
