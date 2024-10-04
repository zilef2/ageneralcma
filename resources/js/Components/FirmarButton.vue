<template>
    <button v-if="!form.processing" @click="createInspection()"
            class="my-1 mx-auto shrink-0 rounded-md
                border border-green-600 bg-green-600 cursor-pointer
                px-8 py-1 text-sm font-medium text-white transition
                focus:outline-none
                hover:bg-transparent hover:text-black
                dark:hover:bg-blue-700 dark:hover:text-white">
        Firmar
    </button>
    <button v-else class="my-1 mx-auto shrink-0 rounded-md inline-flex
                border border-green-600 bg-green-600 cursor-pointer
                px-8 py-1 text-sm font-medium text-white transition
                focus:outline-none
                hover:bg-transparent hover:text-black
                dark:hover:bg-blue-700 dark:hover:text-white">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Firmando inspección...
    </button>
    
</template>
<script>
import {useForm} from "@inertiajs/vue3";

export default {
    name: 'FirmarButton',
    props: {
        inspeccionId: {
            type: Number,
            required: true,
        },
    },
    setup(props) {
        const form = useForm({
            inspeccionid: props.inspeccionId
        });

        const createInspection = () => {
            try {
                form.post(route('inspeccion.firmar'), {
                    replace: true,
                    preserveState: true,
                    preserveScroll: true,
                });

                console.log('Inspection created successfully:', response.data);
                form.reset(); // Reset form on success
            } catch (error) {
                console.error('Error creating inspection:', error);
            }
        };

        return {form, createInspection};
    },
    methods: {
        goBack() {
            if (typeof window !== 'undefined') {
                window.history.back();
            } else {
                alert('No fue posible la operación')
            }
        }
        // inspeccionid
    }
}
</script>
