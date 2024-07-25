<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiDeleteEmptyOutline, mdiFileEditOutline } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import { useForm } from '@inertiajs/vue3'
import { format, parse } from 'date-fns';
import CardBoxComponentEmpty from './CardBoxComponentEmpty.vue'


const props = defineProps({
    checkable: Boolean,
    attendance: {
        type: Array,
        required: true,
    }
})

const user = computed(() => usePage().props.auth.user)
const items = computed(() => props.attendance ? props.attendance : [])
const perPage = ref(10)
const currentPage = ref(0)

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

const deleteAttendance = async (id) => {
    try {
        const form = useForm({})
        await form.delete(route('attendances.destroy', id), {
            preserveScroll: true,
        })
        // Show success notification
    } catch (error) {
        console.error('Error deleting document:', error)
        // Show error notification
    }
}


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
        <table class="my-2 w-full text-gray-800 text-left rtl:text-right">
            <thead class="text-gray-800 text-left">
                <tr class="border-b">
                    <th scope="col" class="px-4 py-3">Date</th>
                    <th scope="col" class="px-4 py-3">Time In (AM)</th>
                    <th scope="col" class="px-4 py-3">Time Out (AM)</th>
                    <th scope="col" class="px-4 py-3">Time In (PM)</th>
                    <th scope="col" class="px-4 py-3">Time Out (PM)</th>
                    <th scope="col" class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="attendance in itemsPaginated" :key="attendance.id" class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td data-label="Date" scope="row" class="px-4 py-1 font-semibold">
                        {{ formatDate(attendance.date) }}
                    </td>
                    <td data-label="Time In (AM)" class="px-4 py-1 text-green-900 font-semibold">
                        {{ formatTime(attendance.time_in_am) }}
                    </td>
                    <td data-label="Time Out (AM)" class="px-4 py-1  text-red-900 font-semibold">
                        {{ formatTime(attendance.time_out_am) }}
                    </td>
                    <td data-label="Time In (PM)" class="px-4 py-1 text-green-900 font-semibold">
                        {{ formatTime(attendance.time_in_pm) }}
                    </td>
                    <td data-label="Time Out (PM)" class="px-4 py-1  text-red-900 font-semibold">
                        {{ formatTime(attendance.time_out_pm) }}
                    </td>
                    <td data-label="Action" class="whitespace-nowrap px-2 py-1">
                        <BaseButtons type="justify-start" no-wrap>
                            <BaseButton roundedFull color="blue" :icon="mdiFileEditOutline"
                                :href="route('attendances.edit', { id: attendance.id })" small />
                            <BaseButton roundedFull color="red" :icon="mdiDeleteEmptyOutline" small
                                @click.prevent="deleteAttendance(attendance.id)" />
                        </BaseButtons>
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
