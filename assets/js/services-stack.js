(() => {
  const section = document.querySelector('[data-services-stack]');
  if (!section) return;
  const cards = [...section.querySelectorAll('[data-service-stack-card]')];
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
  let frame = 0;
  const update = () => {
    frame = 0;
    const mobile = innerWidth <= 800;
    const headerHeight = document.querySelector('[data-site-header]')?.offsetHeight || 0;
    section.style.setProperty('--services-sticky-top', `${headerHeight + 16}px`);
    cards.forEach((card) => {
      // Translation is horizontal, so this vertical measurement stays stable.
      const top = card.getBoundingClientRect().top;
      const progress = innerWidth <= 700 || reducedMotion.matches ? 1 : Math.max(0, Math.min(1,
        (innerHeight * 0.85 - top) / (innerHeight * 0.6)));
      const eased = progress * progress * (3 - 2 * progress);
      card.style.setProperty('--card-x', `${(1 - eased) * (mobile ? 60 : 150)}px`);
      card.style.setProperty('--card-entry-opacity', String(0.12 + eased * 0.88));
    });
  };
  const requestUpdate = () => { if (!frame) frame = requestAnimationFrame(update); };
  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', requestUpdate, { passive: true });
  reducedMotion.addEventListener('change', requestUpdate);
  new ResizeObserver(requestUpdate).observe(section);
  document.fonts?.ready.then(requestUpdate);
  update();
})();
