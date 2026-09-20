<?php
// Load autoloader Composer PHP Native
require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// ==========================================
// KONEKSI DATABASE
// ==========================================
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "digivite";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$kode = $_GET['kode'] ?? '';

if (empty($kode)) {
    die("Kode tiket tidak ditemukan.");
}

$stmt = $conn->prepare("SELECT * FROM rsvps WHERE kode_tiket = ? LIMIT 1");
$stmt->bind_param("s", $kode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data tiket tidak valid.");
}

$data = $result->fetch_assoc();

// Generate QR Code lokal
$qrCode = new QrCode($data['kode_tiket']);
$writer = new PngWriter();
$qrResult = $writer->write($qrCode);
$qrBase64 = $qrResult->getDataUri();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Tiket Undangan - <?php echo htmlspecialchars($data['nama']); ?></title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    * { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }
    
    body { 
      background-color: #f3f4f6; 
      font-family: 'Plus Jakarta Sans', sans-serif; 
      display: flex; 
      flex-direction: column; 
      justify-content: center; 
      align-items: center; 
      min-height: 100vh; 
      padding: 16px; 
    }

    /* KARTU TIKET */
    .ticket-container { 
      width: 100%;
      max-width: 420px; 
      background: #ffffff; 
      border-radius: 20px; 
      border: 2px solid #065f46; 
      overflow: hidden; 
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); 
    }

    .ticket-header { 
      background: #065f46; 
      color: #ffffff; 
      padding: 24px 16px; 
      text-align: center; 
    }
    
    .ticket-header h1 { 
      font-family: 'Caveat', cursive; 
      font-size: 2.2rem; 
      font-weight: 700; 
      color: #ecfdf5; 
      line-height: 1.1; 
    }
    
    .ticket-header p { 
      font-size: 0.68rem; 
      letter-spacing: 2.5px; 
      margin-top: 6px; 
      text-transform: uppercase; 
      color: #a7f3d0; 
    }

    /* BODY DENGAN ALIGNMENT CENTER TOTAL */
    .ticket-body { 
      padding: 24px 20px; 
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center; 
    }
    
    .badge-wrapper {
      width: 100%;
      text-align: center;
      margin-bottom: 16px;
    }

    .badge-status { 
      display: inline-block; 
      background: #d1fae5; 
      color: #065f46; 
      font-size: 0.72rem; 
      font-weight: 700; 
      padding: 6px 16px; 
      border-radius: 50px; 
      text-transform: uppercase; 
      letter-spacing: 0.8px; 
    }

    .qr-box { 
      background: #f9fafb; 
      border: 2px dashed #e5e7eb; 
      border-radius: 16px; 
      padding: 16px; 
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 12px auto; 
      width: fit-content;
    }
    
    .qr-box img { 
      width: 170px; 
      height: 170px; 
      display: block; 
    }

    .ticket-code { 
      font-family: monospace; 
      font-size: 1.25rem; 
      font-weight: 700; 
      color: #065f46; 
      letter-spacing: 3px; 
      margin-bottom: 20px; 
      text-align: center;
      width: 100%;
    }

    /* RINCIAN INFORMASI TAMU */
    .ticket-info { 
      width: 100%;
      border-top: 2px dashed #f3f4f6; 
      padding-top: 16px; 
      display: grid; 
      grid-template-columns: 1fr 1fr; 
      gap: 12px; 
      text-align: left; 
    }

    .info-item label { 
      display: block; 
      font-size: 0.65rem; 
      color: #6b7280; 
      text-transform: uppercase; 
      font-weight: 700; 
      letter-spacing: 0.5px; 
    }
    
    .info-item span { 
      display: block; 
      font-size: 0.82rem; 
      font-weight: 600; 
      color: #111827; 
      margin-top: 2px; 
      word-break: break-word; 
    }

    .ticket-footer { 
      background: #f9fafb; 
      border-top: 1px solid #f3f4f6; 
      padding: 14px; 
      text-align: center; 
      font-size: 0.7rem; 
      color: #6b7280; 
      line-height: 1.4;
    }

    /* TOMBOL AKSI */
    .btn-actions { 
      margin-top: 20px; 
      width: 100%;
      max-width: 420px;
    }
    
    .btn { 
      background-color: #065f46; 
      color: #ffffff; 
      text-decoration: none; 
      padding: 14px 20px; 
      border-radius: 12px; 
      font-size: 0.9rem; 
      font-weight: 600; 
      display: block; 
      width: 100%;
      box-shadow: 0 4px 12px rgba(6, 95, 70, 0.2); 
      text-align: center;
    }
    
    .btn:active {
      background-color: #044e38;
    }

    /* PENYESUAIAN MOBILE HARMONIS */
    @media (max-width: 480px) {
      body {
        padding: 12px;
      }

      .ticket-header h1 {
        font-size: 1.8rem;
      }

      .qr-box img {
        width: 150px;
        height: 150px;
      }

      .ticket-code {
        font-size: 1.1rem;
      }
    }
  </style>
</head>
<body>

  <div class="ticket-container">
    <div class="ticket-header">
      <h1>Dohar & Wynona</h1>
      <p>Wedding Reception E-Ticket</p>
    </div>

    <div class="ticket-body">
      <!-- CONTAINER BADGE KHUSUS AGAR SIMETRIS DI TENGAH -->
      <div class="badge-wrapper">
        <span class="badge-status">KONFIRMASI: <?php echo strtoupper($data['kehadiran']); ?></span>
      </div>

      <!-- QR CODE CENTER -->
      <div class="qr-box">
        <img src="<?php echo $qrBase64; ?>" alt="QR Code Registrasi">
      </div>

      <!-- KODE TIKET CENTER -->
      <div class="ticket-code"><?php echo htmlspecialchars($data['kode_tiket']); ?></div>

      <!-- DETAILS -->
      <div class="ticket-info">
        <div class="info-item">
          <label>Nama Tamu</label>
          <span><?php echo htmlspecialchars($data['nama']); ?></span>
        </div>
        <div class="info-item">
          <label>Keluarga / Kerabat</label>
          <span><?php echo htmlspecialchars($data['keluarga']); ?></span>
        </div>
        <div class="info-item">
          <label>Jumlah Tamu</label>
          <span><?php echo htmlspecialchars($data['jumlah_tamu']); ?> Orang</span>
        </div>
        <div class="info-item">
          <label>No. Telepon</label>
          <span><?php echo htmlspecialchars($data['telp']); ?></span>
        </div>
      </div>
    </div>

    <div class="ticket-footer">
      <strong>Sabtu, 7 November 2026</strong> | Layar Resto, Manyar Surabaya
    </div>
  </div>

  <div class="btn-actions">
    <a href="download-pdf.php?kode=<?php echo urlencode($data['kode_tiket']); ?>" class="btn">
      Download PDF Tiket
    </a>
  </div>

</body>
</html>