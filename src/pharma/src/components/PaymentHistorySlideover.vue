<template>
    <!-- BEGIN: Super Large Slide Over Content -->
    <Slideover size="md" :open="smallSlideoverSizePreview">
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
          <h2 class="mr-auto text-base font-medium">Payment History</h2>
        </Slideover.Title>
        <Slideover.Description>
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

const smallSlideoverSizePreview = ref(false);
const setSmallSlideoverSizePreview = (value: boolean) => {
    smallSlideoverSizePreview.value = value;
};

const openSlideover = async () => {
  setSmallSlideoverSizePreview(true);
};

defineExpose({
  openSlideover,
});

</script>