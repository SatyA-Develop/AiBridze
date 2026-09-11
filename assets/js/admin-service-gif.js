(() => {
  const field = document.querySelector('[data-service-gif-field]');
  if (!field || !window.wp?.media) return;
  const input = field.querySelector('[data-gif-input]');
  const preview = field.querySelector('[data-gif-preview]');
  const remove = field.querySelector('[data-gif-remove]');
  const error = field.querySelector('[data-gif-error]');
  let frame;
  field.querySelector('[data-gif-select]').addEventListener('click', () => {
    if (!frame) {
      frame = wp.media({ title: 'Choose an animated service GIF', button: { text: 'Use this GIF' }, library: { type: 'image/gif' }, multiple: false });
      frame.on('select', () => {
        const attachment = frame.state().get('selection').first()?.toJSON();
        if (!attachment || attachment.mime !== 'image/gif') {
          error.hidden = false;
          return;
        }
        input.value = attachment.id;
        preview.src = attachment.url;
        preview.hidden = remove.hidden = false;
        error.hidden = true;
      });
    }
    frame.open();
  });
  remove.addEventListener('click', () => {
    input.value = '';
    preview.removeAttribute('src');
    preview.hidden = remove.hidden = error.hidden = true;
  });
})();
