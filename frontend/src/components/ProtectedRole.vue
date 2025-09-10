<!-- components/ProtectedRole.vue -->
<template>
  <div v-if="canAccess">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useUserStore } from '../stores/userStore';
import { hasRole } from '../utils/roles';

interface Props {
  roles: string | string[];
}

// Явно указываем тип через defineProps
const props = defineProps<Props>();

const userStore = useUserStore();

const canAccess = computed(() => hasRole(userStore.user, props.roles));
</script>
