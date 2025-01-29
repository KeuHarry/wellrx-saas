<template>
  <Dialog size="md" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-sm font-medium">Inventory Out - {{ modalTitle }}</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-cols-1 gap-4 p-10">
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-2">
            <label for="qty" class="block text-sm/6 text-gray-600"
              >Quantity <span class="text-danger">*</span></label
            >
            <input
              v-model="formData.quantity"
              type="number"
              min="1"
              placeholder="Ex. 0"
              name="qty"
              id="qty"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-4">
            <label for="qty" class="block text-sm/6 text-gray-600"
              >Type <span class="text-danger">*</span></label
            >
            <select
              v-model="formData.type"
              class="h-9 border w-full border-gray-300 rounded-md shadow-sm text-sm text-slate-900"
              name="inventory-type"
              id="inventory-type"
            >
              <option
                v-for="(type, ikey) in inventoryTypes"
                :key="ikey"
                :value="type.value"
                :class="[type.class ? type.class : 'text-slate-900']"
              >
                {{ type.name }}
              </option>
            </select>
          </div>
        </div>
        <div>
          <label for="reason" class="block text-sm/6 text-gray-600">Reason</label>
          <textarea
            v-model="formData.reason"
            class="block w-full border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            name="reason"
            id="reason"
          >
          </textarea>
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
import { InventoryOutRequest } from '@/composables/models/InventoryOutRequest';
import InventoryType from '@/enums/inventoryType';
import { inventoryTypeList } from '@/constants/inventoryTypeList';
import { Inventory } from '@/composables/models/Inventory';

const emit = defineEmits(['submit']);

const show = ref(false);
const inventoryId = ref<number | null>(null);
const formData = ref<InventoryOutRequest>({
  inventoryId: null,
  quantity: 1,
  type: InventoryType.RETURN,
  reason: '',
});
const batchId = ref<number>(0);
const productName = ref<string>('');

// COMPUTED
const modalTitle = computed(() => `#${batchId.value} ${productName.value}`);

const inventoryTypes = computed(() => {
  const excludedTypes = [
    InventoryType.IN,
    InventoryType.DECOMPOSED,
    InventoryType.SELL,
    InventoryType.REFUND,
  ];
  return inventoryTypeList.filter(
    (inventoryType) => inventoryType.value !== null && !excludedTypes.includes(inventoryType.value),
  );
});

// METHODS
const openInventoryOutModal = (inventory: Inventory) => {
  inventoryId.value = inventory.id;
  show.value = true;
  formData.value = {
    ...formData.value,
    quantity: inventory.batchQoh,
    inventoryId: inventory.id,
  };

  batchId.value = inventory.batchId;
  productName.value = inventory.product.label;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  formData.value.inventoryId = null;
  formData.value.type = InventoryType.RETURN;
  formData.value.quantity = 1;
  formData.value.reason = '';
};

const handleSubmit = async () => {
  formData.value.quantity = -formData.value.quantity;
  emit('submit', formData);
  closeModal();
};

defineExpose({
  openInventoryOutModal,
});
</script>
