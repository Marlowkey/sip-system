<script setup>
import { computed, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { mdiCalendar, mdiFileDocumentAlertOutline } from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import InputError from '@/components/InputError.vue'

const props = defineProps({
    schoolYear: Object // Document passed as a prop from Inertia
})

const schoolYearId = ref(props.schoolYear?.id)
const isEditMode = computed(() => !!props.schoolYear)
const title = computed(() => isEditMode.value ? 'Update School Year' : 'Add School Year')

const form = useForm({
    year: props.schoolYear?.year || '' // Initial value for the year field
})


const submit = async () => {
    try {
        if (isEditMode.value) {
            await form.put(route('schoolyears.update', schoolYearId.value))
        } else {
            await form.post(route('schoolyears.store'))
        }
    } catch (error) {
        console.error('Error submitting form:', error)
    }
}

const deleteSchoolYear = async (schoolYearId) => {
    try {
        const form = useForm({})
        await form.delete(route('schoolyears.destroy', schoolYearId))
    } catch (error) {
        console.error('Error deleting school year:', error)
    }
}
</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiCalendar" :title="title" main />
            <CardBox isForm @submit.prevent="submit" enctype="multipart/form-data">
                <FormField label="School Year">
                    <FormControl v-model="form.year" type="text" />
                </FormField>
                <InputError :message="form.errors.year" />

                <BaseDivider />

                <template #footer>
                    <BaseButtons>
                        <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                        <BaseButton roundedFull small color="red" label="Delete" @click.prevent="deleteSchoolYear(schoolYearId)" v-if="isEditMode" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
