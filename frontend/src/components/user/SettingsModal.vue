<template>
  <TransitionRoot as="template" :show="isOpen">
    <HeadlessDialog as="div" class="relative z-50" @close="handleClose">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        >TEST</div>
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div
          class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
        >
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <HeadlessDialogPanel
              class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6"
            >
              <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                <button
                  type="button"
                  class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  @click="handleClose"
                >
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>

              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10"
                >
                  <Cog6ToothIcon
                    class="h-6 w-6 text-indigo-600 dark:text-indigo-400"
                    aria-hidden="true"
                  />
                </div>

                <div
                  class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                >
                  <HeadlessDialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                  >
                    Settings
                  </HeadlessDialogTitle>

                  <div class="mt-4">
                    <TabGroup>
                      <TabList
                        class="flex space-x-1 rounded-xl bg-indigo-900/20 p-1"
                      >
                        <Tab
                          v-for="tab in tabs"
                          :key="tab.id"
                          v-slot="{ selected }"
                          class="w-full"
                        >
                          <button
                            :class="[
                              'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                              'ring-white ring-opacity-60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                              selected
                                ? 'bg-white dark:bg-gray-700 shadow text-indigo-700 dark:text-white'
                                : 'text-indigo-100 hover:bg-white/[0.12] hover:text-white',
                            ]"
                          >
                            <div
                              class="flex items-center justify-center space-x-2"
                            >
                              <component :is="tab.icon" class="h-4 w-4" />
                              <span>{{ tab.name }}</span>
                            </div>
                          </button>
                        </Tab>
                      </TabList>

                      <TabPanels class="mt-4">
                        <TabPanel>
                          <div class="space-y-4">
                            <div>
                              <h4
                                class="text-md font-medium text-gray-900 dark:text-white mb-3"
                              >
                                Account Information
                              </h4>
                              <div class="space-y-4">
                                <div>
                                  <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                  >
                                    Email
                                  </label>
                                  <div class="mt-1">
                                    <input
                                      type="email"
                                      id="email"
                                      v-model="user.email"
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                                      placeholder="you@example.com"
                                    />
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </TabPanel>
                        <TabPanel>
                          <div class="space-y-4">
                            <h4
                              class="text-md font-medium text-gray-900 dark:text-white"
                            >
                              Notifications
                            </h4>
                            <div class="space-y-4">
                              <div
                                v-for="setting in notificationSettings"
                                :key="setting.key"
                                class="flex items-center justify-between"
                              >
                                <div class="flex flex-col">
                                  <span
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                    >{{ setting.label }}</span
                                  >
                                </div>
                                <SwitchGroup as="div" class="flex items-center justify-between">
                                  <SwitchComponent
                                    v-model="setting.enabled"
                                    :class="[
                                      setting.enabled
                                        ? 'bg-indigo-600'
                                        : 'bg-gray-200 dark:bg-gray-600',
                                      'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                                    ]"
                                  />
                                </SwitchGroup>
                              </div>
                            </div>
                          </div>
                        </TabPanel>
                      </TabPanels>
                    </TabGroup>
                  </div>
                </div>
              </div>

              <div
                class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3"
              >
                <button
                  type="button"
                  class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2"
                  @click="saveSettings"
                >
                  Save
                </button>
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0"
                  @click="handleClose"
                >
                  Cancel
                </button>
              </div>
            </HeadlessDialogPanel>
          </TransitionChild>
        </div>
      </div>
    </HeadlessDialog>
  </TransitionRoot>
</template>

<script>
import { ref, reactive } from "vue";
import {
  Dialog as HeadlessDialog,
  DialogPanel as HeadlessDialogPanel,
  DialogTitle as HeadlessDialogTitle,
  Switch as SwitchComponent,
  TransitionChild,
  TransitionRoot,
  TabGroup,
  TabList,
  Tab,
  TabPanels,
  TabPanel,
} from "@headlessui/vue";
import {
  XMarkIcon,
  Cog6ToothIcon,
  BellIcon,
  UserIcon,
} from "@heroicons/vue/24/outline";

export default {
  name: "SettingsModal",
  components: {
    HeadlessDialog,
    HeadlessDialogPanel,
    HeadlessDialogTitle,
    SwitchComponent,
    TransitionChild,
    TransitionRoot,
    TabGroup,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
    XMarkIcon,
    Cog6ToothIcon,
    BellIcon,
    UserIcon,
  },
  props: {
    isOpen: {
      type: Boolean,
      required: true,
    },
  },
  emits: ["update:isOpen", "saved"],
  setup(props, { emit }) {
    const user = reactive({
      email: "",
      marketingEmails: true,
    });

    const notificationSettings = ref([
      {
        key: "email",
        label: "Email notifications",
        enabled: true,
      },
      {
        key: "push",
        label: "Push notifications",
        enabled: true,
      },
    ]);

    const tabs = [
      { id: "account", name: "Account", icon: "UserIcon" },
      { id: "notifications", name: "Notifications", icon: "BellIcon" },
    ];

    const handleClose = () => {
      emit("update:isOpen", false);
    };

    const saveSettings = async () => {
      try {
        // Save settings logic here
        emit("saved");
        handleClose();
      } catch (error) {
        console.error("Failed to save settings:", error);
      }
    };

    return {
      user,
      tabs,
      notificationSettings,
      handleClose,
      saveSettings,
    };
  },
};
</script>
