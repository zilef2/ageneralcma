<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {onMounted, reactive, watchEffect} from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import "vue-select/dist/vue-select.css";
import {calcularEdad} from "@/global";

import TextFormuInput from "@/Pages/User/TextFormuInput.vue";

const props = defineProps({
    show: Boolean,
    title: String,
    user: Object,
    titulos: Object, //parametros de la clase principal
    roles: Object,
    numberPermissions: Number,
    losSelect: Object,
})

const emit = defineEmits(["close"]);
const data = reactive({
    params: {
        pregunta: ''
    },
    onceTime: true,
    sexo: [{title: 'Masculino', value: 'Masculino'}, {title: 'Femenino', value: 'Femenino'}],
    // empresa_id: props.losSelect['empresa']

})
//very usefull
const justNames = props.titulos.map(names => names['order'])
let form = useForm({
    ...Object.fromEntries(justNames.map(field => [field, ''])),
    role: ''
});
const roles = props.roles?.map(role => ({
    label: role.name.replace(/_/g, " "),
    value: (role.name)
}))

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        form.firma = reader.result;
    };
}


watchEffect(() => {
    if (props.show) {
        form.errors = {}
        // if(data.onceTime){

        form.role = props.user?.roles == 0 ? '' : props.user?.roles[0].name


        form.name = props.user?.name
        form.email = props.user?.email

        form.identificacion = props.user?.identificacion
        form.sexo = props.user?.sexo
        form.fecha_nacimiento = props.user?.fecha_nacimiento
        form.celular = props.user?.celular

        form.cargo = props.user?.cargo
        form.tipo_user = props.user?.tipo_user
        form.firma = props.user?.firma
        data.onceTime = false
        // }
    } else {
        form.name = null
        form.email = null
        form.role = null

        form.identificacion = null
        form.sexo = null
        form.fecha_nacimiento = null
        form.celular = null
        form.area = null
        form.cargo = null
    }
})


onMounted(() => {
    if (props.numberPermissions > 900) {
        const valueRAn = Math.floor(Math.random() * (9000) + 1)
        form.name = 'nombre prueba ' + (valueRAn);
        form.email = 'hola+' + (valueRAn) + '@hola.com';
        form.identificacion = (valueRAn + valueRAn * valueRAn);
        form.cargo = 'cargo prueba ' + (valueRAn);
        form.celular = 'celular prueba ' + (valueRAn);
        form.area = 'area prueba ' + (valueRAn);
        // form.hora_inicial = '0'+valueRAn+':00'//temp
        // form.fecha = '2023-06-01'

    }
});

const printForm = [
    'name',
    'email',
    'role',
    'identificacion',
    'sexo',
    'fecha_nacimiento',
    'cargo',
    // 'celular',
    'area',

    // 'firma',
    'tipo_user',
];

function ValidarVacios() {
    let result = true
    printForm.forEach(element => {
        if (!form[element]) {
            data.CampoError = element
            result = false
            return result
        }
    });
    return result
}


