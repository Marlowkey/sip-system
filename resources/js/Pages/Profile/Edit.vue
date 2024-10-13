<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useMainStore } from '@/stores/main'
import { mdiAccount, mdiMail, mdiAsterisk, mdiFormTextboxPassword, mdiGithub } from '@mdi/js'
import SectionMain from '@/components/SectionMain.vue'
import CardBox from '@/components/CardBox.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import FormFilePicker from '@/components/FormFilePicker.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import UserCard from '@/components/UserCard.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'

const user = usePage().props.auth.user;

const profileForm = useForm({
    first_name: user.first_name,
    middle_initial: user.middle_initial,
    last_name: user.last_name,
    email: user.email,
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    avatarForm.avatar = file;
};

const avatarForm = useForm({
    avatar: null,
})

const submitAvatar = async () => {
    try {
        const formData = new FormData()
        if (avatarForm.avatar) {
            formData.append('avatar', avatarForm.avatar)
        }
        await avatarForm.post(route('profile.avatar'), formData)
    } catch (error) {
        console.error('Error submitting form:', error)
    }
}


const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccount" title="Profile" main>
            </SectionTitleLineWithButton>


            <UserCard :username="userName" class="mb-4">
                <!-- File upload form in the slot -->
                <template #file-upload>
                    <div class="flex flex-col items-center my-4">
                            <FormControl  transparent type="file" @change="handleFileUpload" class="w-60- h-auto"/>
                    </div>
                    <div class="flex flex-col items-center space-y-3">
                        <BaseButton small rounded-full color="info" @click="submitAvatar" label="Submit" />
                    </div>
                </template>
            </UserCard>



            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <CardBox is-form @submit.prevent="profileForm.patch(route('profile.update'))">
                    <FormField label="First Name" help="Required. Your first name">
                        <FormControl v-model="profileForm.first_name" :icon="mdiAccount" name="first_name" required
                            autocomplete="first_name" />
                    </FormField>

                    <FormField label="Middle Initial">
                        <FormControl v-model="profileForm.middle_initial" :icon="mdiAccount" name="middle_initial"
                            autocomplete="middle_initial" />
                    </FormField>

                    <FormField label="Last Name" help="Required. Your last name">
                        <FormControl v-model="profileForm.last_name" :icon="mdiAccount" name="last_name" required
                            autocomplete="last_name" />
                    </FormField>

                    <FormField label="E-mail" help="Required. Your e-mail">
                        <FormControl v-model="profileForm.email" :icon="mdiMail" type="email" name="email" required
                            autocomplete="email" />
                    </FormField>

                    <template #footer>
                        <BaseButtons class="flex justify-end">
                            <BaseButton small rounded-full color="info" type="submit" label="Submit" />
                        </BaseButtons>
                    </template>
                </CardBox>

                <CardBox is-form @submit.prevent="passwordForm.patch(route('profile.update'))">
                    <FormField label="Current password" help="Required. Your current password">
                        <FormControl v-model="passwordForm.current_password" :icon="mdiAsterisk" name="current_password"
                            type="password" required autocomplete="current-password" />
                    </FormField>

                    <BaseDivider />

                    <FormField label="New password" help="Required. New password">
                        <FormControl v-model="passwordForm.password" :icon="mdiFormTextboxPassword" name="password"
                            type="password" required autocomplete="new-password" />
                    </FormField>

                    <FormField label="Confirm password" help="Required. New password one more time">
                        <FormControl v-model="passwordForm.password_confirmation" :icon="mdiFormTextboxPassword"
                            name="password_confirmation" type="password" required autocomplete="new-password" />
                    </FormField>
                    <template #footer>
                        <BaseButtons class="flex justify-end">
                            <BaseButton small rounded-full type="submit" color="info" label="Submit" />
                        </BaseButtons>
                    </template>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
