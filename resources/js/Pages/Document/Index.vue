<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiMonitorCellphone,
  mdiAccountFile,
} from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import CardBox from '@/components/CardBox.vue'
import TableUsers from '@/components/TableUsers.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import BaseButton from '@/components/BaseButton.vue'
import CardBoxTransaction from '@/components/CardBoxTransaction.vue'
import CardBoxClient from '@/components/CardBoxClient.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import StudentTableDocuments from '@/components/StudentTableDocuments.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'

const props = defineProps({
  documents: Array // Documents passed as a prop from Inertia
})

const mainStore = useMainStore()

const clientBarItems = computed(() => mainStore.clients.slice(0, 4))

const transactionBarItems = computed(() => mainStore.history)
</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
      <SectionTitleLineWithButton :icon="mdiAccountFile" title="Document Submission" />
      <CardBox has-table >
        <StudentTableDocuments :documents="documents" checkable/>
      </CardBox>
    </SectionMain>
    </LayoutAuthenticated>
</template>
