(() => {
  const section = document.querySelector('[data-success-cta]');
  const video = section?.querySelector('[data-success-cta-video]');
  const source = video?.querySelector('source[data-src]');
  if (!section || !video || !source) return;

  let isLoaded = false;
  let isVisible = false;
  const mobile = window.matchMedia('(max-width: 1100px)');

  const playFromStart = () => {
    if (!isVisible || mobile.matches) return;
    video.currentTime = 0;
    video.play().catch(() => {
      // Muted inline playback is expected; keep the loaded frame if blocked.
    });
  };

  const loadVideo = () => {
    if (mobile.matches) return;
    if (isLoaded) {
      playFromStart();
      return;
    }

    isLoaded = true;
    source.src = source.dataset.src;
    source.removeAttribute('data-src');
    video.addEventListener('loadeddata', playFromStart, { once: true });
    video.load();
  };

  const observer = new IntersectionObserver(
    ([entry]) => {
      isVisible = entry.isIntersecting;

      if (isVisible) {
        loadVideo();
      } else {
        video.pause();
      }
    },
    { threshold: 0.15 }
  );

  observer.observe(section);
  mobile.addEventListener('change', () => {
    if (mobile.matches) video.pause();
    else if (isVisible) loadVideo();
  });
})();
