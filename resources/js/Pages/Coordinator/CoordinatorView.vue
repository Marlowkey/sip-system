<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiAccountGroup ,
} from '@mdi/js'

import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/SectionMain.vue'
import CardBoxWidget from '@/components/CardBoxWidget.vue'
import CardBox from '@/components/CardBox.vue'
import TableCoordinatorStudentUsers from '@/components/TableCoordinatorStudentUsers.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'

const props = defineProps({
  users: Array, // Users passed as a prop from Inertia
  user: Object,
})

const title = computed(() => props.user.course + ' Student Interns');

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
          label="Test"
        />
        <CardBoxWidget

          color="text-blue-500"
          :icon="mdiCartOutline"
          :number="7770"

          label="Test"
        />
        <CardBoxWidget

          color="text-red-500"
          :icon="mdiChartTimelineVariant"
          :number="256"

          label="Test"
        />
      </div>

      <SectionTitleLineWithButton :icon="mdiAccountGroup " :title="title" >
        </SectionTitleLineWithButton>
      <CardBox has-table >
        <TableCoordinatorStudentUsers :users="users"/>
      </CardBox>
    </SectionMain>
    </LayoutAuthenticated>
</template>
