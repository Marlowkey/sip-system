<script setup>
import BaseButton from './BaseButton.vue';
import BaseDivider from './BaseDivider.vue';
import {
    mdiPlaylistEdit,
    mdiArrowRight
} from '@mdi/js'

const props = defineProps({
    id: {
        id: Number,
        required: true
    },
    image_path: {
        type: String,
        default: '/docs/images/blog/image-1.jpg'
    },
    week: {
        type: Number,
    },
    title: {
        type: String,
        default: 'Weekly Journal'
    },
    content: {
        type: String,
        default: 'Content here...'
    },
    date: {
        type: String,
    },
    user: {
        type: Object,
    }
});

const getImageUrl = (path) => {
    return `/storage/${path}`;
};

const markAsReviewed = () => {
    console.log(`Marking journal ${props.id} as reviewed.`);
};

</script>


<template>
    <CardBox>

    </CardBox>
    <div class="max-w-18">
        <div class="flex justify-center p-6 ">
            <img class="rounded w-[28rem] h-[25rem] object-cover " :src="getImageUrl(image_path)"
                alt="Journal attached image" />
        </div>

        <div class="p-5">
            <h1 class="mb-2 text-2xl font-bold  text-center text-gray-900 dark:text-white">{{ title }}
            </h1>
            <div class="flex items-center justify-between mx-10">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Week {{ week }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ date }}</p>
            </div>
            <BaseDivider />
            <div class="flex justify-center">
                <p class="mb-3 p-10 font-normal text-justify text-gray-700 dark:text-gray-400">{{ content }}</p>
            </div>
            <div  class="flex justify-end mx-12">
                <BaseButton label="Edit" v-if="user && user.role === 'student'" small :icon="mdiArrowRight " roundedFull color="info"  :href="route('journals.edit', { id: props.id })"/>
            </div>
        </div>
    </div>
</template>
