(() => {
  const scene = document.querySelector('[data-manual-cost]');
  if (!scene) return;

  const sticky = scene.querySelector('.manual-cost__sticky');
  const title = scene.querySelector('.manual-cost__title');
  const challenges = [...scene.querySelectorAll('[data-manual-challenge]')];
  let ticking = false;

  const clamp = (value, min = 0, max = 1) => Math.min(max, Math.max(min, value));
  const smooth = (value) => {
    const progress = clamp(value);
    return progress * progress * (3 - (2 * progress));
  };

  const update = () => {
    const rect = scene.getBoundingClientRect();
    const distance = Math.max(scene.offsetHeight - window.innerHeight, 1);
    const progress = clamp(-rect.top / distance);
    scene.style.setProperty('--manual-progress', progress.toFixed(4));

    challenges.forEach((challenge, index) => {
      const start = 0.12 + (index * 0.145);
      const reveal = smooth((progress - start) / 0.13);
      challenge.style.setProperty('--challenge-opacity', reveal.toFixed(3));
      challenge.style.setProperty('--challenge-scale', String(0.55 + (reveal * 0.45)));
      challenge.style.setProperty('--challenge-lift', `${(1 - reveal) * 22}px`);
    });

    const titleFade = 1 - smooth((progress - 0.72) / 0.16);
    title.style.opacity = titleFade.toFixed(3);
    title.style.transform = `scale(${0.96 + (titleFade * 0.04)})`;
    sticky.dataset.progress = progress.toFixed(3);
    ticking = false;
  };

  const requestUpdate = () => {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(update);
  };

  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', requestUpdate, { passive: true });
  const observer = new IntersectionObserver(
    ([entry]) => scene.classList.toggle('is-in-view', entry.isIntersecting),
    { threshold: 0.02 }
  );
  observer.observe(scene);
  update();
})();
