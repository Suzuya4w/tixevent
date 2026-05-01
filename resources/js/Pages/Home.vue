<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { useIntersectionObserver, useTransition } from '@vueuse/core';
import { onMounted, ref, watchEffect } from "vue";
import EventCard from "../Components/EventCard.vue";
import MainLayout from "../Layouts/MainLayout.vue";

const props = defineProps({
    events: Array,
    categories: Array,
    filters: Object,
    vouchers: Array,
    stats: Object,
});

const searchQuery = ref("");
const searchDate = ref("");

const localVouchers = ref([]);

watchEffect(() => {
    if (props.vouchers) {
        localVouchers.value = props.vouchers.map(v => ({
            ...v,
            isFlipped: false
        }));
    }
});

const toggleVoucher = (index) => {
    localVouchers.value[index].isFlipped = !localVouchers.value[index].isFlipped;
};

const statsSectionRef = ref(null);
const targetEvents = ref(0);
const targetTickets = ref(0);
const targetUsers = ref(0);

useIntersectionObserver(statsSectionRef, ([entry]) => {
    if (entry.isIntersecting) {

        targetEvents.value = props.stats?.total_events || 0;
        targetTickets.value = props.stats?.total_tiket_terjual || 0;
        targetUsers.value = props.stats?.total_user || 0;
    }
}, { threshold: 0.3 }); 


const animatedEvents = useTransition(targetEvents, { duration: 2000 });
const animatedTickets = useTransition(targetTickets, { duration: 2000 });
const animatedUsers = useTransition(targetUsers, { duration: 2000 });

const rotateWords = ["Music.", "Sports.", "E-Sports.", "Art.", "Memories."];
const displayText = ref("");
let loopNum = 0;
let isDeleting = false;

onMounted(() => {
const type = () => {
        const i = loopNum % rotateWords.length;
        const fullText = rotateWords[i];

      
        if (isDeleting) {
            displayText.value = fullText.substring(0, displayText.value.length - 1);
        } else {
            displayText.value = fullText.substring(0, displayText.value.length + 1);
        }

       
        let typeSpeed = isDeleting ? 50 : 100;

       
        if (!isDeleting && displayText.value === fullText) {
            typeSpeed = 2000;
            isDeleting = true;
        } 
       
        else if (isDeleting && displayText.value === '') {
            isDeleting = false;
            loopNum++;
            typeSpeed = 500; 
        }

        setTimeout(type, typeSpeed);
    };

    type();
});

const eventsSectionRef = ref(null);
const featuresSectionRef = ref(null);
const howItWorksSectionRef = ref(null);

const observeReveal = (elementRef) => {
    useIntersectionObserver(elementRef, ([entry]) => {

        if (entry.isIntersecting && elementRef.value) {

            elementRef.value.classList.remove('opacity-0', 'translate-y-10');
            elementRef.value.classList.add('opacity-100', 'translate-y-0');
        }
    }, { threshold: 0.1 }); 
};

onMounted(() => {
    observeReveal(eventsSectionRef);
    observeReveal(featuresSectionRef);
    observeReveal(howItWorksSectionRef);
});

const submitSearch = () => {
    let params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (searchDate.value) params.date = searchDate.value;

    router.get("/events", params);
};
</script>

<template>
    <MainLayout>
        <Head title="TIX-EVENT | Cari Memori Terbaikmu" />

        <section
            class="relative pt-20 pb-16 px-4 flex flex-col items-center justify-center text-center overflow-hidden"
        >
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-50 via-white to-white -z-10 animate-fade-in"
            ></div>

