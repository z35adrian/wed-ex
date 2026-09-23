<?php
// Memanggil file koneksi database terpisah
require_once "koneksi.php";

// ==========================================
// PROSES SIMPAN DATA RSVP & GENERATE QR
// ==========================================
$status_pesan = "";
$data_rsvp    = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama        = htmlspecialchars(strip_tags($_POST['nama'] ?? ''));
    $keluarga    = htmlspecialchars(strip_tags($_POST['keluarga'] ?? ''));
    $kehadiran   = htmlspecialchars(strip_tags($_POST['kehadiran'] ?? ''));
    $jumlah_tamu = htmlspecialchars(strip_tags($_POST['jumlah_tamu'] ?? '1'));
    $telp        = preg_replace('/\D/', '',$_POST['telp'] ?? '');
    $pesan       = htmlspecialchars(strip_tags($_POST['pesan'] ?? ''));

    if ($kehadiran === 'Tidak Hadir') {$jumlah_tamu = '0';
    }

    $valid = !empty($nama) && !empty($keluarga) && !empty($kehadiran) && !empty($telp);
    if ($kehadiran === 'Tidak Hadir') {$valid = $valid && !empty($pesan);
    }

    if ($valid) {
        // Generate Kode Registrasi Unik (Contoh: DHR-2026-X89A)
        $kode_tiket = "DHR-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        $stmt =$conn->prepare("INSERT INTO rsvps (link_id, kode_tiket, nama, keluarga, kehadiran, jumlah_tamu, telp, pesan, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("isssssss", $link_id,$kode_tiket, $nama,$keluarga, $kehadiran,$jumlah_tamu, $telp,$pesan);

        if ($stmt->execute()) {$status_pesan = "sukses";
            // Simpan data untuk ditampilkan pada tiket QR
            $data_rsvp = [
                'kode_tiket'  => $kode_tiket,
                'nama'        => $nama,
                'keluarga'    => $keluarga,
                'kehadiran'   => $kehadiran,
                'jumlah_tamu' => $jumlah_tamu
            ];
        } else {
            $status_pesan = "gagal";
        }
        $stmt->close();
    } else {
        $status_pesan = "kosong";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="no-scroll">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Undangan Dohar & Wynona</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- Penautan CSS dengan penambahan versi cache breaker -->
  <link rel="stylesheet" href="asset/style.css?v=1.3">
  
  <!-- html2pdf.js untuk export elemen HTML menjadi PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <style>
    .hidden { display: none !important; }
    .status-alert {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 12px;
      text-align: center;
      font-weight: 600;
    }
    .status-sukses { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .status-gagal { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-kosong { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 4px;
      text-align: left;
    }
    .form-group label {
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #444;
    }

    /* Style Kartu Tiket QR Code */
    .ticket-card {
      background: #ffffff;
      border: 2px dashed #065f46;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      color: #333;
      margin-top: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .ticket-card h3 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin-bottom: 5px;
      font-size: 1.1rem;
      color: #065f46;
    }
    .ticket-card p {
      font-size: 0.85rem;
      margin: 3px 0;
    }
    .qr-image {
      width: 150px;
      height: 150px;
      margin: 12px auto;
      display: block;
    }
    .btn-download {
      background-color: #065f46;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-download:hover {
      background-color: #044e38;
    }
  </style>
</head>
<body class="no-scroll">

  <!-- Floating Music Player -->
  <div id="music-container" class="music-player">
    <button id="music-btn" class="muted" type="button" aria-label="Nyalakan musik" title="Nyalakan musik">
      <svg id="icon-music" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 18V5l12-2v13"></path>
        <circle cx="6" cy="18" r="3"></circle>
        <circle cx="18" cy="16" r="3"></circle>
        <path class="speaker-slash" d="M4 4l16 16"></path>
      </svg>
    </button>
    <audio id="bg-music" loop preload="auto" muted>
      <source src="asset/music.mp3" type="audio/mpeg">
    </audio>
  </div>

  <main class="invitation-container">

    <!-- Cover Section -->
    <section class="card cover-section">
      <div class="image-wrapper">
        <img src="asset/image/11.jpg" alt="Cover Photo">
        <div class="overlay-text font-handwriting pos-center">
          <p class="txt-md">The Wedding of</p>
          <h1 class="txt-xl">Dohar & Wynona</h1>
          <p class="txt-xs font-sans" style="margin-top: 10px; letter-spacing: 1px;">SABTU, 7 NOVEMBER 2026</p>
          
          <button id="btn-open" class="btn-open-invitation font-sans">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Buka Undangan
          </button>
        </div>
      </div>
    </section>

    <!-- Section 1: Opening -->
    <section id="main-content" class="card reveal">
      <div class="image-wrapper">
        <img src="asset/image/1.jpg" alt="Cover Photo">
        <div class="overlay-text font-handwriting center-left">
          <p class="txt-lg">We're tying the knot</p>
          <p class="txt-md">Glad you're on the Guest List</p>
        </div>
      </div>
    </section>

    <!-- Section 2: Quote -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="asset/image/2.jpg" alt="Shadow Hand Photo">
        <div class="overlay-text quote-box">
          <p class="quote-text font-serif">
          "Demikianlah mereka bukan lagi dua, melainkan satu. Karena itu, apa yang telah dipersatukan Allah, tidak boleh diceraikan manusia."
          </p>
          <p class="quote-source font-serif">(Matius 19:6)</p>
        </div>
      </div>
    </section>

    <!-- Section 3: Bride & Groom -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="asset/image/3.jpg" alt="Couple Photo">
        <div class="overlay-text font-handwriting pos-left-mid">
          <br><br><br>
          <p class="txt-xl">Dohar Adi </p>
          <p class="txt-xl">Putra Manihuruk</p>
          <p class="txt-md">Groom</p>
        </div>
        <div class="overlay-text font-handwriting pos-right-mid">
          <p class="txt-xl">Wynona Benita</p>
          <p class="txt-md">Bride</p>
        </div>
      </div>
    </section>

    <!-- Section 4: Story (Love Story) -->
    <section class="card reveal story-section">
      <div class="image-wrapper">
        <img src="asset/image/13.jpeg" alt="Story Background Photo" class="img-story">
        <div class="story-overlay">
          <div class="story-content">
            <h2 class="font-serif story-main-title">LOVE STORY</h2>

            <!-- Item 1: Pertemuan -->
            <div class="story-item">
              <h3 class="font-serif story-sub-title">Sebuah Pertemuan</h3>
              <p class="story-text">
                Ada masa ketika kami berjalan dengan cerita kami masing-masing.<br>
                Pernah mencintai, pernah berharap, pernah terluka, dan pernah bertanya-tanya kepada Tuhan, “Kapan waktunya?”
              </p>
            </div>

            <div class="story-divider">
              <span class="divider-line"></span>
              <span class="divider-heart">♥</span>
              <span class="divider-line"></span>
            </div>

            <!-- Item 2: Perkenalan & Hubungan -->
            <div class="story-item">
              <h3 class="font-serif story-sub-title">Perjalanan Rasa</h3>
              <p class="story-text">
                Lalu, pada Maret 2025, melalui seorang teman, Tuhan mempertemukan kami.<br>
                Tidak ada yang terlalu istimewa pada awalnya. Hanya sebuah perkenalan sederhana. Namun dari perkenalan itu, perlahan tumbuh rasa yang membawa kami semakin dekat.<br><br>
                Mei 2025 menjadi awal perjalanan kami sebagai sepasang kekasih.<br>
                Kami belajar mengenal satu sama lain, menerima masa lalu masing-masing, dan menemukan bahwa terkadang jawaban dari doa datang bukan seperti yang kita bayangkan, tetapi jauh lebih indah dari yang kita harapkan.
              </p>
            </div>

            <div class="story-divider">
              <span class="divider-line"></span>
              <span class="divider-heart">♥</span>
              <span class="divider-line"></span>
            </div>

            <!-- Item 3: Lamaran -->
            <div class="story-item">
              <h3 class="font-serif story-sub-title">Lamaran</h3>
              <p class="story-text">
                Pada 25 Agustus 2026, kami memilih untuk melangkah lebih jauh melalui sebuah lamaran.<br>
                Bukan karena perjalanan kami selalu mudah, tetapi karena kami menemukan seseorang yang ingin kami pilih untuk setiap perjalanan setelahnya.
              </p>
            </div>

            <div class="story-divider">
              <span class="divider-line"></span>
              <span class="divider-heart">♥</span>
              <span class="divider-line"></span>
            </div>

            <!-- Item 4: Penutup -->
            <div class="story-item">
              <h3 class="font-serif story-sub-title">Takdir-Nya</h3>
              <p class="story-text">
                Mungkin kami terlambat bertemu.<br>
                Tetapi kami percaya, Tuhan tidak pernah terlambat mempertemukan kami.
              </p>
            </div>

          </div>
        </div>
      </div>
    </section>

    <!-- Section 5: Save the Date -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="asset/image/7.jpg" alt="Date Photo">
        <div class="overlay-text font-handwriting pos-top-center">
          <p class="txt-lg">Save the date!</p>
          <h2 class="date-header">07 / 11 / 2026</h2>
          <p class="txt-md">11.30 WIB Hingga Selesai</p>
        </div>
        <div class="overlay-text font-handwriting pos-bottom-center">
          <p class="txt-sm">Open Gate 11.00 WIB</p>
          <p class="txt-xs">Come on time so you won't miss a thing — we'd really appreciate it!</p>
        </div>
      </div>
    </section>

    <!-- Section 6: Location / Venue -->
    <section class="card parallax-section parallax-bg-6">
      <div class="overlay-text font-handwriting pos-center">
        <p class="txt-md">at</p>
        <h1 class="venue-title">Gedung Golkar</h1>
        <p class="txt-sm">Ciamis</p>
        <p class="txt-xs italic">"This will be an intimate event"</p>
        <a href="https://maps.app.goo.gl/5WPJHJQhrpkq5dERA" target="_blank" class="btn-maps font-sans">View Maps</a>
      </div>
    </section>

    <!-- Section 7: Wedding Gift -->
    <section class="card parallax-section parallax-bg-7">
      <div class="overlay-text font-handwriting pos-center">
        <h2 class="txt-xl">Wedding Gift</h2>
        <p class="txt-xs gift-desc">
          Your presence is truly the best gift, but if you'd like to send a little something, we provide a digital envelope to make it easier for you.
        </p>
        <!-- Tombol Buka Modal Gift -->
        <button type="button" id="btn-gift-modal" class="btn-gift font-sans">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"></path><path d="M2 7h20v5H2z"></path><path d="M12 22V7"></path><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
          Send Gift
        </button>
      </div>
    </section>

    <!-- Section 8: RSVP / KONFIRMASI KEHADIRAN & TIKET QR -->
    <section id="rsvp" class="card parallax-section parallax-bg-8 form-section">
      <div class="form-overlay">
        <div class="form-overlay-content">
          <h2 class="font-serif form-title" style="font-size: 1.8rem; margin-bottom: 4px;">Konfirmasi Kehadiran</h2>
          <p class="font-sans" style="font-size: 0.8rem; color: #555; text-align: center; margin-bottom: 12px;">Mohon konfirmasikan kehadiran Anda sebelum tanggal 7 November 2026.</p>
          
          <!-- Notifikasi Status Input PHP -->
          <?php if ($status_pesan == "sukses"): ?>
            <div class="status-alert status-sukses">
              ✓ Terima kasih! Konfirmasi kehadiran Anda berhasil disimpan.
            </div>

            <!-- TAMPILAN TIKET QR CODE SETELAH SUBMIT -->
            <?php if ($data_rsvp &&$data_rsvp['kehadiran'] === 'Hadir'): ?>
              <?php 
                $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($data_rsvp['kode_tiket']);
              ?>
              <div id="e-ticket" class="ticket-card">
                <h3>TIKET MASUK DIGITAL</h3>
                <p><strong><?php echo $data_rsvp['nama']; ?></strong></p>
                <p style="font-size: 0.75rem; color: #666;"><?php echo $data_rsvp['keluarga']; ?></p>
                
                <img src="<?php echo $qr_api; ?>" alt="QR Code Registrasi" class="qr-image">
                
                <p>Kode Unik: <strong><?php echo $data_rsvp['kode_tiket']; ?></strong></p>
                <p style="font-size: 0.75rem; color: #555;">Jumlah Tamu: <?php echo $data_rsvp['jumlah_tamu']; ?> Orang</p>
                <p style="font-size: 0.7rem; color: #888; margin-top: 8px;">*Tunjukkan QR Code ini pada penerima tamu di lokasi acara.</p>
              </div>

              <a href="cetak-tiket.php?kode=<?php echo $data_rsvp['kode_tiket']; ?>" target="_blank" class="btn-download" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Download PDF Tiket
              </a>
            <?php endif; ?>

          <?php elseif ($status_pesan == "gagal"): ?>
            <div class="status-alert status-gagal">
              ✕ Terjadi kesalahan teknis. Silakan coba lagi.
            </div>
          <?php elseif ($status_pesan == "kosong"): ?>
            <div class="status-alert status-kosong">
              ⚠️ Mohon lengkapi semua field yang wajib diisi.
            </div>
          <?php endif; ?>

          <!-- Form RSVP (Sembunyikan form jika sudah sukses hadir) -->
          <?php if (!($status_pesan == "sukses" && isset($data_rsvp['kehadiran']) &&$data_rsvp['kehadiran'] === 'Hadir')): ?>
          <form action="index.php#rsvp" method="POST">
            <div class="form-group">
              <label>Nama Lengkap</label>
              <input type="text" name="nama" required class="form-input">
            </div>

            <div class="form-group">
              <label>Keluarga/Kerabat</label>
              <input type="text" name="keluarga" required placeholder="Contoh: Keluarga Mempelai Pria" class="form-input">
            </div>

            <div class="form-group">
              <label>No. Telepon</label>
              <input type="tel" name="telp" required placeholder="08xxxxxxxxxx" class="form-input">
            </div>

            <div class="form-group">
              <label>Kehadiran</label>
              <select name="kehadiran" id="kehadiran" required class="form-input">
                <option value="Hadir">Ya, Saya Akan Datang</option>
                <option value="Tidak Hadir">Maaf, Saya Tidak Bisa Datang</option>
              </select>
            </div>

            <div class="form-group" id="jumlah-tamu-wrap">
              <label>Jumlah Tamu</label>
              <select name="jumlah_tamu" id="jumlah_tamu" class="form-input">
                <option value="1">1 Orang</option>
                <option value="2">2 Orang</option>
              </select>
            </div>

            <div class="form-group hidden" id="pesan-wrap">
              <label>Pesan untuk Mempelai</label>
              <textarea name="pesan" id="pesan" rows="3" placeholder="Tuliskan ucapan atau pesan Anda..." class="form-input" style="resize: none;"></textarea>
            </div>

            <button type="submit" class="btn-submit" style="margin-top: 8px;">Kirim Konfirmasi</button>
          </form>
          <?php endif; ?>

        </div>
      </div>
    </section>

    <!-- Section 9: Closing / Thank You -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="asset/image/9.jpg" alt="Closing Sky Photo">
        <div class="overlay-text font-handwriting pos-center">
          <h1 class="txt-xl">Thank You</h1>
        </div>
      </div>
    </section>

  </main>

  <!-- POP-UP MODAL WEDDING GIFT -->
  <div id="gift-modal" class="modal-gift-overlay">
    <div class="modal-gift-content">
      <button type="button" id="btn-close-gift" class="btn-close-modal" aria-label="Close">&times;</button>
      <h2 class="modal-title font-sans">WEDDING GIFT</h2>
      
      <!-- Card BCA -->
      <div class="bank-card">
        <div class="bank-logo">
          <span class="bank-name">BCA</span>
        </div>
        <p class="rekening-number" id="rek-bca">1380468201</p>
        <p class="rekening-owner">Dohar Adi Putra Manihuruk</p>
        <button type="button" class="btn-copy" onclick="copyToClipboard('rek-bca', this)">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          SALIN NOMOR REK
        </button>
      </div>

      <!-- Card Mandiri -->
      <div class="bank-card">
        <div class="bank-logo">
          <span class="bank-name">mandiri</span>
        </div>
        <p class="rekening-number" id="rek-mandiri">1300021234789</p>
        <p class="rekening-owner">Wynona Benita Elizabeth Gultom</p>
        <button type="button" class="btn-copy" onclick="copyToClipboard('rek-mandiri', this)">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          SALIN NOMOR REK
        </button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const htmlEl = document.documentElement;
      const bodyEl = document.body;
      const btnOpen = document.getElementById("btn-open");
      const mainContent = document.getElementById("main-content");
      
      const musicBtn = document.getElementById("music-btn");
      const musicContainer = document.getElementById("music-container");
      const bgMusic = document.getElementById("bg-music");
      let isPlaying = false;

      function updateMusicButtonState() {
        if (isPlaying) {
          musicBtn.classList.remove("muted");
          musicBtn.classList.add("playing");
          musicBtn.setAttribute("aria-label", "Matikan musik");
          musicBtn.setAttribute("title", "Matikan musik");
        } else {
          musicBtn.classList.remove("playing");
          musicBtn.classList.add("muted");
          musicBtn.setAttribute("aria-label", "Nyalakan musik");
          musicBtn.setAttribute("title", "Nyalakan musik");
        }
      }

      <?php if (!empty($status_pesan)): ?>
        htmlEl.classList.remove("no-scroll");
        bodyEl.classList.remove("no-scroll");
        musicContainer.classList.add("show");
      <?php endif; ?>

      function toggleMusic() {
        if (isPlaying) {
          bgMusic.pause();
          bgMusic.muted = true;
          isPlaying = false;
          updateMusicButtonState();
          return;
        }

        bgMusic.muted = false;
        bgMusic.play().then(() => {
          isPlaying = true;
          updateMusicButtonState();
        }).catch(err => {
          console.log("Autoplay ditolak:", err);
          bgMusic.muted = true;
          isPlaying = false;
          updateMusicButtonState();
        });
      }

      if (btnOpen) {
        btnOpen.addEventListener("click", function () {
          htmlEl.classList.remove("no-scroll");
          bodyEl.classList.remove("no-scroll");
          mainContent.scrollIntoView({ behavior: "smooth" });
          musicContainer.classList.add("show");
          if (!isPlaying) {
            toggleMusic();
          }
        });
      }

      if (musicBtn) {
        updateMusicButtonState();
        musicBtn.addEventListener("click", toggleMusic);
      }

      // Scroll Reveal
      const reveals = document.querySelectorAll(".reveal");
      function revealOnScroll() {
        const windowHeight = window.innerHeight;
        const elementVisible = 100;
        reveals.forEach((reveal) => {
          const elementTop = reveal.getBoundingClientRect().top;
          if (elementTop < windowHeight - elementVisible) {
            reveal.classList.add("active");
          }
        });
      }
      window.addEventListener("scroll", revealOnScroll);
      revealOnScroll();

      // Form Field Toggling
      const kehadiranSelect = document.getElementById("kehadiran");
      const jumlahTamuWrap = document.getElementById("jumlah-tamu-wrap");
      const pesanWrap = document.getElementById("pesan-wrap");
      const pesanInput = document.getElementById("pesan");

      function toggleFormFields() {
        if (kehadiranSelect && kehadiranSelect.value === "Tidak Hadir") {
          jumlahTamuWrap.classList.add("hidden");
          pesanWrap.classList.remove("hidden");
          pesanInput.setAttribute("required", "required");
        } else if (kehadiranSelect) {
          jumlahTamuWrap.classList.remove("hidden");
          pesanWrap.classList.add("hidden");
          pesanInput.removeAttribute("required");
        }
      }

      if (kehadiranSelect) {
        kehadiranSelect.addEventListener("change", toggleFormFields);
        toggleFormFields();
      }

      // Modal Gift Handlers
      const btnGiftModal = document.getElementById("btn-gift-modal");
      const giftModal = document.getElementById("gift-modal");
      const btnCloseGift = document.getElementById("btn-close-gift");

      if (btnGiftModal && giftModal) {
        btnGiftModal.addEventListener("click", function (e) {
          e.preventDefault();
          giftModal.classList.add("active");
        });

        if (btnCloseGift) {
          btnCloseGift.addEventListener("click", function () {
            giftModal.classList.remove("active");
          });
        }

        giftModal.addEventListener("click", function (e) {
          if (e.target === giftModal) {
            giftModal.classList.remove("active");
          }
        });
      }
    });

    // Salin Nomor Rekening
    function copyToClipboard(elementId, btnElement) {
      const textToCopy = document.getElementById(elementId).innerText;
      navigator.clipboard.writeText(textToCopy).then(() => {
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = `✓ TERSALIN`;
        btnElement.style.background = "#d1fae5";
        btnElement.style.color = "#065f46";

        setTimeout(() => {
          btnElement.innerHTML = originalText;
          btnElement.style.background = "#ffffff";
          btnElement.style.color = "#333333";
        }, 2000);
      }).catch(err => {
        console.error("Gagal menyalin: ", err);
      });
    }

    // Export PDF Tiket
    function downloadPDF() {
      const element = document.getElementById('e-ticket');
      const opt = {
        margin:       0.5,
        filename:     'Tiket_Undangan_Dohar_Wynona.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'in', format: 'a5', orientation: 'portrait' }
      };

      html2pdf().set(opt).from(element).save();
    }
  </script>
</body>
</html>