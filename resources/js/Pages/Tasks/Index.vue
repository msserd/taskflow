<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import LinkButton from "@/Components/UI/LinkButton.vue";
import TaskToolbar from "@/Components/Tasks/TaskToolbar.vue";
import TaskTable from "@/Components/Tasks/TaskTable.vue";

defineProps({
    tasks: Object,
    statuses: Object,
    filters: Object,
    sort: String,
    direction: String,
});
</script>

<template>
    <Head title="Задачи" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Список задач
            </h2>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-gray-900">
            <div class="mb-6 flex items-center justify-between">
                <LinkButton :href="route('tasks.create')" variant="primary">
                    Создать задачу
                </LinkButton>
            </div>

            <TaskToolbar :statuses="statuses" :filters="filters" />

            <TaskTable
                :tasks="tasks"
                :sort="sort"
                :direction="direction"
                :filters="filters"
            />
        </div>
    </AuthenticatedLayout>
</template>
