<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">{{ modalTitle }}</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
        <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
      </a>
      <div class="grid grid-rows-2 gap-4 p-10">
        <div>
          <label for="category-name" class="block text-sm/6 text-gray-900">Name</label>
          <input
            v-model="formData.name"
            type="text"
            placeholder="Enter category name"
            name="category-name"
            id="category-name"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <label for="markup" class="block text-sm/6 text-gray-900">Markup</label>
          <input
            v-model="formData.markup"
            type="number"
            placeholder="Ex. 0.15"
            name="markup"
            id="markup"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
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
import { computed } from 'vue';

const emit = defineEmits(['submit']);

const show = ref(false);
const formData = ref<{ id?: number | null; name: string; markup: number; description: string }>({
  id: null,
  name: '',
  markup: 0,
  description: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${formData.value.id ? 'Update' : 'Add'} Category`;
});

// METHODS
const openCategoryModal = (category: {
  id?: number;
  name: string;
  markup: number;
  description: string;
}) => {
  show.value = true;
  formData.value.id = category.id;
  formData.value.name = category.name;
  formData.value.markup = category.markup;
  formData.value.description = category.description;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  formData.value.id = null;
  formData.value.name = '';
  formData.value.markup = 0;
  formData.value.description = '';
};

const handleSubmit = async () => {
  emit('submit', formData);
  closeModal();
};

defineExpose({
  openCategoryModal,
});
</script>
