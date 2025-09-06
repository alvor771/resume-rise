<template>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          {{ $t('admin.users.user_details') }}
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t('admin.users.managing_user', { name: user.full_name || user.email }) }}
        </p>
      </div>
      <div class="mt-4 md:mt-0 flex space-x-3">
        <router-link
          :to="{ name: 'AdminUsers' }"
          class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <ArrowLeftIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('common.back_to_list') }}
        </router-link>
        <router-link
          v-if="hasPermission('users.update')"
          :to="{ name: 'AdminEditUser', params: { id: user.id } }"
          class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PencilIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('common.edit') }}
        </router-link>
      </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
      <nav class="-mb-px flex space-x-8 overflow-x-auto">
        <button
          v-for="tab in tabs"
          :key="tab.name"
          @click="activeTab = tab.name"
          :class="[
            activeTab === tab.name
              ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-500',
            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
          ]"
        >
          {{ tab.title }}
          <span v-if="tab.count" class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
            {{ tab.count }}
          </span>
        </button>
      </nav>
    </div>

    <!-- Tab Content -->
    <div class="mt-6">
      <!-- Profile Tab -->
      <div v-if="activeTab === 'profile'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('admin.users.profile_information') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('admin.users.basic_information_about_user') }}
          </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
          <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ $t('auth.full_name') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                {{ user.full_name || '—' }}
              </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ $t('auth.email') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                {{ user.email }}
              </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ $t('auth.role') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  user.role === 'admin' 
                    ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' 
                    : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                ]">
                  {{ $t(`roles.${user.role}`) }}
                </span>
              </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ $t('common.status') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  user.is_active 
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                ]">
                  {{ user.is_active ? $t('common.active') : $t('common.inactive') }}
                </span>
              </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ $t('common.created_at') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                {{ formatDate(user.created_at) }}
              </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                {{ $t('common.last_login') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                {{ user.last_login_at ? formatDate(user.last_login_at) : $t('common.never') }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Resume Info Tab -->
      <div v-else-if="activeTab === 'resume-info'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('resume.resume_information') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('resume.detailed_resume_information') }}
          </p>
        </div>
        <div class="px-4 py-5 sm:p-0">
          <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <DocumentTextIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium">{{ $t('resume.no_resume_info') }}</h3>
            <p class="mt-1 text-sm">{{ $t('resume.no_resume_description') }}</p>
          </div>
        </div>
      </div>

      <!-- Resumes Tab -->
      <div v-else-if="activeTab === 'resumes'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('resume.my_resumes') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('resume.manage_your_resumes') }}
          </p>
        </div>
        <div class="px-4 py-5 sm:p-0">
          <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <DocumentTextIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium">{{ $t('resume.no_resumes') }}</h3>
            <p class="mt-1 text-sm">{{ $t('resume.get_started_by_creating_new') }}</p>
            <div class="mt-6">
              <router-link
                :to="{ name: 'CreateResume' }"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
                {{ $t('resume.new_resume') }}
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Templates Tab -->
      <div v-else-if="activeTab === 'templates'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('templates.my_templates') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('templates.manage_your_resume_templates') }}
          </p>
        </div>
        <div class="px-4 py-5 sm:p-0">
          <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <TemplateIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium">{{ $t('templates.no_templates') }}</h3>
            <p class="mt-1 text-sm">{{ $t('templates.get_started_by_creating_new') }}</p>
          </div>
        </div>
      </div>

      <!-- Subscriptions Tab -->
      <div v-else-if="activeTab === 'subscriptions'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('subscriptions.my_subscriptions') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('subscriptions.manage_your_subscriptions') }}
          </p>
        </div>
        <div class="px-4 py-5 sm:p-0">
          <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <BellIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium">{{ $t('subscriptions.no_subscriptions') }}</h3>
            <p class="mt-1 text-sm">{{ $t('subscriptions.get_started_by_subscribing') }}</p>
          </div>
        </div>
      </div>

      <!-- Job Applications Tab -->
      <div v-else-if="activeTab === 'applications'" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('applications.my_applications') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('applications.track_your_job_applications') }}
          </p>
        </div>
        <div class="px-4 py-5 sm:p-0">
          <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <BriefcaseIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium">{{ $t('applications.no_applications') }}</h3>
            <p class="mt-1 text-sm">{{ $t('applications.start_applying_to_jobs') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useUserStore } from '@/stores/user';
import { useToast } from 'vue-toastification';
import { 
  ArrowLeftIcon,
  PencilSquareIcon,
  DocumentTextIcon,
  RectangleGroupIcon,
  BellIcon,
  BriefcaseIcon,
  PlusIcon 
} from '@heroicons/vue/24/outline';

export default {
  name: 'UserView',
  components: {
    ArrowLeftIcon,
    PencilIcon: PencilSquareIcon,
    DocumentTextIcon,
    TemplateIcon: RectangleGroupIcon,
    BellIcon,
    BriefcaseIcon,
    PlusIcon
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { t } = useI18n();
    const toast = useToast();
    const userStore = useUserStore();
    
    console.log('Route params:', route.params);
    console.log('Route full path:', route.fullPath);
    
    const userId = route.params.id;
    
    if (!userId) {
      console.error('User ID is missing from route params');
      router.push({ name: 'AdminUsers' });
    }
    const user = ref({
      id: '',
      full_name: '',
      email: '',
      role: 'user',
      is_active: true,
      created_at: null,
      last_login_at: null
    });
    
    const tabs = [
      { name: 'profile', title: t('profile.my_profile'), count: 0 },
      { name: 'resume-info', title: t('resume.resume_info'), count: 0 },
      { name: 'resumes', title: t('resume.my_resumes'), count: 0 },
      { name: 'templates', title: t('templates.my_templates'), count: 0 },
      { name: 'subscriptions', title: t('subscriptions.my_subscriptions'), count: 0 },
      { name: 'applications', title: t('applications.my_applications'), count: 0 },
    ];
    
    const activeTab = ref('profile');
    const isLoading = ref(true);

    // Format date for display
    const formatDate = (dateString) => {
      if (!dateString) return '—';
      const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };

    // Check permissions
    const hasPermission = (permission) => {
        console.log(permission);
      // Implement your permission logic here
      return true;
    };

    // Fetch user data
    const fetchUser = async () => {
      try {
        isLoading.value = true;
        const userData = await userStore.fetchUser(userId);
        user.value = { ...userData };
      } catch (error) {
        console.error('Error fetching user:', error);
        toast.error(t('common.errors.failed_to_load_data'));
        router.push({ name: 'AdminUsers' });
      } finally {
        isLoading.value = false;
      }
    };

    onMounted(() => {
      fetchUser();
    });

    return {
      user,
      tabs,
      activeTab,
      isLoading,
      formatDate,
      hasPermission
    };
  }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
