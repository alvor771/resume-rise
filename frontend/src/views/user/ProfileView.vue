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
            <div class="space-y-8">

            <router-view />

              <!-- Profile Header -->
              <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center space-x-4">
                  <div class="relative">
                    <img 
                      src="https://ui-avatars.com/api/?name=John+Doe&background=4f46e5&color=fff&size=96" 
                      alt="Profile"
                      class="w-20 h-20 rounded-full border-4 border-white dark:border-gray-700 shadow"
                    />
                    <button 
                      v-if="isEditing"
                      class="absolute -bottom-2 -right-2 bg-indigo-600 text-white p-1.5 rounded-full hover:bg-indigo-700 transition-colors"
                    >
                      <PencilIcon class="h-4 w-4" />
                    </button>
                  </div>
                  <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                      {{ user.first_name }} {{ user.last_name }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ user.position }}</p>
                  </div>
                </div>
                <div class="flex space-x-3">
                  <button
                    v-if="!isEditing"
                    @click="handleEditProfile"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <PencilIcon class="-ml-1 mr-2 h-4 w-4" />
                    {{ $t('common.edit_profile') }}
                  </button>
                  <button
                    v-else
                    @click="handleSaveProfile"
                    :disabled="isSaving"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg v-if="isSaving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <CheckIcon v-else class="-ml-1 mr-2 h-4 w-4" />
                    {{ isSaving ? $t('common.saving') : $t('common.save_changes') }}
                  </button>
                  <button
                    v-if="isEditing"
                    @click="isEditing = false"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ $t('common.cancel') }}
                  </button>
                </div>
              </div>

              <!-- Personal Information -->
              <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $t('user.profile.personal_info') }}
                  </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.first_name') }}
                    </label>
                    <input
                      v-model="user.first_name"
                      :readonly="!isEditing"
                      type="text"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.last_name') }}
                    </label>
                    <input
                      v-model="user.last_name"
                      :readonly="!isEditing"
                      type="text"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.email') }}
                    </label>
                    <input
                      v-model="user.email"
                      type="email"
                      readonly
                      class="w-full rounded-md border-gray-300 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.phone') }}
                    </label>
                    <input
                      v-model="user.phone"
                      :readonly="!isEditing"
                      type="tel"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.bio') }}
                    </label>
                    <textarea
                      v-model="user.bio"
                      :readonly="!isEditing"
                      rows="3"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    ></textarea>
                  </div>
                </div>
              </div>

              <!-- Address Information -->
              <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $t('user.profile.address_info') }}
                  </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.country') }}
                    </label>
                    <input
                      v-model="user.address.country"
                      :readonly="!isEditing"
                      type="text"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.city') }}
                    </label>
                    <input
                      v-model="user.address.city"
                      :readonly="!isEditing"
                      type="text"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.street') }}
                    </label>
                    <input
                      v-model="user.address.street"
                      :readonly="!isEditing"
                      type="text"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      {{ $t('user.profile.postal_code') }}
                    </label>
                    <input
                      v-model="user.address.postal_code"
                      :readonly="!isEditing"
                      type="text"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                      :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                    />
                  </div>
                </div>
              </div>

              <!-- Skills Section -->
              <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $t('user.profile.skills') }}
                  </h3>
                  <button
                    v-if="isEditing"
                    @click="addNewSkill"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <PlusIcon class="-ml-0.5 mr-1.5 h-4 w-4" />
                    {{ $t('common.add') }}
                  </button>
                </div>
                <div class="space-y-4">
                  <div v-for="(skill, index) in user.skills" :key="index" class="space-y-2">
                    <div class="flex items-center justify-between">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $t('user.profile.skill_name') }} {{ index + 1 }}
                      </label>
                      <button
                        v-if="isEditing"
                        @click="removeSkill(index)"
                        class="text-red-500 hover:text-red-700"
                      >
                        <TrashIcon class="h-4 w-4" />
                      </button>
                    </div>
                    <div class="flex items-center space-x-4">
                      <div class="flex-1">
                        <input
                          v-model="skill.name"
                          :readonly="!isEditing"
                          type="text"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                          :class="{ 'bg-gray-100 dark:bg-gray-700': !isEditing }"
                        />
                      </div>
                      <div class="w-32">
                        <div class="flex items-center">
                          <input
                            v-model.number="skill.level"
                            :disabled="!isEditing"
                            type="range"
                            min="0"
                            max="100"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                          >
                          <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 w-12 text-right">
                            {{ skill.level }}%
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Experience Section -->
              <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $t('user.profile.experience') }}
                  </h3>
                  <button
                    v-if="isEditing"
                    @click="addExperience"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <PlusIcon class="-ml-0.5 mr-1.5 h-4 w-4" />
                    {{ $t('common.add') }}
                  </button>
                </div>
                <div class="space-y-6">
                  <div v-for="(exp) in user.experience" :key="exp.id" class="relative pl-6 pb-6 border-l-2 border-gray-200 dark:border-gray-700">
                    <div v-if="isEditing" class="absolute -left-2.5 top-0 w-4 h-4 rounded-full bg-indigo-600"></div>
                    <div class="relative">
                      <div class="flex items-center justify-between">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white">
                          {{ exp.position || $t('user.profile.new_experience') }}
                        </h4>
                        <button
                          v-if="isEditing"
                          @click="removeExperience(exp.id)"
                          class="text-red-500 hover:text-red-700"
                        >
                          <TrashIcon class="h-4 w-4" />
                        </button>
                      </div>
                      <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ exp.company }}
                        <span v-if="exp.company && exp.start_date"> • </span>
                        <template v-if="exp.start_date || exp.end_date">
                          {{ exp.start_date }} - {{ exp.end_date || $t('user.profile.present') }}
                        </template>
                      </div>
                      <div v-if="exp.description" class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ exp.description }}
                      </div>
                      
                      <div v-if="isEditing" class="mt-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                              {{ $t('user.profile.position') }}
                            </label>
                            <input
                              v-model="exp.position"
                              type="text"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            />
                          </div>
                          <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                              {{ $t('user.profile.company') }}
                            </label>
                            <input
                              v-model="exp.company"
                              type="text"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            />
                          </div>
                          <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                              {{ $t('user.profile.start_date') }}
                            </label>
                            <input
                              v-model="exp.start_date"
                              type="date"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            />
                          </div>
                          <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                              {{ $t('user.profile.end_date') }}
                            </label>
                            <input
                              v-model="exp.end_date"
                              type="date"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            />
                          </div>
                        </div>
                        <div>
                          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ $t('user.profile.description') }}
                          </label>
                          <textarea
                            v-model="exp.description"
                            rows="3"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                          ></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { 
  PencilIcon, 
  Cog6ToothIcon, 
  CheckIcon, 
  PlusIcon, 
  TrashIcon 
} from '@heroicons/vue/24/outline';
import { useI18n } from 'vue-i18n';
import { useToast } from 'vue-toastification';
import { useAuthStore } from '@/stores/auth';
import userApi from '@/api/user';
import ProfileNavigation from '@/components/user/ProfileNavigation.vue';
import Breadcrumb from '@/components/ui/Breadcrumb.vue';
import SettingsModal from '@/components/user/SettingsModal.vue';

