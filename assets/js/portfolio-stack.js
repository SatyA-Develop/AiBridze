(() => {
  const section = document.querySelector('[data-portfolio-stack]');
  if (!section) return;
  const region = section.querySelector('[data-portfolio-scroll-region]');
  const viewport = section.querySelector('[data-portfolio-viewport]');
  const cards = [...section.querySelectorAll('[data-portfolio-card]')];
  if (!region || !viewport || !cards.length) return;
  let frame = 0;

  const update = () => {
    frame = 0;
    const mobile = window.innerWidth <= 800;
    const top = mobile ? 24 : 72;
    const viewportHeight = viewport.offsetHeight;
    const stepDistance = Math.max(mobile ? 440 : 680, window.innerHeight * (mobile ? 0.72 : 1.05));
    region.style.height = `${viewportHeight + (stepDistance * (cards.length - 1))}px`;
    viewport.style.top = `${top}px`;

    const regionTop = region.getBoundingClientRect().top;
    const travelled = Math.max(0, Math.min(stepDistance * (cards.length - 1), top - regionTop));
    const rawStep = travelled / stepDistance;
    const activeIndex = Math.min(cards.length - 1, Math.floor(rawStep));
    const localProgress = activeIndex === cards.length - 1 ? 0 : rawStep - activeIndex;

    cards.forEach((card, index) => {
      let reveal = 1;
      let layer = 1;
      if (index === activeIndex) {
        reveal = 1;
        layer = 20;
      } else if (index === activeIndex + 1) {
        reveal = localProgress;
        layer = 21;
      } else if (index > activeIndex + 1) {
        reveal = 0;
        layer = 2;
      }

      card.style.setProperty('--portfolio-image-clip', `${(1 - reveal) * 100}%`);
      card.style.setProperty('--portfolio-image-opacity', String(index === activeIndex + 1 ? 0.48 + (localProgress * 0.52) : reveal));
      card.style.setProperty('--portfolio-edge-opacity', String(index === activeIndex + 1 ? 4 * localProgress * (1 - localProgress) : 0));
      card.style.setProperty('--portfolio-layer', String(layer));
      card.classList.toggle('is-active', index === activeIndex);

      const contentItems = [...card.querySelector('.portfolio-card__content').children];
      contentItems.forEach((item, itemIndex) => {
        const animatedItem = item.firstElementChild;
        if (!animatedItem) return;
        let itemOpacity = 0;
        let itemY = 18;
		const itemDuration = 1 / contentItems.length;
		const itemStart = itemIndex * itemDuration;
		const itemProgress = Math.max(0, Math.min(1, (localProgress - itemStart) / itemDuration));

        if (index === activeIndex) {
          itemOpacity = 1 - itemProgress;
          itemY = -24 * itemProgress;
        } else if (index === activeIndex + 1) {
          itemOpacity = itemProgress;
          itemY = 24 * (1 - itemProgress);
        }

        animatedItem.style.setProperty('--portfolio-item-opacity', String(itemOpacity));
        animatedItem.style.setProperty('--portfolio-item-y', `${itemY}px`);
      });
    });
  };
  const requestUpdate = () => { if (!frame) frame = requestAnimationFrame(update); };
  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', requestUpdate, { passive: true });
  update();
})();
