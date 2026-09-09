<div x-data="{
    scanner: null,
    dispatchTo: '{{ $dispatchTo }}',
    isScanning: false,

    init() {
        this.startScanner();
    },

    startScanner() {
        if (this.isScanning) return;

        setTimeout(() => {
            if (typeof Html5Qrcode === 'undefined') {
                let script = document.createElement('script');
                script.src = 'https://unpkg.com/html5-qrcode';
                script.onload = () => this.initScanner();
                document.head.appendChild(script);
            } else {
                this.initScanner();
            }
        }, 150);
    },

    initScanner() {
        if (this.scanner) return;

        this.scanner = new Html5Qrcode('qr-reader-{{ $componentId = md5(uniqid()) }}');

        this.scanner.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                try {
                    let url = new URL(decodedText);
                    window.location.href = url.href;
                    this.stopScanner();
                    return;
                } catch (_) {}

                if (window.Livewire) {
                    Livewire.dispatch(this.dispatchTo, { code: decodedText });
                }
                window.dispatchEvent(new CustomEvent('qr-scanned', { detail: decodedText }));
            },
            (error) => {}
        ).then(() => {
            this.isScanning = true;
        }).catch((err) => {
            console.error('Camera access error: ', err);
        });
    },

    stopScanner() {
        if (this.scanner && this.isScanning) {
            this.scanner.stop().then(() => {
                this.scanner.clear();
                this.scanner = null;
                this.isScanning = false;
            }).catch(error => {
                console.error('Failed to stop scanner.', error);
            });
        }
    }
}"
     x-on:open-scanner.window="startScanner()"
     x-on:close-scanner.window="stopScanner()"
     class="w-full">
    <div id="qr-reader-{{ $componentId }}" class="w-full rounded-xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800"></div>
</div>
