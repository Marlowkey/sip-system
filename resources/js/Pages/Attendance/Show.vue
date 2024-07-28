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
})

const date = ref(new Date().toISOString().substr(0, 10))
const url = route('attendances.index')

const fetchAttendances = debounce(() => {
  router.get(url, { date: date.value });
}, 300);

watch(date, () => {
  fetchAttendances();
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

            <SectionTitleLineWithButton :icon="mdiLocationEnter" :title="title" main />
            <CardBoxComponentEmpty v-if="isItemEmpty(props.attendance)" />
            <CardBox has-table v-else>
                <TableStudentAttendance :attendance="attendance" :user="user" />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
