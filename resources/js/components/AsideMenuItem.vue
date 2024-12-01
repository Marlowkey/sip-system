<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { getButtonColor } from '@/colors.js'
import BaseIcon from '@/components/BaseIcon.vue'
import { useDarkModeStore } from '@/stores/darkMode.js'

const darkModeStore = useDarkModeStore() // Initialize the store

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  isDropdownList: Boolean
})

const itemHref = computed(() => (props.item.route ? route(props.item.route) : props.item.href))

const activeInactiveStyle = computed(() =>
  props.item.route && route().current(props.item.route)
    ? darkModeStore.isEnabled
      ? `${darkModeStore.asideMenuItemActiveStyle} underline` // Add underline for dark mode
      : 'underline text-blue-900 font-bold' // Blue text for light mode
    : ''
)


const hasColor = computed(() => props.item && props.item.color)

const asideMenuItemActiveStyle = computed(() =>
  hasColor.value ? '' : 'aside-menu-item-active font-bold'
)

const componentClass = computed(() => [
  props.isDropdownList ? 'py-3 px-6 text-sm' : 'py-3',
  hasColor.value
    ? getButtonColor(props.item.color, false, true)
    : `aside-menu-item dark:text-slate-300 dark:hover:text-white`
])

</script>

<template>
    <li>
      <component
        :is="item.route ? Link : 'a'"
        :href="itemHref"
        :target="item.target ?? null"
        class="flex cursor-pointer"
        :class="componentClass"
      >
        <BaseIcon
          v-if="item.icon"
          :path="item.icon"
          class="flex-none"
          :class="activeInactiveStyle"
          w="w-16"
          :size="18"
        />
        <span
          class="grow text-ellipsis line-clamp-1"
          :class="activeInactiveStyle"
        >{{ item.label }}</span>
      </component>
    </li>
  </template>
