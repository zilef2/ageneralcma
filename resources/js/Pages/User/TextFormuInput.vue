<template>
    <div>
        <div class="inline-flex">
            <InputLabel for="elmodelo" :value="lang().label[elmodelo]"/>
            <small v-if="obligatorio" class="text-lg ml-1 font-bold"> * </small>
        </div>
        <TextInput name="elmodelo" :type="type" class="mt-1 block w-full" v-model="form[elmodelo]"
                   :placeholder="lang().placeholder[elmodelo]" :error="form.errors[elmodelo]"/>
        <InputError class="mt-2" :message="form.errors[elmodelo]"/>
    </div>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';

import InputLabel from '@/Components/InputLabel.vue';

import TextInput from '@/Components/TextInput.vue';

 const props = defineProps({
    type: String,
    form: Object,
    elmodelo: String,
    obligatorio: Boolean,
})
</script>