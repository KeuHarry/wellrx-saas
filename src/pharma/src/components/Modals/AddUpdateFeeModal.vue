<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">Add Fee</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-rows-2 gap-4 p-10">
        <div>
          <label for="fee-name" class="block text-sm/6 text-gray-900">Name</label>
          <input
            v-model="formData.name"
            type="text"
            placeholder="Enter fee name"
            name="fee-name"
            id="fee-name"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div class="grid grid-cols-6 gap-2">
          <div class="col-span-4">
            <label for="cost" class="block text-sm/6 text-gray-900">Cost</label>
            <input
              v-model="formData.cost"
              type="number"
              placeholder="Ex. 2.19"
              name="cost"
              id="cost"
              class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
            />
          </div>
          <div class="col-span-2">
            <label for="tag" class="block text-sm/6 text-gray-900">Tag</label>
            <select
              v-model="formData.tag"
              name="tag"
              id="tag"
              class="w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm"
            >
              <option :value="tag.value" v-for="(tag, tkey) in feeTags" :key="tkey">
                {{ tag.name }}
              </option>
            </select>
          </div>
        </div>
        <div>
          <label for="description" class="block text-sm/6 text-gray-900">Description</label>
          <input
            v-model="formData.description"
            type="text"
            placeholder="Enter description"
            name="description"
            id="description"
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
import { ref } from 'vue';
import { tags } from '@/constants/tags';

const emit = defineEmits(['submit']);

const show = ref(false);
const formData = ref<{
  id?: number | null;
  name: string;
  cost: number;
  tag: number;
  description: string;
}>({
  id: null,
  name: '',
  cost: 0,
  tag: 0,
  description: '',
});
const feeTags = ref(tags);

// METHODS
const openFeeModal = (fee: {
  id?: number;
  name: string;
  cost: number;
  tag: number;
  description: string;
}) => {
  show.value = true;
  formData.value.id = fee.id;
  formData.value.name = fee.name;
  formData.value.cost = fee.cost;
  formData.value.tag = fee.tag;
  formData.value.description = fee.description;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  formData.value.id = null;
  formData.value.name = '';
  formData.value.cost = 0;
  formData.value.tag = 0;
  formData.value.description = '';
};

const handleSubmit = async () => {
  emit('submit', formData);
  closeModal();
};

defineExpose({
  openFeeModal,
});
</script>
