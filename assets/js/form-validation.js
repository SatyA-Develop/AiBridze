(() => {
  const selector = 'input[type="email"]';
  const pattern = '[^\\s@]+@[^\\s@]+\\.[^\\s@]+';
  const validate = (input) => {
    input.pattern = pattern;
    input.setCustomValidity('');
    const value = input.value;
    const parts = value.split('@');
    const domain = parts[1] || '';
    const valid = parts.length === 2 && /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~\-]+$/.test(parts[0]) &&
      domain.includes('.') && domain.split('.').every(label =>
        /^[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/.test(label));
    if (value && (!valid || input.validity.typeMismatch || input.validity.patternMismatch)) {
      input.setCustomValidity('Please enter a valid email address, such as name@example.com.');
    }
  };
  // All lead/application forms use the same inline feedback, including popups.
  const inlineForms = new WeakSet();
  const errors = new WeakMap();
  let errorId = 0;
  const showError = (field) => {
    if (field.matches(selector)) validate(field);
    let error = errors.get(field);
    if (!error) {
      error = document.createElement('small');
      error.className = 'form-field-error';
      error.id = `form-field-error-${++errorId}`;
      error.setAttribute('aria-live', 'polite');
      field.insertAdjacentElement('afterend', error);
      errors.set(field, error);
      field.setAttribute('aria-describedby', [field.getAttribute('aria-describedby'), error.id].filter(Boolean).join(' '));
    }
    const message = field.validity.valueMissing
      ? ({ full_name: 'Please enter your full name.', email: 'Please enter your email address.', message: 'Please tell us how we can help you.' }[field.name] || 'Please complete this field.')
      : field.validationMessage;
    error.textContent = message;
    error.hidden = !message;
    field.setAttribute('aria-invalid', String(Boolean(message)));
    return !message;
  };
  const setupForm = (form) => {
    if (inlineForms.has(form) || !form.querySelector(selector)) return;
    inlineForms.add(form);
    form.noValidate = true;
    form.addEventListener('focusout', event => {
      if (event.target.matches('input:not([type="hidden"]), textarea, select') && event.target.willValidate) showError(event.target);
    });
    ['input', 'change'].forEach(type => form.addEventListener(type, event => {
      if (event.target.willValidate && event.target.hasAttribute('aria-invalid')) showError(event.target);
    }));
    form.addEventListener('reset', () => {
      form.querySelectorAll('.form-field-error').forEach(error => { error.textContent = ''; error.hidden = true; });
      form.querySelectorAll('[aria-invalid]').forEach(field => field.removeAttribute('aria-invalid'));
    });
  };
  const scan = (root) => {
    if (root.matches?.(selector)) validate(root);
    root.querySelectorAll?.(selector).forEach(validate);
    if (root.matches?.('form')) setupForm(root);
    root.querySelectorAll?.('form').forEach(setupForm);
    const parentForm = root.closest?.('form');
    if (parentForm) setupForm(parentForm);
  };
  scan(document);
  ['input', 'change', 'focusout', 'invalid'].forEach(type => {
    document.addEventListener(type, event => {
      if (event.target.matches?.(selector)) validate(event.target);
    }, true);
  });
  document.addEventListener('submit', event => {
    if (inlineForms.has(event.target)) {
      const fields = [...event.target.elements].filter(field => field.willValidate && field.matches('input, textarea, select'));
      const invalidFields = fields.filter(field => !showError(field));
      if (invalidFields.length) {
        event.preventDefault();
        event.stopImmediatePropagation();
        invalidFields[0].focus();
      }
      return;
    }
  }, true);
  document.addEventListener('reset', event => {
    event.target.querySelectorAll(selector).forEach(input => input.setCustomValidity(''));
  }, true);
  new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(scan)))
    .observe(document.body, { childList: true, subtree: true });
})();
