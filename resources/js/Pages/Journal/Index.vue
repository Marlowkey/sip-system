<script setup>
import { Head } from '@inertiajs/vue3'
import {
    mdiAccountFile,
    mdiPlus
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import TableStudentDocuments from '@/components/TableStudentDocuments.vue'
import TableCoordinatorDocuments from '@/components/TableCoordinatorDocuments.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import BaseButton from '@/components/BaseButton.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import CardBoxComponentEmpty from '@/components/CardBoxComponentEmpty.vue'
import CardBoxJournal from '@/components/CardBoxJournal.vue'


const props = defineProps({
    user: Object, // Authenticated user passed as a prop from Inertia
})

const userRole = props.user.role;
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Journals" />
        <SectionMain v-if="userRole === 'student'">

            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" title="Journal Entries" main>
                <BaseButton roundedFull :icon="mdiPlus" color="whiteDark" routeName="journals.create"/>
            </SectionTitleLineWithButton>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <CardBoxJournal :title="'Test'" :date="'Now'" :text="'text'" :type="'type'" />
                <CardBoxJournal :title="'Test'" :date="'Now'" :text="'text'" :type="'type'" />
                <CardBoxJournal :title="'Test'" :date="'Now'" :text="'text'" :type="'type'" />
                <CardBoxJournal :title="'Test'" :date="'Now'" :text="'text'" :type="'type'" />
                <CardBoxJournal :title="'Test'" :date="'Now'" :text="'text'" :type="'type'" />
                <CardBoxJournal :title="'Test'" :date="'Now'" :text="'text'" :type="'type'" />

            </div>
        </SectionMain>

        <SectionMain v-else-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" title="Journal Entries" main>
                <BaseButton roundedFull :icon="mdiPlus" color="whiteDark" routeName="documents.create" />
            </SectionTitleLineWithButton>
            <CardBox>
            </CardBox>
        </SectionMain>


        <SectionMain v-else>
            <NotificationBar color="danger" :icon="mdiTableOff">
                <b>Empty table.</b>
            </NotificationBar>
            <CardBox>
                <CardBoxComponentEmpty />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
