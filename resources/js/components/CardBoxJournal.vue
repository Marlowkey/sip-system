<script setup>
import { computed } from 'vue'
import { mdiTextBoxCheckOutline, mdiFolderText, mdiTextBoxMinusOutline } from '@mdi/js'
import CardBox from '@/components/CardBox.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import PillTag from '@/components/PillTag.vue'
import BaseButton from './BaseButton.vue'


const props = defineProps({
  title: {
    type: String,
    required: true
  },
  date: {
    type: String,
    required: true
  },
  reviewed: {
    type: Boolean,
    default: null
  },
  href: {
    type: String,
    required: true
  },
})
const pillType = computed(() => {
  return props.reviewed ? 'success' : 'danger'
})

const pillIcon = computed(() => {
  return props.reviewed ? mdiTextBoxCheckOutline : mdiTextBoxMinusOutline
})

const pillText = computed(() => props.text ?? (props.reviewed ? 'Reviewed' : 'Pending'))

</script>

<template>
  <CardBox isHoverable>
    <BaseLevel>
      <BaseLevel type="justify-start">
        <BaseButton rounded-full :icon="mdiFolderText" color="blue" class="h-12 w-12"  Table :href="href"/>
        <div class="mx-8 text-center md:text-left overflow-hidden">
          <h4 class="text-xl text-ellipsis">
            {{ title }}
          </h4>
          <p class="text-gray-500 dark:text-slate-400">{{ date }}</p>
        </div>
      </BaseLevel>
      <PillTag :color="pillType" :icon="pillIcon" small/>
    </BaseLevel>
  </CardBox>
</template>