<!-- Hero Section Text -->
            <h1 class="text-6xl md:text-[5.5rem] font-black tracking-tighter mb-4 text-gray-900 leading-none animate-fade-in-up flex flex-col md:flex-row items-center justify-center md:gap-3">
                <span>Fans Know</span>
                
                <span class="text-rose-600 inline-flex items-center">
                    {{ displayText }}
                    
                    <span class="w-1.5 md:w-2 h-12 md:h-16 bg-gray-900 ml-1 animate-pulse"></span>
                </span>
            </h1>
            <p
                class="text-lg md:text-xl text-gray-500 font-medium mb-12 max-w-2xl animate-fade-in-up-delay-1 opacity-0"
            >
                Memori terbaik dimulai dengan harga terbaik. <br />Temukan event
                favoritmu sekarang.
            </p>

            <div
                class="bg-white rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 flex items-center p-2 w-full max-w-3xl hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-shadow duration-300 animate-fade-in-up-delay-2 opacity-0"
            >
                <label
                    class="flex-1 px-6 py-2 text-left border-r border-gray-200 cursor-text group"
                >
                    <span
                        class="block text-[11px] font-extrabold uppercase tracking-wider text-gray-800 group-hover:text-rose-600 transition-colors"
                        >Cari</span
                    >
                    <input
                        v-model="searchQuery"
                        @keyup.enter="submitSearch"
                        type="text"
                        placeholder="Artis, tim, atau venue"
                        class="w-full bg-transparent outline-none text-sm text-gray-600 placeholder-gray-400 mt-0.5 truncate"
                    />
                </label>
                <label
                    class="flex-1 px-6 py-2 text-left hidden md:block cursor-text group"
                >
                    <span
                        class="block text-[11px] font-extrabold uppercase tracking-wider text-gray-800 group-hover:text-rose-600 transition-colors"
                        >Kapan</span
                    >
                    <input
                        v-model="searchDate"
                        @change="submitSearch"
                        type="date"
                        class="w-full bg-transparent outline-none text-sm text-gray-600 mt-0.5"
                    />
                </label>
                <button
                    @click="submitSearch"
                    class="bg-rose-600 hover:bg-rose-700 text-white rounded-full p-4 ml-2 transition flex items-center justify-center cursor-pointer hover:scale-105 active:scale-95"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                        />
                    </svg>
                </button>
            </div>
        </section>

        <section class="container mx-auto px-6 mb-12 animate-fade-in-up-delay-2 opacity-0">
            <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
                <Link
                    href="/"
                    preserve-scroll
                    class="snap-start flex-shrink-0 flex items-center px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300 hover:scale-105"
                    :class="
                        !filters?.kategori
                            ? 'bg-gray-900 text-white shadow-md'
                            : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-900'
                    "
                >
                    Semua Event
                </Link>
                <Link
                    v-for="(cat, index) in categories"
                    :key="cat.id"
                    :href="`/?kategori=${cat.slug}`"
                    preserve-scroll
                    class="snap-start flex-shrink-0 flex items-center px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300 hover:scale-105"
                    :class="
                        filters?.kategori === cat.slug
                            ? 'bg-gray-900 text-white shadow-md'
                            : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-900'
                    "
                >
                    {{ cat.nama_kategori }}
                </Link>
            </div>
        </section>

        <section 
            ref="eventsSectionRef" 
            class="container mx-auto px-6 mb-20 flex-grow opacity-0 translate-y-10 transition-all duration-1000 ease-out"
        >
            <div
                class="flex justify-between items-end mb-6 border-b border-gray-100 pb-4"
            >
                <h2 class="text-2xl font-bold text-gray-900">Event Terdekat</h2>
                <Link
                    href="/events"
                    class="text-sm font-semibold text-rose-600 hover:text-rose-800 transition flex items-center gap-1 group"
                >
                    Lihat Semua <span aria-hidden="true" class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </Link>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
            >
                <EventCard
                    v-for="(event, index) in events"
                    :key="event.id"
                    :event="event"
                    class="animate-scale-in opacity-0"
                    :style="`animation-delay: ${0.2 + (index * 0.1)}s`"
                />
            </div>

            <div v-if="events.length === 0" class="text-center py-20">
                <p class="text-gray-500 text-lg">Belum ada event terdekat.</p>
            </div>
        </section>

       
        <section v-if="vouchers && vouchers.length > 0" class="container mx-auto px-6 mb-16 animate-fade-in-up-delay-3 opacity-0">
            <div class="bg-gradient-to-r from-rose-600 via-pink-600 to-orange-500 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden group">

                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-700"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2 group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl animate-bounce-slow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-black">Voucher Spesial 🎉</h2>
                            <p class="text-white/80 text-sm font-medium">Hemat lebih banyak dengan kode promo ini!</p>
                        </div>
                    </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">

    <div 
        v-for="(voucher, index) in localVouchers" 
        :key="voucher.id" 
        class="flip-card w-full h-36 cursor-pointer group/card"
        :class="{ 'is-flipped': voucher.isFlipped }"
        @click="toggleVoucher(index)"
    >
        <div class="flip-card-inner">

            <div class="flip-card-front bg-white/20 border-2 border-dashed border-white/40 rounded-2xl flex flex-col items-center justify-center hover:bg-white/30 transition-colors shadow-lg">
                <div class="bg-white/20 p-3 rounded-full mb-2 group-hover/card:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
                <span class="text-white font-bold text-xs uppercase tracking-widest animate-pulse">Tap to Reveal!</span>
            </div>

            <div class="flip-card-back bg-white/20 border border-white/30 rounded-2xl p-5 shadow-[0_0_25px_rgba(255,255,255,0.15)] flex flex-col justify-center">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-white text-rose-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Promo</span>
                    <span class="text-white/80 text-xs font-medium">Sisa {{ voucher.kuota }}</span>
                </div>
                <p class="font-mono text-2xl font-black tracking-wider text-white mb-1">{{ voucher.kode_voucher }}</p>
                <p class="text-white/90 text-sm font-semibold">Potongan Rp {{ Number(voucher.potongan).toLocaleString('id-ID') }}</p>
                <p class="text-white/60 text-[10px] mt-1">s.d {{ new Date(voucher.tanggal_berakhir).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}</p>
            </div>
        </div>
    </div>
</div>

                    <p class="text-white/70 text-xs mt-6 text-center">* Masukkan kode voucher saat checkout di halaman detail event.</p>
                </div>
            </div>
        </section>

 
        <!-- Stats Section -->
        <section ref="statsSectionRef" class="container mx-auto px-6 mb-24">
            <div class="bg-gray-900 rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-rose-500/20 transition-colors duration-700"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 group-hover:bg-blue-500/20 transition-colors duration-700"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 relative z-10 divide-y md:divide-y-0 md:divide-x divide-gray-800">
                    <div class="text-center pt-4 md:pt-0 hover:-translate-y-1 transition-transform duration-300">
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                           
                            <span class="text-4xl md:text-5xl font-black text-white">{{ Math.round(animatedEvents) }}+</span>
                        </div>
                        <p class="text-gray-400 font-medium">Event Tersedia</p>
                    </div>
                    <div class="text-center pt-8 md:pt-0 hover:-translate-y-1 transition-transform duration-300">
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            
                            <span class="text-4xl md:text-5xl font-black text-white">{{ Math.round(animatedTickets) }}+</span>
                        </div>
                        <p class="text-gray-400 font-medium">Tiket Terjual</p>
                    </div>
                    <div class="text-center pt-8 md:pt-0 hover:-translate-y-1 transition-transform duration-300">
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            
                            <span class="text-4xl md:text-5xl font-black text-white">{{ Math.round(animatedUsers) }}+</span>
                        </div>
                        <p class="text-gray-400 font-medium">Pengguna Terdaftar</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Section -->
        <section 
            ref="featuresSectionRef" 
            class="py-24 bg-white overflow-hidden relative opacity-0 translate-y-10 transition-all duration-1000 ease-out"
        >
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-rose-50 rounded-full blur-3xl opacity-50 z-0"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-rose-600 font-bold tracking-wider uppercase text-sm mb-2 block">Keunggulan Kami</span>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Kenapa Harus TIXEVENT?</h2>
                    <p class="text-gray-500 text-lg">Platform penjualan tiket yang dirancang khusus untuk memberikan pengalaman terbaik, aman, dan mudah bagi semua pengguna.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gray-50 rounded-3xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transaksi Aman</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Setiap transaksi diverifikasi oleh sistem kami untuk memastikan keamanan data dan pembayaran Anda terlindungi 100%.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="bg-gray-50 rounded-3xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">QR Code Digital</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Tiket otomatis terbit dengan QR Code unik. Cukup tunjukkan dari HP Anda dan scan saat tiba di lokasi event.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gray-50 rounded-3xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Penawaran Eksklusif</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Dapatkan kode voucher spesial dan nikmati potongan harga. Lebih hemat untuk setiap pengalaman seru Anda.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-gray-50 rounded-3xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Download PDF</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">Simpan tiket dalam format PDF yang berisi detail acara lengkap. Mudah diakses kapanpun, bahkan tanpa koneksi internet.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section 
            ref="howItWorksSectionRef" 
            class="py-24 bg-gray-50 border-t border-gray-200 opacity-0 translate-y-10 transition-all duration-1000 ease-out"
        >
            <div class="container mx-auto px-6 max-w-6xl">
                <div class="flex flex-col md:flex-row items-center gap-16">
                    <div class="w-full md:w-1/2">
                        <span class="text-rose-600 font-bold tracking-wider uppercase text-sm mb-2 block">Langkah Mudah</span>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-6">Cara Cepat<br/>Memesan Tiket</h2>
                        <p class="text-gray-500 text-lg mb-8">Tidak perlu ribet. Cukup 3 langkah sederhana untuk mengamankan tempat Anda di event yang paling ditunggu-tunggu.</p>
                        
                        <div class="space-y-8">
                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-12 h-12 bg-white border border-gray-200 rounded-full flex items-center justify-center font-black text-rose-600 shadow-sm z-10 group-hover:scale-110 transition-transform">1</div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pilih Event & Tiket</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed">Cari event favoritmu dan pilih kategori tiket yang sesuai keinginan. Jangan lupa masukkan voucher jika ada.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-12 h-12 bg-white border border-gray-200 rounded-full flex items-center justify-center font-black text-rose-600 shadow-sm z-10 group-hover:scale-110 transition-transform">2</div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Lakukan Pembayaran</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed">Selesaikan pembayaran sesuai instruksi. Unggah bukti transfer Anda untuk verifikasi oleh tim admin kami.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 group">
                                <div class="flex-shrink-0 w-12 h-12 bg-rose-600 text-white shadow-lg shadow-rose-200 rounded-full flex items-center justify-center font-black z-10 group-hover:scale-110 transition-transform">3</div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Dapatkan Tiket</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed">Setelah pembayaran dikonfirmasi, e-ticket dengan QR Code otomatis dikirim ke akun Anda. Siap digunakan!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-1/2 relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-rose-100 to-blue-50 rounded-[3rem] transform rotate-3 scale-105 -z-10 animate-pulse-slow"></div>
                        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100 hover:shadow-2xl transition-shadow duration-500 hover:-translate-y-2">
                            <div class="space-y-4">
                                <!-- Mockup Ticket -->
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex gap-4 items-center hover:bg-white transition-colors">
                                    <div class="w-16 h-16 bg-gray-200 rounded-xl flex-shrink-0 overflow-hidden">
                                        <div class="w-full h-full bg-rose-200 animate-pulse"></div>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                                </div>
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex gap-4 items-center opacity-70 hover:opacity-100 transition-opacity">
                                    <div class="w-16 h-16 bg-gray-200 rounded-xl flex-shrink-0 overflow-hidden">
                                        <div class="w-full h-full bg-blue-200"></div>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex gap-4 items-center opacity-40 hover:opacity-100 transition-opacity">
                                    <div class="w-16 h-16 bg-gray-200 rounded-xl flex-shrink-0 overflow-hidden">
                                        <div class="w-full h-full bg-emerald-200"></div>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}




.flip-card {
    perspective: 1000px;
}

.flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.7s cubic-bezier(0.4, 0.2, 0.2, 1);
    transform-style: preserve-3d;
}


.is-flipped .flip-card-inner {
    transform: rotateY(180deg);
}

.flip-card-front,
.flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    

    -webkit-backface-visibility: hidden; 
    backface-visibility: hidden;
    

    -webkit-transform: translateZ(1px);
    transform: translateZ(1px);

    border-radius: 1rem; 
}

.flip-card-back {
    transform: rotateY(180deg);
}


/* Animations */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-in-up-delay-1 {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards;
}

.animate-fade-in-up-delay-2 {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
}

.animate-fade-in-up-delay-3 {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.45s forwards;
}

.animate-scale-in {
    animation: scale-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-bounce-slow {
    animation: bounce 3s infinite;
}

.animate-pulse-slow {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>