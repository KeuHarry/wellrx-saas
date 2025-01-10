<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">{{ modalTitle }}</h2>
        <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3">
          <Lucide icon="X" class="w-6 h-6 text-slate-400 cursor-pointer" />
        </a>
      </Dialog.Title>
      <Dialog.Description class="grid grid-rows-2 gap-4 p-10">
        <div>
          <label for="code" class="block text-sm/6 text-gray-900">Code</label>
          <input
            v-model="formData.labelCode"
            type="text"
            placeholder="Ex. OTPD"
            name="code"
            id="code"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <label for="label" class="block text-sm/6 text-gray-900">Label</label>
          <input
            v-model="formData.label"
            type="text"
            placeholder="Enter label"
            name="label"
            id="label"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
      </Dialog.Description>
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
import { LabelCodeRequest } from '@/composables/models/LabelCodeRequest';

const emit = defineEmits(['submit']);

const show = ref(false);
const labelCodeId = ref<number | null>(null);
const formData = ref<LabelCodeRequest>({
  labelCode: '',
  label: '',
});

// COMPUTED
const modalTitle = computed(() => {
  return `${labelCodeId.value ? 'Update' : 'Add'} Label`;
});

// METHODS
const openLabelModal = (labelCodeRequest: LabelCodeRequest, id: number | null) => {
  labelCodeId.value = id;
  show.value = true;
  formData.value = labelCodeRequest;
};

const closeModal = () => {
  show.value = false;
  resetForm();
};

const resetForm = () => {
  labelCodeId.value = null;
  formData.value.labelCode = '';
  formData.value.label = '';
};

const handleSubmit = async () => {
  emit('submit', formData, labelCodeId.value);
  closeModal();
};

defineExpose({
  openLabelModal,
});
</script>
