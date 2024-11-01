<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main.js'
import {
    mdiAccountFile,
    mdiDomain,
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
import TableAdminHtes from '@/components/TableAdminHtes.vue'


const props = defineProps({
    establishments: Array,
})

</script>

<template>
    <LayoutAuthenticated>

        <Head title="Host Training Establishments" />


        <SectionMain v-if="establishments">
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiDomain" title="Host Training Establishments " main>
                <BaseButton label="Add" roundedFull :icon="mdiPlus" color="info" small routeName="htes.create" />
            </SectionTitleLineWithButton>
            <CardBox>
                <TableAdminHtes :establishments="establishments" />
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
