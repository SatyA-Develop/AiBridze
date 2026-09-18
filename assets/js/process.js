(() => {
  const section = document.querySelector('[data-process-section]');
  const track = section?.querySelector('[data-process-track]');
  if (!section || !track) return;
  const layout = section.querySelector('.process-section__layout');
  const sticky = section.querySelector('.process-section__sticky');
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');

  const images = [...track.querySelectorAll('[data-process-image]')];
  let frame = 0;

  const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

  const render = () => {
    frame = 0;
    // Center the complete timeline when it fits, otherwise leave a trailing gutter.
    const endSpace = Math.max(40, (sticky.clientWidth - track.scrollWidth) / 2);
    layout.style.setProperty('--process-end-space', `${endSpace}px`);
    if (window.innerWidth <= 1100 || reducedMotion.matches) {
      layout.style.removeProperty('transform');
      images.forEach((image) => {
        const media = image.closest('.process-card__media');
        if (!media) return;
        const mediaRect = media.getBoundingClientRect();
        const viewportCenter = window.innerHeight / 2;
        const mediaCenter = mediaRect.top + (mediaRect.height / 2);
        const imageProgress = clamp((viewportCenter - mediaCenter) / ((window.innerHeight + mediaRect.height) / 2), -1, 1);
        image.style.transform = reducedMotion.matches ? 'none' : `translate3d(0, ${imageProgress * 22}px, 0) scale(1.14)`;
      });
      return;
    }

    const rect = section.getBoundingClientRect();
    const headerHeight = document.querySelector('[data-site-header]')?.offsetHeight || 0;
    section.style.setProperty('--process-sticky-top', `${headerHeight}px`);
    const range = Math.max(1, section.offsetHeight - sticky.offsetHeight);
    const progress = clamp((headerHeight - rect.top) / range, 0, 1);
    const maxShift = Math.max(0, layout.scrollWidth - sticky.clientWidth);
    layout.style.transform = `translate3d(${-maxShift * progress}px, 0, 0)`;
    images.forEach((image) => {
      const imageX = (0.5 - progress) * 90;
      image.style.transform = `translate3d(${imageX}px, 0, 0) scale(1.34)`;
    });
  };

  const requestRender = () => {
    if (frame) return;
    frame = requestAnimationFrame(render);
  };

  window.addEventListener('scroll', requestRender, { passive: true });
  window.addEventListener('resize', requestRender, { passive: true });
  reducedMotion.addEventListener('change', requestRender);
  new ResizeObserver(requestRender).observe(layout);
  document.fonts?.ready.then(requestRender);
  render();
})();
