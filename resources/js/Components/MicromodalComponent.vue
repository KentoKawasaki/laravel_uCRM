<script setup>
import PaginationComponent from "@/Components/PaginationComponent.vue";
import axios from "axios";
import { Link } from "@inertiajs/inertia-vue3";
import { ref, reactive, onMounted } from "vue";

defineProps({
  placeHolder: String,
});

// onMounted(() => {
  // axios.get("/api/user").then((res) => {
  //   console.log(res.data);
  // });
// });


const isShow = ref(false);
const toggleStatus = () => {
  isShow.value = !isShow.value;
};

const search = ref("");

const searchForm = reactive({
  noResults: null,
  countOverMessage: null,
  customers: null,
});

const getResData = (res) => {
  const resData = res.data;
  // console.log(resData);
  const customersProps = resData.searchedCustomers;
  // console.log(customersProps);
  return {
    noResults: resData.noResults,
    countOverMessage: resData.countOverMessage,
    customers: customersProps,
  };
};

const searchCustomers = async () => {
  try {
    await axios
      .get(`/api/searchCustomers/?search=${search.value}`)
      .then((res) => {
        // console.log(res.data)
        // console.log(getResData(res));

        // console.log(searchForm);
        for (const [key, value] of Object.entries(getResData(res))) {
          searchForm[key] = value;
        }
      });
    toggleStatus();
  } catch (e) {
    console.log(e);
    console.log(e.message);
  }
};

const emit = defineEmits([
  'update:customerId'
])

const setCustomer = (object) =>{
  search.value= object.customer_name
  emit('update:customerId', object.id)
  toggleStatus()
}
</script>

<template>
  <div v-show="isShow" class="modal" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div
        class="modal__container w-2/3"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-1-title"
      >
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">Micromodal</h2>
          <button
            type="button"
            @click="toggleStatus"
            class="modal__close"
            aria-label="Close modal"
            data-micromodal-close
          ></button>
        </header>
        <main
          v-if="searchForm.noResults"
          class="modal__content"
          id="modal-1-content"
        >
          <p v-if="searchForm.noResults.isShow">
            {{ searchForm.noResults.message }}
          </p>
          <p v-else-if="searchForm.countOverMessage">
            {{ searchForm.countOverMessage }}
          </p>
          <div v-else class="lg:w-2/3 w-full mx-auto overflow-auto">
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
                <tr v-for="customer in searchForm.customers" :key="customer.id">
                  <td class="px-4 py-3">
                    <button
                      type="button"
                      @click="setCustomer({ id: customer.id, customer_name: customer.customer_name})"
                      class="text-blue-400"
                      >{{ customer.id }}</button>
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
        </main>
        <footer class="modal__footer">
          <button
            type="button"
            @click="toggleStatus"
            class="modal__btn"
            data-micromodal-close
            aria-label="Close this dialog window"
          >
            閉じる
          </button>
        </footer>
      </div>
    </div>
  </div>
  <input
    name="customer_name"
    v-model="search"
    :placeholder="(placeHolder === null) ? '' : placeHolder"
    @keyup.enter="searchCustomers"
    class="
      w-full
      bg-gray-100 bg-opacity-50
      rounded
      border border-gray-300
      focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200
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
  <button
    type="button"
    @click="searchCustomers"
    data-micromodal-trigger="modal-1"
    class="
      flex
      mx-auto
      text-white
      bg-teal-500
      border-0
      py-2
      px-8
      focus:outline-none
      hover:bg-teal-600
      rounded
      mt-2
      mb-5
    "
  >
    検索する
  </button>
</template>