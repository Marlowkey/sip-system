<script setup>
import { computed } from 'vue'
import { useDarkModeStore } from '@/stores/darkMode.js'
import { gradientBgPurplePink, gradientBgDark, gradientBgBlueIndigo } from '@/colors.js'

const props = defineProps({
  bg: {
    type: String,
    required: true,
    validator: (value) => ['purplePink', 'pinkRed', 'dark'].includes(value)
  }
})

const colorClass = computed(() => {

  switch (props.bg) {
    case 'purplePink':
      return gradientBgPurplePink
    case 'blue':
      return gradientBgBlueIndigo
    case 'dark':
    return gradientBgDark
  }

  return ''
})
</script>

<template>
  <div class="flex min-h-screen items-center justify-center" :class="colorClass">
    <slot card-class="w-11/12 md:w-7/12 lg:w-6/12 xl:w-4/12 shadow-2xl" />
  </div>
</template>
