<script setup>
import { reactive, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { mdiBallotOutline, mdiAccount, mdiMail, mdiFileDocumentAlertOutline   } from '@mdi/js'
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
import NotificationBarInCard from '@/components/NotificationBarInCard.vue'
import InputError from '@/components/InputError.vue'

const props = defineProps({
    journal: Object // Documents passed as a prop from Inertia
})

const journalId = ref(props.journal?.id);
const isEditMode = computed(() => !!props.journal)
const title = computed(() => isEditMode.value ? 'Edit Journal' : 'Create Journal')

const form = useForm({
    title: props.journal?.title || '',
    date: props.journal?.date || '',
    week: props.journal?.week || '',
    content: props.journal?.content || '',
    image_path: '',
})

const submit = async () => {
    try {
        // Append file to FormData if it exists
        const formData = new FormData()
        formData.append('title', form.title)
        formData.append('date', form.date)
        formData.append('content', form.content)
        if (form.image_path) {
            formData.append('image_path', form. image_path)
        }

        if (isEditMode.value) {
            await form.put(route('journals.update', journalId.value), formData)
        } else {
            await form.post(route('journals.store'), formData)
        }
        // Show success notification
    } catch (error) {
        console.error('Error submitting form:', error)
        // Show error notification
    }
}

const handleFileUpload = (event) => {
    form.image_path = event.target.files[0]
}

const deleteJournal = async (journalId) => {
    try {
        const form = useForm({})
        await form.delete(route('journals.destroy', journalId))
        // Show success notification
    } catch (error) {
        console.error('Error deleting journal:', error)
        // Show error notification
    }
}
</script>
<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiFileDocumentAlertOutline " :title="title" main>
            </SectionTitleLineWithButton>
            <CardBox isForm @submit.prevent="submit" enctype="multipart/form-data">
                <FormField label="Title">
                    <FormControl v-model="form.title" type="text" />
                </FormField>
                <InputError :message="form.errors.title" />

                <FormField label="Date">
                    <FormControl v-model="form.date" type="date" />
                </FormField>
                <InputError :message="form.errors.date" />
                <FormField label="Week">
                    <FormControl v-model="form.week" type="number" />
                </FormField>
                <InputError :message="form.errors.week" />
                <BaseDivider />

                <FormField label="Content" help="Describe the your experience.">
                    <FormControl v-model="form.content" type="textarea" />
                </FormField>
                <InputError :message="form.errors.content" />

                <FormField  label="Update image" help="Upload an image file">
                    <FormControl type="file"  @change="handleFileUpload"/>
                </FormField>
                <InputError :message="form.errors.image_path" />

                <template #footer>
                    <BaseButtons>
                        <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                        <BaseButton roundedFull small type="reset" color="whiteTwo" label="Reset" v-if="!isEditMode" />
                        <BaseButton roundedFull small color="red" label="Delete" @click.prevent="deleteJournal(journalId)"
                            v-if="isEditMode" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
