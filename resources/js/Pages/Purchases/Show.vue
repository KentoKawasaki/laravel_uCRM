<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { onMounted, reactive, ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import dayjs from "dayjs";

const props = defineProps({
  // customers: Array,
  items: Array,
  order: Array,
});

onMounted(() => {
  console.log(props.items);
  console.log(props.order);
});
</script>

<template>
  <Head title="購買履歴 詳細画面" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        購買履歴 詳細画面
      </h2>
    </template>

    <div class="py-12">
      {{ isResult }}
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="storePurchase">
                <div class="container px-5 py-8 mx-auto">
                  <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                      <!-- 日付 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="date"
                            class="leading-7 text-sm text-gray-600"
                            >日付</label
                          >
                          <div
                            type="date"
                            id="date"
                            class="
                              w-full
                              bg-gray-100 bg-opacity-50
                              rounded
                              border border-gray-300
                              focus:border-indigo-500
                              focus:bg-white
                              focus:ring-2
                              focus:ring-indigo-200
                              text-base
                              outline-none
                              text-gray-700
                              py-1
                              px-3
                              leading-8
                              transition-colors
                              duration-200
                              ease-in-out
                            "
                          >
                            {{
                              dayjs(props.order[0].created_at).format(
                                "YYYY/MM/DD"
                              )
                            }}
                          </div>
                        </div>
                      </div>

                      <!-- 会員名 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="customer_name"
                            class="leading-7 text-sm text-gray-600"
                            >会員名</label
                          >
                          <div
                            type="date"
                            id="date"
                            class="
                              w-full
                              bg-gray-100 bg-opacity-50
                              rounded
                              border border-gray-300
                              focus:border-indigo-500
                              focus:bg-white
                              focus:ring-2
                              focus:ring-indigo-200
                              text-base
                              outline-none
                              text-gray-700
                              py-1
                              px-3
                              leading-8
                              transition-colors
                              duration-200
                              ease-in-out
                            "
                          >
                            {{ props.order[0].customer_name }}
                          </div>
                        </div>
                      </div>

                      <!-- 商品・サービス -->
                      <div class="w-full mx-auto overflow-auto mt-5">
                        <label
                          for="customer"
                          class="leading-7 text-sm text-gray-600"
                          >商品・サービス</label
                        >
                        <table
                          class="
                            table-auto
                            w-full
                            text-left
                            whitespace-no-wrap
                            border border-b-0 border-slate-400
                          "
                        >
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
                                商品ID
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
                                商品名
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
                                数量
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
                                小計
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="item in props.items" :key="item.item_id">
                              <td class="px-4 py-3 border-b-2 border-slate-300">
                                {{ item.item_id }}
                              </td>
                              <td class="px-4 py-3 border-b-2 border-slate-300">
                                {{ item.item_name }}
                              </td>
                              <td class="px-4 py-3 border-b-2 border-slate-300">
                                {{ item.item_price }}
                              </td>
                              <td class="px-4 py-3 border-b-2 border-slate-300">
                                {{ item.quantity }}
                              </td>
                              <td class="px-4 py-3 border-b-2 border-slate-300">
                                {{ item.subtotal }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="p-2 w-full">
                        <label for="" class="leading-7 text-sm text-gray-600"
                          >合計金額</label
                        >
                        <div
                          class="
                            text-end
                            w-full
                            bg-gray-100 bg-opacity-50
                            rounded
                            border border-gray-300
                            focus:border-indigo-500
                            focus:bg-white
                            focus:ring-2
                            focus:ring-indigo-200
                            text-base
                            outline-none
                            text-gray-700
                            py-1
                            px-3
                            leading-8
                            transition-colors
                            duration-200
                            ease-in-out
                          "
                        >
                          {{ props.order[0].total }} 円
                        </div>
                      </div>

                      <div class="p-2 w-full">
                        <label for="" class="leading-7 text-sm text-gray-600"
                          >ステータス</label
                        >
                        <div
                          v-if="props.order[0].status == true"
                          class="
                            w-full
                            bg-gray-100 bg-opacity-50
                            rounded
                            border border-gray-300
                            focus:border-indigo-500
                            focus:bg-white
                            focus:ring-2
                            focus:ring-indigo-200
                            text-base
                            outline-none
                            text-gray-700
                            py-1
                            px-3
                            leading-8
                            transition-colors
                            duration-200
                            ease-in-out
                          "
                        >
                          未キャンセル
                        </div>
                        <div
                          v-else
                          class="
                            w-full
                            bg-gray-100 bg-opacity-50
                            rounded
                            border border-gray-300
                            focus:border-indigo-500
                            focus:bg-white
                            focus:ring-2
                            focus:ring-indigo-200
                            text-base
                            outline-none
                            text-gray-700
                            py-1
                            px-3
                            leading-8
                            transition-colors
                            duration-200
                            ease-in-out
                          "
                        >
                          キャンセル済み
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <label for="" class="leading-7 text-sm text-gray-600"
                          >キャンセル日</label
                        >
                        <div
                          v-if="props.order[0].status == false"
                          class="
                            w-full
                            bg-gray-100 bg-opacity-50
                            rounded
                            border border-gray-300
                            focus:border-indigo-500
                            focus:bg-white
                            focus:ring-2
                            focus:ring-indigo-200
                            text-base
                            outline-none
                            text-gray-700
                            py-1
                            px-3
                            leading-8
                            transition-colors
                            duration-200
                            ease-in-out
                          "
                        >
                          {{
                            dayjs(props.order[0].updated_at).format(
                              "YYYY/MM/DD"
                            )
                          }}
                        </div>
                      </div>

                      <div v-if="props.order[0].status == true" class="p-2 w-full mt-3">
                        <Link
                          as="button"
                          :href="
                            route('purchases.edit', {
                              purchase: props.order[0].id,
                            })
                          "
                          class="
                            flex
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
                          >編集する</Link
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>