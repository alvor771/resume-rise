<template>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="md:flex md:items-center md:justify-between">
      <div class="min-w-0 flex-1">
        <h2
          class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight"
        >
          {{
            isEditMode
              ? $t("admin.users.edit_user")
              : $t("admin.users.create_user")
          }}
        </h2>
      </div>
      <div class="mt-4 flex md:ml-4 md:mt-0">
        <router-link
          to="/admin/users"
          class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600"
        >
          <ArrowLeftIcon class="-ml-0.5 mr-1.5 h-5 w-5" />
          {{ $t("common.back") }}
        </router-link>
      </div>
    </div>

    <div class="mt-8">
      <UserForm
        :user="user"
        :is-loading="isLoading"
        :is-edit-mode="isEditMode"
        :errors="errors"
        @submit="handleSubmit"
      />
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { ArrowLeftIcon } from "@heroicons/vue/20/solid";
import UserForm from "./UserForm.vue";
import { useUserStore } from "@/stores/user";
import { useToast } from "vue-toastification";

export default {
  name: "UserEdit",
  components: {
    UserForm,
    ArrowLeftIcon,
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const { t } = useI18n();
    const toast = useToast();
    const userStore = useUserStore();

    const userId = computed(() => route.params.id);
    const isEditMode = computed(() => !!userId.value);

    const user = ref({
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      passwordConfirmation: "",
      role: "user",
      isActive: true,
    });

    const isLoading = ref(false);
    const errors = ref({});

    // Fetch user data if in edit mode
    onMounted(async () => {
      if (isEditMode.value) {
        await fetchUser();
      }
    });

    async function fetchUser() {
      try {
        isLoading.value = true;
        const userData = await userStore.fetchUser(userId.value);
        // Map backend user to form fields
        const fullName = userData.name || "";
        const [firstNamePart, ...rest] = fullName.split(" ");
        user.value = {
          firstName: firstNamePart || "",
          lastName: rest.join(" ") || "",
          email: userData.email || "",
          role: userData.role || "user",
          isActive: userData.is_active ?? userData.isActive ?? true,
          password: "",
          passwordConfirmation: "",
        };
      } catch (error) {
        console.error("Error fetching user:", error);
        toast.error(t("common.errors.failed_to_load_data"));
        router.push("/admin/users");
      } finally {
        isLoading.value = false;
      }
    }

    async function handleSubmit(formData) {
      try {
        isLoading.value = true;
        errors.value = {};

        // Map form fields to backend expected payload
        const payload = {
          name: `${formData.firstName || ""} ${formData.lastName || ""}`.trim(),
          email: formData.email,
          role: formData.role,
          // Only include password fields when provided
          ...(formData.password
            ? {
                password: formData.password,
                password_confirmation: formData.passwordConfirmation || formData.password,
              }
            : {}),
        };

        if (isEditMode.value) {
          await userStore.updateUser(userId.value, payload);
          toast.success(t("admin.users.user_updated"));
        } else {
          await userStore.createUser(payload);
          toast.success(t("admin.users.user_created"));
        }

        router.push("/admin/users");
      } catch (error) {
        console.error("Error saving user:", error);
        if (error.response?.data?.errors) {
          errors.value = error.response.data.errors;
        } else {
          const errorMessage =
            error.response?.data?.message ||
            t("common.errors.something_went_wrong");
          toast.error(errorMessage);
        }
      } finally {
        isLoading.value = false;
      }
    }

    return {
      user,
      isLoading,
      isEditMode,
      errors,
      handleSubmit,
    };
  },
};
</script>
