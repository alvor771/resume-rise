<template>
  <Teleport to="body">
    <Transition name="modal">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
      >
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <div
              class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
              aria-hidden="true"
              @click="handleBackdropClick"
            />
          </TransitionChild>

          <!-- Modal panel -->
          <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
          <TransitionChild
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle"
              :class="maxWidthClass"
            >
              <div v-if="showCloseButton" class="absolute top-0 right-0 pt-4 pr-4">
                <button
                  type="button"
                  class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  @click="close"
                >
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>
              <div>
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                  <h3
                    v-if="title"
                    id="modal-title"
                    class="text-lg font-medium leading-6 text-gray-900"
                  >
                    {{ title }}
                  </h3>
                  <div :class="{ 'mt-2': title }" class="text-sm text-gray-500">
                    <slot></slot>
                  </div>
                </div>
              </div>
              <div v-if="showFooter" class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <slot name="footer">
                  <button
                    type="button"
                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="$emit('confirm')"
                  >
                    {{ confirmText }}
                  </button>
                  <button
                    type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                    @click="close"
                  >
                    {{ cancelText }}
                  </button>
                </slot>
              </div>
            </div>
          </TransitionChild>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

export default {
  name: 'BaseModal',
  components: {
    XMarkIcon,
    Transition: {
      name: 'modal',
      setup(props, { slots }) {
        return () => slots.default();
      },
    },
    TransitionChild: {
      name: 'modal-child',
      setup(props, { slots }) {
        return () => slots.default();
      },
    },
  },
  props: {
    isOpen: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: '',
    },
    showCloseButton: {
      type: Boolean,
      default: true,
    },
    showFooter: {
      type: Boolean,
      default: true,
    },
    closeOnBackdrop: {
      type: Boolean,
      default: true,
    },
    closeOnEscape: {
      type: Boolean,
      default: true,
    },
    maxWidth: {
      type: String,
      default: '2xl',
      validator: (value) => ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'].includes(value),
    },
    confirmText: {
      type: String,
      default: 'Confirm',
    },
    cancelText: {
      type: String,
      default: 'Cancel',
    },
  },
  emits: ['update:isOpen', 'close', 'confirm'],
  setup(props, { emit }) {
    const isActive = ref(props.isOpen);

    const maxWidthClass = computed(() => {
      return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
        '6xl': 'sm:max-w-6xl',
        '7xl': 'sm:max-w-7xl',
      }[props.maxWidth];
    });

    const close = () => {
      isActive.value = false;
      emit('update:isOpen', false);
      emit('close');
    };

    const handleBackdropClick = () => {
      if (props.closeOnBackdrop) {
        close();
      }
    };

    const handleEscape = (e) => {
      if (e.key === 'Escape' && props.closeOnEscape && isActive.value) {
        close();
      }
    };

    watch(
      () => props.isOpen,
      (newVal) => {
        if (newVal) {
          document.body.style.overflow = 'hidden';
          document.addEventListener('keydown', handleEscape);
        } else {
          document.body.style.overflow = '';
          document.removeEventListener('keydown', handleEscape);
        }
        isActive.value = newVal;
      }
    );

    onMounted(() => {
      if (props.isOpen) {
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleEscape);
      }
    });

    onBeforeUnmount(() => {
      document.body.style.overflow = '';
      document.removeEventListener('keydown', handleEscape);
    });

    return {
      close,
      handleBackdropClick,
      maxWidthClass,
    };
  },
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-child-enter-active,
.modal-child-leave-active {
  transition: all 0.3s ease;
}

.modal-child-enter-from,
.modal-child-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
