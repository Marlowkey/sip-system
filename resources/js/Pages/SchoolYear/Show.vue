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
    schoolYear: String,
    classBlocks: Array,

})


const groupedUsers = computed(() => {
  return props.users.reduce((acc, user) => {
    if (user.course === 'Computer Science') {
      acc.cs.push(user);
    } else if (user.course === 'Information System') {
      acc.is.push(user);
    } else if (user.course === 'Information Technology') {
      acc.it.push(user);
    } else {
      console.warn('Unknown course for user:', user); // Handle unknown courses
    }
    return acc;
  }, { it: [], is: [], cs: [] });
});

const itStudentCount = computed(() => groupedUsers.value.it.length);
const isStudentCount = computed(() => groupedUsers.value.is.length);
const csStudentCount = computed(() => groupedUsers.value.cs.length);
</script>

<template>
    <LayoutAuthenticated>

        <Head :title="schoolYear.year" />
        <SectionMain>
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <div class="grid grid-cols-1 gap-6 mb-16 lg:grid-cols-3">
                <CardBoxWidget color="text-green-500" :icon="mdiLaptop" :number="itStudentCount" label="IT Student Interns" />
                <CardBoxWidget color="text-indigo-500" :icon="mdiBookOpenPageVariant" :number="isStudentCount"
                    label="IS Student Interns" />
                <CardBoxWidget color="text-purple-500" :icon="mdiLaptopAccount" :number="csStudentCount"
                    label="CS Student Interns" />
            </div>


            <SectionTitleLineWithButton :icon="mdiAccountGroupOutline" title="Users" main>
            </SectionTitleLineWithButton>
            <TableAdminStudentUsers v-if="users.length > 1"  :users="users" filterRole :classBlocks="classBlocks"/>
            <CardBox v-else>
                <CardBoxComponentEmpty />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
