<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
  mdiAccountFile,
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import StudentTableDocuments from '@/components/StudentTableDocuments.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'

const props = defineProps({
  documents: Array // Documents passed as a prop from Inertia
})

const mainStore = useMainStore()

</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
      <SectionTitleLineWithButton :icon="mdiAccountFile" title="Document Submission" />
      <CardBox has-table >
        <StudentTableDocuments :document="documents" checkable/>
      </CardBox>
    </SectionMain>
    </LayoutAuthenticated>
</template>
