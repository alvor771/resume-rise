<template>
  <nav class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Mobile menu button -->
          <div class="-mr-2 flex items-center md:hidden">
            <button 
              @click="isOpen = !isOpen"
              type="button" 
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
              aria-controls="mobile-menu" 
              aria-expanded="false"
            >
              <span class="sr-only">Open main menu</span>
              <Bars3Icon v-if="!isOpen" class="block h-6 w-6" />
              <XMarkIcon v-else class="block h-6 w-6" />
            </button>
          </div>
          
          <!-- Desktop navigation -->
          <div class="hidden md:flex space-x-8">
            <router-link 
              v-for="item in navigation" 
              :key="item.name" 
              :to="{ name: item.route }"
              class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              :class="[isActive(item.route) 
                ? 'border-indigo-500 text-gray-900 dark:text-white' 
                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white',
                'group inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium']"
            >
              <component 
                :is="item.icon" 
                class="mr-2 h-5 w-5" 
                :class="[isActive(item.route) 
                  ? 'text-indigo-600 dark:text-indigo-400' 
                  : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300']"
                aria-hidden="true" 
              />
              {{ item.name }}
            </router-link>
          </div>
        </div>
        
        <div class="hidden md:flex items-center">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
            <CheckCircleIcon class="h-4 w-4 mr-1.5" />
            {{ $t('user.profile.active_account') }}
          </span>
        </div>
      </div>
    </div>
    
    <!-- Mobile menu -->
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 -translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-1"
    >
      <div v-show="isOpen" class="md:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
          <router-link
            v-for="item in navigation"
            :key="item.name"
            :to="{ name: item.route }"
            @click="isOpen = false"
            class="group flex items-center px-3 py-2 text-base font-medium rounded-md"
            :class="[isActive(item.route)
              ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-gray-700 dark:text-white' 
              : 'text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white',
              'border-l-4']"
          >
            <component 
              :is="item.icon" 
              class="mr-3 h-5 w-5" 
              :class="[isActive(item.route) 
                ? 'text-indigo-600 dark:text-indigo-400' 
                : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300']"
              aria-hidden="true" 
            />
            {{ item.name }}
          </router-link>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center px-4">
            <div class="flex-shrink-0">
              <UserCircleIcon class="h-10 w-10 text-gray-400" />
            </div>
            <div class="ml-3">
              <div class="text-base font-medium text-gray-800 dark:text-white">
                {{ user.name || $t('user.profile.guest') }}
              </div>
              <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ user.email || 'user@example.com' }}
              </div>
            </div>
          </div>
          <div class="mt-3 space-y-1">
            <button
              @click="logout"
              class="block w-full px-4 py-2 text-left text-base font-medium text-red-600 hover:bg-red-50 hover:text-red-800 dark:hover:bg-gray-700"
            >
              {{ $t('auth.logout') }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { 
  UserCircleIcon,
  UserIcon,
  DocumentTextIcon,
  RectangleGroupIcon,
  CreditCardIcon,
  BriefcaseIcon,
  CheckCircleIcon,
  Bars3Icon,
  XMarkIcon
} from '@heroicons/vue/24/outline';

export default {
  name: 'ProfileNavigation',
  components: {
    UserCircleIcon,
    UserIcon,
    DocumentTextIcon,
    CreditCardIcon,
    BriefcaseIcon,
    CheckCircleIcon,
    Bars3Icon,
    XMarkIcon,
    RectangleGroupIcon
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { t } = useI18n();
    const isOpen = ref(false);
    
    // Mock user data - replace with actual user data from your auth store
    const user = computed(() => {
      // In a real app, get this from your auth store
      return {
        name: 'John Doe',
        email: 'john@example.com',
        avatar: null
      };
    });
    
    const navigation = computed(() => [
      { 
        name: t('user.profile.my_profile'), 
        route: 'ProfileInfo',
        icon: 'UserIcon'
      },
      { 
        name: t('user.profile.cv_info'), 
        route: 'ProfileCV',
        icon: 'DocumentTextIcon'
      },
      { 
        name: t('user.profile.my_resumes'), 
        route: 'ProfileResumes',
        icon: 'DocumentTextIcon'
      },
      { 
        name: t('user.profile.my_templates'), 
        route: 'ProfileTemplates',
        icon: 'RectangleGroupIcon'
      },
      { 
        name: t('user.profile.subscriptions'), 
        route: 'ProfileSubscriptions',
        icon: 'CreditCardIcon'
      },
      { 
        name: t('user.profile.job_applications'), 
        route: 'ProfileApplications',
        icon: 'BriefcaseIcon'
      }
    ]);
    
    const isActive = (routeName) => {
      return route.name === routeName;
    };
    
    const logout = () => {
      // In a real app, call your logout action
      localStorage.removeItem('token');
      router.push({ name: 'Login' });
    };
    
    return {
      isOpen,
      user,
      navigation,
      isActive,
      logout
    };
  }
};
</script>
