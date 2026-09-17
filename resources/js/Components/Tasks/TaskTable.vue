<script setup>
import { Link, router } from "@inertiajs/vue3";
import { useTaskDelete } from "@/Composables/useTaskDelete.js";
import TableHeader from "@/Components/Table/TableHeader.vue";
import TableCell from "@/Components/Table/TableCell.vue";
import SortableHeader from "@/Components/Table/SortableHeader.vue";
import DeleteModal from "./DeleteModal.vue";

defineProps({
    tasks: Object,
    sort: String,
    direction: String,
    filters: Object,
});

const { showDeleteModal, confirmDelete, destroy, closeModal } = useTaskDelete();
</script>

<template>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <SortableHeader
                    field="id"
                    label="ID"
                    :current-sort="sort"
                    :current-direction="direction"
                    :filters="filters"
                />
                <SortableHeader
                    field="title"
                    label="Название"
                    :current-sort="sort"
                    :current-direction="direction"
                    :filters="filters"
                />
                <TableHeader>Статус</TableHeader>
                <SortableHeader
                    field="deadline"
                    label="Дедлайн"
                    :current-sort="sort"
                    :current-direction="direction"
                    :filters="filters"
                />
                <TableHeader>Назначена</TableHeader>
                <TableHeader>Действия</TableHeader>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="task in tasks.data" :key="task.id">
                <TableCell>{{ task.id }}</TableCell>
                <TableCell>{{ task.title }}</TableCell>
                <TableCell>{{ task.status_label }}</TableCell>
                <TableCell>{{ task.deadline ?? "—" }}</TableCell>
                <TableCell>{{ task.assigned_user?.name ?? "—" }}</TableCell>
                <TableCell class="flex flex-wrap gap-3">
                    <Link
                        :href="route('tasks.show', task.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                        >Просмотр</Link
                    >
                    <Link
                        :href="route('tasks.edit', task.id)"
                        class="text-yellow-600 hover:text-yellow-900"
                        >Редактировать</Link
                    >
                    <button
                        @click="confirmDelete(task.id)"
                        class="text-red-600 hover:text-red-900"
                    >
                        Удалить
                    </button>
                </TableCell>
            </tr>
        </tbody>
    </table>

    <p v-if="tasks.length === 0" class="py-4 text-center text-sm text-gray-500">
        Задач пока нет. Создайте первую!
    </p>

     <div v-if="tasks.links.length > 3" class="mt-4 flex flex-wrap gap-1">
        <component
            :is="link.url ? 'a' : 'span'"
            v-for="(link, i) in tasks.links"
            :key="i"
            :href="link.url"
            @click.prevent="link.url && router.get(link.url, {}, { preserveState: true })"
            class="rounded border px-3 py-1 text-sm"
            :class="{
                'bg-indigo-600 text-white': link.active,
                'text-gray-500': !link.url,
                'hover:bg-gray-100': link.url && !link.active,
            }"
            v-html="link.label"
        />
    </div>

    <DeleteModal
        :show="showDeleteModal"
        @confirm="destroy"
        @close="closeModal"
    />
</template>
