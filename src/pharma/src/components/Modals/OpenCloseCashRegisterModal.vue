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
        <h2 class="text-center text-lg">Do you really want to {{ canOpen }} the cash register ?</h2>
      </div>
      <Dialog.Footer>
        <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
          Cancel
        </Button>
        <Button variant="primary" type="button" @click="handleSubmit" class="w-20">Confirm</Button>
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

const emit = defineEmits(['submit']);

const show = ref(false);
const cashRegisterId = ref<number | null>(null);

// COMPUTED
const canOpen = computed(() => `${cashRegisterId.value ? 'close' : 'open'}`);
const modalTitle = computed(() => {
  return `${capitalizeFirstLetter(canOpen.value)} Cash Register`;
});

// METHODS
const openCashRegisterModal = (id: number | null) => {
  cashRegisterId.value = id;
  show.value = true;
};

const closeModal = () => {
  show.value = false;
};

const handleSubmit = async () => {
  emit('submit');
  closeModal();
};

defineExpose({
  openCashRegisterModal,
});
</script>
