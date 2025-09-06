<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Welcome back, {{ companyName }}</p>
      </div>
      <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        + New Job
      </button>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.label" 
           class="gradient-card p-6 rounded-xl border border-gray-700/50 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="p-3 rounded-lg mr-4" :class="stat.bgColor">
            <component :is="stat.icon" class="w-6 h-6 text-white" />
          </div>
          <div>
            <p class="text-sm text-gray-400">{{ stat.label }}</p>
            <p class="text-2xl font-bold text-white">{{ stat.value }}</p>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm" :class="stat.trendColor">
          <component :is="stat.trendIcon" class="w-4 h-4 mr-1" />
          {{ stat.trend }}
        </div>
      </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column -->
      <div class="lg:col-span-2 space-y-6">

        <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
          <router-view></router-view>
        </div>

        <!-- Recent Applicants -->
        <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-white">Recent Applicants</h2>
            <button class="text-purple-400 text-sm hover:text-purple-300">View All</button>
          </div>
          
          <div class="space-y-4">
            <div v-for="applicant in recentApplicants" :key="applicant.id" 
                 class="flex items-center p-3 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors">
              <img :src="applicant.avatar" :alt="applicant.name" class="w-10 h-10 rounded-full mr-3">
              <div class="flex-1">
                <h3 class="font-medium text-white">{{ applicant.name }}</h3>
                <p class="text-sm text-gray-400">{{ applicant.position }}</p>
              </div>
              <span class="text-xs px-2 py-1 rounded-full" :class="{
                'bg-green-900/30 text-green-400': applicant.status === 'Hired',
                'bg-yellow-900/30 text-yellow-400': applicant.status === 'Interview',
                'bg-blue-900/30 text-blue-400': applicant.status === 'Applied'
              }">
                {{ applicant.status }}
              </span>
            </div>
          </div>
        </div>
        
        <!-- Active Jobs -->
        <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-white">Active Job Postings</h2>
            <button class="text-purple-400 text-sm hover:text-purple-300">View All</button>
          </div>
          
          <div class="space-y-4">
            <div v-for="job in activeJobs" :key="job.id" 
                 class="p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-medium text-white">{{ job.title }}</h3>
                  <div class="flex items-center mt-1 text-sm text-gray-400">
                    <span class="flex items-center mr-4">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {{ job.location }}
                    </span>
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ job.type }}
                    </span>
                  </div>
                </div>
                <span class="px-2 py-1 text-xs rounded-full" 
                  :class="{
                    'bg-green-900/30 text-green-400': job.status === 'Active',
                    'bg-yellow-900/30 text-yellow-400': job.status === 'Draft',
                    'bg-red-900/30 text-red-400': job.status === 'Closed'
                  }"
                >
                  {{ job.status }}
                </span>
              </div>
              <div class="mt-3 flex justify-between items-center text-sm">
                <div class="flex -space-x-2">
                  <img v-for="(avatar, idx) in job.applicants" :key="idx" :src="avatar" 
                       class="w-6 h-6 rounded-full border-2 border-gray-800" :style="{ zIndex: 5 - idx }" />
                  <span v-if="job.applicants.length > 5" class="w-6 h-6 rounded-full bg-gray-700 text-xs flex items-center justify-center">
                    +{{ job.applicants.length - 5 }}
                  </span>
                </div>
                <span class="text-gray-400">{{ job.applicants.length }} applicants</span>
              </div>
              <div class="mt-2 text-sm text-gray-500">
                {{ job.applications }} applications • {{ job.newApplications }} new
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Column -->
      <div class="space-y-6">
        <!-- Upcoming Interviews -->
        <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-white">Upcoming Interviews</h2>
            <button class="text-purple-400 text-sm hover:text-purple-300">View All</button>
          </div>
          <div class="space-y-4">
            <div 
              v-for="interview in upcomingInterviews" 
              :key="interview.id"
              class="p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors"
            >
              <div class="flex items-center">
                <img 
                  :src="interview.avatar" 
                  :alt="interview.candidate" 
                  class="w-12 h-12 rounded-full mr-4 border-2 border-purple-500/30"
                >
                <div class="flex-1">
                  <h3 class="font-medium text-white">{{ interview.candidate }}</h3>
                  <p class="text-sm text-gray-300">{{ interview.position }}</p>
                  <div class="flex items-center mt-1 text-xs text-gray-400">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ interview.time }}
                    <span class="mx-1">•</span>
                    <span class="flex items-center">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      {{ interview.type }}
                    </span>
                  </div>
                </div>
                <button class="p-1.5 rounded-full bg-purple-600/20 text-purple-400 hover:bg-purple-500/30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Hiring Pipeline -->
        <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-white">Hiring Pipeline</h2>
            <button class="text-purple-400 text-sm hover:text-purple-300">View All</button>
          </div>
          <div class="space-y-5">
            <div 
              v-for="stage in hiringPipeline" 
              :key="stage.stage"
            >
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-300">{{ stage.stage }}</span>
                <span class="font-medium text-white">{{ stage.count }}</span>
              </div>
              <div class="w-full bg-gray-700/50 rounded-full h-2 overflow-hidden">
                <div 
                  class="h-full rounded-full" 
                  :class="stage.color"
                  :style="{ width: (stage.count / 200 * 100) + '%' }"
                ></div>
              </div>
            </div>
          </div>
          
          <div class="mt-6 pt-6 border-t border-gray-700">
            <div class="flex justify-between items-center mb-3">
              <h3 class="text-sm font-medium text-gray-300">Quick Actions</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
              <button 
                v-for="action in quickActions" 
                :key="action.id"
                @click="triggerAction(action)"
                class="group flex items-center justify-center p-3 rounded-lg transition-all duration-200 text-sm"
                :class="[
                  action.type === 'primary' 
                    ? 'bg-purple-600/20 text-purple-400 hover:bg-purple-500/30' 
                    : 'bg-gray-700/50 text-gray-300 hover:bg-gray-600/50',
                  action.disabled ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-0.5 hover:shadow-md'
                ]"
                :disabled="action.disabled"
              >
                <component 
                  :is="action.icon" 
                  class="w-4 h-4 mr-2" 
                  :class="{'text-purple-400': action.type === 'primary'}" 
                />
                {{ action.label }}
              </button>
            </div>
          </div>
        </div>
        
        <!-- Analytics -->
        <div class="gradient-card rounded-xl border border-gray-700/50 p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-white">Analytics Overview</h2>
            <button class="text-purple-400 text-sm hover:text-purple-300">View Report</button>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-gray-800/30 rounded-lg">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Profile Views</span>
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </div>
              <p class="text-2xl font-bold text-white">{{ analytics.views.toLocaleString() }}</p>
              <div class="flex items-center mt-1 text-xs text-green-400">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                12.5% from last month
              </div>
            </div>
            
            <div class="p-4 bg-gray-800/30 rounded-lg">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Applications</span>
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <p class="text-2xl font-bold text-white">{{ analytics.applications }}</p>
              <div class="flex items-center mt-1 text-xs text-green-400">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                8.2% from last month
              </div>
            </div>
            
            <div class="p-4 bg-gray-800/30 rounded-lg">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Match Rate</span>
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <p class="text-2xl font-bold text-white">{{ analytics.matchRate }}%</p>
              <div class="flex items-center mt-1 text-xs text-green-400">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                3.7% from last month
              </div>
            </div>
          </div>
          
          <div class="mt-6 pt-6 border-t border-gray-700">
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-sm font-medium text-gray-300">Performance</h3>
              <span class="text-xs px-2 py-1 bg-green-900/30 text-green-400 rounded-full">+12.5%</span>
            </div>
            <div class="h-2 bg-gray-700/50 rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-purple-500 to-blue-500 rounded-full" style="width: 68%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { 
  UserGroupIcon, 
  BriefcaseIcon, 
  DocumentTextIcon, 
  ClockIcon,
  ArrowUpIcon,
  ArrowDownIcon
} from '@heroicons/vue/24/outline';

