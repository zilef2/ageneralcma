<script setup>
import Breadcrumb from '@/Components/Breadcrumb.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ChevronRightIcon, KeyIcon, ShieldCheckIcon, UserIcon } from '@heroicons/vue/24/solid';
import { Head, Link } from '@inertiajs/vue3';
import { watchEffect } from 'vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";


    // roles: Number,
const props = defineProps({
    users: Number,
    roles: Number,
    rolesNameds: Object,
    numberPermissions: Number,
    horarios: Object,
})

const dashboard =[
    // 'Informes',
    // 'roles',
];
const dashLinks =[
    // 'Informes',
    // 'roles',
];
const dashSinS = dashboard.map((value, index, array) => {
    return value.slice(0,-1)
})
const colores =[
    'bg-gray-400',
    // 'bg-gray-500',
    // 'bg-gray-600',
];
const descripcion =[
    'Descripcion',
    // 'Descripcion',
    // 'descripcion',
];
const laImg =[
    'KeyIcon',
    // 'KeyIcon',
    // 'KeyIcon',
    // 'KeyIcon',
];
const downloadAnexos = () => {
    window.open('downloadAnexos', '_blank')
}
</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <Breadcrumb :title="'Resumen'" :breadcrumbs="[]" />
        <div class="space-y-4">
            <div class="grid gap-6 text-white shadow-sm py-1 sm:grid-cols-3 lg:grid-cols-3 dark:text-gray-100">
                <div
                    v-for="(model, index) in dashboard"
                    :key="index"
                    class="rounded-lg overflow-hidden transform transition-transform hover:scale-105"
                >
                    <div :class="['flex flex-col justify-between p-6 h-full', colores[index]]">
                        <div>
                            <p class="text-4xl font-extrabold">{{ model }}</p>
                            <p class="mt-2 text-lg">{{ descripcion[index] }}</p>
                        </div>
                        <div class="mt-4 flex items-center justify-between">

                            <Link :href="route(dashLinks[index]+'.index')" class="flex font-bold items-center space-x-4 mx-2">
                                <button class="px-4 py-2 bg-white text-black rounded-full shadow-md focus:outline-none">
                                    Ver
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-auto">
                <ul>
                    <li>
                        {{ props.horarios }}
                    </li>
                </ul>
            </div>


<!--            <h2 class="text-4xl mt-32">Roles</h2>-->
<!--            <div class="grid grid-cols-2 gap-2">-->
<!--                <div v-for="(rol, index) in rolesNameds"-->
<!--                    :key="index"-->
<!--                    class="col-span-1 px-4 py-6 my-2 w-5/6 flex justify-between-->
<!--                     bg-cyan-600/70 dark:bg-cyan-400/80 items-center overflow-hidden-->
<!--                     rounded-lg shadow-2xl hover:bg-red-600">-->
<!--                    <div class="flex flex-col">-->
<!--                        <p class="text-4xl font-bold capitalize">{{ rol }}</p>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <KeyIcon class="w-10 h-auto" />-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

        </div>

    </AuthenticatedLayout>
</template>
