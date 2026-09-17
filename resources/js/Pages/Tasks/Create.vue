<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import LinkButton from "@/Components/UI/LinkButton.vue";
import TaskForm from "@/Components/Tasks/TaskForm.vue";

defineProps({
    statuses: Object,
    users: Array,
});
</script>

<template>
    <Head title="Создать задачу" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Создать задачу
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <TaskForm :statuses="statuses" :users="users" />

                        <LinkButton
                            :href="route('tasks.index')"
                            variant="secondary"
                            class="mt-4 inline-block"
                        >
                            Назад
                        </LinkButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
