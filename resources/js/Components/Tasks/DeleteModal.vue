<script setup>
import Modal from "../UI/Modal.vue";
import DangerButton from "../UI/DangerButton.vue";
import SecondaryButton from "../UI/SecondaryButton.vue";

defineProps({
    show: Boolean,
});

const emit = defineEmits(["confirm", "close"]);
</script>

<template>
    <Modal :show="show" @close="emit('close')">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Подтверждение удаления
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Вы уверены, что хотите удалить задачу?
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="emit('close')">
                    Отмена
                </SecondaryButton>

                <DangerButton @click="emit('confirm')"> Удалить </DangerButton>
            </div>
        </div>
    </Modal>
</template>
