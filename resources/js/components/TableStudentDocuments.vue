<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiEye, mdiTrashCan, mdiDownload, mdiClose} from '@mdi/js'
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
import FormControl from './FormControl.vue'

const props = defineProps({
    document: {
        type: Array,
        required: true,
    },
    file: {
        type: Boolean,
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
const selectedFile = ref('') // Store selected file URL for preview
const isModalPreviewActive = ref(false)


let form = useForm({
    user_id: user.value.id,
    document_id: null,
    file: null,
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

const isSubmitted = (document) => {
    return document.users.some(user => user.pivot.file_path);
};

const isCompleted = (document) => {
    return document.users.some(user => user.pivot.is_completed);
};




const formatDueDate = (dueDate) => {
    if (!dueDate) return 'No date';

    const parsedDate = parse(dueDate, 'yyyy-MM-dd', new Date());

    if (isNaN(parsedDate)) {
        return 'Invalid date';
    }

    return format(parsedDate, 'MMMM do, yyyy');
}

const buttonLabel = (document) => {
    return isSubmitted(document) ? 'Update' : 'Submit';          // Show "Submit" if file selected, otherwise "Update"
}


const handleFileSelect = (document, event) => {
    const file = event.target.files[0]
    if (file) {
        form.document_id = document.id
        form.file = file
    }
}

const fileErrors = ref([])

const submitFile = (document) => {
    if (form.file) {
        form.document_id = document.id

        form.post(route('documents.upload', { id: form.document_id }), {
            onSuccess: () => {
                form.reset('file')
                console.log('File uploaded successfully');
                fileErrors.value = [] // Clear errors on successful upload
            },
            onError: (errors) => {
                console.error('Upload failed:', errors)
                fileErrors.value = errors.file || [] // Store the errors related to the file
            },
            preserveScroll: true,
        })
    } else {
        console.log("No file selected")
    }
}

const visibleInputs = ref({});

const toggleFileInput = (documentId) => {
    visibleInputs.value[documentId] = !visibleInputs.value[documentId];
};

const hideFileInput = (documentId) => {
    visibleInputs.value[documentId] = false;
};

    // Function to open the PDF modal with the selected student's file
    const openPdfPreview = (filePath) => {
        selectedFile.value = `/storage/${filePath}`; // Set the file path to show in modal
        isModalPreviewActive.value = true; // Open the modal
    }

    // Function to close the modal
    const closePdfPreview = () => {
        isModalPreviewActive.value = false; // Close the modal
        selectedFile.value = ''; // Reset file path
    }
</script>

    <template>`
        <CardBoxModal v-model="isModalActive" title="Description">
        <p>{{ currentDescription }}</p>
    </CardBoxModal>

    <div class="relative overflow-x-auto">
        <table class="w-full my-2 text-left text-gray-800 rtl:text-right">
            <thead class="text-gray-700">
                <tr class="border-b">
                    <th scope="col" class="px-4 py-3">Title</th>
                    <th scope="col" class="px-4 py-3">Due on</th>
                    <th scope="col" class="px-4 py-3">Action</th>
                    <th v-if="file" scope="col" class="px-4 py-3 text-center">File</th>
                </tr>
            </thead>
            <tbody class="font-medium text-gray-600">
                <tr v-for="document in itemsPaginated" :key="document.id"
                    class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td data-label="Title" scope="row" class="px-6 py-3">
                        {{ document.title }}
                    </td>
                    <td data-label="Due on" class="px-4 py-1 text-red-800">
                        {{ formatDueDate(document.due_date) }}
                    </td>
                    <td class="px-4 py-1 whitespace-nowrap">
                        <BaseButtons type="justify-start" no-wrap>
                            <BaseButton label="View" roundedFull color="blue" :icon="mdiEye" small
                                @click="viewDocument(document)" />
                            <BaseButton label="Download" v-if="document.file_path" roundedFull color="teal"
                                :icon="mdiDownload" small :href="`/storage/${document.file_path}`" target="_blank" />
                        </BaseButtons>
                    </td>
                    <td v-if="file" class="px-4 py-1 text-center">
    <div class="flex items-center">
        <BaseButton roundedFull color="blue" :icon="mdiEye"
        small @click="openPdfPreview(document.users[0].pivot.file_path)" label="Preview" />
        <!-- Toggle Button (X instead of Cancel) -->
        <BaseButton v-if="!isCompleted(document)"
            :label="visibleInputs[document.id] ? 'Close' : 'Upload File'"
            :color="visibleInputs[document.id] ? 'danger' : 'info'"
            small roundedFull @click="toggleFileInput(document.id)" class="py-1 mr-2" />

        <!-- File Input -->
        <input v-if="visibleInputs[document.id] && !isCompleted(document)" type="file"
            @change="event => handleFileSelect(document, event)"
            class="h-auto p-1 mb-2 border rounded-md w-60" />

        <!-- Submit Button -->
        <BaseButton v-if="visibleInputs[document.id] && !isCompleted(document)" :label="buttonLabel(document)"
            roundedFull color="success" small class="ml-2" @click="submitFile(document)" :disabled="isCompleted(document)" />

        <!-- Status for Completed Documents -->
        <div v-if="isCompleted(document)"
            class="flex items-center justify-center w-full h-full font-semibold text-green-500">
            Completed
        </div>
    </div>
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

    <div v-if="isModalPreviewActive" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
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
                <iframe v-if="selectedFile" :src="selectedFile" class="w-full h-[500px]" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</template>
