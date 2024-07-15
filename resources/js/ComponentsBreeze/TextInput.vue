<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class=" focus:border-blue-500 focus:ring-indigo-500 rounded-md shadow-sm p-2"
        v-model="model"
        ref="input"
    />
</template>
