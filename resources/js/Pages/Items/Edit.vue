<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
  errors: Object,
  item: Object,
});

const form = reactive({
  id: props.item.id,
  item_name: props.item.item_name,
  memo: props.item.memo,
  price: props.item.price,
  is_selling: props.item.is_selling,
});

const updateItem = (id) => {
  Inertia.put(route("items.update", {item: id}), form);
};
</script>

<template>
  <Head title="商品編集" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        商品編集
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="updateItem(form.id)">
                <div class="container px-5 py-8 mx-auto">
                  <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label
                            for="item_name"
                            class="leading-7 text-sm text-gray-600"
                            >商品名</label
                          >
                          <input
                            type="text"
                            id="item_name"
                            name="item_name"
                            v-model="form.item_name"
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
                            v-if="errors.item_name"
                            :message="errors.item_name"
                          ></InputError>
                        </div>
                      </div>

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
                        <div class="relative">
                          <label
                            for="price"
                            class="leading-7 text-sm text-gray-600"
                            >商品価格</label
                          >
                          <input
                            type="number"
                            id="price"
                            name="price"
                            v-model="form.price"
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
                            v-if="errors.price"
                            :message="errors.price"
                          ></InputError>
                        </div>
                      </div>

                      <div class="p-2 w-full">
                        <div class="relative">
                          <label class="leading-7 text-sm text-gray-600"
                            >ステータス</label
                          >
                          <div class="inline-block ml-10">
                            <input
                              type="radio"
                              id="is_selling_t"
                              name="is_selling"
                              v-model="form.is_selling"
                              value="1"
                              class=""
                            />
                            <label for="is_selling_t" class="ml-2">販売中</label>
                          </div>
                          <div class="inline-block ml-5">
                            <input
                              type="radio"
                              id="is_selling_f"
                              name="is_selling"
                              v-model="form.is_selling"
                              value="0"
                              class=""
                            />
                            <label for="is_selling_f" class="ml-2">停止中</label>
                          </div>

                          <InputError
                            class="mt-2"
                            v-if="errors.is_selling"
                            :message="errors.is_selling"
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
