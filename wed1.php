<!DOCTYPE html>
<html lang="id" class="no-scroll">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Undangan Dohar & Wynona</title>
  
  <!-- Font Handwriting (Caveat) & Sans-Serif (Plus Jakarta Sans) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="style.css?v=1.1">
  <!-- <link rel="stylesheet" href="style.css"> -->
  
  <style>
    /* Utility tambahan untuk form */
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
  </style>
</head>
<body class="no-scroll">

  <!-- Floating Music Player (Pojok Kiri Bawah) -->
  <div id="music-container" class="music-player">
    <button id="music-btn" aria-label="Toggle Music">
      <svg id="icon-music" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 18V5l12-2v13"></path>
        <circle cx="6" cy="18" r="3"></circle>
        <circle cx="18" cy="16" r="3"></circle>
      </svg>
    </button>
    <!-- Ganti URL di bawah dengan file musik Anda (Contoh MP3) -->
    <audio id="bg-music" loop preload="auto">
      <source src="music.mp3" type="audio/mpeg">
    </audio>
  </div>

  <main class="invitation-container">

    <!-- Section 0: Cover Section (Buka Undangan) -->
    <section class="card cover-section">
      <div class="image-wrapper">
        <img src="image/11.jpg" alt="Cover Photo">
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
        <img src="image/1.jpg" alt="Cover Photo">
        <div class="overlay-text font-handwriting center-left">
          <p class="txt-lg">We're tying the knot</p>
          <p class="txt-md">Glad you're on the Guest List</p>
        </div>
      </div>
    </section>

    <!-- Section 2: Quote -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="image/2.jpg" alt="Shadow Hand Photo">
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
        <img src="image/3.jpg" alt="Couple Photo">
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

    <!-- Section 4: Parents Info -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="https://images.unsplash.com/photo-1469371670807-013ccf25f16a?auto=format&fit=crop&w=800&q=80" alt="Street View Photo">
        <div class="overlay-text font-handwriting pos-top-left">
          <p class="txt-sm">Son of</p>
          <p class="txt-md">Iqbal Hidayat & Hamsiah</p>
        </div>
        <div class="overlay-text font-handwriting pos-top-right">
          <p class="txt-sm">Daughter of</p>
          <p class="txt-md">Setyo Nugroho & Dian Farida Anies</p>
        </div>
      </div>
    </section>

    <!-- Section 5: Save the Date -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="image/5.jpg" alt="Date Photo">
        <div class="overlay-text font-handwriting pos-top-center">
          <p class="txt-lg">Save the date!</p>
          <h2 class="date-header">20 / 09 / 2025</h2>
          <p class="txt-md">10.00 - 13.00 WIB</p>
        </div>
        <div class="overlay-text font-handwriting pos-bottom-center">
          <p class="txt-sm">Open Gate 09.30 WIB</p>
          <p class="txt-xs">Come on time so you won't miss a thing — we'd really appreciate it!</p>
        </div>
      </div>
    </section>

    <!-- PARALLAX SECTIONS (Section 6, 7, & 8) -->

    <!-- Section 6: Location / Venue -->
    <section class="card parallax-section parallax-bg-6">
      <div class="overlay-text font-handwriting pos-center">
        <p class="txt-md">at</p>
        <h1 class="venue-title">Layar Resto</h1>
        <p class="txt-sm">Manyar Surabaya</p>
        <p class="txt-xs italic">"This will be an intimate event"</p>
        <a href="#" class="btn-maps font-sans">View Maps</a>
      </div>
    </section>

    <!-- Section 7: Wedding Gift -->
    <section class="card parallax-section parallax-bg-7">
      <div class="overlay-text font-handwriting pos-center">
        <h2 class="txt-xl">Wedding Gift</h2>
        <p class="txt-xs gift-desc">
          Your presence is truly the best gift, but if you'd like to send a little something, we provide a digital envelope to make it easier for you.
        </p>
      </div>
    </section>

    <!-- Section 8: RSVP / KONFIRMASI KEHADIRAN (PARALLAX) -->
    <section id="rsvp" class="card parallax-section parallax-bg-8 form-section">
      <div class="form-overlay">
        <div class="form-overlay-content">
          <h2 class="font-serif form-title" style="font-size: 1.8rem; margin-bottom: 4px;">Konfirmasi Kehadiran</h2>
          <p class="font-sans" style="font-size: 0.8rem; color: #555; text-align: center; margin-bottom: 12px;">Mohon konfirmasikan kehadiran Anda sebelum tanggal 10 Juli 2026.</p>

          <!-- Form RSVP -->
          <form action="#rsvp" method="POST">
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
        </div>
      </div>
    </section>

    <!-- Section 9: Closing / Thank You -->
    <section class="card reveal">
      <div class="image-wrapper">
        <img src="image/9.jpg" alt="Closing Sky Photo">
        <div class="overlay-text font-handwriting pos-center">
          <h1 class="txt-xl">Thank You</h1>
        </div>
      </div>
    </section>

  </main>

  <!-- JavaScript Toggle untuk Form RSVP, Musik & Smooth Scroll -->
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

      // Function Play/Pause Audio
      function toggleMusic() {
        if (isPlaying) {
          bgMusic.pause();
          musicBtn.classList.remove("playing");
        } else {
          bgMusic.play().then(() => {
            musicBtn.classList.add("playing");
          }).catch(err => console.log("Autoplay ditolak:", err));
        }
        isPlaying = !isPlaying;
      }

      // Event Klik Tombol "Buka Undangan"
      if (btnOpen) {
        btnOpen.addEventListener("click", function () {
          // 1. Buka Akses Scroll
          htmlEl.classList.remove("no-scroll");
          bodyEl.classList.remove("no-scroll");

          // 2. Smooth Scroll ke Section Berikutnya
          mainContent.scrollIntoView({ behavior: "smooth" });

          // 3. Tampilkan Tombol Musik & Putar Lagu
          musicContainer.classList.add("show");
          if (!isPlaying) {
            toggleMusic();
          }
        });
      }

      // Manual Control Tombol Musik Pojok Kiri Bawah
      musicBtn.addEventListener("click", toggleMusic);

      // 4. Scroll Reveal
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

      // 5. Logic Dynamic Form (Hadir vs Tidak Hadir)
      const kehadiranSelect = document.getElementById("kehadiran");
      const jumlahTamuWrap = document.getElementById("jumlah-tamu-wrap");
      const pesanWrap = document.getElementById("pesan-wrap");
      const pesanInput = document.getElementById("pesan");

      function toggleFormFields() {
        if (kehadiranSelect.value === "Tidak Hadir") {
          jumlahTamuWrap.classList.add("hidden");
          pesanWrap.classList.remove("hidden");
          pesanInput.setAttribute("required", "required");
        } else {
          jumlahTamuWrap.classList.remove("hidden");
          pesanWrap.classList.add("hidden");
          pesanInput.removeAttribute("required");
        }
      }

      if (kehadiranSelect) {
        kehadiranSelect.addEventListener("change", toggleFormFields);
        toggleFormFields();
      }
    });
  </script>
</body>
</html>