<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'
import { computed, ref, onMounted, watch } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiLocationEnter,
    mdiFilterMenuOutline,
    mdiPlus,
    mdiClockCheckOutline,
    mdiFilterMenu,
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import BaseButton from '@/components/BaseButton.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import TableStudentAttendance from '@/components/TableStudentAttendance.vue'
import CardBoxComponentEmpty from '@/components/CardBoxComponentEmpty.vue'
import TableCoordinatorAttendance from '@/components/TableCoordinatorAttendance.vue'
import Dropdown from '@/ComponentsBreeze/Dropdown.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import FormControl from '@/components/FormControl.vue'


const props = defineProps({
    user: Object, // Authenticated user passed as a prop from Inertia
    attendance: Array, // Documents passed as a prop from Inertia
    studentAttendance: Array, // Student attendance passed as a prop from Inertia
})

const getDateNow = () => {
    return new Date().toISOString().substr(0, 10)
}

const date = ref(getDateNow())
const url = route('attendances.index')


const fetchAttendances = debounce(() => {
    router.get(url, { date: date.value });
}, 1500);

watch(date, (newDate, oldate) => {
    if (newDate !== oldDate) {
        fetchAttendances();
    }
});

const isItemEmpty = ((item) => {
    return item.length === 0;
})

const userRole = props.user.role;

</script>
<template>
    <LayoutAuthenticated>

        <Head title="Daily Time Record" />
        <SectionMain v-if="userRole === 'student'">

            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Daily Time Record" main>
                <BaseButton roundedFull :icon="mdiClockCheckOutline" color="blue" routeName="attendances.create"
                    class="mx-4" />
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

            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Daily Time Record" main>
                <FormControl v-model="date" borderless type="date" placeholder="Select Date"
                    class="mx-2 text-sm font-medium" />
            </SectionTitleLineWithButton>
            <CardBoxComponentEmpty v-if="isItemEmpty(props.studentAttendance)" />
            <CardBox has-table v-else>
                <TableCoordinatorAttendance :attendance="studentAttendance" />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
