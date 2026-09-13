(() => {
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
  // Each carousel owns one timer. Interaction always gives readers a fresh interval.
  window.aibridzeAutoplay = (element, advance, delay = 5200) => {
    if (!element) return;
    let timer;
    let visible = false;
    let hovered = false;
    let touching = false;
    const schedule = () => {
      clearTimeout(timer);
      timer = setTimeout(tick, delay);
    };
    const tick = () => {
      const playing = [...element.querySelectorAll('video')].some(video => !video.paused);
      const modalOpen = document.querySelector('.consultation-modal.is-open, dialog[open]');
      if (visible && !document.hidden && !reducedMotion.matches && !hovered && !touching &&
          !(element.contains(document.activeElement) && document.activeElement.matches(':focus-visible')) && !playing &&
          (!modalOpen || modalOpen.contains(element))) advance();
      schedule();
    };
    new IntersectionObserver(entries => {
      visible = entries[0].isIntersecting;
      schedule();
    }, { threshold: 0 }).observe(element);
    element.addEventListener('pointerenter', event => { if (event.pointerType === 'mouse') hovered = true; });
    element.addEventListener('pointerleave', () => { hovered = false; schedule(); });
    element.addEventListener('pointerdown', () => { touching = true; schedule(); });
    window.addEventListener('pointerup', () => { if (touching) { touching = false; schedule(); } });
    window.addEventListener('pointercancel', () => { touching = false; schedule(); });
    window.addEventListener('blur', () => { touching = false; hovered = false; schedule(); });
    ['click', 'focusin', 'focusout', 'wheel'].forEach(type => element.addEventListener(type, schedule, { passive: true }));
    document.addEventListener('visibilitychange', schedule);
    reducedMotion.addEventListener('change', schedule);
    schedule();
  };

  const initialize = () => {
    // Testimonial strips only advance when another card is outside the viewport.
    document.querySelectorAll('[data-contact-video-track]').forEach(track => {
      window.aibridzeAutoplay(track, () => {
        const cards = [...track.children];
        const end = track.scrollWidth - track.clientWidth;
        if (cards.length < 2 || end <= 2) return;
        const origin = cards[0].getBoundingClientRect().left;
        const next = cards.map(card => card.getBoundingClientRect().left - origin)
          .find(position => position > track.scrollLeft + 2);
        track.scrollTo({
          left: track.scrollLeft >= end - 2 ? 0 : Math.min(end, next ?? end),
          behavior: 'smooth'
        });
      }, 3500);
    });
    const selectors = '[data-carousel-track], [data-category-scroller], [data-contact-video-track], .service-group__grid, .service-process__track, .service-capabilities__track, .service-solutions__track, .service-expertise--rag__track, .service-tech-stack__groups, .service-tech-stack__items, .service-industries__track, .category-value-grid, .category-value-viewport';
    document.querySelectorAll(selectors).forEach(track => {
      if (track.matches('[data-contact-video-track]')) return;
      const section = track.closest('section') || track;
      window.aibridzeAutoplay(section, () => {
        const end = track.scrollWidth - track.clientWidth;
        // Stacked mobile cards and desktop grids remain static.
        if (end <= 2 || !['auto', 'scroll'].includes(getComputedStyle(track).overflowX)) return;
        const cards = [...(track.querySelector('[data-value-track]') || track).children];
        const origin = cards[0]?.getBoundingClientRect().left;
        const positions = cards.map(card => card.getBoundingClientRect().left - origin);
        const left = track.scrollLeft >= end - 2 ? 0 : Math.min(end, positions.find(position => position > track.scrollLeft + 2) ?? end);
        track.scrollTo({ left, behavior: 'smooth' });
        const activeIndex = positions.reduce((nearest, position, index) => Math.abs(position - left) < Math.abs(positions[nearest] - left) ? index : nearest, 0);
        cards.forEach((card, index) => {
          if (card.classList.contains('service-card')) card.classList.toggle('is-active', index === activeIndex);
        });
      });
      track.addEventListener('scroll', () => {
        const cards = [...(track.querySelector('[data-value-track]') || track).children];
        const first = cards[0];
        if (!first) return;
        const origin = first.getBoundingClientRect().left;
        ['process', 'capability'].forEach(kind => {
          if (!track.classList.contains(kind === 'process' ? 'service-process__track' : 'service-capabilities__track')) return;
          const dots = [...section.querySelectorAll(`[data-${kind}-page]`)];
          if (!dots.length) return;
          const perPage = kind === 'capability' || section.closest('.service-detail--chatbot') ? 2 : 1;
          let nearest = 0;
          let distance = Infinity;
          dots.forEach((dot, index) => {
            const card = cards[index * perPage];
            const delta = card ? Math.abs(card.getBoundingClientRect().left - origin - track.scrollLeft) : Infinity;
            if (delta < distance) { nearest = index; distance = delta; }
          });
          dots.forEach((dot, index) => dot.classList.toggle('is-active', index === nearest));
        });
      }, { passive: true });
    });
  };
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initialize, { once: true });
  } else initialize();
})();
