<script setup>
import { router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    field: String,
    label: String,
    currentSort: String,
    currentDirection: String,
    filters: Object,
});

const isActive = computed(() => props.currentSort === props.field);

const direction = computed(() => {
    if (!isActive.value) return "asc";
    return props.currentDirection === "asc" ? "desc" : "asc";
});

const indicator = computed(() => {
    if (!isActive.value) return "";
    return props.currentDirection === "asc" ? "↑" : "↓";
});

function sort() {
    router.get(
        route("tasks.index"),
        {
            ...props.filters,
            sort: props.field,
            direction: direction.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}
</script>

<template>
    <th
        @click="sort"
        class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700"
    >
        {{ label }}
        <span v-if="indicator" class="ml-1">{{ indicator }}</span>
    </th>
</template>
