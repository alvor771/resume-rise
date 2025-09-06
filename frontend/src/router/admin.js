// Import views
const AdminDashboardView = () => import('@/views/dashboard/AdminDashboardView.vue');
const UsersView = () => import('@/views/admin/UsersView.vue');
const UserEdit = () => import('@/views/admin/users/UserEdit.vue');
const UserView = () => import('@/views/admin/users/UserView.vue');
const ResumesView = () => import('@/views/admin/ResumesView.vue');
const TemplatesView = () => import('@/views/admin/TemplatesView.vue');
const TemplateForm = () => import('@/views/admin/templates/TemplateForm.vue');
const TemplateDetailView = () => import('@/views/admin/TemplateDetailView.vue');
const SettingsView = () => import('@/views/admin/SettingsView.vue');
const MenuView = () => import('@/views/admin/MenuEditor.vue');
const ActivityLogView = () => import('@/views/admin/ActivityLogView.vue');
const ProfileView = () => import('@/views/admin/ProfileView.vue');
const ApiEndpointsView = () => import('@/views/admin/ApiEndpointsView.vue');
const UiComponentsView = () => import('@/views/admin/UiComponentsView.vue');
const RBACManagement = () => import('@/views/admin/RBACManagement.vue');
const MediaView = () => import('@/views/admin/MediaView.vue');

// Company and Vacancy Management
const CompanyList = () => import('@/views/companies/CompanyList.vue');
const CompanyDetail = () => import('@/views/companies/CompanyDetail.vue');
const CompanyEdit = () => import('@/views/companies/CompanyEdit.vue');
const CompanyForm = () => import('@/views/companies/CompanyForm.vue');
const VacancyList = () => import('@/components/vacancies/VacancyList.vue');
const VacancyForm = () => import('@/views/vacancies/VacancyForm.vue');
const VacancyDetail = () => import('@/views/vacancies/VacancyDetail.vue');

