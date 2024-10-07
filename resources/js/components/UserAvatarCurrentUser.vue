<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import UserAvatar from '@/components/UserAvatar.vue'

const user = computed(() => usePage().props.auth.user)
const userName = computed(() => `${user.value.first_name} ${user.value.last_name}`)
const userAvatar = computed(() => user.value.avatar)
</script>

<template>
  <UserAvatar :username="userName" api="initials" :avatar="userAvatar">
    <slot />
  </UserAvatar>
</template>

