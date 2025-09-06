<template>
  <form @submit.prevent="handleSubmit">
    <div class="space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3
            class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
          >
            {{ $t("admin.users.user_information") }}
          </h3>
          <div class="mt-5 grid grid-cols-1 gap-6 sm:grid-cols-6">
            <!-- First Name -->
            <div class="sm:col-span-3">
              <BaseInput
                id="first_name"
                v-model="formData.firstName"
                :label="$t('auth.first_name') + ' *'"
                :disabled="isLoading"
                required
                :error="errors.firstName && errors.firstName[0]"
                :prefix-icon="IconUser"
              />
            </div>

            <!-- Last Name -->
            <div class="sm:col-span-3">
              <BaseInput
                id="last_name"
                v-model="formData.lastName"
                :label="$t('auth.last_name') + ' *'"
                :disabled="isLoading"
                required
                :error="errors.lastName && errors.lastName[0]"
                :prefix-icon="IconUser"
              />
            </div>

            <!-- Email -->
            <div class="sm:col-span-3">
              <BaseInput
                id="email"
                type="email"
                v-model="formData.email"
                :label="$t('auth.email') + ' *'"
                :disabled="isLoading || isEditMode"
                required
                :error="errors.email && errors.email[0]"
                :prefix-icon="IconMail"
              />
            </div>

            <!-- Password -->
            <div class="sm:col-span-3">
              <PasswordInput
                id="password"
                v-model="formData.password"
                :label="$t('auth.password') + (isEditMode ? '' : ' *')"
                :disabled="isLoading"
                :required="!isEditMode"
                :error="errors.password && errors.password[0]"
                :show-strength="!isEditMode"
              />
              <p v-if="!errors.password" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ isEditMode ? $t('auth.leave_blank_to_keep') : $t('auth.min_password_length', { length: 8 }) }}
              </p>
            </div>

            <!-- Password Confirmation -->
            <div class="sm:col-span-3">
              <PasswordInput
                id="password_confirmation"
                v-model="formData.passwordConfirmation"
                :label="$t('auth.confirm_password') + (isEditMode && !formData.password ? '' : ' *')"
                :disabled="isLoading || !formData.password"
                :required="formData.password.length > 0"
                :error="errors.passwordConfirmation && errors.passwordConfirmation[0]"
              />
            </div>

            <!-- Role -->
            <div class="sm:col-span-3">
              <label
                for="role"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                {{ $t("admin.users.role") }} *
              </label>
              <select
                id="role"
                v-model="formData.role"
                :class="[
                  'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm',
                  errors.role ? 'border-red-500' : '',
                ]"
                :disabled="isLoading"
                required
              >
                <option value="user">{{ $t("admin.users.roles.user") }}</option>
                <option value="admin">
                  {{ $t("admin.users.roles.admin") }}
                </option>
              </select>
              <p v-if="errors.role" class="mt-2 text-sm text-red-600">
                {{ errors.role[0] }}
              </p>
            </div>

            <!-- Status -->
            <div class="sm:col-span-3">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
              >
                {{ $t("admin.users.status") }}
              </label>
              <div class="mt-2">
                <label class="inline-flex items-center">
                  <input
                    type="checkbox"
                    v-model="formData.isActive"
                    :disabled="isLoading"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                  />
                  <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    {{
                      formData.isActive
                        ? $t("admin.users.active")
                        : $t("admin.users.inactive")
                    }}
                  </span>
                </label>
              </div>
              <p v-if="errors.isActive" class="mt-2 text-sm text-red-600">
                {{ errors.isActive[0] }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end space-x-3">
        <router-link
          to="/admin/users"
          class="rounded-md border border-gray-300 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
          :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
        >
          {{ $t("common.cancel") }}
        </router-link>
        <button
          type="submit"
          :disabled="isLoading"
          class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <svg
            v-if="isLoading"
            class="-ml-1 mr-2 h-4 w-4 animate-spin text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          {{ isEditMode ? $t("common.update") : $t("common.create") }}
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import { watch, reactive } from "vue";
import BaseInput from "@/components/ui/BaseInput.vue";
import PasswordInput from "@/components/ui/PasswordInput.vue";
import IconUser from "@/components/ui/icons/IconUser.vue";
import IconMail from "@/components/ui/icons/IconMail.vue";

export default {
  name: "UserForm",
  components: {
    BaseInput,
    PasswordInput,
  },
  props: {
    user: {
      type: Object,
      default: () => ({
        firstName: "",
        lastName: "",
        email: "",
        password: "",
        passwordConfirmation: "",
        role: "user",
        isActive: true,
      }),
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
    isEditMode: {
      type: Boolean,
      default: false,
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
  },
  emits: ["submit"],
  setup(props, { emit }) {
    const formData = reactive({
      ...props.user,
      // Ensure we don't send password fields if they're empty in edit mode
      ...(props.isEditMode && {
        password: "",
        passwordConfirmation: "",
      }),
    });

    // Watch for prop changes and update form data
    watch(
      () => props.user,
      (newUser) => {
        if (newUser) {
          Object.assign(formData, {
            ...newUser,
            // Don't overwrite password fields with empty values
            ...(props.isEditMode && {
              password: formData.password,
              passwordConfirmation: formData.passwordConfirmation,
            }),
          });
        }
      },
      { immediate: true }
    );

    const handleSubmit = () => {
      // Prepare data to submit
      const submitData = { ...formData };

      // Don't send password confirmation to the server
      delete submitData.passwordConfirmation;

      // If in edit mode and password is empty, don't send password
      if (props.isEditMode && !submitData.password) {
        delete submitData.password;
      }

      emit("submit", submitData);
    };

    return {
      formData,
      handleSubmit,
      IconUser,
      IconMail,
    };
  },
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
