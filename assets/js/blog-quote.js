document.querySelectorAll('.single-blog__country').forEach((field) => {
  const select = field.querySelector('select');
  const flag = field.querySelector('[data-country-flag]');
  const update = () => {
    const country = select.selectedOptions[0]?.dataset.country;
    if (!/^[a-z]{2}$/.test(country || '')) return;
    flag.src = `https://flagcdn.com/${country}.svg`;
    select.title = select.selectedOptions[0].textContent;
  };
  select.addEventListener('change', update);
  select.form?.addEventListener('reset', () => requestAnimationFrame(update));
  update();
});
