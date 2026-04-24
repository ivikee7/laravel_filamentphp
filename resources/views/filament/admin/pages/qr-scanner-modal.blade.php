<div x-data="qrScannerModal()" x-init="init()" class="space-y-4 p-2">
    <div
        x-ref="reader"
        id="qr-reader-modal"
        wire:ignore
        class="overflow-hidden rounded-lg border-2 border-gray-200"
        style="width: 100%; min-height: 280px;"
    ></div>
    <p class="text-center text-sm text-gray-600">
        Allow camera access and position the QR code inside the frame.
    </p>
    <div x-show="isLoadingLibrary" x-cloak class="text-center text-sm text-gray-500">
        Loading scanner library...
    </div>
    <div x-show="!isLoadingLibrary && !isScanning && !scanError && !lastScannedUrl" x-cloak class="text-center text-sm text-gray-500">
        Starting camera...
    </div>
    <div x-show="lastScannedUrl" x-cloak class="rounded-lg border border-green-200 bg-green-50 p-4">
        <p class="text-sm font-medium text-green-900">QR Code detected — redirecting…</p>
        <p class="mt-1 break-all text-xs text-green-700" x-text="lastScannedUrl"></p>
    </div>
    <div x-show="scanError" x-cloak class="rounded-lg border border-red-200 bg-red-50 p-4">
        <p class="text-sm font-medium text-red-900">Error</p>
        <p class="mt-1 text-xs text-red-700" x-text="scanError"></p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button
            type="button"
            @click="startScanning()"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        >
            Open Camera
        </button>

        <button
            type="button"
            @click="restartScanner()"
            class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        >
            Restart Camera
        </button>
    </div>
</div>

<script>
    window.qrScannerModal = window.qrScannerModal || function () {
        return {
            scanner: null,
            isScanning: false,
            isLoadingLibrary: false,
            lastScannedUrl: '',
            scanError: '',
            readerId: 'qr-reader-modal',

            async init() {
                setTimeout(() => this.startScanning(), 150)
            },

            async loadLibrary() {
                if (window.Html5QrcodeScanner) {
                    return
                }

                if (! window.qrCodeScannerLibraryPromise) {
                    window.qrCodeScannerLibraryPromise = new Promise((resolve, reject) => {
                        const script = document.createElement('script')
                        script.src = 'https://unpkg.com/html5-qrcode@2.3.7/dist/html5-qrcode.min.js'
                        script.onload = resolve
                        script.onerror = () => reject(new Error('Unable to load QR scanner library.'))
                        document.head.appendChild(script)
                    })
                }

                this.isLoadingLibrary = true

                try {
                    await window.qrCodeScannerLibraryPromise
                } catch (error) {
                    this.scanError = error.message
                } finally {
                    this.isLoadingLibrary = false
                }
            },

            async startScanning() {
                if (this.isScanning || this.scanner) {
                    return
                }

                this.scanError = ''
                this.lastScannedUrl = ''

                await this.$nextTick()
                await this.loadLibrary()

                if (! window.Html5QrcodeScanner) {
                    this.scanError = this.scanError || 'QR scanner library did not load.'
                    return
                }

                const reader = this.$refs.reader

                if (! reader) {
                    this.scanError = 'Scanner element not found.'
                    return
                }

                reader.innerHTML = ''

                try {
                    this.scanner = new Html5QrcodeScanner(
                        this.readerId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            aspectRatio: 1.77778,
                            rememberLastUsedCamera: true,
                            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
                            videoConstraints: { facingMode: 'environment' },
                            showTorchButtonIfSupported: true,
                        },
                        false,
                    )

                    this.scanner.render(
                        (decodedText) => this.onScanSuccess(decodedText),
                        () => {},
                    )

                    this.isScanning = true
                } catch (error) {
                    this.scanError = error?.message ?? 'Unable to access the camera.'
                    this.scanner = null
                    this.isScanning = false
                }
            },

            async onScanSuccess(decodedText) {
                this.lastScannedUrl = decodedText

                if ('vibrate' in navigator) {
                    navigator.vibrate(150)
                }

                await this.stopScanning()

                try {
                    window.location.href = new URL(decodedText, window.location.origin).toString()
                } catch (error) {
                    this.scanError = 'The QR code does not contain a valid URL.'
                }
            },

            async stopScanning() {
                if (! this.scanner) {
                    this.isScanning = false
                    return
                }

                try {
                    await this.scanner.clear()
                } catch (error) {
                    console.debug('QR scanner cleanup warning:', error)
                } finally {
                    this.scanner = null
                    this.isScanning = false
                }
            },

            async restartScanner() {
                await this.stopScanning()
                await this.startScanning()
            },
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    #qr-reader-modal video { border-radius: 0.375rem; }
    #qr-reader-modal { padding: 0; }
</style>
