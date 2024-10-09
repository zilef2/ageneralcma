<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import SwitchDarkMode from '@/Components/SwitchDarkMode.vue';
import {Head, router} from '@inertiajs/vue3'; //Link
import {reactive, ref, watch, watchEffect} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {form} from './form';
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Toast from "@/Components/Toast.vue";
import {throttle} from 'lodash';



const props = defineProps({
    cedLideres: Object,
    losSelect: Object,
    cedLideresGuardados: Object,
    cedLideresDiligenciados: Object,
    TodosDiligenciados: Boolean,
    infoEnviada: Object,
})

let anioActual = new Date().getFullYear();

const data = reactive({
    // modoCero:false,
    TamanoMAX: 5, //5MB
});
</script>

<template>
    <div>
        <Head :title="lang().label.welcome"/>

        <div class="relative flex justify-center min-h-screen bg-gray-300 dark:bg-gray-900 text-gray-900 dark:text-gray-100 items-center sm:pt-0">
            <section class="items-center w-full m-10 lg:mx-20 xl:mx-40 sm:px-6 lg:px-8 2xl:px-auto">
                <article class="flex pt-8 px-4 sm:px-0 sm:pt-0">
                    <div class="flex mx-auto text-center items-center">
                        <ApplicationLogo class="h-32 w-auto text-primary fill-current"/>

                    </div>
                    <div class="bg-gray-100 p-10 rounded-lg shadow-lg text-center">
                        <div class="flex justify-center mb-4">
                            <svg class="h-16 w-16 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 0C5.372 0 0 5.372 0 12s5.372 12 12 12 12-5.372 12-12S18.628 0 12 0zm0 22.344c-5.704 0-10.344-4.64-10.344-10.344S6.296 1.656 12 1.656 22.344 6.296 22.344 12 17.704 22.344 12 22.344zm-1.734-9.061h3.468v1.374H10.266zm.566-7.601h2.336c.312 0 .566.254.566.566v4.698c0 .312-.254.566-.566.566h-2.336c-.312 0-.566-.254-.566-.566V6.248c0-.312.254-.566.566-.566z"/>
                            </svg>
                            <svg class="h-16 w-16 text-green-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M21.736 0H2.264A2.264 2.264 0 000 2.264v19.472A2.264 2.264 0 002.264 24h19.472A2.264 2.264 0 0024 21.736V2.264A2.264 2.264 0 0021.736 0zM12 20.658a8.66 8.66 0 110-17.32 8.66 8.66 0 010 17.32zm0-14.345a5.685 5.685 0 100 11.37 5.685 5.685 0 000-11.37zm0 8.792a3.107 3.107 0 110-6.214 3.107 3.107 0 010 6.214z"/>
                            </svg>
                        </div>
                        <h1 class="text-4xl font-bold text-blue-600">Sistema de proposito general</h1>
                        <p class="mt-4 text-lg text-gray-700">
                            Aun no se ha definido un objetivo claro para este proycto
                        </p>
                    </div>
                    <div class="flex items-center">
                        <!-- <SwitchLangNavbar /> -->
                        <SwitchDarkMode/>
                    </div>
                </article>

                <!-- component -->
            </section>
            <p class="2xl:text-4xl text-sm mx-auto text-[#499884] font-medium">{{ anioActual }}</p>
        </div>

    </div>
</template>
<style>
::-webkit-scrollbar {
    height: 14px; /* Altura del scrollbar vertical */
    width: 4px; /* Ancho del scrollbar horizontal */
    border-radius: 2px;
}

/* Estilo del thumb del scrollbar */
::-webkit-scrollbar-thumb {
    background-color: #00004f; /* Color del thumb */
    border-radius: 1px; /* Radio de borde del thumb */
}

</style>
