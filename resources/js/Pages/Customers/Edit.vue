<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";
import { Core as YubinBangoCore } from "yubinbango-core2";

const props = defineProps({
  errors: Object,
  customer: Object,
});

const form = reactive({
  customer_name: props.customer.customer_name,
  kana: props.customer.kana,
  tel: props.customer.tel,
  email: props.customer.email,
  postcode: props.customer.postcode,
  address: props.customer.address,
  birthday: props.customer.birthday,
  gender: props.customer.gender,
  memo: props.customer.memo,
});

const fetchAddress = () => {
  new YubinBangoCore(String(form.postcode), (value) => {
    form.address = value.region + value.locality + value.street
  })
}

const updateCustomer = (id) => {
  Inertia.put(route("customers.update", {customer: id}), form);
};
</script>

<template>
  <Head title="顧客編集" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        顧客編集
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="updateCustomer(customer.id)">
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
                          <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            v-model="form.customer_name"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.customer_name"
                            :message="errors.customer_name"
                          ></InputError>
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
                          <input
                            type="text"
                            id="kana"
                            name="kana"
                            v-model="form.kana"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.kana"
                            :message="errors.kana"
                          ></InputError>
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
                          <input
                            type="tel"
                            id="tel"
                            name="tel"
                            v-model="form.tel"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.tel"
                            :message="errors.tel"
                          ></InputError>
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
                          <input
                            type="email"
                            id="email"
                            name="email"
                            v-model="form.email"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.email"
                            :message="errors.email"
                          ></InputError>
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
                          <input
                            type="number"
                            id="postcode"
                            name="postcode"
                            @change="fetchAddress"
                            v-model="form.postcode"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.postcode"
                            :message="errors.postcode"
                          ></InputError>
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
                          <input
                            type="text"
                            id="address"
                            name="address"
                            v-model="form.address"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.address"
                            :message="errors.address"
                          ></InputError>
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
                          <input
                            type="date"
                            id="birthday"
                            name="birthday"
                            v-model="form.birthday"
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
                          />
                          <InputError
                            class="mt-2"
                            v-if="errors.birthday"
                            :message="errors.birthday"
                          ></InputError>
                        </div>
                      </div>

                      <!-- 性別 -->
                      <div class="p-2 w-full">
                        <div class="relative">
                          <div class="flex">
                            <label
                              for=""
                              class="leading-7 text-sm text-gray-600"
                              >性別</label
                            >
                            <div class="flex justify-center ml-10">
                              <div>
                                <input
                                  type="radio"
                                  id="gender_0"
                                  name="gender"
                                  v-model="form.gender"
                                  value="0"
                                  class=""
                                />
                                <label for="gender_0">男性</label>
                              </div>

                              <div class="mx-5">
                                <input
                                  type="radio"
                                  id="gender_1"
                                  name="gender"
                                  v-model="form.gender"
                                  value="1"
                                  class=""
                                />
                                <label for="gender_1">女性</label>
                              </div>

                              <div>
                                <input
                                  type="radio"
                                  id="gender_2"
                                  name="gender"
                                  v-model="form.gender"
                                  value="2"
                                  class=""
                                />
                                <label for="gender_2">その他</label>
                              </div>
                            </div>
                          </div>

                          <InputError
                            class="mt-2"
                            v-if="errors.gender"
                            :message="errors.gender"
                          ></InputError>
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
                          <textarea
                            id="memo"
                            name="memo"
                            v-model="form.memo"
                            class="
                              w-full
                              bg-gray-100 bg-opacity-50
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
                          ></textarea>
                          <InputError
                            class="mt-2"
                            v-if="errors.memo"
                            :message="errors.memo"
                          ></InputError>
                        </div>
                      </div>

                      <div class="p-2 w-full">
                        <button
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
                          更新する
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
