<script setup>
import { computed, ref, defineProps } from 'vue'
import { mdiEye, mdiTrashCan } from '@mdi/js'
import CardBoxModal from '@/components/CardBoxModal.vue'
import TableCheckboxCell from '@/components/TableCheckboxCell.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import UserAvatar from '@/components/UserAvatar.vue'
import ProgressBar from '@/components/ProgressBar.vue'

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

const itemsPaginated = computed(() =>
    items.value.slice(perPage.value * currentPage.value, perPage.value * (currentPage.value + 1))
)

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
    <CardBoxModal v-model="isModalActive" title="Sample modal">
        <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
        <p>This is sample modal</p>
    </CardBoxModal>
    <div class="relative overflow-x-auto m-4">
    <table class="w-full text-gray-800 text-left rtl:text-right my-2">
        <thead class="text-gray-700">
            <tr class="border-b">
                <th v-if="checkable" />
                <th  scope="col" class="px-6 py-3">Name</th>
                <th  scope="col" class="px-6 py-3">Email</th>
                <th  scope="col" class="px-6 py-3">Block</th>
                <th scope="col" class="px-8 py-3">Progress</th>
                <th scope="col" class="px-6 py-3">HTE</th>
                <!-- <th>Action</th> -->

            </tr>
        </thead>
        <tbody>
            <tr v-for="user in itemsPaginated" :key="user.id" class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td data-label="Name" scope="row" class="px-4 py-1 font-semibold">
                    {{ user.last_name }} , {{ user.first_name }}
                </td>
                <td data-label="Email" class="px-4 py-1">
                    {{ user.email }}
                </td>
                <td data-label="Year Level" class="px-4 py-1">
                    {{ user.block }}
                </td>

                <td data-label="Progress" class="px-4 py-1 min-w-ful">
                    <ProgressBar :progress="user.progress" />
                </td>
                <td data-label="HTE" class="px-4 py-1">
                    {{ user.host_training_establishment }}
                </td>
                <!-- <td class="before:hidden lg:w-1 whitespace-nowrap text-center px-6">
                    <BaseButtons type="justify-start lg:justify-end" no-wrap>
                        <BaseButton color="info" :icon="mdiEye" small @click="isModalActive = true" />
                    </BaseButtons>
                </td> -->
            </tr>
        </tbody>
    </table>
    </div>
    <div class="p-3 mt-2 lg:px-6 border-t border-gray-100 dark:border-slate-800">
        <BaseLevel>
            <BaseButtons>
                <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage" :label="page + 1"
                    :color="page === currentPage ? 'lightDark' : 'whiteDark'" small @click="currentPage = page" />
            </BaseButtons>
            <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
        </BaseLevel>
    </div>
</template>
