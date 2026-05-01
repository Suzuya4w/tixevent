<template>
    <MainLayout>
        <Head :title="`${event.nama_event} - TIXEVENT`" />

        <!-- Hero Section -->
        <section class="relative w-full h-[55vh] md:h-[65vh] bg-gray-900 overflow-hidden md:rounded-b-[3rem] shadow-2xl z-10">
            <img :src="event.gambar ? (event.gambar.startsWith('http') ? event.gambar : `/storage/${event.gambar}`) : 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&q=80&w=1920'" 
                 :alt="event.nama_event"
                 class="w-full h-full object-cover opacity-50 scale-105" />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-950/80 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full">
                <div class="container mx-auto px-6 pb-16 md:pb-24">
                    <div class="max-w-4xl animate-slide-up">
                        <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 border border-white/20 shadow-lg">
                            {{ event.category?.nama_kategori || 'Event' }}
                        </span>
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tighter mb-6 leading-tight drop-shadow-lg">{{ event.nama_event }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm md:text-base font-medium">
                            <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                {{ event.tanggal || 'Belum ditentukan' }}
                            </span>
                            <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                {{ event.venue?.nama_venue || 'Lokasi Belum Ditentukan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="container mx-auto px-6 flex-grow relative z-20 -mt-8 md:-mt-12 mb-20">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                <!-- Left Column -->
                <div class="lg:w-2/3 space-y-8">
                    <!-- Informasi Box -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden group hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-shadow duration-300">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-rose-50 rounded-full blur-3xl opacity-50 z-0"></div>
                        <h2 class="text-2xl font-black text-gray-900 mb-8 relative z-10 flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-rose-600 rounded-full"></span>
                            Informasi Acara
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                            <!-- Tanggal -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tanggal</p>
                                    <p class="text-gray-900 font-bold">{{ event.tanggal || 'Belum ditentukan' }}</p>
                                </div>
                            </div>

                            <!-- Waktu -->
                            <div class="flex items-start gap-4" v-if="event.waktu">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Waktu</p>
                                    <p class="text-gray-900 font-bold">{{ event.waktu.substring(0, 5) }} <template v-if="event.waktu_selesai">- {{ event.waktu_selesai.substring(0, 5) }}</template> WIB</p>
                                </div>
                            </div>
                            
                            <!-- Lokasi -->
                            <div class="flex items-start gap-4 md:col-span-2">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Lokasi</p>
                                    <p class="text-gray-900 font-bold">{{ event.venue?.nama_venue || 'Lokasi Belum Ditentukan' }}</p>
                                    <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">{{ event.venue?.alamat || '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Box -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-shadow duration-300">
                        <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                            Deskripsi Acara
                        </h2>
                        <div class="prose max-w-none text-gray-600 leading-relaxed text-sm md:text-base">
                            <div v-if="event.deskripsi" v-html="event.deskripsi" class="whitespace-pre-wrap"></div>
                            <p v-else class="whitespace-pre-wrap italic">Tidak ada deskripsi untuk event ini.</p>
                        </div>
                    </div>

                    <!-- SnK Box -->
                    <div class="bg-gray-50 rounded-3xl border border-gray-100 p-8 md:p-10">
                        <h3 class="text-lg font-black text-gray-900 mb-6">Syarat & Ketentuan</h3>
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex gap-3 items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400 shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span class="leading-relaxed">Tiket yang sudah dibeli tidak dapat dikembalikan (Non-refundable) dengan alasan apapun kecuali acara dibatalkan oleh penyelenggara.</span>
                            </li>
                            <li class="flex gap-3 items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400 shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" /></svg>
                                <span class="leading-relaxed">Pembeli wajib menunjukkan e-tiket (QR Code) dari website dan identitas valid saat check-in.</span>
                            </li>
                            <li class="flex gap-3 items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-400 shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                <span class="leading-relaxed">Panitia berhak menolak masuk jika tiket terindikasi palsu, duplikat, atau tidak sesuai dengan identitas pemilik.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Column: Sticky Checkout -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-6 md:p-8 sticky top-28 z-20">
                        <h3 class="text-xl font-black text-gray-900 mb-6">Pilih Tiket</h3>
                        
                        <div v-if="tikets.length === 0" class="bg-gray-50 text-center p-8 rounded-2xl border border-gray-100">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                            </div>
                            <p class="text-gray-500 font-medium">Tiket belum tersedia untuk event ini.</p>
                        </div>
                        
                        <div v-else class="space-y-4 mb-8">
                            <label v-for="tiket in tikets" :key="tiket.id" 
                                   class="relative flex flex-col p-5 cursor-pointer rounded-2xl border-2 transition-all duration-300 overflow-hidden group"
                                   :class="[
                                       selectedTiketId === tiket.id ? 'border-rose-500 bg-rose-50/30 shadow-md shadow-rose-100' : 'border-gray-100 bg-white hover:border-gray-300 hover:shadow-sm',
                                       (tiket.sisa !== undefined ? tiket.sisa : tiket.kuota) === 0 ? 'opacity-60 grayscale cursor-not-allowed' : ''
                                   ]">
                                <input type="radio" :value="tiket.id" v-model="selectedTiketId" class="sr-only" :disabled="(tiket.sisa !== undefined ? tiket.sisa : tiket.kuota) === 0" />
                                
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-gray-900" :class="selectedTiketId === tiket.id ? 'text-rose-700' : ''">{{ tiket.nama_tiket }}</span>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors mt-0.5"
                                         :class="selectedTiketId === tiket.id ? 'border-rose-600' : 'border-gray-300'">
                                         <div v-if="selectedTiketId === tiket.id" class="w-2.5 h-2.5 bg-rose-600 rounded-full animate-scale-in"></div>
                                    </div>
                                </div>
                                <span class="font-black text-lg text-gray-900 mb-3">Rp {{ Number(tiket.harga).toLocaleString('id-ID') }}</span>

                                <div v-if="tiket.terjual !== undefined" class="mt-auto">
                                    <div class="flex justify-between text-[10px] text-gray-500 mb-1.5 font-extrabold uppercase tracking-wider">
                                        <span>Terjual {{ tiket.terjual }}</span>
                                        <span :class="{'text-rose-600': tiket.sisa <= 10}">Sisa {{ tiket.sisa }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-rose-500 h-full transition-all duration-1000 ease-out" 
                                             :style="{ width: ((tiket.terjual / tiket.kuota) * 100) + '%' }">
                                        </div>
                                    </div>
                                    <p v-if="tiket.sisa > 0 && tiket.sisa <= 10" class="text-rose-600 text-[10px] mt-2 font-bold flex items-center gap-1 animate-pulse">
                                        🔥 Stok hampir habis!
                                    </p>
                                </div>
                                
                                <div v-if="(tiket.sisa !== undefined ? tiket.sisa : tiket.kuota) === 0" class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center z-10">
                                     <span class="bg-gray-900 text-white text-xs font-black uppercase tracking-widest px-4 py-2 rounded-lg shadow-xl rotate-[-5deg]">HABIS TERJUAL</span>
                                </div>
                            </label>
                        </div>

                        <!-- Quantity & Promo -->
                        <div v-if="tikets.length > 0">
                            <div class="flex justify-between items-center mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <span class="font-bold text-gray-700 text-sm uppercase tracking-wider">Jumlah Tiket</span>
                                <div class="flex items-center bg-white rounded-xl border border-gray-200 p-1 shadow-sm">
                                    <button @click="quantity > 1 && quantity--" type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition cursor-pointer font-bold">-</button>
                                    <span class="w-10 text-center font-black text-gray-900">{{ quantity }}</span>
                                    <button @click="canIncreaseQuantity && quantity++" type="button" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg transition font-bold"
                                            :class="canIncreaseQuantity ? 'text-gray-500 hover:text-gray-900 hover:bg-gray-100 cursor-pointer' : 'text-gray-300 cursor-not-allowed'">+</button>
                                </div>
                            </div>
                            
                            <!-- Promo Selection -->
                            <div class="mb-6">
                                <label class="block text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Makin Hemat Pakai Promo</label>
                                <button @click="isPromoModalOpen = true" type="button" 
                                    class="w-full flex items-center justify-between bg-rose-50/50 border border-rose-200/50 p-4 rounded-2xl hover:bg-rose-50 transition cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-rose-100 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-rose-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 3v18m0 0h-9a2.25 2.25 0 0 1-2.25-2.25V5.25A2.25 2.25 0 0 1 5.25 3h9m0 18h4.5A2.25 2.25 0 0 0 21 18.75V5.25A2.25 2.25 0 0 0 18.75 3h-4.5m-9 6h3m-3 4.5h3" />
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <span v-if="selectedVoucher" class="block font-black text-gray-900">{{ selectedVoucher.kode_voucher }}</span>
                                            <span v-if="selectedVoucher" class="block text-[11px] text-rose-600 font-bold mt-0.5">Diskon Rp {{ Number(selectedVoucher.potongan).toLocaleString('id-ID') }} terpakai!</span>
                                            <span v-else class="block font-bold text-gray-700 text-sm">Pakai Kode Promo</span>
                                        </div>
                                    </div>
                                    <span class="text-rose-600 font-bold text-sm bg-white px-3 py-1.5 rounded-lg border border-rose-100 shadow-sm">
                                        <span v-if="selectedVoucher">Ganti</span>
                                        <span v-else>Pilih</span>
                                    </span>
                                </button>
                            </div>

                            <!-- Info Batas Pembelian -->
                            <div v-if="selectedTiket && selectedTiket.sudah_dibeli > 0" class="mb-6 p-4 rounded-2xl border flex gap-3 text-sm transition-all"
                                 :class="selectedTiket.sudah_dibeli >= selectedTiket.max_beli ? 'bg-red-50 border-red-100 text-red-700' : 'bg-blue-50 border-blue-100 text-blue-700'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 shrink-0 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                                <div>
                                    <strong class="block mb-1 font-bold">Informasi Batas Pembelian</strong>
                                    <span v-if="selectedTiket.sudah_dibeli >= selectedTiket.max_beli" class="leading-relaxed">
                                        Tidak bisa menambah pesanan. Akun kamu sudah mencapai batas maksimal kepemilikan ({{ selectedTiket.max_beli }} tiket) untuk kategori ini.
                                    </span>
                                    <span v-else class="leading-relaxed">
                                        Kamu sudah memiliki <strong>{{ selectedTiket.sudah_dibeli }}</strong> tiket. Sisa kuota yang bisa kamu beli tinggal <strong>{{ selectedTiket.max_beli - selectedTiket.sudah_dibeli }}</strong> tiket lagi.
                                    </span>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="border-t-2 border-dashed border-gray-100 pt-6 mb-8 space-y-3">
                                <h4 class="font-extrabold text-[11px] text-gray-400 uppercase tracking-widest mb-4">Ringkasan Pesanan</h4>
                                <div class="flex justify-between text-sm text-gray-600 font-medium">
                                    <span>{{ selectedTiket?.nama_tiket || 'Tiket' }} <span class="text-gray-400">x{{ quantity }}</span></span>
                                    <span class="font-bold text-gray-900">Rp {{ originalSubtotal.toLocaleString('id-ID') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-emerald-600 font-bold" v-if="discount > 0">
                                    <span>Potongan Promo</span>
                                    <span>- Rp {{ discount.toLocaleString('id-ID') }}</span>
                                </div>
                                <div class="flex justify-between items-end pt-4 mt-2 border-t border-gray-50">
                                    <span class="text-gray-500 font-bold text-sm">Total Harga</span>
                                    <span class="text-3xl font-black text-rose-600 tracking-tight">Rp {{ finalTotal.toLocaleString('id-ID') }}</span>
                                </div>
                            </div>

                            <button @click="submitCheckout" 
                                    :disabled="form.processing || !selectedTiket || (selectedTiket.sisa !== undefined ? selectedTiket.sisa : selectedTiket.kuota) < quantity || (selectedTiket.sudah_dibeli >= selectedTiket.max_beli)"
                                    class="w-full cursor-pointer py-4 px-6 rounded-2xl text-white font-bold text-lg shadow-xl shadow-rose-600/20 transition-all duration-300 flex items-center justify-center hover:-translate-y-1"
                                    :class="(!selectedTiket || selectedTiket.kuota < quantity || (selectedTiket.sudah_dibeli >= selectedTiket.max_beli)) ? 'bg-gray-400 cursor-not-allowed shadow-none hover:translate-y-0' : 'bg-rose-600 hover:bg-rose-700 hover:shadow-rose-600/40'">
                                <span v-if="form.processing" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memproses...
                                </span>
                                <span v-else>Beli Tiket Sekarang</span>
                            </button>
                            <p v-if="form.errors.qty || form.errors.id_tiket || form.errors.voucher_id" class="text-red-500 text-sm mt-4 text-center bg-red-50 p-3 rounded-xl font-medium border border-red-100">
                                {{ form.errors.qty || form.errors.id_tiket || form.errors.voucher_id }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Promo Modal -->
        <Teleport to="body">
            <div v-if="isPromoModalOpen" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="isPromoModalOpen = false">
            <div class="bg-white w-full max-w-md md:rounded-3xl rounded-t-3xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] animate-slide-up md:animate-scale-in">
                
                <div class="p-5 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                    <h3 class="font-black text-gray-900 text-lg">Pilih Promo</h3>
                    <button @click="isPromoModalOpen = false" class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-500 rounded-full hover:bg-gray-200 transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-5 overflow-y-auto flex-grow bg-slate-50 space-y-5">
                    <div class="bg-white p-2 rounded-2xl border border-gray-200 shadow-sm flex gap-2">
                        <input type="text" v-model="manualPromoCode" placeholder="Punya kode promo lain?" class="flex-grow border-0 bg-transparent focus:ring-0 rounded-xl text-sm font-bold uppercase tracking-wider px-4 outline-none" />
                        <button @click="applyManualPromo" :disabled="!manualPromoCode" class="bg-gray-900 cursor-pointer text-white px-5 py-2.5 rounded-xl text-sm font-bold disabled:bg-gray-300 transition hover:bg-black">Terapkan</button>
                    </div>

                    <div v-if="manualPromoError" class="text-xs text-red-500 font-bold px-2">{{ manualPromoError }}</div>

                    <h4 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest pt-2">Promo Tersedia</h4>
                    
                    <div v-if="vouchers && vouchers.length > 0" class="space-y-4">
                        <div v-for="v in vouchers" :key="v.id" 
                             @click="originalSubtotal > v.potongan ? selectPromo(v.id) : null"
                             class="bg-white rounded-2xl border-2 p-5 shadow-sm relative overflow-hidden flex flex-col gap-2 transition-all"
                             :class="originalSubtotal > v.potongan ? 'border-gray-100 hover:border-rose-200 cursor-pointer group' : 'border-gray-100 opacity-50 cursor-not-allowed'">
                            
                            <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-50 rounded-full border-r-2" :class="originalSubtotal > v.potongan ? 'border-gray-100 group-hover:border-rose-200' : 'border-gray-100'"></div>
                            <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-50 rounded-full border-l-2" :class="originalSubtotal > v.potongan ? 'border-gray-100 group-hover:border-rose-200' : 'border-gray-100'"></div>
                            
                            <div class="flex justify-between items-center px-4">
                                <div>
                                    <span class="inline-block text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded bg-rose-50 text-rose-600 mb-2">{{ v.kode_voucher }}</span>
                                    <h4 class="font-black text-gray-900 text-sm">Diskon Rp {{ Number(v.potongan).toLocaleString('id-ID') }}</h4>
                                    <p v-if="originalSubtotal <= v.potongan" class="text-[10px] text-red-500 font-bold mt-1">Min. belanja > Rp {{ Number(v.potongan).toLocaleString('id-ID') }}</p>
                                </div>
                                
                                <button v-if="selectedVoucherId !== v.id" @click.stop="originalSubtotal > v.potongan ? selectPromo(v.id) : null" 
                                        class="text-xs font-bold px-4 py-2 rounded-xl transition-colors"
                                        :class="originalSubtotal > v.potongan ? 'text-rose-600 bg-rose-50 group-hover:bg-rose-100 cursor-pointer' : 'text-gray-400 bg-gray-100 cursor-not-allowed'">Pakai</button>
                                <button v-else @click.stop="selectPromo(null)" class="text-xs font-bold text-gray-600 bg-gray-100 px-4 py-2 rounded-xl hover:bg-gray-200 transition-colors cursor-pointer border border-gray-200">Batal</button>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-10 bg-white rounded-2xl border border-gray-100">
                        <p class="text-gray-400 text-sm font-medium">Belum ada promo yang tersedia saat ini.</p>
                    </div>
                </div>
            </div>
            </div>
        </Teleport>
    </MainLayout>
</template>

<style>
@keyframes slide-up { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes scale-in { from { opacity: 0; transform: scale(0.90); } to { opacity: 1; transform: scale(1); } }
.animate-slide-up { animation: slide-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-scale-in { animation: scale-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';

const page = usePage();

const props = defineProps({
    event: Object,
    tikets: Array,
    vouchers: Array
});

const isPromoModalOpen = ref(false);
const manualPromoCode = ref('');
const manualPromoError = ref('');

const selectedTiketId = ref(props.tikets.length > 0 ? props.tikets[0].id : null);
const quantity = ref(1);
const selectedVoucherId = ref(null);

const selectedTiket = computed(() => {
    return props.tikets.find(t => t.id === selectedTiketId.value);
});

const canIncreaseQuantity = computed(() => {
    if (!selectedTiket.value) return false;
    
    const sisaReal = selectedTiket.value.sisa !== undefined ? selectedTiket.value.sisa : selectedTiket.value.kuota;
    const sudahDibeli = selectedTiket.value.sudah_dibeli || 0;
    const maxLimit = selectedTiket.value.max_beli || 5; 
    const sisaBolehBeli = maxLimit - sudahDibeli;
    
    return quantity.value < sisaReal && quantity.value < sisaBolehBeli;
});

const originalSubtotal = computed(() => {
    if (!selectedTiket.value) return 0;
    return selectedTiket.value.harga * quantity.value;
});

const selectedVoucher = computed(() => {
    if (!selectedVoucherId.value || !props.vouchers) return null;
    return props.vouchers.find(v => v.id === selectedVoucherId.value);
});

const discount = computed(() => {
    if (!selectedVoucher.value) return 0;
    return Number(selectedVoucher.value.potongan);
});

const finalTotal = computed(() => {
    const total = originalSubtotal.value - discount.value;
    return total > 0 ? total : 0;
});

const selectPromo = (id) => {
    if (id !== null) {
        const voucher = props.vouchers.find(v => v.id === id);
        if (voucher && originalSubtotal.value <= voucher.potongan) {
            manualPromoError.value = "Total belanja harus lebih besar dari potongan voucher.";
            return;
        }
    }

    selectedVoucherId.value = id;
    if (id !== null) {
        isPromoModalOpen.value = false;
        manualPromoError.value = '';
        manualPromoCode.value = '';
    }
};

const applyManualPromo = () => {
    manualPromoError.value = '';
    const code = manualPromoCode.value.trim().toUpperCase();
    
    if (!props.vouchers || props.vouchers.length === 0) {
        manualPromoError.value = "Kode promo tidak valid atau kuota habis.";
        return;
    }

    const found = props.vouchers.find(v => v.kode_voucher.toUpperCase() === code);
    if (found) {
        if (originalSubtotal.value <= found.potongan) {
            manualPromoError.value = "Min. belanja > Rp " + Number(found.potongan).toLocaleString('id-ID');
        } else {
            selectPromo(found.id);
        }
    } else {
        manualPromoError.value = "Kode promo tidak valid atau kuota habis.";
    }
};


watch(originalSubtotal, (newSubtotal) => {
    if (selectedVoucher.value && newSubtotal <= selectedVoucher.value.potongan) {
        selectedVoucherId.value = null;
    }
});

const form = useForm({
    id_tiket: '',
    qty: 1,
    voucher_id: null
});

const submitCheckout = () => {
    if (!page.props.auth?.user) {
        router.visit('/login?redirect=' + encodeURIComponent(window.location.pathname));
        return;
    }
    
    if (!selectedTiket.value) return;
    form.id_tiket = selectedTiketId.value;
    form.qty = quantity.value;
    form.voucher_id = selectedVoucherId.value;
    
    form.post('/checkout');
};
</script>