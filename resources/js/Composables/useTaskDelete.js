import { ref } from "vue";
import { router } from "@inertiajs/vue3";

export function useTaskDelete() {
    const showDeleteModal = ref(false);
    const taskToDelete = ref(null);

    function confirmDelete(taskId) {
        taskToDelete.value = taskId;
        showDeleteModal.value = true;
    }

    function destroy() {
        if (!taskToDelete.value) return;

        router.delete(route("tasks.destroy", taskToDelete.value), {
            preserveScroll: true,
            onFinish: () => {
                showDeleteModal.value = false;
                taskToDelete.value = null;
            },
        });
    }

    function closeModal() {
        showDeleteModal.value = false;
        taskToDelete.value = null;
    }

    return {
        showDeleteModal,
        taskToDelete,
        confirmDelete,
        destroy,
        closeModal,
    };
}
