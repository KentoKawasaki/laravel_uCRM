<script setup>
import { Chart, registerables } from "chart.js";
import { BarChart, BubbleChart } from "vue-chart-3";
import { reactive, computed, onMounted } from "vue";

Chart.register(...registerables);

const props = defineProps({
  data: Object,
});

onMounted(() => {
  // console.log(props.data.data)
  console.log('Chart', rfmData.target)
  console.log('Chart totals', totals)
})

const rfmData = computed(() => {
  // const rankData = {};
  // data.rfmData.forEach((rank) => {
  //   rankData[rank.mRank] = 
  // })
  return props.data.rfmData
});
const eachCount = computed(() => props.data.eachCount);
const labels = computed(() => props.data.labels);
const totals = computed(() => props.data.totals);

const barData = reactive({
  labels: labels,
  datasets: [
    {
      label: "売上",
      data: totals,
      backgroundColor: "rgb(75, 192, 192)",
      tension: 0.1,
    },
  ],
});

if (rfmData.value) {
  console.log('ifRFM', rfmData)
//   const bubbleData = reactive({
//   labels: labels,
//   datasets: [
//     {
//       label: "mRank1",
//       data: rfmData.mRank1.map(row => ({
//         x: row.r,
//         y: row.f,
//         r: r.r_n
//       })),
//       backgroundColor: "rgb(75, 192, 192)",
//       tension: 0.1,
//     },
//   ],
// });
}

</script>

<template>
  <div v-show="props.data.data">
    <BubbleChart v-if="eachCount" :chartData="bubbleData" />
    <BarChart v-else :chartData="barData" />
  </div>
</template>