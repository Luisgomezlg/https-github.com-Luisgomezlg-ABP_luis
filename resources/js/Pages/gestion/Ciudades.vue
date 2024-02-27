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
                    <div class="grid grid-cols-3 gap-4 my-5 rounded-xl">
                        <div v-for="item in ciudadesArray"
                            class="py-5 flex justify-center place-items-center gap-x-5 bg-slate-800 border rounded-lg p-3">
                            <Link :key="item.id"
                                class="transition duration-300 text-white ease-in-out hover:scale-105 cursor-pointer">
                            {{ item.name_ciudades }}

                            </Link>
                        </div>

                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <Combobox v-model="selected">
                        <div class="relative mt-1">
                            <div
                                class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
                                <ComboboxInput
                                    class="w-full border-none outline-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900"
                                    :displayValue="(departamento) => departamento" @change="query = $event.target.value" />
                                <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                </ComboboxButton>
                            </div>
                            <TransitionRoot enter="duration-150 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                                leave="duration-150 ease-in" leave-from="opacity-100" leave-to="opacity-0"
                                @after-leave="query = ''">
                                <ComboboxOptions
                                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                    <div v-if="filteredCountries.length === 0 && query !== ''"
                                        class="relative cursor-default select-none py-2 px-4 text-gray-700">
                                        No se encontraron datos.
                                    </div>

                                    <ComboboxOption v-for="departamento in filteredCountries" :key="departamento.id"
                                        :value="departamento.departamento" v-slot="{ selected, active }">
                                        <li @click="selectDepartament(departamento)"
                                            class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{
                                                'bg-teal-600 text-white': active,
                                                'text-gray-900': !active,
                                            }">
                                            <span class="block truncate"
                                                :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                                {{ departamento.departamento }}
                                            </span>
                                            <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                :class="{ 'text-white': active, 'text-teal-600': !active }">
                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </li>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </TransitionRoot>
                        </div>
                    </Combobox>
                    <Listbox v-model="selectedPerson">
                        <div class="relative mt-1">
                            <ListboxButton
                                class="relative w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                                <span class="block truncate">{{ selectedPerson }}</span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                </span>
                            </ListboxButton>

                            <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                                leave-to-class="opacity-0">
                                <ListboxOptions
                                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                                    <ListboxOption v-slot="{ active, selected }" v-for="person in people" :key="person"
                                        :value="person" as="template">
                                        <li :class="[
                                            active ? 'bg-amber-100 text-amber-900' : 'text-gray-900',
                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                        ]">
                                            <span :class="[
                                                selected ? 'font-medium' : 'font-normal',
                                                'block truncate',
                                            ]">{{ person }}</span>
                                            <span v-if="selected"
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600">
                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                </div>
                <div class="my-5">
                    <button @click="ciudadesSave" class="bg-slate-800 text-white rounded-md py-2 px-5 float-right">Guardar</button>
                </div>
            </div>

        </div>
        <SuccessOrError :show="modalOpen" :general="modalOpen" :message="modalMessage" @valueGeneral="onValueGeneral"
            @close="modalOpen = false" />
    </AppLayout>
</template>
