<template>
  <div class="space-y-6">
    <!-- Tabs for different views -->
    <div class="border-b border-gray-200 dark:border-gray-700">
      <nav class="-mb-px flex space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.name"
          @click="currentView = tab.name"
          :class="[
            currentView === tab.name
              ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300'
              : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200',
            'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium'
          ]"
        >
          {{ tab.label }}
          <span 
            v-if="tab.count"
            :class="[
              currentView === tab.name 
                ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-200' 
                : 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-200',
              'ml-2 rounded-full py-0.5 px-2.5 text-xs font-medium'
            ]"
          >
            {{ tab.count }}
          </span>
        </button>
      </nav>
    </div>

    <!-- Application Stats -->
    <div v-show="currentView === 'applications'" class="space-y-6">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <div v-for="stat in stats" :key="stat.name" class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0" :class="stat.bgColorClass">
              <component :is="stat.icon" class="h-6 w-6 text-white" />
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                  {{ stat.name }}
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ stat.value }}
                  </div>
                  <div 
                    v-if="stat.change" 
                    :class="[
                      stat.changeType === 'increase' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400',
                      'ml-2 flex items-baseline text-sm font-semibold'
                    ]"
                  >
                    <ArrowUpIcon 
                      v-if="stat.changeType === 'increase'" 
                      class="self-center flex-shrink-0 h-4 w-4 text-green-500" 
                      aria-hidden="true" 
                    />
                    <ArrowDownIcon 
                      v-else 
                      class="self-center flex-shrink-0 h-4 w-4 text-red-500" 
                      aria-hidden="true" 
                    />
                    <span class="sr-only">
                      {{ stat.changeType === 'increase' ? 'Increased' : 'Decreased' }} by
                    </span>
                    {{ stat.change }}
                  </div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

      </div>
      
      <!-- Applications List -->
      <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('user.applications.recent_applications') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('user.applications.recent_applications_description') }}
          </p>
        </div>
        <button
          @click="showNewApplicationModal = true"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('user.applications.new_application') }}
        </button>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.applications.position') }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.applications.company') }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.applications.applied_date') }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('user.applications.status') }}
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">{{ $t('common.actions') }}</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="application in applications" :key="application.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ application.position }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ application.jobType }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ application.company }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ application.location }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(application.appliedDate) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="applicationStatusClass(application.status)">
                    {{ $t(`user.applications.status_${application.status}`) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button 
                    @click="viewApplication(application.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-4"
                  >
                    {{ $t('common.view') }}
                  </button>
                  <button 
                    @click="editApplication(application.id)"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                  >
                    {{ $t('common.edit') }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              :disabled="currentPage === 1"
              @click="currentPage--"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              {{ $t('common.previous') }}
            </button>
            <button
              :disabled="currentPage * itemsPerPage >= totalItems"
              @click="currentPage++"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              {{ $t('common.next') }}
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ $t('common.showing') }}
                <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                {{ $t('common.to') }}
                <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, totalItems) }}</span>
                {{ $t('common.of') }}
                <span class="font-medium">{{ totalItems }}</span>
                {{ $t('common.results') }}
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  :disabled="currentPage === 1"
                  @click="currentPage--"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <span class="sr-only">{{ $t('common.previous') }}</span>
                  <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                </button>
                <button
                  v-for="page in totalPages"
                  :key="page"
                  @click="currentPage = page"
                  :class="[
                    currentPage === page 
                      ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-200' 
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700',
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  :disabled="currentPage >= totalPages"
                  @click="currentPage++"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <span class="sr-only">{{ $t('common.next') }}</span>
                  <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Interview Calendar View -->
    <div v-show="currentView === 'calendar'" class="space-y-6">
      <InterviewCalendar :interviews="upcomingInterviews" />
      
      <!-- Upcoming Interviews List -->
      <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('user.applications.upcoming_interviews') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('user.applications.upcoming_interviews_description') }}
          </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
          <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            <li v-for="interview in upcomingInterviews" :key="interview.id" class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                      <CalendarIcon class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                    </div>
                  </div>
                  <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ interview.position }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ formatDateTime(interview.interviewDate) }}
                    </p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ interview.company }}
                  </span>
                  <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', 
                    interview.status === 'scheduled' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                    interview.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                  ]">
                    {{ $t(`user.applications.status.${interview.status}`) }}
                  </span>
                </div>
              </div>
            </li>
            <li v-if="upcomingInterviews.length === 0" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
              {{ $t('user.applications.no_upcoming_interviews') }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { format, parseISO, isAfter } from 'date-fns';
import { 
  ArrowUpIcon, 
  ArrowDownIcon, 
  DocumentTextIcon, 
  CheckCircleIcon, 
  ClockIcon, 
  XCircleIcon,
  PlusIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  CalendarIcon
} from '@heroicons/vue/24/outline';
import InterviewCalendar from './InterviewCalendar.vue';

export default {
  name: 'JobApplications',
  components: {
    ArrowUpIcon,
    ArrowDownIcon,
    DocumentTextIcon,
    CheckCircleIcon,
    ClockIcon,
    XCircleIcon,
    PlusIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    CalendarIcon,
    InterviewCalendar
  },
  setup() {
    const { t } = useI18n();
    
    // Current view state
    const currentView = ref('applications');
    
    // Tabs configuration
    const tabs = [
      { name: 'applications', label: t('user.applications.applications'), count: 0 },
      { name: 'calendar', label: t('user.applications.interviews'), count: 0 }
    ];
    
    // Stats data
    const stats = ref([
      { 
        name: t('user.applications.total_applications'),
        value: 24,
        change: '22%',
        changeType: 'increase',
        icon: DocumentTextIcon,
        bgColorClass: 'bg-indigo-500 p-3 rounded-md'
      },
      { 
        name: t('user.applications.in_review'),
        value: 8,
        change: '5.4%',
        changeType: 'increase',
        icon: ClockIcon,
        bgColorClass: 'bg-yellow-500 p-3 rounded-md'
      },
      { 
        name: t('user.applications.interview_scheduled'),
        value: 4,
        change: '3.2%',
        changeType: 'decrease',
        icon: CheckCircleIcon,
        bgColorClass: 'bg-green-500 p-3 rounded-md'
      },
      { 
        name: t('user.applications.rejected'),
        value: 3,
        change: '1.1%',
        changeType: 'decrease',
        icon: XCircleIcon,
        bgColorClass: 'bg-red-500 p-3 rounded-md'
      }
    ]);

    // Mock applications data with interviews
    const applications = ref([
      {
        id: 1,
        position: 'Senior Frontend Developer',
        company: 'TechCorp',
        location: 'San Francisco, CA',
        jobType: 'Full-time',
        appliedDate: '2023-06-15T10:30:00Z',
        status: 'interview',
        interviewDate: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000).toISOString(), // 2 days from now
        interviewType: 'Technical',
        interviewer: 'Sarah Johnson',
        meetingLink: 'https://meet.techcorp.com/abc123',
        notes: 'Focus on React and TypeScript. Prepare for coding challenge.',
        resume: 'Resume_Senior_Frontend_Developer.pdf',
        coverLetter: 'Cover_Letter_TechCorp.pdf'
      },
      {
        id: 2,
        position: 'UI/UX Designer',
        company: 'DesignHub',
        location: 'Remote',
        jobType: 'Contract',
        appliedDate: '2023-06-10T14:45:00Z',
        status: 'interview',
        interviewDate: new Date(Date.now() + 5 * 24 * 60 * 60 * 1000).toISOString(), // 5 days from now
        interviewType: 'Portfolio Review',
        interviewer: 'Alex Chen',
        meetingLink: 'https://zoom.us/j/123456789',
        notes: 'Be ready to present 2-3 case studies',
        resume: 'Resume_UIUX_Designer.pdf',
        coverLetter: 'Cover_Letter_DesignHub.pdf'
      },
      {
        id: 3,
        position: 'Full Stack Developer',
        company: 'WebSolutions Inc.',
        location: 'New York, NY',
        jobType: 'Full-time',
        appliedDate: '2023-06-05T09:15:00Z',
        status: 'completed',
        interviewDate: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(), // 2 days ago
        interviewType: 'System Design',
        notes: 'Waiting for feedback',
        resume: 'Resume_Full_Stack_Developer.pdf',
        rejectionReason: 'Position filled internally.'
      },
      {
        id: 4,
        position: 'Product Manager',
        company: 'InnovateCo',
        location: 'Austin, TX',
        jobType: 'Full-time',
        appliedDate: '2023-05-28T11:20:00Z',
        status: 'interview',
        interviewDate: new Date(Date.now() + 1 * 24 * 60 * 60 * 1000).toISOString(), // Tomorrow
        interviewType: 'Cultural Fit',
        interviewer: 'Jamie Smith (HR)',
        meetingLink: 'https://teams.microsoft.com/l/meetup-join/19:meeting_123',
        notes: 'Prepare questions about team structure and product roadmap',
        offerAmount: 120000,
        offerDate: '2023-06-20T10:00:00Z',
      },
      {
        id: 5,
        position: 'DevOps Engineer',
        company: 'CloudScale',
        location: 'Remote',
        jobType: 'Full-time',
        appliedDate: '2023-06-18T16:30:00Z',
        status: 'scheduled',
        interviewDate: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString(), // 7 days from now
        interviewType: 'Technical Deep Dive',
        interviewer: 'Michael Brown',
        meetingLink: 'https://whereby.com/cloudscale-interview',
        notes: 'Focus on AWS, Kubernetes, and CI/CD pipelines'
      }
    ]);

    // Get upcoming interviews (interviews with future dates)
    const upcomingInterviews = computed(() => {
      return applications.value
        .filter(app => app.interviewDate && isAfter(parseISO(app.interviewDate), new Date()))
        .sort((a, b) => new Date(a.interviewDate) - new Date(b.interviewDate));
    });

    // Update tab counts
    tabs[0].count = applications.value.length;
    tabs[1].count = upcomingInterviews.value.length;

    // Pagination
    const currentPage = ref(1);
    const itemsPerPage = 10;
    const totalItems = computed(() => applications.value.length);
    const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage));

    // Modal state
    const showNewApplicationModal = ref(false);

    // Format date and time
    const formatDate = (dateString) => {
      return format(new Date(dateString), 'MMM d, yyyy');
    };
    
    const formatDateTime = (dateString) => {
      if (!dateString) return '';
      return format(new Date(dateString), 'MMM d, yyyy • h:mm a');
    };

    // Get status badge class
    const applicationStatusClass = (status) => {
      const baseClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
      switch (status) {
        case 'applied':
          return `${baseClass} bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200`;
        case 'interview':
          return `${baseClass} bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200`;
        case 'offer':
          return `${baseClass} bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200`;
        case 'rejected':
          return `${baseClass} bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200`;
        default:
          return `${baseClass} bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200`;
      }
    };

    // View application details
    const viewApplication = (id) => {
      // In a real app, this would navigate to the application detail page
      console.log('View application:', id);
    };

    // Edit application
    const editApplication = (id) => {
      // In a real app, this would open an edit form
      console.log('Edit application:', id);
    };

    return {
      // State
      currentView,
      tabs,
      stats,
      applications,
      upcomingInterviews,
      currentPage,
      itemsPerPage,
      totalItems,
      totalPages,
      showNewApplicationModal,
      
      // Methods
      formatDate,
      formatDateTime,
      applicationStatusClass,
      viewApplication,
      editApplication
    };
  }
};
</script>
