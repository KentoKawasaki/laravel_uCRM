<script setup>
import { Chart as ChartJS, registerables } from "chart.js";
import { Bubble } from "vue-chartjs";
import { reactive, computed, onMounted, onBeforeMount } from "vue";

ChartJS.register(...registerables);

const props = defineProps({
  data: Object,
});

// const mRanks = computed(() => props.data.rfmData.map((rank) => rank[0].mRank));
const rfmBorderColor = {
  mRank1: "rgba(220, 53, 69, 0.5)",
  mRank2: "rgba(255, 193, 7, 0.5)",
  mRank3: "rgba(25, 135, 84, 0.5)",
  mRank4: "rgba(13, 202, 240, 0.5)",
  mRank5: "rgba(13, 110, 253, 0.5)",
};

const rfmBackgroundColor = {
  mRank1: "rgba(220, 53, 69, 0.45)",
  mRank2: "rgba(255, 193, 7, 0.45)",
  mRank3: "rgba(25, 135, 84, 0.45)",
  mRank4: "rgba(13, 202, 240, 0.45)",
  mRank5: "rgba(13, 110, 253, 0.45)",
};

const countRankLen = 30;
const countRank = new Array(countRankLen)
  .fill(5)
  .map((num, index) => num * (index + 1));
// const mRankLen = 5
// const mRanks = new Array(mRankLen).fill('mRank').map((mRank, index) => `${mRank}${(index + 1)}`)
// const isMRankLabels = {}
// mRanks.forEach(isMRank => {
//   isMRankLabels[isMRank] = true
// })

// const isMRankLabels = computed(() =>({
//   mRank1: true,
//   mRank2: true,
//   mRank3: true,
//   mRank4: true,
//   mRank5: true,
// }))

// console.log(isMRankLabels);
// const countRank = [15, 30, 50, 80, 100]

// const showMRanks = [];

const bubbleData = computed(() => {
  const propsRfmData = props.data.rfmData;
  const countRankSpan = Math.ceil(props.data.totals / countRankLen);

  const datasets = [];
  // const labels = []
  
  // showMRanks.length = 0;

  propsRfmData.forEach((rank) => {
    const mRank = rank[0].mRank;
    // rank.forEach((item) => {
    //   labels.push(`${item.mRank}${item.r}${item.f}${item.r_n}`)
    // })
    datasets.push({
      label: mRank,
      data: rank.map((row) => {
        const countRankIndex = Math.floor(row.r_n / countRankSpan);
        return {
          x: row.r,
          y: row.f,
          r: countRank[countRankIndex],
        };
      }),
      backgroundColor: rfmBackgroundColor[mRank],
      borderColor: rfmBorderColor[mRank],
      tension: 0.1,
    });
  });



  
  console.log(datasets)

  const data = { 
    datasets: datasets };

  return data;
});

const eachCount = computed(() => props.data.eachCount);
const totals = computed(() => props.data.totals);

const bubbleHeight = reactive({
  type: Number,
  default: 1000
})
const bubbleOptions = reactive({
  responsive: true,
  maintainAspectRation: false,
  scales: {
    x: {
      min: 0.5,
      max: 5.5,
      ticks: {
        callback: (value) => {
          if (Number.isInteger(value)) {
            return `rRank${value}`;
          } else {
            return "";
          }
        },
      },
    },
    y: {
      min: 0.5,
      max: 5.5,
      ticks: {
        callback: (value) => {
          if (Number.isInteger(value)) {
            return `fRank${value}`;
          } else {
            return "";
          }
        },
      },
    },
  },
});

// bubbleChart = drawChart(ctx, bubbleData, bubbleOptions)
</script>

<template>
  <div v-if="data.graphType === 'bubble'" class="mb-10 relative">
    <!-- <BubbleChart
      :chartData="bubbleData"
      :options="bubbleOptions"
      :onChartUpdate="(bubbleData) => {}"
      height="650"
    /> -->
    <Bubble :chart-data="bubbleData" :chart-options="bubbleOptions" :height="bubbleHeight" />
  </div>
</template>