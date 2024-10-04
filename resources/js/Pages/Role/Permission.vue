<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    show: Boolean,
    title: String,
    role: Object,
})

const emit = defineEmits(["close"]);
// const groupedData = data.groupBy(item => item.category);

</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')" max-width="xl6">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-lg font-medium">{{ lang().label.permission }}</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-200">
                    {{ lang().label.all_permission }} {{ lang().label.role }} <b>{{ props.role?.name }}</b>
                </p>
                <div class="my-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                    <div v-for="(permission, index) in props.role?.permissions" :key="index"
                        class="flex justify-between w-full px-4 2xl:px-1 py-2 2xl:text-sm">
                        {{ ++index + ". " + permission.name }}
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="emit('close')"> {{ lang().button.close }} </SecondaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
