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
    establishment: Object // Documents passed as a prop from Inertia
})

const establishmentId = ref(props.establishment?.id);
const isEditMode = computed(() => !!props.establishment)
const title = computed(() => isEditMode.value ? 'Update HTE' : 'Add  HTE')

const form = useForm({
    name: props.establishment?.name || '',
    email: props.establishment?.email || '',
    address: props.establishment?.address || '',
})

const submit = async () => {
    try {
        if (isEditMode.value) {
            await form.put(route('htes.update', establishmentId.value))
        } else {
            await form.post(route('htes.store'))
        }
    } catch (error) {
        console.error('Error submitting form:', error)
    }
}


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

                <BaseDivider />

                <template #footer>
                    <BaseButtons>
                        <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                        <BaseButton roundedFull small type="reset" color="whiteTwo" v-if="!isEditMode" />
                        <BaseButton roundedFull small color="red" label="Delete" @click.prevent="deleteEstablishment(establishmentId)" v-if="isEditMode" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
