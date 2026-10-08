import { useForm } from "@inertiajs/vue3";

export function useTaskForm(initialData = {}) {
    const form = useForm({
        title: initialData.title ?? "",
        description: initialData.description ?? "",
        status: initialData.status ?? "pending",
        deadline: initialData.deadline
            ? initialData.deadline.replace(' ', 'T').slice(0, 16)
            : '',
        assigned_to: initialData.assigned_to ?? "",
    });

    function submit() {
        if (initialData.id) {
            form.put(route("tasks.update", initialData.id));
        } else {
            form.post(route("tasks.store"));
        }
    }

    return { form, submit };
}
