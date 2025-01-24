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
        <div>
          <label for="name" class="block text-sm/6 text-gray-900">Name</label>
          <input
            v-model="formData.name"
            type="text"
            placeholder="Enter name"
            name="name"
            id="name"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <label for="tax-payer-number" class="block text-sm/6 text-gray-900"
            >Tax Payer Number</label
          >
          <input
            v-model="formData.taxPayerNumber"
            type="text"
            placeholder="Enter tax payer number"
            name="tax-payer-number"
            id="tax-payer-number"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <label for="registration-number" class="block text-sm/6 text-gray-900"
            >Registration Number</label
          >
          <input
            v-model="formData.registrationNumber"
            type="text"
            placeholder="Enter registration number"
            name="registration-number"
            id="registration-number"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
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
        <div>
          <label for="neighborhood" class="block text-sm/6 text-gray-900">Neighborhood</label>
          <input
            v-model="formData.neighborhood"
            type="text"
            placeholder="Enter neighborhood"
            name="neighborhood"
            id="neighborhood"
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
import { InsurerRequest } from '@/composables/models/InsurerRequest';

const emit = defineEmits(['submit']);

const show = ref(false);
const insurerId = ref<number | null>(null);
const formData = ref<InsurerRequest>({
  name: '',
  taxPayerNumber: '',
  registrationNumber: '',
  address: '',
  neighborhood: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${insurerId.value ? 'Update' : 'Add'} Insurance`;
});

// METHODS
const openInsurerModal = (insurerRequest: InsurerRequest, id: number | null) => {
  insurerId.value = id;
  show.value = true;
  formData.value = insurerRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  insurerId.value = null;
  formData.value.name = '';
  formData.value.taxPayerNumber = '';
  formData.value.registrationNumber = '';
  formData.value.address = '';
  formData.value.neighborhood = '';
};

const handleSubmit = async () => {
  emit('submit', formData, insurerId.value);
  closeModal();
};

defineExpose({
  openInsurerModal,
});
</script>
