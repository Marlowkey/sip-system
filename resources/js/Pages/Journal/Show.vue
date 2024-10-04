<script setup>
import { Head } from '@inertiajs/vue3'
import {
    mdiAccountFile,
    mdiPlus
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import BaseButton from '@/components/BaseButton.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import CardBoxComponentEmpty from '@/components/CardBoxComponentEmpty.vue'
import CardBoxJournal from '@/components/CardBoxJournal.vue'
import CardBoxJournalView from '@/components/CardBoxJournalView.vue'


const props = defineProps({
    user: Object,
    journal: Object,
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
                <BaseButton roundedFull :icon="mdiPlus" color="whiteDark" routeName="journals.create" />
            </SectionTitleLineWithButton>
            <CardBox>
                <CardBoxJournalView
                :id="journal.id"
                :title="journal.title"
                :date="journal.date"
                :content="journal.content"
                :image_path="journal.image_path"
                :week="journal.week"
                />
            </CardBox>
        </SectionMain>

        <SectionMain v-else-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" title="Journal Entries" main>
            </SectionTitleLineWithButton>
            <CardBox>
                <CardBoxJournalView
                :id="journal.id"
                :title="journal.title"
                :date="journal.date"
                :content="journal.content"
                :image_path="journal.image_path"
                :week="journal.week"
                :user="journal.userRole"
                />
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
