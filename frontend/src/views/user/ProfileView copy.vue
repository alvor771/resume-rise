<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Settings Modal -->
    <SettingsModal 
      :is-open="showSettingsModal"
      @update:is-open="val => showSettingsModal = val"
      @saved="handleSettingsSaved"
    />
    <!-- Page header with gradient background -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800 overflow-hidden">
      <div class="relative max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-4">
          <Breadcrumb />
        </div>
        
        <div class="md:flex md:items-center md:justify-between">
          <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
              {{ $t('user.profile.title') }}
            </h1>
            <p class="mt-2 text-lg text-indigo-100/90">
              {{ $t('user.profile.subtitle') }}
            </p>
          </div>
          <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <button
              type="button"
              class="glass-button group inline-flex items-center justify-center px-5 py-2.5 text-base font-medium rounded-xl shadow-lg backdrop-blur-sm transition-all duration-200"
              @click="handleSettingsClick"
            >
              <Cog6ToothIcon class="-ml-1 mr-2 h-5 w-5 transition-transform duration-200 group-hover:rotate-45" />
              {{ $t('common.settings') }}
            </button>
            <button
              type="button"
              class="glass-button group inline-flex items-center justify-center px-5 py-2.5 text-base font-medium rounded-xl shadow-lg backdrop-blur-sm transition-all duration-200"
              @click="handleEditProfile"
            >
              <PencilIcon class="-ml-1 mr-2 h-5 w-5 transition-transform duration-200 group-hover:scale-110" />
              {{ $t('common.edit_profile') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Profile navigation -->
      <ProfileNavigation />
      
      <!-- Main content -->
      <main class="py-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
          <!-- Loading state -->
          <div v-if="loading" class="p-6">
            <div class="animate-pulse space-y-4">
              <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
              <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
              <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
            </div>
          </div>
          
          <!-- Content -->
          <div v-else class="p-6">
            <router-view v-slot="{ Component }">
              <transition 
                name="fade" 
                mode="out-in"
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
              >
                <component :is="Component" />
              </transition>
            </router-view>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { PencilIcon, Cog6ToothIcon } from '@heroicons/vue/24/outline';
import { useI18n } from 'vue-i18n';
import { useToast } from 'vue-toastification';
import ProfileNavigation from '@/components/user/ProfileNavigation.vue';
import Breadcrumb from '@/components/ui/Breadcrumb.vue';
import SettingsModal from '@/components/user/SettingsModal.vue';

export default {
  name: 'ProfileView',
  components: {
    PencilIcon,
    Cog6ToothIcon,
    ProfileNavigation,
    Breadcrumb,
    SettingsModal
  },
  setup() {
    const { t } = useI18n();
    const toast = useToast();
    const loading = ref(true);
    const showSettingsModal = ref(false);
    
    // Simulate loading
    onMounted(() => {
      setTimeout(() => {
        loading.value = false;
      }, 500);
    });
    
    const handleEditProfile = () => {
      // In a real app, you might open an edit modal or navigate to an edit page
      console.log('Edit profile clicked');
    };
    
    const handleSettingsClick = () => {
      showSettingsModal.value = true;
    };
    
    const handleSettingsSaved = () => {
      toast.success(t('settings.saved_success'));
      showSettingsModal.value = false;
    };
    

    
    return {
      loading,
      showSettingsModal,
      handleEditProfile,
      handleSettingsClick,
      handleSettingsSaved,
    };
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Glass button styles */
.glass-button {
  position: relative;
  overflow: hidden;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  transition: all 0.3s ease;
}

.glass-button:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.glass-button:active {
  transform: translateY(0);
}

.glass-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  transition: 0.5s;
}

.glass-button:hover::before {
  left: 100%;
}
</style>
