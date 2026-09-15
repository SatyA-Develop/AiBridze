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

    const onToggle = (event) => {
      const button = event.target.closest('.industry-showcase-card__toggle');
      if (!button) return;
      if (matchMedia('(hover: hover) and (pointer: fine)').matches && innerWidth > 700 && event.detail !== 0) return;
      const card = button.parentElement;
      const open = !card.classList.contains('is-open');
      viewport.querySelectorAll('.is-open').forEach((item) => {
        item.classList.remove('is-open');
        item.querySelector('button')?.setAttribute('aria-expanded', 'false');
      });
      card.classList.toggle('is-open', open);
      button.setAttribute('aria-expanded', String(open));
    };
    viewport.addEventListener('click', onToggle);

    const responsive = gsap.matchMedia();
    responsive.add({ wide: '(min-width: 1921px)', desktop: '(min-width: 1367px)', tablet: '(min-width: 701px) and (max-width: 1366px)' }, (context) => {
    if (!context.conditions.desktop && !context.conditions.tablet) return;
    const desktop = context.conditions.desktop;
    const clones = [];
    const cards = sourceCards.slice(0, context.conditions.wide ? 12 : 10);

    const links = cards.map((card) => {
      const link = document.createElement('div');
      link.className = 'industries-showcase__interaction';
      link.append(card.querySelector('.industry-showcase-card__toggle').cloneNode(true));
      link.append(card.querySelector('.industry-showcase-card__cta').cloneNode(true));
      card.querySelectorAll('button, a').forEach((item) => { item.tabIndex = -1; });
      linksLayer.appendChild(link);
      return link;
    });

    let dragStartRotation = 0;
    let didDrag = false;
    let cardWidth = 0;
    let angleStep = 0;
    let built = false;
    let disposed = false;
    let inView = false;
    let resumeAt = 0;
    const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
    const finePointer = matchMedia('(hover: hover) and (pointer: fine)');

    // Match the projected front face at the reference's radius/perspective ratio.
    const getVisibleArc = () => Math.acos(-0.25) * 180 / Math.PI;

    const getBackgroundPosition = (index) => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const angle = (rotation - index * angleStep) * Math.PI / 180;
      return `${50 + Math.sin(angle) * 25}% center`;
    };

    const updateLinks = (rotation) => {
      const viewportRect = viewport.getBoundingClientRect();
      cards.forEach((card, index) => {
        const cardRect = card.getBoundingClientRect();
        const isVisible = Math.abs(gsap.utils.wrap(-180, 180, rotation - index * angleStep)) < getVisibleArc();
        links[index].style.left = `${cardRect.left - viewportRect.left}px`;
        links[index].style.top = `${cardRect.top - viewportRect.top}px`;
        links[index].style.width = `${cardRect.width}px`;
        links[index].style.height = `${cardRect.height}px`;
        links[index].inert = !isVisible;
        gsap.set(links[index], {
          autoAlpha: isVisible ? 1 : 0,
          pointerEvents: isVisible ? 'auto' : 'none'
        });
      });
    };

    const updateCards = () => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const visibleArc = getVisibleArc();
      gsap.set(cards, {
        backgroundPosition: (index) => getBackgroundPosition(index),
        // The browser rotates each face into view; there is no slide-entry fade.
        autoAlpha: 1,
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
      const radius = cardWidth * (context.conditions.wide ? (5 / 3) * Math.tan(Math.PI / 10) / Math.tan(Math.PI / cards.length) : 5 / 3);
      scene.style.perspective = `${radius * 4}px`;

      // Use the same circular geometry at desktop and tablet sizes.
      if (!built) gsap.set(ring, { rotationY: 180 });
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

    gsap.set(cards, { y: 0 });

    const [drag] = Draggable.create(dragger, {
      type: 'x',
      allowNativeTouchScrolling: true,
      trigger: viewport,
      dragClickables: true,
      onPress() {
        didDrag = false;
        gsap.killTweensOf(ring);
        dragStartRotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      },
      onDragStart() {
        didDrag = true;
        viewport.classList.add('is-dragging');
      },
      onDrag() {
        // Follow the full pointer displacement, without dropping movement to overwritten tweens.
        gsap.to(ring, { rotationY: dragStartRotation - (this.x - this.startX), duration: 0.5, ease: 'power1.out', overwrite: true, onUpdate: updateCards });
      },
      onDragEnd() {
        viewport.classList.remove('is-dragging');
        gsap.set(dragger, { x: 0, y: 0 });
      },
      onRelease() {
        resumeAt = performance.now() + 2000;
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
      resumeAt = performance.now() + 2000;
      gsap.to(ring, {
        rotationY: `${event.key === 'ArrowLeft' ? '-' : '+'}=${angleStep}`,
        duration: 0.45,
        ease: 'power2.out',
        onUpdate: updateCards
      });
    };
    viewport.addEventListener('keydown', onKeyDown);

    // A circular ring has no final slide: keep rotating through the same cards.
    const visibility = new IntersectionObserver(([entry]) => { inView = entry.isIntersecting; });
    visibility.observe(viewport);
    const autoplay = (time, delta) => {
      if (!inView || document.hidden || reducedMotion.matches || drag.isPressed ||
          performance.now() < resumeAt || gsap.isTweening(ring) ||
          (finePointer.matches && viewport.matches(':hover')) ||
          viewport.querySelector(':focus-visible, .is-open')) return;
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      gsap.set(ring, { rotationY: gsap.utils.wrap(0, 360, rotation + Math.min(delta, 50) * angleStep / 4000) });
      updateCards();
    };
    gsap.ticker.add(autoplay);

    const observer = new ResizeObserver(buildRing);
    observer.observe(viewport);
    document.fonts?.ready.then(() => { if (!disposed) buildRing(); });
    return () => {
      disposed = true;
      gsap.ticker.remove(autoplay);
      visibility.disconnect();
      observer.disconnect();
      drag.kill();
      gsap.killTweensOf(ring);
      scene.style.removeProperty('perspective');
      viewport.removeEventListener('keydown', onKeyDown);
      viewport.removeEventListener('click', onClick, true);
      links.forEach((link) => link.remove());
      clones.forEach((clone) => clone.remove());
      sourceCards.forEach((card) => card.querySelectorAll('button, a').forEach((item) => item.removeAttribute('tabindex')));
    };
    });
    responsive.add('(max-width: 700px)', () => {
      const copies = sourceCards.map((card) => {
        const clone = card.cloneNode(true);
        ring.append(clone);
        return clone;
      });
      let inView = false, pressed = false, resumeAt = 0, position = scene.scrollLeft;
      const visibility = new IntersectionObserver(([entry]) => { inView = entry.isIntersecting; });
      visibility.observe(viewport);
      const down = () => { pressed = true; };
      const up = () => { pressed = false; position = scene.scrollLeft; resumeAt = performance.now() + 2000; };
      viewport.addEventListener('pointerdown', down);
      window.addEventListener('pointerup', up);
      window.addEventListener('pointercancel', up);
      const autoplay = (time, delta) => {
        if (!inView || document.hidden || pressed || performance.now() < resumeAt ||
            viewport.querySelector(':focus-visible, .is-open') ||
            matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const cycle = copies[0].offsetLeft - sourceCards[0].offsetLeft;
        if (!cycle) return;
        position = (position + Math.min(delta, 50) * 0.025) % cycle;
        scene.scrollLeft = position;
      };
      gsap.ticker.add(autoplay);
      return () => {
        gsap.ticker.remove(autoplay);
        visibility.disconnect();
        viewport.removeEventListener('pointerdown', down);
        window.removeEventListener('pointerup', up);
        window.removeEventListener('pointercancel', up);
        copies.forEach((card) => card.remove());
        scene.scrollLeft = 0;
        scene.scrollTop = 0;
      };
    });
  });
})();
