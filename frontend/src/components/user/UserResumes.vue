<template>
  <div class="space-y-6">
    <!-- Create New Resume Button -->
    <div class="flex justify-end">
      <router-link
        to="/resume-builder"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
        {{ $t('user.resumes.create_new') }}
      </router-link>
    </div>

    <!-- Resumes List -->
    <div v-if="resumes.length > 0" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
      <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
        <li v-for="resume in resumes" :key="resume.id">
          <div class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <DocumentTextIcon class="h-10 w-10 text-indigo-600 dark:text-indigo-400 flex-shrink-0" />
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ resume.title }}
                  </h3>
                  <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                      <CalendarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                      {{ $t('user.resumes.last_updated') }}: {{ formatDate(resume.updatedAt) }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                      <EyeIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                      {{ resume.views }} {{ $t('user.resumes.views') }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="ml-4 flex-shrink-0 flex space-x-2">
                <span
                  :class="[
                    resume.status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                  ]"
                >
                  {{ resume.status === 'published' ? $t('common.published') : $t('common.draft') }}
                </span>
                <span
                  v-if="resume.isPublic"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                >
                  {{ $t('common.public') }}
                </span>
              </div>
            </div>
            <div class="mt-4 flex justify-between items-center">
              <div class="flex space-x-3">
                <button
                  @click="previewResume(resume.id)"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-900 dark:text-indigo-200 dark:hover:bg-indigo-800"
                >
                  <EyeIcon class="h-3 w-3 mr-1" />
                  {{ $t('common.preview') }}
                </button>
                <button
                  @click="editResume(resume.id)"
                  class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600"
                >
                  <PencilIcon class="h-3 w-3 mr-1" />
                  {{ $t('common.edit') }}
                </button>
                <button
                  @click="duplicateResume(resume.id)"
                  class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600"
                >
                  <DocumentDuplicateIcon class="h-3 w-3 mr-1" />
                  {{ $t('common.duplicate') }}
                </button>
              </div>
              <button
                @click="confirmDelete(resume.id)"
                class="inline-flex items-center p-1.5 border border-transparent rounded-full text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:text-red-400 dark:hover:bg-red-900"
              >
                <TrashIcon class="h-4 w-4" />
                <span class="sr-only">{{ $t('common.delete') }}</span>
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center">
      <DocumentTextIcon class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $t('user.resumes.no_resumes') }}
      </h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        {{ $t('user.resumes.get_started') }}
      </p>
      <div class="mt-6">
        <router-link
          to="/resume-builder"
          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
          {{ $t('user.resumes.create_resume') }}
        </router-link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot as="template" :show="showDeleteModal">
      <TransitionRoot as="template" :show="isDeleteModalOpen">
        <HeadlessDialog as="div" class="relative z-50" @close="isDeleteModalOpen = false">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <HeadlessDialogOverlay class="fixed inset-0 bg-black bg-opacity-30 transition-opacity" />
          </TransitionChild>

          <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
              <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to="opacity-100 translate-y-0 sm:scale-100"
                leave="ease-in duration-200"
                leave-from="opacity-100 translate-y-0 sm:scale-100"
                leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              >
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                  <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                      <ExclamationIcon class="h-6 w-6 text-red-600" aria-hidden="true" />
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                      <HeadlessDialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        {{ $t('common.are_you_sure') }}
                      </HeadlessDialogTitle>
                      <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                          {{ $t('user.resumes.delete_confirm') }}
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button
                      type="button"
                      class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                      @click="deleteResume"
                    >
                      {{ $t('common.delete') }}
                    </button>
                    <button
                      type="button"
                      class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                      @click="isDeleteModalOpen = false"
                    >
                      {{ $t('common.cancel') }}
                    </button>
                  </div>
                </div>
              </TransitionChild>
            </div>
          </div>
        </HeadlessDialog>
      </TransitionRoot>
    </TransitionRoot>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { format } from 'date-fns';
import { 
  DocumentTextIcon,
  EyeIcon,
  PencilIcon,
  DocumentDuplicateIcon,
  TrashIcon,
  PlusIcon,
  CalendarIcon,
  ExclamationIcon 
} from '@heroicons/vue/24/outline';
import { TransitionRoot, TransitionChild } from '@headlessui/vue';

export default {
  name: 'UserResumes',
  components: {
    DocumentTextIcon,
    EyeIcon,
    PencilIcon,
    DocumentDuplicateIcon,
    TrashIcon,
    PlusIcon,
    ExclamationIcon,
    CalendarIcon,
    // Dialog components are used via <HeadlessDialog> in template
    TransitionRoot,
    TransitionChild
  },
  setup() {
    const router = useRouter();
    const { t } = useI18n();
    
    // Mock data - in a real app, this would come from a store or API
    const resumes = ref([
      {
        id: '1',
        title: 'Senior Software Engineer',
        updatedAt: '2023-05-15T14:30:00Z',
        status: 'published',
        isPublic: true,
        views: 42,
        template: 'professional'
      },
      {
        id: '2',
        title: 'Frontend Developer',
        updatedAt: '2023-06-20T09:15:00Z',
        status: 'draft',
        isPublic: false,
        views: 0,
        template: 'modern'
      },
      {
        id: '3',
        title: 'Full Stack Developer',
        updatedAt: '2023-04-10T16:45:00Z',
        status: 'published',
        isPublic: true,
        views: 28,
        template: 'creative'
      }
    ]);

    const showDeleteModal = ref(false);
    const resumeToDelete = ref(null);

    const formatDate = (dateString) => {
      return format(new Date(dateString), 'MMM d, yyyy');
    };

    const previewResume = (id) => {
      // In a real app, this would open a preview modal or redirect to a preview page
      console.log('Preview resume:', id);
    };

    const editResume = (id) => {
      router.push({ name: 'ResumeBuilder', params: { id } });
    };

    const duplicateResume = (id) => {
      // In a real app, this would make an API call to duplicate the resume
      const resume = resumes.value.find(r => r.id === id);
      if (resume) {
        const newResume = {
          ...resume,
          id: Math.random().toString(36).substr(2, 9),
          title: `${resume.title} (${t('common.copy')})`,
          status: 'draft',
          isPublic: false,
          views: 0,
          updatedAt: new Date().toISOString()
        };
        resumes.value.unshift(newResume);
      }
    };

    const confirmDelete = (id) => {
      resumeToDelete.value = id;
      showDeleteModal.value = true;
    };

    const deleteResume = () => {
      if (resumeToDelete.value) {
        const index = resumes.value.findIndex(r => r.id === resumeToDelete.value);
        if (index !== -1) {
          resumes.value.splice(index, 1);
        }
        showDeleteModal.value = false;
        resumeToDelete.value = null;
      }
    };

    return {
      resumes,
      showDeleteModal,
      formatDate,
      previewResume,
      editResume,
      duplicateResume,
      confirmDelete,
      deleteResume
    };
  }
};
</script>
