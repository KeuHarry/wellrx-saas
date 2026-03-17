<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">{{ modalTitle }}</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-cols-1 gap-4 p-10">
        <h2 class="text-center text-lg">{{ content }}</h2>
      </div>
      <Dialog.Footer>
        <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
          Cancel
        </Button>
        <Button variant="primary" type="button" @click="handleSubmit(false)" class="w-20 mr-1"
          >Confirm</Button
        >
        <Button v-if="!isOneTimePayment" variant="primary" type="button" @click="handleSubmit(true)"
          >Confirm + Deduct</Button
        >
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, ref } from 'vue';
import { capitalizeFirstLetter } from '@/utils/helper';

defineProps({
  modalTitle: {
    type: String,
    required: true,
  },
  content: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['submit']);

const show = ref(false);
const isOneTimePayment = ref(true);

// METHODS
const openConfirmModal = (isOneTime: boolean = false) => {
  show.value = true;
  isOneTimePayment.value = isOneTime;
};

const closeModal = () => {
  show.value = false;
};

const handleSubmit = async (canDeduct: boolean) => {
  emit('submit', canDeduct);
  closeModal();
};

defineExpose({
  openConfirmModal,
});
</script>
