<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {reactive} from 'vue';
import pkg from 'lodash';

import {ArrowUpCircleIcon, TableCellsIcon} from '@heroicons/vue/24/solid';
import '@vuepic/vue-datepicker/dist/main.css'


const {_, debounce, pickBy} = pkg
const props = defineProps({
    title: String,
    numUsuarios: Number,
    UniversidadSelect: Object
})
const data = reactive({
    UniversidadSelect: null
})


const form = useForm({
    archivo1: null,
    archivo2: null,
    archivo3: null,
    archivo4: null,
    archivo5: null,
    Mes: 0,
    // fecha_fin: '2023-04-03T'+horas[1]+':00', //toerase
});

// const SeleccioneMes = [
//     { 'value': null, 'label': 'seleccione una quincena' },
//     { 'value': 1, 'label': 1 },
//     { 'value': 2, 'label': 2 }
// ]

function uploadFileCuentas() {
    form.post(route('upExCuentas'), {
        preserveScroll: true,
        onSuccess: () => {
            // emit("close")
            // form.reset()
            // data.respuesta = $page.props.flash.success
        },
        onError: () => {
            alert('Hay errores en algunos campos')
        },
        onFinish: () => null,
    });
}

function uploadFileTransacciones() {
    form.post(route('upExTransacciones'), {
        preserveScroll: true,
        onSuccess: () => {
            // emit("close")
            // form.reset()
            // data.respuesta = $page.props.flash.success
        },
        onError: () => {
            alert('Hay errores en algunos campos')
        },
        onFinish: () => null,
    });
}

function uploadFileComprobantes() {
    form.post(route('uploadFileComprobantes'), {
        preserveScroll: true,
        onSuccess: () => {
            // emit("close")
            // form.reset()
            // data.respuesta = $page.props.flash.success
        },
        onError: () => {
            alert('Hay errores en algunos campos')
        },
        onFinish: () => null,
    });
}
function uploadFileBancos() {
    form.post(route('uploadFileBancos'), {
        preserveScroll: true,
        onSuccess: () => {
            // emit("close")
            // form.reset()
            // data.respuesta = $page.props.flash.success
        },
        onError: () => {
            alert('Hay errores en algunos campos')
        },
        onFinish: () => null,
    });
}

// <!--<editor-fold desc="formatoNecesit">-->
let formatoNecesita = [
    'codigo_cuenta_contable',
    'numero_cuenta_bancaria',
    'banco',
    'tipo_de_cuenta',
    'tipo_de_recurso',
    'convenio',
]
let formatoNecesita2 = [
    'codigo_cuenta_contable',
    'nombre_cuenta',
    'codigo',
    'documento',
    'fecha_elaboracion',
    'descripcion',
    'comprobante',
    'valor_debito',
    'valor_credito',
    'nit',
    'nombre',
    'cod_costos',
    'desc_costos',
    'codigo_interno_cuenta',
    'codigo_tercero',
    'ccostos',
    'saldo_inicial',
    'saldo_final',
    'nombre_empresa',
    'nit_empresa',
    'documento_ref',
    'consecutivo',
    'periodo',
    'plan_cuentas',
]
let formatoNecesita3 = [
    'codigo',
    'descripcion',
    'comprobante',
    'notas',
    'numero_documento',
    'numero_cheque',
    'fecha_elaboracion',
    'consecutivo',
    'codigo_cuenta',
    'nombre_cuenta',
    'ccostos',
    'nit',
    'nombre',
    'valor_debito',
    'valor_credito',
    'codigo_asiento',
    'documento_ref',
    'plan_cuentas',
]
    // <!--</editor-fold>-->

// data.UniversidadSelect = vectorSelect(data.UniversidadSelect,props.UniversidadSelect,'una')

// const downloadExcel = () => { window.open('users/export/' + form.quincena + '/' + (form.fecha_ini.month) + '/' + form.fecha_ini.year, '_blank') }
</script>

