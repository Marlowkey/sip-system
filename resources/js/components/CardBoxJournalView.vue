<script setup>
import BaseDivider from './BaseDivider.vue';

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
        type: String,
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
            <div class="flex justify-end mx-12">
                <a v-if="user === 'student'" :href="route('journals.edit', { id: props.id })"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Edit
                    <svg class="rtl:rotate-180 w-3 h-3 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
            </div>

        </div>
    </div>
</template>
