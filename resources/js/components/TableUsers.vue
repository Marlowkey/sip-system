<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiEye, mdiTrashCan } from '@mdi/js'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'

const props = defineProps({
  checkable: Boolean,
  users: {
    type: Array,
    required: true,
}
})


const items = computed(() => props.users)
const isModalActive = ref(false)
const isModalDangerActive = ref(false)
const perPage = ref(5)
const currentPage = ref(0)
const checkedRows = ref([])

const itemsPaginated = computed(() =>
  items.value.slice(perPage.value * currentPage.value, perPage.value * (currentPage.value + 1))
)

const numPages = computed(() => Math.ceil(items.value.length / perPage.value))

const currentPageHuman = computed(() => currentPage.value + 1)

const pagesList = computed(() => {
  const pagesList = []

  for (let i = 0; i < numPages.value; i++) {
    pagesList.push(i)
  }

  return pagesList
})

</script>

<template>
    <CardBoxModal v-model="isModalActive" title="Sample modal">
      <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
      <p>This is sample modal</p>
    </CardBoxModal>

    <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
      <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
      <p>This is sample modal</p>
    </CardBoxModal>

    <table class="min-w-full px-0">
      <thead>
        <tr class="py-6">
          <th v-if="checkable" />
          <th class="py-6">First Name</th>
          <th class="py-6">Last Name</th>
          <th>Email</th>
          <th>Host Training Establishment</th>
          <th>Action</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="user in itemsPaginated" :key="user.id">
          <td data-label="First Name" class="text-center">
            {{ user.first_name }}
          </td>
          <td data-label="Last Name" class="text-center">
            {{ user.last_name }}
          </td>
          <td data-label="Email" class="text-center">
            {{ user.email }}
          </td>
          <td data-label="Host Training Establishment" class="text-center">
          {{ user.host_training_establishment }}
        </td>
          <td class="before:hidden lg:w-1 whitespace-nowrap text-center px-6">
            <BaseButtons type="justify-start lg:justify-end" no-wrap>
              <BaseButton color="info" :icon="mdiEye" small @click="isModalActive = true" />
            </BaseButtons>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
      <BaseLevel>
        <BaseButtons>
          <BaseButton
            v-for="page in pagesList"
            :key="page"
            :active="page === currentPage"
            :label="page + 1"
            :color="page === currentPage ? 'lightDark' : 'whiteDark'"
            small
            @click="currentPage = page"
          />
        </BaseButtons>
        <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
      </BaseLevel>
    </div>
  </template>
