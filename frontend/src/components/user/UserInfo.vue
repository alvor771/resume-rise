<template>
  <div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
      <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ $t('user.profile.personal_info') }}
          </h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $t('user.profile.personal_info_description') }}
          </p>
        </div>
      </div>
      <div class="mt-5 md:mt-0 md:col-span-2">
        <form @submit.prevent="saveProfile">
          <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                  <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.first_name') }}
                  </label>
                  <input
                    type="text"
                    id="first_name"
                    v-model="user.firstName"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>

                <div class="col-span-6 sm:col-span-3">
                  <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.last_name') }}
                  </label>
                  <input
                    type="text"
                    id="last_name"
                    v-model="user.lastName"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>

                <div class="col-span-6 sm:col-span-4">
                  <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.email') }}
                  </label>
                  <input
                    type="email"
                    id="email"
                    v-model="user.email"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>

                <div class="col-span-6 sm:col-span-4">
                  <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.phone') }}
                  </label>
                  <input
                    type="tel"
                    id="phone"
                    v-model="user.phone"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>
              </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6">
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                {{ $t('common.save') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Change Password -->
    <div class="hidden sm:block" aria-hidden="true">
      <div class="py-5">
        <div class="border-t border-gray-200 dark:border-gray-600"></div>
      </div>
    </div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
      <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ $t('user.profile.change_password') }}
          </h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $t('user.profile.change_password_description') }}
          </p>
        </div>
      </div>
      <div class="mt-5 md:mt-0 md:col-span-2">
        <form @submit.prevent="changePassword">
          <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
                  <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.current_password') }}
                  </label>
                  <input
                    type="password"
                    id="current_password"
                    v-model="password.current"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>

                <div class="col-span-6">
                  <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.new_password') }}
                  </label>
                  <input
                    type="password"
                    id="new_password"
                    v-model="password.new"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>

                <div class="col-span-6">
                  <label for="confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $t('user.profile.confirm_password') }}
                  </label>
                  <input
                    type="password"
                    id="confirm_password"
                    v-model="password.confirm"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                  />
                </div>
              </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6">
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                {{ $t('user.profile.update_password') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'UserInfo',
  setup() {
    // Mock user data - in a real app, this would come from a store or API
    const user = ref({
      firstName: 'John',
      lastName: 'Doe',
      email: 'john.doe@example.com',
      phone: '+1 (555) 123-4567',
      avatar: '/path/to/avatar.jpg'
    });

    const password = ref({
      current: '',
      new: '',
      confirm: ''
    });

    const saveProfile = () => {
      console.log('Saving profile:', user.value);
      // In a real app, this would make an API call to save the profile
    };

    const changePassword = () => {
      console.log('Changing password');
      // In a real app, this would validate and make an API call to change the password
      if (password.value.new !== password.value.confirm) {
        alert('New passwords do not match');
        return;
      }
      // Reset form
      password.value = { current: '', new: '', confirm: '' };
    };

    return {
      user,
      password,
      saveProfile,
      changePassword
    };
  }
};
</script>
