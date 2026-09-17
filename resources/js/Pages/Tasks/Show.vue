<script setup>
import { Head } from "@inertiajs/vue3";
import { useTaskDelete } from "@/Composables/useTaskDelete";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DescriptionItem from "@/Components/UI/DescriptionItem.vue";
import DeleteModal from "@/Components/Tasks/DeleteModal.vue";
import DangerButton from "@/Components/UI/DangerButton.vue";
import LinkButton from "@/Components/UI/LinkButton.vue";

defineProps({
    task: Object,
});

const { showDeleteModal, confirmDelete, destroy, closeModal } = useTaskDelete();
</script>

<template>
    <Head :title="`Задача: ${task.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Просмотр задачи {{ task.title }}
            </h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="space-y-4">
                        <DescriptionItem label="Название">
                            {{ task.titl}}
                        </DescriptionItem>

                        <DescriptionItem label="Описание">
                            {{ task.description ?? "—" }}
                        </DescriptionItem>

                        <DescriptionItem label="Статус">
                            {{ task.status_label }}
                        </DescriptionItem>

                        <DescriptionItem label="Дедлайн">
                            {{ task.deadline ?? "—" }}
                        </DescriptionItem>

                        <DescriptionItem label="Создал">
                            {{ task.user?.name ?? "—" }}
                        </DescriptionItem>

                        <DescriptionItem label="Назначена">
                            {{ task.assigned_user?.name ?? "—" }}
                        </DescriptionItem>
                    </dl>

                    <div class="mt-6 flex gap-3">
                        <LinkButton
                            :href="route('tasks.edit', task.id)"
                            variant="primary"
                        >
                            Редактировать
                        </LinkButton>
                        <DangerButton @click="confirmDelete(task.id)">
                            Удалить
                        </DangerButton>
                        <LinkButton
                            :href="route('tasks.index')"
                            variant="secondary"
                        >
                            Назад
                        </LinkButton>
                    </div>
                </div>
            </div>
        </div>

        <DeleteModal
            :show="showDeleteModal"
            @confirm="destroy"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>
