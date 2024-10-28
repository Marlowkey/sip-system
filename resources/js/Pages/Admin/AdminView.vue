
<script setup>
import { Head, router, usePage} from '@inertiajs/vue3'

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
            <!-- <SectionTitleLineWithButton title="Users Overview" main>
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                <CardBoxWidget color="text-emerald-500" :icon="mdiAccountMultiple" :number="usersCount"
                    label="Student Interns" />
                <CardBoxWidget color="text-blue-500" :icon="mdiCheckCircle" :number="attendancesTodayCount"
                    label="Today's User Attendance" />
                <CardBoxWidget color="text-red-500" :icon="mdiAlertCircle" :number="unreviewedJournals"
                    label="Unreviewed Journals" />
            </div> -->
<!--
            <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Users">
            </SectionTitleLineWithButton>


            <SectionTitleLineWithButton :icon="mdiNoteText" title="Recent Journal Submission" class="my-4">
                <BaseButton roundedFull :icon="mdiArrowRight" color="whiteDark" routeName="journals.index" />
            </SectionTitleLineWithButton> -->
        </SectionMain>
    </LayoutAuthenticated>
</template>



