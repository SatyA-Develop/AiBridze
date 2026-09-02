document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-compliance-section]').forEach((section) => {
    const toggle = section.querySelector('[data-compliance-toggle]');
    if (!toggle) return;
    toggle.addEventListener('click', () => {
      const expanded = section.classList.toggle('is-expanded');
      toggle.setAttribute('aria-expanded', String(expanded));
      const label = toggle.querySelector('span');
      if (label) label.textContent = expanded ? 'View Less' : 'View More';
    });
  });

  document.querySelectorAll('[data-single-accordion]').forEach((accordion) => {
    const panels = [...accordion.querySelectorAll(':scope > details')];
    panels.forEach((panel) => {
      panel.removeAttribute('open');
      panel.addEventListener('toggle', () => {
        if (!panel.open) return;
        panels.forEach((other) => {
          if (other !== panel) other.removeAttribute('open');
        });
      });
    });
  });

  document.querySelectorAll('[data-industry-accordion]').forEach((accordion) => {
    const panels = [...accordion.querySelectorAll(':scope > details')];
    panels.forEach((panel) => panel.addEventListener('toggle', () => {
      if (!panel.open) return;
      panels.forEach((other) => {
        if (other !== panel) other.removeAttribute('open');
      });
    }));
  });

  document.querySelectorAll('[data-category-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-carousel-track]');
    const previous = carousel.querySelector('[data-carousel-prev]');
    const next = carousel.querySelector('[data-carousel-next]');
    if (!track || !previous || !next) return;

    const step = () => {
      const card = track.querySelector('.category-service-card');
      if (!card) return track.clientWidth;
      const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
      return card.getBoundingClientRect().width + gap;
    };
    const update = () => {
      const end = Math.max(0, track.scrollWidth - track.clientWidth);
      previous.disabled = track.scrollLeft <= 2;
      next.disabled = track.scrollLeft >= end - 2;
    };

    previous.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
    next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
  });

  document.querySelectorAll('[data-value-scroll]').forEach((section) => {
    const sticky = section.querySelector('.category-value-scroll__sticky');
    const viewport = section.querySelector('.category-value-viewport');
    const track = section.querySelector('[data-value-track]');
    if (!sticky || !viewport || !track) return;

    let maximum = 0;
    let frame = 0;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const mobileLayout = window.matchMedia('(max-width: 560px)');

    const measure = () => {
      section.style.height = 'auto';
      track.style.transform = 'translate3d(0,0,0)';
      maximum = Math.max(0, track.scrollWidth - viewport.clientWidth);
      if (reducedMotion.matches || mobileLayout.matches) {
        return;
      }
      // One vertical pixel drives one horizontal pixel. With the sticky panel
      // exactly one viewport high, its top pins on the downward pass and its
      // bottom pins at the viewport bottom for the complete reverse pass.
      section.style.height = `${sticky.offsetHeight + maximum}px`;
      render();
    };
    const render = () => {
      frame = 0;
      if (reducedMotion.matches || mobileLayout.matches) return;
      const rect = section.getBoundingClientRect();
      const travel = Math.max(1, section.offsetHeight - sticky.offsetHeight);
      const progress = Math.min(1, Math.max(0, -rect.top / travel));
      track.style.transform = `translate3d(${-maximum * progress}px,0,0)`;
    };
    const requestRender = () => {
      if (!frame) frame = requestAnimationFrame(render);
    };

    window.addEventListener('scroll', requestRender, { passive: true });
    window.addEventListener('resize', measure, { passive: true });
    reducedMotion.addEventListener?.('change', measure);
    mobileLayout.addEventListener?.('change', measure);
    section.querySelectorAll('img').forEach((image) => {
      if (!image.complete) image.addEventListener('load', measure, { once: true });
    });
    measure();
  });
});
