<script setup>
import dayjs from "dayjs";

const props = defineProps({
  data: Object,
});
</script>

<template>
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
          <td class="px-4 py-3">{{ datum.total }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div
    v-else-if="
      data.type === 'decile'
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
          <td class="px-4 py-3">&yen; {{ Number(datum.average).toLocaleString() }}</td>
          <td class="px-4 py-3">&yen; {{ Number(datum.totalPerGroup).toLocaleString() }}</td>
          <td class="px-4 py-3">{{ datum.totalRatio }} %</td>
        </tr>
      </tbody>
    </table>
  </div>

</template>