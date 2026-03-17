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
            <label for="customer-name" class="block text-sm/6 text-gray-900">Name</label>
            <input
              v-model="formData.name"
              type="text"
              placeholder="Enter customer name"
              name="customer-name"
              id="customer-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="email" class="block text-sm/6 text-gray-900">Email</label>
            <input
              v-model="formData.email"
              type="email"
              placeholder="Ex. customer@example.com"
              name="email"
              id="email"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-9 gap-2">
          <div class="col-span-3">
            <label for="credit" class="block text-sm/6 text-gray-900">Credit Limit</label>
            <input
              v-model="formData.creditLimit"
              type="number"
              placeholder="Enter credit limit"
              name="credit"
              id="credit"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="balance" class="block text-sm/6 text-gray-900">Balance</label>
            <input
              v-model="formData.balance"
              type="number"
              placeholder="Enter balance"
              name="balance"
              id="balance"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="deposit" class="block text-sm/6 text-gray-900">Deposit</label>
            <input
              v-model="formData.deposit"
              type="number"
              placeholder="Ex. customer@example.com"
              name="deposit"
              id="deposit"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
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
          <div class="col-span-3">
            <label for="city" class="block text-sm/6 text-gray-900">City</label>
            <input
              v-model="formData.city"
              type="text"
              placeholder="Ex. Accra"
              name="city"
              id="city"
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
import { CustomerRequest } from '@/composables/models/CustomerRequest';

const emit = defineEmits(['submit']);

const show = ref(false);
const customerId = ref<number | null>(null);
const formData = ref<CustomerRequest>({
  name: '',
  email: '',
  creditLimit: 0,
  balance: 0,
  deposit: 0,
  phone: '',
  city: '',
  address: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${customerId.value ? 'Update' : 'Add'} Customer`;
});

// METHODS
const openCustomerModal = (customerRequest: CustomerRequest, id: number | null) => {
  customerId.value = id;
  show.value = true;
  formData.value = customerRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  customerId.value = null;
  formData.value.name = '';
  formData.value.email = '';
  formData.value.creditLimit = 0;
  formData.value.balance = 0;
  formData.value.deposit = 0;
  formData.value.phone = '';
  formData.value.city = '';
  formData.value.address = '';
};

const handleSubmit = async () => {
  emit('submit', formData, customerId.value);
  closeModal();
};

defineExpose({
  openCustomerModal,
});
</script>
