<script setup>
import BaseButton from './BaseButton.vue';
import BaseDivider from './BaseDivider.vue';
import { reactive, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
    mdiPlaylistEdit,
    mdiArrowRight,
    mdiPlus,
} from '@mdi/js'
import CardBox from './CardBox.vue';
import FormField from './FormField.vue';
import FormControl from './FormControl.vue';
import { format, parse } from 'date-fns';


const props = defineProps({
    journal: {
        type: Object,
    },
    user: {
        type: Object,
    },
    username: {
        type: String
    },
});

const isEditMode = computed(() => !!props.journal.feedback)
const journalId = ref(props.journal?.id);


const form = useForm({
    feedback: props.journal?.feedback || '',
})

const getImageUrl = (path) => {
    return `/storage/${path}`;
};


const showFeedbackTextarea = ref(false);

const toggleFeedbackTextarea = () => {
    showFeedbackTextarea.value = !showFeedbackTextarea.value;
};

const submit = async () => {
    try {
        await form.post(route('journals.addFeedback', journalId.value));
    } catch (error) {
        console.error('Error submitting form:', error)
    }
}

const markAsReviewed = async () => {
    try {
        await form.post(route('journals.markReviewed', journalId.value), {
            preserveScroll: true
        });
    } catch (error) {
        console.error('Error marking journal as reviewed:', error);
    }
};

const formatDate = (date) => {
    if (!date) return '---';
    return format(parse(date, 'yyyy-MM-dd', new Date()), 'MMMM dd, yyyy');
}

</script>

<template>
    <CardBox>
        <div class="max-w-18">
            <div class="flex justify-center p-6">
                <img class="rounded w-[28rem] h-[25rem] object-cover" :src="getImageUrl(journal.image_path)"
                    alt="Journal attached image" />
            </div>

            <div class="p-5">
                <h1 class="mb-2 text-2xl font-bold text-center text-gray-900 dark:text-white">{{ journal.title }}</h1>
                <p v-if="username" class="text-sm font-medium text-center text-gray-600 dark:text-gray-300">
                {{ username }}
            </p>
                <div class="flex items-center justify-between mx-10">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Week {{ journal.week }}</p>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ formatDate(journal.date) }}</p>
                </div>
                <BaseDivider />
                <div class="flex justify-center">
                    <p class="p-10 mb-3 font-normal text-justify text-gray-700 dark:text-gray-400">{{ journal.content }}
                    </p>
                </div>
                <div class="flex justify-between gap-4 mx-12">
                    <!-- Edit button only for students -->
                    <BaseButton label="Edit" v-if="user && user.role === 'student'" small :icon="mdiArrowRight"
                        roundedFull color="info" :href="route('journals.edit', { id: journal.id })" />

                    <!-- Mark as Reviewed and Feedback buttons only for coordinators -->
                    <template v-if="user && user.role === 'coordinator'">
                        <BaseButton small :label="journal.reviewed ? 'Reviewed' : 'Mark as Reviewed'"
                            :icon="mdiArrowRight" roundedFull :color="journal.reviewed ? 'success' : 'info'"
                            @click="markAsReviewed" :disabled="journal.reviewed" />

                        <BaseButton small label="Add / Edit Feedback" :icon="mdiPlus" roundedFull color="info"
                            @click="toggleFeedbackTextarea" />
                    </template>
                </div>

                <div v-if="journal.feedback" class="mt-4">
                    <div
                        class="flex items-center w-full flex-row my-8 py-6 pl-12 pr-4 isolate [unicode-bidi:isolate] rounded-xl relative before:content-[''] before:absolute before:w-1 before:h-4/5 before:bg-green-700 before:z-[10] before:left-6">
                        <p class="white-space-pre-wrap [&amp;:not(:first-child)]:mt-3">
                            <span class="font-semibold">Coordinator :</span> {{ journal.feedback }}
                        </p>
                    </div>
                </div>

                <div v-if="showFeedbackTextarea" class="flex flex-col">
                    <form @submit.prevent="submit">
                        <FormControl type="textarea" v-model="form.feedback" rows="4"
                            placeholder="Enter your feedback here..." class="my-4" />
                        <div class="flex justify-start gap-2 mt-2">
                            <BaseButton roundedFull small type="submit" color="success" label="Submit" />
                            <BaseButton roundedFull small type="reset" color="danger" label="Cancel"
                                @click="toggleFeedbackTextarea" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </CardBox>
</template>
