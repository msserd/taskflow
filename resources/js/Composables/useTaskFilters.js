import { router } from "@inertiajs/vue3";
import { reactive } from "vue";
import { debounce } from 'lodash'

export function useTaskFilters(initialFilters = {}) {
    const filters = reactive({
        status: initialFilters.status ?? "",
        deadline_from: initialFilters.deadline_from ?? "",
        deadline_to: initialFilters.deadline_to ?? "",
    });

    function applyFilters() {
        const query = Object.fromEntries(
            Object.entries(filters).filter(
                ([_, value]) => value !== "" && value !== null,
            ),
        );

        router.get(route("tasks.index"), query, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

    const applyFiltersDebounced = debounce(applyFilters, 1000)

    function resetFilters() {
        filters.status = "";
        filters.deadline_from = "";
        filters.deadline_to = "";
        applyFilters();
    }

    return { filters, applyFilters: applyFiltersDebounced, resetFilters };
}
