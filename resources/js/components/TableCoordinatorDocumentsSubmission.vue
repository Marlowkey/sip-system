<script setup>
    import {
        computed,
        ref,
        defineProps
    } from 'vue'
    import {
        format,
        parse
    } from 'date-fns'
    import {
        mdiEye,
        mdiTrashCan,
        mdiFileEditOutline,
        mdiDownload
    } from '@mdi/js'
    import {
        router,
        usePage
    } from '@inertiajs/vue3'
    import CardBoxModal from '@/components/CardBoxModal.vue'
    import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
    import BaseLevel from '@/components/BaseLevel.vue'
    import BaseButtons from '@/components/BaseButtons.vue'
    import BaseButton from '@/components/BaseButton.vue'
    import UserAvatar from '@/components/UserAvatar.vue'
    import {
        useForm
    } from '@inertiajs/vue3'
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
    const selectedFile = ref('') // Store selected file URL for preview


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

    const submissionForm = useForm({
        user_id: null,
        document_id: null,
        is_completed: false,
    })

    const updateCompletionStatus = (document, event, userId) => {
        submissionForm.user_id = userId
        submissionForm.document_id = document.id
        submissionForm.is_completed = event.target.checked

        submissionForm.post(route('student-document.update'), {
            onFinish: () => {
                document.is_completed = submissionForm.is_completed
            },
            preserveScroll: true,
        })
    }

    // Function to open the PDF modal with the selected student's file
    const openPdfPreview = (filePath) => {
        selectedFile.value = `/storage/${filePath}`; // Set the file path to show in modal
        isModalActive.value = true; // Open the modal
    }

    // Function to close the modal
    const closePdfPreview = () => {
        isModalActive.value = false; // Close the modal
        selectedFile.value = ''; // Reset file path
    }
</script>
<template>
    <CardBoxModal v-if="isModalActive" @close="closePdfPreview" title="Document Preview">
        <iframe v-if="selectedFile" :src="selectedFile" class="w-full h-[500px]" frameborder="0"></iframe>
    </CardBoxModal>

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
                    <tr v-for="student in itemsPaginated" :key="student.id"
                        class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-4 py-2">
                            {{ student . first_name }} {{ student . last_name }}
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-4">
                                <!-- Added space-x-4 for more spacing between buttons -->
                                <BaseButton v-if="student.file_path" roundedFull color="blue" :icon="mdiEye"
                                    small @click="openPdfPreview(student.file_path)" label="Preview" />
                                <BaseButton v-if="student.file_path" roundedFull color="teal" :icon="mdiDownload"
                                    small :href="`/storage/${student.file_path}`" target="_blank" label="Download" />
                            </div>

                        </td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <input type="checkbox" :checked="student.is_completed"
                                    @change="event => updateCompletionStatus(document, event, student.id)" />
                                <span
                                    :class="{ 'text-green-500': student.is_completed, 'text-red-500': !student.is_completed }">
                                    {{ student . is_completed ? 'Completed' : 'Pending' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t border-gray-100 lg:px-6 dark:border-slate-800">
            <BaseLevel>
                <BaseButtons>
                    <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage"
                        :label="page + 1" :color="page === currentPage ? 'lightDark' : 'whiteDark'" small
                        @click="currentPage = page" />
                </BaseButtons>
                <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
            </BaseLevel>
        </div>
    </CardBox>

    <div v-if="isModalActive" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-4 w-full max-w-4xl rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Document Preview</h2>
                <button @click="closePdfPreview" class="text-gray-600 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div>
                <!-- PDF Preview (using iframe) -->
                <iframe v-if="selectedFile" :src="selectedFile" class="w-full h-[500px]" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</template>
