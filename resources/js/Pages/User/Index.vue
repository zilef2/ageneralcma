<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import {computed, reactive, watch, watchEffect} from 'vue';
import pkg from 'lodash';
import Pagination from '@/Components/Pagination.vue';
import {CheckBadgeIcon, ChevronUpDownIcon, PencilIcon, TrashIcon} from '@heroicons/vue/24/solid';
import Checkbox from '@/Components/Checkbox.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InfoButton from '@/Components/InfoButton.vue';
import vSelect from 'vue-select'

import Create from "@/Pages/User/Create.vue";
import Edit from "@/Pages/User/Edit.vue";
import Delete from "@/Pages/User/Delete.vue";
import DeleteBulk from "@/Pages/User/DeleteBulk.vue";


const {_, debounce, pickBy} = pkg
const props = defineProps({
    title: String,
    filters: Object,
    users: Object,
    roles: Object,
    breadcrumbs: Object,
    perPage: Number,
    numberPermissions: Number,
    losSelect: Object,
    funcionalidades: Array,
})

const data = reactive({
    params: {
        search: props.filters.search,
        field: props.filters.field,
        order: props.filters.order,
        perPage: props.perPage,
    },
    selectedId: [],
    multipleSelect: false,
    createOpen: false,
    editOpen: false,
    deleteOpen: false,
    deleteBulkOpen: false,
    user: null,
    ArchivoNombre: '',
    dataSet: usePage().props.app.perpage
})

const order = (field) => {
    data.params.field = field
    data.params.order = data.params.order === "asc" ? "desc" : "asc"
}

