<template>
  <Dialog size="lg" :open="show">
    <Dialog.Panel>
      <div class="grid grid-cols-1 gap-4 p-12">
        <div class="flex flex-col justify-center items-center">
          <Lucide icon="AlertTriangle" class="w-1/2 h-1/2 mr-2 text-slate-200" />
          <h2 class="text-center text-xl">You need to open the cash register</h2>
        </div>
      </div>
      <Dialog.Footer>
        <Button
          type="button"
          variant="outline-secondary"
          @click="goToCashRegister"
          class="w-20 mr-1"
        >
          Cancel
        </Button>
        <Button variant="warning" type="button" @click="openCashRegisterModal()" class="w-20"
          >Open</Button
        >
      </Dialog.Footer>
    </Dialog.Panel>
  </Dialog>
  <OpenCloseCashRegisterModal ref="cashRegisterModal" @submit="openCashRegister" />
</template>

<script setup lang="ts">
import { Dialog } from '@/components/Base/Headless';
import Button from '@/components/Base/Button';
import Lucide from '@/components/Base/Lucide';
import { computed, ref } from 'vue';
import { capitalizeFirstLetter } from '@/utils/helper';
import OpenCloseCashRegisterModal from './OpenCloseCashRegisterModal.vue';
import { useRoute, useRouter } from 'vue-router';
import useCashRegisterStore from '@/stores/cashRegister';
import CashRegisterStatus from '@/enums/cashRegisterStatus';

const route = useRoute();
const router = useRouter();

const cashRegisterModal = ref<InstanceType<typeof OpenCloseCashRegisterModal> | null>(null);

const show = ref(false);

// STORE
const cashRegisterStore = useCashRegisterStore();

// COMPUTED
const cashRegister = computed(() => {
  const data = localStorage.getItem('cashRegister');
  const cashRegister: { id: number; status: number } | null = data ? JSON.parse(data) : null;

  return cashRegister;
});

const openCashRegisterModal = () => {
  cashRegisterModal.value?.openCashRegisterModal(cashRegister.value ? cashRegister.value.id : null);
};

const openCashRegisterWarningModal = () => {
  show.value = true;
};

const goToCashRegister = () => {
  router.push({
    name: 'cash-register',
    params: { identifier: route.params.identifier },
  });
};

const openCashRegister = async () => {
  await cashRegisterStore.openCashRegisterApi({
    status: CashRegisterStatus.OPEN,
  });

  if (cashRegister.value) {
    show.value = false;
  }
};

defineExpose({
  openCashRegisterWarningModal,
});
</script>
