document.addEventListener('click', function (event) {
  const mediaButton = event.target.closest('[data-service-media]');
  if (mediaButton) {
    const input = mediaButton.parentElement.querySelector('input');
    const frame = wp.media({ title: 'Choose image', multiple: false, library: { type: 'image' } });
    frame.on('select', function () { input.value = frame.state().get('selection').first().toJSON().url; });
    frame.open(); return;
  }
  const add = event.target.closest('[data-row-add]');
  if (add) {
    const repeater = add.closest('[data-repeater]');
    const template = repeater.querySelector('.is-template');
    const index = Date.now().toString();
    const row = template.cloneNode(true);
    row.hidden = false; row.classList.remove('is-template');
    row.innerHTML = row.innerHTML.replaceAll('__INDEX__', index);
    repeater.querySelector('.service-repeater__rows').insertBefore(row, template); return;
  }
  const row = event.target.closest('[data-row]');
  if (!row) return;
  if (event.target.closest('[data-row-remove]')) row.remove();
  if (event.target.closest('[data-row-up]') && row.previousElementSibling) row.parentElement.insertBefore(row, row.previousElementSibling);
  if (event.target.closest('[data-row-down]') && row.nextElementSibling && !row.nextElementSibling.classList.contains('is-template')) row.parentElement.insertBefore(row.nextElementSibling, row);
});
