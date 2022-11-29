<script setup>
import dayjs from "dayjs";

const props = defineProps({
  data: Object,
});
</script>

<template>
  <!-- 日別、月別、年別の結果をテーブルに表示 -->
  <div
    v-if="
      data.type === 'perDay' ||
      data.type === 'perMonth' ||
      data.type === 'perYear'
    "
    class="lg:w-2/3 w-full mt-5 mx-auto overflow-auto"
  >
    <table class="table-auto w-full text-left whitespace-no-wrap">
      <thead>
        <tr>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
              rounded-tl rounded-bl
            "
          >
            年月日
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            金額
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="datum in data.data"
          :key="datum.date"
          class="border-b-2 border-slate-200"
        >
          <td class="px-4 py-3">
            {{ dayjs(datum.date).format("YYYY年MM月DD日") }}
          </td>
          <td class="px-4 py-3">
            &yen; {{ Number(datum.total).toLocaleString() }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- デシル分析の結果をテーブルに表示 -->
  <div
    v-else-if="data.type === 'decile'"
    class="lg:w-2/3 w-full mt-5 mx-auto overflow-auto"
  >
    <table class="table-auto w-full text-left whitespace-no-wrap">
      <thead>
        <tr>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
              rounded-tl rounded-bl
            "
          >
            グループ
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            平均
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            合計金額
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            構成比
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="datum in data.data"
          :key="datum.date"
          class="border-b-2 border-slate-200"
        >
          <td class="px-4 py-3">
            {{ datum.decile }}
          </td>
          <td class="px-4 py-3">
            &yen; {{ Number(datum.average).toLocaleString() }}
          </td>
          <td class="px-4 py-3">
            &yen; {{ Number(datum.totalPerGroup).toLocaleString() }}
          </td>
          <td class="px-4 py-3">{{ datum.totalRatio }} %</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- RFM分析の結果をテーブルに表示 -->
  <div
    v-else-if="data.type === 'rfm'"
    class="lg:w-2/3 w-full mt-5 mx-auto overflow-auto"
  >
  <h3 class="text-center text-3xl font-bold mb-5">RFM 分析結果</h3>
  <div class="my-5 text-center text-xl"><span class="border-b-2 border-slate-500"><span class="font-medium">合計:</span> {{ data.totals }}人</span></div>
    
    <h4 class="text-center text-2xl my-4 font-semibold">RFMランクごとの人数</h4>
    <table
      class="
        mt-2
        mx-auto
        table-auto
        text-left text-gray-500
        dark:text-gray-400
        w-full
        whitespace-no-wrap
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
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            Rank
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            R
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            F
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            M
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="rfm in data.eachCount"
          :key="rfm.rank"
          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
        >
          <td class="px-4 py-3 text-gray-700 bg-gray-50">{{ rfm.rank }}</td>
          <td class="p-3">{{ rfm.r }}</td>
          <td class="p-3">{{ rfm.f }}</td>
          <td class="p-3">{{ rfm.m }}</td>
        </tr>
      </tbody>
    </table>


    <h4 class="text-center text-2xl my-4 font-semibold">RFM分析表</h4>
    <table
      class="
        mt-2
        mx-auto
        table-auto
        text-left text-gray-500
        dark:text-gray-400
        w-full
        whitespace-no-wrap
      "
    >
      <thead
        class="
          text-gray-700
          bg-gray-100
          dark:bg-gray-700 dark:text-gray-400
        "
      >
        <tr>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            rRank
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            f_5
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            f_4
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            f_3
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            f_2
          </th>
          <th
            class="
              px-4
              py-3
              title-font
              tracking-wider
              font-medium
              text-gray-900 text-sm
              bg-gray-100
            "
          >
            f_1
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="rf in data.data" :key="rf.rRank" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
          <td class="px-4 py-3 text-gray-700 bg-gray-50">{{ rf.rRank }}</td>
          <td class="p-3">{{ rf.f_5 }}</td>
          <td class="p-3">{{ rf.f_4 }}</td>
          <td class="p-3">{{ rf.f_3 }}</td>
          <td class="p-3">{{ rf.f_2 }}</td>
          <td class="p-3">{{ rf.f_1 }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>