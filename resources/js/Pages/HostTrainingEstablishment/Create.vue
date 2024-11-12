<script setup>
import { reactive, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { mdiBallotOutline, mdiAccount, mdiMail, mdiFileDocumentAlertOutline } from '@mdi/js'
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
    establishment: Object // Documents passed as a prop from Inertia
})

const establishmentId = ref(props.establishment?.id);
const isEditMode = computed(() => !!props.establishment)
const title = computed(() => isEditMode.value ? 'Update HTE' : 'Add  HTE')

const form = useForm({
    name: props.establishment?.name || '',
    email: props.establishment?.email || '',
    address: props.establishment?.address || '',
    moa_file_path: null,
    moa_validity_period: props.establishment?.moa_validity_period || '',
})


const handleFileUpload = (event) => {
    form.moa_file_path = event.target.files[0];
};


const submit = async () => {
    try {
        if (isEditMode.value) {
            await form.put(route('htes.update', establishmentId.value), {
                transformRequest: (data) => {
                    const formData = new FormData();
                    for (let key in data) {
                        formData.append(key, data[key]);
                    }
                    return formData;
                },
            });
        } else {
            await form.post(route('htes.store'), {
                transformRequest: (data) => {
                    const formData = new FormData();
                    for (let key in data) {
                        formData.append(key, data[key]);
                    }
                    return formData;
                },
            });
        }
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const submitMoaFile = async () => {
    try {
        const formData = new FormData();
        formData.append('moa_file_path', form.moa_file_path);

        await form.post(route('htes.updateMoaFile', { id: establishmentId.value }), {
            data: formData,
            onSuccess: () => alert('MOA file updated successfully.'),
        });
    } catch (error) {
        console.error('Error updating MOA file:', error);
    }
};

const deleteEstablishment = async (establishmentId) => {
    try {
        const form = useForm({})
        await form.delete(route('htes.destroy', establishmentId))
    } catch (error) {
        console.error('Error deleting document:', error)
    }
}
</script>
<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiFileDocumentAlertOutline" :title="title" main />
            <CardBox v-if="isEditMode" isForm @submit.prevent="submit" enctype="multipart/form-data" class="my-4">
                <FormField label="MOA File">
                    <div class="flex items-center space-x-2">
                        <input type="file" @change="handleFileUpload" />
                        <InputError :message="form.errors.moa_file_path" />
                        <BaseButton small label="Update MOA File" roundedFull color="blue" @click.prevent="submitMoaFile" />
                    </div>
                </FormField>
            </CardBox>

            <CardBox isForm @submit.prevent="submit" enctype="multipart/form-data">
                <FormField label="Name">
                    <FormControl v-model="form.name" type="text" />
                </FormField>
                <InputError :message="form.errors.name" />

                <FormField label="Email">
                    <FormControl v-model="form.email" type="email" />
                </FormField>
                <InputError :message="form.errors.email" />

                <FormField label="Address">
                    <FormControl v-model="form.address" type="text" />
                </FormField>
                <InputError :message="form.errors.address" />

                <FormField v-if="!isEditMode" label="MOA File">
                    <FormControl v-model="form.moa_file_path" type="file" @change="handleFileUpload" />
                </FormField>
                <InputError :message="form.errors.moa_file_path" />

                <FormField label="MOA Validity Period">
                    <FormControl v-model="form.moa_validity_period" type="date" />
                </FormField>
                <InputError :message="form.errors.moa_validity_period" />
                <BaseDivider />
                <template #footer>
                    <BaseButtons>
                        <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                        <BaseButton roundedFull small type="reset" color="whiteTwo" v-if="!isEditMode" />
                        <BaseButton roundedFull small color="red" label="Delete"
                            @click.prevent="deleteEstablishment(establishmentId)" v-if="isEditMode" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
