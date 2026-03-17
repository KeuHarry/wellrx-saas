<template>
  <!-- BEGIN: Top Bar -->
  <div class="relative z-[51] flex h-[67px] items-center border-b border-slate-200">
    <!-- BEGIN: Breadcrumb -->
    <Breadcrumb class="hidden mr-auto -intro-x sm:flex">
      <Breadcrumb.Link to="/">Application</Breadcrumb.Link>
      <Breadcrumb.Link to="/" :active="true"> Dashboard </Breadcrumb.Link>
    </Breadcrumb>
    <!-- END: Breadcrumb -->
    <!-- BEGIN: Notifications -->
    <Popover class="mr-auto intro-x sm:mr-6">
      <Popover.Button
        class="relative text-slate-600 outline-none block before:content-[''] before:w-[8px] before:h-[8px] before:rounded-full before:absolute before:top-[-2px] before:right-0 before:bg-danger"
      >
        <Lucide icon="Bell" class="w-5 h-5 dark:text-slate-500" />
      </Popover.Button>
      <Popover.Panel class="w-[280px] sm:w-[350px] p-5 mt-2">
        <div class="mb-5 font-medium">Notifications</div>
        <button
          type="button"
          class="mb-4 text-xs text-primary hover:underline"
          @click="sendTestNotification"
        >
          Envoyer une notification test (Reverb)
        </button>
        <p v-if="notifications.length === 0" class="text-sm text-slate-500">
          Aucune notification. Cliquez sur le bouton ci‑dessus pour en recevoir une.
        </p>
        <div
          v-for="(notification, nKey) in notifications"
          :key="nKey"
          :class="['cursor-pointer relative flex items-center', { 'mt-5': nKey }]"
        >
          <div class="ml-2 overflow-hidden">
            <div class="flex items-center">
              <span class="mr-5 font-medium truncate">
                {{ notification.title }}
              </span>
              <div class="ml-auto text-xs text-slate-400 whitespace-nowrap">
                {{ formatTimestamp(notification.timestamp) }}
              </div>
            </div>
            <div class="w-full truncate text-slate-500 mt-0.5">
              {{ notification.message }}
            </div>
          </div>
        </div>
      </Popover.Panel>
    </Popover>
    <!-- END: Notifications  -->
    <!-- BEGIN: Account Menu -->
    <Menu>
      <Menu.Button
        class="block w-8 h-8 overflow-hidden rounded-full shadow-lg image-fit zoom-in intro-x"
      >
        <img alt="wellrx user" src="@/assets/images/user-avatar.png" />
      </Menu.Button>
      <Menu.Items class="w-56 mt-px text-white bg-primary">
        <Menu.Header class="font-normal">
          <div class="font-medium">{{ user.username }}</div>
          <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">
            {{ user.role }}
          </div>
        </Menu.Header>
        <Menu.Divider class="bg-white/[0.08]" />
        <Menu.Item class="hover:bg-white/5">
          <Lucide icon="User" class="w-4 h-4 mr-2" /> Profile
        </Menu.Item>
        <Menu.Item class="hover:bg-white/5">
          <Lucide icon="HelpCircle" class="w-4 h-4 mr-2" /> Help
        </Menu.Item>
        <Menu.Divider class="bg-white/[0.08]" />
        <Menu.Item class="hover:bg-white/5" @click="authStore.logoutApi()">
          <Lucide icon="ToggleRight" class="w-4 h-4 mr-2" /> Logout
        </Menu.Item>
      </Menu.Items>
    </Menu>
  </div>
  <!-- END: Top Bar -->
</template>

<script setup lang="ts">
import type { NotificationItem } from '@/composables/useNotifications';
import { useSendTestNotification } from '@/composables/api/broadcasting/useSendTestNotification';
import { useNotifications } from '@/composables/useNotifications';
import useAuthStore from '@/stores/auth';
import useTenantStore from '@/stores/tenant';
import Breadcrumb from '@/components/Base/Breadcrumb';
import { Menu, Popover } from '@/components/Base/Headless';
import Lucide from '@/components/Base/Lucide';
import { computed, ref, watch } from 'vue';

const authStore = useAuthStore();
const tenantStore = useTenantStore();
const user = computed(() => {
  const data = sessionStorage.getItem('user');
  return data ? JSON.parse(data) : null;
});

const notifications = ref<NotificationItem[]>([]);
const hasSubscribed = ref(false);

watch(
  () => [user.value?.id, tenantStore.getTenantUrl] as const,
  ([userId, tenantUrl]) => {
    if (hasSubscribed.value || !userId || !tenantUrl) return;
    hasSubscribed.value = true;
    try {
      const { notifications: incoming } = useNotifications(userId);
      watch(
        incoming,
        (val) => {
          notifications.value = val ?? [];
        },
        { immediate: true, deep: true }
      );
    } catch {
      hasSubscribed.value = false;
    }
  },
  { immediate: true }
);

async function sendTestNotification() {
  try {
    await useSendTestNotification();
  } catch (e) {
    console.error('Test Reverb:', e);
  }
}

function formatTimestamp(ts: Date): string {
  return ts instanceof Date ? ts.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : String(ts);
}
</script>
