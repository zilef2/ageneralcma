<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import {reactive, watch} from 'vue';
import pkg from 'lodash';
import {PencilIcon} from '@heroicons/vue/24/solid';

import Create from '@/Pages/parametro/Create.vue';
import Edit from '@/Pages/parametro/Edit.vue';
import Delete from '@/Pages/parametro/Delete.vue';
import InfoButton from '@/Components/InfoButton.vue';
import {formatDate, PrimerasPalabras} from '@/global.ts';


const { _, debounce, pickBy } = pkg
    const props = defineProps({
        title: String,
        filters: Object,
        breadcrumbs: Object,

        fromController: Object,
        nombresTabla: Array,

    })

    const data = reactive({
        params: {
            search: props.filters.search,
            field: props.filters.field,
            order: props.filters.order,
        },
        selectedId: [],
        createOpen: false,
        editOpen: false,
        deleteOpen: false,
        generico: null,
    })


    watch(() => _.cloneDeep(data.params), debounce(() => {
        let params = pickBy(data.params)
        router.get(route("parametro.index"), params, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        })
    }, 150))


</script>

<template>
    <Head :title="props.title"></Head>

    <AuthenticatedLayout>
        <Breadcrumb :title="title" :breadcrumbs="breadcrumbs" class="capitalize text-xl font-bold"/>
        <div class="space-y-4">
            <div class="px-4 sm:px-0">
                <div class="rounded-lg overflow-hidden w-fit">
                    <!-- <PrimaryButton class="rounded-none" @click="data.createOpen = true" v-show="can(['create parametro'])">
                        {{ lang().button.add }}
                    </PrimaryButton> -->
                    <Create :show="data.createOpen" @close="data.createOpen = false" :title="props.title"
                        v-show="can(['create parametro'])" :parametrosSelect="data.parametrosSelect" />
                    <Edit :show="data.editOpen" @close="data.editOpen = false" :parametro="props.fromController"
                        v-show="can(['edit parametro'])" :title="props.title" :parametrosSelect="data.parametrosSelect" />
                    <Delete :show="data.deleteOpen" @close="data.deleteOpen = false" :parametro="props.fromController"
                        v-show="can(['delete parametro'])" :title="props.title" />
                </div>
            </div>
            <div class="relative bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex justify-between p-2">
                    <div class="flex space-x-2">
                        <!-- zona de acciones masivas -->
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table">
                    <table class="w-full">
                        <thead class="uppercase text-sm border-t border-gray-200 dark:border-gray-700">
                            <tr class="dark:bg-gray-900 text-left">
                                <th v-for="(titulos, indiceN) in nombresTabla[0]" :key="indiceN"
                                    class="px-2 py-4 cursor-pointer hover:bg-sky-50 dark:hover:bg-sky-800">
                                    <div class="flex justify-between items-center">
                                        <span>{{ titulos }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-200/30 hover:dark:bg-gray-900/20">
                                <td class="whitespace-nowrap py-4 px-2 sm:py-3">
                                    <div class="flex justify-start items-center">
                                        <div class="rounded-md overflow-hidden">
                                            <InfoButton type="button" @click="(data.editOpen = true)"
                                                class="px-2 py-1.5 rounded-none" v-tooltip="lang().tooltip.edit">
                                                <PencilIcon class="w-4 h-4" />
                                            </InfoButton>
                                        </div>
                                    </div>
                                </td>
                                <!-- <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{ (index+1) }}</td> -->
                                <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{
                                                                    formatDate(props.fromController.Fecha_creacion_parametro,false) }} </td>
                                <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{
                                                                    PrimerasPalabras(props.fromController.nombre,20) }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
