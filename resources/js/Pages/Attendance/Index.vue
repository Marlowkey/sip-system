<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'
import { computed, ref, onMounted, watch } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiLocationEnter,
    mdiPlus,
    mdiClockCheckOutline,
    mdiFileExport,
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

import FormControl from '@/components/FormControl.vue'


const props = defineProps({
    user: Object, // Authenticated user passed as a prop from Inertia
    attendance: Array, // Documents passed as a prop from Inertia
    studentAttendance: Array, // Student attendance passed as a prop from Inertia
    date: String, // Date passed as a prop from Inertia
    month: String, // Month passed as a prop from Inertia
})



const getDateNow = () => {
    return new Date().toISOString().substr(0, 10)
}

const getMonthNow = () => {
    return new Date().toISOString().substr(0, 7)
}

const date = ref(props.date ?? getDateNow())
const month = ref(props.month ?? getMonthNow())
const userId = ref(props.user?.id);
const url = route('attendances.index')


const fetchAttendancesForCoordinator = debounce(() => {
    router.get(url, { date: date.value });
}, 1500);

const fetchAttendancesForStudent = debounce(() => {
    router.get(url, { month: month.value });
}, 1500);

watch(date, (newDate, oldDate) => {
    if (newDate !== oldDate) {
        fetchAttendancesForCoordinator();
    }
});

watch(month, (newMonth, oldMonth) => {
    if (newMonth !== oldMonth) {
        fetchAttendancesForStudent();
    }
});

const isItemEmpty = ((item) => {
    return item.length === 0;
})

const userRole = props.user.role;

const form = useForm({
    month: props.month,
    user_id: userId.value,
})

const exportAttendance = () => {
    console.log('Month:', month.value);
    console.log('User ID:', userId.value);

    form.post(route('attendances.export'), {
        month: month.value,
        user_id: userId.value,
    }, {
        onSuccess: () => {
            reset();
        }
    });
}
</script>
<template>
    <LayoutAuthenticated>

        <Head title="Daily Time Record" />
        <SectionMain v-if="userRole === 'student'">

            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiLocationEnter" title="Daily Time Record" main>
                <div class="flex mx-6">
                    <FormControl v-model="month" borderless type="month" placeholder="Select Date"
                        class="justify-end text-sm font-medium" />
                    <div class="justify-end content-center	mx-2">
                        <BaseButton label="Log in" roundedFull small :icon="mdiPlus" color="info"
                            routeName="attendances.create" class="p-2 mx-1" />
                        <BaseButton label="Export" roundedFull small :icon="mdiFileExport " color="success"
                            :href="route('attendances.export', { month: month, user_id: userId})" class="p-2 mx-1" />
                    </div>
                </div>

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
