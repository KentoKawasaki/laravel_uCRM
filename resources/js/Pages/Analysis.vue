<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import ResultTable from "@/Components/ResultTable.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { reactive, ref, onMounted } from "vue";
import { getToday } from "@/common";
import CustomBarChart from "@/Components/ChartVue/CustomBarChart.vue";
import BubbleChart from "@/Components/ChartVue/BubbleChart.vue";

onMounted(() => {
  form.startDate = "2021-12-01";
  form.endDate = "2022-11-30";
});

defineProps({
  errors: Object,
});

const form = reactive({
  startDate: null,
  endDate: null,
  isMRanks: {
    mRank1: true,
    mRank2: true,
    mRank3: true,
    mRank4: true,
    mRank5: true,
  },
  type: "perDay",
  rfmPrms: [14, 30, 90, 180, 7, 5, 3, 2, 10000000, 5000000, 1000000, 200000],
});

const data = reactive({});

// const isMRankLabels = reactive({
//   mRank1: true,
//   mRank2: true,
//   mRank3: true,
//   mRank4: true,
//   mRank5: true,
// });

const getData = async () => {
  try {
    await axios
      .get("/api/analysis/", {
        params: {
          startDate: form.startDate,
          endDate: form.endDate,
          isMRanks: form.isMRanks,
          type: form.type,
          rfmPrms: form.rfmPrms,
        },
      })
      .then((res) => {
        console.log("then", res.data);

        data.totals = res.data.totals;
        data.type = res.data.type;

        if (res.data.type !== "rfm") {
          data.data = res.data.data;
          data.labels = res.data.labels;
          data.graphType = "bar";
          data.eachCount = null;
          data.rfmData = null;
          data.startDate = null;
          data.endDate = null;
        }

        if (res.data.type === "rfm") {
          data.eachCount = res.data.eachCount;
          data.graphType = "bubble";
          data.mRanks = res.data.mRanks;
          data.rfmData = res.data.rfmData;
          data.startDate = form.startDate;
          data.endDate = form.endDate;
        }
      });
  } catch (e) {
    console.log(e.message);
    if (e.response.data.errors) {
      alert(e.response.data.message);
    }
  }
};
</script>

<template>
  <Head title="データ分析" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        データ分析
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="getData" class="mb-5">
              <div class="flex flex-col items-center mb-3">
                <fieldset class="py-5 mb-5">
                  <legend class="text-lg font-bold">分析方法</legend>
                  <input
                    type="radio"
                    id="per_day"
                    v-model="form.type"
                    value="perDay"
                    checked
                  /><label for="per_day" class="mr-2">日別</label>
                  <input
                    type="radio"
                    id="per_month"
                    v-model="form.type"
                    value="perMonth"
                  /><label for="per_month" class="mr-2">月別</label>
                  <input
                    type="radio"
                    id="per_year"
                    v-model="form.type"
                    value="perYear"
                  /><label for="per_year" class="mr-2">年別</label>
                  <input
                    type="radio"
                    id="decile"
                    v-model="form.type"
                    value="decile"
                  /><label for="decile" class="mr-2">デシル分析</label>
                  <input
                    type="radio"
                    id="rfm"
                    v-model="form.type"
                    value="rfm"
                  /><label for="frm" class="mr-2">RFM分析</label>
                </fieldset>
                <div
                  class="
                    text-sm
                    grid
                    gap-3
                    grid-cols-1 grid-rows-2
                    sm:grid-cols-2 sm:grid-rows-1
                  "
                >
                  <div class="text-end sm:text-center">
                    <label for="startDate" class="mr-2">From:</label>
                    <input
                      type="date"
                      id="startDate"
                      name="startDate"
                      v-model="form.startDate"
                    />
                  </div>
                  <div class="text-end sm:text-center">
                    <label for="endDate" class="mr-2">To:</label>
                    <input
                      type="date"
                      id="endDate"
                      name="endDate"
                      v-model="form.endDate"
                    />
                  </div>
                </div>
              </div>
              <div v-if="form.type === 'rfm'" class="my-5">
                <h4 class="mx-auto text-center text-base font-bold">
                  RFM分析パラメータ設定
                </h4>
                <table
                  class="
                    mt-2
                    mx-auto
                    table-auto
                    text-left text-gray-500
                    dark:text-gray-400
                  "
                >
                  <thead
                    class="
                      text-gray-700
                      uppercase
                      bg-gray-100
                      dark:bg-gray-700 dark:text-gray-400
                    "
                  >
                    <tr>
                      <th class="py-3 px-6">ランク</th>
                      <th class="py-3 px-6">R (○日以内)</th>
                      <th class="py-3 px-6">F (○回以上)</th>
                      <th class="py-3 px-6">M (○円以上)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="i in 4"
                      :key="i"
                      class="
                        bg-white
                        border-b
                        dark:bg-gray-800 dark:border-gray-700
                      "
                    >
                      <td class="py-4 px-6 text-gray-700 bg-gray-50">
                        {{ 6 - i }}
                      </td>
                      <td v-for="j in 3" :key="i * j">
                        <input
                          class="border-none"
                          type="number"
                          v-model="form.rfmPrms[4 * (j - 1) + (i - 1)]"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button
                class="
                  flex
                  my-10
                  mx-auto
                  text-white
                  bg-indigo-500
                  border-0
                  py-2
                  px-8
                  focus:outline-none
                  hover:bg-indigo-600
                  rounded
                  text-lg
                "
              >
                分析する
              </button>
            </form>

            <CustomBarChart :data="data" />
            <div v-if="data.graphType === 'bubble'">
              <h3 class="text-center text-3xl font-bold mb-5">RFM 分析結果</h3>
              <div class="grid sm:grid-cols-3 gap-3 grid-cols-1 mb-10 text-center">
                <div v-for="mRank in data.mRanks" :key="mRank" class="sm:mx-0 mx-auto">
                  <input
                    type="checkbox"
                    :id="mRank"
                    v-model="form.isMRanks[mRank]"
                    class="mr-3"
                  />
                  <label :for="mRank">{{ mRank }}</label>
                </div>
              </div>
              <div>
                <BubbleChart :data="data" />
              </div>
            </div>

            <ResultTable :data="data" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
