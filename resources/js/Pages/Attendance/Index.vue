<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiLocationEnter,
    mdiPlus,
    mdiClockCheckOutline,
    mdiClockOut,
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import BaseButton from '@/components/BaseButton.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import TableStudentAttendance from '@/components/TableStudentAttendance.vue'
import CardBoxComponentEmpty from '@/components/CardBoxComponentEmpty.vue'


const props = defineProps({
    user: Object, // Authenticated user passed as a prop from Inertia
    attendance: Array, // Documents passed as a prop from Inertia
})

const userRole = props.user.role;

const isItemEmpty = ((item) => {
    return item.length === 0;
})
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Daily Time Record" />
        <SectionMain v-if="userRole === 'student'">

            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Daily Time Record" main >
                    <BaseButton roundedFull :icon="mdiClockCheckOutline" color="blue"  routeName="attendances.create"  class="mx-4"/>
        </SectionTitleLineWithButton>
        <CardBoxComponentEmpty v-if="isItemEmpty(props.attendance)" />
            <CardBox has-table v-else>
                <TableStudentAttendance :attendance="attendance" />
            </CardBox>
        </SectionMain>

        <SectionMain v-else-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Document Submission" main>
                <BaseButton roundedFull :icon="mdiPlus" color="whiteDark" routeName="documents.create" />
            </SectionTitleLineWithButton>
            <CardBoxComponentEmpty v-if="isItemEmpty(props.attendance)" />
            <CardBox has-table>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
