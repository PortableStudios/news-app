<script setup>
import { ref, onMounted, getCurrentInstance } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'
import NewsLayout from '@/Layouts/NewsLayout.vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import { Head } from '@inertiajs/vue3';
import moment from "moment";
import _ from "lodash"

const openNewsDetail = ref(false);
const props = defineProps(["data"]);
const newsReport = ref({})
const currentNews = ref({});
const bookmarkedNews = ref([]);

const showNewsDetail = (news) => {
    currentNews.value = news;
    openNewsDetail.value = true;
}

const removeBookmark = (news) => {
    axios.delete('/news/bookmark'.news.id)
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
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl pb-8 sm:pt-12 lg:max-w-none">
            <h2 class="text-2xl font-bold text-gray-900">Bookmarked News</h2>

            <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-6 lg:space-y-0">
                <div v-for="news in data" :key="news.id" class="group relative pb-4">
                    <div class="absolute z-50 end-1" @click="removeBookmark(news)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fde047" class="w-6 h-6">
      <path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0111.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 01-1.085.67L12 18.089l-7.165 3.583A.75.75 0 013.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93z" clip-rule="evenodd" />
    </svg>


                    </div>
                    <div
                        class="relative h-80 w-full overflow-hidden rounded-lg bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
                        <img :src="news.image" :alt="news.webTitle" class="h-full w-full object-cover object-center" />
                    </div>
                    <h3 class="mt-6 text-sm text-gray-500">
                        <a @click.prevent="showNewsDetail(news)" href="#">
                            <span class="absolute inset-0" />
                            {{ moment(news.published_date).format('DD/MM/YYYY') }}
                        </a>
                    </h3>
                    <p class="text-base font-semibold text-gray-900">{{ news.title }}</p>
                </div>
            </div>
        </div>
    </div>

    <TransitionRoot as="template" :show="openNewsDetail">
        <Dialog as="div" class="relative z-10" @close="openNewsDetail = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">

                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">
                                            {{ currentNews.webTitle }}</DialogTitle>
                                        <img :src="currentNews.fields.thumbnail" :alt="currentNews.webTitle"
                                            class="h-full w-full object-cover object-center" />
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">{{ `News Details` }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="button"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                    @click="openNewsDetail = false">Close</button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
