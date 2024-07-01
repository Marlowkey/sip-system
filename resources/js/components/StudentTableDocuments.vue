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
  documents: {
    type: Array,
    required: true,
}
})


const items = computed(() => props.documents)
const isModalActive = ref(false)
const isModalDangerActive = ref(false)
const perPage = ref(8)
const currentPage = ref(0)
const checkedRows = ref([])
const currentDescription = ref('')

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

const viewDocument = (document) => {
  currentDescription.value = document.description  // Set the current document's description
  isModalActive.value = true
}

const isCompleted = (document) => {
  return document.users.length > 0 && document.users[0].pivot.is_completed
}
</script>

<template>
    <CardBoxModal v-model="isModalActive" title="Description">
      <p>{{ currentDescription }}</p>
    </CardBoxModal>


    <table class="min-w-full px-0">
      <thead>
        <tr class="py-6">
          <th v-if="checkable" />
          <th class="py-6">Title</th>
          <th>Due on</th>
          <th>Action</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="documents in itemsPaginated" :key="documents.id">
        <TableCheckboxCell v-if="checkable" @checked="checked($event, client)" :checked="isCompleted(documents)" />
          <td data-label="Title" class="text-center">
            {{ documents.title }}
          </td>
          <td data-label="Due on" class="text-center">
            {{ documents.due_date }}
          </td>
          <td class="before:hidden lg:w-1 whitespace-nowrap text-center px-6">
            <BaseButtons type="justify-start lg:justify-end" no-wrap>
              <BaseButton color="info" :icon="mdiEye" small @click="viewDocument(documents)"/>
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
