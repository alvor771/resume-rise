<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-indigo-600 px-6 py-8 text-white">
          <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-6">
              <div class="relative">
                <img 
                  :src="user.photo || 'https://ui-avatars.com/api/?name=' + user.name + '&background=4f46e5&color=fff&size=128'" 
                  :alt="user.name"
                  class="w-24 h-24 rounded-full border-4 border-white dark:border-gray-700 shadow-md"
                >
              </div>
              <div>
                <h1 class="text-2xl font-bold">{{ user.name }}</h1>
                <p class="text-indigo-100">{{ user.title || $t('cvSwap.profile.noTitle') }}</p>
              </div>
            </div>
            <button 
              @click="editMode = !editMode" 
              class="mt-4 md:mt-0 px-4 py-2 bg-white text-indigo-600 rounded-md hover:bg-indigo-50 transition-colors flex items-center"
            >
              {{ editMode ? $t('cvSwap.actions.cancel') : $t('cvSwap.actions.edit') }}
            </button>
          </div>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
          <!-- About Section -->
          <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ $t('cvSwap.profile.about') }}</h2>
            <div v-if="!editMode" class="text-gray-600 dark:text-gray-300">
              <p v-if="user.bio">{{ user.bio }}</p>
              <p v-else class="text-gray-400 italic">{{ $t('cvSwap.profile.noBio') }}</p>
            </div>
            <textarea 
              v-else
              v-model="editableUser.bio" 
              class="w-full p-3 border rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              rows="4"
              :placeholder="$t('cvSwap.profile.bioPlaceholder')"
            ></textarea>
          </div>

          <!-- Skills Section -->
          <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ $t('cvSwap.profile.skills') }}</h2>
            <div v-if="!editMode" class="flex flex-wrap gap-2">
              <span 
                v-for="(skill, index) in user.skills" 
                :key="index"
                class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full dark:bg-indigo-900 dark:text-indigo-200"
              >
                {{ skill }}
              </span>
            </div>
            <div v-else>
              <div class="flex flex-wrap gap-2 mb-2">
                <span 
                  v-for="(skill, index) in editableUser.skills" 
                  :key="index"
                  class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full dark:bg-indigo-900 dark:text-indigo-200"
                >
                  {{ skill }}
                  <button 
                    @click="removeSkill(index)"
                    class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-indigo-600 text-white hover:bg-indigo-700"
                  >
                    ×
                  </button>
                </span>
              </div>
              <div class="flex gap-2">
                <input 
                  v-model="newSkill" 
                  type="text" 
                  class="flex-1 p-2 border rounded-md"
                  :placeholder="$t('cvSwap.profile.addSkill')"
                  @keyup.enter="addSkill"
                >
                <button 
                  @click="addSkill"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                  {{ $t('cvSwap.actions.add') }}
                </button>
              </div>
            </div>
          </div>

          <!-- Save/Cancel Buttons -->
          <div v-if="editMode" class="flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button 
              @click="cancelEdit"
              class="px-4 py-2 border border-gray-300 rounded-md"
            >
              {{ $t('cvSwap.actions.cancel') }}
            </button>
            <button 
              @click="saveProfile"
              class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
              :disabled="isSaving"
            >
              {{ isSaving ? $t('cvSwap.actions.saving') : $t('cvSwap.actions.save') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue';
// import { useI18n } from 'vue-i18n';

export default {
  name: 'ProfileView',
  
  setup() {
    // const { t } = useI18n();
    const editMode = ref(false);
    const isSaving = ref(false);
    const newSkill = ref('');
    
    // Mock user data
    const user = reactive({
      name: 'Alex Johnson',
      title: 'Senior Software Engineer',
      bio: 'Passionate software engineer with 8+ years of experience.',
      skills: ['Vue.js', 'JavaScript', 'Node.js', 'TypeScript']
    });
    
    const editableUser = reactive({ ...user });
    
    const addSkill = () => {
      if (newSkill.value.trim() && !editableUser.skills.includes(newSkill.value.trim())) {
        editableUser.skills.push(newSkill.value.trim());
        newSkill.value = '';
      }
    };
    
    const removeSkill = (index) => {
      editableUser.skills.splice(index, 1);
    };
    
    const saveProfile = () => {
      isSaving.value = true;
      // Simulate API call
      setTimeout(() => {
        Object.assign(user, editableUser);
        editMode.value = false;
        isSaving.value = false;
      }, 1000);
    };
    
    const cancelEdit = () => {
      Object.assign(editableUser, user);
      editMode.value = false;
    };
    
    return {
      user,
      editableUser,
      editMode,
      isSaving,
      newSkill,
      addSkill,
      removeSkill,
      saveProfile,
      cancelEdit
    };
  }
};
</script>
