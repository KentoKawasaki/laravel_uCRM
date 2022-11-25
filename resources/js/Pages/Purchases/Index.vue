<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
import PaginationComponent from "@/Components/PaginationComponent.vue";
import { Inertia } from "@inertiajs/inertia";
import { onMounted, ref } from "vue";
import dayjs from "dayjs";

const props = defineProps({
  orders: Object,
});

onMounted(() => {
  console.log(props.orders.data);
});
</script>

<template>
  <Head title="購買履歴" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        購買履歴
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <section class="text-gray-600 body-font">
              <div class="container px-5 py-8 mx-auto">
                <FlashMessage />

                <div
                  class="
                    flex
                    my-4
                    justify-between
                    sm:flex-row
                    flex-col
                    items-center
                    lg:w-2/3
                    w-full
                    mx-auto
                  "
                >
                  <div
                    class="
                      mb-5
                      sm:mb-0
                      flex
                      sm:flex-row
                      flex-col
                      justify-center
                      items-center
                    "
                  >
                    <input
                      type="text"
                      name="search"
                      v-model="search"
                      @keyup.enter="searchCustomers"
                      placeholder="'カナ'または'電話番号'を入力"
                    />
                    <buttton
                      class="
                        bg-blue-300
                        text-white
                        py-2
                        px-2
                        sm:text-lg sm:ml-2 sm:mt-0
                        mt-2
                        w-1/2
                        text-center
                        block
                      "
                      @click="searchCustomers"
                      >検索</buttton
                    >
                  </div>
                </div>
                <div>
                  <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                    <table
                      class="table-auto w-full text-left whitespace-no-wrap"
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
                            注文ID
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
                            氏名
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
                            ステータス
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
                            購入日
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="order in props.orders.data" :key="order.id">
                          <td class="px-4 py-3">
                            <Link
                              class="text-blue-400"
                              :href="
                                route('purchases.show', { purchase: order.id })
                              "
                            >
                              {{ order.id }}
                            </Link>
                          </td>
                          <td class="px-4 py-3">
                            {{ order.customer_name }}
                          </td>
                          <td class="px-4 py-3">{{ order.total }}</td>
                          <td class="px-4 py-3 text-lg text-gray-900">
                            {{ order.status }}
                          </td>
                          <td class="px-4 py-3 text-lg text-gray-900">
                            {{
                              dayjs(order.created_at).format(
                                "YYYY-MM-DD HH:mm:ss"
                              )
                            }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <PaginationComponent
                    class="mt-6 w-full"
                    :links="props.orders.links"
                  />
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
