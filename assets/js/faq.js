(() => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  document.querySelectorAll('[data-faq-accordion]').forEach((accordion) => {
    const items = Array.from(accordion.querySelectorAll('.faq-item'));
    const animations = new WeakMap();
    const updateEdges = () => {
      accordion.style.setProperty('--faq-fade-top', accordion.scrollTop > 8 ? '14px' : '0px');
      accordion.style.setProperty('--faq-fade-bottom', accordion.scrollHeight - accordion.clientHeight - accordion.scrollTop > 8 ? '14px' : '0px');
    };
    const setOpen = (item, open) => {
      const answer = item.querySelector('.faq-item__answer');
      const button = item.querySelector('[data-faq-toggle]');
      const startHeight = answer.getBoundingClientRect().height;
      const startOpacity = answer.hidden ? 0 : Number(getComputedStyle(answer).opacity);
      const previous = animations.get(answer);
      if (previous) { previous.onfinish = null; previous.cancel(); }
      item.classList.toggle('is-open', open);
      button.setAttribute('aria-expanded', String(open));
      answer.inert = !open;
      answer.hidden = false;
      // Animate to the same natural box used after the animation is removed.
      // scrollHeight rounds to integers and can differ from the rendered height.
      const endHeight = open ? answer.getBoundingClientRect().height : 0;
      if (reducedMotion.matches || !answer.animate) {
        answer.hidden = !open;
        updateEdges();
        return;
      }
      const animation = answer.animate([
        { height: `${startHeight}px`, opacity: startOpacity },
        { height: `${endHeight}px`, opacity: open ? 1 : 0 }
      ], { duration: 260, easing: 'cubic-bezier(0.22, 1, 0.36, 1)', fill: 'both' });
      animations.set(answer, animation);
      animation.onfinish = () => {
        answer.hidden = !open;
        animation.cancel();
        animations.delete(answer);
        updateEdges();
      };
    };
    accordion.addEventListener('click', (event) => {
      const button = event.target.closest('[data-faq-toggle]');
      if (!button || !accordion.contains(button)) return;
      const item = button.closest('.faq-item');
      const open = !item.classList.contains('is-open');
      items.forEach(other => {
        if (other === item || other.classList.contains('is-open')) setOpen(other, other === item && open);
      });
    });
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => entry.target.classList.toggle('is-in-view', entry.isIntersecting));
      }, { root: accordion, threshold: 0.15 });
      items.forEach(item => observer.observe(item));
      accordion.classList.add('has-scroll-animation');
    }
    accordion.addEventListener('scroll', updateEdges, { passive: true });
    if ('ResizeObserver' in window) {
      const resizeObserver = new ResizeObserver(updateEdges);
      resizeObserver.observe(accordion);
      items.forEach(item => resizeObserver.observe(item));
    }
    updateEdges();
  });
})();
