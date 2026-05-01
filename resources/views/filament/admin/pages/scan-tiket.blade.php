<x-filament-panels::page>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>

    <div style="margin-bottom: 1.5rem;">
        {{ $this->form }}
    </div>

    <div 
        x-data="{
            code: '',
            status: 'idle', 
            message: '',
            title: '',
            titleColor: '',
            
            // Fungsi ini akan otomatis dipanggil saat halaman dibuka
            initScanner() {
                const html5QrCode = new Html5Qrcode('reader');
                html5QrCode.start(
                    { facingMode: 'environment' }, // Paksa pakai kamera belakang
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => {
                        html5QrCode.pause(); // Jeda kamera
                        this.processTicket(decodedText, html5QrCode);
                    }
                ).catch(err => {
                    document.getElementById('reader').innerHTML = '<div style=\'padding:2rem;text-align:center;color:red;\'><b>Akses Kamera Ditolak!</b><br>Izinkan browser mengakses kamera, lalu muat ulang halaman.</div>';
                });
            },

            // Fungsi untuk verifikasi ke server
            processTicket(scannedCode, scannerInstance = null) {
                // Ambil Event ID langsung dari state Livewire!
                let eventId = $wire.get('data.event_id');

                if(!eventId) {
                    alert('Tolong pilih Event di atas terlebih dahulu!');
                    if(scannerInstance) scannerInstance.resume();
                    return;
                }

                this.status = 'loading';
                this.code = scannedCode;

                fetch('/api/verify-tiket', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kode_tiket: scannedCode,
                        event_id: eventId // Kirim event yang dipilih
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.status = 'result';
                    if (data.status === 'success') {
                        this.title = 'BERHASIL MASUK';
                        this.titleColor = '#16a34a'; // Hijau
                        this.message = data.message;
                    } else {
                        this.title = 'DITOLAK / PALSU';
                        this.titleColor = '#dc2626'; // Merah
                        this.message = data.message;
                    }

                    // Kembali ke status awal setelah 3 detik
                    setTimeout(() => {
                        this.status = 'idle';
                        if(scannerInstance) scannerInstance.resume();
                    }, 3000);
                }).catch(err => {
                    this.status = 'result';
                    this.title = 'JARINGAN ERROR';
                    this.titleColor = '#dc2626';
                    this.message = 'Gagal menghubungi server.';
                    setTimeout(() => {
                        this.status = 'idle';
                        if(scannerInstance) scannerInstance.resume();
                    }, 3000);
                });
            }
        }"
        x-init="initScanner()"
    >
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            
            <x-filament::section heading="Kamera Scanner">
                <div wire:ignore>
                    <div id="reader" style="width: 100%; min-height: 300px; border-radius: 0.5rem; border: 2px dashed #a1a1aa; background-color: #18181b;"></div>
                </div>
            </x-filament::section>

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <x-filament::section heading="Monitor Layar">
                    
                    <div x-show="status === 'idle'" style="text-align: center; padding: 2rem 0; color: #a1a1aa;">
                        <p>Arahkan Kode QR Tiket ke Kamera...</p>
                    </div>

                    <div x-show="status === 'loading'" style="text-align: center; padding: 2rem 0; color: #d97706;">
                        <h3 style="font-size: 1.25rem; font-weight: bold;">Mengecek...</h3>
                    </div>

                    <div x-show="status === 'result'" style="text-align: center; padding: 1rem 0;">
                        <h3 x-text="title" :style="'font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; color: ' + titleColor"></h3>
                        <p x-text="message" style="margin-bottom: 1rem;"></p>
                        <code x-text="code" style="background-color: #27272a; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;"></code>
                    </div>

                </x-filament::section>

                <x-filament::section heading="Input Manual">
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <div style="flex-grow: 1;">
                            <x-filament::input.wrapper>
                                <x-filament::input type="text" x-model="code" placeholder="Ketik Kode..." />
                            </x-filament::input.wrapper>
                        </div>
                        <x-filament::button type="button" @click="processTicket(code)" color="primary">
                            Cek
                        </x-filament::button>
                    </div>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>