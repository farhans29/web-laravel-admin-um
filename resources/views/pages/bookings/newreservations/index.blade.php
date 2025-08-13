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
            <form method="GET" action="{{ route('checkin.filter') }}"
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
                agreementAccepted: false,
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
                },

                async fetchBookingDetails() {
                    try {
                        const response = await fetch(
                            `/bookings/newReserv-in/${this.bookingId}/details`);
                        const data = await response.json();

                        this.bookingDetails = {
                            order_id: data.order_id,
                            check_in: data.transaction?.check_in ?
                                new Date(data.transaction.check_in).toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                }) : 'N/A',

                            check_out: data.transaction?.check_out ?
                                new Date(data.transaction.check_out).toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                }) : 'N/A',
                            guest_name: data.user?.first_name || 'N/A',
                            guest_email: data.user?.email || 'N/A',
                            guest_phone: data.transaction?.user_phone_number || 'N/A',
                            property_name: data.property?.name || 'N/A',
                            property_address: data.property?.address || 'N/A',
                            room_name: data.room?.name || 'N/A',
                            duration: this.calculateDuration(data.transaction?.check_in, data
                                .transaction?.check_out),
                            total_payment: data.transaction?.grandtotal_price ?
                                this.formatRupiah(data.transaction.grandtotal_price) : 'N/A',

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
                    console.log(logoPath);
                    printWindow.document.write(`
                        <html>
                            <head>
                                <title>Booking Agreement - ${this.bookingDetails.order_id}</title>
                                <style>
                                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
                                    
                                    body {
                                        font-family: 'Inter', sans-serif;
                                        line-height: 1.6;
                                        padding: 0;
                                        margin: 0;
                                        color: #1a1a1a;
                                        background-color: #f8fafc;
                                    }
                                    
                                    .page-header {
                                        background: linear-gradient(135deg, #7b3f00, #a0522d, #d2691e);
                                        color: white;
                                        padding: 1.5rem 2.5rem;
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                        border-bottom-left-radius: 12px;
                                        border-bottom-right-radius: 12px;
                                        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                                    }

                                    .header-left {
                                        display: flex;
                                        align-items: center;
                                    }
                                    
                                    .header-content {
                                        margin-left: 1.5rem;
                                    }
                                    
                                    .header-title {
                                        font-size: 1.75rem;
                                        font-weight: 700;
                                        margin: 0;
                                        letter-spacing: -0.5px;
                                    }
                                    
                                    .header-subtitle {
                                        font-size: 0.875rem;
                                        opacity: 0.9;
                                        margin: 0.25rem 0 0 0;
                                        font-weight: 400;
                                    }
                                    
                                    .logo {
                                        height: 3.5rem;
                                        width: 3.5rem;
                                        object-fit: contain;
                                        border-radius: 8px;
                                        background: white;
                                        padding: 5px;
                                        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                                    }

                                    .header-text {
                                        margin-left: 1rem;
                                        line-height: 1.4;
                                    }

                                    .header-text h1 {
                                        font-size: 1.6rem;
                                        font-weight: 700;
                                        margin: 0;
                                        letter-spacing: -0.5px;
                                    }

                                    .property-name {
                                        font-size: 1rem;
                                        font-weight: 500;
                                        opacity: 0.95;
                                        margin: 2px 0;
                                    }

                                    .property-address {
                                        font-size: 0.875rem;
                                        opacity: 0.85;
                                    }

                                    .header-right .order-id {
                                        display: inline-block;
                                        background: white;
                                        color: #7b3f00;
                                        padding: 0.4rem 1rem;
                                        border-radius: 20px;
                                        font-size: 1rem;
                                        font-weight: 600;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                    }

                                    @media (max-width: 600px) {
                                        .page-header {
                                            flex-direction: column;
                                            align-items: flex-start;
                                            text-align: left;
                                        }
                                        .header-right {
                                            margin-top: 0.8rem;
                                        }
                                    }
                                    
                                    .container {
                                        max-width: 850px;
                                        margin: 0 auto;
                                        padding: 0 3rem 3rem;
                                        background-color: white;
                                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                                        border-radius: 0.5rem;
                                    }
                                    
                                    .document-title {
                                        text-align: center;
                                        font-size: 1.75rem;
                                        font-weight: 700;
                                        color: #1e293b;
                                        margin-bottom: 2.5rem;
                                        padding-bottom: 1rem;
                                        border-bottom: 1px solid #e2e8f0;
                                        position: relative;
                                    }
                                    
                                    .document-title:after {
                                        content: "";
                                        position: absolute;
                                        bottom: -1px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 80px;
                                        height: 3px;
                                        background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
                                        border-radius: 3px;
                                    }
                                    
                                    .section {
                                        margin-bottom: 2rem;
                                    }
                                    
                                    .section-title {
                                        font-size: 1.25rem;
                                        font-weight: 600;
                                        color: #1e293b;
                                        margin-bottom: 1.25rem;
                                        padding-bottom: 0.5rem;
                                        border-bottom: 1px solid #f1f5f9;
                                        position: relative;
                                    }
                                    
                                    .section-title:after {
                                        content: "";
                                        position: absolute;
                                        bottom: -1px;
                                        left: 0;
                                        width: 50px;
                                        height: 2px;
                                        background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
                                    }
                                    
                                    .detail-grid {
                                        display: grid;
                                        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                                        gap: 1rem;
                                        margin-bottom: 1rem;
                                    }
                                    
                                    .detail-item {
                                        display: flex;
                                        flex-direction: column;
                                    }
                                    
                                    .detail-label {
                                        font-weight: 500;
                                        color: #64748b;
                                        font-size: 0.875rem;
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    .detail-value {
                                        font-weight: 500;
                                        color: #1e293b;
                                        padding: 0.5rem 0;
                                        border-bottom: 1px dashed #e2e8f0;
                                    }
                                    
                                    .terms-list {
                                        padding-left: 1.25rem;
                                        margin: 1.5rem 0;
                                        list-style-type: decimal;
                                        list-style-position: inside;
                                    }

                                    .terms-list li {
                                        margin-bottom: 0.75rem;
                                        padding-left: 0.5rem;
                                        position: relative;
                                    }

                                    .terms-list li:before {
                                        content: none; /* Hilangkan bullet custom */
                                    }

                                    
                                    .signature-section {
                                        display: flex;
                                        justify-content: space-between;
                                        margin-top: 4rem;
                                    }
                                    
                                    .signature-box {
                                        width: 45%;
                                        text-align: center;
                                    }
                                    
                                    .signature-placeholder {
                                        height: 3rem;
                                        margin-bottom: 0.5rem;
                                        border-bottom: 1px solid #94a3b8;
                                    }
                                    
                                    .signature-label {
                                        font-size: 0.875rem;
                                        color: #64748b;
                                        margin-top: 0.5rem;
                                    }
                                    
                                    .signature-name {
                                        font-weight: 500;
                                        margin-top: 0.25rem;
                                    }
                                    
                                    .footer {
                                        margin-top: 3rem;
                                        text-align: center;
                                        font-size: 0.75rem;
                                        color: #94a3b8;
                                        padding-top: 1rem;
                                        border-top: 1px solid #f1f5f9;
                                    }
                                    
                                    .badge {
                                        display: inline-block;
                                        padding: 0.25rem 0.75rem;
                                        background-color: #e0e7ff;
                                        color: #4f46e5;
                                        border-radius: 9999px;
                                        font-size: 0.75rem;
                                        font-weight: 600;
                                        margin-left: 0.5rem;
                                        vertical-align: middle;
                                    }
                                    
                                    @media print {
                                        body { 
                                            padding: 0 !important;
                                            background-color: white !important;
                                            -webkit-print-color-adjust: exact !important;
                                            print-color-adjust: exact !important;
                                        }
                                        .page-header {
                                            -webkit-print-color-adjust: exact !important;
                                            print-color-adjust: exact !important;
                                        }
                                        .container {
                                            box-shadow: none !important;
                                        }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="page-header">
                                    <div class="header-left">
                                        <img src="${logoPath}" alt="Logo Ulin Mahoni" class="logo" 
                                            onerror="this.style.display='none'">
                                        <div class="header-text">
                                            <h1>Ulin Mahoni</h1>
                                            <p class="property-name">${this.bookingDetails.property_name}</p>
                                            <p class="property-address">${this.bookingDetails.property_address}</p>
                                        </div>
                                    </div>
                                    <div class="header-right">
                                        <span class="order-id">${this.bookingDetails.order_id}</span>
                                    </div>
                                </div>
                                <div class="container">
                                    <h2 class="document-title">BOOKING AGREEMENT</h2>
                                    
                                    <div class="section">
                                        <h3 class="section-title">Informasi Pemesanan</h3>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span class="detail-label">Tanggal Pemesanan</span>
                                                <span class="detail-value">${new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Nama Tamu</span>
                                                <span class="detail-value">${this.bookingDetails.guest_name}</span>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Tipe Kamar</span>
                                                <span class="detail-value">${this.bookingDetails.room_name}</span>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Check-In</span>
                                                <span class="detail-value">${this.bookingDetails.check_in}</span>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Check-Out</span>
                                                <span class="detail-value">${this.bookingDetails.check_out}</span>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Durasi</span>
                                                <span class="detail-value">${this.bookingDetails.duration}</span>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-label">Total Pembayaran</span>
                                                <span class="detail-value">${this.bookingDetails.total_payment}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="section">
                                        <h3 class="section-title">Syarat & Ketentuan</h3>
                                        <ol class="terms-list">
                                            <li>Properti hanya digunakan untuk tujuan hunian oleh tamu yang terdaftar</li>
                                            <li>Dilarang merokok di dalam properti. Biaya pembersihan sebesar Rp 1.000.000 akan dikenakan jika melanggar</li>
                                            <li>Hewan peliharaan tidak diperbolehkan tanpa persetujuan tertulis dari manajemen</li>
                                            <li>Tamu bertanggung jawab penuh atas kerusakan properti atau isinya selama menginap</li>
                                            <li>Jam tenang berlaku pukul 22:00 hingga 07:00. Kebisingan berlebihan dapat mengakibatkan penghentian masa inap tanpa pengembalian dana</li>
                                            <li>Kapasitas maksimal tidak boleh melebihi jumlah tamu yang tercantum pada konfirmasi pemesanan</li>
                                            <li>Semua peraturan gedung harus dipatuhi selama menginap</li>
                                            <li>Properti harus dijaga kebersihannya dan ditinggalkan dalam kondisi yang sama seperti saat kedatangan</li>
                                            <li>Kunci atau kartu akses yang hilang akan dikenakan biaya penggantian sebesar Rp 500.000</li>
                                            <li>Waktu check-out adalah pukul 12:00 WIB. Keterlambatan check-out dapat dikenakan biaya tambahan kecuali telah disetujui sebelumnya</li>
                                            <li>Pesta atau acara tanpa izin dilarang keras</li>
                                            <li>Manajemen berhak memasuki properti untuk keperluan perawatan atau darurat dengan pemberitahuan yang wajar</li>
                                        </ol>

                                        
                                        <p style="margin-top: 2rem; font-weight: 500; color: #475569; font-size: 0.9375rem;">
                                            Dengan menandatangani perjanjian ini, tamu menyatakan telah menerima syarat dan ketentuan ini dan setuju untuk mematuhinya selama masa menginap.
                                        </p>
                                    </div>
                                    
                                    <div class="signature-section">
                                    <div class="signature-box">
                                            <div class="signature-placeholder"></div>
                                            <div class="signature-label">Tanda Tangan Tamu</div>
                                            <div class="signature-name">${this.bookingDetails.guest_name}</div>
                                            <div class="signature-label">
                                                Tanggal: ${new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="footer">
                                        <p>Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan.</p>
                                        <p>${window.location.hostname} • © ${new Date().getFullYear()} Ulin Mahoni. Hak cipta dilindungi undang-undang.</p>
                                    </div>
                                </div>
                            </body>
                        </html>

                    `);
                    printWindow.document.close();
                    setTimeout(() => {
                        printWindow.print();
                    }, 500);
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
                    try {
                        if (!this.agreementAccepted) {
                            this.showErrorToast('Please agree to the terms and conditions first');
                            return;
                        }

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');

                        // Prepare the data to send
                        const requestData = {
                            doc_type: this.selectedDocType,
                            has_profile_photo: !!this.profilePhotoUrl,
                            agreement_accepted: true
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
                    this.resetForm();
                },

                resetForm() {
                    this.docPreview = null;
                    this.docPreviewType = null;
                    this.selectedDocType = 'ktp';
                    this.agreementAccepted = false;
                    if (this.$refs.docInput) {
                        this.$refs.docInput.value = '';
                    }
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
                fetch(`{{ route('checkin.filter') }}?${params.toString()}`, {
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
