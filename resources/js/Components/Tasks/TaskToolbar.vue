<script setup>
import { useTaskFilters } from "@/Composables/useTaskFilters";
import PrimaryButton from "../UI/PrimaryButton.vue";
import InputLabel from "@/Components/UI/InputLabel.vue";
import TextInput from "@/Components/UI/TextInput.vue";
import SelectInput from "../UI/SelectInput.vue";

const props = defineProps({
    statuses: Object,
    filters: Object,
});

const {
    filters: localFilters,
    applyFilters,
    resetFilters,
} = useTaskFilters(props.filters);
</script>

<template>
    <div class="mb-6 flex flex-wrap items-end gap-4">
        <div>
            <InputLabel for="status" value="Статус" />

            <SelectInput
                id="status"
                v-model="localFilters.status"
                @change="applyFilters"
            >
                <option value="">Все статусы</option>
                <option
                    v-for="(label, key) in statuses"
                    :key="key"
                    :value="key"
                >
                    {{ label }}
                </option>
            </SelectInput>
        </div>

        <div>
            <InputLabel for="deadline_from" value="Дедлайн с" />
            <TextInput
                id="deadline_from"
                v-model="localFilters.deadline_from"
                type="date"
                @change="applyFilters"
            />
        </div>

        <div>
            <InputLabel for="deadline_to" value="Дедлайн по" />
            <TextInput
                id="deadline_to"
                v-model="localFilters.deadline_to"
                type="date"
                @change="applyFilters"
            />
        </div>

        <div>
            <PrimaryButton @click="resetFilters">Сбросить</PrimaryButton>
        </div>
    </div>
</template>
