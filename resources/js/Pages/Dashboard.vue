<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'
import axios from 'axios';
import { ref, onMounted } from 'vue';

defineProps({ users: Array })
const users = ref()


const usersFunc = () => {
    axios.get('/users').then((response) => {
        users.value = response.data;
    })
}

onMounted(() => {
    usersFunc()
})
</script>

<template>
    <AppLayout title="Dashboard">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl p-5 sm:rounded-lg">
                    <h1 class="text-black font-bold">Mi usuario</h1>
                    <div class="grid grid-cols-3 gap-4 my-5 rounded-xl">
                        <div v-for="user in users" :key="user.id"
                            class="transition duration-300 ease-in-out hover:scale-105 cursor-pointer border rounded-lg p-3">
                            <div>
                                <Link :href="`/users/${user.id}`">
                                Nombre: {{ user.name }}
                                </Link>
                                <div>Correo electrónico: {{ user.email }}</div>
                            </div>
                        </div>
                        <Link :href="`/info-city`"
                            class="flex justify-center place-items-center transition duration-300 bg-slate-800 text-white ease-in-out hover:scale-105 cursor-pointer border rounded-lg p-3">
                        Agregar Ciudades
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
