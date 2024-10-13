<script setup>
import { computed, ref } from 'vue'
import { useMainStore } from '@/stores/main'
import { mdiCheckDecagram } from '@mdi/js'
import BaseLevel from '@/components/BaseLevel.vue'
import UserAvatarCurrentUser from '@/components/UserAvatarCurrentUser.vue'
import CardBox from '@/components/CardBox.vue'
import FormCheckRadio from '@/components/FormCheckRadio.vue'
import PillTag from '@/components/PillTag.vue'

const props = defineProps({
    username: {
        type: String,
        required: true
    },

})
const mainStore = useMainStore()

const userName = computed(() => mainStore.userName)

const userSwitchVal = ref(false)
</script>
<template>
    <CardBox>
        <BaseLevel type="justify-around lg:justify-center">
            <div class="flex justify-center">
                <div class=" h-96 w-96 mx-2 flex items-center justify-center overflow-hidden"> <!-- Flex container -->
                    <UserAvatarCurrentUser class="lg:mx-12 w-full h-ful" />
                </div>
            </div>
            <div class=" text-center md:text-left ">
                <h1 class="text-2xl">
                    Howdy, <b>{{ username ?? "User" }}</b>!
                </h1>
            </div>
        </BaseLevel>
        <!-- Slot for file upload or other custom content -->
        <slot name="file-upload"></slot>
    </CardBox>
</template>
