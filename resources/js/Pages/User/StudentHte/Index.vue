<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiDomain,
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import TableCoordinatorStudentHte from '@/components/TableCoordinatorStudentHte.vue'


const props = defineProps({
    user: Object,
    establishments: Array,
    studentUsers: Array,
    classBlocks: Array,
})

const userRole = props.user.role;

</script>

<template>
    <LayoutAuthenticated>
        <Head title="HTE Placements" />
        <SectionMain v-if="establishments && userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>
            <SectionTitleLineWithButton :icon="mdiDomain" title="Student HTE's" main>
            </SectionTitleLineWithButton>
                <TableCoordinatorStudentHte v-if="studentUsers.length > 1" :users="studentUsers" :classBlocks="classBlocks" :htes="establishments"/>
        </SectionMain>
    </LayoutAuthenticated>
</template>
