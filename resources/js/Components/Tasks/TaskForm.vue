<script setup>
import { useTaskForm } from "@/Composables/useTaskForm";
import InputError from "@/Components/UI/InputError.vue";
import InputLabel from "@/Components/UI/InputLabel.vue";
import PrimaryButton from "../UI/PrimaryButton.vue";
import TextInput from "@/Components/UI/TextInput.vue";
import SelectInput from "../UI/SelectInput.vue";
import TextareaInput from "@/Components/UI/TextareaInput.vue";

const props = defineProps({
    statuses: Object,
    users: Array,
    initialData: Object,
    submitLabel: { type: String, default: "Создать" },
});

const { form, submit } = useTaskForm(props.initialData);
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="title" value="Название" />
            <TextInput
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
            />
            <InputError :message="form.errors.title" class="mt-2" />
        </div>

        <div>
            <InputLabel for="description" value="Описание" />
            <TextareaInput
                id="description"
                v-model="form.description"
                :rows="3"
            />
            <InputError :message="form.errors.description" class="mt-2" />
        </div>

        <div>
            <InputLabel for="status" value="Статус" />
            <SelectInput id="status" v-model="form.status">
                <option
                    v-for="(label, key) in statuses"
                    :key="key"
                    :value="key"
                >
                    {{ label }}
                </option>
            </SelectInput>
            <InputError :message="form.errors.status" class="mt-2" />
        </div>

        <div>
            <InputLabel for="deadline" value="Дедлайн" />
            <TextInput
                id="deadline"
                v-model="form.deadline"
                type="datetime-local"
                class="mt-1 block w-full"
            />
            <InputError :message="form.errors.deadline" class="mt-2" />
        </div>

        <div>
            <InputLabel for="assigned_to" value="Назначить" />

            <SelectInput id="assigned_to" v-model="form.assigned_to">
                <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                </option>
            </SelectInput>
            <InputError :message="form.errors.assigned_to" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">
                {{ submitLabel }}
            </PrimaryButton>
        </div>
    </form>
</template>
