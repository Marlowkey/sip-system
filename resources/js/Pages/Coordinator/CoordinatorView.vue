<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref, onMounted, watch } from 'vue'
import {
    mdiAccountMultiple,
    mdiCartOutline,
    mdiChartTimelineVariant,
    mdiAccountGroup,
    mdiNoteText,
    mdiArrowRight,
    mdiCheckCircle,
    mdiAlertCircle,
} from '@mdi/js'

import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import CardBox from '@/components/CardBox.vue'
import TableCoordinatorStudentUsers from '@/components/TableCoordinatorStudentUsers.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import CardBoxJournal from '@/components/CardBoxJournal.vue'
import BaseButton from '@/components/BaseButton.vue'
import debounce from 'lodash.debounce'


const props = defineProps({
    users: Array, // Users passed as a prop from Inertia
    user: Object,
    latestJournals: Array,
    classBlocks: Array,
    classBlock: String,
    usersCount: Number,
    attendancesTodayCount: Number,
    unreviewedJournals: Number,

})

const title = computed(() => props.user.course + ' Student Interns & Progress');
const classBlock = ref(props.classBlock ?? 'A');

const url = route('home');

const fetchUsersWithFilters = debounce(() => {
    router.get(url, {
        class_block: classBlock.value
    });
}, 1500);

watch(classBlock, ([newClassBlock, oldClassBlock]) => {
    if (newClassBlock !== oldClassBlock) {
        fetchUsersWithFilters();
    }
});
const user = computed(() => usePage().props.auth.user)
const userName = computed(() => `${user.value.first_name} ${user.value.last_name}`)
const userRole = computed(() => {
    return user.value.role.charAt(0).toUpperCase() + user.value.role.slice(1).toLowerCase();
});
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
                            Welcome, {{ userRole  }} ~ {{ userName }}!
                        </h1>
                    </div>
                    <!--Right Col-->
                    <div class="w-full py-6 text-center md:w-3/5">
                        <img class="z-50 w-full md:w-4/5" src="/images/hero.png" />
                    </div>
                </div>
            </div>

            <SectionTitleLineWithButton title="Users Overview" main>
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                <CardBoxWidget color="text-emerald-500" :icon="mdiAccountMultiple" :number="usersCount"
                    label="Student Interns" />
                <CardBoxWidget color="text-blue-500" :icon="mdiCheckCircle" :number="attendancesTodayCount"
                    label="Today's User Attendance" />
                <CardBoxWidget color="text-red-500" :icon="mdiAlertCircle" :number="unreviewedJournals"
                    label="Unreviewed Journals" />
            </div>

            <SectionTitleLineWithButton :icon="mdiAccountGroup" :title="title">
                <div class="flex items-center">
                    <label for="block" class="mr-2 text-sm font-medium">Class Block:</label>
                    <!-- Label for class block -->
                    <select v-model="classBlock" id="block" class="px-2 py-1 mx-2 border rounded">
                        <option value="">Select a Class Block</option>
                        <option v-for="block in classBlocks" :key="block" :value="block">
                            {{ block }}
                        </option>
                    </select>
                </div>
            </SectionTitleLineWithButton>
            <CardBox has-table>
                <TableCoordinatorStudentUsers :users="users" />
            </CardBox>

            <SectionTitleLineWithButton :icon="mdiNoteText" title="Recent Journal Submission" class="my-4">
                <BaseButton roundedFull :icon="mdiArrowRight" color="whiteDark" routeName="journals.index" />
            </SectionTitleLineWithButton>
            <div v-if="latestJournals" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
                <CardBoxJournal v-for="(journal, index) in latestJournals" :key="index"
                    :title="'Journal: ' + journal.username" :date="journal.date" :reviewed="journal.reviewed" />
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
        </SectionMain>
    </LayoutAuthenticated>
</template>
