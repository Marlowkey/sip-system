<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'
import { computed, ref, onMounted, watch } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiSchoolOutline,
    mdiPlus
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
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import TableAdminStudentUsers from '@/components/TableAdminStudentUsers.vue'
import FolderSchoolYear from '@/components/FolderSchoolYear.vue'

const props = defineProps({
    schoolYears: Array,
})


// const confirmDelete = () => {
//     deleteSchoolYear(1);
// };

// const deleteSchoolYear = async (schoolYearId) => {
//     try {
//         const form = useForm({});
//         await form.delete(route('schoolyears.destroy', schoolYearId));
//         // Optionally, you can add logic to refresh the list or show a success message
//     } catch (error) {
//         console.error('Error deleting school year:', error);
//     }
// };
</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiSchoolOutline" title="School Years" main>
                <BaseButton label="Add SY" roundedFull :icon="mdiPlus" color="info" small
                    :href="route('schoolyears.create')" class="w-full lg:w-auto" />
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-4 my-16 md:grid-cols-2 lg:grid-cols-4">

                <!-- <BaseButton label="Delete" color="red" @click.prevent="confirmDelete" roundedFull small /> -->
                <template v-if="schoolYears.length > 0">
                    <FolderSchoolYear v-for="(year, index) in schoolYears" :key="year.id" :text="year.year" />
                </template>
                <template v-else>
                    <CardBoxComponentEmpty title="No School Years Available" />
                </template>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
