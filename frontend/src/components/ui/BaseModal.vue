<template>
  <Teleport to="body">
    <Transition name="fade">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
      >
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <div 
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            @click="closeModal"
          ></div>

          <!-- This element is to trick the browser into centering the modal contents. -->
          <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

          <!-- Modal panel -->
          <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                  <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                    <slot name="title">{{ title }}</slot>
                  </h3>
                  <div class="mt-2">
                    <slot></slot>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="$slots.footer" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <slot name="footer"></slot>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script>
import {  watch } from 'vue';

export default {
  name: 'BaseModal',
  props: {
    isOpen: {
      type: Boolean,
      required: true
    },
    title: {
      type: String,
      default: ''
    },
    closeOnBackdrop: {
      type: Boolean,
      default: true
    }
  },
  emits: ['close'],
  setup(props, { emit }) {
    const closeModal = () => {
      if (props.closeOnBackdrop) {
        emit('close');
      }
    };

    // Close on escape key
    const onEscape = (e) => {
      if (e.key === 'Escape' && props.isOpen) {
        closeModal();
      }
    };

    // Add/remove event listener when modal opens/closes
    watch(() => props.isOpen, (isOpen) => {
      if (isOpen) {
        document.addEventListener('keydown', onEscape);
        document.body.style.overflow = 'hidden';
      } else {
        document.removeEventListener('keydown', onEscape);
        document.body.style.overflow = '';
      }
    });

    return {
      closeModal
    };
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
