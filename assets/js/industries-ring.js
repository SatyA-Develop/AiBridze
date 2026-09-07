(() => {
  if (!window.gsap || !window.Draggable) return;

  gsap.registerPlugin(Draggable);

  document.querySelectorAll('[data-industry-ring]').forEach((viewport) => {
    const ring = viewport.querySelector('.industries-showcase__ring');
    const scene = viewport.querySelector('.industries-showcase__scene');
    const dragger = viewport.querySelector('.industries-showcase__dragger');
    const linksLayer = viewport.querySelector('.industries-showcase__links');
    const sourceCards = gsap.utils.toArray(viewport.querySelectorAll('.industry-showcase-card'));
    if (!ring || !scene || !dragger || !linksLayer || sourceCards.length < 2) return;

    const responsive = gsap.matchMedia();
    responsive.add({ desktop: '(min-width: 1367px)', tablet: '(min-width: 701px) and (max-width: 1366px)' }, (context) => {
    if (!context.conditions.desktop && !context.conditions.tablet) return;
    const desktop = context.conditions.desktop;
    const clones = [];
    const cards = [...sourceCards];
    // Fill the wider desktop arc without duplicating the mobile content.
    if (desktop) {
      while (cards.length < 18) {
        const clone = sourceCards[cards.length % sourceCards.length].cloneNode(true);
        ring.appendChild(clone);
        clones.push(clone);
        cards.push(clone);
      }
    }

    const links = cards.map((card) => {
      const link = document.createElement('a');
      link.className = 'industries-showcase__link';
      link.href = card.dataset.href;
      link.textContent = card.querySelector('.industry-showcase-card__link').textContent.trim();
      link.setAttribute('aria-label', card.dataset.label || link.textContent);
      linksLayer.appendChild(link);
      return link;
    });

    let xPos = 0;
    let didDrag = false;
    let cardWidth = 0;
    let angleStep = 0;
    let built = false;
    let disposed = false;

    const getVisibleArc = () => desktop ? angleStep * 3 : angleStep * 1.5;
    const getCardOpacity = (angle) => {
      const arc = getVisibleArc();
      return !desktop
        ? gsap.utils.clamp(0, 1, (arc - Math.abs(angle)) / 10)
        : (Math.abs(angle) < arc ? 1 : 0);
    };

    const getBackgroundPosition = (index) => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const wrapped = gsap.utils.wrap(0, 360, rotation - 180 - index * angleStep);
      return `${(wrapped / 360) * 100}% center`;
    };

    const updateLinks = (rotation) => {
      const isCompact = viewport.clientWidth < 901;
      const visibleArc = getVisibleArc();
      const viewportRect = viewport.getBoundingClientRect();
      cards.forEach((card, index) => {
        const cardRect = card.getBoundingClientRect();
        const isVisible = Math.abs(gsap.utils.wrap(-180, 180, rotation - index * angleStep)) < visibleArc;
        links[index].style.left = `${cardRect.left - viewportRect.left + cardRect.width / 2}px`;
        links[index].style.top = `${cardRect.bottom - viewportRect.top - (isCompact ? 28 : 43)}px`;
        gsap.set(links[index], {
          autoAlpha: getCardOpacity(gsap.utils.wrap(-180, 180, rotation - index * angleStep)),
          pointerEvents: isVisible ? 'auto' : 'none'
        });
      });
    };

    const updateCards = () => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const visibleArc = getVisibleArc();
      gsap.set(cards, {
        backgroundPosition: (index) => getBackgroundPosition(index),
        autoAlpha: (index) => {
          const angle = gsap.utils.wrap(-180, 180, rotation - index * angleStep);
          return getCardOpacity(angle);
        },
        pointerEvents: (index) => {
          const angle = gsap.utils.wrap(-180, 180, rotation - index * angleStep);
          return Math.abs(angle) < visibleArc ? 'auto' : 'none';
        },
        scaleX: 1
      });
      updateLinks(rotation);
    };

    const buildRing = () => {
      // Measure layout width, never the projected width of a rotated card.
      cardWidth = cards[0].offsetWidth;
      if (!cardWidth || !viewport.clientWidth) return;
      angleStep = 360 / cards.length;
      const radius = desktop ? viewport.clientWidth * 0.66 : cardWidth * (500 / 300);
      scene.style.perspective = desktop ? '3200px' : `${cardWidth * (2000 / 300)}px`;

      // Six cards across desktop; a centered card and two neighbours on iPad.
      if (!built) gsap.set(ring, { rotationY: desktop ? 180 + angleStep / 2 : 180 });
      built = true;
      gsap.set(cards, {
        rotationY: (index) => index * -angleStep,
        transformOrigin: `50% 50% ${radius}px`,
        z: -radius,
        backgroundPosition: (index) => getBackgroundPosition(index),
        backfaceVisibility: 'hidden'
      });
      updateCards();
    };

    buildRing();

    gsap.from(cards, {
      duration: 1.5,
      y: 200,
      stagger: 0.1,
      ease: 'expo.out',
      onUpdate: () => updateLinks(Number(gsap.getProperty(ring, 'rotationY')) || 0),
      onComplete: updateCards
    });

    const [drag] = Draggable.create(dragger, {
      type: 'x',
      allowNativeTouchScrolling: true,
      trigger: viewport,
      dragClickables: true,
      onPress() {
        didDrag = false;
      },
      onDragStart(event) {
        const point = event.touches ? event.touches[0] : event;
        xPos = Math.round(point.clientX);
        didDrag = true;
        viewport.classList.add('is-dragging');
      },
      onDrag(event) {
        const point = event.touches ? event.touches[0] : event;
        const nextX = Math.round(point.clientX);
        gsap.to(ring, {
          rotationY: `-=${(nextX - xPos) % 360}`,
          duration: 0.5,
          overwrite: true,
          ease: 'power1.out',
          onUpdate: updateCards
        });
        xPos = nextX;
      },
      onDragEnd() {
        viewport.classList.remove('is-dragging');
        gsap.set(dragger, { x: 0, y: 0 });
      }
    });

    // A swipe may finish over a link. Only genuine clicks should navigate.
    const onClick = (event) => {
      if (didDrag && event.detail !== 0) {
        event.preventDefault();
        event.stopImmediatePropagation();
      }
    };
    viewport.addEventListener('click', onClick, true);

    const onKeyDown = (event) => {
      if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
      event.preventDefault();
      gsap.to(ring, {
        rotationY: `${event.key === 'ArrowLeft' ? '-' : '+'}=${angleStep}`,
        duration: 0.45,
        ease: 'power2.out',
        onUpdate: updateCards
      });
    };
    viewport.addEventListener('keydown', onKeyDown);

    const observer = new ResizeObserver(buildRing);
    observer.observe(viewport);
    document.fonts?.ready.then(() => { if (!disposed) buildRing(); });
    return () => {
      disposed = true;
      observer.disconnect();
      drag.kill();
      gsap.killTweensOf(ring);
      scene.style.removeProperty('perspective');
      viewport.removeEventListener('keydown', onKeyDown);
      viewport.removeEventListener('click', onClick, true);
      links.forEach((link) => link.remove());
      clones.forEach((clone) => clone.remove());
    };
    });
  });
})();
