<template>
    <Head title="Selesaikan Pembayaran - TIXEVENT" />

    <div class="min-h-screen bg-slate-50 text-gray-900 font-sans flex flex-col">
        <!-- Navbar -->
        <nav class="border-b border-gray-100 sticky top-0 z-50 bg-white/90 backdrop-blur-md">
            <div class="container mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-rose-600 rounded-lg flex items-center justify-center text-white font-bold">T</div>
                    <span class="font-bold text-xl tracking-tight">TIXEVENT</span>
                </div>
                <div class="hidden md:flex items-center gap-2 font-bold text-sm text-green-600 bg-green-50 border border-green-100 px-4 py-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    Pembayaran Aman
                </div>
            </div>
        </nav>

        <section class="container mx-auto px-4 md:px-6 py-12 flex-grow flex items-center justify-center">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-rose-100/50 p-6 md:p-10 max-w-xl w-full">
                
                <div class="text-center mb-8">
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-2">Selesaikan Pembayaran</h2>
                    <p class="text-gray-500 mb-3">Mohon segera transfer sebelum batas waktu habis.</p>

                    <div class="inline-flex items-center gap-2 bg-rose-50 text-rose-700 px-4 py-2 rounded-full font-bold shadow-sm border border-rose-100 transition-all" :class="{ 'animate-pulse bg-red-100 text-red-700 border-red-200': timeLeft < 60 }">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <span>Sisa Waktu: {{ formatTime(timeLeft) }}</span>
                    </div>
                </div>
                
                <!-- Rincian Pesanan -->
                <div class="bg-gray-50 rounded-2xl p-6 mb-8 border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-rose-100 rounded-full blur-3xl -mr-10 -mt-10 opacity-60"></div>
                    
                    <div class="flex justify-between items-center mb-6 relative z-10">
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Order ID</span>
                        <span class="bg-white border border-gray-200 px-3 py-1 rounded-full text-xs font-bold text-gray-700 shadow-sm">#{{ order.id }}</span>
                    </div>

                    <!-- Iterasi Detail (Multiple Items) -->
                    <div class="space-y-4 mb-6 relative z-10">
                        <div v-for="detail in order.details" :key="detail.id" class="flex justify-between items-start border-b border-gray-200/60 pb-4 last:border-0 last:pb-0">
                            <div>
                                <h4 class="font-bold text-gray-900 line-clamp-1 mb-1">{{ detail.tiket.event.nama_event }}</h4>
                                <p class="text-xs text-gray-500 mb-1">{{ detail.tiket.event.tanggal }} • {{ detail.tiket.event.venue.nama_venue }}</p>
                                <span class="inline-block bg-rose-50 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">{{ detail.tiket.nama_tiket }} x{{ detail.qty }}</span>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <span class="font-bold text-gray-900">Rp {{ Number(detail.subtotal).toLocaleString('id-ID') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 border-dashed pt-5 mt-2 relative z-10">
                        <div v-if="order.voucher" class="flex justify-between items-center mb-2">
                            <span class="text-sm font-bold text-gray-600 uppercase tracking-widest flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 3v18m0 0h-9a2.25 2.25 0 0 1-2.25-2.25V5.25A2.25 2.25 0 0 1 5.25 3h9m0 18h4.5A2.25 2.25 0 0 0 21 18.75V5.25A2.25 2.25 0 0 0 18.75 3h-4.5m-9 6h3m-3 4.5h3" /></svg>
                                Promo: {{ order.voucher.kode_voucher }}
                            </span>
                            <span class="text-sm font-bold text-green-500">-Rp {{ Number(order.voucher.potongan).toLocaleString('id-ID') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-bold text-gray-600 uppercase tracking-widest">Total Tagihan</span>
                            <span class="text-2xl font-black text-rose-600">Rp {{ Number(order.total).toLocaleString('id-ID') }}</span>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                <span class="font-black text-blue-800 tracking-tighter">BCA</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Transfer Rekening</p>
                                <p class="font-black text-gray-900 text-lg tracking-widest">1234 567 890</p>
                                <p class="text-xs font-semibold text-gray-500">a.n. TIXEVENT UKK</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="mb-8">
                        <label class="block text-sm font-bold uppercase tracking-wider text-gray-700 mb-3">Upload Bukti Transfer</label>
                        <div class="relative">
                            <input type="file" @change="e => form.bukti_transfer = e.target.files[0]" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition-all border-2 border-dashed border-gray-200 rounded-2xl p-2 cursor-pointer focus:outline-none focus:border-rose-400"
                                accept="image/*" required />
                        </div>
                        <p v-if="form.errors.bukti_transfer" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.bukti_transfer }}</p>
                    </div>

                    <!-- Tombol Konfirmasi Solid -->
                    <button type="button" @click="openConfirmModal" :disabled="!form.bukti_transfer" class="w-full cursor-pointer bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-6 rounded-xl transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Konfirmasi Pembayaran
                    </button>
                    
                    <!-- Hidden submit button triggered by script -->
                    <button type="submit" class="hidden" ref="submitBtn"></button>
                </form>

<div class="mt-6 text-center">
    <button 
        @click="cancelOrder" 
        :disabled="isCancelling" 
        class="text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-full transition-colors cursor-pointer inline-flex items-center gap-1 disabled:opacity-50"
    >
        <!-- SVG/Ikon X telah dihapus -->
        <span v-if="isCancelling">Membatalkan...</span>
        <span v-else>Batalkan Pesanan</span>
    </button>
</div>

            </div>
        </section>
        
        <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800 mt-auto">
            <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-rose-600 rounded-lg flex items-center justify-center text-white font-bold">T</div>
                        <span class="font-bold text-xl tracking-tight text-white">TIXEVENT</span>
                    </div>
                    <p class="text-sm text-gray-400 max-w-sm mb-6">
                        Platform penjualan tiket event No.1 untuk mendukung Ujian Kompetensi Keahlian (UKK) 2026. Aman, cepat, dan terpercaya.
                    </p>
                    <div class="flex gap-4 opacity-50 grayscale">
                        <span class="px-3 py-1 bg-white text-black text-xs font-bold rounded">BCA</span>
                        <span class="px-3 py-1 bg-white text-black text-xs font-bold rounded">MANDIRI</span>
                        <span class="px-3 py-1 bg-white text-black text-xs font-bold rounded">GOPAY</span>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="container mx-auto px-6 pt-8 mt-8 border-t border-gray-800 text-sm text-center md:text-left flex flex-col md:flex-row justify-between items-center">
                <p>&copy; 2026 TIXEVENT UKK. Hak Cipta Dilindungi.</p>
                <p class="mt-2 md:mt-0">Dibuat dengan Vue 3 & Laravel 12.</p>
            </div>
        </footer>
        
        <!-- Modal Konfirmasi Pembayaran -->
        <div v-if="isConfirmModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 flex flex-col items-center text-center animate-scale-in relative overflow-hidden">
                <template v-if="!paymentSuccess">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Pembayaran</h3>
                    <p class="text-gray-500 mb-8">Konfirmasi Pembayaran Anda sebesar <br/><strong class="text-2xl text-rose-600 font-black">Rp {{ Number(order.total).toLocaleString('id-ID') }}</strong></p>
                    
                    <!-- Swipe to Confirm Component inside Modal -->
                    <div class="relative w-full h-16 bg-gray-100 rounded-full overflow-hidden shadow-inner select-none touch-none" ref="track">
                        <!-- Background Text -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="text-sm font-bold uppercase tracking-widest text-gray-400" :class="{ 'opacity-0': isConfirmed }">
                                Geser untuk Bayar
                            </span>
                        </div>
                        
                        <!-- Progress Fill -->
                        <div class="absolute inset-y-0 left-0 bg-rose-500 transition-colors"
                             :class="{ 'bg-green-500': isConfirmed }"
                             :style="{ width: `calc(${currentX}px + 4rem)` }">
                             <div class="absolute inset-0 flex items-center justify-center pointer-events-none pr-16" v-if="isConfirmed">
                                 <span class="text-sm font-bold uppercase tracking-widest text-white">Memproses...</span>
                             </div>
                        </div>

                        <!-- Thumb -->
                        <div class="absolute top-1 bottom-1 left-1 w-14 rounded-full bg-white shadow-md flex items-center justify-center cursor-grab active:cursor-grabbing hover:scale-[1.02] transition-transform"
                             :class="{ 'cursor-default hover:scale-100': isConfirmed }"
                             :style="{ transform: `translateX(${currentX}px)`, transition: isDragging ? 'none' : 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)' }"
                             @mousedown="startDrag"
                             @touchstart="startDrag"
                             ref="thumb">
                            <svg v-if="form.processing" class="animate-spin h-6 w-6 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <svg v-else-if="!isConfirmed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 text-rose-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                    
                    <button @click="closeConfirmModal" class="mt-6 cursor-pointer text-sm font-bold text-gray-400 hover:text-gray-600 transition" :disabled="form.processing">Batal</button>
                </template>
                
                <template v-else>
                    <!-- E-Wallet Style Animation -->
                    <div class="relative flex items-center justify-center mb-8 mt-4">
                        <!-- Ripple Effects -->
                        <div class="absolute w-32 h-32 bg-green-400 rounded-full animate-[ping_2s_cubic-bezier(0,0,0.2,1)_infinite] opacity-20"></div>
                        <div class="absolute w-24 h-24 bg-green-300 rounded-full animate-pulse opacity-40"></div>
                        
                        <!-- Main Circle & Checkmark Container -->
                        <div class="relative w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-xl z-10 animate-bounce-in">
                            <svg class="w-16 h-16 text-green-500" viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg">
                                <circle class="animate-draw-circle" cx="26" cy="26" r="25" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="166" stroke-dashoffset="166" stroke-linecap="round"/>
                                <path class="animate-draw-path" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="48" stroke-dashoffset="48" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-gray-900 mb-2 opacity-0 animate-fade-in-up" style="animation-delay: 0.6s;">Pembayaran Berhasil!</h3>
                    <p class="text-gray-500 text-sm opacity-0 animate-fade-in-up" style="animation-delay: 0.8s;">Pembayaran Berhasil Dikirim! Petugas akan segera memverifikasi pesanan Anda.</p>
                </template>
            </div>
        </div>

        <!-- Intro Modal (Ticket Locked) -->
        <div v-if="isIntroModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 flex flex-col items-center text-center animate-scale-in relative overflow-hidden">
                <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-purple-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-3 leading-tight">Anda memiliki {{ Math.ceil(timeLeft / 60) }} menit untuk membayar</h3>
                <p class="text-gray-500 text-sm mb-8 font-medium">Harga dan kuota tiket Anda diamankan (locked) sementara selama waktu ini.</p>
                <button @click="isIntroModalOpen = false" class="w-full bg-[#5f27cd] hover:bg-[#341f97] text-white font-bold py-3.5 px-6 rounded-xl transition cursor-pointer shadow-lg shadow-purple-200">
                    Mulai Pembayaran
                </button>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes draw-circle {
    0% { stroke-dashoffset: 166; }
    100% { stroke-dashoffset: 0; }
}
@keyframes draw-path {
    0% { stroke-dashoffset: 48; }
    100% { stroke-dashoffset: 0; }
}
@keyframes bounce-in {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-draw-circle { animation: draw-circle 0.6s ease-out forwards; }
.animate-draw-path { animation: draw-path 0.4s ease-out 0.6s forwards; }
.animate-bounce-in { animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
.animate-fade-in-up { animation: fade-in-up 0.4s ease-out forwards; }
@keyframes scale-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
.animate-scale-in { animation: scale-in 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
</style>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    order: Object
});

const form = useForm({
    bukti_transfer: null,
});

const submitBtn = ref(null);
const isCancelling = ref(false);
const isConfirmModalOpen = ref(false);
const isIntroModalOpen = ref(true); // Modal start
const paymentSuccess = ref(false);

const timeLeft = ref(0);
let timerInterval = null;

const formatTime = (seconds) => {
    if (seconds <= 0) return "00:00";
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
};

const checkExpiration = () => {
    if (!props.order || !props.order.created_at) return;
    

    const createdAt = new Date(props.order.created_at).getTime();
    const expireTime = createdAt + (15 * 60 * 1000); 
    const now = new Date().getTime();
    
    const diff = Math.floor((expireTime - now) / 1000);
    
    if (diff <= 0) {
        timeLeft.value = 0;
        clearInterval(timerInterval);
        
        // Auto redirect & notifikasi
        alert("Waktu pembayaran telah habis. Pesanan Anda otomatis dibatalkan.");
        router.visit('/tiket-saya');
    } else {
        timeLeft.value = diff;
    }
};

const openConfirmModal = () => {
    if (!form.bukti_transfer) {
        alert("Harap unggah bukti transfer terlebih dahulu!");
        return;
    }
    isConfirmModalOpen.value = true;
};

const closeConfirmModal = () => {
    isConfirmModalOpen.value = false;
    resetSwipe();
};

const cancelOrder = () => {
    if (confirm("Yakin ingin membatalkan pesanan ini? Kuota tiket akan dikembalikan.")) {
        isCancelling.value = true;
        form.post(`/payment/${props.order.id}/cancel`, {
            onFinish: () => isCancelling.value = false
        });
    }
};

const submitForm = () => {
    form.post(`/payment/${props.order.id}/process`, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            paymentSuccess.value = true;
            setTimeout(() => {
                isConfirmModalOpen.value = false;
                router.visit('/tiket-saya');
            }, 3000);
        },
        onError: () => resetSwipe()
    });
};

// --- SWIPE TO CONFIRM LOGIC ---
const track = ref(null);
const thumb = ref(null);
const isDragging = ref(false);
const startX = ref(0);
const currentX = ref(0);
const maxTravel = ref(0);
const isConfirmed = ref(false);

const startDrag = (e) => {
    if (isConfirmed.value || form.processing) return;
    
    // Check if file is uploaded before allowing swipe
    if (!form.bukti_transfer) {
        alert("Pilih file bukti transfer terlebih dahulu sebelum menggeser.");
        return;
    }

    isDragging.value = true;
    startX.value = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
    // Calculate max travel distance (track width - thumb width - paddings)
    maxTravel.value = track.value.offsetWidth - thumb.value.offsetWidth - 8; 
};

const onDrag = (e) => {
    if (!isDragging.value || isConfirmed.value) return;
    const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
    let newX = clientX - startX.value;
    
    if (newX < 0) newX = 0;
    if (newX > maxTravel.value) newX = maxTravel.value;
    
    currentX.value = newX;
};

const stopDrag = () => {
    if (!isDragging.value || isConfirmed.value) return;
    isDragging.value = false;
    
    // threshold 90%
    if (currentX.value >= maxTravel.value * 0.9) {
        currentX.value = maxTravel.value;
        isConfirmed.value = true;
        submitForm();
    } else {
        // snap back
        currentX.value = 0;
    }
};

const resetSwipe = () => {
    isConfirmed.value = false;
    currentX.value = 0;
};

const handleBeforeUnload = (e) => {
    if (!paymentSuccess.value && !isCancelling.value) {
        e.preventDefault();
        e.returnValue = ''; // Memicu konfirmasi bawaan browser
    }
};

onMounted(() => {

    if (['waiting', 'waiting_confirmation'].includes(props.order.status) && !paymentSuccess.value) {
        router.visit('/tiket-saya');
        return;
    }

    window.addEventListener('beforeunload', handleBeforeUnload);

    window.addEventListener('mousemove', onDrag);
    window.addEventListener('mouseup', stopDrag);
    window.addEventListener('touchmove', onDrag, { passive: false });
    window.addEventListener('touchend', stopDrag);

    // Mulai Countdown Timer
    checkExpiration();
    if (timeLeft.value > 0) {
        timerInterval = setInterval(checkExpiration, 1000);
    }
});

onUnmounted(() => {
    window.removeEventListener('mousemove', onDrag);
    window.removeEventListener('mouseup', stopDrag);
    window.removeEventListener('touchmove', onDrag);
    window.removeEventListener('touchend', stopDrag);
    window.removeEventListener('beforeunload', handleBeforeUnload);
    
    if (timerInterval) clearInterval(timerInterval);
});
</script>
