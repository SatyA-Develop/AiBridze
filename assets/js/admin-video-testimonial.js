(() => {
  const field = document.querySelector('[data-video-testimonial-field]');
  if (!field || !window.wp?.media) return;
  const input = field.querySelector('[data-video-testimonial-input]');
  const preview = field.querySelector('[data-video-testimonial-preview]');
  const remove = field.querySelector('[data-video-testimonial-remove]');
  let frame;

  field.querySelector('[data-video-testimonial-select]').addEventListener('click', () => {
    frame ||= wp.media({ title: 'Choose testimonial video', button: { text: 'Use this video' }, library: { type: 'video' }, multiple: false });
    frame.off('select').on('select', () => {
      const selected = frame.state().get('selection').first().toJSON();
      input.value = selected.url;
      preview.src = selected.url;
      preview.style.display = 'block';
      remove.hidden = false;
    });
    frame.open();
  });

  remove.addEventListener('click', () => {
    input.value = '';
    preview.removeAttribute('src');
    preview.style.display = 'none';
    remove.hidden = true;
  });
})();
