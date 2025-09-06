<template>
  <div>
    <TransitionRoot appear :show="isOpen" as="template">
      <DialogComponent as="div" class="relative z-50" @close="cancel">
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
              <DialogPanel
                :class="[
                  'relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6',
                  isDanger ? 'border-l-4 border-red-500' : 'border-l-4 border-indigo-500'
                ]"
              >
                <div class="sm:flex sm:items-start">
                  <div
                    :class="[
                      'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10',
                      isDanger ? 'bg-red-100 dark:bg-red-900/30' : 'bg-indigo-100 dark:bg-indigo-900/30'
                    ]"
                  >
                    <ExclamationTriangleIcon
                      v-if="isDanger"
                      class="h-6 w-6 text-red-600 dark:text-red-400"
                      aria-hidden="true"
                    />
                    <InformationCircleIcon
                      v-else
                      class="h-6 w-6 text-indigo-600 dark:text-indigo-400"
                      aria-hidden="true"
                    />
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <DialogTitle
                      as="h3"
                      class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                    >
                      {{ title }}
                    </DialogTitle>
                    <div class="mt-2">
                      <p class="text-sm text-gray-500 dark:text-gray-300">
                        {{ message }}
                      </p>
                    </div>
                  </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                  <button
                    type="button"
                    :class="[
                      'inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm',
                      isDanger
                        ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                        : 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'
                    ]"
                    @click="confirm"
                  >
                    {{ confirmText }}
                  </button>
                  <button
                    type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600"
                    @click="cancel"
                  >
                    {{ cancelText }}
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </DialogComponent>
    </TransitionRoot>
  </div>
</template>

<script>
import { ref, watch } from 'vue';
import { Dialog as DialogComponent, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ExclamationTriangleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline';

export default {
  name: 'ConfirmationModal',
  components: {
    DialogComponent,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    ExclamationTriangleIcon,
    InformationCircleIcon
  },
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Confirm Action'
    },
    message: {
      type: String,
      default: 'Are you sure you want to perform this action?'
    },
    confirmText: {
      type: String,
      default: 'Confirm'
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    isDanger: {
      type: Boolean,
      default: false
    }
  },
  emits: ['confirm', 'cancel', 'update:isOpen'],
  setup(props, { emit }) {
    const isVisible = ref(props.isOpen);

    watch(
      () => props.isOpen,
      (newVal) => {
        isVisible.value = newVal;
      }
    );

    const confirm = () => {
      emit('confirm');
      close();
    };

    const cancel = () => {
      emit('cancel');
      close();
    };

    const close = () => {
      isVisible.value = false;
      emit('update:isOpen', false);
    };

    return {
      isVisible,
      confirm,
      cancel
    };
  }
};
</script>
