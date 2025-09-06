// Import components
import BaseWidget from '@/components/dashboard/widgets/BaseWidget.vue';
import StatWidget from '@/components/dashboard/widgets/StatWidget.vue';
import WidgetMenu from '@/components/dashboard/widgets/WidgetMenu.vue';

// Import views
import AdminDashboardView from '@/views/dashboard/AdminDashboardView.vue';
import HRDashboardView from '@/views/dashboard/HRDashboardView.vue';
import CandidateDashboardView from '@/views/dashboard/CandidateDashboardView.vue';

const DashboardModule = {
  install(app) {
    // Register global components
    app.component('BaseWidget', BaseWidget);
    app.component('StatWidget', StatWidget);
    app.component('WidgetMenu', WidgetMenu);

    // Register views as global components
    app.component('AdminDashboardView', AdminDashboardView);
    app.component('HRDashboardView', HRDashboardView);
    app.component('CandidateDashboardView', CandidateDashboardView);

    // Add dashboard routes
    const routes = [
      {
        path: '/admin/dashboard',
        name: 'AdminDashboard',
        component: AdminDashboardView,
        meta: {
          requiresAuth: true,
          layout: 'admin',
          title: 'Admin Dashboard',
          icon: 'HomeIcon',
          permissions: ['view_admin_dashboard']
        }
      },
      {
        path: '/hr/dashboard',
        name: 'HRDashboard',
        component: HRDashboardView,
        meta: {
          requiresAuth: true,
          layout: 'default',
          title: 'HR Dashboard',
          icon: 'UsersIcon',
          permissions: ['view_hr_dashboard']
        }
      },
      {
        path: '/candidate/dashboard',
        name: 'CandidateDashboard',
        component: CandidateDashboardView,
        meta: {
          requiresAuth: true,
          layout: 'default',
          title: 'My Dashboard',
          icon: 'UserIcon',
          permissions: ['view_candidate_dashboard']
        }
      }
    ];

    // Add routes to the router
    if (app.config.globalProperties.$router) {
      routes.forEach(route => {
        app.config.globalProperties.$router.addRoute(route);
      });
    }
  }
};

export default DashboardModule;
