<template>
  <div
    v-if="showPagination"
    class="flex flex-wrap justify-between items-center col-span-12 intro-y sm:flex-row sm:flex-nowrap"
  >
    <Pagination>
      <Pagination.Link @click="goToFirstPage">
        <Lucide icon="ChevronsLeft" class="w-4 h-4" />
      </Pagination.Link>
      <Pagination.Link @click="goToPreviousPage">
        <Lucide icon="ChevronLeft" class="w-4 h-4" />
      </Pagination.Link>
      <Pagination.Link v-if="showDotsBefore">...</Pagination.Link>
      <Pagination.Link
        v-for="page in visiblePages"
        :key="page"
        :active="page === currentPage"
        @click="changePage(page)"
      >
        {{ page }}
      </Pagination.Link>
      <Pagination.Link v-if="showDotsAfter">...</Pagination.Link>
      <Pagination.Link @click="goToNextPage">
        <Lucide icon="ChevronRight" class="w-4 h-4" />
      </Pagination.Link>
      <Pagination.Link @click="goToLastPage">
        <Lucide icon="ChevronsRight" class="w-4 h-4" />
      </Pagination.Link>
    </Pagination>
    <div class="hidden mx-auto md:block text-slate-500">
      Showing {{ startItemCount }} to {{ initialItemsPerPage }} of {{ totalItems }} entries
    </div>
    <FormSelect v-model="itemsPerPage" class="w-20 mt-3 !box sm:mt-0">
      <option v-for="(limit, lkey) in pageLimits" :key="lkey" :value="limit">
        {{ limit }}
      </option>
    </FormSelect>
  </div>
</template>
<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import Pagination from '@/components/Base/Pagination';
import { FormSelect } from '@/components/Base/Form';
import Lucide from '@/components/Base/Lucide';
import { limits } from '@/constants/pagination';

const props = defineProps({
  totalItems: {
    type: Number,
    required: true,
  },
  initialPage: {
    type: Number,
    default: 1,
  },
  initialItemsPerPage: {
    type: Number,
    default: 10,
  },
  pageLimits: {
    type: Array as () => number[],
    default: () => limits,
  },
});

const emit = defineEmits(['page-change', 'items-per-page-change']);

const currentPage = ref(props.initialPage);
const itemsPerPage = ref(props.initialItemsPerPage);
const totalPages = computed(() => Math.ceil(props.totalItems / itemsPerPage.value));
const showPagination = computed(() => props.totalItems > 10);

const visiblePages = computed(() => {
  const pages = [];
  const maxVisiblePages = 5;
  const half = Math.floor(maxVisiblePages / 2);

  let start = Math.max(1, currentPage.value - half);
  let end = Math.min(totalPages.value, currentPage.value + half);

  if (end - start + 1 < maxVisiblePages) {
    if (start === 1) {
      end = Math.min(start + maxVisiblePages - 1, totalPages.value);
    } else if (end === totalPages.value) {
      start = Math.max(end - maxVisiblePages + 1, 1);
    }
  }

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }

  return pages;
});

const startItemCount = computed(() => (props.totalItems > 0 ? 1 : 0));
const showDotsBefore = computed(() => currentPage.value > 3);
const showDotsAfter = computed(() => currentPage.value < totalPages.value - 2);

const changePage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    emit('page-change', page);
  }
};

const goToFirstPage = () => changePage(1);
const goToPreviousPage = () => changePage(currentPage.value - 1);
const goToNextPage = () => changePage(currentPage.value + 1);
const goToLastPage = () => changePage(totalPages.value);

watch(itemsPerPage, (newLimit) => {
  emit('items-per-page-change', newLimit);
  changePage(1);
});
</script>
