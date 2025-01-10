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
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="intermediary-name" class="block text-sm/6 text-gray-900">Name</label>
            <input
              v-model="formData.name"
              type="text"
              placeholder="Enter intermediary name"
              name="intermediary-name"
              id="intermediary-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="phone" class="block text-sm/6 text-gray-900">Phone Number</label>
            <input
              v-model="formData.phone"
              type="text"
              placeholder="+233XXXXXXXXX"
              name="phone"
              id="phone"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div>
          <label for="address" class="block text-sm/6 text-gray-900">Address</label>
          <input
            v-model="formData.address"
            type="text"
            placeholder="Enter address"
            name="address"
            id="address"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
      </div>
      <Dialog.Footer>
        <Button type="button" variant="outline-secondary" @click="closeModal" class="w-20 mr-1">
          Cancel
        </Button>
        <Button variant="primary" type="button" @click="handleSubmit" class="w-20"> Save </Button>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, ref } from 'vue';
import { IntermediaryRequest } from '@/composables/models/IntermediaryRequest';

const emit = defineEmits(['submit']);

const show = ref(false);
const intermediaryId = ref<number | null>(null);
const formData = ref<IntermediaryRequest>({
  name: '',
  phone: '',
  address: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${intermediaryId.value ? 'Update' : 'Add'} Intermediary`;
});

// METHODS
const openIntermediaryModal = (intermediaryRequest: IntermediaryRequest, id: number | null) => {
  intermediaryId.value = id;
  show.value = true;
  formData.value = intermediaryRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  intermediaryId.value = null;
  formData.value.name = '';
  formData.value.phone = '';
  formData.value.address = '';
};

const handleSubmit = async () => {
  emit('submit', formData, intermediaryId.value);
  closeModal();
};

defineExpose({
  openIntermediaryModal,
});
</script>
