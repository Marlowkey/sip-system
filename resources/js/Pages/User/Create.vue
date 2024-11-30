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
import NotificationBar from '@/components/NotificationBar.vue'

const props = defineProps({
    user: Object,
    htes: Array,
    school_years: Array, // Add this line to accept school years
})

const userId = ref(props.user?.id);
const isEditMode = computed(() => !!props.user)
const title = computed(() => isEditMode.value ? 'Edit User' : 'Create User')

const form = useForm({
    first_name: props.user?.first_name || '',
    last_name: props.user?.last_name || '',
    middle_initial: props.user?.middle_initial || '',
    email: props.user?.email || '',
    student_number: props.user?.student_number || '',
    course: props.user?.course || '',
    block: props.user?.block || '',
    password: '',
    role: props.user?.role || 'student',
    host_training_establishment_id: props.user?.host_training_establishment_id || null,
    school_year_id: props.user?.school_year_id || null, // Add this line for school year
})


const roles = ref(["admin", "coordinator", "student",])


const courses = ref(["Information Technology", "Information System", "Computer Science",])

const selectedHostTrainingEstablishment = computed(() => {
    const selectedHte = props.htes.find(hte => hte.id === form.host_training_establishment_id);
    return selectedHte ? selectedHte.name : 'None';
});

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

const deleteUser = async (userId) => {
    try {
        const form = useForm({})
        await form.delete(route('users.destroy', userId))
        // Show success notification
    } catch (error) {
        console.error('Error deleting journal:', error)
        // Show error notification
    }
}

</script>

<template>
    <LayoutAuthenticated>
        <NotificationBar v-if="$page.props.flash.message" icon="mdiAlert" color="info" class="m-2">
            {{ $page.props.flash.message }}
        </NotificationBar>

        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccount" :title="title" main />
            <CardBox isForm @submit.prevent="submit">

                <FormField label="Name">
                    <FormControl v-model="form.first_name" type="text" placeholder="Enter First Name" />
                    <FormControl v-model="form.middle_initial" type="text" placeholder="Enter Middle Initial" />
                </FormField>
                <InputError :message="form.errors.first_name" />
                <InputError :message="form.errors.middle_initial" />

                <FormField label="Last Name">
                    <FormControl v-model="form.last_name" type="text" placeholder="Enter Last Name" />
                </FormField>
                <InputError :message="form.errors.last_name" />

                <FormField label="Email">
                    <FormControl v-model="form.email" type="email" placeholder="Enter Email Address" />
                </FormField>
                <InputError :message="form.errors.email" />


                <FormField label="Student Number">
                    <FormControl v-model="form.student_number" type="text" placeholder="Student Number (if student)" />
                </FormField>
                <InputError :message="form.errors.student_number" />


                <FormField label="Course">
                    <FormControl v-model="form.course" :options="courses" placeholder="Select Course">
                    </FormControl>
                    <FormControl v-model="form.block" type="text" placeholder="Enter Block/Section" />

                </FormField>
                <InputError :message="form.errors.course" />
                <InputError :message="form.errors.block" />

                <FormField label="Password">
                    <FormControl v-model="form.password" type="password" placeholder="Enter Password" />
                </FormField>
                <InputError :message="form.errors.password" />

                <FormField label="Role">
                    <FormControl v-model="form.role" :options="roles" placeholder="Select Role">
                    </FormControl>
                </FormField>
                <InputError :message="form.errors.role" />
                <FormField label="Host Training Establishment (if student)">

                    <select id="host_training_establishment" v-model="form.host_training_establishment_id"
                        placeholder="Select Host Training Establishment" class="w-full p-3 border rounded-md">
                        <option value="" disabled>Select Host Training Establishment</option>
                        <option v-for="hte in htes" :key="hte.id" :value="hte.id">
                            {{ hte.name }}
                        </option>
                    </select>

                </FormField>
                <InputError :message="form.errors.host_training_establishment" />
                <FormField label="School Year">
                    <select id="school_year" v-model="form.school_year_id" placeholder="Select School Year"
                        class="w-full p-3 border rounded-md">
                        <option value="" disabled>Select School Year</option>
                        <option v-for="schoolYear in school_years" :key="schoolYear.id" :value="schoolYear.id">
                            {{ schoolYear.year }} <!-- Assuming 'year' is a property of the school year -->
                        </option>
                    </select>
                </FormField>
                <InputError :message="form.errors.school_year_id" />

                <BaseButtons class="flex justify-end">
                    <BaseButton roundedFull small type="submit" color="blue" label="Submit" />
                    <BaseButton roundedFull small type="reset" color="whiteTwo" label="Reset" v-if="!isEditMode" />
                    <BaseButton roundedFull small color="red" label="Delete" @click.prevent="deleteUser(userId)"
                        v-if="isEditMode" />
                </BaseButtons>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
