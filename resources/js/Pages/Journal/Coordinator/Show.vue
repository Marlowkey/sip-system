<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import {
    mdiAccountFile,
    mdiPlus, mdiFilterVariant
} from '@mdi/js'
import debounce from 'lodash.debounce'
import { computed, ref, onMounted, watch } from 'vue'
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
import CardBoxJournalView from '@/components/CardBoxJournalView.vue'
import FormControl from '@/components/FormControl.vue'
import FormField from '@/components/FormField.vue'
import TableCoordinatorJournalsUser from '@/components/TableCoordinatorJournalsUser.vue'


const props = defineProps({
    user: Object,
    journals: Array,
})

const userRole = props.user.role;
const title = computed(() => {
    if (props.user) {
        return 'Journal Entries' + ' - ' + props.user.last_name + ', ' + props.user.first_name;
    }
})

</script>

<template>
    <LayoutAuthenticated>

        <Head title="Journals" />
        <SectionMain v-if="journals.length > 0">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" :title="title" main>
            </SectionTitleLineWithButton>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-1">
                <CardBoxJournal v-for="item in journals" :key="item.id" :title="item.title" :date="item.date"
                    :reviewed="item.reviewed" :href="route('journals.show', { id: item.id })" />
            </div>
        </SectionMain>

                <SectionMain v-else>
                    <SectionTitleLineWithButton :icon="mdiAccountFile" :title="title" main>
                    </SectionTitleLineWithButton>
            <NotificationBar color="danger" :icon="mdiTableOff">
                <b>Empty content.</b>
            </NotificationBar>
            <CardBox>
                <CardBoxComponentEmpty />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>
