document.querySelectorAll('[data-contact-videos]').forEach((section) => {
  const cards = [...section.querySelectorAll('.contact-video-card')];

  cards.forEach((card) => {
    const video = card.querySelector('video');
    const button = card.querySelector('[data-contact-video-play]');
    if (!video || !button) return;

    button.addEventListener('click', async () => {
      cards.forEach((otherCard) => {
        if (otherCard === card) return;
        const otherVideo = otherCard.querySelector('video');
        otherVideo?.pause();
        otherCard.classList.remove('is-playing');
      });

      if (video.paused) {
        try {
          window.aibridzeLoadVideo?.(video);
          await video.play();
          card.classList.add('is-playing');
          button.setAttribute('aria-label', 'Pause testimonial video');
        } catch (error) {
          card.classList.remove('is-playing');
        }
      } else {
        video.pause();
        card.classList.remove('is-playing');
        button.setAttribute('aria-label', 'Play testimonial video');
      }
    });

    video.addEventListener('ended', () => {
      card.classList.remove('is-playing');
      button.setAttribute('aria-label', 'Play testimonial video');
    });
  });
});
