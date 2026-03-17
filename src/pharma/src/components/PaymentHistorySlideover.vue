<template>
  <!-- BEGIN: Super Large Slide Over Content -->
  <Slideover size="lg" :open="smallSlideoverSizePreview">
    <Slideover.Panel>
      <a
        @click="
          (event: MouseEvent) => {
            event.preventDefault();
            setSmallSlideoverSizePreview(false);
          }
        "
        class="absolute top-0 left-0 right-auto mt-4 -ml-12"
        href="#"
      >
        <Lucide icon="X" class="w-8 h-8 text-white" />
      </a>
      <Slideover.Title class="px-5 py-3">
        <h2 class="mr-auto text-base font-medium">Payment History - {{ saleCode }}</h2>
      </Slideover.Title>
      <Slideover.Description>
        <div class="col-span-12 py-5 rounded-md shadow-sm">
          <Table>
            <Table.Thead variant="dark">
              <Table.Tr>
                <Table.Th class="whitespace-nowrap">Amount</Table.Th>
                <Table.Th class="whitespace-nowrap">Payment Method</Table.Th>
                <Table.Th class="whitespace-nowrap">Reference</Table.Th>
                <Table.Th class="whitespace-nowrap">Tendered</Table.Th>
                <Table.Th class="whitespace-nowrap">Change</Table.Th>
              </Table.Tr>
            </Table.Thead>
            <Table.Tbody>
              <Table.Tr v-for="(payment, ikey) in payments" :key="ikey">
                <Table.Td>{{ formatPrice(payment.amount) }}</Table.Td>
                <Table.Td>{{ getPaymentMethod(payment.paymentMethod) }}</Table.Td>
                <Table.Td>{{ payment.reference ?? '-' }}</Table.Td>
                <Table.Td>{{ formatPrice(payment.tendered) }}</Table.Td>
                <Table.Td>{{ formatPrice(payment.change) }}</Table.Td>
              </Table.Tr>
              <Table.Tr v-if="payments.length === 0">
                <Table.Td :colspan="10">
                  <div class="grid grid-cols-1 p-2 text-center">
                    <span class="text-slate-400">No Entry</span>
                  </div>
                </Table.Td>
              </Table.Tr>
            </Table.Tbody>
          </Table>
        </div>
      </Slideover.Description>
    </Slideover.Panel>
  </Slideover>
  <!-- END: Super Large Slide Over Content -->
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Lucide from '@/components/Base/Lucide';
import { Slideover } from '@/components/Base/Headless';
import { computed } from 'vue';
import { onMounted } from 'vue';
import useTenantStore from '@/stores/tenant';
import { useRoute } from 'vue-router';
import { SalePayment } from '@/composables/models/SalePayment';
import Table from '@/components/Base/Table';
import { formatPrice, getPaymentMethod } from '@/utils/helper';

const smallSlideoverSizePreview = ref(false);
const setSmallSlideoverSizePreview = (value: boolean) => {
  smallSlideoverSizePreview.value = value;
};

const saleCode = ref<string>('');
const payments = ref<SalePayment[]>([]);

const openSlideover = async (code: string, salePayments: SalePayment[]) => {
  saleCode.value = code;
  setSmallSlideoverSizePreview(true);
  payments.value = salePayments;
};

defineExpose({
  openSlideover,
});
</script>
