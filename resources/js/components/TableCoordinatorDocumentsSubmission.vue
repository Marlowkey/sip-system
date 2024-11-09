<script setup>
import { computed, ref, defineProps } from 'vue'
import { format, parse } from 'date-fns'
import { mdiEye, mdiTrashCan, mdiFileEditOutline, mdiDownload } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'
import { useForm } from '@inertiajs/vue3'
import FormControl from './FormControl.vue'
import CardBox from './CardBox.vue'

const props = defineProps({
    classBlocks: Array,
    document: {
        type: Object,
        required: true,
    },
    students: {
        type: Array,
        required: true,
    }
})



const user = computed(() => usePage().props.auth.user)
const items = computed(() => props.students ? props.students : [])
const isModalActive = ref(false)
const isModalDangerActive = ref(false)
const perPage = ref(10)
const currentPage = ref(0)
const checkedRows = ref([])
const currentDescription = ref('')
const searchTerm = ref("")
const classBlock = ref("")


const form = useForm({
    user_id: user.value.id,
    document_id: null,
    is_completed: false,
})

const itemsPaginated = computed(() => {
let filteredItems = items.value

if (searchTerm.value) {
    filteredItems = filteredItems.filter(user =>
        user.first_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        user.last_name.toLowerCase().includes(searchTerm.value.toLowerCase())
    )
}

if (classBlock.value) {
        filteredItems = filteredItems.filter(user => user.block === classBlock.value)
    }

return filteredItems.slice(perPage.value * currentPage.value, perPage.value * (currentPage.value + 1))
})

const numPages = computed(() => Math.ceil(items.value.length / perPage.value))

const currentPageHuman = computed(() => currentPage.value + 1)

const pagesList = computed(() => {
const pagesList = []

for (let i = 0; i < numPages.value; i++) {
    pagesList.push(i)
}

return pagesList
})


const formatDueDate = (dueDate) => {
    if (!dueDate) return 'No date';

    const parsedDate = parse(dueDate, 'yyyy-MM-dd', new Date());

    if (isNaN(parsedDate)) {
        return 'Invalid date';
    }

    return format(parsedDate, 'MMMM do, yyyy');
}

</script>
<template>
    <div class="p-2 m-2">
        <div class="flex flex-col items-center justify-between mb-4 lg:flex-row">
            <div class="flex flex-col items-center w-1/2 mb-4 space-x-0 lg:flex-row lg:space-x-4 lg:mb-0">
                <label for="block" class="text-lg font-medium">Block:</label>
                <FormControl :options="classBlocks" v-model="classBlock" label="Class Block" :icon="mdiFilterCheck"
                    placeholder="Select a Class Block" class="text-sm font-medium " />
            </div>
            <div class="flex items-center space-x-4">
                <FormControl v-model="searchTerm" type="text" placeholder="Search user..." :icon="mdiAccountSearch"
                    class="w-full max-w-xs text-sm font-medium" />
            </div>
        </div>
    </div>
    <CardBox has-table>
        <div class="relative m-4 overflow-x-auto">
            <table class="w-full text-left text-gray-800 rtl:text-right">
                <thead class="text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-300">
                    <tr class="border-b">
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3 text-center">Completion Status</th>
                    </tr>
                </thead>
                <tbody class="font-medium text-gray-600">
                    <tr v-for="student in itemsPaginated" :key="student.id" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-4 py-2">
                            {{ student.first_name }} {{ student.last_name }}
                        </td>
                        <td class="px-4 py-2">

                            <BaseButton
    v-if="student.file_path"
    roundedFull
    color="teal"
    :icon="mdiDownload"
    small
    :href="route('student-documents.download', { document: document.id, user: student.id })"
    label="Download"
/>

                        </td>
                        <td class="px-4 py-2 text-center">
                            <span :class="{'text-green-500': student.is_completed, 'text-red-500': !student.is_completed}">
                                {{ student.is_completed ? 'Completed' : 'Pending' }}
                            </span>
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
