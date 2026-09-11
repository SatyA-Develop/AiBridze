document.querySelectorAll('[data-contact-videos], [data-customer-stories]').forEach((section) => {
  // Show a frame for uploaded videos without a featured thumbnail, without playing audio.
  section.querySelectorAll('[data-testimonial-preview]').forEach((video) => {
    video.addEventListener('loadedmetadata', () => {
      if (Number.isFinite(video.duration) && video.duration > 0) video.currentTime = Math.min(.1, video.duration / 2);
    }, { once: true });
  });
  const dialog = document.createElement('dialog');
  dialog.className = 'testimonial-video-modal';
  dialog.setAttribute('aria-label', 'Client testimonial video');
  dialog.innerHTML = '<button type="button" class="testimonial-video-modal__close" aria-label="Close video"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2.25" stroke-linecap="round"/></svg></button><div class="testimonial-video-modal__stage"></div>';
  document.body.append(dialog);
  const stage = dialog.querySelector('.testimonial-video-modal__stage');
  let player;
  let trigger;
  let previousOverflow;
  dialog.querySelector('button').addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', (event) => {
    const rect = dialog.getBoundingClientRect();
    if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
  });
  dialog.addEventListener('close', () => {
    player?.pause();
    player?.destroy();
    player = null;
    stage.replaceChildren();
    document.body.style.overflow = previousOverflow;
    trigger?.focus({ preventScroll: true });
  });
  section.querySelectorAll('[data-contact-video-play], [data-video-card]').forEach((button) => {
    button.addEventListener('click', () => {
      const card = button.closest('[data-video-source]');
      const source = card.dataset.videoSource;
      if (!source) return;
      trigger = button.querySelector('[data-video-play]') || button;
      previousOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      const media = document.createElement(card.dataset.youtubeId ? 'div' : 'video');
      if (card.dataset.youtubeId) {
        media.dataset.plyrProvider = 'youtube';
        media.dataset.plyrEmbedId = card.dataset.youtubeId;
      } else {
        media.src = source;
        media.controls = true;
        media.playsInline = true;
      }
      stage.replaceChildren(media);
      dialog.showModal();
      if (!window.Plyr) {
        const fallback = document.createElement('a');
        fallback.href = source;
        fallback.textContent = 'Open video';
        fallback.target = '_blank';
        fallback.rel = 'noopener noreferrer';
        stage.append(fallback);
        return;
      }
      player = new Plyr(media, {
        autoplay: true,
        youtube: { noCookie: true, rel: 0 },
        controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
        iconUrl: new URL('../vendor/plyr/plyr.svg', document.querySelector('script[src*="contact-video-testimonials.js"]').src).href
      });
      const toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'testimonial-video-modal__toggle';
      toggle.setAttribute('aria-label', 'Play video');
      toggle.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path class="video-play-shape" d="M8 5v14l11-7z"/><path class="video-pause-shape" d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>';
      player.elements.container.append(toggle);
      toggle.addEventListener('click', () => player?.togglePlay());
      const sync = () => {
        toggle.classList.toggle('is-playing', !player.paused);
        toggle.setAttribute('aria-label', player.paused ? 'Play video' : 'Pause video');
      };
      player.on('play', sync);
      player.on('pause', sync);
      player.on('ended', sync);
      player.on('ready', () => { if (dialog.open) player.play()?.catch(() => {}); });
    });
  });
});
