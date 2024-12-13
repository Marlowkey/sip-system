<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'
import { computed, ref, onMounted, watch } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiAccountSearch,
    mdiAccountGroupOutline,
    mdiLaptop,
    mdiBookOpenPageVariant,
    mdiLaptopAccount,
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
import TableAdminCoordinatorUser from '@/components/TableAdminCoordinatorUser.vue'


const props = defineProps({
    users: Array,
    coordinatorUser: Array,
    schoolYears: Array,
    schoolYear: String,
})

const schoolYear = ref(props.schoolYear ?? null);

const url = route('users-coordinator.index');

const fetchUsersWithFilters = debounce(() => {
    router.get(url, {
        school_year: schoolYear.value
    });
}, 1500);

watch([schoolYear], (newSchoolYear, oldSchoolYear) => {
    if (newSchoolYear !== oldSchoolYear) {
        fetchUsersWithFilters();
    }
});
</script>
<template>
    <LayoutAuthenticated>
        <SectionMain>
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>


            <SectionTitleLineWithButton :icon="mdiAccountGroupOutline" title="Coordinator Users" main>
                <div class="flex justify-end items-center space-x-2 space-y-1 lg:flex-row lg:space-y-0 lg:space-x-4 w-1/2">
                    <!-- Label for School Year (left of select) -->
                    <label for="schoolYear"
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 w-full lg:w-auto">
                        School Year
                    </label>
                    <!-- School Year Select -->
                    <select v-model="schoolYear" placeholder="S/Y"
                        class="w-1/3 p-2 text-sm border border-black rounded-md">
                        <option value="">S/Y</option>
                        <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">
                            {{ sy . year }}
                        </option>
                    </select>
                    <BaseButton label="Add User" roundedFull :icon="mdiPlus" color="info" small
                        routeName="users.create" class="w-full lg:w-auto" />
                </div>

            </SectionTitleLineWithButton>
            <CardBox has-table v-if="coordinatorUser.length > 1">
                <TableAdminCoordinatorUser :users="coordinatorUser" />
            </CardBox>

            <CardBox v-else>
                <CardBoxComponentEmpty />
            </CardBox>

            <div class="m-24"></div>
        </SectionMain>



    </LayoutAuthenticated>
</template>