export default {
  name: 'CompanyDashboard',
  components: {
    UserGroupIcon,
    BriefcaseIcon,
    DocumentTextIcon,
    ClockIcon,
    ArrowUpIcon,
    ArrowDownIcon
  },
  
  setup() {
    const router = useRouter();
    const companyName = 'Acme Inc.';
    
    // Stats data
    const stats = ref([
      {
        label: 'Total Applicants',
        value: '1,234',
        trend: '12% from last month',
        trendIcon: ArrowUpIcon,
        trendColor: 'text-green-400',
        icon: UserGroupIcon,
        bgColor: 'bg-purple-600'
      },
      {
        label: 'Open Positions',
        value: '24',
        trend: '5 new this week',
        trendIcon: ArrowUpIcon,
        trendColor: 'text-green-400',
        icon: BriefcaseIcon,
        bgColor: 'bg-blue-600'
      },
      {
        label: 'Interviews',
        value: '18',
        trend: '3 today',
        trendIcon: ArrowUpIcon,
        trendColor: 'text-green-400',
        icon: DocumentTextIcon,
        bgColor: 'bg-green-600'
      },
      {
        label: 'Average Hire Time',
        value: '14 days',
        trend: '2 days faster',
        trendIcon: ArrowDownIcon,
        trendColor: 'text-green-400',
        icon: ClockIcon,
        bgColor: 'bg-yellow-600'
      }
    ]);

    // Recent applicants data
    const recentApplicants = ref([
      {
        id: 1,
        name: 'John Doe',
        position: 'Senior Frontend Developer',
        avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
        status: 'Hired',
        date: '2 hours ago'
      },
      {
        id: 2,
        name: 'Jane Smith',
        position: 'UX/UI Designer',
        avatar: 'https://randomuser.me/api/portraits/women/1.jpg',
        status: 'Interview',
        date: '1 day ago'
      },
      {
        id: 3,
        name: 'Robert Johnson',
        position: 'Backend Developer',
        avatar: 'https://randomuser.me/api/portraits/men/2.jpg',
        status: 'Applied',
        date: '2 days ago'
      },
      {
        id: 4,
        name: 'Emily Davis',
        position: 'Product Manager',
        avatar: 'https://randomuser.me/api/portraits/women/2.jpg',
        status: 'Applied',
        date: '3 days ago'
      }
    ]);

    // Active jobs data
    const activeJobs = ref([
      {
        id: 1,
        title: 'Senior Frontend Developer',
        location: 'San Francisco, CA',
        type: 'Full-time',
        status: 'Active',
        applications: 42,
        newApplications: 5,
        applicants: [
          'https://randomuser.me/api/portraits/men/1.jpg',
          'https://randomuser.me/api/portraits/women/1.jpg',
          'https://randomuser.me/api/portraits/men/2.jpg',
          'https://randomuser.me/api/portraits/women/2.jpg',
          'https://randomuser.me/api/portraits/men/3.jpg'
        ]
      },
      {
        id: 2,
        title: 'UX/UI Designer',
        location: 'Remote',
        type: 'Contract',
        status: 'Active',
        applications: 28,
        newApplications: 3,
        applicants: [
          'https://randomuser.me/api/portraits/women/3.jpg',
          'https://randomuser.me/api/portraits/men/4.jpg',
          'https://randomuser.me/api/portraits/women/4.jpg'
        ]
      },
      {
        id: 3,
        title: 'Backend Engineer',
        location: 'New York, NY',
        type: 'Full-time',
        status: 'Draft',
        applications: 0,
        newApplications: 0,
        applicants: []
      }
    ]);
    
    const analytics = {
      views: 2453,
      applications: 124,
      matchRate: 68
    };
    
    // Quick actions data
    // const router = useRouter();
    
    const quickActions = [
      {
        id: 'new-job',
        label: 'New Job',
        type: 'primary',
        icon: 'PlusIcon',
        handler: () => router.push({ name: 'NewJob' })
      },
      {
        id: 'candidates',
        label: 'Candidates',
        type: 'secondary',
        icon: 'UserGroupIcon',
        handler: () => router.push({ name: 'CandidatesList' })
      },
      {
        id: 'schedule',
        label: 'Schedule',
        type: 'secondary',
        icon: 'CalendarIcon',
        handler: () => router.push({ name: 'Schedule' })
      },
      {
        id: 'analytics',
        label: 'Analytics',
        type: 'secondary',
        icon: 'ChartBarIcon',
        handler: () => router.push({ name: 'Analytics' })
      }
    ];
    
    // Trigger quick action
    const triggerAction = (action) => {
      if (action.disabled) return;
      action.handler();
    };
    
    return {
      companyName,
      stats,
      recentApplicants,
      activeJobs,
      analytics,
      quickActions,
      triggerAction
    };
  }
};
</script>
