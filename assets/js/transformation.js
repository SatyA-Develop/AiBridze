(() => {
  const section = document.querySelector('[data-transformation]');
  if (!section) return;

  const hero = document.querySelector('.hero');
  const heroStage = hero?.querySelector('[data-hero-stage]');
  const words = [...section.querySelectorAll('[data-transform-word]')];
  const slides = [...section.querySelectorAll('[data-section-testimonial]')];
  const testimonialContainer = section.querySelector('[data-section-testimonials]');
  let activeSlide = 0;
  let ticking = false;

  const updateTestimonialHeight = () => {
    if (!testimonialContainer) return;
    if (window.innerWidth > 700) {
      testimonialContainer.style.removeProperty('min-height');
      return;
    }

    const slideTop = 49;
    const tallestSlide = slides.reduce((height, slide) => Math.max(height, slide.scrollHeight), 0);
    testimonialContainer.style.minHeight = `${Math.max(300, slideTop + tallestSlide)}px`;
  };

  const updateWords = () => {
    const rect = section.getBoundingClientRect();
    const distance = Math.max(window.innerHeight, 1);
    const progress = Math.max(0, Math.min(1, (window.innerHeight - rect.top) / distance));
    const activeWords = Math.ceil(progress * words.length);
    words.forEach((word, index) => word.classList.toggle('is-filled', index < activeWords));

    if (hero && heroStage) {
      const sectionTop = Math.max(section.offsetTop, 1);
      const heroProgress = Math.max(0, Math.min(1, window.scrollY / sectionTop));
      const easedHeroProgress = 1 - Math.pow(1 - heroProgress, 2);
      const maximumInset = 0.70;
      heroStage.style.setProperty('--hero-scroll-scale', String(1 - (easedHeroProgress * maximumInset)));
      heroStage.style.setProperty('--hero-scroll-radius', `${easedHeroProgress * (window.innerWidth <= 700 ? 20 : 36)}px`);
      heroStage.dataset.scrollProgress = heroProgress.toFixed(3);
    }
    ticking = false;
  };

  const requestUpdate = () => {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(updateWords);
  };

  const showSlide = (index) => {
    if (!slides.length) return;
    activeSlide = index % slides.length;
    slides.forEach((slide, slideIndex) => {
      const active = slideIndex === activeSlide;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
    });
  };

  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', () => {
    requestUpdate();
    updateTestimonialHeight();
  }, { passive: true });
  updateWords();
  updateTestimonialHeight();
  document.fonts?.ready.then(updateTestimonialHeight);

  if (slides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    window.setInterval(() => showSlide(activeSlide + 1), 5200);
  }
})();
