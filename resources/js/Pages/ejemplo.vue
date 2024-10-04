<script setup>
import Breadcrumb from '@/Components/Breadcrumb.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {ChevronRightIcon, KeyIcon, ShieldCheckIcon, UserIcon} from '@heroicons/vue/24/solid';
import {Head, Link} from '@inertiajs/vue3';
import {watchEffect} from 'vue';


// roles: Number,
const props = defineProps({
    users: Number,
    formulariosEnviados: Number,
    formulariosGuardados: Number,
    permissions: Number,
    nanexos: Number,
})

const dashboard = [
    'users',
    'formulariosEnviados',
    'formulariosGuardados',
    // 'roles',
];
const dashSinS = dashboard.map((value, index, array) => {
    return value.slice(0, -1)
})
const downloadAnexos = () => {
    window.open('downloadAnexos', '_blank')
}

</script>

<template>

    <Head title="Dashboard"/>
    <AuthenticatedLayout>
        <Breadcrumb :title="'Resumen'" :breadcrumbs="[]"/>
        <div class="bg-gray-100 dark:bg-gray-900 py-2 md:py-16 lg:py-2">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 md:p-10 lg:p-12">
                    <h1 class="text-3xl font-bold text-[#499884] dark:text-gray-100 mb-6">Necesidades económicas anuales por dependencia</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        Llena el siguiente formulario para que podamos ayudarte.
                    </p>
                    <form class="space-y-6">
                        <div>
                            <label htmlFor="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Necesidad
                            </label>
                            <textarea id="message"
                                name="message"
                                rows={4}
                                class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Plantea tu necesidad aquí..."
                            />
                        </div>
                        <div>
                            <label htmlFor="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Justificación
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autoComplete="email"
                                required
                                class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Porque si"
                            />
                        </div>
                        <div class="flex items-center">
                            <input
                                id="terms"
                                name="terms"
                                type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label htmlFor="terms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Acepto los términos y condiciones
                            </label>
                        </div>
                        <div>
                            <label htmlFor="plan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Proceso que solicita presupuesto
                            </label>
                            <select
                                id="plan"
                                name="plan"
                                class="block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                                <option>Opción 1</option>
                                <option>Opción 2</option>
                                <option>Opción 3</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de usuario</label>
                            <div class="flex items-center">
                                <input
                                    id="individual"
                                    name="user-type"
                                    type="radio"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                />
                                <label htmlFor="individual" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Lider de Area
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="business"
                                    name="user-type"
                                    type="radio"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                />
                                <label htmlFor="business" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Planeacion (administrador)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="business"
                                    name="user-type"
                                    type="radio"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                />
                                <label htmlFor="business" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Ambos
                                </label>
                            </div>
                        </div>
                        <div>
                            <label htmlFor="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Adjuntar archivo
                            </label>
                            <input
                                id="file"
                                name="file"
                                type="file"
                                class="hidden w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <button
                                type="submit"
                                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
