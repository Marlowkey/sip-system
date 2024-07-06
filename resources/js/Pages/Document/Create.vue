<script setup>
import { reactive, ref } from 'vue'
import { useForm, router} from '@inertiajs/vue3'
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
import NotificationBarInCard from '@/components/NotificationBarInCard.vue'
import InputError from '@/components/InputError.vue'


const form = useForm({
  title: null,
  due_date: null,
  description: null,
})

const submit = async () => {
  console.log('Form data before submission:', form)

  try {
    await form.post(route('documents.store'))
    console.log('Form data after submission:', form)
    form.reset()
    // Show success notification
  } catch (error) {
    console.error('Error submitting form:', error)
    // Show error notification
  }
}
</script>


<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiBallotOutline" title="Create Document" main>
      </SectionTitleLineWithButton>
      <CardBox isForm @submit.prevent="submit">
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
          <FormControl  v-model="form.description"  type="textarea" />
        </FormField>
        <InputError :message="form.errors.description" />

        <template #footer>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Submit" />
            <BaseButton type="reset" color="info" outline label="Reset" />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
