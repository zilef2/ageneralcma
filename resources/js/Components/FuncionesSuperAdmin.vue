<template>
    <div>
        <form @submit.prevent="downClaro" id="downloadprestamo"
              class="hover:bg-gray-100">
            <button type="submit"
                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                {{ text }}
            </button>
        </form>
    </div>
</template>
<script>
export default {
    name: 'FuncionesSuperAdmin',
    data() {
        return {
            text: 'descargar BD',
            text2: 'Ver inspecciones borradas', //aquiii
        }
    },
    methods: {
        writeText() {
            this.text = 'Descargada'
        },
        downClaro() {
            window.open('downClaro', '_blank')
        }
    }
}
</script>
