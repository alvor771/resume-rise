<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
      <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
          {{ $t('admin.api_endpoints.title') }}
        </h1>
        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
          {{ $t('admin.api_endpoints.description') }}
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <div class="relative rounded-md shadow-sm">
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
          </div>
          <input
            v-model="searchQuery"
            type="text"
            class="block w-full rounded-md border-0 py-1.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:placeholder-gray-400"
            :placeholder="$t('admin.api_endpoints.search_placeholder')"
          />
        </div>
      </div>
    </div>
    
    <div class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">
                    {{ $t('admin.api_endpoints.method') }}
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $t('admin.api_endpoints.endpoint') }}
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $t('admin.api_endpoints.description') }}
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $t('admin.api_endpoints.auth') }}
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <tr 
                  v-for="(endpoint, index) in filteredEndpoints" 
                  :key="index"
                  class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                  @click="openEndpointDetails(endpoint)"
                >
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-mono sm:pl-6">
                    <span :class="getMethodBadgeClass(endpoint.method)">
                      {{ endpoint.method }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-200 font-mono">
                    {{ endpoint.path }}
                  </td>
                  <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ endpoint.description || endpoint.name || endpoint.action || '' }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                    <span v-if="endpoint.auth" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                      {{ $t('common.required') }}
                    </span>
                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                      {{ $t('common.none') }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
            <div v-if="filteredEndpoints.length === 0" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
              {{ $t('admin.api_endpoints.no_results') }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Endpoint Details Modal -->
    <TransitionRoot as="template" :show="isModalOpen">
      <Dialog as="div" class="relative z-10" @close="closeModal">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
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
              <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl sm:p-6 dark:bg-gray-800">
                <div>
                  <div class="flex items-center justify-between">
                    <div>
                      <div class="flex items-center">
                        <span :class="[getMethodBadgeClass(selectedEndpoint?.method), 'text-sm font-medium mr-3']">
                          {{ selectedEndpoint?.method }}
                        </span>
                        <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                          {{ selectedEndpoint?.path }}
                        </DialogTitle>
                      </div>
                      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ selectedEndpoint ? $t(`admin.api_endpoints.${selectedEndpoint.key}.description`) : '' }}
                      </p>
                    </div>
                    <button
                      type="button"
                      class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none dark:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-200"
                      @click="closeModal"
                    >
                      <span class="sr-only">Close</span>
                      <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                  </div>

                  <div class="mt-6">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                      <nav class="-mb-px flex space-x-8">
                        <button
                          v-for="tab in tabs"
                          :key="tab.name"
                          @click="currentTab = tab.name"
                          :class="[
                            currentTab === tab.name
                              ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-300'
                              : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-500 dark:hover:text-gray-200',
                            'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                          ]"
                        >
                          {{ tab.name }}
                        </button>
                      </nav>
                    </div>

                    <div class="mt-4">
                      <!-- Request Parameters -->
                      <div v-if="currentTab === 'Parameters'" class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-200">
                          {{ $t('admin.api_endpoints.parameters') }}
                        </h4>
                        <div v-if="selectedEndpoint?.parameters?.length" class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                          <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                              <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">
                                  {{ $t('admin.api_endpoints.parameter') }}
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                  {{ $t('admin.api_endpoints.type') }}
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                  {{ $t('admin.api_endpoints.required') }}
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                  {{ $t('admin.api_endpoints.description') }}
                                </th>
                              </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                              <tr v-for="(param, index) in selectedEndpoint.parameters" :key="index">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-mono text-gray-900 dark:text-gray-200 sm:pl-6">
                                  {{ param.name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ param.type }}
                                  </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                  <span v-if="param.required" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $t('common.yes') }}
                                  </span>
                                  <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $t('common.no') }}
                                  </span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                  {{ param.description }}
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                          {{ $t('admin.api_endpoints.no_parameters') }}
                        </p>
                      </div>

                      <!-- Response -->
                      <div v-else-if="currentTab === 'Response'" class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                          {{ $t('admin.api_endpoints.response') }}
                        </h4>
                        <div v-if="selectedEndpoint?.response" class="overflow-hidden shadow sm:rounded-lg">
                          <div class="px-4 py-5 sm:p-6 bg-gray-50 dark:bg-gray-700">
                            <pre class="text-xs text-gray-800 dark:text-gray-200 overflow-x-auto">{{ formatJson(selectedEndpoint.response) }}</pre>
                          </div>
                        </div>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                          {{ $t('admin.api_endpoints.no_response') }}
                        </p>
                      </div>

                      <!-- Example -->
                      <div v-else-if="currentTab === 'Example'" class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                          {{ $t('admin.api_endpoints.example') }}
                        </h4>
                        <div class="space-y-2">
                          <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                              {{ $t('admin.api_endpoints.curl') }}
                            </label>
                            <div class="relative">
                              <pre class="text-xs bg-gray-100 dark:bg-gray-700 p-3 rounded-md overflow-x-auto">
                                <code>{{ getCurlExample(selectedEndpoint) }}</code>
                              </pre>
                              <button
                                @click="copyToClipboard(getCurlExample(selectedEndpoint))"
                                class="absolute top-2 right-2 p-1 rounded-md bg-white dark:bg-gray-600 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                                :title="$t('common.copy')"
                              >
                                <DocumentDuplicateIcon class="h-4 w-4" />
                              </button>
                            </div>
                          </div>
                          
                          <div v-if="selectedEndpoint?.method === 'POST' || selectedEndpoint?.method === 'PUT' || selectedEndpoint?.method === 'PATCH'">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-3 mb-1">
                              {{ $t('admin.api_endpoints.request_body') }}
                            </label>
                            <div class="relative">
                              <pre class="text-xs bg-gray-100 dark:bg-gray-700 p-3 rounded-md overflow-x-auto">
                                <code>{{ formatJson(selectedEndpoint.requestBody) }}</code>
                              </pre>
                              <button
                                v-if="selectedEndpoint.requestBody"
                                @click="copyToClipboard(JSON.stringify(selectedEndpoint.requestBody, null, 2))"
                                class="absolute top-2 right-2 p-1 rounded-md bg-white dark:bg-gray-600 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                                :title="$t('common.copy')"
                              >
                                <DocumentDuplicateIcon class="h-4 w-4" />
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { XMarkIcon, MagnifyingGlassIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline';

const searchQuery = ref('');
const isModalOpen = ref(false);
const selectedEndpoint = ref(null);
const currentTab = ref('Parameters');

const tabs = [
  { name: 'Parameters' },
  { name: 'Response' },
  { name: 'Example' },
];

const endpoints = ref([
  {
    key: 'login',
    method: 'POST',
    path: '/api/auth/login',
    auth: false,
    parameters: [
      { name: 'email', type: 'string', required: true, description: 'User\'s email address' },
      { name: 'password', type: 'string', required: true, description: 'User\'s password' },
    ],
    requestBody: {
      email: 'user@example.com',
      password: 'your_password_here',
    },
    response: {
      token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
      user: {
        id: 1,
        name: 'John Doe',
        email: 'user@example.com'
      }
    }
  },
  {
    key: 'register',
    method: 'POST',
    path: '/api/auth/register',
    auth: false,
    parameters: [
      { name: 'name', type: 'string', required: true, description: 'User\'s full name' },
      { name: 'email', type: 'string', required: true, description: 'User\'s email address' },
      { name: 'password', type: 'string', required: true, description: 'User\'s password' },
      { name: 'password_confirmation', type: 'string', required: true, description: 'Password confirmation' },
    ],
    requestBody: {
      name: 'John Doe',
      email: 'user@example.com',
      password: 'your_password_here',
      password_confirmation: 'your_password_here',
    },
    response: {
      message: 'User registered successfully',
      user: {
        id: 1,
        name: 'John Doe',
        email: 'user@example.com',
        created_at: '2023-01-01T00:00:00.000000Z',
        updated_at: '2023-01-01T00:00:00.000000Z'
      }
    }
  },
  {
    key: 'logout',
    method: 'POST',
    path: '/api/auth/logout',
    auth: true,
    parameters: [],
    response: {
      message: 'Successfully logged out'
    }
  },
  {
    key: 'user_profile',
    method: 'GET',
    path: '/api/user',
    auth: true,
    parameters: [],
    response: {
      id: 1,
      name: 'John Doe',
      email: 'user@example.com',
      created_at: '2023-01-01T00:00:00.000000Z',
      updated_at: '2023-01-01T00:00:00.000000Z'
    }
  },
  {
    key: 'update_profile',
    method: 'PUT',
    path: '/api/user/profile',
    auth: true,
    parameters: [
      { name: 'name', type: 'string', required: false, description: 'User\'s full name' },
      { name: 'email', type: 'string', required: false, description: 'User\'s email address' },
      { name: 'current_password', type: 'string', required: false, description: 'Current password for verification' },
      { name: 'password', type: 'string', required: false, description: 'New password' },
      { name: 'password_confirmation', type: 'string', required: false, description: 'New password confirmation' },
    ],
    requestBody: {
      name: 'John Updated',
      email: 'updated@example.com',
      current_password: 'current_password_here',
      password: 'new_password_here',
      password_confirmation: 'new_password_here',
    },
    response: {
      message: 'Profile updated successfully',
      user: {
        id: 1,
        name: 'John Updated',
        email: 'updated@example.com',
        created_at: '2023-01-01T00:00:00.000000Z',
        updated_at: '2023-01-01T00:00:00.000000Z'
      }
    }
  },
  {
    key: 'list_resumes',
    method: 'GET',
    path: '/api/resumes',
    auth: true,
    parameters: [
      { name: 'page', type: 'integer', required: false, description: 'Page number' },
      { name: 'per_page', type: 'integer', required: false, description: 'Items per page' },
    ],
    response: {
      data: [
        {
          id: 1,
          user_id: 1,
          title: 'Senior Web Developer',
          created_at: '2023-01-01T00:00:00.000000Z',
          updated_at: '2023-01-01T00:00:00.000000Z'
        }
      ],
      links: {
        first: 'http://example.com/api/resumes?page=1',
        last: 'http://example.com/api/resumes?page=1',
        prev: null,
        next: null
      },
      meta: {
        current_page: 1,
        from: 1,
        last_page: 1,
        path: 'http://example.com/api/resumes',
        per_page: 15,
        to: 1,
        total: 1
      }
    }
  },
  {
    key: 'create_resume',
    method: 'POST',
    path: '/api/resumes',
    auth: true,
    parameters: [
      { name: 'title', type: 'string', required: true, description: 'Resume title' },
      { name: 'personal_info', type: 'object', required: true, description: 'Personal information' },
      { name: 'sections', type: 'array', required: true, description: 'Resume sections' },
    ],
    requestBody: {
      title: 'Senior Web Developer',
      personal_info: {
        name: 'John Doe',
        email: 'john@example.com',
        phone: '+1234567890',
        location: 'New York, USA',
        website: 'https://johndoe.com',
        summary: 'Experienced web developer with 5+ years of experience...'
      },
      sections: [
        {
          type: 'experience',
          title: 'Work Experience',
          items: [
            {
              title: 'Senior Web Developer',
              company: 'Tech Corp',
              location: 'New York, USA',
              start_date: '2020-01-01',
              end_date: '2023-12-31',
              current: true,
              description: 'Led a team of developers in building web applications...'
            }
          ]
        },
        {
          type: 'education',
          title: 'Education',
          items: [
            {
              degree: 'Master of Computer Science',
              institution: 'Tech University',
              location: 'Boston, USA',
              start_date: '2015-09-01',
              end_date: '2018-05-30',
              description: 'Specialized in Web Technologies'
            }
          ]
        }
      ]
    },
    response: {
      message: 'Resume created successfully',
      resume: {
        id: 1,
        user_id: 1,
        title: 'Senior Web Developer',
        created_at: '2023-01-01T00:00:00.000000Z',
        updated_at: '2023-01-01T00:00:00.000000Z'
      }
    }
  },
  {
    key: 'get_resume',
    method: 'GET',
    path: '/api/resumes/{id}',
    auth: true,
    parameters: [
      { name: 'id', type: 'integer', required: true, description: 'Resume ID', in: 'path' },
    ],
    response: {
      id: 1,
      user_id: 1,
      title: 'Senior Web Developer',
      personal_info: {
        name: 'John Doe',
        email: 'john@example.com',
        phone: '+1234567890',
        location: 'New York, USA',
        website: 'https://johndoe.com',
        summary: 'Experienced web developer with 5+ years of experience...'
      },
      sections: [
        {
          type: 'experience',
          title: 'Work Experience',
          items: [
            {
              title: 'Senior Web Developer',
              company: 'Tech Corp',
              location: 'New York, USA',
              start_date: '2020-01-01',
              end_date: '2023-12-31',
              current: true,
              description: 'Led a team of developers in building web applications...'
            }
          ]
        },
        {
          type: 'education',
          title: 'Education',
          items: [
            {
              degree: 'Master of Computer Science',
              institution: 'Tech University',
              location: 'Boston, USA',
              start_date: '2015-09-01',
              end_date: '2018-05-30',
              description: 'Specialized in Web Technologies'
            }
          ]
        }
      ],
      created_at: '2023-01-01T00:00:00.000000Z',
      updated_at: '2023-01-01T00:00:00.000000Z'
    }
  },
  {
    key: 'update_resume',
    method: 'PUT',
    path: '/api/resumes/{id}',
    auth: true,
    parameters: [
      { name: 'id', type: 'integer', required: true, description: 'Resume ID', in: 'path' },
      { name: 'title', type: 'string', required: false, description: 'Resume title' },
      { name: 'personal_info', type: 'object', required: false, description: 'Personal information' },
      { name: 'sections', type: 'array', required: false, description: 'Resume sections' },
    ],
    requestBody: {
      title: 'Senior Web Developer (Updated)',
      personal_info: {
        name: 'John Doe',
        email: 'john.doe@example.com',
        phone: '+1234567890',
        location: 'New York, USA',
        website: 'https://johndoe.com',
        summary: 'Experienced web developer with 5+ years of experience...'
      }
    },
    response: {
      message: 'Resume updated successfully',
      resume: {
        id: 1,
        user_id: 1,
        title: 'Senior Web Developer (Updated)',
        created_at: '2023-01-01T00:00:00.000000Z',
        updated_at: '2023-01-02T00:00:00.000000Z'
      }
    }
  },
  {
    key: 'delete_resume',
    method: 'DELETE',
    path: '/api/resumes/{id}',
    auth: true,
    parameters: [
      { name: 'id', type: 'integer', required: true, description: 'Resume ID', in: 'path' },
    ],
    response: {
      message: 'Resume deleted successfully'
    }
  },
  {
    key: 'list_templates',
    method: 'GET',
    path: '/api/templates',
    auth: true,
    parameters: [
      { name: 'page', type: 'integer', required: false, description: 'Page number' },
      { name: 'per_page', type: 'integer', required: false, description: 'Items per page' },
    ],
    response: {
      data: [
        {
          id: 1,
          name: 'Professional',
          description: 'Clean and professional resume template',
          thumbnail: 'https://placehold.co/300x400?text=Professional',
          created_at: '2023-01-01T00:00:00.000000Z',
          updated_at: '2023-01-01T00:00:00.000000Z'
        }
      ],
      links: {
        first: 'http://example.com/api/templates?page=1',
        last: 'http://example.com/api/templates?page=1',
        prev: null,
        next: null
      },
      meta: {
        current_page: 1,
        from: 1,
        last_page: 1,
        path: 'http://example.com/api/templates',
        per_page: 15,
        to: 1,
        total: 1
      }
    }
  },
  {
    key: 'get_template',
    method: 'GET',
    path: '/api/templates/{id}',
    auth: true,
    parameters: [
      { name: 'id', type: 'integer', required: true, description: 'Template ID', in: 'path' },
    ],
    response: {
      id: 1,
      name: 'Professional',
      description: 'Clean and professional resume template',
      thumbnail: '/templates/professional.jpg',
      preview: 'https://placehold.co/800x1000?text=Professional+Preview',
      created_at: '2023-01-01T00:00:00.000000Z',
      updated_at: '2023-01-01T00:00:00.000000Z'
    }
  },
]);

const filteredEndpoints = computed(() => {
  if (!searchQuery.value) return endpoints.value;
  
  const query = searchQuery.value.toLowerCase();
  return endpoints.value.filter(endpoint => 
    endpoint.path.toLowerCase().includes(query) ||
    endpoint.method.toLowerCase().includes(query) ||
    endpoint.key.toLowerCase().includes(query) ||
    (endpoint.parameters && endpoint.parameters.some(param => 
      param.name.toLowerCase().includes(query) ||
      param.description.toLowerCase().includes(query)
    ))
  );
});

// Dynamically load implemented API routes from backend controller
const loadRoutes = async () => {
  try {
    const res = await fetch('/api/admin/routes', {
      headers: { 'Accept': 'application/json' },
      credentials: 'include',
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    const list = Array.isArray(json.data) ? json.data : [];
    endpoints.value = list.map(r => ({
      key: `${(r.method || 'GET')}_${r.path || ''}`
        .replace(/[^a-zA-Z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '')
        .toLowerCase(),
      method: r.method || 'GET',
      path: r.path || '',
      auth: !!r.auth,
      name: r.name || '',
      action: r.action || '',
      description: r.name || r.action || '',
      parameters: [],
      requestBody: null,
      response: null,
    }));
  } catch (e) {
    console.error('Failed to load routes', e);
  }
};

onMounted(loadRoutes);

const getMethodBadgeClass = (method) => {
  const classes = 'inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium';
  
  switch (method) {
    case 'GET':
      return `${classes} bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200`;
    case 'POST':
      return `${classes} bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200`;
    case 'PUT':
    case 'PATCH':
      return `${classes} bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200`;
    case 'DELETE':
      return `${classes} bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200`;
    default:
      return `${classes} bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200`;
  }
};

const openEndpointDetails = (endpoint) => {
  selectedEndpoint.value = endpoint;
  currentTab.value = 'Parameters';
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  setTimeout(() => {
    selectedEndpoint.value = null;
  }, 200);
};

const formatJson = (obj) => {
  if (!obj) return '';
  return JSON.stringify(obj, null, 2);
};

const getCurlExample = (endpoint) => {
  if (!endpoint) return '';
  
  let curl = `curl -X ${endpoint.method} \\
  '${window.location.origin}${endpoint.path.replace('{id}', '1')}'`;
  
  if (endpoint.auth) {
    curl += ' \\-H "Authorization: Bearer YOUR_AUTH_TOKEN"';
  }
  
  if (endpoint.method === 'POST' || endpoint.method === 'PUT' || endpoint.method === 'PATCH') {
    curl += ' \\-H "Content-Type: application/json" \\ -d \'\'\'' + JSON.stringify(endpoint.requestBody || {}, null, 2) + '\'\'\'';
  }
  
  return curl;
};

const copyToClipboard = async (text) => {
  try {
    await navigator.clipboard.writeText(text);
    // You can add a toast notification here if you have one
    console.log('Copied to clipboard');
  } catch (err) {
    console.error('Failed to copy text: ', err);
  }
};
</script>
