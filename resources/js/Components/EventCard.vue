<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    event: Object
});

// Logika Urgensi
const urgencyStatus = computed(() => {
    if (!props.event.tanggal) return null;
    
    const eventDate = new Date(props.event.tanggal);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const diffTime = eventDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) {
        return { text: 'Telah Berakhir', class: 'bg-gray-800 text-white', icon: 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' };
    } else if (diffDays === 0) {
        return { text: 'HARI INI!', class: 'bg-red-600 text-white animate-pulse', icon: 'M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z' };
    } else if (diffDays <= 7) {
        return { text: `H-${diffDays} SEGERA!`, class: 'bg-orange-500 text-white', icon: 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' };
    }
    
    return null;
});
</script>

<template>
    <Link :href="`/event/${event.id}`" class="group block cursor-pointer flex flex-col h-full bg-white rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border border-gray-100 overflow-hidden relative">
        
        <div class="relative w-full aspect-[3/2] bg-gray-200 overflow-hidden">
            <img :src="event.gambar ? (event.gambar.startsWith('http') ? event.gambar : `/storage/${event.gambar}`) : 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=800'" 
                 :alt="event.nama_event"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out" />
            
            <!-- Subtle gradient overlay for premium feel -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

            <!-- Category Badge -->
            <div class="absolute top-4 right-4 z-10">
                <span class="bg-white/95 backdrop-blur-md text-gray-900 text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                    {{ event.category?.nama_kategori || 'Event' }}
                </span>
            </div>

            <!-- Urgency Badge -->
            <div v-if="urgencyStatus" class="absolute top-4 left-4 z-10">
                <span :class="['flex items-center gap-1.5 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg', urgencyStatus.class]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="urgencyStatus.icon" />
                    </svg>
                    {{ urgencyStatus.text }}
                </span>
            </div>
        </div>

        <div class="p-6 flex flex-col flex-grow relative bg-white">
            <h3 class="text-xl font-black text-gray-900 leading-snug mb-4 line-clamp-2 group-hover:text-rose-600 transition-colors duration-300">
                {{ event.nama_event }}
            </h3>
            
            <div class="flex flex-col gap-2.5 mb-5">
                <div class="flex items-center gap-3 text-gray-600">
                    <div class="bg-indigo-50 p-1.5 rounded-lg text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    </div>
                    <span class="text-sm font-medium">{{ event.tanggal || 'Belum ditentukan' }}</span> 
                </div>
                <div class="flex items-center gap-3 text-gray-600" v-if="event.waktu">
                    <div class="bg-orange-50 p-1.5 rounded-lg text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <span class="text-sm font-medium">{{ event.waktu.substring(0, 5) }} <template v-if="event.waktu_selesai">- {{ event.waktu_selesai.substring(0, 5) }}</template> WIB</span> 
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <div class="bg-rose-50 p-1.5 rounded-lg text-rose-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    </div>
                    <span class="text-sm font-medium truncate">{{ event.venue?.nama_venue || 'Lokasi Belum Ditentukan' }}</span>
                </div>
            </div>

            <!-- Voucher Banner (Promo FOMO) -->
            <div class="mb-5 bg-gradient-to-r from-rose-50 to-orange-50 border border-dashed border-rose-300 rounded-xl p-3 flex items-center gap-3 transform transition-transform duration-300 group-hover:scale-[1.02]">
                <div class="bg-white p-2 rounded-lg shadow-sm border border-rose-100 relative overflow-hidden">
                    <div class="absolute inset-0 bg-rose-100 opacity-20 animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-rose-600 relative z-10">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-black text-rose-700 uppercase tracking-wide">Promo Menarik! 🎉</p>
                    <p class="text-[11px] text-gray-600 font-medium mt-0.5">Klaim voucher diskon di detail event.</p>
                </div>
            </div>

            <div class="mt-auto"></div>

            <div class="pt-4 mt-2 border-t border-gray-100 flex items-end justify-between">
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Mulai Dari</p>
                    <p class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500 leading-none truncate pr-2">
                        {{ event.tikets && event.tikets.length > 0 ? 'Rp ' + Math.min(...event.tikets.map(t => t.harga)).toLocaleString('id-ID') : 'Belum Tersedia' }}
                    </p>
                </div>
                <button class="bg-gradient-to-r cursor-pointer from-rose-600 to-orange-500 hover:from-rose-500 hover:to-orange-400 text-white text-sm font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-rose-500/30 group-hover:shadow-rose-500/50 group-hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                    Beli Tiket
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover:translate-x-1 transition-transform">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                    </svg>
                </button>
            </div>
        </div>
    </Link>
</template>