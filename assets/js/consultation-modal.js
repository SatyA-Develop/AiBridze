(() => {
  const modal = document.querySelector('[data-consultation-modal]');
  if (!modal) return;

  const dialog = modal.querySelector('.consultation-modal__dialog');
  const closeButtons = modal.querySelectorAll('[data-consultation-close]');
  let opener = null;
  let closeTimer = null;
  let testimonialTimer = null;
  let testimonialIndex = 0;
  const testimonialSlides = [...modal.querySelectorAll('[data-testimonial-slide]')];
  const testimonialDots = [...modal.querySelectorAll('[data-testimonial-dot]')];

  const showTestimonial = (index) => {
    if (!testimonialSlides.length) return;
    testimonialIndex = (index + testimonialSlides.length) % testimonialSlides.length;
    testimonialSlides.forEach((slide, slideIndex) => {
      const active = slideIndex === testimonialIndex;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
    });
    testimonialDots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === testimonialIndex));
  };

  const startTestimonials = () => {
    if (testimonialTimer || testimonialSlides.length < 2) return;
    testimonialTimer = true;
    window.aibridzeAutoplay(modal.querySelector('[data-testimonial-slider]'), () => {
      if (modal.classList.contains('is-open')) showTestimonial(testimonialIndex + 1);
    });
  };

  const focusable = () => [...dialog.querySelectorAll('button, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])')]
    .filter((item) => !item.disabled && item.offsetParent !== null);

  const open = (trigger = null) => {
    clearTimeout(closeTimer);
    opener = trigger || document.activeElement;
    modal.removeAttribute('inert');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('has-consultation-modal');
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        modal.classList.add('is-open');
        dialog.focus({ preventScroll: true });
        startTestimonials();
      });
    });
  };

  const close = () => {
    modal.classList.remove('is-open');
    document.body.classList.remove('has-consultation-modal');
    closeTimer = window.setTimeout(() => {
      modal.setAttribute('aria-hidden', 'true');
      modal.setAttribute('inert', '');
      opener?.focus?.({ preventScroll: true });
    }, 420);
  };

  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('a[href$="#consultation"], [data-consultation-open]');
    if (!trigger) return;
    event.preventDefault();
    open(trigger);
  });

  closeButtons.forEach((button) => button.addEventListener('click', close));
  testimonialDots.forEach((dot) => dot.addEventListener('click', () => {
    showTestimonial(Number(dot.dataset.testimonialDot));
    startTestimonials();
  }));
  document.addEventListener('keydown', (event) => {
    if (!modal.classList.contains('is-open')) return;
    if (event.key === 'Escape') close();
    if (event.key !== 'Tab') return;
    const items = focusable();
    if (!items.length) return;
    const first = items[0];
    const last = items[items.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });

  modal.setAttribute('inert', '');
  if (modal.dataset.formStatus) open();
})();
