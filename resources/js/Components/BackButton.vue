<template>
    <button @click="goBack()"
            class="my-1 mx-auto shrink-0 rounded-md 
            border border-blue-600 bg-blue-600
            cursor-pointer
             px-8 py-1 text-sm font-medium text-white transition
             hover:bg-transparent hover:text-blue-600
             focus:outline-none focus:ring active:text-blue-500
              dark:hover:bg-blue-700 dark:hover:text-white">
        Atrás
    </button>
</template>
<script>
export default {
    name: 'BackButton',
    methods: {
        goBack() {
            if (typeof window !== 'undefined') {
                window.history.back();
            }else{
                alert('No fue posible la operación')
            }
        }
    }
}
</script>