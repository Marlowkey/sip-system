<script setup>
import { reactive, ref, computed } from 'vue'
import {
    mdiCalendarPlus,
    mdiCalendarRemove,
    mdiAccountArrowRight
} from '@mdi/js'
import { useForm } from '@inertiajs/vue3'
import { mdiBallotOutline, mdiAccount, mdiMail, mdiGithub } from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import FormCheckRadioGroup from '@/components/FormCheckRadioGroup.vue'
import FormFilePicker from '@/components/FormFilePicker.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import InputError from '@/components/InputError.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import { format } from 'date-fns';

const props = defineProps({
    user: Object, // Authenticated user passed as a prop from Inertia`
    attendance: Object // Documents passed as a prop from Inertia
})

const attendanceId = ref(props.attendance?.id);
const isEditMode = computed(() => !!props.attendance)
const title = computed(() => isEditMode.value ? 'Edit' : 'Time In/Out')
const now = new Date();
const formattedDate = format(now, 'yyyy-MM-dd');
const formattedTime = format(now, 'HH:mm');

const form = useForm({
    user_id: props.attendance?.user_id || props.user.id,
    date: props.attendance?.date || formattedDate,
    time_in_am: props.attendance?.time_in_am || formattedTime,
    time_out_am: props.attendance?.time_out_am || formattedTime,
    time_in_pm: props.attendance?.time_in_pm || formattedTime,
    time_out_pm: props.attendance?.time_out_pm || formattedTime,
});

const submit = async () => {

    try {
        if (isEditMode.value) {
            await form.put(route('attendances.update', attendanceId.value), {
                preserveScroll: true,
            });
        } else {
            await form.post(route('attendances.store'), {
                preserveScroll: true,
            });
        }
        // Show success notification
    } catch (error) {
        console.error('Error submitting form:', error);
        // Show error notification
    }
}


const deleteAttendance = async (documentId) => {
    try {
        const form = useForm({})
        await form.delete(route('attendances.destroy', attendanceId.value), {
            preserveScroll: true,
        })
        // Show success notification
    } catch (error) {
        console.error('Error deleting document:', error)
        // Show error notification
    }
}
</script>
<template>
    <LayoutAuthenticated>
        <SectionMain>
            <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
                {{ $page.props.flash.message }}
            </NotificationBar>

            <SectionTitleLineWithButton :icon="mdiAccountArrowRight" :title="title" main>
            </SectionTitleLineWithButton>
            <CardBox isForm @submit.prevent="submit">
                <FormField label="Date">
                    <FormControl v-model="form.date" type="date" />
                </FormField>
                <InputError :message="form.errors.date" />

                <FormField label="Time In/Out (AM)">
                    <FormControl v-model="form.time_in_am" type="time" :icon="mdiCalendarPlus" class="text-green-800" />
                    <FormControl v-model="form.time_out_am" type="time" :icon="mdiCalendarRemove"
                        class="text-red-800" />
                </FormField>
                <InputError :message="form.errors.time_in_am" />
                <InputError :message="form.errors.time_out_am" />
                <BaseDivider />

                <FormField label="Time In/Out (PM)">
                    <FormControl v-model="form.time_in_pm" type="time" :icon="mdiCalendarPlus" class="text-green-800" />
                    <FormControl v-model="form.time_out_pm" type="time" :icon="mdiCalendarRemove"
                        class="text-red-800" />
                </FormField>
                <InputError :message="form.errors.time_in_pm" />
                <InputError :message="form.errors.time_out_pm" />
                <template #footer>
                    <BaseButtons>
                        <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                        <BaseButton roundedFull small type="reset" color="whiteTwo" label="Reset" v-if="!isEditMode" />
                        <BaseButton roundedFull small color="red" label="Delete" @click="deleteAttendance(attendanceId)"
                            v-if="isEditMode" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
