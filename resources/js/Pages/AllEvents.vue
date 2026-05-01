<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import EventCard from '../Components/EventCard.vue';
import MainLayout from '../Layouts/MainLayout.vue';

const props = defineProps({
    events: Object,
    categories: Array,
    filters: Object,
});

const searchQuery = ref(props.filters?.search || '');
const searchDate = ref(props.filters?.date || '');

const submitSearch = () => {
    let params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (searchDate.value) params.date = searchDate.value;
    if (props.filters?.kategori) params.kategori = props.filters.kategori;
    
    router.get('/events', params, { preserveState: true, replace: true });
};
</script>

<template>
    <MainLayout>
    <Head title="TIX-EVENT | Cari Memori Terbaikmu" />

        <section class="bg-slate-50 border-b border-gray-100 pt-10 pb-8">
            <div class="container mx-auto px-6 max-w-4xl">
                <h1 class="text-3xl font-black tracking-tight text-gray-900 mb-6 text-center">Eksplorasi Semua Event</h1>
                <div class="bg-white rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 flex items-center p-2 w-full hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-shadow duration-300">
                    <label class="flex-1 px-6 py-2 text-left border-r border-gray-200 cursor-text">
                        <span class="block text-[11px] font-extrabold uppercase tracking-wider text-gray-800">Cari</span>
                        <input v-model="searchQuery" @keyup.enter="submitSearch" type="text" placeholder="Artis, tim, atau venue" class="w-full bg-transparent outline-none text-sm text-gray-600 placeholder-gray-400 mt-0.5 truncate" />
                    </label>
                    <label class="flex-1 px-6 py-2 text-left hidden md:block cursor-text">
                        <span class="block text-[11px] font-extrabold uppercase tracking-wider text-gray-800">Kapan</span>
                        <input v-model="searchDate" @change="submitSearch" type="date" class="w-full bg-transparent outline-none text-sm text-gray-600 mt-0.5" />
                    </label>
                    <button @click="submitSearch" class="bg-rose-600 hover:bg-rose-700 text-white rounded-full p-4 ml-2 transition flex items-center justify-center cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </button>
                </div>
            </div>
        </section>
        <section class="container mx-auto px-6 mb-12 mt-12">
            <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
                <Link href="/" preserve-scroll class="snap-start flex-shrink-0 flex items-center px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300" :class="!filters?.kategori ? 'bg-gray-900 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-900'">
                    Semua Event
                </Link>
                <Link v-for="cat in categories" :key="cat.id" :href="`/?kategori=${cat.slug}`" preserve-scroll class="snap-start flex-shrink-0 flex items-center px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300" :class="filters?.kategori === cat.slug ? 'bg-gray-900 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-900'">
                    {{ cat.nama_kategori }}
                </Link>
            </div>
        </section>

        <section class="container mx-auto px-6 py-12 flex-grow">
            <div class="flex justify-between items-end mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">Daftar Semua Event</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <EventCard v-for="event in events.data" :key="event.id" :event="event" />
            </div>

            <div v-if="events.data.length === 0" class="text-center py-20">
                <p class="text-gray-500 text-lg">Tidak ada event yang sesuai pencarian.</p>
            </div>
        </section>

    </MainLayout>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>