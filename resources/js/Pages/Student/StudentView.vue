<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiAccountMultiple,
    mdiCartOutline,
    mdiFileDocumentCheck,
    mdiFolderArrowLeft,
    mdiAccountFile,
    mdiLoginVariant,
    mdiArrowRight,
    mdiNoteText, // For journal icon
    mdiClockOutline, // For attendance icon
} from '@mdi/js'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import CardBox from '@/components/CardBox.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import BaseButton from '@/components/BaseButton.vue'
import CardBoxTransaction from '@/components/CardBoxTransaction.vue'
import CardBoxClient from '@/components/CardBoxJournal.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import TableStudentDocuments from '@/components/TableStudentDocuments.vue'
import CardBoxJournal from '@/components/CardBoxJournal.vue'
import { format, parse, differenceInDays, parseISO } from 'date-fns';


const props = defineProps({
    documents: Array,
    progress: Number,
    journalsCount: Number,
    attendanceCount: Number,
    latestJournal: Array,
    latestAttendance: Array,
})

const mainStore = useMainStore()

const user = computed(() => usePage().props.auth.user)
const userName = computed(() => `${user.value.first_name} ${user.value.last_name}`)

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
    <LayoutAuthenticated>

        <Head title="Home" />
        <SectionMain>
            <div class="">
                <div class="container px-3 mx-auto flex flex-wrap flex-col md:flex-row items-center">
                    <!--Left Col-->
                    <div class="flex flex-col w-full md:w-2/5 justify-center items-start text-center md:text-left">
                        <p class="uppercase text-base font-bold tracking-loose w-full">Student Internship Program (SIP)
                            of CICT CATSU</p>
                        <h1 class="my-4 text-4xl font-bold leading-tight">
                            Welcome, {{ userName }}!
                        </h1>
                    </div>
                    <!--Right Col-->
                    <div class="w-full md:w-3/5 py-6 text-center">
                        <img class="w-full md:w-4/5 z-50" src="/images/hero.png" />
                    </div>
                </div>
            </div>

            <SectionTitleLineWithButton title="Overview" main />


            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <CardBoxWidget color="text-emerald-500" :icon="mdiFileDocumentCheck" :number="progress.toFixed()"
                    label="Submission Progress" suffix="%" />
                <CardBoxWidget color="text-blue-500" :icon="mdiFolderArrowLeft" :number="journalsCount.toFixed()"
                    label="Journal Entries" />
                <CardBoxWidget color="text-red-500" :icon="mdiLoginVariant" :number="attendanceCount.toFixed()"
                    label="Attendance Logged" />
            </div>

            <SectionTitleLineWithButton :icon="mdiAccountFile" title="Pending Requirements">
                <BaseButton roundedFull :icon="mdiArrowRight" color="whiteDark" routeName="documents.index" />
            </SectionTitleLineWithButton>

            <CardBox has-table>
                <TableStudentDocuments :document="documents" />
            </CardBox>

            <!-- Latest Journal Section -->
            <SectionTitleLineWithButton :icon="mdiNoteText" title="Latest Journal" class="my-4" />

            <div v-if="latestJournal" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <CardBoxJournal :title="latestJournal.title" :date="latestJournal.date"
                    :reviewed="latestJournal.reviewed" />
            </div>
            <p v-else class="p-4 text-center bg-gray-100 text-gray-600 rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mb-2 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m2 6H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                    <span class="block text-lg font-semibold">No journal entries available.</span>
                    <span class="text-sm">You haven't submitted any journal entries yet.</span>
                </p>


            <!-- Latest Attendance Section -->
            <SectionTitleLineWithButton :icon="mdiClockOutline" title="Latest Attendance" />
            <CardBox v-if="latestAttendance">
                <div  class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-500 rounded-full text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m0 0a4 4 0 014 4v4M12 21H5a2 2 0 01-2-2v-7a2 2 0 012-2h7a2 2 0 012 2v7a2 2 0 01-2 2zm12-10h-4m0 0a4 4 0 00-4 4v4a4 4 0 004 4h4m0 0v-4m0 4v4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Latest Attendance</h3>
                            <p class="text-sm text-gray-500">Date: {{ formatDate(latestAttendance.date) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600"><strong>Time In AM:</strong></p>
                            <p class="text-lg font-medium text-gray-800">{{ formatTime(latestAttendance.time_in_am) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><strong>Time Out AM:</strong></p>
                            <p class="text-lg font-medium text-gray-800">{{ formatTime(latestAttendance.time_out_am) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><strong>Time In PM:</strong></p>
                            <p class="text-lg font-medium text-gray-800">{{ formatTime(latestAttendance.time_in_pm) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><strong>Time Out PM:</strong></p>
                            <p class="text-lg font-medium text-gray-800">{{ formatTime(latestAttendance.time_out_pm) }}
                            </p>
                        </div>
                    </div>
                </div>
            </CardBox>
            <p v-else class="p-4 text-center bg-gray-100 text-gray-600 rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mb-2 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m2 6H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                    <span class="block text-lg font-semibold">No attendance entries available.</span>
                </p>



        </SectionMain>
    </LayoutAuthenticated>
</template>
