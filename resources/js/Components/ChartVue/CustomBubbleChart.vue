<script setup>
import { Chart, registerables } from "chart.js";
import { BubbleChart } from "vue-chart-3";
import { reactive, computed, onMounted } from "vue";

Chart.register(...registerables);

const props = defineProps({
  data: Object,
});

onMounted(() => {
//   console.log(props.data.data);
  // console.log("Mounted Chart", rfmData);
  // console.log("Mounted Chart totals", totals);
});

const rfmBorderColor = {
  mRank1: 'rgba(220, 53, 69, 0.5)',
  mRank2: 'rgba(255, 193, 7, 0.5)',
  mRank3: 'rgba(25, 135, 84, 0.5)',
  mRank4: 'rgba(13, 202, 240, 0.5)',
  mRank5: 'rgba(13, 110, 253, 0.5)',
}

const rfmBackgroundColor = {
  mRank1: 'rgba(220, 53, 69, 0.45)',
  mRank2: 'rgba(255, 193, 7, 0.45)',
  mRank3: 'rgba(25, 135, 84, 0.45)',
  mRank4: 'rgba(13, 202, 240, 0.45)',
  mRank5: 'rgba(13, 110, 253, 0.45)',
}

const countRankLen = 30;
const countRank = new Array(countRankLen).fill(5).map((num, index) => num * (index + 1))
// const countRank = [15, 30, 50, 80, 100]
const rfmDataSets = computed(() => {
  const propsRfmData = props.data.rfmData;
  const countRankSpan = Math.ceil(props.data.totals / countRankLen)

  if (propsRfmData) {
    const datasets = [];
    propsRfmData.forEach((rank) => {
      const mRank = rank[0].mRank
      datasets.push({
        label: mRank,
        data: rank.map((row) => {
          const countRankIndex = Math.floor(row.r_n / countRankSpan)
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

    return datasets;
  }
});

const eachCount = computed(() => props.data.eachCount);
const totals = computed(() => props.data.totals);


const bubbleData = reactive({
  datasets: rfmDataSets,
});

const bubbleOptions = reactive({
  aspectRatio: 1,
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
</script>

<template>
  <div v-if="props.data.graphType === 'bubble'" class="mb-10 relative">
    <h3 class="text-center text-3xl font-bold mb-5">RFM 分析結果</h3>
    <BubbleChart
      :chartData="bubbleData"
      :options="bubbleOptions"
      height=650
    />
  </div>
</template>