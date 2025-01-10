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
            <label for="first-name" class="block text-sm/6 text-gray-900"
              >First Name <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.firstName"
              type="text"
              placeholder="Enter first name"
              name="first-name"
              id="first-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="email" class="block text-sm/6 text-gray-900"
              >Last Name <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.lastName"
              type="text"
              placeholder="Enter last name"
              name="last-name"
              id="last-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="phone" class="block text-sm/6 text-gray-900"
              >Phone Number <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.phone"
              type="text"
              placeholder="Ex. +233XXXXXXXXX"
              name="phone"
              id="phone"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="reg-number" class="block text-sm/6 text-gray-900"
              >Registration Number <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.registrationNumber"
              type="text"
              placeholder="Enter registration"
              name="reg-number"
              id="reg-number"
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
        <Button variant="primary" type="button" @click="handleSubmit" class="w-20">Save</Button>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, ref } from 'vue';
import { PrescriberRequest } from '@/composables/models/PrescriberRequest';

const emit = defineEmits(['submit']);

const show = ref(false);
const prescriberId = ref<number | null>(null);
const formData = ref<PrescriberRequest>({
  firstName: '',
  lastName: '',
  registrationNumber: '',
  phone: '',
  address: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${prescriberId.value ? 'Update' : 'Add'} Prescriber`;
});

// METHODS
const openPrescriberModal = (prescriberRequest: PrescriberRequest, id: number | null) => {
  prescriberId.value = id;
  show.value = true;
  formData.value = prescriberRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  prescriberId.value = null;
  formData.value.firstName = '';
  formData.value.lastName = '';
  formData.value.registrationNumber = '';
  formData.value.phone = '';
  formData.value.address = '';
};

const handleSubmit = async () => {
  emit('submit', formData, prescriberId.value);
  closeModal();
};

defineExpose({
  openPrescriberModal,
});
</script>
