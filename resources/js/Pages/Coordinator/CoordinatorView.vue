<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiMonitorCellphone,
} from '@mdi/js'

import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import CardBox from '@/components/CardBox.vue'
import TableStudentUsers from '@/components/TableStudentUsers.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import BaseButton from '@/components/BaseButton.vue'
import CardBoxTransaction from '@/components/CardBoxTransaction.vue'
import CardBoxClient from '@/components/CardBoxClient.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'

const props = defineProps({
  users: Array, // Users passed as a prop from Inertia
  user: Object,
})

const title = computed(() => props.user.course + ' Student Interns');

    const mainStore = useMainStore()

</script>

<template>
        <LayoutAuthenticated>
            <Head title="Home" />
    <SectionMain>
      <SectionTitleLineWithButton title="Overview" main>
      </SectionTitleLineWithButton>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
        <CardBoxWidget

          color="text-emerald-500"
          :icon="mdiAccountMultiple"
          :number="512"
          label="Clients"
        />
        <CardBoxWidget

          color="text-blue-500"
          :icon="mdiCartOutline"
          :number="7770"

          label="Sales"
        />
        <CardBoxWidget

          color="text-red-500"
          :icon="mdiChartTimelineVariant"
          :number="256"

          label="Performance"
        />
      </div>

      <SectionTitleLineWithButton :icon="mdiAccountMultiple" :title="title" />
      <CardBox has-table >
        <TableStudentUsers :users="users"/>
      </CardBox>
    </SectionMain>
    </LayoutAuthenticated>
</template>
