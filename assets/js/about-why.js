(() => {
  document.querySelectorAll('[data-about-why]').forEach((section) => {
    const track = section.querySelector('[data-about-why-track]');
    const cards = [...section.querySelectorAll('.about-why__card')];
    const previousButtons = [...section.querySelectorAll('[data-about-why-previous]')];
    const nextButtons = [...section.querySelectorAll('[data-about-why-next]')];
    if (!track || !cards.length) return;

    let index = 0;

    const render = () => {
      const visibleCards = window.innerWidth <= 700 ? 1 : 3;
      const maximumIndex = Math.max(0, cards.length - visibleCards);
      index = Math.min(index, maximumIndex);

      const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;
      const distance = cards[0].getBoundingClientRect().width + gap;
      track.style.transform = `translate3d(${-index * distance}px, 0, 0)`;

      previousButtons.forEach((button) => { button.disabled = index === 0; });
      nextButtons.forEach((button) => { button.disabled = index === maximumIndex; });
      cards.forEach((card, cardIndex) => {
        const visible = cardIndex >= index && cardIndex < index + visibleCards;
        card.setAttribute('aria-hidden', String(!visible));
      });
    };

    previousButtons.forEach((button) => button.addEventListener('click', () => {
      index = Math.max(0, index - 1);
      render();
    }));
    nextButtons.forEach((button) => button.addEventListener('click', () => {
      const visibleCards = window.innerWidth <= 700 ? 1 : 3;
      index = Math.min(cards.length - visibleCards, index + 1);
      render();
    }));

    window.addEventListener('resize', render, { passive: true });
    render();
  });
})();
