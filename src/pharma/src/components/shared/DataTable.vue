<template>
  <div class="col-span-12 overflow-auto intro-y lg:overflow-visible">
    <Table class="border-spacing-y-[5px] border-separate -mt-2">
      <Table.Thead>
        <Table.Tr>
          <Table.Th
            v-for="col in columns"
            :key="col.key"
            class="border-b-0 whitespace-nowrap"
            :class="col.thClass"
          >
            {{ col.label }}
          </Table.Th>

          <Table.Th
            v-if="hasActions"
            class="text-center border-b-0 whitespace-nowrap"
            :class="actionsThClass"
          >
            {{ actionsLabel }}
          </Table.Th>
        </Table.Tr>
      </Table.Thead>

      <Table.Tbody>
        <Table.Tr
          v-for="(item, index) in items"
          :key="resolveKey(item, index)"
          @click="onRowClickInternal(item)"
          :class="rowClickable ? 'cursor-pointer' : ''"
        >
          <Table.Td
            v-for="col in columns"
            :key="col.key"
            :class="[baseTdClass, col.tdClass]"
          >
            <slot
              :name="`cell-${col.key}`"
              :item="item"
              :value="item?.[col.key]"
              :col="col"
            >
              {{ item?.[col.key] ?? '' }}
            </slot>
          </Table.Td>

          <Table.Td
            v-if="hasActions"
            @click.stop
            :class="[baseActionsTdClass, actionsTdClass]"
          >
            <slot name="actions" :item="item" />
          </Table.Td>
        </Table.Tr>

        <Table.Tr v-if="!loading && items.length === 0">
          <Table.Td :colspan="computedColspan" :class="[baseTdClass, emptyTdClass]">
            <slot name="empty">
              <div class="grid grid-cols-1 p-5 text-center">
                <span class="text-slate-400">{{ emptyText }}</span>
              </div>
            </slot>
          </Table.Td>
        </Table.Tr>

        <Table.Tr v-if="loading">
          <Table.Td :colspan="computedColspan" :class="[baseTdClass, emptyTdClass]">
            <slot name="loading">
              <div class="grid grid-cols-1 p-5 text-center">
                <span class="text-slate-400">Loading...</span>
              </div>
            </slot>
          </Table.Td>
        </Table.Tr>
      </Table.Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from "vue";
import Table from "@/components/Base/Table";

export type DataTableColumn<T> = {
  key: string;
  label: string;
  thClass?: string;
  tdClass?: string;
};

type Props<T> = {
  items: T[];
  columns: DataTableColumn<T>[];
  loading?: boolean;
  emptyText?: string;
  rowKey?: (item: T, index: number) => string | number;

  // actions column
  actionsLabel?: string;
  actionsThClass?: string;
  actionsTdClass?: string;

  // row click
  rowClickable?: boolean;
};

const props = withDefaults(defineProps<Props<any>>(), {
  loading: false,
  emptyText: "No Records",
  actionsLabel: "ACTIONS",
  rowClickable: true,
});

const emit = defineEmits<{
  (e: "row-click", item: any): void;
}>();

const slots = useSlots();
const hasActions = computed(() => !!slots.actions);

const computedColspan = computed(() => props.columns.length + (hasActions.value ? 1 : 0));

const baseTdClass =
  "box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r dark:bg-darkmode-600";

const baseActionsTdClass = [
  baseTdClass,
  "w-56",
  "before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200 before:dark:bg-darkmode-400",
].join(" ");

const emptyTdClass = "w-full";

const resolveKey = (item: any, index: number) => {
  if (props.rowKey) return props.rowKey(item, index);
  return item?.id ?? index;
};

const onRowClickInternal = (item: any) => {
  if (!props.rowClickable) return;
  emit("row-click", item);
};
</script>
