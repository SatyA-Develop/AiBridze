document.querySelectorAll('[data-careers-accordion]').forEach((section) => {
  const items = section.querySelectorAll('[data-careers-item]');
  const image = section.querySelector('[data-careers-image]');
  if (!items.length || !image) return;

  items.forEach((item) => {
    const trigger = item.querySelector('[data-careers-trigger]');
    const panel = item.querySelector('.careers-accordion__panel');
    if (!trigger || !panel) return;
    trigger.addEventListener('click', () => {
      if (item.classList.contains('is-active')) return;
      items.forEach((other) => {
        const active = other === item;
        other.classList.toggle('is-active', active);
        other.querySelector('[data-careers-trigger]')?.setAttribute('aria-expanded', active ? 'true' : 'false');
        const otherPanel = other.querySelector('.careers-accordion__panel');
        if (otherPanel) otherPanel.hidden = !active;
      });
      const nextImage = trigger.dataset.image;
      if (!nextImage) return;
      image.classList.add('is-changing');
      const preload = new Image();
      preload.onload = () => {
        image.src = nextImage;
        requestAnimationFrame(() => image.classList.remove('is-changing'));
      };
      preload.src = nextImage;
    });
  });
});

document.querySelectorAll('[data-career-openings]').forEach((section) => {
  const cards = [...section.querySelectorAll('[data-career-card]')];
  const search = section.querySelector('[data-career-search]');
  const previous = section.querySelector('[data-career-prev]');
  const next = section.querySelector('[data-career-next]');
  const count = section.querySelector('[data-career-count]');
  const empty = section.querySelector('[data-career-empty]');
  const modal = document.querySelector('[data-career-modal]');
  if (!cards.length || !search || !previous || !next || !count || !empty || !modal) return;

  const pageSize = 5;
  let page = 1;
  let filtered = cards;
  let lastFocused = null;
  const dialog = modal.querySelector('.career-modal__dialog');
  const vacancyView = modal.querySelector('[data-vacancy-view]');
  const applicationForm = modal.querySelector('[data-application-form]');
  const modalHeading = modal.querySelector('[data-modal-heading]');
  const opportunityInput = modal.querySelector('[data-application-opportunity]');
  const positionSelect = modal.querySelector('[data-application-position]');
  const applicationStatus = modal.querySelector('[data-application-status]');

  const render = () => {
    const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
    page = Math.min(page, totalPages);
    const start = (page - 1) * pageSize;
    const visible = new Set(filtered.slice(start, start + pageSize));
    cards.forEach((card) => { card.hidden = !visible.has(card); });
    empty.hidden = filtered.length > 0;
    previous.disabled = page === 1;
    next.disabled = page === totalPages || filtered.length === 0;
    count.textContent = filtered.length ? `Showing ${start + 1}–${Math.min(start + pageSize, filtered.length)} of ${filtered.length} openings` : 'Showing 0 openings';
  };

  const filter = () => {
    const term = search.value.trim().toLowerCase();
    filtered = cards.filter((card) => !term || (card.dataset.search || '').includes(term));
    page = 1;
    render();
  };

  search.addEventListener('input', filter);
  previous.addEventListener('click', () => { if (page > 1) { page -= 1; render(); section.scrollIntoView({ behavior: 'smooth', block: 'start' }); } });
  next.addEventListener('click', () => { if (page * pageSize < filtered.length) { page += 1; render(); section.scrollIntoView({ behavior: 'smooth', block: 'start' }); } });

  const closeModal = () => {
    modal.classList.add('is-closing');
    window.setTimeout(() => {
      modal.hidden = true;
      modal.classList.remove('is-open', 'is-closing');
      document.documentElement.classList.remove('career-modal-open');
      lastFocused?.focus();
    }, 240);
  };

  const showVacancy = (id, opener) => {
    const template = section.querySelector(`[data-career-detail="${CSS.escape(id)}"]`);
    if (!template) return;
    const content = template.content;
    modal.querySelector('[data-vacancy-title]').textContent = content.querySelector('[data-title]')?.textContent || '';
    modal.querySelector('[data-vacancy-experience]').textContent = content.querySelector('[data-experience]')?.textContent || '';
    modal.querySelector('[data-vacancy-location]').textContent = content.querySelector('[data-location]')?.textContent || '';
    modal.querySelector('[data-vacancy-description]').innerHTML = content.querySelector('[data-description]')?.innerHTML || '';
    opportunityInput.value = id;
    positionSelect.value = id;
    vacancyView.hidden = false;
    applicationForm.hidden = true;
    modalHeading.textContent = 'Vacancy Details';
    applicationStatus.textContent = '';
    lastFocused = opener;
    modal.hidden = false;
    document.documentElement.classList.add('career-modal-open');
    requestAnimationFrame(() => {
      modal.classList.add('is-open');
      dialog?.focus?.();
    });
  };

  section.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-career-open]');
    if (opener) showVacancy(opener.dataset.careerOpen || '', opener);
  });
  modal.querySelectorAll('[data-career-close]').forEach((control) => control.addEventListener('click', closeModal));
  modal.querySelector('[data-career-apply]')?.addEventListener('click', () => {
    vacancyView.hidden = true;
    applicationForm.hidden = false;
    modalHeading.textContent = 'Application Form';
    applicationForm.querySelector('input:not([type="hidden"])')?.focus();
  });
  document.querySelectorAll('[data-career-general-apply]').forEach((control) => control.addEventListener('click', () => {
    lastFocused = control;
    opportunityInput.value = '0';
    positionSelect.value = '0';
    vacancyView.hidden = true;
    applicationForm.hidden = false;
    modalHeading.textContent = 'Application Form';
    applicationStatus.textContent = '';
    modal.hidden = false;
    document.documentElement.classList.add('career-modal-open');
    requestAnimationFrame(() => {
      modal.classList.add('is-open');
      applicationForm.querySelector('input:not([type="hidden"])')?.focus();
    });
  }));
  positionSelect.addEventListener('change', () => { opportunityInput.value = positionSelect.value; });
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !modal.hidden) closeModal(); });

  applicationForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submit = applicationForm.querySelector('[type="submit"]');
    const resume = applicationForm.querySelector('[name="resume"]');
    if (resume.files[0] && resume.files[0].size > 2 * 1024 * 1024) {
      applicationStatus.textContent = 'The resume must be no larger than 2 MB.';
      applicationStatus.classList.add('is-error');
      return;
    }
    submit.disabled = true;
    applicationStatus.classList.remove('is-error', 'is-success');
    applicationStatus.textContent = 'Submitting your application…';
    try {
      const response = await fetch(applicationForm.action, { method: 'POST', body: new FormData(applicationForm), credentials: 'same-origin' });
      const payload = await response.json();
      if (!payload.success) throw new Error(payload.data?.message || 'Unable to submit the application.');
      applicationStatus.textContent = payload.data.message;
      applicationStatus.classList.add('is-success');
      applicationForm.reset();
      opportunityInput.value = positionSelect.value;
    } catch (error) {
      applicationStatus.textContent = error.message;
      applicationStatus.classList.add('is-error');
    } finally {
      submit.disabled = false;
    }
  });

  render();
});
