<script setup>
import { ref, onMounted, getCurrentInstance } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'
import NewsLayout from '@/Layouts/NewsLayout.vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import { Head } from '@inertiajs/vue3';
import moment from "moment";
import _ from "lodash"
import BookmarkedNews from './BookmarkedNews.vue';

const openNewsDetail = ref(false);
const props = defineProps(["data", 'bookmark', "status"]);
const newsReport = ref({})
const searchText = ref('')
const currentPage = ref(1)
const currentNews = ref({});
const bookmarkedNews = ref([]);

onMounted(() => {
    newsReport.value = props.data
    bookmarkedNews.value = props.bookmark
})

const search = _.debounce(() => {
    axios.get("/news/search", { params: { q: searchText.value, page: currentPage.value } })
        .then((res) => {
            newsReport.value = res.data.data;
            console.log(res);
        }).catch((err) => {
            console.log("Error while searching news", err);
        }).finally(() => {

        })
}, 1000)

const showNewsDetail = (news) => {
    currentNews.value = news;
    openNewsDetail.value = true;
}

const addBookmark = (news) => {
    let postData = {
        news_id: news.id,
        title: news.webTitle,
        published_date: moment(news.webPublicationDate).format('YYYY-MM-DD HH:mm:ss'),
        image: news.fields.thumbnail,
        api_url: news.apiUrl
    };
    axios.post('/news/bookmark', postData)
        .then(result => {
            console.log("Result: ", result);
            bookmarkedNews.value.push(result.data.bookmark)
        })
        .catch((err) => {
            console.log("Error Data: ", err)
        })
        .finally(() => {
        })
}
</script>

<template>
    <Head title="News" />
    <NewsLayout>
        <template #header>
             <div class="flex max-w-md gap-x-4">
                <input name="search" v-model="searchText" type="text" @keyup="search" class="border-violet-500 min-w-0 flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2 shadow-sm shadow-inner ring-1 ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="Search Here..." />
                <!-- <button type="button" class="flex-none rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Search</button> -->
              </div>
        </template>
        <div class="bg-gray-100">
            <BookmarkedNews v-if="bookmarkedNews.length" :data="bookmarkedNews" />
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl pb-8 sm:pt-12 lg:max-w-none">
                    <h2 class="text-2xl font-bold text-gray-900">News</h2>

                    <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-6 lg:space-y-0">
                        <div v-for="news in newsReport.results" :key="news.id" class="group relative pb-4">
                            <div class="absolute z-50 end-1" @click="addBookmark(news)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fde047" class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
    </svg>

                            </div>
                            <div
                                class="relative h-80 w-full overflow-hidden rounded-lg bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
                                <img :src="news.fields.thumbnail" :alt="news.webTitle"
                                    class="h-full w-full object-cover object-center" />
                            </div>
                            <h3 class="mt-6 text-sm text-gray-500">
                                <a @click.prevent="showNewsDetail(news)" href="#">
                                    <span class="absolute inset-0" />
                                    {{ moment(news.webPublicationDate).format('DD/MM/YYYY') }}
                                </a>
                            </h3>
                            <p class="text-base font-semibold text-gray-900">{{ news.webTitle }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a href="#"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                        <a href="#"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                {{ ' ' }}
                                <span class="font-medium">1</span>
                                {{ ' ' }}
                                to
                                {{ ' ' }}
                                <span class="font-medium">10</span>
                                {{ ' ' }}
                                of
                                {{ ' ' }}
                                <span class="font-medium">97</span>
                                {{ ' ' }}
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <a href="#"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                                </a>
                                <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
                                <a href="#" aria-current="page"
                                    class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">1</a>
                                <a href="#"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a>
                                <a href="#"
                                    class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">3</a>
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                                <a href="#"
                                    class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">8</a>
                                <a href="#"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">9</a>
                                <a href="#"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">10</a>
                                <a href="#"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</NewsLayout>
    <TransitionRoot as="template" :show="openNewsDetail">
        <Dialog as="div" class="relative z-10" @close="openNewsDetail = false">
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
              <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                  <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">

                      <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">{{currentNews.webTitle}}</DialogTitle>
                        <img :src="currentNews.fields.thumbnail" :alt="currentNews.webTitle"
                                        class="h-full w-full object-cover object-center" />
                        <div class="mt-2">
                          <p class="text-sm text-gray-500">{{ `News Details` }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" @click="openNewsDetail = false">Close</button>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
</template>
