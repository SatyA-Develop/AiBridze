(() => {
  const videos = [...document.querySelectorAll('video[data-lazy-video]')];
  if (!videos.length) return;

  const loadVideo = (video) => {
    if (video.dataset.lazyLoaded === 'true') return;
    const source = video.dataset.src ? video : video.querySelector('source[data-src]');
    if (!source?.dataset.src) return;
    source.src = source.dataset.src;
    source.removeAttribute('data-src');
    video.dataset.lazyLoaded = 'true';
    video.load();
  };

  window.aibridzeLoadVideo = loadVideo;

  if (!('IntersectionObserver' in window)) {
    videos.forEach(loadVideo);
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        loadVideo(entry.target);
        observer.unobserve(entry.target);
      });
    },
    { rootMargin: '300px 0px', threshold: 0.01 }
  );

  videos.forEach((video) => observer.observe(video));
})();
