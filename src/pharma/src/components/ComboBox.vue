<template>
  <div class="relative">
    <!-- Input Field -->
    <div class="flex items-center w-full">
      <input
        v-model="searchFilter"
        type="text"
        @input="filterOptions"
        @click="showDropdown = !showDropdown"
        :disabled="disabled"
        class="w-full h-9 py-2 cursor-pointer border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500"
        :placeholder="placeholder"
        autocomplete="off"
      />
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        aria-hidden="true"
        data-slot="icon"
        class="om bar w-5 h-5 absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none"
      >
        <path
          fill-rule="evenodd"
          d="M10.53 3.47a.75.75 0 0 0-1.06 0L6.22 6.72a.75.75 0 0 0 1.06 1.06L10 5.06l2.72 2.72a.75.75 0 1 0 1.06-1.06l-3.25-3.25Zm-4.31 9.81 3.25 3.25a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 1 0-1.06-1.06L10 14.94l-2.72-2.72a.75.75 0 0 0-1.06 1.06Z"
          clip-rule="evenodd"
        ></path>
      </svg>
    </div>

    <!-- Dropdown -->
    <ul v-if="showDropdown" class="absolute w-full mt-1 bg-white border rounded-md shadow-lg z-10">
      <li
        v-for="(option, index) in options"
        :key="index"
        @click="selectOption(option)"
        class="px-4 py-2 cursor-pointer text-sm hover:bg-indigo-100"
      >
        {{ option.name }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { Option } from '@/composables/models/Option';
import { ref, defineProps, watchEffect, onMounted, watch } from 'vue';

// Define the props for the component
const props = defineProps({
  options: {
    type: Array as () => Option[],
    required: true,
  },
  placeholder: {
    type: String,
    default: 'Select an option',
    required: false,
  },
  disabled: {
    type: Boolean,
    required: false,
    default: false,
  },
  defaultOption: {
    type: Object as () => Option,
    required: false,
  },
});

const emit = defineEmits(['selected', 'search']);

const searchFilter = ref<string>('');
const filteredOptions = ref<Option[]>(props.options);
const showDropdown = ref<boolean>(false);

// Function to filter the options based on user input
const filterOptions = () => {
  if (searchFilter.value.length === 0 || searchFilter.value.length >= 3) {
    emit('search', searchFilter.value);
  }
};

// Function to handle option selection
const selectOption = (option: Option) => {
  searchFilter.value = option.name;
  emit('selected', option);
  showDropdown.value = false;
  filteredOptions.value = props.options; // Reset the list to show all options
};

// Watch for clicks outside of the component to close the dropdown
watchEffect(() => {
  document.addEventListener('click', (event) => {
    const isClickInside = event.target instanceof HTMLElement && event.target.closest('.relative');
    if (!isClickInside) {
      showDropdown.value = false;
    }
  });
});

watch(
  () => props.defaultOption,
  (newDefaultOption) => {
    if (newDefaultOption) {
      searchFilter.value = newDefaultOption.name;
      emit('selected', newDefaultOption);
    }
  },
);

onMounted(() => {
  if (props.defaultOption) {
    searchFilter.value = props.defaultOption.name;
    emit('selected', props.defaultOption);
  }
});
</script>

<style scoped>
/* You can add custom styles here if necessary */
</style>
