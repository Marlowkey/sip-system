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
    users: Array,
    user: Object,
    // latestJournals: Array,
    classBlocks: Array,
    classBlock: String,
    usersCount: Number,
    attendancesTodayCount: Number,
    unreviewedJournalsCount: Number,
    unreviewedJournals: Array,


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

            <SectionTitleLineWithButton title="Users Overview" main>
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                <CardBoxWidget color="text-emerald-500" :icon="mdiAccountMultiple" :number="usersCount"
                    label="Student Interns" />
                <CardBoxWidget color="text-blue-500" :icon="mdiCheckCircle" :number="attendancesTodayCount"
                    label="Today's User Attendance" />
                <CardBoxWidget color="text-red-500" :icon="mdiAlertCircle" :number="unreviewedJournalsCount"
                    label="Unreviewed Journals" />
            </div>

            <SectionTitleLineWithButton :icon="mdiAccountGroup" :title="title">
                <div class="flex items-center">
                    <label for="block" class="mr-2 text-lg font-medium">Class Block:</label>
                    <select v-model="classBlock" id="block" class="px-2 py-1 mx-2 border rounded">
                        <option value="">Select a Class Block</option>
                        <option v-for="block in classBlocks" :key="block" :value="block">
                            {{ block }}
                        </option>
                    </select>
                </div>
            </SectionTitleLineWithButton>
                <TableCoordinatorStudentUsers :users="users" />
        </SectionMain>
    </LayoutAuthenticated>
</template>
