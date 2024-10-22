
<script setup>
import { Head, router } from '@inertiajs/vue3'
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



</script>

<template>
    <LayoutAuthenticated>

        <Head title="Home" />
        <SectionMain>
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

            <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Users">
            </SectionTitleLineWithButton>


            <SectionTitleLineWithButton :icon="mdiNoteText" title="Recent Journal Submission" class="my-4">
                <BaseButton roundedFull :icon="mdiArrowRight" color="whiteDark" routeName="journals.index" />
            </SectionTitleLineWithButton>
        </SectionMain>
    </LayoutAuthenticated>
</template>



