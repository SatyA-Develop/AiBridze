(() => {
  if (!window.gsap || !window.Draggable) return;

  gsap.registerPlugin(Draggable);

  document.querySelectorAll('[data-industry-ring]').forEach((viewport) => {
    const ring = viewport.querySelector('.industries-showcase__ring');
    const scene = viewport.querySelector('.industries-showcase__scene');
    const dragger = viewport.querySelector('.industries-showcase__dragger');
    const linksLayer = viewport.querySelector('.industries-showcase__links');
    const cards = gsap.utils.toArray(viewport.querySelectorAll('.industry-showcase-card'));
    if (!ring || !scene || !dragger || !linksLayer || cards.length < 2) return;

    const responsive = gsap.matchMedia();
    responsive.add('(min-width: 901px)', () => {

    const links = cards.map((card) => {
      const link = document.createElement('a');
      link.className = 'industries-showcase__link';
      link.href = card.dataset.href;
      link.textContent = card.querySelector('.industry-showcase-card__link').textContent.trim();
      link.setAttribute('aria-label', card.dataset.label || link.textContent);
      link.addEventListener('pointerup', (event) => {
        event.preventDefault();
        event.stopPropagation();
        window.location.assign(link.href);
      });
      link.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        window.location.assign(link.href);
      });
      linksLayer.appendChild(link);
      return link;
    });

    let xPos = 0;
    let cardWidth = 0;
    let angleStep = 0;
    let built = false;
    let disposed = false;

    const getBackgroundPosition = (index) => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const wrapped = gsap.utils.wrap(0, 360, rotation - 180 - index * angleStep);
      return `${100 - (wrapped / 360) * 100}% center`;
    };

    const updateLinks = (rotation) => {
      const isCompact = viewport.clientWidth < 901;
      const visibleArc = angleStep * (isCompact ? 1.51 : 3);
      const viewportRect = viewport.getBoundingClientRect();
      cards.forEach((card, index) => {
        const cardRect = card.getBoundingClientRect();
        const isVisible = Math.abs(gsap.utils.wrap(-180, 180, rotation - index * angleStep)) < visibleArc;
        links[index].style.left = `${cardRect.left - viewportRect.left + cardRect.width / 2}px`;
        links[index].style.top = `${cardRect.bottom - viewportRect.top - (isCompact ? 28 : 43)}px`;
        gsap.set(links[index], {
          autoAlpha: isVisible ? 1 : 0,
          pointerEvents: isVisible ? 'auto' : 'none'
        });
      });
    };

    const updateCards = () => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const visibleArc = angleStep * (viewport.clientWidth < 901 ? 1.51 : 3);
      gsap.set(cards, {
        backgroundPosition: (index) => getBackgroundPosition(index),
        autoAlpha: (index) => {
          const angle = gsap.utils.wrap(-180, 180, rotation - index * angleStep);
          return Math.abs(angle) < visibleArc ? 1 : 0;
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
      const isCompact = viewport.clientWidth < 901;
      const radius = isCompact
        ? cardWidth * 3.15
        : viewport.clientWidth * 0.6;

      // The reference uses 180deg for five front-facing cards. A half-step
      // offset exposes six cards while retaining the same circular geometry.
      if (!built) gsap.set(ring, { rotationY: isCompact ? 180 : 180 + angleStep / 2 });
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
      type: 'x,y',
      trigger: scene,
      dragClickables: false,
      onDragStart(event) {
        const point = event.touches ? event.touches[0] : event;
        xPos = Math.round(point.clientX);
        viewport.classList.add('is-dragging');
      },
      onDrag(event) {
        const point = event.touches ? event.touches[0] : event;
        const nextX = Math.round(point.clientX);
        gsap.to(ring, {
          rotationY: `-=${(nextX - xPos) % 360}`,
          duration: 0.18,
          overwrite: true,
          ease: 'power1.out',
          onUpdate: updateCards
        });
        xPos = nextX;
      },
      onDragEnd() {
        viewport.classList.remove('is-dragging');
        gsap.set(dragger, { x: 0, y: 0 });
        const halfStep = angleStep / 2;
        const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
        const snappedRotation = Math.round((rotation - halfStep) / angleStep) * angleStep + halfStep;

        gsap.to(ring, {
          rotationY: snappedRotation,
          duration: 0.35,
          ease: 'power2.out',
          overwrite: true,
          onUpdate: updateCards
        });
      }
    });

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
      viewport.removeEventListener('keydown', onKeyDown);
      links.forEach((link) => link.remove());
    };
    });
  });
})();
