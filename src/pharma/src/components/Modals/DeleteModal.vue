<template>
  <Dialog
    :open="deleteConfirmationModal"
    @close="setDeleteConfirmationModal(false)"
    :initial-focus="deleteRef"
  >
    <Dialog.Panel>
      <div class="p-5 text-center">
        <Lucide icon="XCircle" class="w-12 h-12 mx-auto mt-3 text-danger" />
        <div class="mt-5 text-3xl">Are you sure ?</div>
        <div class="mt-2 text-slate-500">
          {{ content }} <span class="font-semibold">{{ reference }}</span> ? <br />
        </div>
      </div>
      <div class="px-5 pb-8 text-center">
        <Button
          variant="outline-secondary"
          type="button"
          @click="setDeleteConfirmationModal(false)"
          class="w-24 mr-1"
        >
          Cancel
        </Button>
        <Button variant="danger" type="button" class="w-24" ref="deleteRef" @click="handleSubmit"
          >Delete</Button
        >
      </div>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import Button from '@/components/Base/Button';
import { Dialog } from '@/components/Base/Headless';
import Lucide from '@/components/Base/Lucide';
import { ref } from 'vue';

const emit = defineEmits(['submit']);

const deleteRef = ref(null);
const id = ref<number | null>(null);
const reference = ref<string>('');
const content = ref<string>('');
const deleteConfirmationModal = ref(false);

const setDeleteConfirmationModal = (value: boolean) => {
  deleteConfirmationModal.value = value;
};

const open = (
  record: { id: number; reference: string },
  message: string = 'Do you really want to delete',
) => {
  id.value = record.id;
  reference.value = record.reference;
  content.value = message;
  setDeleteConfirmationModal(true);
};

const handleSubmit = async () => {
  emit('submit', id.value);
  setDeleteConfirmationModal(false);
};

defineExpose({
  open,
});
</script>
