(() => {
  const field = document.querySelector('[data-video-testimonial-field]');
  if (!field || !window.wp?.media) return;
  const input = field.querySelector('[data-video-testimonial-input]');
  const preview = field.querySelector('[data-video-testimonial-preview]');
  const remove = field.querySelector('[data-video-testimonial-remove]');
  let frame;
  const external = field.querySelector('[data-video-testimonial-external]');
  const update = () => {
    let youtube = false;
    try { youtube = /^(www\.|m\.)?(youtube\.com|youtu\.be|youtube-nocookie\.com)$/.test(new URL(input.value).hostname); } catch (_) {}
    preview.pause();
    preview.removeAttribute('src');
    preview.style.display = input.value && !youtube ? 'block' : 'none';
    if (input.value && !youtube) preview.src = input.value;
    external.hidden = !youtube;
    remove.hidden = !input.value;
  };
  input.addEventListener('input', update);
  update();

  field.querySelector('[data-video-testimonial-select]').addEventListener('click', () => {
    frame ||= wp.media({ title: 'Choose testimonial video', button: { text: 'Use this video' }, library: { type: 'video' }, multiple: false });
    frame.off('select').on('select', () => {
      const selected = frame.state().get('selection').first().toJSON();
      input.value = selected.url;
      update();
    });
    frame.open();
  });

  remove.addEventListener('click', () => {
    input.value = '';
    update();
  });
})();
