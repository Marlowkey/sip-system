<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import {
    mdiAccountGroupOutline,
    mdiLaptop,
    mdiBookOpenPageVariant,
    mdiLaptopAccount ,
    mdiPlus
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import BaseButton from '@/components/BaseButton.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import TableAdminStudentUsers from '@/components/TableAdminStudentUsers.vue'
import TableAdminCoordinatorUser from '@/components/TableAdminCoordinatorUser.vue'


const props = defineProps({
    users: Array,
    coordinatorUser: Array,
    studentUser: Array,
    itStudentCount: Number,
    isStudentCount: Number,
    csStudentCount: Number,
    classBlocks: Array,
})


</script>
<template>
    <LayoutAuthenticated>

        <Head title="Daily Time Record" />
        <SectionMain>
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountGroupOutline" title="Coordinator Users" main>
                <BaseButton label="Add User" roundedFull :icon="mdiPlus" color="info" small routeName="users.create" />

            </SectionTitleLineWithButton>
            <CardBox has-table>
                <TableAdminCoordinatorUser :users="coordinatorUser" />
            </CardBox>

            <div class="m-24"></div>

            <div class="grid grid-cols-1 gap-6 mb-16 lg:grid-cols-3">
                <CardBoxWidget color="text-green-500" :icon="mdiLaptop" :number="itStudentCount" label="IT Students" />
                <CardBoxWidget color="text-indigo-500" :icon="mdiBookOpenPageVariant" :number="isStudentCount"
                    label="IS Students" />
                <CardBoxWidget color="text-purple-500" :icon="mdiLaptopAccount " :number="csStudentCount"
                    label="CS Students" />
            </div>


            <SectionTitleLineWithButton :icon="mdiAccountGroupOutline" title="Student Intern Users" main>
                    <BaseButton label="Add User" roundedFull :icon="mdiPlus" color="info" small routeName="users.create" />
            </SectionTitleLineWithButton>
                <TableAdminStudentUsers :users="studentUser"  :classBlocks="classBlocks"/>
        </SectionMain>
    </LayoutAuthenticated>
</template>
