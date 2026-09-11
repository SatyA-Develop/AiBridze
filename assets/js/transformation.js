(() => {
  const section = document.querySelector('[data-transformation]');
  if (!section) return;

  const hero = document.querySelector('.hero');
  const heroStage = hero?.querySelector('[data-hero-stage]');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const header = document.querySelector('[data-site-header]');
  const words = [...section.querySelectorAll('[data-transform-word]')];
  const slides = [...section.querySelectorAll('[data-section-testimonial]')];
  const testimonialContainer = section.querySelector('[data-section-testimonials]');
  let activeSlide = 0;
  let ticking = false;

  // Fixed progressive backdrop blur, matching the reference's eight bands.
  // Keeping this outside the transformed stage lets its upper corners pass
  // through the blur while the rest of the image and copy remain sharp.
  const edgeBlur = document.createElement('div');
  edgeBlur.className = 'hero-edge-blur';
  edgeBlur.setAttribute('aria-hidden', 'true');
  for (let index = 0; index < 8; index += 1) {
    const layer = document.createElement('span');
    const start = index * 12.5;
    const stops = index < 6
      ? `transparent ${start}%, #000 ${start + 12.5}%, #000 ${start + 25}%, transparent ${start + 37.5}%`
      : index === 6 ? 'transparent 75%, #000 87.5%, #000 100%'
        : 'transparent 87.5%, #000 100%';
    layer.style.setProperty('--edge-blur', `${0.40625 * (2 ** index)}px`);
    layer.style.setProperty('--edge-mask', `linear-gradient(to top, ${stops})`);
    edgeBlur.append(layer);
  }
  if (heroStage) hero.after(edgeBlur);

  const updateTestimonialHeight = () => {
    if (!testimonialContainer) return;
    // Overlapping grid cells reserve space for the tallest quote at every width.
    testimonialContainer.style.removeProperty('min-height');
  };

  const updateWords = () => {
    const rect = section.getBoundingClientRect();
    const distance = Math.max(window.innerHeight, 1);
    const progress = Math.max(0, Math.min(1, (window.innerHeight - rect.top) / distance));
    const activeWords = reducedMotion.matches ? words.length : Math.ceil(progress * words.length);
    words.forEach((word, index) => word.classList.toggle('is-filled', index < activeWords));

    if (hero && heroStage) {
      const headerHeight = header?.offsetHeight || 0;
      const mobile = window.innerWidth <= 700;
      const stickyTop = !mobile ? headerHeight
        : Math.min(headerHeight, window.innerHeight - hero.offsetHeight);
      hero.style.setProperty('--hero-sticky-top', `${stickyTop}px`);
      // Mobile copy sits closer to the header: end the blur before its first
      // line while still softening the retreating image's upper corners.
      edgeBlur.style.top = `${Math.max(0, headerHeight - (mobile ? 24 : 0))}px`;
      edgeBlur.hidden = mobile || reducedMotion.matches || rect.top <= headerHeight;
      // The next section supplies the bottom wipe. Only retreat the hero's
      // top and sides; never animate its height or collapse its contents.
      const heroProgress = mobile || reducedMotion.matches ? 0 : Math.max(0, Math.min(1,
        window.scrollY / Math.max(window.innerHeight - headerHeight, 1)));
      // Reach 80% scale after 60vh, then hold while the next section covers it.
      const shrink = Math.min(0.2, heroProgress / 3);
      heroStage.style.setProperty('--hero-scroll-scale', String(1 - shrink));
      // Treat the header bottom as the top of the hero viewport.
      const stoppedInset = (window.innerHeight - headerHeight) * 0.1 + 100;
      const retreat = stoppedInset * (shrink / 0.2);
      heroStage.style.setProperty('--hero-scroll-y', `${retreat}px`);
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
  reducedMotion.addEventListener('change', requestUpdate);
  window.addEventListener('resize', () => {
    requestUpdate();
    updateTestimonialHeight();
  }, { passive: true });
  updateWords();
  updateTestimonialHeight();
  document.fonts?.ready.then(() => {
    updateTestimonialHeight();
    requestUpdate();
  });

  if (slides.length > 1) window.aibridzeAutoplay(testimonialContainer, () => showSlide(activeSlide + 1));
})();

(() => {
  const video = document.querySelector('.hero__video[data-hero-src]');
  if (!video) return;
  video.muted = true;
  const play = () => {
    if (!video.hasAttribute('src')) {
      video.src = video.dataset.heroSrc;
      video.load();
    }
    video.play().catch(() => {});
  };
  if (!('IntersectionObserver' in window)) {
    play();
    return;
  }
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !document.hidden) play();
      else video.pause();
    });
  }, { rootMargin: '150px 0px', threshold: 0.01 });
  observer.observe(video);
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) video.pause();
    else {
      const rect = video.getBoundingClientRect();
      if (rect.bottom > -150 && rect.top < window.innerHeight + 150) play();
    }
  });
})();
