<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
import PaginationComponent from "@/Components/PaginationComponent.vue";
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

defineProps({
  customers: Object,
  noResults: Array,
});

const search = ref("");

const searchCustomers = () => {
  Inertia.get(route("customers.index", { search: search.value }));
};
</script>

<template>
  <Head title="顧客一覧" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        顧客一覧
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
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
                  <div class="mb-5 sm:mb-0 sm:w-2/3 flex sm:flex-row flex-col sm:justify-start items-center">
                    <input
                      type="text"
                      name="search"
                      v-model="search"
                      @keyup.enter="searchCustomers"
                      placeholder="'氏名(漢字)'または'電話番号'を入力"
                      class="sm:w-2/3 w-full"
                    />
                    <buttton
                      class="bg-blue-300 text-white sm:py-2 p-1 sm:text-lg sm:ml-2 sm:mt-0 mt-2 sm:w-1/5 w-2/3 text-center block"
                      @click="searchCustomers"
                      >検索</buttton
                    >
                  </div>
                  <div class="sm:w-1/3 flex sm:flex-row justify-end">
                    <Link
                      as="button"
                      :href="route('customers.create')"
                      class="
                        text-white
                        bg-indigo-500
                        border-0
                        py-2
                        px-6
                        focus:outline-none
                        hover:bg-indigo-600
                        rounded
                      "
                      >顧客登録</Link
                    >
                  </div>
                </div>
                <div v-if="noResults.isShow" class="text-center mt-10">
                  {{ noResults.message }}
                </div>
                <div v-else>
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
                            顧客ID
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
                            カナ
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
                            電話番号
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="customer in customers.data"
                          :key="customer.id"
                        >
                          <td class="px-4 py-3">
                            <a
                              class="text-blue-400"
                              :href="
                                route('customers.show', {
                                  customer: customer.id,
                                })
                              "
                              >{{ customer.id }}</a
                            >
                          </td>
                          <td class="px-4 py-3">
                            {{ customer.customer_name }}
                          </td>
                          <td class="px-4 py-3">{{ customer.kana }}</td>
                          <td class="px-4 py-3 text-lg text-gray-900">
                            {{ customer.tel }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <PaginationComponent
                    class="mt-6 w-full"
                    :links="customers.links"
                    v-if="customers.links"
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
