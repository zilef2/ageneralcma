<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        default: null,
    },
    checked2: {
        type: [Array, Boolean],
        default: false,
    },
    value2: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
const proxyChecked2 = computed({
    get() {
        return props.checked2;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input
        type="radio"
        :value="value"
        v-model="proxyChecked"
        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary dark:text-primary shadow-sm focus:ring-primary/80 dark:focus:ring-primary dark:focus:ring-offset-gray-800 dark:checked:bg-primary dark:checked:border-primary"
    />
    <input
        type="radio"
        :value="value"
        v-model="proxyChecked2"
        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary dark:text-primary shadow-sm focus:ring-primary/80 dark:focus:ring-primary dark:focus:ring-offset-gray-800 dark:checked:bg-primary dark:checked:border-primary"
    />
</template>