export default {
  name: 'ProfileView',
  components: {
    PencilIcon,
    Cog6ToothIcon,
    CheckIcon,
    PlusIcon,
    TrashIcon,
    ProfileNavigation,
    Breadcrumb,
    SettingsModal
  },
  setup() {
    const { t } = useI18n();
    const toast = useToast();
    const authStore = useAuthStore();
    const loading = ref(true);
    const showSettingsModal = ref(false);
    const isEditing = ref(false);
    const isSaving = ref(false);
    
    // User data with default values
    const user = ref({
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      position: '',
      bio: '',
      address: {
        country: '',
        city: '',
        street: '',
        postal_code: ''
      },
      social_links: {
        linkedin: '',
        github: '',
        twitter: ''
      },
      skills: [],
      experience: []
    });

    // Fetch user profile data
    const fetchUserProfile = async () => {
      try {
        loading.value = true;
        const profileData = await userApi.getProfile();
        
        // Map the API response to our user object structure
        if (profileData) {
          user.value = {
            ...user.value,
            ...profileData,
            // Ensure nested objects exist
            address: profileData.address || user.value.address,
            social_links: profileData.social_links || user.value.social_links,
            skills: Array.isArray(profileData.skills) ? profileData.skills : [],
            experience: Array.isArray(profileData.experience) ? profileData.experience : []
          };
        }
      } catch (error) {
        console.error('Error fetching profile:', error);
        toast.error(t('user.profile.fetch_error'));
      } finally {
        loading.value = false;
      }
    };

    // Save profile data
    const saveProfile = async () => {
      try {
        isSaving.value = true;
        
        // Prepare the data for the API
        const profileData = {
          first_name: user.value.first_name,
          last_name: user.value.last_name,
          phone: user.value.phone,
          position: user.value.position,
          bio: user.value.bio,
          address: { ...user.value.address },
          social_links: { ...user.value.social_links }
        };

        // Update profile
        await userApi.updateProfile(profileData);
        
        // Update skills if they exist
        if (user.value.skills && user.value.skills.length > 0) {
          await userApi.updateSkills(user.value.skills);
        }
        
        // Update experience if it exists
        if (user.value.experience && user.value.experience.length > 0) {
          // Format experience data before sending
          const formattedExperience = user.value.experience.map(exp => ({
            id: exp.id,
            position: exp.position,
            company: exp.company,
            start_date: exp.start_date,
            end_date: exp.end_date,
            description: exp.description
          }));
          await userApi.updateExperience(formattedExperience);
        }
        
        // Update auth store with new user data
        authStore.user = { ...authStore.user, ...profileData };
        
        toast.success(t('user.profile.update_success'));
        isEditing.value = false;
      } catch (error) {
        console.error('Error saving profile:', error);
        toast.error(error.response?.data?.message || t('user.profile.update_error'));
      } finally {
        isSaving.value = false;
      }
    };

    // Load user data when component mounts
    onMounted(() => {
      fetchUserProfile();
    });
    
    const handleEditProfile = () => {
      isEditing.value = !isEditing.value;
    };
    
    const handleSaveProfile = async () => {
      await saveProfile();
    };
    
    const handleSettingsClick = () => {
      showSettingsModal.value = true;
    };
    
    const handleSettingsSaved = () => {
      toast.success(t('settings.saved_success'));
      showSettingsModal.value = false;
    };
    
    const addNewSkill = () => {
      if (!user.value.skills) user.value.skills = [];
      user.value.skills.push({ name: '', level: 50 });
    };
    
    const removeSkill = (index) => {
      if (user.value.skills && user.value.skills.length > index) {
        user.value.skills.splice(index, 1);
      }
    };
    
    const addExperience = () => {
      if (!user.value.experience) user.value.experience = [];
      user.value.experience.unshift({
        id: Date.now(),
        position: '',
        company: '',
        start_date: '',
        end_date: '',
        description: ''
      });
    };
    
    const removeExperience = (id) => {
      if (!user.value.experience) return;
      const index = user.value.experience.findIndex(exp => exp.id === id);
      if (index !== -1) {
        user.value.experience.splice(index, 1);
      }
    };
    
    return {
      loading,
      isSaving,
      showSettingsModal,
      isEditing,
      user,
      handleEditProfile,
      handleSaveProfile,
      handleSettingsClick,
      handleSettingsSaved,
      addNewSkill,
      removeSkill,
      addExperience,
      removeExperience
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
