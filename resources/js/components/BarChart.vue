<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import {
  Chart,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend
} from 'chart.js'

const props = defineProps({
  data: {
    type: Object,
    required: true
  }
})

const root = ref(null)

let chart

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend)

onMounted(() => {
  chart = new Chart(root.value, {
    type: 'bar',  // Change chart type to bar
    data: props.data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          display: true,  // Display the Y-axis
          beginAtZero: true  // Start Y-axis from zero
        },
        x: {
          display: true
        }
      },
      plugins: {
        legend: {
          display: true  // Show the legend
        }
      }
    }
  })
})

const chartData = computed(() => props.data)

watch(chartData, (data) => {
  if (chart) {
    chart.data = data
    chart.update()
  }
})
</script>

<template>
  <canvas ref="root" />
</template>
