<script setup>
import { reactive, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { mdiAccount, mdiEmailOutline, mdiShieldAccount } from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import InputError from '@/components/InputError.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'

const props = defineProps({
    user: Object
})

const userId = ref(props.user?.id);
const isEditMode = computed(() => !!props.user)
const title = computed(() => isEditMode.value ? 'Edit User' : 'Create User')

const form = useForm({
    first_name: props.user?.first_name || '',
    last_name: props.user?.last_name || '',
    middle_initial: props.user?.middle_initial || '',
    email: props.user?.email || '',
    course: props.user?.course || '',
    block: props.user?.block || '',
    password: '',
    role: props.user?.role || 'student',
    host_training_establishment: props.user?.host_training_establishment || '',
})

const submit = async () => {
    try {
        if (isEditMode.value) {
            await form.put(route('users.update', userId.value))
        } else {
            await form.post(route('users.store'))
        }
    } catch (error) {
        console.error('Error submitting form:', error)
    }
}

</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccount" :title="title" main />
            <CardBox isForm @submit.prevent="submit">

                <FormField label="First Name">
                    <FormControl v-model="form.first_name" type="text" />
                </FormField>
                <InputError :message="form.errors.first_name" />

                <FormField label="Last Name">
                    <FormControl v-model="form.last_name" type="text" />
                </FormField>
                <InputError :message="form.errors.last_name" />

                <FormField label="Middle Initial">
                    <FormControl v-model="form.middle_initial" type="text" />
                </FormField>
                <InputError :message="form.errors.middle_initial" />

                <FormField label="Email">
                    <FormControl v-model="form.email" type="email" />
                </FormField>
                <InputError :message="form.errors.email" />

                <FormField label="Course">
                    <FormControl v-model="form.course" as="select">
                        <option value="" disabled>Select Course</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Information System">Information System</option>
                    </FormControl>
                </FormField>
                <InputError :message="form.errors.course" />

                <FormField label="Block">
                    <FormControl v-model="form.block" type="text" />
                </FormField>
                <InputError :message="form.errors.block" />

                <FormField label="Password" v-if="!isEditMode">
                    <FormControl v-model="form.password" type="password" />
                </FormField>
                <InputError :message="form.errors.password" />

                <FormField label="Role">
                    <FormControl v-model="form.role" as="select">
                        <option value="student">Student</option>
                        <option value="coordinator">Coordinator</option>
                        <option value="admin">Admin</option>
                    </FormControl>
                </FormField>
                <InputError :message="form.errors.role" />

                <FormField label="Host Training Establishment">
                    <FormControl v-model="form.host_training_establishment" type="text" />
                </FormField>
                <InputError :message="form.errors.host_training_establishment" />

                <BaseButtons>
                    <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                    <BaseButton roundedFull small type="reset" color="whiteTwo" label="Reset" v-if="!isEditMode" />
                </BaseButtons>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
