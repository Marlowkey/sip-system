<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiAccountFile,
    mdiCog,
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
import TableCoordinatorDocumentsSubmission from '@/components/TableCoordinatorDocumentsSubmission.vue'

const props = defineProps({
    user: Object,
    document: Array,
    students: Array,
    classBlocks: Array,
})

const userRole = props.user.role;
</script>

<template>
    <LayoutAuthenticated>

        <Head title="SIP Requirements" />
        <SectionMain v-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" :title="document.title" main>
            </SectionTitleLineWithButton>

                <TableCoordinatorDocumentsSubmission :students="students" :classBlocks="classBlocks" :document="document"/>
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
