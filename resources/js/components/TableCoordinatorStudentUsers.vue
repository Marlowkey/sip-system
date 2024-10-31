<script setup>
import { computed, ref, defineProps } from 'vue'
import {     mdiAccountSearch,
} from '@mdi/js'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'
import ProgressBar from '@/components/ProgressBar.vue'
import CardBox from './CardBox.vue'
import FormControl from '@/components/FormControl.vue'


const props = defineProps({
    checkable: Boolean,
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


const itemsPaginated = computed(() => {

    let filteredItems = items.value
    if (searchTerm.value) {
        filteredItems = filteredItems.filter(user =>
            user.first_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            user.last_name.toLowerCase().includes(searchTerm.value.toLowerCase())
        )
    }

    return filteredItems.slice(perPage.value * currentPage.value, perPage.value * (currentPage.value + 1))
})

const numPages = computed(() => Math.ceil(items.value.length / perPage.value))

const currentPageHuman = computed(() => currentPage.value + 1)

const pagesList = computed(() => {
    const pagesList = []

    for (let i = 0; i < numPages.value; i++) {
        pagesList.push(i)
    }

    return pagesList
})



</script>

<template>
    <div>
        <div class="flex flex-col items-center justify-between mb-4 lg:flex-row">
            <div class="flex flex-col items-center mb-4 space-x-0 lg:flex-row lg:space-x-4 lg:mb-0">
            </div>
            <div class="flex items-center ">
                <FormControl borderless v-model="searchTerm" type="text" placeholder="Search user..." :icon="mdiAccountSearch"
                    class="w-full max-w-xs text-sm" />
            </div>
        </div>
    </div>
    <CardBox has-table>
        <div class="relative m-4 overflow-x-auto">
            <table class="w-full text-left text-gray-800 rtl:text-right">
                <thead class="text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-300">
                    <tr class="border-b">
                        <th v-if="checkable" />
                        <th />
                        <th scope="col" class="px-4 py-3">Name</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">Block</th>
                        <th scope="col" class="px-8 py-3">SIP Requirements Progress</th>
                        <!-- <th>Action</th> -->

                    </tr>
                </thead>
                <tbody class="font-medium text-gray-600 ">
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

                        <td data-label="Year Level" class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            {{ user.block }}
                        </td>

                        <td data-label="Progress" class="min-w-full px-6 py-4">
                            <div class="flex items-center">
                                <ProgressBar :progress="user.progress" />
                                <span class="ml-4 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ Math.floor(user.progress) }}%
                                </span>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="p-3 mt-2 border-t border-gray-100 lg:px-6 dark:border-slate-800">
            <BaseLevel>
                <BaseButtons>
                    <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage" :label="page + 1"
                        :color="page === currentPage ? 'lightDark' : 'whiteDark'" small @click="currentPage = page" />
                </BaseButtons>
                <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
            </BaseLevel>
        </div>
    </CardBox>

</template>
