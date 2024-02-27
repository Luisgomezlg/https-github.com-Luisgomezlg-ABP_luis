<script setup>
import SuccessOrError from '@/Components/SuccessOrError.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, usePage } from '@inertiajs/vue3'
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { TrashIcon } from '@heroicons/vue/24/outline'
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'

const people = ref([])
const selectedPerson = ref()
const page = usePage()
const ciudadesArray = ref([])
const modalOpen = ref(false), modalMessage = ref("")

let selected = ref(null)
let query = ref('')
let departamentos = ref([])


let filteredCountries = computed(() =>
    query.value === ''
        ? departamentos.value
        : departamentos.value.filter((departamentos) =>
            JSON.parse(departamentos.departamento.toLowerCase().includes(query.value.toLowerCase())),
        )
)

const selectDepartament = (valor) => {
    people.value = valor.ciudades
    selectedPerson.value = valor.ciudades[0]
}

const onValueGeneral = (value) => {
    modalOpen.value = value;
}

const ciudades = async () => {
    try {
        const response = await fetch('/ciudades/' + page.props.auth.user.id);
        if (response.ok) {
            const data = await response.json();
            ciudadesArray.value = data;
        } else {
            throw new Error('Error al cargar la lista de departamentos:', response.statusText);
        }
    } catch (error) {
        console.error(error);
    }
}

const ciudadesSave = async () => {
    try {
        const formData = new FormData();
        formData.append('ciudad', selectedPerson.value)

        axios.post('/ciudades_save', formData).then((response) => {
            if (response.data && response.status == 200) {
                modalOpen.value = true
                modalMessage.value = response.data.message
                ciudades()
            }
        }).catch(error => console.log(error))

    } catch (error) {
        console.error(error);
    }
}

const DeleteCiudad = async (value) => {
    try {
        const formData = new FormData();
        formData.append('ciudad', value)

        axios.post('/ciudades_delete/'+value, formData).then((response) => {
            if (response.data && response.status == 200) {
                modalOpen.value = true
                modalMessage.value = response.data.message
                ciudades()
            }
        }).catch(error => console.log(error))

    } catch (error) {
        console.error(error);
    }
}


onMounted(async () => {
    try {
        const response = await fetch('https://raw.githubusercontent.com/marcovega/colombia-json/master/colombia.min.json');
        if (response.ok) {
            const data = await response.json();
            departamentos.value = data;
            selected.value = departamentos.value[0].departamento;
            selectedPerson.value = departamentos.value[0].ciudades[0];
        } else {
            throw new Error('Error al cargar la lista de departamentos:', response.statusText);
        }
    } catch (error) {
        console.error(error);
    }
    ciudades()
})

</script>

<template>
    <AppLayout title="Dashboard">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-3">
                <div class="bg-white overflow-hidden shadow-xl p-5 sm:rounded-lg">
                    <h1 class="text-black font-bold">Ciudades</h1>
                    <ul role="list" class="divide-y divide-gray-100">
                        <li v-for="item in ciudadesArray" :key="item.id" class="flex justify-between gap-x-6 py-5">
                        <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ item.name_ciudades }}</p>
                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ item.created_at_ciudades }}</p>
                            </div>
                        </div>
                        <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                            <p class="text-sm leading-6 text-gray-900">{{ item.user }}</p>
                            <div class="flex justify-end">
                                <TrashIcon @click="DeleteCiudad(item.id_ciudades)"
                                    class="border bg-slate-800 rounded-md transition text-white duration-300 ease-in-out hover:bg-red-400 hover:text-white p-1 w-10" />
                            </div>
                        </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <SuccessOrError :show="modalOpen" :general="modalOpen" :message="modalMessage" @valueGeneral="onValueGeneral"
            @close="modalOpen = false" />
    </AppLayout>
</template>
