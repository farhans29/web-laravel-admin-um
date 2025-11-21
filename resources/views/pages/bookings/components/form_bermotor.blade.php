<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran Kendaraan Bermotor</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px;
        }

        .no-border {
            border: none !important;
        }

        .checkbox-box {
            border: 1px solid #000;
            width: 12px;
            height: 12px;
            display: inline-block;
            margin-right: 5px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .signature-box {
            height: 80px;
            border: 1px dashed #000;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="title">
        FORM PENDAFTARAN KENDARAAN BERMOTOR <br>
        PENYEWA KOST ULIN MAHONI
    </div>

    <!-- Informasi Penyewa -->
    <div class="section-title">Informasi Penyewa</div>

    <table>
        <tr>
            <th width="40%">Name (Mr/Mrs/Miss) / Nama (Tn/Ny/Nona)</th>
            <th width="30%">Nationality / Kewarganegaraan</th>
            <th width="30%">Date of Birth / Tanggal Lahir</th>
        </tr>
        <tr>
            <td>Hadrian</td>
            <td>Indonesia</td>
            <td>-</td>
        </tr>
    </table>

    <!-- Informasi Kendaraan -->
    <div class="section-title">Informasi Kendaraan</div>

    <table>
        <tr>
            <th width="40%">Jenis Kendaraan</th>
            <th width="30%">Merk / Tipe</th>
            <th width="30%">Warna Kendaraan</th>
        </tr>
        <tr>
            <td>Hadiran</td>
            <td>-</td>
            <td>-</td>
        </tr>

        <tr>
            <th>STNK atas nama</th>
            <th colspan="2">Plat Nomor</th>
        </tr>
        <tr>
            <td>-</td>
            <td colspan="2">-</td>
        </tr>
    </table>

    <div class="section-title">Lampiran Wajib</div>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border"><span class="checkbox-box"></span> Fotokopi / Foto STNK</td>
        </tr>
        <tr class="no-border">
            <td class="no-border"><span class="checkbox-box"></span> Foto Kendaraan</td>
        </tr>
        <tr class="no-border">
            <td class="no-border"><span class="checkbox-box"></span> Fotokopi KTP Penyewa</td>
        </tr>
    </table>

    <div class="section-title">Pernyataan</div>

    <p>
        Saya yang bertanda tangan di bawah ini menyatakan bahwa data yang saya isi adalah benar.
        Saya juga bersedia mematuhi aturan parkir dan ketertiban kendaraan yang berlaku di lingkungan kost.
    </p>

    <p>
        Form ini berlaku <strong>hanya untuk satu kendaraan.</strong> Jika penghuni memiliki lebih dari satu kendaraan
        maka akan dikenakan charge sebesar:
        <br>1. Mobil: Rp. 250.000/bulan
        <br>2. Motor: Rp. 100.000/bulan
    </p>

    <table>
        <tr>
            <td width="50%">
                Tanggal pengisian: _____ / ______ / _____ <br><br>
                Tanda Tangan Penyewa:
                <div class="signature-box"></div>
            </td>

            <td width="50%">
                Untuk Pengelola Kost: <br><br>
                Disetujui oleh: __________________ <br><br>
                Tanggal Verifikasi: _____ / ______ / _____
            </td>
        </tr>
    </table>

</body>

</html>
