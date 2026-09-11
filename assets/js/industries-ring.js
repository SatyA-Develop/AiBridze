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

    const getVisibleArc = () => desktop ? angleStep * 3 : angleStep * 1.5;
    const getCardOpacity = (angle) => {
      const arc = getVisibleArc();
      const fade = gsap.utils.clamp(0, 1, (arc - Math.abs(angle)) / (angleStep * 0.55));
      return fade * fade * (3 - 2 * fade);
    };

    const getBackgroundPosition = (index) => {
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      const angle = (rotation - index * angleStep) * Math.PI / 180;
      return `${50 + Math.sin(angle) * 25}% center`;
    };

    const updateLinks = (rotation) => {
      const visibleArc = getVisibleArc();
      const viewportRect = viewport.getBoundingClientRect();
      cards.forEach((card, index) => {
        const cardRect = card.getBoundingClientRect();
        const isVisible = Math.abs(gsap.utils.wrap(-180, 180, rotation - index * angleStep)) < visibleArc;
        links[index].style.left = `${cardRect.left - viewportRect.left}px`;
        links[index].style.top = `${cardRect.top - viewportRect.top}px`;
        links[index].style.width = `${cardRect.width}px`;
        links[index].style.height = `${cardRect.height}px`;
        links[index].inert = !isVisible;
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
      scene.style.perspective = desktop ? `${viewport.clientWidth * 1.25}px` : `${cardWidth * 4.5}px`;

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

    // Tablet breakpoint changes must not slide cards outside the clipped scene.
    if (desktop) gsap.from(cards, {
      duration: 1.5,
      y: 200,
      stagger: 0.1,
      ease: 'expo.out',
      onUpdate: () => updateLinks(Number(gsap.getProperty(ring, 'rotationY')) || 0),
      onComplete: updateCards
    });
    else gsap.set(cards, { y: 0 });

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
        gsap.set(ring, { rotationY: dragStartRotation - (this.x - this.startX) * angleStep / cardWidth });
        updateCards();
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

    let inView = false;
    const visibility = new IntersectionObserver(([entry]) => { inView = entry.isIntersecting; });
    visibility.observe(viewport);
    const autoplay = (time, delta) => {
      if (!inView || document.hidden || matchMedia('(prefers-reduced-motion: reduce)').matches ||
          viewport.matches(':hover, :focus-visible') || viewport.querySelector(':focus-visible, .is-open') ||
          drag.isPressed || gsap.isTweening(ring)) return;
      const rotation = Number(gsap.getProperty(ring, 'rotationY')) || 0;
      gsap.set(ring, { rotationY: rotation + Math.min(delta, 50) * 0.004 });
      updateCards();
    };
    gsap.ticker.add(autoplay);

    const observer = new ResizeObserver(buildRing);
    observer.observe(viewport);
    document.fonts?.ready.then(() => { if (!disposed) buildRing(); });
    return () => {
      disposed = true;
      observer.disconnect();
      visibility.disconnect();
      gsap.ticker.remove(autoplay);
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
