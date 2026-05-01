<template>
    <MainLayout>
    <Head title="Tiket Saya - TIXEVENT" />

        <section class="container mx-auto px-6 py-12 flex-grow max-w-5xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <h1 class="text-3xl font-black text-gray-900">Tiket Saya</h1>
                
                <a v-if="tikets.length > 0" href="/tiket-saya/download-pdf" target="_blank" class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-2.5 rounded-xl transition shadow-sm cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Unduh Semua Tiket (.PDF)
                </a>
            </div>
            
            <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-8">
                {{ $page.props.flash.success }}
            </div>
            
            <div v-if="tikets.length === 0" class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-lg">Anda belum memiliki tiket.</p>
                <Link href="/" class="mt-4 inline-block bg-rose-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-rose-700">Cari Event</Link>
            </div>
            
            <div v-else class="flex flex-col gap-6">
                <div v-for="(groupTikets, namaEvent) in groupedTikets" :key="namaEvent" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                    
                    <button @click="toggleGroup(namaEvent)" class="w-full flex items-center justify-between p-6 bg-white hover:bg-slate-50 transition text-left cursor-pointer outline-none">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ namaEvent }}</h2>
                                <p class="text-sm text-gray-500 font-medium mt-1">
                                    {{ groupTikets.length }} Tiket &bull; {{ groupTikets[0].tanggal }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span v-if="groupTikets.some(t => t.status_order === 'waiting_confirmation')" class="hidden md:inline-block bg-yellow-100 text-yellow-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                Menunggu Verifikasi Admin
                            </span>
                            
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 transition-transform duration-300" :class="{'rotate-180': expandedGroups.includes(namaEvent)}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                            </div>
                        </div>
                    </button>

                    <div v-show="expandedGroups.includes(namaEvent)" class="border-t border-gray-100 bg-slate-50/50 p-6">
                        
                        <div class="mb-5 flex justify-end">
                            <a :href="`/tiket-saya/download-pdf?event=${encodeURIComponent(namaEvent)}`" target="_blank" class="inline-flex items-center gap-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 px-4 py-2 rounded-lg text-sm font-bold transition shadow-sm cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                Cetak Tiket Event Ini (.PDF)
                            </a>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            
                            <div v-for="tiket in groupTikets" :key="tiket.id" class="flex flex-row bg-white border border-gray-200 rounded-xl shadow-sm relative transition-all overflow-hidden" :class="{'opacity-75 grayscale': tiket.status_checkin === 'sudah' || (isExpired(tiket) && tiket.status_checkin !== 'sudah')}">
                                
                                <div class="p-4 w-2/3 border-r border-gray-200 border-dashed relative flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">{{ tiket.jenis_tiket }}</span>
                                            
                                            <span v-if="tiket.status_checkin === 'sudah'" class="text-rose-600 flex items-center gap-1 text-[10px] font-bold uppercase">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                                                Dipakai
                                            </span>
                                            <span v-else-if="isExpired(tiket)" class="text-gray-500 flex items-center gap-1 text-[10px] font-bold uppercase">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                                                Kedaluwarsa
                                            </span>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-sm mb-1" :class="{'line-through text-gray-400': tiket.status_checkin === 'sudah' || isExpired(tiket)}">{{ tiket.kode_tiket.split('.')[0] }}</h4>
                                        <p class="text-xs text-gray-500 line-clamp-1">{{ tiket.nama_venue }}</p>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Waktu</p>
                                            <p class="text-xs font-semibold text-gray-900">{{ tiket.waktu ? tiket.waktu.substring(0,5) : '00:00' }}</p>
                                        </div>
                                        <div class="text-right" v-if="tiket.status_checkin !== 'sudah'">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Harga</p>
                                            <p class="text-xs font-semibold text-rose-600">Rp {{ Number(tiket.total_harga).toLocaleString('id-ID') }}</p>
                                        </div>
                                    </div>

                                    <div class="absolute -right-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-slate-50/50 rounded-full border-l border-gray-200"></div>
                                </div>
                                
                                <div class="w-1/3 flex items-center justify-center bg-gray-50 p-3 relative">
                                    <div class="absolute -left-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-slate-50/50 rounded-full border-r border-gray-200"></div>
                                    
                                    <div v-if="tiket.status_order === 'waiting_confirmation'" class="text-center">
                                        <span class="text-yellow-600 text-[10px] font-bold text-center">Menunggu Verifikasi</span>
                                    </div>
                                    
                                    <button v-else-if="tiket.status_order === 'paid'" @click="openQrModal(tiket)" class="w-full flex flex-col items-center justify-center group transition-all cursor-pointer">
                                        <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm transition-colors group-hover:border-rose-300">
                                            <qrcode-vue :value="tiket.kode_tiket" :size="60" level="M" />
                                        </div>
                                        <p class="text-[10px] font-bold mt-2 uppercase tracking-widest text-gray-500 group-hover:text-rose-600">
                                            Tampilkan QR
                                        </p>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        

        <Teleport to="body">
            <div v-if="selectedQrTiket" class="fixed inset-0 z-[100] flex items-center justify-center px-4" @click.self="closeQrModal">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeQrModal"></div>
            
            <div class="relative z-10 w-full max-w-md animate-fade-in-up">
                
                <div id="ticket-capture-area" class="bg-white rounded-3xl overflow-hidden shadow-2xl relative">
                    <div class="bg-rose-600 text-white p-6 text-center relative">
                        <button v-if="!isDownloading" @click="closeQrModal" class="absolute cursor-pointer top-4 right-4 text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-full transition" data-html2canvas-ignore>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <h4 class="font-bold text-xl mb-1">{{ selectedQrTiket.status_checkin === 'sudah' ? 'Tiket Telah Dipakai' : 'Siap di-Scan!' }}</h4>
                        <p class="text-white/80 text-sm">{{ selectedQrTiket.status_checkin === 'sudah' ? 'Tiket ini sudah tidak berlaku' : 'Tunjukkan layar ini ke panitia di pintu masuk' }}</p>
                    </div>
                    
                    <div class="p-10 flex flex-col items-center justify-center bg-white relative">
                        <div v-if="selectedQrTiket.status_checkin === 'sudah'" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                            <span class="rotate-[-25deg] border-4 border-red-500 text-red-500 font-black text-4xl px-4 py-2 uppercase tracking-widest rounded-lg opacity-80 backdrop-blur-[1px]">DIPAKAI</span>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border-2 border-gray-100 shadow-sm mb-6 inline-block" :class="{'opacity-50 grayscale': selectedQrTiket.status_checkin === 'sudah'}">
                            <qrcode-vue :value="selectedQrTiket.kode_tiket" :size="240" level="H"/>
                        </div>
                        
                        <p class="text-sm text-gray-500 uppercase tracking-widest font-bold mb-1">KODE TIKET</p>
                        <div class="flex items-center justify-center gap-3 w-full px-4">
                            <p class="text-2xl font-black text-gray-900 tracking-widest break-all text-center" :class="{'line-through text-gray-400': selectedQrTiket.status_checkin === 'sudah'}">{{ selectedQrTiket.kode_tiket.split('.')[0] }}</p>
                            <button v-if="!isDownloading" @click="copyKode(selectedQrTiket.kode_tiket)" class="shrink-0 cursor-pointer p-2 bg-gray-100 hover:bg-rose-100 hover:text-rose-600 text-gray-500 rounded-xl transition" title="Salin Kode Tiket" data-html2canvas-ignore>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" /></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 border-t border-gray-100 p-6 relative">
                        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                            <span class="text-6xl font-black tracking-tighter">TIXEVENT</span>
                        </div>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Event</span>
                            <span class="font-bold text-gray-900 text-right">{{ selectedQrTiket.nama_event }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Kategori</span>
                            <span class="font-bold text-gray-900 text-right">{{ selectedQrTiket.jenis_tiket }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pemesan</span>
                            <span class="font-bold text-gray-900 text-right">{{ $page.props.auth?.user?.name }}</span>
                        </div>
                    </div>
                </div>

                <button @click="downloadTiketAsImage" :disabled="isDownloading" class="w-full mt-4 bg-gray-900 text-white font-bold py-4 px-6 rounded-2xl shadow-xl hover:bg-black transition-all flex items-center justify-center gap-2 cursor-pointer disabled:bg-gray-600">
                    <span v-if="isDownloading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyimpan...
                    </span>
                    <span v-else class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Download Tiket
                    </span>
                </button>
            </div>
            </div>
        </Teleport>
</MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import QrcodeVue from 'qrcode.vue';
import { toPng } from 'html-to-image';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    tikets: Array
});


const groupedTikets = computed(() => {
    if (!props.tikets) return {};
    
    return props.tikets.reduce((groups, tiket) => {
        const eventName = tiket.nama_event;
        if (!groups[eventName]) {
            groups[eventName] = [];
        }
        groups[eventName].push(tiket);
        return groups;
    }, {});
});


const expandedGroups = ref([]);


const toggleGroup = (eventName) => {
    const index = expandedGroups.value.indexOf(eventName);
    if (index === -1) {

        expandedGroups.value.push(eventName);
    } else {

        expandedGroups.value.splice(index, 1);
    }
};


if (props.tikets && props.tikets.length > 0) {
    const firstEventName = Object.keys(groupedTikets.value)[0];
    if (firstEventName) {
        expandedGroups.value.push(firstEventName);
    }
}


const selectedQrTiket = ref(null);
const isDownloading = ref(false);

const openQrModal = (tiket) => {
    selectedQrTiket.value = tiket;
    document.body.style.overflow = 'hidden';
};

const closeQrModal = () => {
    if(isDownloading.value) return; 
    selectedQrTiket.value = null;
    document.body.style.overflow = 'auto';
};

const copyKode = (kode) => {
    navigator.clipboard.writeText(kode).then(() => {
        alert('Kode tiket disalin!');
    }).catch(err => {
        console.error('Gagal menyalin:', err);
    });
};

const downloadTiketAsImage = async () => {
    if (!selectedQrTiket.value) return;
    
    isDownloading.value = true;
    
    try {
        await new Promise(resolve => setTimeout(resolve, 300));
        
        const captureArea = document.getElementById('ticket-capture-area');
        
        const dataUrl = await toPng(captureArea, {
            pixelRatio: 2,
            backgroundColor: '#ffffff'
        });
        
        const safeEventName = selectedQrTiket.value.nama_event.replace(/[^a-zA-Z0-9]/g, '-').toUpperCase();
        const fileName = `TIXEVENT-${safeEventName}-${selectedQrTiket.value.kode_tiket.split('.')[0]}.png`;
        
        const link = document.createElement('a');
        link.download = fileName;
        link.href = dataUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
    } catch (err) {
        console.error('Gagal mengunduh tiket:', err);
        alert('Terjadi kesalahan saat mengunduh tiket.');
    } finally {
        isDownloading.value = false;
    }
};

const isExpired = (tiket) => {
    if (!tiket.tanggal) return false;
    const eventDate = new Date(tiket.tanggal);
    const today = new Date();
    eventDate.setHours(0,0,0,0);
    today.setHours(0,0,0,0);
    return eventDate < today;
};
</script>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>