const routes = [
  {
    path: '/admin',
    meta: { 
      requiresAuth: false, 
      admin: true,
      layout: 'admin' 
    },
    children: [
      {
        path: '',
        redirect: '/admin/dashboard',
      },
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: AdminDashboardView,
        meta: { 
          title: 'admin.dashboard.title',
          icon: 'HomeIcon',
          navOrder: 1,
          requiresAuth: true,
          permissions: ['view_admin_dashboard']
        }
      },
      // Media Management
      {
        path: 'media',
        name: 'Media',
        component: MediaView,
        meta: {
          title: 'media.media_library',
          icon: 'ImageIcon',
          navOrder: 5,
          requiresAuth: true,
          permissions: ['manage_media']
        }
      },
      // RBAC Management
      {
        path: 'rbac',
        name: 'RBACManagement',
        component: RBACManagement,
        meta: { 
          title: 'RBAC Management',
          icon: 'ShieldCheckIcon',
          navOrder: 2,
          requiresAuth: true,
          permissions: ['manage_roles', 'manage_users'],
          breadcrumb: 'RBAC Management'
        }
      },
      // Vacancy Management
      {
        path: 'vacancies',
        name: 'vacancies.list',
        component: VacancyList,
        meta: {
          title: 'Vacancies',
          icon: 'BriefcaseIcon',
          navOrder: 3,
          permissions: ['view_vacancies']
        }
      },
      {
        path: 'vacancies/create',
        name: 'vacancies.create',
        component: VacancyForm,
        meta: {
          title: 'Create Vacancy',
          permissions: ['create_vacancies']
        }
      },
      {
        path: ':companySlug/vacancies/:vacancySlug-:id',
        name: 'vacancies.view',
        component: VacancyDetail,
        props: (route) => ({
          id: route.params.id,
          companySlug: route.params.companySlug,
          vacancySlug: route.params.vacancySlug
        }),
        meta: { 
          title: 'View Vacancy',
          icon: 'BriefcaseIcon',
          navOrder: 3,
          requiresAuth: true,
          permissions: ['view_vacancies'],
          layout: 'admin'
        },
        beforeEnter: (to, from, next) => {
          // Redirect to new URL format if accessed via legacy URL
          if (to.params.id && to.params.id.includes('-')) {
            const parts = to.params.id.split('-')
            const id = parts.pop()
            const slug = parts.join('-')
            next({
              name: 'vacancies.view',
              params: {
                companySlug: 'company', // Default slug
                vacancySlug: slug,
                id: id
              }
            })
          } else {
            next()
          }
        }
      },
      // Old URL format (temporary)
      {
        path: 'vacancies/:id',
        name: 'vacancies.view.legacy',
        component: VacancyDetail,
        props: (route) => ({
          id: route.params.id,
          isLegacyRoute: true
        }),
        meta: { 
          title: 'View Vacancy',
          icon: 'BriefcaseIcon',
          navOrder: 3,
          requiresAuth: true,
          permissions: ['view_vacancies'],
          layout: 'admin'
        },
        beforeEnter: (to, from, next) => {
          // Redirect to new URL format if possible
          if (to.params.id && to.params.id.includes('-')) {
            const parts = to.params.id.split('-')
            const id = parts.pop()
            const slug = parts.join('-')
            next({
              name: 'vacancies.view',
              params: {
                companySlug: 'company', // Default slug
                vacancySlug: slug,
                id: id
              }
            })
          } else {
            next()
          }
        }
      },
      // Company Management
      {
        path: 'companies',
        name: 'companies.list',
        component: CompanyList,
        meta: {
          title: 'Companies',
          icon: 'OfficeBuildingIcon',
          navOrder: 2,
          permissions: ['view_companies']
        }
      },
      {
        path: 'companies/create',
        name: 'companies.create',
        component: CompanyEdit,
        meta: {
          title: 'Create Company',
          permissions: ['create_companies']
        }
      },
      {
        path: 'companies/:id',
        name: 'companies.view',
        component: CompanyDetail,
        props: true,
        meta: {
          title: 'View Company',
          permissions: ['view_companies']
        }
      },
      {
        path: 'companies/:id/edit',
        name: 'companies.edit',
        component: CompanyEdit,
        props: true,
        meta: {
          title: 'Edit Company',
          permissions: ['edit_companies']
        }
      },
      
      // User Management
      {
        path: 'users',
        name: 'AdminUsers',
        component: UsersView,
        meta: { 
          title: 'admin.users.title',
          icon: 'UsersIcon',
          navOrder: 2 
        }
      },
      {
        path: 'users/:id',
        name: 'AdminViewUser',
        component: UserView,
        meta: { 
          title: 'admin.users.viewUser',
          // requiresAuth: true,
          admin: true,
          layout: 'admin'
        },
        props: true
      },
      {
        path: 'users/new',
        name: 'AdminNewUser',
        component: UserEdit,
        meta: { 
          title: 'admin.users.newUser',
          // requiresAuth: true,
          admin: true,
          layout: 'admin'
        }
      },
      {
        path: 'users/:id/edit',
        name: 'AdminEditUser',
        component: UserEdit,
        meta: { 
          title: 'admin.users.editUser',
          // requiresAuth: true,
          admin: true,
          layout: 'admin' 
        },
        props: true
      },
      {
        path: 'resumes',
        name: 'AdminResumes',
        component: ResumesView,
        meta: { 
          title: 'admin.resumes.title',
          icon: 'DocumentTextIcon',
          navOrder: 3 
        }
      },
      {
        path: 'api-endpoints',
        name: 'AdminApiEndpoints',
        component: ApiEndpointsView,
        meta: { 
          title: 'admin.api_endpoints.title',
          icon: 'ServerIcon',
          navOrder: 4
        }
      },
      {
        path: 'ui-components',
        name: 'AdminUiComponents',
        component: UiComponentsView,
        meta: {
          title: 'admin.ui_components.title',
          icon: 'Squares2X2Icon',
          navOrder: 8
        }
      },
      {
        path: 'templates/:id',
        name: 'AdminTemplateDetail',
        component: TemplateDetailView,
        meta: { 
          title: 'admin.templates.templateDetails',
          icon: 'DocumentTextIcon'
        },
        props: true
      },
      {
        path: 'templates',
        name: 'AdminTemplates',
        component: TemplatesView,
        meta: { 
          title: 'admin.templates.title',
          icon: 'RectangleGroupIcon',
          navOrder: 5 
        },
        children: [
          {
            path: 'new',
            name: 'AdminNewTemplate',
            component: TemplateForm,
            meta: { title: 'admin.templates.newTemplate' }
          },
          {
            path: ':id/edit',
            name: 'AdminEditTemplate',
            component: TemplateForm,
            meta: { title: 'admin.templates.editTemplate' },
            props: true
          }
        ]
      },
      {
        path: 'companies',
        name: 'AdminCompanies',
        component: CompanyList,
        meta: { 
          title: 'companies.title',
          icon: 'BuildingOfficeIcon',
          navOrder: 30,
          permissions: ['view_companies']
        }
      },
      {
        path: 'companies/create',
        name: 'CompanyCreate',
        component: CompanyForm,
        meta: { 
          title: 'companies.create',
          permissions: ['create_companies'],
          hideInMenu: true
        }
      },
      {
        path: 'companies/:id/edit',
        name: 'CompanyEdit',
        component: CompanyForm,
        props: true,
        meta: { 
          title: 'companies.edit',
          permissions: ['update_companies'],
          hideInMenu: true
        }
      },
      {
        path: 'vacancies',
        name: 'AdminVacancies',
        component: VacancyList,
        meta: { 
          title: 'vacancies.title',
          icon: 'BriefcaseIcon',
          navOrder: 31,
          permissions: ['view_vacancies']
        }
      },
      {
        path: ':companySlug/vacancies/:vacancySlug-:id/edit',
        name: 'vacancies.edit',
        component: VacancyForm,
        props: (route) => ({
          id: route.params.id,
          companySlug: route.params.companySlug,
          vacancySlug: route.params.vacancySlug
        }),
        meta: { 
          title: 'View Vacancy',
          permissions: ['view_vacancies'],
          hideInMenu: true
        }
      },
      {
        path: 'vacancies/create',
        name: 'vacancies.create',
        component: VacancyForm,
        meta: { 
          title: 'vacancies.create',
          permissions: ['create_vacancies'],
          hideInMenu: true
        }
      },
      {
        path: 'vacancies/:id/edit',
        name: 'VacancyEdit',
        component: VacancyForm,
        props: true,
        meta: { 
          title: 'vacancies.edit',
          permissions: ['update_vacancies'],
          hideInMenu: true
        }
      },
      {
        path: 'settings',
        name: 'AdminSettings',
        component: SettingsView,
        meta: { 
          title: 'admin.settings.title',
          icon: 'SettingsIcon',
          navOrder: 100 
        }
      },
      {
        path: 'menu',
        name: 'AdminMenu',
        component: MenuView,
        meta: { 
          title: 'admin.menu.title',
          icon: 'MenuIcon',
          navOrder: 6 
        }
      },
      {
        path: 'activity-log',
        name: 'AdminActivityLog',
        component: ActivityLogView,
        meta: { 
          title: 'admin.activityLog.title',
          icon: 'ClockIcon',
          navOrder: 7 
        }
      },
      {
        path: 'profile',
        name: 'AdminProfile',
        component: ProfileView,
        meta: {
          title: 'admin.profile.title',
          icon: 'UserIcon',
          navOrder: 9
        }
      }
    ]
  }
];

// Export the routes array
export default routes;
