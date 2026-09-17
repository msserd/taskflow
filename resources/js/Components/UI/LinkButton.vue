<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    href: String,
    variant: {
        type: String,
        default: 'primary',
    },
})

const variants = {
    primary: 'font-semibold text-white bg-indigo-600  hover:bg-indigo-500',
    secondary: 'border border-gray-300 bg-white font-medium text-gray-700 hover:bg-gray-50',
    danger: 'bg-red-600 font-semibold text-white hover:bg-red-500',
}
</script>

<template>
    <Link
        :href="href"
        :class="[
            'rounded-md px-4 py-2 text-sm shadow-sm',
            variants[variant]
        ]"
    >
        <slot />
    </Link>
</template>
