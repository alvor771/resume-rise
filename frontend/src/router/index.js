import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import adminRoutes from "./admin";
import resumeGeneratorRoutes from "@/modules/resume-generator/routes";
import cvSwapRoutes from "@/modules/cv-swap/routes";

// Public routes with MainLayout
const publicRoutes = [
  {
    path: "/",
    name: "Home",
    component: () => import("@/views/HomeView.vue"),
    meta: { title: "ResumeRise - Create Professional Resumes" },
  },
  {
    path: "/cv-generator",
    name: "CVGenerator",
    component: () => import("@/views/CVGeneratorView.vue"),
    meta: { title: "CV Generator - ResumeRise" },
  },
  {
    path: "/templates",
    name: "Templates",
    component: () => import("@/views/TemplatesView.vue"),
    meta: { title: "Templates - ResumeRise" },
  },
  {
    path: "/pricing",
    name: "Pricing",
    component: () => import("@/views/PricingView.vue"),
    meta: { title: "Pricing - ResumeRise" },
  },
  {
    path: "/help-center",
    name: "HelpCenter",
    component: () => import("@/views/HelpCenterView.vue"),
    meta: { title: "Help Center - ResumeRise" },
  },
  {
    path: "/documentation",
    name: "Documentation",
    component: () => import("@/views/static/DocumentationView.vue"),
    meta: { title: "Documentation - ResumeRise" },
  },
  {
    path: "/guides",
    name: "Guides",
    component: () => import("@/views/static/GuidesView.vue"),
    meta: { title: "Guides - ResumeRise" },
  },
  {
    path: "/api-status",
    name: "APIStatus",
    component: () => import("@/views/static/APIStatusView.vue"),
    meta: { title: "API Status - ResumeRise" },
  },
  {
    path: "/terms",
    name: "TermsOfService",
    component: () => import("@/views/static/TermsOfService.vue"),
    meta: { title: "Terms of Service - ResumeRise" },
  },
  {
    path: "/privacy",
    name: "PrivacyPolicy",
    component: () => import("@/views/static/PrivacyPolicy.vue"),
    meta: { title: "Privacy Policy - ResumeRise" },
  },
  {
    path: "/cookies",
    name: "CookiePolicy",
    component: () => import("@/views/static/CookiePolicy.vue"),
    meta: { title: "Cookie Policy - ResumeRise" },
  }
  ,
  {
    path: "/icons",
    name: "IconsShowcase",
    component: () => import("@/views/IconsShowcase.vue"),
    meta: { title: "Icons - ResumeRise" },
  },
  {
    path: "/api-test",
    name: "ApiTest",
    component: () => import("@/views/ApiTest.vue"),
    meta: { title: "API Test - ResumeRise" },
  }
];

// Auth routes (login, register, etc.)
const authRoutes = [
  {
    path: "/login",
    name: "Login",
    component: () => import("@/views/auth/LoginView.vue"),
    meta: {
      title: "Sign In - ResumeRise",
      guest: true,
    },
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("@/views/auth/RegisterView.vue"),
    meta: {
      title: "Sign Up - ResumeRise",
      guest: true,
    },
  },
  {
    path: "/forgot-password",
    name: "ForgotPassword",
    component: () => import("@/views/auth/ForgotPassword.vue"),
    meta: {
      title: "Reset Password - ResumeRise",
      guest: true,
    },
  }
];

