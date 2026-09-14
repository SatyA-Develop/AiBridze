(() => {
  const section = document.querySelector('[data-portfolio-stack]');
  if (!section) return;
  const region = section.querySelector('[data-portfolio-scroll-region]');
  const viewport = section.querySelector('[data-portfolio-viewport]');
  const heading = section.querySelector('.portfolio-stack__heading');
  const cards = [...section.querySelectorAll('[data-portfolio-card]')];
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
  if (!region || !viewport || !cards.length) return;
  let frame = 0, active = -1, step = 0, top = 0, introHold = 0, animated = false;
  let transitionFrame = 0, transitioning = false, lastWheel = -Infinity, transitionFinished = -Infinity;
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
    const regionTop = region.getBoundingClientRect().top;
    // The first card rises over the pinned heading before any project changes.
    const coverDistance = (heading?.offsetHeight || 144) + 90;
    const cover = clamp((top + coverDistance - regionTop) / coverDistance);
    heading?.style.setProperty('--portfolio-heading-scale', String(1 - cover * 0.35));
    heading?.style.setProperty('--portfolio-heading-squeeze', String(1 - cover * 0.5));
    // Hold the first project after it covers the heading before starting the wipes.
    let raw = Math.max(0, Math.min(cards.length - 1, (top - regionTop - introHold) / step));
    if (Math.abs(raw - Math.round(raw)) * step < 1) raw = Math.round(raw);
    const current = Math.min(cards.length - 1, Math.floor(raw));
    const phase = raw - current;
    // Switch the left-side details at the midpoint of the image wipe,
    // regardless of monitor height or the card's position on screen.
    select(Math.min(cards.length - 1, current + (phase >= 0.5 ? 1 : 0)));
    cards.forEach((card, index) => {
      const reveal = index === 0 ? 1 : clamp(raw - index + 1);
      card.style.setProperty('--portfolio-image-clip', `${(1 - reveal) * 100}%`);
      card.style.setProperty('--portfolio-image-y', `${(1 - clamp(raw - index + 1)) * 4}%`);
    });
  };
  const requestUpdate = () => { if (!frame) frame = requestAnimationFrame(update); };
  const transitionTo = (target) => {
    const from = window.scrollY;
    const started = performance.now();
    transitioning = true;
    const tick = (now) => {
      const progress = clamp((now - started) / 850);
      const eased = (1 - Math.cos(Math.PI * progress)) / 2;
      window.scrollTo({ top: from + (target - from) * eased, behavior: 'instant' });
      update();
      if (progress < 1) transitionFrame = requestAnimationFrame(tick);
      else {
        transitionFrame = 0;
        transitioning = false;
        transitionFinished = now;
      }
    };
    transitionFrame = requestAnimationFrame(tick);
  };
  window.addEventListener('wheel', (event) => {
    if (!animated || event.ctrlKey || Math.abs(event.deltaX) > Math.abs(event.deltaY) || !event.deltaY) return;
    if (document.querySelector('dialog[open], .consultation-modal.is-open')) return;
    const now = performance.now();
    const continuingGesture = now - lastWheel < 160;
    const start = window.scrollY + region.getBoundingClientRect().top - top + introHold;
    const end = start + step * (cards.length - 1);
    const y = window.scrollY;
    const direction = Math.sign(event.deltaY);
    if (transitioning) { lastWheel = now; event.preventDefault(); return; }
    const delta = event.deltaY * (event.deltaMode === 1 ? 16 : event.deltaMode === 2 ? innerHeight : 1);
    const coverDistance = (heading?.offsetHeight || 144) + 90;
    // Cover the heading as its own step, with the first image fully intact.
    // Browser scroll positions may round fractional layout coordinates; use
    // the same tolerance here as below so entry cannot trap the next wheel.
    const entering = direction > 0 && y < start - 2 &&
      (y >= start - introHold - coverDistance || y + delta >= start);
    const returning = direction < 0 && y > end + 2 && y + delta <= end;
    if (entering || returning) {
      lastWheel = now;
      event.preventDefault();
      transitionTo(entering ? start : end);
      return;
    }
    if (y < start - 2 || y > end + 2) return;
    lastWheel = now;
    const index = Math.round((y - start) / step);
    const next = index + direction;
    if (next < 0 || next >= cards.length) return;
    // Absorb brief momentum tails, but do not require continuous wheel input
    // to stop entirely. The bounded hold lets sustained scrolling advance
    // one project at a time without skipping through several projects.
    if (continuingGesture && now - transitionFinished < 300) { event.preventDefault(); return; }
    event.preventDefault();
    transitionTo(start + next * step);
  }, { passive: false });
  const measure = () => {
    cancelAnimationFrame(transitionFrame);
    transitioning = false;
    lastWheel = -Infinity;
    transitionFinished = -Infinity;
    animated = innerWidth > 1000 && innerHeight > 680 && !reducedMotion.matches && cards.length > 1;
    section.classList.toggle('is-scroll-animated', animated);
    top = (document.querySelector('[data-site-header]')?.offsetHeight || 0) + 16;
    // One viewport of scroll drives each wipe; the change occurs at its center
    // crossing rather than after an additional bottom-of-screen delay.
    step = innerHeight;
    introHold = Math.min(320, Math.max(200, innerHeight * 0.3));
    viewport.style.setProperty('--portfolio-top', `${top}px`);
    section.style.setProperty('--portfolio-top', `${top}px`);
    if (animated) region.style.height = `${viewport.offsetHeight + introHold + step * (cards.length - 1)}px`;
    else {
      region.style.removeProperty('height');
      heading?.style.removeProperty('--portfolio-heading-scale');
      heading?.style.removeProperty('--portfolio-heading-squeeze');
    }
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
