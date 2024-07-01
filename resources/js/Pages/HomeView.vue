<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import StudentView from './Student/StudentView.vue'
import CoordinatorView from './Coordinator/CoordinatorView.vue'
import AdminView from './Admin/AdminView.vue'

const props = defineProps({
  user: Object, // Authenticated user passed as a prop from Inertia
  users: Array  // Users passed as a prop from Inertia
})

const currentDashboard = computed(() => {
  switch (props.user.role) {
    case 'student':
      return StudentView
    case 'coordinator':
      return CoordinatorView
    case 'admin':
      return AdminView
    default:
      return null
  }
})
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Home" />
    <component :is="currentDashboard" :users="users" />
  </LayoutAuthenticated>
</template>
