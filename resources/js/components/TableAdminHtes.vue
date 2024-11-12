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

const props = defineProps({
    establishments: {
        type: Array,
        required: true,
    }
})



const user = computed(() => usePage().props.auth.user)
const items = computed(() => props.establishments ? props.establishments : [])
const isModalActive = ref(false)
const isModalDangerActive = ref(false)
const perPage = ref(10)
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

const formatTime = (time) => {
    if (!time) return '---';

    const date = new Date(`1970-01-01T${time}`);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
}

const formatDate = (date) => {
    if (!date) return '---';

    return format(parse(date, 'yyyy-MM-dd', new Date()), 'MMMM dd, yyyy');
}

</script>
<template>

    <div class="relative overflow-x-auto">
        <table class="w-full mb-2 text-left rtl:text-right">
    <thead class="text-gray-700">
        <tr class="border-b">
            <th scope="col" class="px-4 py-3">Name</th>
            <th scope="col" class="px-4 py-3">Address</th>
            <th scope="col" class="px-4 py-3 text-center">Email</th>
            <th scope="col" class="px-4 py-3 text-center">MOA Validity Period</th>
            <th scope="col" class="px-4 py-3 text-center">MOA File</th>
            <th scope="col" class="px-4 py-3 text-center">Update</th>
        </tr>
    </thead>
    <tbody class="font-medium text-gray-600">
        <tr v-for="establishment in itemsPaginated" :key="establishment.id" class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td data-label="Name" scope="row" class="px-4 py-4">
                {{ establishment.name }}
            </td>
            <td data-label="Address" class="px-4 py-1">
                {{ establishment.address }}
            </td>
            <td data-label="Email" class="px-4 py-1">
                {{ establishment.email }}
            </td>
            <td data-label="MOA Validity Period" class="px-4 py-1 text-center">
                {{ establishment.moa_validity_period ? formatDate(establishment.moa_validity_period) : 'N/A' }}
            </td>
            <td data-label="MOA File" class="px-4 py-1 text-center">
                <a v-if="establishment.moa_file_path" :href="`/storage/${establishment.moa_file_path}`" target="_blank" class="text-blue-500 hover:underline">
                    Download
                </a>
                <span v-else>N/A</span>
            </td>
            <td class="px-4 py-1">
                <BaseButtons no-wrap>
                    <BaseButton label="Edit" roundedFull color="yellow" :icon="mdiFileEditOutline" small
                        :href="route('htes.edit', { id: establishment.id })" />
                </BaseButtons>
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
</template>
