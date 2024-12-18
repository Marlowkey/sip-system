<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiEyeArrowRightOutline } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import { useForm } from '@inertiajs/vue3'
import { format, parse } from 'date-fns';
import CardBoxComponentEmpty from './CardBoxComponentEmpty.vue'
import FormControl from '@/components/FormControl.vue'
import NavBarItemPlain from '@/components/NavBarItemPlain.vue'
import CardBox from './CardBox.vue'
import UserAvatar from './UserAvatar.vue'

const props = defineProps({
    attendance: {
        type: [Array, Object],
        required: true,
        default: () => [],
    }
})

const user = computed(() => usePage().props.auth.user)
const items = computed(() => Array.isArray(props.attendance) ? props.attendance : Object.values(props.attendance))
const perPage = ref(12)
const currentPage = ref(0)
const searchTerm = ref("")


let form = useForm({
    user_id: user.value.id,
    document_id: null,
    is_completed: false,
})

console.log('Attendance Props:', props.attendance);
console.log('Computed Items:', items.value);


const itemsPaginated = computed(() => {

    let filteredItems = items.value
    if (searchTerm.value) {
        filteredItems = filteredItems.filter(user =>
            user.first_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            user.last_name.toLowerCase().includes(searchTerm.value.toLowerCase())
        )
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
    <div>
        <div class="flex flex-col items-center justify-between mb-4 lg:flex-row">
            <div class="flex flex-col items-center mb-4 space-x-0 lg:flex-row lg:space-x-4 lg:mb-0">
            </div>
            <div class="flex items-center ">
                <FormControl borderless v-model="searchTerm" type="text" placeholder="Search user..." :icon="mdiAccountSearch"
                    class="w-full max-w-xs text-base " />
            </div>
        </div>
    </div>
    <CardBox has-table>
    <div class="relative my-2 overflow-x-auto">
        <table class="w-full my-2 text-left text-gray-800 rtl:text-right">
            <thead class="text-left text-gray-800">
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th />
                    <th scope="col" class="px-4 py-3">Date</th>
                    <th scope="col" class="px-4 py-3">Name</th>
                    <th scope="col" class="px-4 py-3">Time In (AM)</th>
                    <th scope="col" class="px-4 py-3">Time Out (AM)</th>
                    <th scope="col" class="px-4 py-3">Time In (PM)</th>
                    <th scope="col" class="px-4 py-3">Time Out (PM)</th>
                    <th scope="col" class="px-4 py-3">Action</th>

                </tr>
            </thead>
            <tbody>
                <tr v-for="attendance in itemsPaginated" :key="attendance.id"
                    class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 border-b-0 lg:w-6 before:hidden">
                            <UserAvatar :avatar="attendance.avatar" :username="`${attendance.last_name}, ${attendance.first_name}`"
                                class="w-24 h-24 mx-auto lg:w-6 lg:h-6" />
                        </td>
                    <td data-label="Date" scope="row" class="px-4 py-4 font-semibold">
                        {{ formatDate(attendance.date) }}
                    </td>
                    <td data-label="Name" class="px-4 py-1 font-semibold">
                        {{ attendance.last_name }}, {{ attendance.first_name }}
                    </td>
                    <td data-label="Time In (AM)" class="px-4 py-1 font-semibold text-green-900">
                        {{ formatTime(attendance.time_in_am) }}
                    </td>
                    <td data-label="Time Out (AM)" class="px-4 py-1 font-semibold text-red-900">
                        {{ formatTime(attendance.time_out_am) }}
                    </td>
                    <td data-label="Time In (PM)" class="px-4 py-1 font-semibold text-green-900">
                        {{ formatTime(attendance.time_in_pm) }}
                    </td>
                    <td data-label="Time Out (PM)" class="px-4 py-1 font-semibold text-red-900">
                        {{ formatTime(attendance.time_out_pm) }}
                    </td>
                    <td data-label="Action" class="px-2 py-1 hitespace-nowrap">
                        <BaseButtons type="justify-start" no-wrap>
                            <BaseButton label="View"  roundedFull color="blue" :icon="mdiEyeArrowRightOutline" small
                                :href="route('student-attendance.show', { id: attendance.user_id })" />
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
</CardBox>
</template>
