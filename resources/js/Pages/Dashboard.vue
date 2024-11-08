<script setup>
import Breadcrumb from '@/Components/Breadcrumb.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {ChevronRightIcon, KeyIcon, ShieldCheckIcon, UserIcon} from '@heroicons/vue/24/solid';
import {Head, Link} from '@inertiajs/vue3';
import {watchEffect} from 'vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";


// roles: Number,
const props = defineProps({
    users: Number,
    roles: Number,
    rolesNameds: Object,
    numberPermissions: Number,
    Obtenidos: Object,
    Busqueda: Object,
})

const dashboard = [
    // 'Informes',
    // 'roles',
];
const dashLinks = [
    // 'Informes',
    // 'roles',
];
const downloadAnexos = () => {
    window.open('downloadAnexos', '_blank')
}

const agrupadoPorPrefijo = props.Obtenidos.losQueFaltan.reduce((acc, prestamo) => {
    const prefijoAula = prestamo.nombreAula.slice(0, 2); // Ejemplo: "A1", "C1", etc.

    if (!acc[prefijoAula]) {
        acc[prefijoAula] = [];
    }
    acc[prefijoAula].push(prestamo);

    return acc;
}, {});
console.log("=>(Dashboard.vue:33) agrupadoPorAula", agrupadoPorPrefijo);


// Ordenar y agrupar solo los prefijos específicos
const gruposEspecificos = ["A1", "A2", "C1", "C2", "C3"];
const prestamosAgrupados = gruposEspecificos.map(grupo => ({
    aula: grupo,
    prestamos: agrupadoPorPrefijo[grupo] || []
}));
</script>


<template>
    <Head title="Dashboard"/>
    <AuthenticatedLayout>
        <Breadcrumb :title="'Resumen'" :breadcrumbs="[]"/>
        <div class="space-y-4">
            <div class="grid gap-6 shadow-sm py-1 sm:grid-cols-1 dark:text-gray-100">
                <div class="mx-auto">
                    <p class="my-4 text-xl font-bold text-center">Los que faltan 10pm
                        ({{ props.Obtenidos.losQueFaltan.length }})
                        ({{ props.Obtenidos.articuloprestamo.length }})
                    </p>
                    <ul>
                        <div v-if="prestamosAgrupados" v-for="(grupo, index) in prestamosAgrupados" :key="index"
                             class="grid gap-6 shadow-sm py-1 sm:grid-cols-1 dark:text-gray-100">
                                <p class="text-2xl text-blue-600 col-span-full">{{ grupo.aula }} ({{ grupo.prestamos.length }})</p>
                                <div class="grid gap-1 shadow-sm py-1 sm:grid-cols-6">
                                    <ul v-for="(prestamo, index2) in grupo.prestamos" :key="index2" class="list-disc my-2">
                                        <li>doc : {{ prestamo.docenteId }} | Aula: {{ prestamo.aulaId }}</li>
                                        <li>fecha : {{ prestamo.fecha }}</li>
                                        <li>horario: {{ prestamo.horainicio }} | {{ prestamo.horafin }}</li>
                                        <li>observaciones : {{ prestamo.observaciones }}</li>
                                        <li>docente_nombre : {{ prestamo.docente_nombre }}</li>
                                        <li>nombreAula : {{ prestamo.nombreAula }}</li>
                                    </ul>
                            </div>
                        </div>

                        <div v-else> Sin Busqueda = {{ props.Busqueda }}</div>
                    </ul>
                </div>
            </div>
            <div class="grid gap-6 shadow-sm py-1 sm:grid-cols-1 dark:text-gray-100">
                <div class="mx-auto">
                    <p class="my-4 text-xl font-bold text-center">Busqueda</p>
                    <ul>
                        <div v-if="props.Busqueda"
                             class="grid gap-6 shadow-sm py-1 sm:grid-cols-3 dark:text-gray-100">
                            <li v-for="bus in props.Busqueda">
                                horaInicio : {{ bus.horaInicio }}
                                horaFin : {{ bus.horaFin }}
                                docid : {{ bus.docid }}
                                nombredoc : {{ bus.nombre }}
                                aulaId : {{ bus.aulaId }}
                                nombreAula : {{ bus.nombreAula }}

                            </li>
                        </div>
                        <div v-else> Sin Busqueda = {{ props.Busqueda }}</div>
                    </ul>
                </div>
            </div>
            <div class="grid gap-6 shadow-sm py-1 sm:grid-cols-3 4xl:grid-cols-3 dark:text-gray-100">
                <div class="mx-auto ">
                    <ul>
                        horarios
                        <div v-if="props.Obtenidos.horarios">
                            <li v-for="hor in props.Obtenidos.horarios">
                                aulaId : {{ hor.aulaId }}
                                docenteId : {{ hor.docenteId }}
                            </li>
                        </div>
                        <div v-else> Sin horarios</div>
                    </ul>
                </div>
                <div class="mx-auto">
                    <ul>
                        docentes
                        <div v-if="props.Obtenidos.docentes">
                            <li v-for="doc in props.Obtenidos.docentes">
                                nombre : {{ doc.nombre }}
                                id : {{ doc.id }}
                            </li>
                        </div>
                        <div v-else> Sin docentes</div>
                    </ul>
                </div>
                <div class="mx-auto">
                    <ul>
                        Aulas
                        <div v-if="props.Obtenidos.AulaAqui">
                            <li v-for="doc in props.Obtenidos.AulaAqui">
                                nombreAula : {{ doc.nombreAula }}
                                capacidad : {{ doc.capacidad }}
                                tipoAula : {{ doc.tipoAula }}
                                disponible : {{ doc.disponible }}
                            </li>
                        </div>
                        <div v-else> Sin docentes</div>
                    </ul>
                </div>
            </div>

            <div class="grid gap-6 shadow-sm py-1 sm:grid-cols-1 dark:text-gray-100">
                <div class="mx-auto">
                    <p class="my-4 text-xl font-bold text-center">Prestamos</p>
                    <ul>
                        <div v-if="props.Obtenidos.prestamo"
                             class="grid gap-6 shadow-sm py-1 sm:grid-cols-3 dark:text-gray-100">
                            <li v-for="doc in props.Obtenidos.prestamo">
                                docenteid : {{ doc.docenteId }}
                                aulaId : {{ doc.aulaId }}
                            </li>
                        </div>
                        <div v-else> Sin prestamo</div>
                    </ul>
                </div>
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
