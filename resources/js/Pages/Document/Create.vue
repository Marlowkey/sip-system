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
    document: Object // Documents passed as a prop from Inertia
})

const documentId = ref(props.document?.id);
const isEditMode = computed(() => !!props.document)
const title = computed(() => isEditMode.value ? 'Edit SIP Requirement' : 'Create  SIP Requirement')

const form = useForm({
    title: props.document?.title || '',
    due_date: props.document?.due_date || '',
    description: props.document?.description || '',
    file_path: null,
})

const submit = async () => {
    try {
        // Append file to FormData if it exists
        const formData = new FormData()
        formData.append('title', form.title)
        formData.append('due_date', form.due_date)
        formData.append('description', form.description)
        if (form.file) {
            formData.append('file_path', form.file_path)
        }

        if (isEditMode.value) {
            await form.put(route('documents.update', documentId.value), formData)
        } else {
            await form.post(route('documents.store'), formData)
        }

    } catch (error) {
        console.error('Error submitting form:', error)

    }
}

const handleFileUpload = (event) => {
    form.file_path = event.target.files[0]
    form.file_path = file
}

const submitFile = async () => {
    try {
        const formData = new FormData();
        formData.append('file_path', form.file_path);

        await form.post(route('documents.updateFile', { id: documentId.value }), {
            data: formData,
            onSuccess: () => alert('File updated successfully.'),
        });
    } catch (error) {
        console.error('Error updating MOA file:', error);
    }
};

const deleteDocument = async (documentId) => {
    try {
        const form = useForm({})
        await form.delete(route('documents.destroy', documentId))
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
            <SectionTitleLineWithButton :icon="mdiFileDocumentAlertOutline " :title="title" main>
            </SectionTitleLineWithButton>

            <CardBox v-if="isEditMode" isForm @submit.prevent="submit" enctype="multipart/form-data" class="my-4">
                <FormField label=" File">
                    <div class="flex items-center space-x-2">
                        <input type="file" @change="handleFileUpload" />
                        <InputError :message="form.errors.file_path" />
                        <BaseButton small label="Update File" roundedFull color="blue" @click.prevent="submitFile" />
                    </div>
                </FormField>
            </CardBox>

            <CardBox isForm @submit.prevent="submit" enctype="multipart/form-data">
                <FormField label="Title">
                    <FormControl v-model="form.title" type="text" />
                </FormField>
                <InputError :message="form.errors.title" />

                <FormField label="Due Date">
                    <FormControl v-model="form.due_date" type="date" />
                </FormField>
                <InputError :message="form.errors.due_date" />

                <BaseDivider />

                <FormField label="Description" help="Describe the document. Max 255 characters">
                    <FormControl v-model="form.description" type="textarea" />
                </FormField>
                <InputError :message="form.errors.description" />

                <FormField v-if="!isEditMode" label="File" help="Upload a document file">
                    <FormControl type="file" @change="handleFileUpload"/>
                </FormField>
                <InputError :message="form.errors.file_path" />

                <template #footer>
                    <BaseButtons>
                        <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                        <BaseButton roundedFull small type="reset" color="whiteTwo" label="Reset" v-if="!isEditMode" />
                        <BaseButton roundedFull small color="red" label="Delete" @click.prevent="deleteDocument(documentId)"
                            v-if="isEditMode" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
