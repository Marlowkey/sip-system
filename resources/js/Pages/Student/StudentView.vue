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
    mdiArrowRight
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

const props = defineProps({
    documents: Array,
    progress: Number,
    journalsCount: Number,
    attendanceCount: Number,


})

const mainStore = useMainStore()

const user = computed(() => usePage().props.auth.user)
const userName = computed(() => `${user.value.first_name} ${user.value.last_name}`)


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
        </SectionMain>
    </LayoutAuthenticated>
</template>
