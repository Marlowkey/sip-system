<script setup>
import { router } from '@inertiajs/vue3'
import {
    mdiAccountSearch,
    mdiAccountGroupOutline,
    mdiLaptop,
    mdiBookOpenPageVariant,
    mdiFilterCheck,
} from '@mdi/js'
import { computed, ref, defineProps } from 'vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from './UserAvatar.vue'



const props = defineProps({
    checkable: Boolean,
    users: {
        type: Array,
        required: true,
    }
})
const items = computed(() => props.users ? props.users : [])
const perPage = ref(15)
const currentPage = ref(0)


const itemsPaginated = computed(() => {
    let filteredItems = items.value
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


const redirectToEdit = (userId) => {
      router.get(route('users.edit', { id: userId }));
    }
</script>

<template>
        <div class="relative my-2 overflow-x-auto">
            <table class="w-full text-left text-gray-800 rtl:text-right">
                <thead class="text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-300">
                    <tr class="border-b">
                        <th v-if="checkable" />
                        <th />
                        <th scope="col" class="px-4 py-3">Name</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">Course</th>
                    </tr>
                </thead>
                <tbody class="font-medium text-gray-600">
                    <tr v-for="user in itemsPaginated" :key="user.id" @click="redirectToEdit(user.id)"
                        class="transition-all duration-200 ease-in-out bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="px-4 py-3 border-b-0 lg:w-6 before:hidden">
                            <UserAvatar :avatar="user.avatar"  :username="`${user.last_name}, ${user.first_name}`" class="w-24 h-24 mx-auto lg:w-6 lg:h-6" />
                        </td>
                        <td data-label="Name" scope="row"
                            class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap">
                            {{ user.last_name }}, {{ user.first_name }}
                        </td>
                        <td data-label="Email" class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            {{ user.email }}
                        </td>
                        <td data-label="Course" class="px-6 py-4 text-gray-600 dark:text-gray-400">
                            {{ user.course }}
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

</template>
