(() => {
  document.querySelectorAll('[data-about-why]').forEach((section) => {
    const track = section.querySelector('[data-about-why-track]');
    const viewport = section.querySelector('.about-why__viewport');
    const freeScroll = matchMedia('(max-width: 1100px)');
    const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
    const cards = [...section.querySelectorAll('.about-why__card')];
    const previousButtons = [...section.querySelectorAll('[data-about-why-previous]')];
    const nextButtons = [...section.querySelectorAll('[data-about-why-next]')];
    if (!track || !cards.length) return;

    let index = 0;
    let drag;
    let loopWidth = 0;
    let settleTimer;
    const copies = [];
    const setupLoop = () => {
      copies.forEach(card => card.remove());
      copies.length = 0;
      loopWidth = 0;
      if (!freeScroll.matches) return;
      const copy = card => {
        const clone = card.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        clone.inert = true;
        clone.removeAttribute('id');
        clone.querySelectorAll('[id]').forEach(element => element.removeAttribute('id'));
        copies.push(clone);
        return clone;
      };
      track.prepend(...cards.map(copy));
      track.append(...cards.map(copy));
      loopWidth = cards[0].getBoundingClientRect().left - copies[0].getBoundingClientRect().left;
      viewport.scrollLeft = loopWidth;
    };
    const normalizeLoop = () => {
      if (!freeScroll.matches || !loopWidth || drag) return;
      const left = viewport.scrollLeft;
      if (left < loopWidth || left >= loopWidth * 2) {
        viewport.scrollLeft = loopWidth + ((left - loopWidth) % loopWidth + loopWidth) % loopWidth;
      }
    };
    const move = (direction) => {
      if (freeScroll.matches) {
        const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
        viewport.scrollBy({ left: direction * (cards[0].getBoundingClientRect().width + gap), behavior: reducedMotion.matches ? 'instant' : 'smooth' });
      } else {
        index = Math.max(0, index + direction);
        render();
      }
    };

    const render = () => {
      const visibleCards = window.innerWidth <= 700 ? 1 : window.innerWidth <= 1100 ? 2 : 3;
      const maximumIndex = Math.max(0, cards.length - visibleCards);
      index = Math.min(index, maximumIndex);

      const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;
      const distance = cards[0].getBoundingClientRect().width + gap;
      if (freeScroll.matches) {
        track.style.transform = 'none';
        previousButtons.forEach(button => { button.disabled = false; button.classList.remove('is-highlighted'); });
        nextButtons.forEach(button => { button.disabled = false; button.classList.add('is-highlighted'); });
        cards.forEach(card => card.removeAttribute('aria-hidden'));
        return;
      }
      track.style.transform = `translate3d(${-index * distance}px, 0, 0)`;

      const atEnd = index === maximumIndex;
      previousButtons.forEach((button) => {
        button.disabled = index === 0;
        button.classList.toggle('is-highlighted', atEnd && !button.disabled);
      });
      nextButtons.forEach((button) => {
        button.disabled = atEnd;
        button.classList.toggle('is-highlighted', !button.disabled);
      });
      cards.forEach((card, cardIndex) => {
        const visible = cardIndex >= index && cardIndex < index + visibleCards;
        card.setAttribute('aria-hidden', String(!visible));
      });
    };

    previousButtons.forEach((button) => button.addEventListener('click', () => {
      move(-1);
    }));
    nextButtons.forEach((button) => button.addEventListener('click', () => {
      move(1);
    }));

    viewport.addEventListener('scroll', () => {
      if (!freeScroll.matches) return;
      clearTimeout(settleTimer);
      settleTimer = setTimeout(normalizeLoop, 180);
    }, { passive: true });
    // Touch uses native momentum scrolling; mouse/pen users can drag the same strip.
    viewport.addEventListener('pointerdown', event => {
      if (!freeScroll.matches || event.pointerType === 'touch' || event.button !== 0) return;
      drag = { x: event.clientX, left: viewport.scrollLeft };
      viewport.classList.add('is-dragging');
      viewport.setPointerCapture(event.pointerId);
    });
    viewport.addEventListener('pointermove', event => {
      if (drag) viewport.scrollLeft = drag.left - (event.clientX - drag.x);
    });
    ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(type => viewport.addEventListener(type, () => { drag = null; viewport.classList.remove('is-dragging'); }));
    viewport.addEventListener('dragstart', event => { if (freeScroll.matches) event.preventDefault(); });
    freeScroll.addEventListener('change', () => {
      index = 0;
      viewport.scrollLeft = 0;
      setupLoop();
      render();
    });

    let lastWidth = viewport.clientWidth;
    window.addEventListener('resize', () => {
      if (viewport.clientWidth !== lastWidth) {
        lastWidth = viewport.clientWidth;
        setupLoop();
      }
      render();
    }, { passive: true });
    setupLoop();
    render();
    window.aibridzeAutoplay(section, () => {
      if (freeScroll.matches) {
        normalizeLoop();
        move(1);
        return;
      }
      const visibleCards = window.innerWidth <= 700 ? 1 : window.innerWidth <= 1100 ? 2 : 3;
      index = index >= Math.max(0, cards.length - visibleCards) ? 0 : index + 1;
      render();
    });
  });
})();
