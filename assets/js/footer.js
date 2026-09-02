(() => {
  const footer = document.querySelector('.site-footer');

  if (!footer) return;

  const toggles = footer.querySelectorAll('.site-footer__toggle');

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const column = toggle.closest('.site-footer__column');
      const willOpen = !column.classList.contains('is-open');

      footer.querySelectorAll('.site-footer__column.is-open').forEach((openColumn) => {
        openColumn.classList.remove('is-open');
        openColumn.querySelector('.site-footer__toggle')?.setAttribute('aria-expanded', 'false');
      });

      column.classList.toggle('is-open', willOpen);
      toggle.setAttribute('aria-expanded', String(willOpen));
    });
  });
})();
