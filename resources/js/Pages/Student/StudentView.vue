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
                <div class="container flex flex-col flex-wrap items-center px-3 mx-auto md:flex-row">
                    <!--Left Col-->
                    <div class="flex flex-col items-start justify-center w-full text-center md:w-2/5 md:text-left">
                        <p class="w-full text-base font-bold uppercase tracking-loose">Student Internship Program (SIP)
                            of CICT CATSU</p>
                        <h1 class="my-4 text-4xl font-bold leading-tight">
                            Welcome, {{ userName }}!
                        </h1>
                    </div>
                    <!--Right Col-->
                    <div class="w-full py-6 text-center md:w-3/5">
                        <img class="z-50 w-full md:w-4/5" src="/images/hero.png" />
                    </div>
                </div>
            </div>

            <SectionTitleLineWithButton title="Overview" main />


            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
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

            <div v-if="latestJournal" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
                <CardBoxJournal :title="latestJournal.title" :date="latestJournal.date"
                    :reviewed="latestJournal.reviewed" />
            </div>
            <p v-else class="p-4 text-center text-gray-600 bg-gray-100 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 mb-2 text-gray-400" fill="none"
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
                <div class="p-4 rounded-lg shadow-sm bg-gray-50">
                    <div class="flex items-center mb-4">
                        <div class="p-2 text-white bg-blue-500 rounded-full">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6">
                                <rect width="24" height="24" fill="none"></rect>
                                <path
                                    d="M9.682,18.75a.75.75,0,0,1,.75-.75,8.25,8.25,0,1,0-6.189-2.795V12.568a.75.75,0,0,1,1.5,0v4.243a.75.75,0,0,1-.751.75H.75a.75.75,0,0,1,0-1.5H3a9.75,9.75,0,1,1,7.433,3.44A.75.75,0,0,1,9.682,18.75Zm2.875-4.814L9.9,11.281a.754.754,0,0,1-.22-.531V5.55a.75.75,0,1,1,1.5,0v4.889l2.436,2.436a.75.75,0,1,1-1.061,1.06Z" />
                            </svg>
                        </div>

                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent</h3>
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
            <p v-else class="p-4 text-center text-gray-600 bg-gray-100 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 mb-2 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 13h6m2 6H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
                <span class="block text-lg font-semibold">No attendance entries available.</span>
            </p>



        </SectionMain>
    </LayoutAuthenticated>
</template>
