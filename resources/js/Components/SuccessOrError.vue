<template>
  <!-- END: Modal Toggle -->
  <!-- BEGIN: Modal Content -->
  <TransitionRoot as="template" :show="modalOpen">
    <Dialog as="div" class="relative z-100" @close="modalOpen = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
        leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel
              class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm">
              <div class="bg-white px-4 pb-4 pt-1 sm:pb-4">
                <div class="sm:flex justify-center sm:items-start">

                  <div class="text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <div class="mt-2">
                      <span class="text-sm text-gray-500">
                        {{ message }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="px-4 py-3 sm:flex justify-center sm:flex-row-reverse sm:px-6">
                <button type="button"
                  class="transition duration-300 ease-in-out rounded-md bg-sky-500 px-3 py-2 text-sm font-semibold shadow-sm text-white hover:bg-sky-600 sm:ml-3 border"
                  @click="handleClose(false)">Ok</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  <!-- END: Modal Content -->
</template>

<script setup>
import { ref, defineProps } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
const props = defineProps({
  general: Boolean,
  message: String,
})
// Props provenientes del padre
const modalOpen = ref(props.general);
const modalMessage = ref(props.message);
const emit = defineEmits(["valueGeneral"])
const handleClose = (sub) => {
  emit("valueGeneral", modalOpen.value = sub);
}
</script>