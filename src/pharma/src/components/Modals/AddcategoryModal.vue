<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <Dialog.Title>
        <h2 class="mr-auto text-base font-medium">Add Category</h2>
      </Dialog.Title>

      <a @click="closeModal" class="absolute top-0 right-0 mt-3 mr-3" href="#">
        <Lucide icon="X" class="w-6 h-6 text-slate-400" />
      </a>
      <div class="grid grid-rows-2 gap-4 p-10">
        <div>
          <label for="shelf-name" class="block text-sm/6 text-gray-900">Name</label>
          <input
            v-model="name"
            type="text"
            placeholder="Enter shelf name"
            name="shelf-name"
            id="shelf-name"
            class="block w-full h-9 border border-gray-300 rounded-md shadow-sm text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm/6"
          />
        </div>
        <div>
          <label for="markup" class="block text-sm/6 text-gray-900">Markup</label>
          <input
            v-model="markup"
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
            v-model="description"
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
        <Button variant="primary" type="button" @click="createCategory" class="w-20"> Save </Button>
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless'
import Button from '@/components/Base/Button'
import Lucide from '@/components/Base/Lucide'
import { ref } from 'vue'
import useShelfStore from '@/stores/shelf'
import useCategoryStore from '@/stores/category'

defineProps({
  show: {
    type: Boolean,
    required: true,
  },
})
const emit = defineEmits(['close', 'cancel', 'refreshData'])

const name = ref('')
const description = ref('')
const markup = ref(0)

const categoryStore = useCategoryStore()

// METHODS
const closeModal = () => {
  emit('close')
}
const cancelModal = () => {
  emit('cancel')
  resetForm()
}

const resetForm = () => {
  name.value = ''
  description.value = ''
}

const createCategory = async () => {
  await categoryStore.createCategoryApi({
    name: name.value,
    markup: markup.value,
    description: description.value,
  })

  emit('refreshData', true)
  resetForm()
}
</script>
