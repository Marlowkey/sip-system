<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiEye, mdiTrashCan } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  checkable: Boolean,
  document: {
    type: Array,
    required: true,
}
})

const user = computed(() => usePage().props.auth.user)
const items = computed(() => props.document)
const isModalActive = ref(false)
const isModalDangerActive = ref(false)
const perPage = ref(8)
const currentPage = ref(0)
const checkedRows = ref([])
const currentDescription = ref('')

const form = useForm({
  user_id: user.value.id,
  document_id: null,
  is_completed: false,
})

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
  // Check if any user has completed the document
  return document.users.some(user => user.pivot.is_completed);
};
const updateCompletionStatus = (document, event) => {
  form.document_id = document.id
  form.is_completed = event.target.checked

  form.post(route('student-document.update'), {
    onFinish: () => {
      document.is_completed = form.is_completed
    },
    preserveScroll: true,
  })
}


</script>

<template>
    <CardBoxModal v-model="isModalActive" title="Description">
      <p>{{ currentDescription }}</p>
    </CardBoxModal>


    <table class="min-w-full ">
      <thead>
        <tr class="py-6">
          <th class="py-6 mx-16 ">Title</th>
          <th class="py-6 mx-16">Due on</th>
          <th class="py-6 mx-2">Action</th>
          <th class="" v-if="checkable" />

        </tr>
      </thead>
      <tbody>
        <tr v-for="document in itemsPaginated" :key="document.id">
          <td data-label="Title" class="px-16">
            {{ document.title }}
          </td>
          <td data-label="Due on" class="px-8">
            {{ document.due_date }}
          </td>
          <td class="before:hidden lg:w-1 whitespace-nowrap text-center px-6">
            <BaseButtons type="justify-start lg:justify-end" no-wrap>
              <BaseButton color="info" :icon="mdiEye" small @click="viewDocument(document)"/>
            </BaseButtons>
          </td>
          <td v-if="checkable" class="px-6 text-center">
          <input
            type="checkbox"
            :checked="isCompleted(document)"
            @change="event => updateCompletionStatus(document, event)"
          />
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
