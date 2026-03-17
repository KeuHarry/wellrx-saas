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
            <label for="supplier-name" class="block text-sm/6 text-gray-900"
              >Name <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.name"
              type="text"
              placeholder="Enter supplier name"
              name="supplier-name"
              id="supplier-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="email" class="block text-sm/6 text-gray-900">Email</label>
            <input
              v-model="formData.email"
              type="email"
              placeholder="Ex. supplier@example.com"
              name="email"
              id="email"
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
              placeholder="+233XXXXXXXXX"
              name="phone"
              id="phone"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="contact" class="block text-sm/6 text-gray-900"
              >Contact <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.contact"
              type="text"
              placeholder="Enter contact"
              name="contact"
              id="contact"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-3">
            <label for="lastDate" class="block text-sm/6 text-gray-900">Last Date</label>
            <input
              v-model="formData.lastDate"
              disabled
              type="text"
              placeholder="last date"
              name="lastDate"
              id="lastDate"
              class="block disabled:bg-blue-50 disabled:cursor-not-allowed w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-3">
            <label for="order-number" class="block text-sm/6 text-gray-900">eOrder Number</label>
            <input
              v-model="formData.eOrderNumber"
              type="number"
              placeholder="Enter eorder number"
              name="order-number"
              id="order-number"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
        </div>
        <div class="grid grid-cols-7 gap-2">
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
          <div class="col-span-2">
            <label for="postal-code" class="block text-sm/6 text-gray-900">Postal Code</label>
            <input
              v-model="formData.postalCode"
              type="text"
              placeholder="Enter postal code"
              name="postal-code"
              id="postal-code"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="fax" class="block text-sm/6 text-gray-900">Fax</label>
            <input
              v-model="formData.fax"
              type="text"
              placeholder="Enter fax"
              name="fax"
              id="fax"
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
import { SupplierRequest } from '@/composables/models/SupplierRequest';

const emit = defineEmits(['submit']);

const show = ref(false);
const supplierId = ref<number | null>(null);
const formData = ref<SupplierRequest>({
  name: '',
  email: '',
  phone: '',
  contact: '',
  city: '',
  address: '',
  postalCode: '',
  lastDate: '',
  eOrderNumber: null,
  fax: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${supplierId.value ? 'Update' : 'Add'} Supplier`;
});

// METHODS
const openSupplierModal = (supplierRequest: SupplierRequest, id: number | null) => {
  supplierId.value = id;
  show.value = true;
  formData.value = supplierRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  supplierId.value = null;
  formData.value.name = '';
  formData.value.email = '';
  formData.value.phone = '';
  formData.value.contact = '';
  formData.value.city = '';
  formData.value.address = '';
  formData.value.postalCode = '';
  formData.value.lastDate = '';
  formData.value.eOrderNumber = null;
  formData.value.fax = '';
};

const handleSubmit = async () => {
  emit('submit', formData, supplierId.value);
  closeModal();
};

defineExpose({
  openSupplierModal,
});
</script>
