<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiEye, mdiTrashCan, mdiDownload } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'
import { useForm } from '@inertiajs/vue3'
import NotificationBar from './NotificationBar.vue'
import { format, parse } from 'date-fns';


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
const perPage = ref(8)
const currentPage = ref(0)
const checkedRows = ref([])
const currentDescription = ref('')

let form = useForm({
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
    <div class="relative overflow-x-auto">
    <table class="my-2 w-full text-gray-800 text-left rtl:text-right">
        <thead class="text-gray-700">
            <tr class="border-b">
                <th scope="col" class="px-4 py-3">Title</th>
                <th scope="col" class="px-4 py-3">Due on</th>
                <th scope="col" class="px-4 py-3">Action</th>
                <th scope="col" class="px-4 py-3 text-center" v-if="checkable">Mark as Done</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 font-medium">
            <tr v-for="document in itemsPaginated" :key="document.id" class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td data-label="Title" scope="row" class="px-4 py-1">
                    {{ document.title }}
                </td>
                <td data-label="Due on" class="px-4 py-1 text-red-800">
                    {{ formatDueDate(document.due_date) }}
                </td>
                <td class="px-4 py-1 whitespace-nowrap">
                    <BaseButtons type="justify-start" no-wrap>
                        <BaseButton  roundedFull color="blue" :icon="mdiEye" small @click="viewDocument(document)" />
                        <BaseButton v-if="document.file_path" roundedFull color="teal" :icon="mdiDownload" small :href="route('documents.download', { id: document.id })" />
                    </BaseButtons>
                </td>
                <td v-if="checkable" class="px-4 py-1 text-center">
                    <input type="checkbox" :checked="isCompleted(document)"
                        @change="event => updateCompletionStatus(document, event)"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                </td>
            </tr>
        </tbody>
    </table>
    </div>
    <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
        <BaseLevel>
            <BaseButtons>
                <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage" :label="page + 1"
                    :color="page === currentPage ? 'lightDark' : 'whiteDark'" small @click="currentPage = page" />
            </BaseButtons>
            <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
        </BaseLevel>
    </div>
</template>
