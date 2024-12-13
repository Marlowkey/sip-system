<script setup>
import { computed, ref, defineProps } from 'vue'
import { format, parse } from 'date-fns'
import { mdiEye, mdiTrashCan, mdiFileEditOutline, mdiDownload, mdiAccountFileText  } from '@mdi/js'
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
const items = computed(() => props.document ? props.document : [])
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
    <CardBox has-table>
    <div class="relative overflow-x-auto">
        <table class="w-full mb-2 text-left rtl:text-right">
            <thead class="text-gray-700">
                <tr class="border-b">
                    <th scope="col" class="px-4 py-3">Title</th>
                    <th scope="col" class="px-4 py-3">Due on</th>
                    <th scope="col" class="px-4 py-3 text-center">Action</th>
                    <th scope="col" class="px-4 py-3 text-center">Completed</th>
                </tr>
            </thead>
            <tbody class="font-medium text-gray-600">
                <tr v-for="document in itemsPaginated" :key="document.id"
                    class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td data-label="Title" scope="row" class="px-4 py-4">
                        {{ document.title }}
                    </td>
                    <td data-label="Due on" class="text-red-900 px-4 py-1">
                        {{ formatDueDate(document.due_date) }}
                    </td>
                    <td class="px-4 py-1">
                        <BaseButtons type="justify-center" no-wrap>
                            <BaseButton label="Description" roundedFull color="blue" :icon="mdiEye" small
                                @click="viewDocument(document)" />
                            <BaseButton label="File" v-if="document.file_path" roundedFull color="teal"
                                :icon="mdiDownload" small :href="`/storage/${document.file_path}`" target="_blank"/>
                            <BaseButton label="Edit" roundedFull color="yellow" :icon="mdiFileEditOutline" small
                                :href="route('documents.edit', { id: document.id })" />
                                <BaseButton label="Submission" roundedFull color="primary" :icon="mdiAccountFileText " small
                                :href="route('documents.show',{ id: document.id })" />
                        </BaseButtons>
                    </td>
                    <td data-label="Completed" class="px-2 text-center py-1">
                        <p  class="text-blue-600 hover:underline w-full">
                            {{ document.completed }}/{{ document.number_of_users }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="p-3 border-t border-gray-100 lg:px-6 dark:border-slate-800">
        <BaseLevel>
            <BaseButtons>
                <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage" :label="page + 1"
                    :color="page === currentPage ? 'lightDark' : 'whiteDark'" small @click="currentPage = page" />
            </BaseButtons>
            <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
        </BaseLevel>
    </div>
</CardBox>
</template>
