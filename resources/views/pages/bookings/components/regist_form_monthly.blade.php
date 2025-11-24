<!DOCTYPE html>
<html>

<head>
    <title>Registration Form - {{ $bookingDetails['order_id'] }}</title>
    <style>
        /* CSS tetap sama seperti sebelumnya */
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
            display: flex;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
            position: relative;
        }

        .logo-container {
            flex-shrink: 0;
            margin-right: 15px;
        }

        .header img {
            height: 45px;
            filter: grayscale(100%) contrast(120%);
        }

        .header-content {
            flex: 1;
            text-align: center;
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

        .notes,
        .disclaimer {
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
        }

        .signature-container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .document-section {
            flex: 1;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            padding: 5px;
        }

        .document-image {
            max-width: 100%;
            max-height: 120px;
            object-fit: contain;
        }

        .document-label {
            font-size: 9px;
            margin-top: 5px;
            font-weight: bold;
        }

        .signature-section {
            padding: 10px;
            text-align: center;
        }

        .signature-title {
            margin-bottom: 15px;
        }

        .signature-spacing {
            height: 60px;
            width: 100%;
            margin-bottom: 10px;
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
        }

        .main-icon {
            width: 100%;
            height: 100%;
            display: block;
        }

        .ban-custom {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
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

            .document-section {
                min-height: 140px;
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
        <div class="logo-container">
            <img src="{{ $logoPath }}" alt="Logo Ulin Mahoni" onerror="this.style.display='none'">
        </div>
        <div class="header-content">
            <h2>ULIN MAHONI</h2>
            <p><span class="en-text">REGISTRATION FORM</span> / FORMULIR PENDAFTARAN</p>
        </div>
    </div>

    <table>
        <tr>
            <td><span class="en-text"><strong>Folio Number</span> / Nomor
                Folio</strong><br>{{ $bookingDetails['order_id'] }}</td>
            <td><span class="en-text"><strong>Arrival Date</span> / Tanggal
                Kedatangan</strong><br>{{ $bookingDetails['check_in_date'] }}</td>
            <td><span class="en-text"><strong>ETA</span></strong><br>{{ $bookingDetails['check_in_time'] }}</td>
            <td><span class="en-text"><strong>Departure Date</span> / Tanggal
                Keberangkatan</strong><br>{{ $bookingDetails['check_out_date'] }}</td>
            <td><span class="en-text"><strong>ETD</span></strong><br>{{ $bookingDetails['check_out_time'] }}</td>
            <td></td>
        </tr>
        <tr>
            <td><span class="en-text"><strong>Rate Per Night</span> / Harga Per
                Malam</strong><br>{{ $bookingDetails['total_payment'] }}</td>
            <td><span class="en-text"><strong>Number of Guest</span> / Jumlah
                Tamu</strong><br>{{ $bookingDetails['guest_count'] }}</td>
            <td><span class="en-text"><strong>Advance Payment</span> / Uang
                Muka</strong><br>{{ $bookingDetails['advance_payment'] }}</td>
            <td><span class="en-text"><strong>Room Type</span> / Jenis
                Kamar</strong><br>{{ $bookingDetails['room_name'] }}</td>
            <td><span class="en-text"><strong>Number of Room</span> / Jumlah Kamar</strong><br>1</td>
            <td><span class="en-text"><strong>Room Number</span> / Nomor
                Kamar</strong><br>{{ $bookingDetails['room_number'] }}</td>
        </tr>
    </table>

    <div class="section-title"><span class="en-text"><strong>Guest Information</span> / Informasi Tamu</div>
    <table>
        <tr>
            <td><span class="en-text"><strong>Name (Mr/Mrs/Miss)</span> / Nama
                (Tn/Ny/Nona)</strong><br>{{ $guestContact['name'] }}</td>
            <td><span class="en-text"><strong>Nationality</span> / Kewarganegaraan</strong><br>Indonesia</td>
            <td><span class="en-text"><strong>Date of Birth</span> / Tanggal Lahir</strong><br>-</td>
        </tr>
        <tr>
            <td><span class="en-text"><strong>ID Card / SIM / Passport No.</span></strong><br>-</td>
            <td><span class="en-text"><strong>Company</span> /
                Perusahaan</strong><br>{{ $bookingDetails['company_name'] }}</td>
            <td><span class="en-text"><strong>Telephone</span> / Telepon</strong><br>{{ $guestContact['phone'] }}</td>
        </tr>
        <tr>
            <td colspan="3"><span class="en-text"><strong>Home Address</span> / Alamat
                Rumah</strong><br>{{ $guestContact['address'] }}</td>
        </tr>
        <tr>
            <td><span class="en-text"><strong>Terms of Payment</span> / Cara
                Pembayaran</strong><br>{{ $bookingDetails['transaction_type'] }}</td>
            <td><span class="en-text"><strong>Credit Card</span> / Kartu Kredit</strong><br>-</td>
            <td><span class="en-text"><strong>Visa Type</span> / Jenis Visa</strong><br><span
                    class="en-text">Tourist</span> / Wisatawan</td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="left-col">
                <div class="compact-text">
                    <span class="en-text"><strong>NOTES FOR NON-SMOKING ROOM</span> / CATATAN UNTUK KAMAR BEBAS
                    ROKOK</strong><br>
                    <em class="en-text">
                        Please note that the hotel strictly prohibits bringing durian and pets into the hotel
                        premises.<br>
                        You have been assigned to a NON-SMOKING room.<br>
                        By signing beside, you agree to comply with the NON-SMOKING policy—both for yourself and any
                        visiting guests.<br>
                        If smoking occurs in your room, you will be liable to pay a cleaning and fabric replacement
                        charge of Rp. 2.000.000,- per incident, which is non-negotiable.
                    </em><br>
                    <span class="id-text">
                        Harap diketahui bahwa hotel tidak mengizinkan Anda membawa durian dan hewan peliharaan ke dalam
                        area hotel.<br>
                        Anda telah ditempatkan di kamar bebas rokok (NON-SMOKING).<br>
                        Dengan menandatangani kolom di samping ini, Anda setuju untuk mematuhi kebijakan bebas rokok,
                        baik oleh Anda sendiri maupun tamu yang berkunjung.<br>
                        Apabila Anda atau tamu Anda diketahui merokok di dalam kamar dan menyebabkan kamar tersebut
                        tidak dapat dijual kembali, maka Anda berkewajiban membayar biaya pembersihan dan penggantian
                        perlengkapan yang tercemar sebesar Rp. 2.000.000,- untuk setiap kejadian, dan biaya tersebut
                        tidak dapat dinegosiasikan.
                    </span>
                </div>
            </td>

            <!-- Kolom kanan digabung jadi 1 kolom kebawah -->
            <td class="right-col" rowspan="2">
                <div class="signature-container">
                    <div class="document-section">
                        @if ($documentImage)
                            <img src="{{ $documentImage }}" alt="Guest Document" class="document-image"
                                onerror="this.style.display='none'">
                        @else
                            <div class="document-label">No Document Available</div>
                        @endif
                    </div>
                    <div class="signature-section">
                        <div class="compact-text signature-title">
                            <span class="en-text"><strong>Guest Signature</span> / Tanda Tangan Tamu</strong>
                        </div>

                        <div class="signature-spacing"></div>

                        <div class="right-signature-box">
                            <div class="right-icons">
                                <!-- No Durian -->
                                <span class="icon-ban" title="No Durian">
                                    <svg class="main-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path
                                            d="M405.62 292.4l-22.85-28 12.17-34a7 7 0 00-3.06-8.4l-31.28-18.29-1.6-36.23a7 7 0 00-6.29-6.66l-36.22-3.63-18.49-31.4a7 7 0 00-9.06-2.75l-25.61 12.35V86.84c18.8-7.44 38.6-18.24 56.16-33.92a7 7 0 00-9.32-10.44C260.49 86.82 188.7 89.23 188 89.25a7 7 0 00-.17 14h.18c1.85 0 28.48-.91 61-11.4v43.83L223.08 123a7 7 0 00-9.06 2.75l-18.53 31.4-36.23 3.63a7 7 0 00-6.29 6.66l-1.57 36.23L120.12 222a7 7 0 00-3.06 8.4l12.17 34-22.85 28a7 7 0 000 8.85l22.85 28-12.17 34a7 7 0 003.06 8.41l31.28 18.26 1.6 36.24a7 7 0 006.29 6.66l36.22 3.64 18.49 31.4a7 7 0 009.06 2.74L256 454.73l32.92 15.87a7 7 0 009.06-2.74l18.53-31.4 36.23-3.64a7 7 0 006.29-6.66l1.57-36.23 31.28-18.26a7 7 0 003.06-8.4l-12.17-34 22.85-28a7 7 0 000-8.87z"
                                            fill="#000" />
                                        <path
                                            d="M321.19 274.21c-4.86 2.81-11.32 1.19-14.13-3.67-2.81-4.86 1.19-11.32 3.67 14.13 4.86 2.81 11.32 1.19 14.13-3.67 2.81-4.86 1.19-11.32-3.67-14.13z"
                                            fill="#000" />
                                        <path
                                            d="M190.81 274.21c-4.86-2.81-6.48-9.27-3.67-14.13 2.81-4.86 9.27-6.48 14.13-3.67 4.86 2.81 6.48 9.27 3.67 14.13-2.81 4.86-9.27 6.48-14.13 3.67z"
                                            fill="#000" />
                                        <path
                                            d="M256 298.67c-23.5 0-42.67-19.17-42.67-42.67s19.17-42.67 42.67-42.67 42.67 19.17 42.67 42.67-19.17 42.67-42.67 42.67z"
                                            fill="#000" />
                                    </svg>

                                    <!-- Custom Red Circle + Slash Overlay -->
                                    <svg class="ban-custom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="46" stroke="red" stroke-width="8"
                                            fill="none" />
                                        <line x1="25" y1="25" x2="75" y2="75" stroke="red"
                                            stroke-width="10" stroke-linecap="round" />
                                    </svg>
                                </span>

                                <!-- No Pets -->
                                <span class="icon-ban" title="No Pets">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 64 64">
                                        <g stroke-width="0">
                                            <path
                                                d="M42.6 8.1c-.3.8-.7 2.7-.9 4.4-.3 1.6-.8 3.9-1.2 5-.9 2.9 6.7 5.7 12.1 4.5 5.9-1.4 8.7-6.9 3.9-7.9-1.1-.2-3.1-1.2-4.4-2.2-1.3-1.1-3.1-1.9-3.9-1.9-.9 0-2.4-.7-3.4-1.6C43.4 7 43 7 42.6 8.1M8 20.1c0 1.7 1 4.9 2.2 7l2.1 3.8-1.8 10.7c-1.5 9-1.6 10.7-.4 11.5 2.5 1.5 4.9 1 4.9-1.1 0-1.1 1.2-4.5 2.7-7.6l2.8-5.6 4.2.6c2.4.3 6.3.9 8.8 1.2l4.5.6v5.2c0 5.5 1.2 7.6 4.2 7.6 1.4 0 1.8-.8 1.8-3.8 0-2 .7-7.7 1.6-12.6.8-4.8 1.4-8.9 1.2-9.1-.2-.1-2.6-1.4-5.5-2.9L36.1 23l-10.2 2.2-10.2 2.2-2.8-3.2C11.3 22.4 10 20.1 10 19s-.4-2-1-2c-.5 0-1 1.4-1 3.1" />
                                            <path
                                                d="M19.8 47.4C17.2 52.7 17.5 54 21 54c3.1 0 3.7-.8 2.1-2.5-.5-.6-1.1-2.5-1.3-4.4l-.3-3.4z" />
                                        </g>
                                        <path fill-opacity=".2"
                                            d="M8 15.4c0 .2.7.7 1.6 1 .8.3 1.2.2.9-.4-.6-1-2.5-1.4-2.5-.6" />
                                    </svg>

                                    <!-- Custom Red Circle + Slash Overlay -->
                                    <svg class="ban-custom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="46" stroke="red" stroke-width="8"
                                            fill="none" />
                                        <line x1="25" y1="25" x2="75" y2="75" stroke="red"
                                            stroke-width="10" stroke-linecap="round" />
                                    </svg>
                                </span>

                                <!-- No Smoking -->
                                <span class="icon-ban" title="No Smoking">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 64 64">
                                        <path
                                            d="M27.3 18c-1.7.7-2.3 1.8-2.3 4 0 3.5 2.6 5 9 5 2.7 0 4.2.6 4.9 1.7.7 1.3 2.7 1.9 7.3 2.3 5.6.4 6.4.8 7.6 3.2 1.7 3.7 5.2 3.8 5.2.2 0-3.9-4.8-8.1-10.3-8.9-4-.6-4.7-1.1-5.2-3.7-.4-1.8-1.6-3.3-3.2-3.9-3-1.1-10-1.1-13 .1M4.4 40.4c-.3.8-.4 2.5-.2 3.8.3 2.1.8 2.3 8.1 2.6l7.7.3V39h-7.5c-5.6 0-7.7.4-8.1 1.4M22 43v4h32v-8H22zm34 0c0 3.4.3 4.1 1.8 3.8 1.2-.2 1.7-1.3 1.7-3.8s-.5-3.6-1.7-3.8c-1.5-.3-1.8.4-1.8 3.8" />
                                    </svg>
                                    <!-- Custom Red Circle + Slash Overlay -->
                                    <svg class="ban-custom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="46" stroke="red" stroke-width="8"
                                            fill="none" />
                                        <line x1="25" y1="25" x2="75" y2="75"
                                            stroke="red" stroke-width="10" stroke-linecap="round" />
                                    </svg>
                                </span>
                            </div>
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
                        By signing this form, I acknowledge that I am fully responsible for any expenses incurred by the
                        above-named person, company, or association during the stay at Ulin Mahoni.
                    </em><br>
                    <span class="id-text">
                        Tamu dihimbau untuk tidak meninggalkan barang-barang berharga tanpa pengawasan di dalam
                        kamar.<br>
                        Untuk keamanan, silakan gunakan kotak penyimpanan (safe deposit box) yang tersedia untuk
                        menyimpan barang-barang berharga.<br>
                        Dengan menandatangani formulir ini, saya menyatakan bahwa saya sepenuhnya bertanggung jawab atas
                        biaya yang timbul oleh nama, perusahaan, atau asosiasi yang disebutkan di atas selama masa
                        menginap di Ulin Mahoni.
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
                    <span class="en-text">Prohibited strong smell fruit, pets & arms with penalty Rp.
                        500.000</span><br>
                    <span class="id-text">Dilarang membawa buah yang berbau menyengat, hewan peliharaan & senjata, akan
                        dikenakan denda Rp. 500.000</span>
                </span>
            </td>
            <td>
                <span class="en-text">Penalty for lost key card Rp. 50.000</span><br>
                <span class="id-text">Denda untuk kartu kunci yang hilang Rp. 50.000</span>
            </td>
            <td>
                <span class="en-text"><strong>Date</span> / Tanggal</strong><br>
                <span class="id-text">{{ $currentDate }}</span>
            </td>
        </tr>
    </table>
    
    <div style="margin-top: 12px; font-size: 9px; text-align: center; color: #555; line-height: 1.2;">
        <p>Dokumen ini dibuat secara elektronik oleh Ulin Mahoni dan sah tanpa tanda tangan basah.</p>
        <p>© {{ date('Y') }} Ulin Mahoni — Semua Hak Dilindungi Undang-Undang.</p>
    </div>
</body>

</html>
