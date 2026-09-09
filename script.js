document.addEventListener("DOMContentLoaded", function () {
  // Reveal Element saat di-scroll
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

  // Trigger saat pertama kali dimuat & saat event scroll berlangsung
  window.addEventListener("scroll", revealOnScroll);
  revealOnScroll();
});