<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    cyan:{
        type:Boolean,
        default: false,
    }
});

let colorButton = props.cyan ?
    'inline-flex items-center px-2 py-1.5 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-250' :
    'inline-flex items-center px-2 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'
</script>

<template>
    <button :type="props.type" :class="colorButton">
        <slot />
    </button>
</template>
