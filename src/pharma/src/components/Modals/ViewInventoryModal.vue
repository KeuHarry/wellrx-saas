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
          <div class="col-span-4">
            <label for="customer-name" class="block text-sm/6 text-gray-600">Product Name</label>
            <input
              v-model="formData.product.label"
              type="text"
              placeholder="Enter customer name"
              name="customer-name"
              id="customer-name"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="email" class="block text-sm/6 text-gray-600">Shelf</label>
            <input
              v-model="formData.product.shelf"
              type="text"
              placeholder="Enter shelf name"
              name="email"
              id="email"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
        </div>
        <div class="grid grid-cols-8 gap-2">
          <div class="col-span-2">
            <label for="batch-id" class="block text-sm/6 text-gray-600">Batch Id</label>
            <input
              v-model="formData.batchId"
              type="text"
              placeholder="Enter batch id"
              name="batch-id"
              id="batch-id"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="qty" class="block text-sm/6 text-gray-600">Quantity</label>
            <input
              v-model="formData.quantity"
              type="number"
              placeholder="Enter quantity"
              name="qty"
              id="qty"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="type" class="block text-sm/6 text-gray-600">Type</label>
            <input
              v-model="inventoryType"
              type="text"
              placeholder="Enter type"
              name="type"
              id="type"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="expiry-date" class="block text-sm/6 text-gray-600">Expiry Date</label>
            <input
              v-model="formData.expiryDate"
              type="text"
              placeholder="Enter expiry date"
              name="expiry-date"
              id="expiry-date"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
        </div>
        <div class="grid grid-cols-8 gap-2">
          <div class="col-span-2">
            <label for="credit" class="block text-sm/6 text-gray-600">Acquisition Cost</label>
            <input
              v-model="formData.acqCost"
              type="number"
              placeholder="Enter credit limit"
              name="credit"
              id="credit"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="balance" class="block text-sm/6 text-gray-600">Wholesale Cost</label>
            <input
              v-model="formData.wholesaleCost"
              type="number"
              placeholder="Enter balance"
              name="balance"
              id="balance"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="deposit" class="block text-sm/6 text-gray-600">Retail Cost</label>
            <input
              v-model="formData.retailCost"
              type="number"
              placeholder="Ex. customer@example.com"
              name="deposit"
              id="deposit"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
          <div class="col-span-2">
            <label for="unit-cost" class="block text-sm/6 text-gray-600">Unit Cost</label>
            <input
              v-model="formData.unitCost"
              type="number"
              placeholder="Enter unit cost"
              name="unit-cost"
              id="unit-cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
              disabled
            />
          </div>
        </div>
        <div>
          <label for="supplier" class="block text-sm/6 text-gray-600">Supplier</label>
          <input
            v-model="formData.supplier.name"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            type="text"
            placeholder="Enter supplier"
            name="supplier"
            id="supplier"
            disabled
          />
        </div>
        <div>
          <label for="reason" class="block text-sm/6 text-gray-600">Reason</label>
          <textarea
            v-model="reason"
            class="block w-full border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-blue-50 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            name="reason"
            id="reason"
            disabled
          >
          </textarea>
        </div>
      </div>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Lucide from '@/components/Base/Lucide';
import { computed, ref, toRaw } from 'vue';
import { Inventory } from '@/composables/models/Inventory';
import InventoryType from '@/enums/inventoryType';
import { getInventoryTypeNameByValue } from '@/utils/helper';

const emit = defineEmits(['submit']);

const show = ref(false);
const reason = ref<string | null>(null);
const formData = ref<Inventory>({
  id: 0,
  product: {
    id: 0,
    label: '',
    shelf: '',
  },
  quantity: 0,
  type: InventoryType.IN,
  expiryDate: '',
  batchId: 0,
  acqCost: 0,
  wholesaleCost: 0,
  retailCost: 0,
  unitCost: 0,
  supplier: {
    id: 0,
    name: '',
  },
  reason: '',
  createdAt: '',
});
const modalTitle = 'View inventory details';

// COMPUTED
const inventoryType = computed(() => getInventoryTypeNameByValue(formData.value.type));

// METHODS
const openViewInventoryModal = (inventory: Inventory) => {
  show.value = true;
  const rawInventory = toRaw(inventory);
  formData.value = { ...rawInventory };
  reason.value = inventory.reason;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  formData.value.product = {
    id: 0,
    label: '',
    shelf: '',
  };
  formData.value.quantity = 0;
  formData.value.type = InventoryType.IN;
  formData.value.expiryDate = '';
  formData.value.batchId = 0;
  formData.value.acqCost = 0;
  formData.value.wholesaleCost = 0;
  formData.value.retailCost = 0;
  formData.value.unitCost = 0;
  formData.value.supplier = {
    id: 0,
    name: '',
  };
  formData.value.reason = '';
  formData.value.createdAt = '';
};

defineExpose({
  openViewInventoryModal,
});
</script>
