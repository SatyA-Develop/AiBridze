(() => {
  const section = document.querySelector('[data-process-section]');
  const track = section?.querySelector('[data-process-track]');
  if (!section || !track) return;

  const images = [...track.querySelectorAll('[data-process-image]')];
  let frame = 0;

  const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

  const render = () => {
    frame = 0;
    if (window.innerWidth <= 800) {
      track.style.removeProperty('transform');
      images.forEach((image) => {
        const media = image.closest('.process-card__media');
        if (!media) return;
        const mediaRect = media.getBoundingClientRect();
        const viewportCenter = window.innerHeight / 2;
        const mediaCenter = mediaRect.top + (mediaRect.height / 2);
        const imageProgress = clamp((viewportCenter - mediaCenter) / ((window.innerHeight + mediaRect.height) / 2), -1, 1);
        image.style.transform = `translate3d(0, ${imageProgress * 22}px, 0) scale(1.14)`;
      });
      return;
    }

    const rect = section.getBoundingClientRect();
    const range = Math.max(1, section.offsetHeight - window.innerHeight);
    const progress = clamp(-rect.top / range, 0, 1);
    const maxShift = Math.max(0, track.scrollWidth - track.parentElement.clientWidth);
    track.style.transform = `translate3d(${-maxShift * progress}px, 0, 0)`;
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
  render();
})();
