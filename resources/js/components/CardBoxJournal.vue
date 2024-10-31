<script setup>
import { computed } from 'vue'
import { mdiTextBoxCheckOutline, mdiFolderText, mdiTextBoxMinusOutline } from '@mdi/js'
import CardBox from '@/components/CardBox.vue'
import BaseLevel from '@/components/BaseLevel.vue'
import PillTag from '@/components/PillTag.vue'
import BaseButton from './BaseButton.vue'
import { format, parse } from 'date-fns';


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
  username: {
    type: String,
    required: true
  },
})
const pillType = computed(() => {
  return props.reviewed ? 'success' : 'warning'
})

const pillIcon = computed(() => {
  return props.reviewed ? mdiTextBoxCheckOutline : mdiTextBoxMinusOutline
})

const pillText = computed(() => props.text ?? (props.reviewed ? 'Reviewed' : 'Pending'))

const formatDate = (date) => {
    if (!date) return '---';
    return format(parse(date, 'yyyy-MM-dd', new Date()), 'MMMM dd, yyyy');
}
</script>

<template>
  <CardBox isHoverable>
    <BaseLevel>
      <BaseLevel type="justify-start">
        <BaseButton rounded-full :icon="mdiFolderText" color="blue" class="w-12 h-12"  Table :href="href"/>
        <div class="mx-8 overflow-hidden text-center md:text-left">
          <h4 class="text-medium text-ellipsis">
            {{ title }}
          </h4>
          <p class="text-gray-500 dark:text-slate-400">{{ formatDate(date) }}</p>
          <p v-if="username"  class="text-gray-500 dark:text-slate-400">User: {{ username }}</p>
        </div>
      </BaseLevel>
      <PillTag :color="pillType" :icon="pillIcon" small/>
    </BaseLevel>
  </CardBox>
</template>
