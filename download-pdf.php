<?php
// Load autoloader dari folder vendor PHP Native
require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Dompdf\Dompdf;
use Dompdf\Options;

// ==========================================
// 1. KONEKSI KE DATABASE LARAGON
// ==========================================
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "digivite";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ==========================================
// 2. AMBIL DATA DARI PARAMETER URL
// ==========================================
$kode = $_GET['kode'] ?? '';

if (empty($kode)) {
    die("Kode tiket tidak ditemukan.");
}

$stmt = $conn->prepare("SELECT * FROM rsvps WHERE kode_tiket = ? LIMIT 1");
$stmt->bind_param("s", $kode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data tiket tidak ditemukan di database.");
}

$data = $result->fetch_assoc();

// ==========================================
// 3. GENERATE QR CODE LOKAL (ENDROID QR)
// ==========================================
$qrCode = new QrCode($data['kode_tiket']);
$writer = new PngWriter();
$qrResult = $writer->write($qrCode);
$qrBase64 = $qrResult->getDataUri();

// ==========================================
// 4. TEMPLATE HTML DENGAN TABEL PURA (COMPATIBLE DOMPDF)
// ==========================================
$namaTamu  = htmlspecialchars($data['nama']);
$keluarga  = htmlspecialchars($data['keluarga']);
$kehadiran = strtoupper(htmlspecialchars($data['kehadiran']));
$jumlah    = htmlspecialchars($data['jumlah_tamu']);
$telp      = htmlspecialchars($data['telp']);
$kodeTiket = htmlspecialchars($data['kode_tiket']);

$html = "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <style>
    @page {
      margin: 15px;
    }
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      color: #1f2937;
      margin: 0;
      padding: 0;
      background-color: #ffffff;
    }
    .ticket-card {
      width: 100%;
      border: 2px solid #065f46;
      border-radius: 12px;
      overflow: hidden;
      border-collapse: collapse;
    }
    .header-cell {
      background-color: #065f46;
      color: #ffffff;
      padding: 18px 15px;
      text-align: center;
    }
    .header-title {
      font-size: 22px;
      font-weight: bold;
      color: #ecfdf5;
      margin: 0;
    }
    .header-subtitle {
      font-size: 9px;
      letter-spacing: 2px;
      color: #a7f3d0;
      margin-top: 4px;
      text-transform: uppercase;
    }
    .body-cell {
      padding: 20px 15px;
      text-align: center;
      background-color: #ffffff;
    }
    .badge-status {
      background-color: #d1fae5;
      color: #065f46;
      font-size: 10px;
      font-weight: bold;
      padding: 5px 14px;
      border-radius: 15px;
      text-transform: uppercase;
      display: inline-block;
      margin-bottom: 12px;
    }
    .qr-container {
      width: 160px;
      height: 160px;
      margin: 0 auto 10px auto;
      border: 2px dashed #d1d5db;
      border-radius: 10px;
      padding: 8px;
      background-color: #f9fafb;
    }
    .qr-image {
      width: 160px;
      height: 160px;
    }
    .ticket-code {
      font-family: 'Courier', monospace;
      font-size: 16px;
      font-weight: bold;
      color: #065f46;
      letter-spacing: 2px;
      margin-top: 8px;
      margin-bottom: 15px;
    }
    .info-table {
      width: 100%;
      border-top: 1px dashed #e5e7eb;
      padding-top: 12px;
      text-align: left;
    }
    .info-label {
      font-size: 8px;
      color: #6b7280;
      text-transform: uppercase;
      font-weight: bold;
      margin-bottom: 2px;
    }
    .info-value {
      font-size: 11px;
      color: #111827;
      font-weight: bold;
    }
    .footer-cell {
      background-color: #f9fafb;
      border-top: 1px solid #f3f4f6;
      padding: 10px;
      text-align: center;
      font-size: 8px;
      color: #6b7280;
    }
  </style>
</head>
<body>

  <table class='ticket-card' cellpadding='0' cellspacing='0'>
    <!-- HEADER -->
    <tr>
      <td class='header-cell'>
        <div class='header-title'>Dohar & Wynona</div>
        <div class='header-subtitle'>WEDDING RECEPTION E-TICKET</div>
      </td>
    </tr>

    <!-- BODY CONTENT -->
    <tr>
      <td class='body-cell'>
        <!-- BADGE STATUS -->
        <div>
          <span class='badge-status'>KONFIRMASI: {$kehadiran}</span>
        </div>

        <!-- QR CODE -->
        <div class='qr-container'>
          <img src='{$qrBase64}' class='qr-image' />
        </div>

        <!-- KODE TIKET -->
        <div class='ticket-code'>{$kodeTiket}</div>

        <!-- DETAIL INFORMASI TAMU -->
        <table class='info-table' cellpadding='0' cellspacing='0'>
          <tr>
            <td width='50%' style='padding-bottom: 10px; vertical-align: top;'>
              <div class='info-label'>NAMA TAMU</div>
              <div class='info-value'>{$namaTamu}</div>
            </td>
            <td width='50%' style='padding-bottom: 10px; vertical-align: top;'>
              <div class='info-label'>KELUARGA / KERABAT</div>
              <div class='info-value'>{$keluarga}</div>
            </td>
          </tr>
          <tr>
            <td width='50%' style='vertical-align: top;'>
              <div class='info-label'>JUMLAH TAMU</div>
              <div class='info-value'>{$jumlah} Orang</div>
            </td>
            <td width='50%' style='vertical-align: top;'>
              <div class='info-label'>NO. TELEPON</div>
              <div class='info-value'>{$telp}</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- FOOTER -->
    <tr>
      <td class='footer-cell'>
        <strong>Sabtu, 7 November 2026</strong> | Layar Resto, Manyar Surabaya
      </td>
    </tr>
  </table>

</body>
</html>
";

// ==========================================
// 5. RENDER DOMPDF & DOWNLOAD
// ==========================================
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();

// Paksa unduh ke riwayat browser
$filename = "Tiket_Undangan_" . preg_replace('/[^a-zA-Z0-9]/', '_', $data['nama']) . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit;