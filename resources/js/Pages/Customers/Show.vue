<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { nl2br } from "@/common";

defineProps({
  customer: Object,
});

const deleteCustomer = (id) => {
  Inertia.delete(route("customers.destroy", { customer: id }), {
    onBefore: () => confirm("本当に削除しますか？"),
  });
};

const gender = ["男性", "女性", "その他"];
</script>

<template>
  <Head title="顧客詳細" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        顧客詳細
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="storeCustomer">
                <div class="container px-5 py-8 mx-auto">
                  <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                      <!-- 氏名 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="customer_name"
                            class="leading-7 text-sm text-gray-600"
                            >氏名</label
                          >
                          <div
                            id="customer_name"
                            class="
                              w-full
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
                            {{ customer.customer_name }}
                          </div>
                        </div>
                      </div>

                      <!-- カナ -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="kana"
                            class="leading-7 text-sm text-gray-600"
                            >カナ</label
                          >
                          <div
                            id="kana"
                            class="
                              w-full
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
                            {{ customer.kana }}
                          </div>
                        </div>
                      </div>

                      <!-- 電話番号 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="tel"
                            class="leading-7 text-sm text-gray-600"
                            >電話番号</label
                          >
                          <div
                            id="tel"
                            class="
                              w-full
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
                            {{ customer.tel }}
                          </div>
                        </div>
                      </div>

                      <!-- メールアドレス -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="email"
                            class="leading-7 text-sm text-gray-600"
                            >メールアドレス</label
                          >
                          <div
                            id="email"
                            class="
                              w-full
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
                            {{ customer.email }}
                          </div>
                        </div>
                      </div>

                      <!-- 郵便番号 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="postcode"
                            class="leading-7 text-sm text-gray-600"
                            >郵便番号</label
                          >
                          <div
                            id="postcode"
                            name="postcode"
                            class="
                              w-full
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
                            {{ customer.postcode }}
                          </div>
                        </div>
                      </div>

                      <!-- 住所  -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="address"
                            class="leading-7 text-sm text-gray-600"
                            >住所</label
                          >
                          <div
                            id="address"
                            class="
                              w-full
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
                            {{ customer.address }}
                          </div>
                        </div>
                      </div>

                      <!-- 誕生日 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="birthday"
                            class="leading-7 text-sm text-gray-600"
                            >誕生日</label
                          >
                          <div
                            id="birthday"
                            class="
                              w-full
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
                            {{ customer.birthday }}
                          </div>
                        </div>
                      </div>

                      <!-- 性別 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="" class="leading-7 text-sm text-gray-600"
                            >性別</label
                          >
                          <div
                            class="
                              w-full
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
                            {{ gender[customer.gender] }}
                          </div>
                        </div>
                      </div>
                      <!-- メモ -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="memo"
                            class="leading-7 text-sm text-gray-600"
                            >メモ</label
                          >
                          <div
                            id="memo"
                            v-html="nl2br(customer.memo)"
                            class="
                              w-full
                              rounded
                              border border-gray-300
                              focus:border-indigo-500
                              focus:bg-white
                              focus:ring-2
                              focus:ring-indigo-200
                              h-32
                              text-base
                              outline-none
                              text-gray-700
                              py-1
                              px-3
                              resize-none
                              leading-6
                              transition-colors
                              duration-200
                              ease-in-out
                            "
                          />
                        </div>
                      </div>

                      <div class="mt-10 p-2 w-1/2 inline-block">
                        <Link
                          as="button"
                          :href="
                            route('customers.edit', { customer: customer.id })
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
                        >
                          編集する
                        </Link>
                      </div>
                      <div class="mt-10 p-2 w-1/2 inline-block">
                        <button
                          @click="deleteCustomer(customer.id)"
                          class="
                            flex
                            mx-auto
                            text-white
                            bg-red-500
                            border-0
                            py-2
                            px-8
                            focus:outline-none
                            hover:bg-red-600
                            rounded
                            text-lg
                          "
                        >
                          削除する
                        </button>
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