watch(() => _.cloneDeep(data.params), debounce(() => {
    let params = pickBy(data.params)
    router.get(route("user.index"), params, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
}, 150))

const selectAll = (event) => {
    if (event.target.checked === false) {
        data.selectedId = []
    } else {
        props.users?.data.forEach((user) => {
            data.selectedId.push(user.id)
        })
    }
}
const select = () => {
    if (props.users?.data.length === data.selectedId.length) {
        data.multipleSelect = true
    } else {
        data.multipleSelect = false
    }
}


const form = useForm({
    archivo1: '',
})

watchEffect(() => {
    data.ArchivoNombre = form.archivo1?.name
})

// text // number // dinero // date // datetime // foreign
const titulos = [
    {order: 'name', label: 'Nombre', type: 'text', required: true},
    {order: 'email', label: 'Email', type: 'text', required: true},

    {order: 'identificacion', label: 'Identificacion', type: 'text', required: true},
    {order: 'sexo', label: 'sexo', type: 'foreign', nameid: 'sexo_S', required: false},
    {order: 'fecha_nacimiento', label: 'Fecha nacimiento', type: 'date', required: false},
    {order: 'celular', label: 'celular', type: 'text', required: false},

    {order: 'cargo', label: 'cargo', type: 'text', required: false},
    {order: 'tipo_user', label: 'tipo_user', type: 'text', required: false},
    {order: 'firma', label: 'firma', type: 'text', required: false},
];
const capitalizeFirstLetter = (text) => {

    if (typeof text !== 'string' || text.length === 0) {
        return text;
    }
    return text.replace(/_/g, ' ')        // Reemplaza todos los guiones bajos con espacios
        .replace(/^(.)/, (match) => match.toUpperCase());
    // return str.charAt(0).toUpperCase() + str.slice(1);

};
</script>

<template>
    <Head :title="props.title"/>

    <AuthenticatedLayout>
        <Breadcrumb :title="title" :breadcrumbs="breadcrumbs" class="capitalize text-xl font-bold"/>
        <div class="space-y-4">
            <div class="px-4 sm:px-0">
                <div class="rounded-lg overflow-hidden w-fit">
                    <PrimaryButton v-show="can(['create user'])" class="rounded-none" @click="data.createOpen = true">
                        {{ lang().button.newa }} Persona
                    </PrimaryButton>
                    <Create :show="data.createOpen" @close="data.createOpen = false" :roles="props.roles"
                            v-if="can(['create user'])" :title="props.title" :titulos="titulos"
                            :numberPermissions="props.numberPermissions" :funcionalidades="props.funcionalidades"/>
                    <Edit :show="data.editOpen" @close="data.editOpen = false" :user="data.user" :roles="props.roles"
                          v-if="can(['update user'])" :title="props.title" :titulos="titulos" :losSelect="props.losSelect"
                          :numberPermissions="props.numberPermissions" :funcionalidades="props.funcionalidades"/>
                    <Delete :show="data.deleteOpen" @close="data.deleteOpen = false" :user="data.user"
                            :title="props.title"/>
                    <DeleteBulk :show="data.deleteBulkOpen"
                                @close="data.deleteBulkOpen = false, data.multipleSelect = false, data.selectedId = []"
                                :selectedId="data.selectedId" :title="props.title"/>
                </div>
            </div>
            <div class="relative bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex justify-between p-2">
                    <div class="flex space-x-2">
                        <SelectInput v-model="data.params.perPage" :dataSet="data.dataSet"/>
                        <DangerButton @click="data.deleteBulkOpen = true"
                                      v-show="data.selectedId.length !== 0 && can(['delete user'])" class="px-3 py-1.5"
                                      v-tooltip="lang().tooltip.delete_selected">
                            <TrashIcon class="w-5 h-5"/>
                        </DangerButton>
                    </div>
                    <TextInput v-if="props.numberPermissions >= 0" v-model="data.params.search" type="text"
                               class="block w-4/6 md:w-3/6 lg:w-2/6 rounded-lg" placeholder="Nombre, correo o identificación"/>
                </div>
                <div class="overflow-x-auto scrollbar-table">
                    <table class="w-full">
                        <thead class="uppercase text-sm border-t border-gray-200 dark:border-gray-700">
                        <tr class="dark:bg-gray-900/50 text-left">
                            <th class="px-2 py-4 text-center">
                                <Checkbox v-model:checked="data.multipleSelect" @change="selectAll"/>
                            </th>
                            <th class="px-2 py-4 text-center">Acciones</th>
                            <th class="px-2 py-4 text-center">#</th>
                            <th class="px-2 py-4 cursor-pointer" v-on:click="order('name')">
                                <div class="flex justify-between items-center">
                                    <span>{{ lang().label.name }}</span>
                                    <ChevronUpDownIcon class="w-4 h-4"/>
                                </div>
                            </th>
                            <th class="px-2 py-4 cursor-pointer" v-on:click="order('cargo')">
                                <div class="flex justify-between items-center"><span>{{ lang().label.cargo }}</span>
                                    <ChevronUpDownIcon class="w-4 h-4"/>
                                </div>
                            </th>
                            <th class="px-2 py-4 cursor-pointer">
                                <div class="flex justify-between items-center">
                                    <span>{{ lang().label.role }}</span>
                                    <ChevronUpDownIcon class="w-4 h-4"/>
                                </div>
                            </th>
                            <th class="px-2 py-4 cursor-pointer" v-on:click="order('identificacion')">
                                <div class="flex justify-between items-center">
                                    <span>{{ lang().label.identificacion }}</span>
                                    <ChevronUpDownIcon class="w-4 h-4"/>
                                </div>
                            </th>
<!--                            <th class="px-2 py-4 cursor-pointer" v-on:click="order('sexo')">-->
<!--                                <div class="flex justify-between items-center"><span>{{ lang().label.sexo }}</span>-->
<!--                                    <ChevronUpDownIcon class="w-4 h-4"/>-->
<!--                                </div>-->
<!--                            </th>-->
<!--                            <th class="px-2 py-4 cursor-pointer" v-on:click="order('celular')">-->
<!--                                <div class="flex justify-between items-center"><span>{{ lang().label.celular }}</span>-->
<!--                                    <ChevronUpDownIcon class="w-4 h-4"/>-->
<!--                                </div>-->
<!--                            </th>-->

                            <th v-show="props.funcionalidades?.tipo_user"  v-on:click="order('tipo_user')" class="px-2 py-4 cursor-pointer">
                                <div class="flex justify-between items-center"><span>{{ lang().label.tipo_user }}</span>
                                    <ChevronUpDownIcon class="w-4 h-4"/>
                                </div>
                            </th>
                            <th v-show="can(['delete user']) && props.funcionalidades?.firma" class="px-2 py-4 cursor-pointer">
                                <div class="flex justify-between items-center"><span>{{ lang().label.firma }}</span>
                                    <ChevronUpDownIcon class="w-4 h-4"/>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(user, index) in users.data" :key="index"
                            class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-200/30 hover:dark:bg-gray-900/20">
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3 text-center">
                                <input
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary dark:text-primary shadow-sm focus:ring-primary/80 dark:focus:ring-primary dark:focus:ring-offset-gray-800 dark:checked:bg-primary dark:checked:border-primary"
                                    type="checkbox" @change="select" :value="user.id" v-model="data.selectedId"/>
                            </td>
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">
                                <div class="flex justify-center items-center">
                                    <div class="rounded-md overflow-hidden">
                                        <InfoButton v-show="can(['update user']) && user.id === $page.props.auth.user.id" type="button"
                                                    @click="(data.editOpen = true), (data.user = user)"
                                                    class="px-2 py-1.5 rounded-none" v-tooltip="lang().tooltip.edit">
                                            <PencilIcon class="w-4 h-4"/>
                                        </InfoButton>
                                        <DangerButton v-show="can(['isSuper'])" type="button"
                                                      @click="(data.deleteOpen = true), (data.user = user)"
                                                      class="px-2 py-1.5 rounded-none" v-tooltip="lang().tooltip.delete">
                                            <TrashIcon class="w-4 h-4"/>
                                        </DangerButton>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3 text-center">{{ ++index }}</td>
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">
                                <span class="flex justify-start items-center">{{ user.name }}<CheckBadgeIcon
                                    class="ml-[2px] w-4 h-4 text-primary dark:text-white" v-show="user.email_verified_at"/></span>
                                <span class="flex justify-start items-center text-sm text-gray-600">{{ user.email }}</span>
                            </td>
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{ user.cargo }} </td>
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">
                                {{ user.roles.length == 0 ? 'No Rol' : capitalizeFirstLetter(user.roles[0].name) }}
                                {{ user.sexo === "Masculino" ? '🤷‍♂️' : '🤷‍♀️' }}
                            </td>
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{ user.identificacion }}</td>
<!--                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{ user.sexo }}</td>-->
<!--                            <td class="whitespace-nowrap py-4 px-2 sm:py-3">{{ user.celular }}</td>-->
                            <td class="whitespace-nowrap py-4 px-2 sm:py-3" v-show="props.funcionalidades?.tipo_user">{{ user.tipo_user }}</td>
                            <td v-show="can(['delete user']) && props.funcionalidades?.firma" class="whitespace-nowrap py-4 px-2 sm:py-3">
                                <div id="foto" class="mt-2  mx-auto text-center">
                                    <div v-if="user.firma">
                                        <img :src="user.firma" alt=" No firma"
                                             class="w-full p-2 mx-auto
                                               lg:max-w-[110px] h-auto text-center
                                               shadow-md hover:shadow-2xl rounded-xl
                                               brightness-75 backdrop-sepia
                                               transition duration-500 ease-in-out transform
                                               scale-75 blur-sm
                                               hover:scale-125 hover:blur-0"/>
                                    </div>
                                    <p v-else clas="text-lg">Sin firma</p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between items-center p-2 border-t border-gray-200 dark:border-gray-700">
                    <Pagination :links="props.users" :filters="data.params"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
