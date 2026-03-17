<template>
  <DataTable
    :items="products"
    :columns="columns"
    :loading="loading"
    :rowKey="(p) => p.id"
    @row-click="(p) => emit('edit', p.id)"
  >
    <template #cell-productCode="{ item }">
      {{ item.productCode }}
    </template>

    <template #cell-label="{ item }">
      {{ item.label }}
    </template>

    <template #cell-type="{ item }">
      {{ item.isPrescription ? RX : OTC }}
    </template>

    <template #cell-retailCost="{ item }">
      {{ formatPrice(item.retailCost) }}
    </template>

    <template #cell-qoh="{ item }">
      {{ item.qoh }}
    </template>

    <template #cell-shelf="{ item }">
      {{ item.shelf?.name ?? "" }}
    </template>

    <template #cell-status="{ item }">
      <span
        class="text-xs border rounded-full px-4 py-1 bg-opacity-20 border-opacity-5"
        :class="[
          item.isActive
            ? 'bg-green-600 border-green-600 text-green-600'
            : 'bg-red-600 border-red-600 text-red-600',
        ]"
      >
        {{ item.isActive ? ACTIVE : INACTIVE }}
      </span>
    </template>

    <template #actions="{ item }">
      <div class="flex items-center justify-center">
        <Tippy content="Delete">
          <a
            class="flex items-center text-danger"
            href="#"
            @click.prevent="emit('delete', item)"
          >
            <Lucide icon="Trash2" class="w-4 h-4 mr-1" />
          </a>
        </Tippy>
      </div>
    </template>
  </DataTable>
</template>

<script setup lang="ts">
import { computed } from "vue";
import DataTable, { type DataTableColumn } from "@/components/shared/DataTable.vue";
import Tippy from "@/components/Base/Tippy";
import Lucide from "@/components/Base/Lucide";

import { OTC, RX } from "@/constants/productType";
import { ACTIVE, INACTIVE } from "@/constants/status";
import { formatPrice } from "@/utils/helper";
import type { Product } from "@/composables/models/Product";

const props = defineProps<{
  products: Product[];
  loading?: boolean;
}>();

const emit = defineEmits<{
  (e: "edit", productId: number): void;
  (e: "delete", product: Product): void;
}>();

const columns = computed<DataTableColumn<Product>[]>(() => [
  { key: "productCode", label: "CODE", tdClass: "cursor-pointer" },
  { key: "label", label: "NAME", tdClass: "cursor-pointer" },
  { key: "type", label: "TYPE", tdClass: "cursor-pointer" },
  { key: "retailCost", label: "RETAIL PRICE", tdClass: "cursor-pointer" },
  { key: "qoh", label: "QOH", tdClass: "cursor-pointer" },
  { key: "shelf", label: "SHELF", tdClass: "cursor-pointer" },
  { key: "status", label: "STATUS", tdClass: "cursor-pointer" },
]);
</script>
