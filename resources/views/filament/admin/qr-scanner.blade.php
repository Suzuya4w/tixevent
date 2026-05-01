<div 
    x-data="{
        scannedCode: null,
        isProcessing: false,
        scanner: null,
        init() {
            // 1. Suntikkan library HTML5-QRCode secara dinamis ke head browser
            if (typeof Html5QrcodeScanner === 'undefined') {
                let script = document.createElement('script');
                script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
                script.onload = () => this.startScanner();
                document.head.appendChild(script);
            } else {
                this.startScanner();
            }
        },
        startScanner() {
            // 2. Beri jeda 100ms agar div #reader benar-benar di-render oleh Livewire
            setTimeout(() => {
                this.scanner = new Html5QrcodeScanner(
                    'reader',
                    { fps: 10, qrbox: { width: 250, height: 250 }, rememberLastUsedCamera: true },
                    false
                );

                this.scanner.render(
                    (decodedText) => {
                        if (this.isProcessing) return;
                        this.isProcessing = true;
                        this.scannedCode = decodedText;

                        // 3. Tembak data ke fungsi validateScannedTicket di PHP
                        $wire.dispatch('processTicketScan', { kode: decodedText });

                        // 4. Jeda kamera 3 detik agar tidak scan berkali-kali
                        this.scanner.pause(true);
                        setTimeout(() => {
                            this.isProcessing = false;
                            try { this.scanner.resume(); } catch(e){}
                        }, 3000);
                    },
                    (error) => { /* Abaikan error pencarian */ }
                );
            }, 100);
        }
    }"
    class="w-full"
>
    <div id="reader" style="width: 100%; min-height: 250px; border: 2px dashed #cbd5e1; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; overflow: hidden;">
        <span style="color: #64748b; font-size: 0.875rem;">Memuat kamera... (Pastikan allow access)</span>
    </div>

    <div x-show="scannedCode" style="display: none; margin-top: 1rem; padding: 0.75rem; border-radius: 0.5rem; background: #f1f5f9;" :style="scannedCode ? 'display: block;' : 'display: none;'">
        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Terakhir discan:</p>
        <p x-text="scannedCode" style="font-weight: 700; color: #0f172a; margin: 0.25rem 0 0; word-break: break-all;"></p>
    </div>
</div>