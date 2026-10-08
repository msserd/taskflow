<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const flash = computed(() => page.props.flash);

const visible = ref(false);
watch(
    () => flash.value.success,
    (val) => {
        if (val) {
            visible.value = true;
            setTimeout(() => (visible.value = false), 5000);
        }
    },
    { immediate: true },
);
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="visible && flash.success"
            class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800 shadow-sm"
        >
            {{ flash.success }}
        </div>
    </Transition>
</template>
