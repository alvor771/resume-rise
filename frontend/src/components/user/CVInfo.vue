<template>
  <div class="space-y-6">
    <!-- Personal Information -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('user.cv.personal_info') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('user.cv.personal_info_description') }}
          </p>
        </div>
        <button
          @click="editSection('personal')"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          {{ isEditing.personal ? $t('common.cancel') : $t('common.edit') }}
        </button>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
              {{ $t('user.cv.full_name') }}
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
              <template v-if="!isEditing.personal">
                {{ cv.personalInfo.fullName }}
              </template>
              <input
                v-else
                v-model="cv.personalInfo.fullName"
                type="text"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
              />
            </dd>
          </div>
          <!-- Add more personal info fields similarly -->
        </dl>
      </div>
    </div>

    <!-- Work Experience -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ $t('user.cv.work_experience') }}
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ $t('user.cv.work_experience_description') }}
          </p>
        </div>
        <button
          @click="addExperience"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          {{ $t('common.add') }}
        </button>
      </div>
      <div class="border-t border-gray-200 dark:border-gray-700">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
          <li v-for="(exp, index) in cv.experience" :key="index" class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-md font-medium text-indigo-600 dark:text-indigo-400">
                  {{ exp.position }}
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ exp.company }} • {{ exp.startDate }} - {{ exp.endDate || $t('user.cv.present') }}
                </p>
              </div>
              <div class="flex space-x-2">
                <button
                  @click="editExperience(index)"
                  class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                >
                  {{ $t('common.edit') }}
                </button>
                <button
                  @click="removeExperience(index)"
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                >
                  {{ $t('common.delete') }}
                </button>
              </div>
            </div>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
              {{ exp.description }}
            </p>
          </li>
        </ul>
      </div>
    </div>

    <!-- Education -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
          {{ $t('user.cv.education') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
          {{ $t('user.cv.education_description') }}
        </p>
      </div>
      <!-- Similar structure as work experience -->
    </div>

    <!-- Skills -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
          {{ $t('user.cv.skills') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
          {{ $t('user.cv.skills_description') }}
        </p>
      </div>
      <div class="px-4 py-5 sm:p-6">
        <div class="flex flex-wrap gap-2">
          <span
            v-for="(skill, index) in cv.skills"
            :key="index"
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
          >
            {{ skill }}
            <button
              @click="removeSkill(index)"
              class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 dark:hover:bg-indigo-800 dark:hover:text-indigo-300"
            >
              <span class="sr-only">Remove skill</span>
              <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                <path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 01.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </span>
          <div class="flex">
            <input
              v-model="newSkill"
              type="text"
              :placeholder="$t('user.cv.add_skill')"
              @keyup.enter="addSkill"
              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
            />
            <button
              @click="addSkill"
              class="ml-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {{ $t('common.add') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'CVInfo',
  setup() {
    // Mock CV data - in a real app, this would come from a store or API
    const cv = ref({
      personalInfo: {
        fullName: 'John Doe',
        email: 'john.doe@example.com',
        phone: '+1 (555) 123-4567',
        address: '123 Main St, Anytown, USA',
        summary: 'Experienced software developer with 5+ years of experience...'
      },
      experience: [
        {
          position: 'Senior Developer',
          company: 'Tech Corp',
          startDate: '2020',
          endDate: 'Present',
          description: 'Leading a team of developers to build amazing products.'
        },
        {
          position: 'Software Engineer',
          company: 'Web Solutions',
          startDate: '2018',
          endDate: '2020',
          description: 'Developed and maintained web applications.'
        }
      ],
      education: [
        {
          degree: 'B.Sc. Computer Science',
          institution: 'State University',
          year: '2018'
        }
      ],
      skills: ['JavaScript', 'Vue.js', 'React', 'Node.js', 'Python', 'Docker']
    });

    const newSkill = ref('');
    const isEditing = ref({
      personal: false,
      experience: null
    });

    const editSection = (section) => {
      isEditing.value[section] = !isEditing.value[section];
    };

    const addExperience = () => {
      cv.value.experience.unshift({
        position: '',
        company: '',
        startDate: '',
        endDate: '',
        description: ''
      });
      isEditing.value.experience = 0; // Edit the newly added experience
    };

    const editExperience = (index) => {
      isEditing.value.experience = isEditing.value.experience === index ? null : index;
    };

    const removeExperience = (index) => {
      if (confirm('Are you sure you want to remove this experience?')) {
        cv.value.experience.splice(index, 1);
      }
    };

    const addSkill = () => {
      if (newSkill.value.trim() && !cv.value.skills.includes(newSkill.value.trim())) {
        cv.value.skills.push(newSkill.value.trim());
        newSkill.value = '';
      }
    };

    const removeSkill = (index) => {
      cv.value.skills.splice(index, 1);
    };

    return {
      cv,
      newSkill,
      isEditing,
      editSection,
      addExperience,
      editExperience,
      removeExperience,
      addSkill,
      removeSkill
    };
  }
};
</script>
