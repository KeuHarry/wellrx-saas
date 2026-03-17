<template>
  <div
    v-if="isLoading"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[99]"
  >
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
  </div>
</template>

<script setup lang="ts">
import { useLoadingStore } from '@/stores/loading';
import { computed } from 'vue';

const loadingStore = useLoadingStore();
const isLoading = computed(() => loadingStore.getLoading);
</script>
