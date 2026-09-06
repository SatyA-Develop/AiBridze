(() => {
  const section = document.querySelector('[data-portfolio-stack]');
  if (!section) return;
  const region = section.querySelector('[data-portfolio-scroll-region]');
  const viewport = section.querySelector('[data-portfolio-viewport]');
  const cards = [...section.querySelectorAll('[data-portfolio-card]')];
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
  if (!region || !viewport || !cards.length) return;
  let frame = 0, active = -1, step = 0, top = 0, animated = false;
  const clamp = (value) => Math.max(0, Math.min(1, value));
  const select = (index) => {
    if (index === active) return;
    active = index;
    cards.forEach((card, i) => {
      card.classList.toggle('is-active', i === index);
      card.classList.toggle('is-before', i < index);
      card.inert = animated && i !== index;
      card.setAttribute('aria-hidden', String(animated && i !== index));
    });

  };
  const update = () => {
    frame = 0;
    if (!animated) return;
    const raw = Math.max(0, Math.min(cards.length - 1, (top - region.getBoundingClientRect().top) / step));
    const current = Math.min(cards.length - 1, Math.floor(raw));
    const phase = raw - current;
    // Text changes early; the sharp, opaque image wipe spans most of the interval.
    select(Math.min(cards.length - 1, current + (phase >= 0.18 ? 1 : 0)));
    cards.forEach((card, index) => {
      const reveal = index === 0 ? 1 : clamp((raw - index + 1 - 0.12) / 0.78);
      card.style.setProperty('--portfolio-image-clip', `${(1 - reveal) * 100}%`);
      card.style.setProperty('--portfolio-image-y', `${(1 - clamp(raw - index + 1)) * 4}%`);
    });
  };
  const requestUpdate = () => { if (!frame) frame = requestAnimationFrame(update); };
  const measure = () => {
    animated = innerWidth > 1000 && innerHeight > 680 && !reducedMotion.matches && cards.length > 1;
    section.classList.toggle('is-scroll-animated', animated);
    top = (document.querySelector('[data-site-header]')?.offsetHeight || 0) + 16;
    step = Math.max(760, innerHeight * 1.25);
    viewport.style.setProperty('--portfolio-top', `${top}px`);
    if (animated) region.style.height = `${viewport.offsetHeight + step * (cards.length - 1)}px`;
    else region.style.removeProperty('height');
    active = -1;
    select(0);
    update();
  };
  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', measure, { passive: true });
  reducedMotion.addEventListener('change', measure);
  document.fonts?.ready.then(measure);
  measure();
})();
