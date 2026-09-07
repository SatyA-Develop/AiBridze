(() => {
  const section = document.querySelector('[data-customer-stories]');
  if (!section) return;

  const cards = [...section.querySelectorAll('[data-video-card]')];
  const quotes = [...section.querySelectorAll('[data-story-quote]')];
  const steps = [...section.querySelectorAll('[data-story-step]')];
  let activeIndex = 0;

  const render = () => {
    cards.forEach((card, index) => {
      const distance = (index - activeIndex % cards.length + cards.length) % cards.length;
      card.classList.toggle('is-active', distance === 0);
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

    quotes.forEach((quote, index) => {
      const active = index === activeIndex % quotes.length;
      quote.classList.toggle('is-active', active);
      quote.setAttribute('aria-hidden', String(!active));
    });
    steps.forEach((step, index) => {
      const active = index === activeIndex % steps.length;
      step.classList.toggle('is-active', active);
      step.setAttribute('aria-pressed', String(active));
    });
  };

  steps.forEach((step, index) => step.addEventListener('click', () => {
    activeIndex = index;
    render();
  }));

  const change = (direction) => {
    activeIndex = (activeIndex + direction + cards.length) % cards.length;
    render();
  };

  section.querySelector('[data-video-next]')?.addEventListener('click', () => change(1));
  section.querySelector('[data-video-prev]')?.addEventListener('click', () => change(-1));
  cards.forEach((card) => card.querySelector('[data-video-play]')?.addEventListener('click', () => {
    if (!card.classList.contains('is-active')) {
      activeIndex = Number(card.dataset.index);
      render();
      return;
    }
    const video = card.querySelector('video');
    if (video.paused) {
      window.aibridzeLoadVideo?.(video);
      video.play();
      card.classList.add('is-playing');
      card.querySelector('[data-video-play]').setAttribute('aria-label', 'Pause testimonial video');
    } else {
      video.pause();
      card.classList.remove('is-playing');
      card.querySelector('[data-video-play]').setAttribute('aria-label', 'Play testimonial video');
    }
  }));

  cards.forEach((card) => {
    const video = card.querySelector('video');
    const control = card.querySelector('[data-video-play]');
    video?.addEventListener('play', () => {
      card.classList.add('is-playing');
      control?.setAttribute('aria-label', 'Pause testimonial video');
    });
    video?.addEventListener('pause', () => {
      card.classList.remove('is-playing');
      control?.setAttribute('aria-label', 'Play testimonial video');
    });
    video?.addEventListener('ended', () => {
      card.classList.remove('is-playing');
      control?.setAttribute('aria-label', 'Play testimonial video');
    });
  });

  let pointerStart = null;
  section.querySelector('[data-video-stack]')?.addEventListener('pointerdown', (event) => { pointerStart = event.clientX; });
  section.querySelector('[data-video-stack]')?.addEventListener('pointerup', (event) => {
    if (pointerStart === null) return;
    const movement = event.clientX - pointerStart;
    if (Math.abs(movement) > 45) change(movement < 0 ? 1 : -1);
    pointerStart = null;
  });

  render();
})();
