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


const props = defineProps({
    user: Object,
    journal: Array,
    week: Number,
    classBlocks: Array
})

const userRole = props.user.role;

const week = ref(props.week ?? 1);
const url = route('journals.index')

const fetchStudentJournalsForCoordinator = debounce(() => {
    router.get(url, { week: week.value });
}, 1500);

watch(week, (newWeek, oldWeek) => {
    if (newWeek !== oldWeek) {
        fetchStudentJournalsForCoordinator();
    }
});

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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <CardBoxJournal v-for="item in journal" :key="item.id" :title="item.title" :date="item.date"
                    :text="'Test'" :type="'type'" :href="route('journals.show', { id: item.id })" />
            </div>
        </SectionMain>

        <SectionMain v-else-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" title="Journal Entries" main>
                <div class="flex items-center">
        <label for="class-block" class="mr-2 text-lg font-medium">Class Block:</label>
        <!-- Label for class block -->
        <select v-model="selectedClassBlock" id="class-block" class="border rounded px-2 py-1 mx-2">
            <option value="">Select a Class Block</option>
            <option v-for="block in classBlocks" :key="block" :value="block">
                {{ block }}
            </option>
        </select>
        <BaseButton roundedFull :icon="mdiFilterVariant" color="whiteDark" routeName="journals.create" />
    </div>
                <div class="flex items-center">
                    <label for="week" class="mr-2 text-lg font-medium">Week:</label> <!-- Label for the input -->
                    <FormControl v-model="week" borderless type="number" id="week"
                        class="text-center mx-2 text-lg font-medium" />
                </div>
            </SectionTitleLineWithButton>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4">
                <CardBoxJournal v-for="item in journal" :key="item.id" :title="item.title" :date="item.date"
                    :text="'Test'" :type="'type'" :href="route('journals.show', { id: item.id })" />
            </div>
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
