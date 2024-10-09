<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import SwitchDarkMode from '@/Components/SwitchDarkMode.vue';
import SwitchLangNavbar from '@/Components/SwitchLangNavbar.vue';
</script>

<template>
    <div
        class="min-h-screen flex flex-col-2 sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-300">
        <div
            class="w-full sm:max-w-sm lg:max-w-lg my-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="grid grid-cols-1">
                <div class="space-y-6 px-6 py-4 lg:py-16">
                    <div class="flex mx-auto content-center items-center">
                        <ApplicationLogo class="block mx-auto h-64 w-auto fill-current" />
                        <!-- <p class="text-lg ml-2">{{ $page.props.app.name }}</p> -->
<!--                        <div class="flex space-x-2 items-center">-->
                            <!-- <SwitchLangNavbar /> -->
<!--                            <SwitchDarkMode />-->
<!--                        </div>-->
                    </div>
                    <p class="text-xl text-center">IUCMA correos</p>
                    <slot />
                </div>
<!--                <div-->
<!--                    class="hidden lg:flex lg:flex-col px-6 py-4-->
<!--                    justify-center items-center space-y-2 bg-gray-200 text-white">-->
<!--                    <slot name="illustration" />-->
<!--                </div>-->
            </div>
        </div>
    </div>
</template>
