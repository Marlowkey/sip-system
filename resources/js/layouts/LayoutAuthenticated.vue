<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import { ref, computed, watchEffect } from 'vue'
import menuNavBar from '@/menuNavBar.js'
import { useDarkModeStore } from '@/stores/darkMode.js'
import BaseIcon from '@/components/BaseIcon.vue'
import FormControl from '@/components/FormControl.vue'
import NavBar from '@/components/NavBar.vue'
import NavBarItemPlain from '@/components/NavBarItemPlain.vue'
import AsideMenu from '@/components/AsideMenu.vue'
import FooterBar from '@/components/FooterBar.vue'

// Retrieve user role
const user = computed(() => usePage().props.auth.user)
const userRole = computed(() => user.value.role)

const menuAside = ref([])

// Dynamic import based on user role
const loadMenuAside = async (role) => {
  switch (role) {
    case 'admin':
      menuAside.value = (await import('@/menuAsideAdmin.js')).default
      break
    case 'coordinator':
      menuAside.value = (await import('@/menuAsideCoordinator.js')).default
      break
    default:
      menuAside.value = (await import('@/menuAsideStudent.js')).default
  }
}

// Load the menu when the user role changes
watchEffect(() => {
  if (userRole.value) {
    loadMenuAside(userRole.value)
  }
})

const layoutAsidePadding = 'xl:pl-60'
const darkModeStore = useDarkModeStore()

const isAsideMobileExpanded = ref(false)
const isAsideLgActive = ref(false)

router.on('navigate', () => {
  isAsideMobileExpanded.value = false
  isAsideLgActive.value = false
})

const menuClick = (event, item) => {
  if (item.isLogout) {
    router.post(route('logout'))
  }
}

</script>

<template>
  <div
    :class="{
      'overflow-hidden lg:overflow-visible': isAsideMobileExpanded
    }"
  >
    <div
      :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
      class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
    >
      <NavBar
        :menu="menuNavBar"
        :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
        @menu-click="menuClick"
      >
        <NavBarItemPlain
          display="flex lg:hidden"
          @click.prevent="isAsideMobileExpanded = !isAsideMobileExpanded"
        >
          <BaseIcon :path="isAsideMobileExpanded ? mdiBackburger : mdiForwardburger" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain display="hidden lg:flex xl:hidden" @click.prevent="isAsideLgActive = true">
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain use-margin>
          <FormControl placeholder="Search (ctrl+k)" ctrl-k-focus transparent borderless />
        </NavBarItemPlain>
      </NavBar>
      <AsideMenu
        :is-aside-mobile-expanded="isAsideMobileExpanded"
        :is-aside-lg-active="isAsideLgActive"
        :menu="menuAside"
        @menu-click="menuClick"
        @aside-lg-close-click="isAsideLgActive = false"
      />
      <slot />
      <FooterBar>
      </FooterBar>
    </div>
  </div>
</template>