const update = () => {
    form.put(route('user.update', props.user?.id), {
    // form.post(route('user.update', props.user?.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close")
            form.reset()
        },
        onError: () => null,
        onFinish: () => null,
    })
}
const flow = ['year', 'month', 'calendar'];
const tipo_user = [{label: 'SST', value: 'SST'}, {label: 'SGA', value: 'SGA'}];
const sexos = [ { label: 'Masculino', value: 'Masculino' }, { label: 'Femenino', value: 'Femenino' } ];
</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')" max-width="xl6">
            <form class="p-6 mb-12" @submit.prevent="update">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    {{ lang().label.edit }} {{ props.title }}
                </h2>
                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">
                    {{ lang().label.RequiredFields }}
                </h3>
                <div class="mt-6 mb-20 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
                        <TextFormuInput :form="form" :type="'text'"
                                        :elmodelo="'name'"
                                        :obligatorio=true
                        />
                        <div>
                            <div class="flex justify-between ">
                                <InputLabel for="fecha_nacimiento" :value="lang().label.fecha_nacimiento"/>
                                <div v-if="form.fecha_nacimiento" class="text-sm  text-right">
                                    <p class="text-right">Edad:ㅤ{{ calcularEdad(form.fecha_nacimiento) }}</p>
                                </div>
                            </div>
                            <VueDatePicker :is-24="false" :day-names="daynames" :format="formatToVue" :flow="flow"
                                           auto-apply :enable-time-picker="false" id="fecha_nacimiento"
                                           v-model="form.fecha_nacimiento" required :placeholder="lang().placeholder.fecha_nacimiento"
                                           :error="form.errors.fecha_nacimiento" class="mt-4 w-full z-100 absolute"/>
                            <InputError class="mt-2" :message="form.errors.fecha_nacimiento"/>
                        </div>

                        <TextFormuInput :form="form" :type="'email'"
                                        :elmodelo="'email'"
                                        :obligatorio=true
                        />
                        <TextFormuInput :form="form" :type="'number'"
                                        :elmodelo="'identificacion'"
                                        :obligatorio=true
                        />
                        <TextFormuInput :form="form" :type="'text'"
                                        :elmodelo="'cargo'"
                                        :obligatorio=true
                        />

                        <div>
                            <div class="inline-flex">
                                <InputLabel for="role" :value="lang().label.role"/>
                                <small class="text-lg ml-1 font-bold"> * </small>
                            </div>
                            <SelectInput id="role" class="mt-1 block w-full" v-model="form.role" required :dataSet="roles">
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.role"/>
                        </div>

                        <div>
                            <div class="inline-flex">
                                <InputLabel for="sexo" :value="lang().label.sexo"/>
                                <small class="text-lg ml-1 font-bold">ㅤ</small>
                            </div>
                            <SelectInput id="sexo" class=" block w-full" v-model="form.sexo" required :dataSet="sexos">
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.sexo"/>
                        </div>
                        <div>
                            <div class="inline-flex">
                                <InputLabel for="tipo_user" :value="lang().label.tipo_user"/>
                                <small class="text-lg ml-1 font-bold">ㅤ</small>
                            </div>
                            <SelectInput id="tipo_user" class=" block w-full" v-model="form.tipo_user" required :dataSet="tipo_user">
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.tipo_user"/>
                        </div>

                        <TextFormuInput :form="form"
                                        :type="'number'"
                                        :elmodelo="'celular'"
                                        :obligatorio=false
                        />

                        <div class="my-12 md:col-span-2">
                            <InputLabel for="firma" :value="lang().label.firma" class="text-xl text-center"/>
                            <p class="text-center">Por favor, use un formato de imagen como jpeg, png, gif, webp </p>
                            <div id="firma" class="mt-2 mx-auto text-center">
                                <input type="file" name="firma" id="firma" @change="handleFileUpload"
                                       accept="image/jpeg,image/png,image/gif,image/webp" class="text-center">
                            </div>
                        </div>
                        <div class="my-12">
                            <div id="foto" class="mt-2 xs:mx-auto mx-1 text-center">
                                <div v-if="form.firma">
                                    <img :src="form.firma" alt=" No firma"
                                         class="w-full p-0 mx-auto
                                           lg:max-w-[290px] h-auto text-center
                                           shadow-md hover:shadow-2xl rounded-xl
                                           brightness-75 backdrop-sepia
                                           transition duration-500 ease-in-out transform hover:scale-75
                                    "/>
                                </div>
                                <p v-else clas="text-lg">Sin firma</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end my-8">
                    <SecondaryButton :disabled="form.processing" @click="emit('close')"> {{ lang().button.close }}</SecondaryButton>
                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                   @click="update">
                        {{ form.processing ? lang().button.save + '...' : lang().button.save }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
