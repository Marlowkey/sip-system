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
    students: Array,
    journal: Array,
    week: Number,
    classBlocks: Array,
    classBlock: String
})

const userRole = props.user.role;


const classBlock = ref(props.classBlock ?? 'A');
const url = route('journals.index');

const fetchJournalsWithFilters = debounce(() => {
    router.get(url, {
        class_block: classBlock.value
    });
}, 1500);

watch(classBlock, (newClassBlock, oldClassBlock) => {
    if (newClassBlock !== oldClassBlock) {
        fetchJournalsWithFilters();
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
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-1">
                <CardBoxJournal v-for="item in journal" :key="item.id" :title="item.title" :date="item.date"
                    :reviewed="item.reviewed" :href="route('journals.show', { id: item.id })" />
            </div>
        </SectionMain>

        <SectionMain v-else-if="userRole === 'coordinator'">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountFile" title="Journal Entries" main>
                <div class="flex items-center">
                    <label for="block" class="mr-2 text-sm font-medium">Class Block:</label>
                    <select v-model="classBlock" id="block" class="px-2 py-1 mx-2 border rounded">
                        <option value="">Select a Class Block</option>
                        <option v-for="block in classBlocks" :key="block" :value="block">
                            {{ block }}
                        </option>
                    </select>
                </div>
            </SectionTitleLineWithButton>
            <TableCoordinatorJournalsUser :users="students" />
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
