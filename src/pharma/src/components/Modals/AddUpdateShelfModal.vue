<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">Add Shelf</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3" href="#">
        <Lucide icon="X" class="w-6 h-6 text-slate-400" />
      </a>
      <div class="grid grid-rows-2 gap-4 p-10">
        <div>
          <label for="shelf-name" class="block text-sm/6 text-gray-900">Name</label>
          <input
            v-model="formData.name"
            type="text"
            placeholder="Enter shelf name"
            name="shelf-name"
            id="shelf-name"
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
        <Button type="button" variant="outline-secondary" @click="cancelModal" class="w-20 mr-1">
          Cancel
        </Button>
        <Button variant="primary" type="button" @click="handleSubmit" class="w-20"> Save </Button>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless'
import Button from '@/components/Base/Button'
import Lucide from '@/components/Base/Lucide'
import { ref } from 'vue'

defineProps({
  show: {
    type: Boolean,
    required: true,
  },
})
const emit = defineEmits(['close', 'cancel', 'submit'])

const formData = ref({
  name: '',
  description: '',
})

// METHODS
const closeModal = () => {
  emit('close')
}
const cancelModal = () => {
  emit('cancel')
  resetForm()
}

const resetForm = () => {
  formData.value.name = ''
  formData.value.description = ''
}

const handleSubmit = async () => {
  emit('submit', formData)
  cancelModal()
}
</script>
