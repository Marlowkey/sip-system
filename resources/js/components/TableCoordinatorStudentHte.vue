<script setup>
import {
    mdiAccountSearch,
    mdiAccountGroupOutline,
    mdiLaptop,
    mdiBookOpenPageVariant,
    mdiFilterCheck,
} from '@mdi/js'
import debounce from 'lodash.debounce'
import { router, useForm } from '@inertiajs/vue3'
import { computed, ref, defineProps, watch } from 'vue'
import { mdiEye, mdiTrashCan } from '@mdi/js'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'
import ProgressBar from '@/components/ProgressBar.vue'
import FormControl from '@/components/FormControl.vue'
import BaseDivider from './BaseDivider.vue'
import CardBox from './CardBox.vue'


const props = defineProps({
    admin: Boolean,
    checkable: Boolean,
    htes: Array,
    classBlocks: Array,
    schoolYears: Array,
    schoolYear: String,
    users: {
        type: Array,
        required: true,
    }
})
const items = computed(() => props.users ? props.users : [])
const isModalActive = ref(false)
const isModalDangerActive = ref(false)
const perPage = ref(15)
const currentPage = ref(0)
const searchTerm = ref("")
const classBlock = ref("")
const course = ref("")
const courseOptions = ref(["Information Technology", "Information System", "Computer Science",])
const htePlacement = ref("");
const availableHTEs = computed(() => {
    const hteSet = new Set();
    props.users.forEach(user => {
        if (user.host_training_establishment?.name) {
            hteSet.add(user.host_training_establishment.name);
        }
    });
    return Array.from(hteSet);
});


const itemsPaginated = computed(() => {
    let filteredItems = items.value
    if (searchTerm.value) {
        filteredItems = filteredItems.filter(user =>
            user.first_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            user.last_name.toLowerCase().includes(searchTerm.value.toLowerCase())
        )
    }

    if (classBlock.value) {
        filteredItems = filteredItems.filter(user => user.block === classBlock.value)
    }

    if (course.value) {
        filteredItems = filteredItems.filter(user => user.course === course.value)
    }

        if (htePlacement.value) {
        filteredItems = filteredItems.filter(user =>
            user.host_training_establishment?.name === htePlacement.value
        );
    }

    return filteredItems.slice(perPage.value * currentPage.value, perPage.value * (currentPage.value + 1))
})

const numPages = computed(() => Math.ceil(itemsPaginated.value.length / perPage.value))

const currentPageHuman = computed(() => currentPage.value + 1)

const pagesList = computed(() => {
    const pagesList = []

    for (let i = 0; i < numPages.value; i++) {
        pagesList.push(i)
    }

    return pagesList
})


const isEditingHTE = ref(null)

const form = useForm({
    host_training_establishment_id: null,
})

const toggleEditingHTE = (userId) => {
    isEditingHTE.value = isEditingHTE.value === userId ? null : userId
}

const saveHTE = (userId) => {
    form.post(route('users.update-hte', userId), {
    })
}
</script>

<template>
    <div class="p-2 m-2">
        <div class="flex flex-col items-center justify-between mb-4 lg:flex-row">
            <div class="flex flex-col items-center w-1/2 mb-4 space-x-0 lg:flex-row lg:space-x-4 lg:mb-0">
                <label v-if="admin" for="course" class="text-lg font-medium">Course:</label>
                <FormControl v-if="admin" :options="courseOptions" v-model="course" label="Course"
                    placeholder="Select a Course" :icon="mdiFilterCheck" class="w-full max-w-2xl text-sm font-medium" />

                <label for="block" class="text-lg font-medium">Block:</label>
                <FormControl :options="classBlocks" v-model="classBlock" label="Class Block" :icon="mdiFilterCheck"
                    placeholder="Select a Class Block" class="w-1/3 text-sm font-medium" />

                    <label for="hte" class="text-lg font-medium">HTE:</label>
    <FormControl :options="availableHTEs" v-model="htePlacement" label="HTE Placement"
        placeholder="Select an HTE" :icon="mdiFilterCheck" class="w-1/3 text-sm font-medium" />
            </div>
            <div class="flex items-center space-x-4">
                <FormControl v-model="searchTerm" type="text" placeholder="Search user..." :icon="mdiAccountSearch"
                    class="w-full max-w-xs text-sm font-medium" />
            </div>
        </div>
    </div>

    <CardBox has-table>
        <div class="relative my-2 overflow-x-auto">
            <table class="w-full text-left text-gray-800 rtl:text-right">
                <thead class="text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-300">
                    <tr class="border-b">
                        <th v-if="checkable" />
                        <th class="px-4 py-3" />
                        <th scope="col" class="px-4 py-3">Name</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">HTE</th>

                    </tr>
                </thead>
                <tbody class="font-medium text-gray-600">
                    <tr v-for="user in itemsPaginated" :key="user.id"
                        class="transition-all duration-200 ease-in-out bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="px-4 py-3 border-b-0 lg:w-6 before:hidden">
                            <UserAvatar :avatar="user.avatar" :username="`${user.last_name}, ${user.first_name}`"
                                class="w-24 h-24 mx-auto lg:w-6 lg:h-6" />
                        </td>
                        <td data-label="Name" scope="row"
                            class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap">
                            {{ user.last_name }}, {{ user.first_name }}
                        </td>
                        <td data-label="Email" class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            {{ user.email }}
                        </td>

                        <td data-label="HTE" class="w-full px-6 py-4 text-gray-600 dark:text-gray-400">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <!-- Editing Mode -->
        <div v-if="isEditingHTE === user.id" class="flex flex-wrap items-center w-full gap-4">
            <select v-model="form.host_training_establishment_id"
                placeholder="Select Host Training Establishment"
                class="w-full p-3 text-sm font-medium border rounded-md sm:flex-1">
                <option value="" disabled>Select Host Training Establishment</option>
                <option v-for="hte in htes" :key="hte.id" :value="hte.id">
                    {{ hte.name }}
                </option>
            </select>
            <div class="flex items-center gap-2">
                <BaseButton roundedFull small color="teal" label="Save"
                    @click="saveHTE(user.id)" class="px-4 py-2" />
                <BaseButton roundedFull small color="whiteTwo" label="Cancel"
                    @click="toggleEditingHTE(user.id)" class="px-4 py-2" />
            </div>
        </div>
        <!-- Default View -->
        <div v-else class="flex flex-wrap items-center justify-between w-full gap-4">
            <p class="flex-1">{{ user.host_training_establishment?.name || 'Not Assigned' }}</p>
            <BaseButton roundedFull small color="blue" label="Update HTE Assignment"
                @click="toggleEditingHTE(user.id)" class="px-4 py-2" />
        </div>
    </div>
</td>



                    </tr>
                </tbody>
            </table>
            <div class="p-3 mt-2 border-t border-gray-100 lg:px-6 dark:border-slate-800">
                <BaseLevel>
                    <BaseButtons>
                        <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage"
                            :label="page + 1" :color="page === currentPage ? 'lightDark' : 'whiteDark'" small
                            @click="currentPage = page" />
                    </BaseButtons>
                    <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
                </BaseLevel>
            </div>
        </div>
    </CardBox>
</template>
