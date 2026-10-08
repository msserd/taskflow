<script setup>
const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    rows: { type: Number, default: 3 },
});
</script>

<template>
    <textarea
        v-model="model"
        :rows="props.rows"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    ></textarea>
</template>
