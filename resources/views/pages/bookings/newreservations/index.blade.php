<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Confirm Reservations
                </h1>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible mb-6">
            <form method="GET" action="{{ route('newReserv.filter') }}"
                onsubmit="event.preventDefault(); fetchFilteredBookings();"
                class="flex flex-col gap-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <!-- Search Booking -->
                    <div class="md:col-span-1 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" placeholder="Order ID or Guest Name"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ request('search') }}">
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <div class="flex-1">
                            <div class="relative z-10">
                                <input type="text" id="date_picker" placeholder="Select date range (Max 30 days)"
                                    data-input
                                    class="w-full min-w-[280px] px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <input type="hidden" id="start_date" name="start_date"
                                    value="{{ request('start_date') }}">
                                <input type="hidden" id="end_date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Show Per Page (aligned to the right) -->
                    <div class="md:col-span-1 md:col-start-5 flex justify-end items-end">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600">Show:</label>
                            <select name="per_page" id="per_page"
                                class="border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="8" {{ request('per_page') == 8 ? 'selected' : '' }}>8</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto" id="bookingTableContainer">
            @include('pages.bookings.newreservations.partials.newreserve_table', [
                'checkIns' => $checkIns,
                'per_page' => request('per_page', 8),
            ])
        </div>
        <!-- Pagination -->
        <div class="bg-gray-50 rounded p-4" id="paginationContainer">
            {{ $checkIns->appends(request()->input())->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkInModal', (initialOrderId) => ({
                isOpen: false,
                isDragging: false,
                docPreview: null,
                docPreviewType: null,
                profilePhotoUrl: null,
                profilePhotoUrlDemo: null,
                profilePhotoUrlWeb: null,
                selectedDocType: 'ktp',
                bookingId: initialOrderId,
                guestContact: {
                    name: '',
                    email: '',
                    phone: ''
                },
                currentDateTime: new Date().toLocaleString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }),
                bookingDetails: {
                    order_id: '',
                    guest_name: '',
                    property_name: '',
                    room_name: '',
                    duration: '',
                    total_payment: ''
                },

                // Variabel baru untuk webcam
                uploadMethod: 'file',
                isCapturing: false,
                webcamPhoto: null,
                webcamStream: null,

                init() {
                    // Update time every second
                    setInterval(() => {
                        this.currentDateTime = new Date().toLocaleString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        });
                    }, 1000);
                },

                async openModal(idrec, orderId) {
                    this.bookingId = orderId;
                    this.isOpen = true;
                    this.currentDateTime = new Date().toLocaleString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });

                    await this.fetchBookingDetails();

                    // Set nilai default untuk form kontak
                    this.guestContact.name = this.bookingDetails.guest_name || '';
                    this.guestContact.email = this.bookingDetails.guest_email || '';
                    this.guestContact.phone = this.bookingDetails.guest_phone || '';
                },

                // Method untuk webcam
                async startWebcam() {
                    try {
                        // Hentikan webcam jika sedang berjalan
                        if (this.webcamStream) {
                            this.stopWebcam();
                        }

                        // Dapatkan akses ke kamera
                        this.webcamStream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'environment',
                                width: {
                                    ideal: 1280
                                },
                                height: {
                                    ideal: 720
                                }
                            },
                            audio: false
                        });

                        // Tunggu hingga video element siap
                        await this.$nextTick();

                        const video = this.$refs.webcamVideo;
                        if (video) {
                            video.srcObject = this.webcamStream;

                            // Tunggu video siap diputar
                            video.onloadedmetadata = () => {
                                video.play().catch(error => {
                                    console.error('Error playing video:', error);
                                });
                            };
                        }

                        this.isCapturing = true;
                        this.webcamPhoto = null;
                        this.webcamInitialized = true;

                    } catch (error) {
                        console.error('Error accessing webcam:', error);
                        this.showErrorToast(
                            'Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.'
                        );
                    }
                },

                stopWebcam() {
                    if (this.webcamStream) {
                        this.webcamStream.getTracks().forEach(track => {
                            track.stop();
                        });
                        this.webcamStream = null;
                    }
                    this.isCapturing = false;
                    this.webcamInitialized = false;

                    // Hapus srcObject dari video element
                    if (this.$refs.webcamVideo) {
                        this.$refs.webcamVideo.srcObject = null;
                    }
                },

                capturePhoto() {
                    if (!this.webcamStream || !this.$refs.webcamVideo) {
                        this.showErrorToast('Webcam tidak tersedia');
                        return;
                    }

                    const video = this.$refs.webcamVideo;
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    // Set canvas size sama dengan video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Gambar frame video ke canvas
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Konversi ke data URL
                    try {
                        this.webcamPhoto = canvas.toDataURL('image/jpeg', 0.8);
                        this.isCapturing = false;

                        // Stop webcam setelah mengambil foto
                        this.stopWebcam();

                        this.showSuccessToast('Foto berhasil diambil');
                    } catch (error) {
                        console.error('Error capturing photo:', error);
                        this.showErrorToast('Gagal mengambil foto');
                    }
                },


                retakePhoto() {
                    this.webcamPhoto = null;
                    this.startWebcam();
                },

                useWebcamPhoto() {
                    if (this.webcamPhoto) {
                        this.docPreview = this.webcamPhoto;
                        this.docPreviewType = 'image';
                        this.webcamPhoto = null;
                        this.showSuccessToast(
                            'Foto berhasil diambil dan akan digunakan sebagai dokumen identifikasi.'
                        );
                    }
                },

                handleUploadMethodChange(method) {
                    this.uploadMethod = method;

                    // Jika beralih dari camera ke file, pastikan webcam dimatikan
                    if (method === 'file' && this.isCapturing) {
                        this.stopWebcam();
                    }
                },

                async fetchBookingDetails() {
                    try {
                        const response = await fetch(
                            `/bookings/newReserv-in/${this.bookingId}/details`);
                        const data = await response.json();

                        // Helper function to format date and time
                        const formatDateTime = (dateString, timeString) => {
                            if (!dateString) return {
                                date: 'N/A',
                                time: 'N/A'
                            };

                            const date = new Date(dateString);
                            const formattedDate = date.toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            // Use provided time string or extract time from date
                            let formattedTime = timeString;
                            if (!timeString && dateString) {
                                formattedTime = date.toLocaleString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });
                            }

                            return {
                                date: formattedDate,
                                time: formattedTime || 'N/A'
                            };
                        };

                        const checkIn = formatDateTime(data.transaction?.check_in, data.transaction
                            ?.check_in_time);
                        const checkOut = formatDateTime(data.transaction?.check_out, data
                            .transaction?.check_out_time);

                        this.bookingDetails = {
                            order_id: data.order_id,
                            check_in_date: checkIn.date,
                            check_in_time: checkIn.time,
                            check_out_date: checkOut.date,
                            check_out_time: checkOut.time,
                            guest_name: data.user?.first_name || 'N/A',
                            guest_email: data.user?.email || 'N/A',
                            guest_phone: data.transaction?.user_phone_number || 'N/A',
                            property_name: data.property?.name || 'N/A',
                            property_address: data.property?.address || 'N/A',
                            room_name: data.room?.name || 'N/A',
                            room_number: data.room?.no || 'N/A',
                            update_by: data.transaction?.update_by || 'N/A',
                            duration: this.calculateDuration(data.transaction?.check_in, data
                                .transaction?.check_out),
                            total_payment: data.transaction?.grandtotal_price ?
                                this.formatRupiah(data.transaction.grandtotal_price) : 'N/A',
                            transaction_type: data.transaction?.transaction_type || 'N/A',
                        };

                        this.profilePhotoUrlDemo = data.user_profile_photo_demo || null;
                        this.profilePhotoUrlWeb = data.user_profile_photo_web || null;
                        this.profilePhotoUrl = this.profilePhotoUrlWeb || this
                            .profilePhotoUrlDemo || null;

                    } catch (error) {
                        console.error('Error fetching booking details:', error);
                        this.showErrorToast('Failed to load booking details');
                    }
                },

                printAgreement() {
                    const printWindow = window.open('', '_blank');
                    const logoPath = window.location.origin + '/images/frist_icon.png';

                    // Format tanggal sekarang untuk ditampilkan
                    const currentDate = new Date().toLocaleString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    printWindow.document.write(`
                            <html>
                            <head>
                                <title>Registration Form - ${this.bookingDetails.order_id}</title>
                                <style>
                                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                                    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
                                    body {
                                        font-family: 'Inter', sans-serif;
                                        margin: 0;
                                        padding: 12px;
                                        background: #fff;
                                        color: #000;
                                        font-size: 12px;
                                        line-height: 1.3;
                                    }
                                    .header {
                                        text-align: center;
                                        border-bottom: 1px solid #000;
                                        padding-bottom: 10px;
                                        margin-bottom: 12px;
                                    }
                                    .header img {
                                        height: 45px;
                                        margin-bottom: 6px;
                                        filter: grayscale(100%) contrast(120%);
                                    }
                                    .header h2 {
                                        font-size: 17px;
                                        font-weight: 700;
                                        margin: 3px 0 2px 0;
                                    }
                                    .header p {
                                        margin: 0;
                                        font-size: 13px;
                                        font-weight: 500;
                                    }
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        font-size: 11px;
                                        margin-bottom: 10px;
                                    }
                                    td {
                                        border: 1px solid #000;
                                        padding: 4px 5px;
                                        vertical-align: top;
                                    }
                                    .section-title {
                                        font-weight: 700;
                                        font-size: 12px;
                                        margin: 10px 0 6px 0;
                                        text-decoration: underline;
                                    }
                                    .notes, .disclaimer {
                                        font-size: 10px;
                                        margin-top: 8px;
                                        line-height: 1.3;
                                    }
                                    .bottom-section {
                                        margin-top: 5px;
                                        border-top: 1px solid #000;
                                        padding-top: 8px;
                                        font-size: 10px;
                                    }
                                    .bottom-table {
                                        margin-bottom: 8px;
                                    }
                                    .bottom-table td {
                                        border: 1px solid #000;
                                        text-align: left;
                                        vertical-align: top;
                                        padding: 5px;
                                        font-size: 10px;
                                        height: 30px;
                                    }
                                    
                                    .info-table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-top: 10px;
                                        font-size: 10px;
                                        height: 170px;
                                    }
                                    .info-table td {
                                        border: 1px solid #000;
                                        padding: 6px;
                                        vertical-align: top;
                                    }
                                    .info-table .left-col {
                                        width: 65%;
                                    }
                                    .info-table .right-col {
                                        width: 35%;
                                        text-align: center;
                                        position: relative;
                                    }
                                    .right-signature-container {
                                        position: absolute;
                                        top: 50%;
                                        left: 50%;
                                        transform: translate(-50%, -50%);
                                        width: 90%;
                                        text-align: center;
                                    }
                                    .signature-title {
                                        margin-bottom: 15px;
                                    }
                                    .signature-spacing {
                                        height: 200px;
                                        width: 100%;
                                    }
                                    .right-signature-box {
                                        height: 50px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin-top: 6px;
                                    }
                                    .right-icons {
                                        font-size: 15px;
                                    }
                                    .right-icons i {
                                        margin: 0 5px;
                                    }

                                    .icon-ban {
                                        position: relative;
                                        display: inline-block;
                                        width: 24px;
                                        height: 24px;
                                        margin-right: 5px;
                                    }

                                    .icon-ban i:first-child {
                                        font-size: 19px;
                                        color: #4b5563;
                                    }

                                    .icon-ban .fa-ban {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        color: red;
                                        font-size: 24px;
                                        opacity: 0.8;
                                    }
                                    
                                    .compact-text {
                                        font-size: 10px;
                                        line-height: 1.3;
                                        margin: 3px 0;
                                    }
                                    
                                    .penalty-table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-top: 8px;
                                        font-size: 10px;
                                    }
                                    .penalty-table td {
                                        border: 1px solid #000;
                                        padding: 5px;
                                        vertical-align: top;
                                        width: 33.33%;
                                    }
                                    .penalty-title {
                                        font-weight: 700;
                                    }
                                    
                                    .left-content-cell {
                                        height: 100%;
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: space-between;
                                    }
                                    
                                    .bottom-table tr td:first-child {
                                        padding-left: 8px;
                                    }
                                    
                                    /* Style untuk text bahasa Indonesia normal */
                                    .id-text {
                                        font-style: normal;
                                        font-weight: normal;
                                    }
                                    
                                    /* Style untuk text bahasa Inggris miring */
                                    .en-text {
                                        font-style: italic;
                                        font-weight: normal;
                                    }
                                    
                                    @media print {
                                        body {
                                            margin: 0;
                                            padding: 10px;
                                            font-size: 11px;
                                            line-height: 1.25;
                                        }
                                        .header {
                                            margin-bottom: 8px;
                                            padding-bottom: 8px;
                                        }
                                        .header img {
                                            height: 40px;
                                        }
                                        table {
                                            font-size: 10px;
                                            margin-bottom: 8px;
                                        }
                                        td {
                                            padding: 3px 4px;
                                        }
                                        .info-table {
                                            height: 150px;
                                        }
                                        .bottom-section {
                                            margin-top: 4px;
                                        }
                                        .compact-text {
                                            font-size: 9px;
                                            line-height: 1.25;
                                        }
                                    }
                                    
                                    @page {
                                        size: A4;
                                        margin: 10mm;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="header">
                                    <img src="${logoPath}" alt="Logo Ulin Mahoni" onerror="this.style.display='none'">
                                    <h2>ULIN MAHONI</h2>
                                    <p><span class="en-text">REGISTRATION FORM</span> / FORMULIR PENDAFTARAN</p>
                                </div>

                                <table>
                                    <tr>
                                        <td><span class="en-text"><strong>Folio Number</span> / Nomor Folio</strong><br>${this.bookingDetails.order_id}</td>
                                        <td><span class="en-text"><strong>Arrival Date</span> / Tanggal Kedatangan</strong><br>${this.bookingDetails.check_in_date}</td>
                                        <td><span class="en-text"><strong>ETA</span></strong><br>${this.bookingDetails.check_in_time}</td>
                                        <td><span class="en-text"><strong>Departure Date</span> / Tanggal Keberangkatan</strong><br>${this.bookingDetails.check_out_date}</td>
                                        <td><span class="en-text"><strong>ETD</span></strong><br>${this.bookingDetails.check_out_time}</td>
                                        <td><span class="en-text"><strong>Guarantee</span> / Garansi</strong><br>-</td>
                                    </tr>
                                    <tr>
                                        <td><span class="en-text"><strong>Rate Per Night</span> / Harga Per Malam</strong><br>${this.bookingDetails.total_payment || '-'}</td>
                                        <td><span class="en-text"><strong>Number of Guest</span> / Jumlah Tamu</strong><br>${this.bookingDetails.guest_count || '-'}</td>
                                        <td><span class="en-text"><strong>Advance Payment</span> / Uang Muka</strong><br>${this.bookingDetails.advance_payment || '-'}</td>
                                        <td><span class="en-text"><strong>Room Type</span> / Jenis Kamar</strong><br>${this.bookingDetails.room_name}</td>
                                        <td><span class="en-text"><strong>Number of Room</span> / Jumlah Kamar</strong><br>1</td>
                                        <td><span class="en-text"><strong>Room Number</span> / Nomor Kamar</strong><br>${this.bookingDetails.room_number || '-'}</td>
                                    </tr>
                                </table>

                                <div class="section-title"><span class="en-text"><strong>Guest Information</span> / Informasi Tamu</div>
                                <table>
                                    <tr>
                                        <td><span class="en-text"><strong>Name (Mr/Mrs/Miss)</span> / Nama (Tn/Ny/Nona)</strong><br>${this.guestContact.name}</td>
                                        <td><span class="en-text"><strong>Nationality</span> / Kewarganegaraan</strong><br>Indonesia</td>
                                        <td><span class="en-text"><strong>Date of Birth</span> / Tanggal Lahir</strong><br>-</td>
                                    </tr>
                                    <tr>
                                        <td><span class="en-text"><strong>ID Card / SIM / Passport No.</span></strong><br>-</td>
                                        <td><span class="en-text"><strong>Company</span> / Perusahaan</strong><br>${this.bookingDetails.company_name || '-'}</td>
                                        <td><span class="en-text"><strong>Telephone</span> / Telepon</strong><br>${this.guestContact.phone}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><span class="en-text"><strong>Home Address</span> / Alamat Rumah</strong><br>${this.guestContact.address || '-'}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="en-text"><strong>Terms of Payment</span> / Cara Pembayaran</strong><br>${this.bookingDetails.transaction_type || '-'}</td>
                                        <td><span class="en-text"><strong>Credit Card</span> / Kartu Kredit</strong><br>-</td>
                                        <td><span class="en-text"><strong>Visa Type</span> / Jenis Visa</strong><br><span class="en-text">Tourist</span> / Wisatawan</td>
                                    </tr>
                                </table>

                                <table class="info-table">
                                    <tr>
                                    <td class="left-col">
                                            <div class="compact-text">
                                                <span class="en-text"><strong>NOTES FOR NON-SMOKING ROOM</span> / CATATAN UNTUK KAMAR BEBAS ROKOK</strong><br>
                                                <em class="en-text">
                                                    Please note that the hotel strictly prohibits bringing durian and pets into the hotel premises.<br>
                                                    You have been assigned to a NON-SMOKING room.<br>
                                                    By signing beside, you agree to comply with the NON-SMOKING policy—both for yourself and any visiting guests.<br>
                                                    If smoking occurs in your room, you will be liable to pay a cleaning and fabric replacement charge of Rp. 2.000.000,- per incident, which is non-negotiable.
                                                </em><br>
                                                <span class="id-text">
                                                    Harap diketahui bahwa hotel tidak mengizinkan Anda membawa durian dan hewan peliharaan ke dalam area hotel.<br>
                                                    Anda telah ditempatkan di kamar bebas rokok (NON-SMOKING).<br>
                                                    Dengan menandatangani kolom di samping ini, Anda setuju untuk mematuhi kebijakan bebas rokok, baik oleh Anda sendiri maupun tamu yang berkunjung.<br>
                                                    Apabila Anda atau tamu Anda diketahui merokok di dalam kamar dan menyebabkan kamar tersebut tidak dapat dijual kembali, maka Anda berkewajiban membayar biaya pembersihan dan penggantian perlengkapan yang tercemar sebesar Rp. 2.000.000,- untuk setiap kejadian, dan biaya tersebut tidak dapat dinegosiasikan.
                                                </span>
                                            </div>
                                        </td>

                                        <td class="right-col" rowspan="2">
                                            <div class="right-signature-container">
                                                <div class="compact-text signature-title">
                                                    <span class="en-text"><strong>Guest Signature</span> / Tanda Tangan Tamu</strong>
                                                </div>
                                                
                                                <!-- Jarak dengan class CSS -->
                                                <div class="signature-spacing"></div>
                                                
                                                <div class="right-signature-box">
                                                    <div class="right-icons">
                                                        <span class="icon-ban" title="No Durian">
                                                            <i class="fa-solid fa-lemon"></i>
                                                            <i class="fa-solid fa-ban"></i>
                                                        </span>
                                                        <span class="icon-ban" title="No Pets">
                                                            <i class="fa-solid fa-dog"></i>
                                                            <i class="fa-solid fa-ban"></i>
                                                        </span>
                                                        <span class="icon-ban" title="No Smoking">
                                                            <i class="fa-solid fa-smoking"></i>
                                                            <i class="fa-solid fa-ban"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left-col">
                                            <div class="compact-text">
                                                <strong>DISCLAIMER / PENAFIAN</strong><br>
                                                <em class="en-text">
                                                    Guests are advised not to leave valuable belongings unattended in the room.<br>
                                                    For your safety, please use the in-room safe deposit box to store valuable items.<br>
                                                    By signing this form, I acknowledge that I am fully responsible for any expenses incurred by the above-named person, company, or association during the stay at Ulin Mahoni.
                                                </em><br>
                                                <span class="id-text">
                                                    Tamu dihimbau untuk tidak meninggalkan barang-barang berharga tanpa pengawasan di dalam kamar.<br>
                                                    Untuk keamanan, silakan gunakan kotak penyimpanan (safe deposit box) yang tersedia untuk menyimpan barang-barang berharga.<br>
                                                    Dengan menandatangani formulir ini, saya menyatakan bahwa saya sepenuhnya bertanggung jawab atas biaya yang timbul oleh nama, perusahaan, atau asosiasi yang disebutkan di atas selama masa menginap di Ulin Mahoni.
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table class="penalty-table">
                                    <tr>
                                        <td class="penalty-title">
                                            <span class="en-text"><strong>PENALTY INFORMATION</span> / INFORMASI DENDA</strong><br>
                                            <span style="font-weight: normal;">
                                                <span class="en-text">Prohibited strong smell fruit, pets & arms with penalty Rp. 500.000</span><br>
                                                <span class="id-text">Dilarang membawa buah yang berbau menyengat, hewan peliharaan & senjata, akan dikenakan denda Rp. 500.000</span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="en-text">Penalty for lost key card Rp. 50.000</span><br>
                                            <span class="id-text">Denda untuk kartu kunci yang hilang Rp. 50.000</span>
                                        </td>
                                        <td>
                                            <span class="en-text"><strong>Date</span> / Tanggal</strong><br>
                                            <span class="id-text">${currentDate}</span>
                                        </td>
                                    </tr>
                                </table>

                                <div class="bottom-section">
                                    <table class="bottom-table">
                                        <tr>
                                            <td><span class="en-text">Check-In By</span><br><span class="id-text">Melapor Masuk Oleh</span></td>
                                            <td><span class="en-text">Check-Out By</span><br><span class="id-text">Melapor Keluar Oleh</span></td>
                                            <td><span class="en-text">Front Desk</span><br><span class="id-text">Penyelia / Supervisor</span></td>
                                        </tr>
                                    </table>

                                    <div style="text-align:center; margin-top:8px;" class="compact-text">
                                        <span class="en-text"><strong>Rates are inclusive of service charge and government tax</strong></span><br>
                                        <span class="id-text">Harga sudah termasuk jasa pelayanan dan pajak pemerintah</span>
                                    </div>
                                </div>

                                <div style="margin-top: 12px; font-size: 9px; text-align: center; color: #555; line-height: 1.2;">
                                    <p>Dokumen ini dibuat secara elektronik oleh Ulin Mahoni dan sah tanpa tanda tangan basah.</p>
                                    <p>© ${new Date().getFullYear()} Ulin Mahoni — Semua Hak Dilindungi Undang-Undang.</p>
                                </div>
                            </body>
                            </html>
                            `);

                    printWindow.document.close();
                    setTimeout(() => {
                        printWindow.print();
                    }, 600);
                },

                calculateDuration(checkIn, checkOut) {
                    if (!checkIn || !checkOut) return 'N/A';

                    const start = new Date(checkIn);
                    const end = new Date(checkOut);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    return `${diffDays} night${diffDays > 1 ? 's' : ''}`;
                },

                handleDocDrop(e) {
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length > 0 && (files[0].type.match('image.*') || files[0].type ===
                            'application/pdf')) {
                        this.previewDoc(files[0]);
                    }
                },

                handleDocUpload(e) {
                    const file = e.target.files[0];
                    if (file && (file.type.match('image.*') || file.type === 'application/pdf')) {
                        this.previewDoc(file);
                    }
                },

                previewDoc(file) {
                    if (file.size > 5 * 1024 * 1024) {
                        this.showErrorToast('File size should be less than 5MB');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.docPreview = e.target.result;
                        this.docPreviewType = file.type === 'application/pdf' ? 'pdf' : 'image';
                    };
                    reader.readAsDataURL(file);
                },

                removeDoc() {
                    this.docPreview = null;
                    this.docPreviewType = null;
                    this.$refs.docInput.value = '';
                },

                async submitCheckIn() {
                    // Validasi form
                    if (!this.guestContact.name || !this.guestContact.email || !this.guestContact
                        .phone) {
                        this.showErrorToast('Harap lengkapi semua informasi kontak');
                        return;
                    }

                    if (!this.docPreview) {
                        this.showErrorToast('Harap unggah dokumen identifikasi');
                        return;
                    }

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');

                        // Prepare the data to send
                        const requestData = {
                            doc_type: this.selectedDocType,
                            has_profile_photo: !!this.profilePhotoUrl,
                            agreement_accepted: true,
                            guest_name: this.guestContact.name,
                            guest_email: this.guestContact.email,
                            guest_phone: this.guestContact.phone
                        };

                        // Include doc_image only if there's no profile photo
                        if (!this.profilePhotoUrl && this.docPreview) {
                            requestData.doc_image = this.docPreview;
                        } else if (!this.profilePhotoUrl && !this.docPreview) {
                            this.showErrorToast('Please upload your identification document first');
                            return;
                        }

                        const response = await fetch(`/bookings/newReserv/${this.bookingId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(requestData)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showSuccessToast('Check-in submitted successfully!');
                            this.printAgreement(); // Langsung buka print agreement
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showErrorToast(data.message || 'Check-in failed');
                        }
                    } catch (error) {
                        console.error('Error during check-in:', error);
                        this.showErrorToast('An error occurred during check-in');
                    }
                },

                showSuccessToast(message) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                },

                showErrorToast(message) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true

                    });
                },

                formatRupiah(value) {
                    const numericValue = parseFloat(value);

                    if (isNaN(numericValue)) {
                        return 'Rp 0';
                    }

                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(numericValue);
                },

                closeModal() {
                    this.isOpen = false;
                    this.stopWebcam(); // Pastikan webcam dihentikan saat modal ditutup
                    this.resetForm();
                },

                resetForm() {
                    this.docPreview = null;
                    this.docPreviewType = null;
                    this.selectedDocType = 'ktp';
                    this.uploadMethod = 'file';
                    this.isCapturing = false;
                    this.webcamPhoto = null;
                    this.guestContact = {
                        name: '',
                        email: '',
                        phone: ''
                    };
                    if (this.$refs.docInput) {
                        this.$refs.docInput.value = '';
                    }
                    this.stopWebcam();
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const defaultStartDate = new Date();
            const defaultEndDate = new Date();
            defaultEndDate.setMonth(defaultEndDate.getMonth() + 1);

            // Initialize Flatpickr with default range
            const datePicker = flatpickr("#date_picker", {
                mode: "range", // Tetap mode range tapi bisa pilih 1 tanggal
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                allowInput: true,
                static: true,
                monthSelectorType: 'static',
                defaultDate: [defaultStartDate, defaultEndDate],
                minDate: "today",
                maxDate: new Date().fp_incr(365),
                onOpen: function(selectedDates, dateStr, instance) {
                    instance.set('minDate', null);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const startDate = selectedDates[0];
                        // Jika hanya 1 tanggal yang dipilih, gunakan tanggal itu saja
                        const endDate = selectedDates[1] || selectedDates[0];

                        // Format tanggal ke YYYY-MM-DD
                        document.getElementById('start_date').value = formatDate(startDate);
                        document.getElementById('end_date').value = formatDate(endDate);
                        fetchFilteredBookings();
                    }
                },
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 0) {
                        document.getElementById('start_date').value = '';
                        document.getElementById('end_date').value = '';
                        fetchFilteredBookings();
                    }
                }
            });

            // Set initial hidden input values
            document.getElementById('start_date').value = formatDate(defaultStartDate);
            document.getElementById('end_date').value = formatDate(defaultEndDate);

            // Fungsi format tanggal
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Set initial values if they exist
            @if (request('start_date') && request('end_date'))
                const startDate = new Date('{{ request('start_date') }}');
                const endDate = new Date('{{ request('end_date') }}');

                // Jika start_date dan end_date sama, set hanya 1 tanggal
                if (formatDate(startDate) === formatDate(endDate)) {
                    datePicker.setDate(startDate);
                } else {
                    datePicker.setDate([startDate, endDate]);
                }
            @endif

            // Get all filter elements
            const searchInput = document.getElementById('search');
            const perPageSelect = document.getElementById('per_page');

            // Debounce function for search
            const debounce = (func, delay) => {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            };

            // Event listeners
            searchInput.addEventListener('input', debounce(fetchFilteredBookings, 300));
            perPageSelect.addEventListener('change', fetchFilteredBookings);

            // Function to fetch filtered bookings
            function fetchFilteredBookings() {
                // Collect all filter values
                const params = new URLSearchParams();

                // Get search value
                const search = document.getElementById('search').value;
                if (search) params.append('search', search);

                // Get date range values
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                // Get per page value
                const perPage = document.getElementById('per_page').value;
                params.append('per_page', perPage);

                // Show loading state
                const tableContainer = document.querySelector('.overflow-x-auto');
                tableContainer.innerHTML = `
                                                <div class="flex justify-center items-center h-64">
                                                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                                                </div>
                                            `;

                // Make AJAX request to the filter endpoint
                fetch(`{{ route('newReserv.filter') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        document.querySelector('.overflow-x-auto').innerHTML = data.table;
                        document.getElementById('paginationContainer').innerHTML = data.pagination;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableContainer.innerHTML = `
                                                        <div class="text-center py-8 text-red-500">
                                                            Error loading data. Please try again.
                                                        </div>
                                                    `;
                    });
            }
        });
    </script>
</x-app-layout>
