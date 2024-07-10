<script setup>
import { computed, ref, defineProps } from 'vue'
import { format, parse } from 'date-fns'
import { mdiEye, mdiTrashCan, mdiFileLinkOutline } from '@mdi/js'
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
const perPage = ref(10)
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

const formatDueDate = (dueDate) => {
    if (!dueDate) return 'No date';

    // Ensure the date string matches the expected format
    const parsedDate = parse(dueDate, 'yyyy-MM-dd', new Date());

    // Check if parsing was successful
    if (isNaN(parsedDate)) {
        return 'Invalid date';
    }

    return format(parsedDate, 'MMMM do, yyyy');
}



</script>

<template>
    <CardBoxModal v-model="isModalActive" title="Description">
      <p>{{ currentDescription }}</p>
    </CardBoxModal>


    <table class="m-auto p-auto">
      <thead>
        <tr class="py-6">
          <th class="py-2 ">Title</th>
          <th class="py-2">Due on</th>
          <th class="py-6 mx-2">Action</th>
          <th class="py-6 mx-2"># of Completed</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="document in itemsPaginated" :key="document.id">
          <td data-label="Title" class="px-8">
            {{ document.title }}
          </td>
          <td data-label="Due on" class="px-8">
            {{ formatDueDate(document.due_date) }}
          </td>
          <td class="before:hidden lg:w-1 whitespace-nowrap text-center px-6">
            <BaseButtons type="justify-start lg:justify-end" no-wrap>
              <BaseButton roundedFull color="contrast" :icon="mdiEye" small @click="viewDocument(document)"/>
              <BaseButton roundedFull color="contrast" :icon="mdiFileLinkOutline" small :href="route('documents.edit', { id: document.id })" />
            </BaseButtons>
          </td>
          <td data-label="Completed" class="text-center px-8">
            {{ document.completed}}/{{ document.number_of_users }}
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
