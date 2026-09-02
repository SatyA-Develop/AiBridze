(() => {
  document.querySelectorAll('[data-faq-accordion]').forEach((accordion) => {
    accordion.addEventListener('click', (event) => {
      const button = event.target.closest('[data-faq-toggle]');
      if (!button) return;
      const item = button.closest('.faq-item');
      const answer = item.querySelector('.faq-item__answer');
      const willOpen = !item.classList.contains('is-open');

      accordion.querySelectorAll('.faq-item').forEach((other) => {
        other.classList.remove('is-open');
        other.querySelector('[data-faq-toggle]').setAttribute('aria-expanded', 'false');
        other.querySelector('.faq-item__answer').hidden = true;
      });

      if (willOpen) {
        item.classList.add('is-open');
        button.setAttribute('aria-expanded', 'true');
        answer.hidden = false;
      }
    });
  });
})();
