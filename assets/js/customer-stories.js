(() => {
  const section = document.querySelector('[data-customer-stories]');
  if (!section) return;

  const cards = [...section.querySelectorAll('[data-video-card]')];
  const quotes = [...section.querySelectorAll('[data-story-quote]')];
  const steps = [...section.querySelectorAll('[data-story-step]')];
  let activeIndex = 0;
  let quoteIndex = 0;

  const render = () => {
    cards.forEach((card, index) => {
      const distance = (index - activeIndex % cards.length + cards.length) % cards.length;
      card.classList.toggle('is-active', distance === 0);
      card.inert = distance !== 0;
      card.querySelector('[data-video-play]').tabIndex = distance ? -1 : 0;
      card.style.zIndex = String(cards.length - distance);
      if (distance > 0) {
        card.style.transform = `translate(${-Math.min(distance, 3) * 12}px, ${Math.min(distance, 3) * 10}px) rotate(${-Math.min(distance, 3) * 2}deg) scale(${1 - Math.min(distance, 3) * 0.02})`;
        card.style.opacity = String(Math.max(0.35, 0.8 - distance * 0.14));
      } else {
        card.style.transform = '';
        card.style.opacity = '';
      }
      if (distance !== 0) {
        card.querySelector('video')?.pause();
        card.classList.remove('is-playing');
        const control = card.querySelector('[data-video-play]');
        if (control) control.setAttribute('aria-label', 'Play testimonial video');
      }
    });

  };

  const renderQuotes = () => {
    quotes.forEach((quote, index) => {
      const active = index === quoteIndex % quotes.length;
      quote.classList.toggle('is-active', active);
      quote.setAttribute('aria-hidden', String(!active));
    });
    steps.forEach((step, index) => {
      const active = index === quoteIndex % steps.length;
      step.classList.toggle('is-active', active);
      step.setAttribute('aria-pressed', String(active));
    });
  };

  steps.forEach((step, index) => step.addEventListener('click', () => {
    quoteIndex = index;
    renderQuotes();
  }));

  const change = (direction) => {
    if (!cards.length) return;
    activeIndex = (activeIndex + direction + cards.length) % cards.length;
    render();
  };

  section.querySelector('[data-video-next]')?.addEventListener('click', () => change(1));
  section.querySelector('[data-video-prev]')?.addEventListener('click', () => change(-1));
  let pointerStart = null;
  let suppressClickUntil = 0;
  const stack = section.querySelector('[data-video-stack]');
  stack?.addEventListener('pointerdown', (event) => {
    if (!event.isPrimary || event.button !== 0 || event.target.closest('.video-stack__controls')) return;
    pointerStart = { x: event.clientX, y: event.clientY, id: event.pointerId };
  });
  window.addEventListener('pointerup', (event) => {
    if (!pointerStart || pointerStart.id !== event.pointerId) return;
    const dx = event.clientX - pointerStart.x;
    const dy = event.clientY - pointerStart.y;
    pointerStart = null;
    if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
      suppressClickUntil = performance.now() + 500;
      change(dx < 0 ? 1 : -1);
    }
  });
  window.addEventListener('pointercancel', () => { pointerStart = null; });
  stack?.addEventListener('click', (event) => {
    if (performance.now() < suppressClickUntil) {
      event.preventDefault();
      event.stopPropagation();
    }
  }, true);

  render();
  renderQuotes();
  if (quotes.length > 1) window.aibridzeAutoplay(section.querySelector('[data-story-quotes]'), () => {
    quoteIndex = (quoteIndex + 1) % quotes.length;
    renderQuotes();
  }, 3500);
})();