// Profile routes (protected)
const profileRoutes = [
  {
    path: "/profile",
    name: "Profile",
    component: () => import("@/views/user/ProfileView.vue"),
    redirect: { name: "ProfileInfo" },
    meta: {
      title: "My Profile - ResumeRise",
      requiresAuth: true,
      breadcrumb: "My Profile",
    },
    children: [
      {
        path: "",
        name: "ProfileInfo",
        component: () => import("@/components/user/UserInfo.vue"),
        meta: {
          title: "My Profile - ResumeRise",
          breadcrumb: "Profile Information",
        },
      },
      {
        path: "cv",
        name: "ProfileCV",
        component: () => import("@/components/user/CVInfo.vue"),
        meta: {
          title: "CV Information - ResumeRise",
          breadcrumb: "CV Information",
        },
      },
      {
        path: "resumes",
        name: "ProfileResumes",
        component: () => import("@/views/user/ProfileResumes.vue"),
        meta: {
          title: "My Resumes - ResumeRise",
          breadcrumb: "My Resumes",
        },
      },
      {
        path: "templates",
        name: "ProfileTemplates",
        component: () => import("@/views/user/ProfileTemplates.vue"),
        meta: {
          title: "My Templates - ResumeRise",
          breadcrumb: "My Templates",
        },
      },
      {
        path: "subscriptions",
        name: "ProfileSubscriptions",
        component: () => import("@/components/user/Subscriptions.vue"),
        meta: {
          title: "Subscriptions - ResumeRise",
          breadcrumb: "Subscriptions",
        },
      },
      {
        path: "applications",
        name: "ProfileApplications",
        component: () => import("@/components/user/JobApplications.vue"),
        meta: {
          title: "Job Applications - ResumeRise",
          breadcrumb: "Job Applications",
        },
      },
    ],
  },
];

// Resume Builder route (protected)
const resumeBuilderRoute = {
  path: "/resume-builder",
  name: "ResumeBuilder",
  component: () => import("@/views/ResumeBuilderView.vue"),
  meta: {
    title: "Resume Builder - ResumeRise",
    requiresAuth: true,
  },
};

// 404 route - Keep this as the last route
const notFoundRoute = {
  path: "/:pathMatch(.*)*",
  name: "NotFound",
  component: () => import("@/views/NotFoundView.vue"),
  meta: { title: "Page Not Found - ResumeRise" },
};

// Combine all routes with their respective layouts
const routes = [
  // Main layout routes (public + auth + profile)
  {
    path: "/",
    // component: () => import("@/layouts/MainLayout.vue"),
    children: [
      ...publicRoutes,
      ...authRoutes,
      resumeBuilderRoute,
      ...profileRoutes,
    ],
  },
  
  // Admin layout routes
  {
    path: "/admin",
    // component: () => import("@/layouts/AdminLayout.vue"),
    children: adminRoutes,
  },
  
  // CV Swap layout routes
  {
    path: "/cv-swap",
    // component: () => import("@/modules/cv-swap/layouts/CvSwapLayout.vue"),
    children: cvSwapRoutes,
  },
  
  // Resume Generator routes (uses its own layout)
  ...resumeGeneratorRoutes,
  
  // 404 route (no layout) - Must be the last route
  notFoundRoute,
];

// Use a direct string for the base URL
const router = createRouter({
  history: createWebHistory("/"),
  routes,
  scrollBehavior() {
    // Always scroll to top when navigating to a new route
    return { top: 0, behavior: "smooth" };
  },
});

// Global navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Check if the route requires authentication
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!authStore.isAuthenticated) {
      // Redirect to login if not authenticated
      next({ name: "Login", query: { redirect: to.fullPath } });
      return;
    }
  }

  // Redirect to home if trying to access guest-only routes while authenticated
  if (
    to.matched.some((record) => record.meta.guest) &&
    authStore.isAuthenticated
  ) {
    next({ name: "Home" });
    return;
  }

  // Update page title
  document.title = to.meta.title || "ResumeRise";

  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const isGuestRoute = to.matched.some((record) => record.meta.guest);
  const isAuthenticated = localStorage.getItem("token");

  // Redirect to home if trying to access guest routes while authenticated
  if (isGuestRoute && isAuthenticated) {
    return next({ name: "Home" });
  }

  // Redirect to login if route requires auth and user is not authenticated
  if (requiresAuth && !isAuthenticated) {
    return next({
      name: "Login",
      query: { redirect: to.fullPath },
    });
  }

  // Redirect to profile if trying to access login/register while authenticated
  if ((to.name === "Login" || to.name === "Register") && isAuthenticated) {
    return next({ name: "Profile" });
  }

  // Continue to the requested route
  next();
});

export default router;
