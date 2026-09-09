(() => {
  const field = document.querySelector('[data-service-icon-field]');

  if (!field || !window.wp?.media) return;

  const input = field.querySelector('[data-service-icon-input]');
  const preview = field.querySelector('.aibridze-icon-field__preview');
  const selectButton = field.querySelector('[data-service-icon-select]');
  const removeButton = field.querySelector('[data-service-icon-remove]');
  const defaultIcon = preview.src;
  let frame;

  selectButton.addEventListener('click', () => {
    if (!frame) {
      frame = wp.media({
        title: 'Choose a service icon',
        button: { text: 'Use this icon' },
        library: { type: 'image' },
        multiple: false,
      });

      frame.on('select', () => {
        const attachment = frame.state().get('selection').first().toJSON();
        input.value = attachment.id;
        preview.src = attachment.sizes?.thumbnail?.url || attachment.url;
        removeButton.hidden = false;
      });
    }

    frame.open();
  });

  removeButton.addEventListener('click', () => {
    input.value = '';
    preview.src = defaultIcon;
    removeButton.hidden = true;
  });
})();
