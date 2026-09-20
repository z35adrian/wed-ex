document.addEventListener("DOMContentLoaded", function () {
  // 1. Reveal Element saat di-scroll
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

  // Trigger scroll awal
  window.addEventListener("scroll", revealOnScroll);
  revealOnScroll();

  // 2. Logic Pop-Up Modal Wedding Gift
  const btnGiftModal = document.getElementById("btn-gift-modal");
  const giftModal = document.getElementById("gift-modal");
  const btnCloseGift = document.getElementById("btn-close-gift");

  if (btnGiftModal && giftModal) {
    // Buka Modal
    btnGiftModal.addEventListener("click", function () {
      giftModal.classList.add("active");
    });

    // Tutup Modal via Tombol X
    if (btnCloseGift) {
      btnCloseGift.addEventListener("click", function () {
        giftModal.classList.remove("active");
      });
    }

    // Tutup Modal jika klik area hitam di luar modal
    giftModal.addEventListener("click", function (e) {
      if (e.target === giftModal) {
        giftModal.classList.remove("active");
      }
    });
  }
});

// 3. Fungsi Salin Nomor Rekening
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