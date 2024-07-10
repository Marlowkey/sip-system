<script setup>
const props = defineProps({
    progress: {
        type: Number,
        default: 0,
        required: true,
    }
})
</script>

<template>
    <div class="flex w-2/5 self-center lg:w-full h-2 bg-neutral-200 dark:bg-neutral-600">
        <div class="h-2 bg-blue-900" :style="{ width: `${props.progress}%` }"></div>
    </div>
</template>