<template>
    <Head :title="props.title"></Head>
    <AuthenticatedLayout>
        <div class="space-y-4">
            <div v-if="$page.props.flash.warning2" class="px-4 my-2 sm:px-0">
                <div class="rounded-lg overflow-hidden w-fit">
                    <div class="flex max-w-screen-xl shadow-lg rounded-lg">
                        <div class="bg-yellow-600 py-4 px-6 rounded-l-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="fill-current text-white" width="20" height="20">
                                <path fill-rule="evenodd"
                                      d="M8.22 1.754a.25.25 0 00-.44 0L1.698 13.132a.25.25 0 00.22.368h12.164a.25.25 0 00.22-.368L8.22 1.754zm-1.763-.707c.659-1.234 2.427-1.234 3.086 0l6.082 11.378A1.75 1.75 0 0114.082 15H1.918a1.75 1.75 0 01-1.543-2.575L6.457 1.047zM9 11a1 1 0 11-2 0 1 1 0 012 0zm-.25-5.25a.75.75 0 00-1.5 0v2.5a.75.75 0 001.5 0v-2.5z"></path>
                            </svg>
                        </div>
                        <div
                            class="px-8 py-6 bg-white rounded-r-lg flex justify-between items-center w-full border border-l-transparent border-gray-200">
                            <div v-html="$page.props.flash.warning2"></div>
                            <!-- <button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-gray-700" viewBox="0 0 16 16" width="20" height="20"> <path fill-rule="evenodd" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"> </path> </svg>
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section class="text-gray-600 body-font">
                    <div class="container px-5 py-24 mx-auto">

                        <!--                        v-if="can(['create user'])"-->
                        <div class="flex flex-wrap -m-4">
                            <!-- user trabajadors -->
                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                    <ArrowUpCircleIcon class=" h-24 lg:h-48 md:h-36 w-full object-cover object-center"/>

                                    <div class="p-6">
                                        <h3 class="title-font text-lg font-medium text-gray-900 mb-3">Subir Cuentas</h3>
                                        <p class="leading-relaxed mb-3"> El excel debe contar con el formato para cuentas</p>

                                        <form @submit.prevent="uploadFileCuentas" id="upload">
                                            <input type="file" @input="form.archivo1 = $event.target.files[0]"
                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                            <br><br>
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="100"
                                                      class="bg-sky-200">
                                                {{ form.progress.percentage }}%
                                            </progress>
                                            <div class="flex">
                                                <!--                                                can(['create user']) && -->
                                                <PrimaryButton v-show="form.archivo1 !== null" :disabled="form.archivo1 == null"
                                                               class=" my-4 rounded-md mx-2" :class="{ 'bg-gray-200' : form.archivo1 == null}">
                                                    {{ lang().button.subir }} excel
                                                </PrimaryButton>
                                            </div>
                                        </form>

                                        <h2 class="text-xl text-gray-900 dark:text-white mt-12">El formato necesita las siguientes columnas</h2>
                                        <ul class="list-decimal my-6 mx-5">
                                            <li v-for="campos in formatoNecesita" class="text-lg">
                                                {{ campos }}
                                            </li>
                                        </ul>

                                        <div class="flex items-center flex-wrap my-6">
                                            <!--                                            <a class="text-gray-500 inline-flex items-center md:mb-2 lg:mb-0">Numero de formularios enviados: </a>-->
                                            <span
                                                class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                <svg class="w-1 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none"
                                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <p class="text-lg">Cuentas inscritas: {{ props.numUsuarios }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                    <TableCellsIcon class=" h-24 lg:h-48 md:h-36 w-full object-cover object-center"/>

                                    <div class="p-6">
                                        <h3 class="title-font text-lg font-medium text-gray-900 mb-3">Subir Transferencias (Auxiliar)</h3>
                                        <p class="leading-relaxed mb-3"> El excel debe contar con el formato aprobado</p>

                                        <form @submit.prevent="uploadFileTransacciones" id="upload">
                                            <input type="file" @input="form.archivo2 = $event.target.files[0]"
                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                            <!--                                            <p class="font-bold text-red-600">AUN NO DISPONIBLE</p>-->
                                            <br><br>
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="100"
                                                      class="bg-sky-200">
                                                {{ form.progress.percentage }}%
                                            </progress>
                                            <div class="flex">
                                                <!--                                                can(['create user']) && -->
                                                <PrimaryButton v-show="form.archivo2 !== null" :disabled="form.archivo2 == null"
                                                               class=" my-4 rounded-md mx-2" :class="{ 'bg-gray-200' : form.archivo2 == null}">
                                                    {{ lang().button.subir }} excel
                                                </PrimaryButton>
                                            </div>
                                        </form>

                                        <h2 class="text-xl text-gray-900 dark:text-white mt-12">El formato necesita las siguientes columnas</h2>
                                        <ul class="list-decimal my-6 mx-5">
                                            <li v-for="campos in formatoNecesita2" class="text-lg">
                                                {{ campos }}
                                            </li>
                                        </ul>

                                        <div class="flex items-center flex-wrap my-6">
                                            <!--                                            <a class="text-gray-500 inline-flex items-center md:mb-2 lg:mb-0">Numero de formularios enviados: </a>-->
                                            <span
                                                class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                <svg class="w-1 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none"
                                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <p class="text-lg">Cuentas inscritas: {{ props.numUsuarios }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                    <TableCellsIcon class=" h-24 lg:h-48 md:h-36 w-full object-cover object-center"/>

                                    <div class="p-6">
                                        <h3 class="title-font text-lg font-medium text-gray-900 mb-3">Subir Comprobantes (CI o CE)</h3>
                                        <p class="leading-relaxed mb-3"> El excel debe contar con el formato aprobado</p>

                                        <form @submit.prevent="uploadFileComprobantes" id="upload">
                                            <input type="file" @input="form.archivo3 = $event.target.files[0]"
                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                            <!--                                            <p class="font-bold text-red-600">AUN NO DISPONIBLE</p>-->
                                            <br><br>
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="100"
                                                      class="bg-sky-200">
                                                {{ form.progress.percentage }}%
                                            </progress>

                                            <div class="flex">
                                                <!--                                                can(['create user']) && -->
                                                <PrimaryButton v-show="form.archivo3 !== null" :disabled="form.archivo3 == null"
                                                               class=" my-4 rounded-md mx-2" :class="{ 'bg-gray-200' : form.archivo3 == null}">
                                                    {{ lang().button.subir }} excel
                                                </PrimaryButton>
                                            </div>
                                        </form>

                                        <h2 class="text-xl text-gray-900 dark:text-white mt-12">El formato necesita las siguientes columnas</h2>
                                        <ul class="list-decimal my-6 mx-5">
                                            <li v-for="campos in formatoNecesita3" class="text-lg">
                                                {{ campos }}
                                            </li>
                                        </ul>

                                        <div class="flex items-center flex-wrap my-6">
                                            <!--                                            <a class="text-gray-500 inline-flex items-center md:mb-2 lg:mb-0">Numero de formularios enviados: </a>-->
                                            <span
                                                class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                <svg class="w-1 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none"
                                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <p class="text-lg">Cuentas inscritas: {{ props.numUsuarios }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 md:w-1/3">
                                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                    <TableCellsIcon class=" h-24 lg:h-48 md:h-36 w-full object-cover object-center"/>

                                    <div class="p-6">
                                        <h3 class="title-font text-lg font-medium text-gray-900 mb-3">Subir Cuentas bancarias flujo efectivo</h3>
                                        <p class="leading-relaxed mb-3"> El excel debe contar con el formato aprobado</p>

                                        <form @submit.prevent="uploadFileBancos" id="upload">
                                            <input type="file" @input="form.archivo4 = $event.target.files[0]"
                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                            <!--                                            <p class="font-bold text-red-600">AUN NO DISPONIBLE</p>-->
                                            <br><br>
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="100"
                                                      class="bg-sky-200">
                                                {{ form.progress.percentage }} %
                                            </progress>

                                            <div class="flex">
                                                <!--                                                can(['create user']) && -->
                                                <PrimaryButton v-show="form.archivo4 !== null" :disabled="form.archivo4 == null"
                                                               class=" my-4 rounded-md mx-2" :class="{ 'bg-gray-200' : form.archivo4 == null}">
                                                    {{ lang().button.subir }} excel
                                                </PrimaryButton>
                                            </div>
                                        </form>

                                        <h2 class="text-xl text-gray-900 dark:text-white mt-12">El formato necesita las siguientes columnas</h2>
                                        <ul class="list-decimal my-6 mx-5">
                                            <li v-for="campos in formatoNecesita4" class="text-lg">
                                                {{ campos }}
                                            </li>
                                        </ul>

                                        <div class="flex items-center flex-wrap my-6">
                                            <!--                                            <a class="text-gray-500 inline-flex items-center md:mb-2 lg:mb-0">Numero de formularios enviados: </a>-->
                                            <span
                                                class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                <svg class="w-1 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none"
                                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <p class="text-lg">Bancos inscritos: {{ props.numUsuarios }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
