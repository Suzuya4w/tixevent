<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen text-gray-900 font-sans flex flex-col relative bg-slate-50/50">

        
        <nav class="border-b border-gray-100/50 sticky top-0 z-50 bg-white/80 backdrop-blur-xl">
            <div class="container mx-auto px-6 h-20 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 bg-rose-600 rounded-lg flex items-center justify-center text-white font-bold">T</div>
                    <span class="font-bold text-xl tracking-tight">TIXEVENT</span>
                </Link>
                <div class="hidden md:flex gap-6 font-medium text-sm text-gray-500">
                    <Link href="/" class="text-gray-900 font-semibold hover:text-rose-600 transition-colors">Beli Tiket</Link>
                </div>
                <div class="flex items-center gap-4" v-if="$page.props.auth?.user">
                    <span class="hidden md:inline text-sm font-semibold text-gray-700">Hai, {{ $page.props.auth.user.name }}</span>
                    <Link href="/tiket-saya" class="bg-rose-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-rose-700 transition shadow-md shadow-rose-200">Tiket Saya</Link>
                    <Link href="/logout" method="post" as="button" class="text-sm cursor-pointer font-semibold text-red-600 hover:underline">Logout</Link>
                </div>
                <div class="flex items-center gap-4" v-else>
                    <Link href="/login" class="text-sm font-semibold hover:underline">Log in</Link>
                    <Link href="/register" class="bg-rose-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-rose-700 transition shadow-md shadow-rose-200">Sign Up</Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col relative z-10 w-full">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white pt-20 pb-10 border-t border-gray-200 mt-auto z-10 relative">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
                    <div class="lg:col-span-4">
                        <Link href="/" class="flex items-center gap-2 mb-6 hover:opacity-80 transition-opacity inline-flex">
                            <div class="w-10 h-10 bg-rose-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-rose-200">
                                T
                            </div>
                            <span class="font-black text-2xl tracking-tight text-gray-900">TIXEVENT</span>
                        </Link>
                        <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-sm">
                            Platform manajemen tiket terpercaya untuk mempermudah penjualan dan pembelian tiket event Anda. Didukung teknologi modern untuk pengalaman yang mulus.
                        </p>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                            </a>
                            <a href="https://github.com/Suzuya4w/tixevent" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:border-gray-400 hover:bg-gray-100 transition-all" title="View Source on GitHub">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-2 md:col-span-1">
                        <h4 class="text-gray-900 font-bold mb-6">Navigasi</h4>
                        <ul class="space-y-4">
                            <li><a href="/" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Beranda</a></li>
                            <li><a href="/events" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Cari Event</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Cara Beli</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Promo</a></li>
                        </ul>
                    </div>

                    <div class="lg:col-span-3 md:col-span-1">
                        <h4 class="text-gray-900 font-bold mb-6">Dukungan</h4>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Pusat Bantuan</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Kebijakan Privasi</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-rose-600 transition-colors">Hubungi Kami</a></li>
                        </ul>
                    </div>

                    <div class="lg:col-span-3">
                        <h4 class="text-gray-900 font-bold mb-6">Pembayaran</h4>
                        <div class="flex flex-wrap gap-3 mb-6">
                            <div class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-gray-50">BCA</div>
                            <div class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-gray-50">MANDIRI</div>
                            <div class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-gray-50">BNI</div>
                            <div class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-gray-50">GOPAY</div>
                            <div class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-gray-50">OVO</div>
                        </div>
                    </div>
                </div>

<div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="flex items-center gap-2">
        <p class="text-sm text-gray-400">&copy; 2026 TIXEVENT.</p>
        <span class="text-gray-300 hidden md:inline">•</span>
        <a href="https://github.com/Suzuya4w/tixevent" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-gray-400 hover:text-gray-900 flex items-center gap-1.5 transition-colors group">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-900 transition-colors" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
            Open Source on GitHub
        </a>
    </div>
    
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <span>Dibuat dengan</span>
        <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
        <span>di Indonesia</span>
    </div>
</div>
            </div>
        </footer>
    </div>
</template>