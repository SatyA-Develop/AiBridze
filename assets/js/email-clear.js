(() => {
  const enhanceEmailField = (input) => {
    if (input.dataset.emailClearReady) return;
    input.dataset.emailClearReady = 'true';

    const originalParent = input.parentElement;
    if (!originalParent) return;
    const field = document.createElement('span');
    field.className = 'has-email-clear';
    originalParent.insertBefore(field, input);
    field.appendChild(input);

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'email-clear-button';
    button.setAttribute('aria-label', 'Clear email address');
    button.setAttribute('title', 'Clear email address');
    field.appendChild(button);

    const update = () => {
      button.hidden = input.value.length === 0;
    };

    button.addEventListener('click', () => {
      input.value = '';
      input.dispatchEvent(new Event('input', { bubbles: true }));
      input.dispatchEvent(new Event('change', { bubbles: true }));
      input.focus();
      update();
    });

    input.addEventListener('input', update);
    input.addEventListener('change', update);
    update();
  };

  const enhanceAll = (root = document) => {
    root.querySelectorAll('form input[type="email"]').forEach(enhanceEmailField);
  };

  enhanceAll();
  new MutationObserver((records) => {
    records.forEach((record) => record.addedNodes.forEach((node) => {
      if (!(node instanceof Element)) return;
      if (node.matches('form input[type="email"]')) enhanceEmailField(node);
      enhanceAll(node);
    }));
  }).observe(document.body, { childList: true, subtree: true });
})();
