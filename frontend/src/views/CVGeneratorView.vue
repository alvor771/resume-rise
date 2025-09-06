<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
        {{ $t('cvGenerator.title') }}
      </h1>
      
      <!-- CV Generator Form -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Personal Information -->
          <div class="col-span-2">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
              {{ $t('cvGenerator.sections.personalInfo') }}
            </h2>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  {{ $t('cvGenerator.form.fullName') }}
                </label>
                <input 
                  type="text" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  :placeholder="$t('cvGenerator.form.fullNamePlaceholder')"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  {{ $t('cvGenerator.form.email') }}
                </label>
                <input 
                  type="email" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  :placeholder="$t('cvGenerator.form.emailPlaceholder')"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  {{ $t('cvGenerator.form.phone') }}
                </label>
                <input 
                  type="tel" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  :placeholder="$t('cvGenerator.form.phonePlaceholder')"
                >
              </div>
            </div>
          </div>

          <!-- Work Experience -->
          <div class="col-span-2">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $t('cvGenerator.sections.workExperience') }}
              </h2>
              <button 
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                @click="addWorkExperience"
              >
                + {{ $t('cvGenerator.buttons.addExperience') }}
              </button>
            </div>
            
            <div v-for="(exp, index) in workExperience" :key="index" class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.jobTitle') }}
                  </label>
                  <input 
                    v-model="exp.jobTitle"
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.company') }}
                  </label>
                  <input 
                    v-model="exp.company"
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.startDate') }}
                  </label>
                  <input 
                    v-model="exp.startDate"
                    type="month" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.endDate') }}
                  </label>
                  <input 
                    v-model="exp.endDate"
                    type="month" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  >
                </div>
              </div>
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  {{ $t('cvGenerator.form.responsibilities') }}
                </label>
                <textarea 
                  v-model="exp.responsibilities"
                  rows="3" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                ></textarea>
              </div>
              <div class="mt-2 flex justify-end">
                <button 
                  @click="removeWorkExperience(index)"
                  class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                >
                  {{ $t('cvGenerator.buttons.remove') }}
                </button>
              </div>
            </div>
          </div>

          <!-- Education -->
          <div class="col-span-2">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
              {{ $t('cvGenerator.sections.education') }}
            </h2>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.degree') }}
                  </label>
                  <input 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    :placeholder="$t('cvGenerator.form.degreePlaceholder')"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.institution') }}
                  </label>
                  <input 
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    :placeholder="$t('cvGenerator.form.institutionPlaceholder')"
                  >
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('cvGenerator.form.graduationYear') }}
                  </label>
                  <input 
                    type="number" 
                    min="1900" 
                    :max="new Date().getFullYear()" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Skills -->
          <div class="col-span-2">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
              {{ $t('cvGenerator.sections.skills') }}
            </h2>
            <div>
              <div class="flex">
                <input 
                  v-model="newSkill"
                  type="text" 
                  class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                  :placeholder="$t('cvGenerator.form.skillsPlaceholder')"
                  @keyup.enter="addSkill"
                >
                <button 
                  @click="addSkill"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  {{ $t('cvGenerator.buttons.add') }}
                </button>
              </div>
              <div class="mt-3 flex flex-wrap gap-2">
                <span 
                  v-for="(skill, index) in skills" 
                  :key="index"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
                >
                  {{ skill }}
                  <button 
                    @click="removeSkill(index)"
                    class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-600 hover:bg-indigo-200 dark:text-indigo-300 dark:hover:bg-indigo-800"
                  >
                    <span class="sr-only">Remove skill</span>
                    <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                      <path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 01.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-8 flex justify-end space-x-3">
          <button 
            type="button" 
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ $t('cvGenerator.buttons.preview') }}
          </button>
          <button 
            type="button" 
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ $t('cvGenerator.buttons.download') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'CVGeneratorView',
  setup() {
    const workExperience = ref([
      { 
        jobTitle: '',
        company: '',
        startDate: '',
        endDate: '',
        responsibilities: ''
      }
    ]);
    
    const skills = ref([]);
    const newSkill = ref('');

    const addWorkExperience = () => {
      workExperience.value.push({
        jobTitle: '',
        company: '',
        startDate: '',
        endDate: '',
        responsibilities: ''
      });
    };

    const removeWorkExperience = (index) => {
      if (workExperience.value.length > 1) {
        workExperience.value.splice(index, 1);
      }
    };

    const addSkill = () => {
      if (newSkill.value.trim() && !skills.value.includes(newSkill.value.trim())) {
        skills.value.push(newSkill.value.trim());
        newSkill.value = '';
      }
    };

    const removeSkill = (index) => {
      skills.value.splice(index, 1);
    };

    return {
      workExperience,
      skills,
      newSkill,
      addWorkExperience,
      removeWorkExperience,
      addSkill,
      removeSkill
    };
  }
};
</script>
