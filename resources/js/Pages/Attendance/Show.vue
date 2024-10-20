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
import NotificationBar from '@/components/NotificationBar.vue'
import TableStudentAttendance from '@/components/TableStudentAttendance.vue'
import CardBoxComponentEmpty from '@/components/CardBoxComponentEmpty.vue'
import FormControl from '@/components/FormControl.vue'
import BaseButton from '@/components/BaseButton.vue'

const props = defineProps({
    user: Object, // Authenticated user passed as a prop from Inertia
    attendance: Array, // Documents passed as a prop from Inertia
    month: String, // Month passed as a prop from Inertia
})

const getMonthNow = () => {
    return new Date().toISOString().substr(0, 7)
}

const month = ref(props.month ?? getMonthNow())
const url = route('student-attendance.show', { id: props.user.id })
const userId = ref(props.user.id)

const fetchAttendancesForStudent = debounce(() => {
    router.get(url, { month: month.value });
}, 1500);

watch(month, (newMonth, oldMonth) => {
    if (newMonth !== oldMonth) {
        fetchAttendancesForStudent();
    }
});

const isItemEmpty = ((item) => {
    return item.length === 0;
})

const userRole = props.user.role;

const title = computed(() => {
    if (props.user) {
        return 'Daily Time Record' + ' - ' + props.user.last_name + ', ' + props.user.first_name;
    }
})

</script>
<template>
    <LayoutAuthenticated>

        <Head title="Daily Time Record" />
        <SectionMain>
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiLocationEnter" :title="title" main>

                <div class="flex justify-end items-center">
                    <FormControl v-model="month" borderless type="month" placeholder="Select Date"
                        class="text-sm font-medium mx-1" />

                    <BaseButton label="Export" roundedFull small :icon="mdiPlus" color="success"
                        :href="route('attendances.export', { month: month, user_id: userId })" class="p-2 mx-1" />
                </div>
            </SectionTitleLineWithButton>
            <CardBoxComponentEmpty v-if="isItemEmpty(props.attendance)" />
            <CardBox has-table v-else>
                <TableStudentAttendance :attendance="attendance" :user="user" />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
