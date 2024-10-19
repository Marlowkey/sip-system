<script setup>
import { Head, router } from '@inertiajs/vue3'
import { computed, ref, onMounted, watch } from 'vue'
import {
    mdiAccountMultiple,
    mdiCartOutline,
    mdiChartTimelineVariant,
    mdiAccountGroup,
    mdiNoteText,
    mdiArrowRight,
} from '@mdi/js'

import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import CardBox from '@/components/CardBox.vue'
import TableCoordinatorStudentUsers from '@/components/TableCoordinatorStudentUsers.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import CardBoxJournal from '@/components/CardBoxJournal.vue'
import BaseButton from '@/components/BaseButton.vue'
import debounce from 'lodash.debounce'


const props = defineProps({
    users: Array, // Users passed as a prop from Inertia
    user: Object,
    latestJournals: Array,
    classBlocks: Array,
    classBlock: String,
})

const title = computed(() => props.user.course + ' Student Interns & Progress');
const classBlock = ref(props.classBlock ?? 'A');

const url = route('home');

const fetchUsersWithFilters = debounce(() => {
    router.get(url, {
        class_block: classBlock.value
    });
}, 1500);

watch( classBlock, ( [newClassBlock, oldClassBlock]) => {
    if (newClassBlock !== oldClassBlock) {
        fetchUsersWithFilters();
    }
});

</script>

<template>
    <LayoutAuthenticated>

        <Head title="Home" />
        <SectionMain>
            <SectionTitleLineWithButton title="Overview" main>
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <CardBoxWidget color="text-emerald-500" :icon="mdiAccountMultiple" :number="512" label="Test" />
                <CardBoxWidget color="text-blue-500" :icon="mdiAccountMultiple" :number="7770" label="Test" />
                <CardBoxWidget color="text-red-500" :icon="mdiAccountMultiple" :number="256" label="Test" />
            </div>

            <SectionTitleLineWithButton :icon="mdiAccountGroup" :title="title">
                <div class="flex items-center">
                    <label for="block" class="mr-2 text-sm font-medium">Class Block:</label>
                    <!-- Label for class block -->
                    <select v-model="classBlock" id="block" class="border rounded px-2 py-1 mx-2">
                        <option value="">Select a Class Block</option>
                        <option v-for="block in classBlocks" :key="block" :value="block">
                            {{ block }}
                        </option>
                    </select>
                </div>
            </SectionTitleLineWithButton>
            <CardBox has-table>
                <TableCoordinatorStudentUsers :users="users" />
            </CardBox>

            <SectionTitleLineWithButton :icon="mdiNoteText" title="Recent Journal Submission" class="my-4" >
            <BaseButton roundedFull :icon="mdiArrowRight" color="whiteDark" routeName="journals.index" />
        </SectionTitleLineWithButton>
            <div v-if="latestJournals" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <CardBoxJournal
                v-for="(journal, index) in latestJournals"
                :key="index"
                :title="journal.title"
                :date="journal.date"
                :reviewed="journal.reviewed"
            />
            </div>
            <p v-else class="p-4 text-center bg-gray-100 text-gray-600 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mb-2 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 13h6m2 6H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
                <span class="block text-lg font-semibold">No journal entries available.</span>
                <span class="text-sm">You haven't submitted any journal entries yet.</span>
            </p>
        </SectionMain>
    </LayoutAuthenticated>
</template>
