<script setup>
import { computed } from 'vue'

// Define the props
const props = defineProps({
  username: {
    type: String,
    required: true
  },
  avatar: {
    type: String,
    default: null
  },
  api: {
    type: String,
    default: 'avataaars'
  }
})

const getImageUrl = (path) => {
  return `/storage/${path}`;
}

const avatar = computed(() => {
  return props.avatar ? getImageUrl(props.avatar) : `https://api.dicebear.com/7.x/${props.api}/svg?seed=${props.username.replace(/[^a-z0-9]+/gi, '-')}.svg`;
});

const username = computed(() => props.username);
</script>

<template>
  <div>
    <img
      :src="avatar"
      :alt="username"
      class="rounded-full block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800 object-cover"
    />
    <slot />
  </div>
</template>
