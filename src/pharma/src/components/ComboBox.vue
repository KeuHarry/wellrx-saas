<template>
  <div class="relative">
    <!-- Input Field -->
    <div class="flex items-center w-full">
      <div
        v-if="multiple"
        @click="showDropdown = !showDropdown"
        class="flex flex-wrap h-9 items-center gap-1 w-full border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 cursor-pointer px-2"
        :class="{
          'focus:ring-indigo-500 focus:border-indigo-500 ring-1 ring-indigo-500': isFocused,
          'border-gray-300': !isFocused,
          'border-indigo-500': isFocused,
        }"
      >
        <span v-if="!showDropdown && !(selectedOptions.length > 0)" class="text-gray-500 px-1"
          >Select label</span
        >
        <div
          v-for="(selected, index) in selectedOptions"
          :key="index"
          class="flex items-center bg-indigo-100 text-indigo-700 text-sm px-2 py-1 rounded-md"
        >
          {{ selected.name }}
          <button
            @click.stop="removeOption(selected)"
            class="ml-1 text-indigo-700 hover:text-indigo-900"
          >
            &times;
          </button>
        </div>
      </div>
      <input
        v-else
        v-model="searchFilter"
        type="text"
        @focus="handleFocus(true)"
        @blur="handleFocus(false)"
        @input="filterOptions"
        @click="showDropdown = !showDropdown"
        :disabled="disabled"
        class="w-full h-9 py-2 cursor-pointer border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500"
        :class="classes"
        :placeholder="placeholder"
        autocomplete="off"
      />
      <button
      v-if="!multiple && clearable && searchFilter.length > 0"
      @click="clearSingleSelection"
      class="absolute right-8 top-1/2 transform -translate-y-1/2 text-red-400 hover:text-red-500 text-sm"
      >
        <XIcon />
      </button>
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
    <ul
      v-if="showDropdown"
      class="absolute w-full mt-1 bg-white border rounded-md shadow-lg z-10 max-h-48 overflow-y-auto"
    >
      <li v-if="multiple" class="px-4 py-2">
        <input
          v-model="searchFilter"
          type="text"
          class="w-full h-9 px-4 py-2 cursor-pointer border border-gray-300 rounded-md shadow-sm text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500"
          @input="filterOptions"
          :placeholder="placeholder"
          autocomplete="off"
        />
      </li>
      <li
        v-for="(option, index) in options"
        :key="index"
        @click="selectOption(option)"
        class="px-4 py-2 cursor-pointer text-sm hover:bg-indigo-100"
      >
        {{ option.name }} <span v-if="option.details">({{ option.details }})</span>
        <span
          v-if="multiple && selectedOptions.some((sel) => sel.id === option.id)"
          class="text-indigo-500"
        >
          Selected
        </span>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { Option } from '@/composables/models/Option';
import { XIcon } from 'lucide-vue-next';
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
  defaultOptions: {
    type: Array as () => Option[],
    required: false,
    default: () => [],
  },
  defaultOption: {
    type: Object as () => Option | null,
    default: null,
    required: false,
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  classes: {
    type: String,
    required: false,
    default: '',
  },
  clearable: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['selected', 'search']);

const searchFilter = ref<string>('');
const filteredOptions = ref<Option[]>([...props.options]);
const selectedOptions = ref<Option[]>([...props.defaultOptions]);
const showDropdown = ref<boolean>(false);
const isFocused = ref<boolean>(false);

const handleFocus = (focus: boolean) => {
  isFocused.value = focus;
};

// Function to filter the options based on user input
const filterOptions = () => {
  if (searchFilter.value.length === 0 || searchFilter.value.length >= 3) {
    emit('search', searchFilter.value);
  }
};

// Function to handle option selection
const selectOption = (option: Option) => {
  // searchFilter.value = option.name;
  // emit('selected', option);
  // showDropdown.value = false;
  // filteredOptions.value = props.options;
  if (props.multiple) {
    const isSelected = selectedOptions.value.some((selected) => selected.id === option.id);
    if (isSelected) {
      selectedOptions.value = selectedOptions.value.filter((selected) => selected.id !== option.id);
    } else {
      selectedOptions.value.push(option);
    }
    emit('selected', selectedOptions.value);
    searchFilter.value = '';
  } else {
    searchFilter.value = option.name;
    selectedOptions.value = [option];
    emit('selected', option);
    showDropdown.value = false;
  }
  // filterOptions();
};

const removeOption = (option: Option) => {
  if (props.multiple) {
    selectedOptions.value = selectedOptions.value.filter((selected) => selected.id !== option.id);
    emit('selected', selectedOptions.value);
    filterOptions();
  }
};

const clearSingleSelection = () => {
  selectedOptions.value = [];
  searchFilter.value = '';
  emit('selected', null);
};

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
    if (newDefaultOption && !props.multiple) {
      searchFilter.value = newDefaultOption.name;
      emit('selected', newDefaultOption);
    }
  },
);

watch(
  () => props.defaultOptions,
  (newDefaultOptions) => {
    if (Array.isArray(newDefaultOptions) && props.multiple) {
      selectedOptions.value = [...newDefaultOptions];
      emit('selected', selectedOptions.value);
    }
  },
  { immediate: true },
);

onMounted(() => {
  if (props.defaultOptions.length) {
    if (props.multiple) {
      selectedOptions.value = [...props.defaultOptions];
    } else {
      searchFilter.value = props.defaultOptions[0].name;
    }
    emit('selected', props.multiple ? props.defaultOptions : props.defaultOptions[0]);
  }

  if (props.defaultOption) {
    searchFilter.value = props.defaultOption.name;
    emit('selected', props.defaultOption);
  }
});
</script>

<style scoped>
</style>
