<template>
  <input type="text" :value="modelValue" @input="handleNumericInput" :placeholder="placeholder" />
</template>

<script setup lang="ts">
import { defineProps, defineEmits, withDefaults } from 'vue';

interface Props {
  modelValue?: string | number;
  placeholder?: string;
  allowDecimals?: boolean;
  allowNegatives?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  placeholder: 'Enter numbers only',
  allowDecimals: false,
  allowNegatives: false,
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void;
}>();

const handleNumericInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  let cleanedValue = target.value;

  // Base regex pattern
  let regexPattern = '[^0-9]';

  // Handle decimal points
  if (props.allowDecimals) {
    regexPattern = '[^0-9.]';
    cleanedValue = cleanedValue
      .replace(/\.+/g, '.') // Replace multiple dots with a single dot
      .replace(/^\./g, ''); // Remove leading dot
  }

  // Handle negative numbers
  if (props.allowNegatives) {
    regexPattern = '[^0-9.-]';
    cleanedValue = cleanedValue
      .replace(/(?!^-)-/g, '') // Only allow a minus at the start
      .replace(/(?<=^-.*)-/g, ''); // Prevent multiple minuses
  }

  // Remove invalid characters
  cleanedValue = cleanedValue.replace(new RegExp(regexPattern, 'g'), '');

  // Emit the cleaned value
  emit('update:modelValue', cleanedValue);

  // Update the input value
  target.value = cleanedValue;
};
</script>
