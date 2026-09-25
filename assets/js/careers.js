document.querySelectorAll('[data-careers-accordion]').forEach((section) => {
  const items = section.querySelectorAll('[data-careers-item]');
  const image = section.querySelector('[data-careers-image]');
  if (!items.length || !image) return;

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const animations = new WeakMap();
  const setPanelOpen = (panel, open) => {
    const height = panel.getBoundingClientRect().height;
    animations.get(panel)?.cancel();
    panel.hidden = false;
    panel.inert = !open;
    if (reducedMotion.matches) {
      panel.hidden = !open;
      return;
    }
    const animation = panel.animate(
      [{ height: `${height}px` }, { height: `${open ? panel.scrollHeight : 0}px` }],
      { duration: 320, easing: 'cubic-bezier(0.22, 1, 0.36, 1)' }
    );
    animations.set(panel, animation);
    animation.onfinish = () => {
      panel.hidden = !open;
      animations.delete(panel);
    };
  };

  items.forEach((item) => {
    const trigger = item.querySelector('[data-careers-trigger]');
    const panel = item.querySelector('.careers-accordion__panel');
    if (!trigger || !panel) return;
    trigger.addEventListener('click', () => {
      const opening = !item.classList.contains('is-active');
      items.forEach((other) => {
        const active = opening && other === item;
        if (other.classList.contains('is-active') === active) return;
        other.classList.toggle('is-active', active);
        other.querySelector('[data-careers-trigger]')?.setAttribute('aria-expanded', active ? 'true' : 'false');
        const otherPanel = other.querySelector('.careers-accordion__panel');
        if (otherPanel) setPanelOpen(otherPanel, active);
      });
      const nextImage = trigger.dataset.image;
      if (!opening || !nextImage) {
        image.classList.remove('is-changing');
        return;
      }
      image.classList.add('is-changing');
      const preload = new Image();
      preload.onload = () => {
        if (!item.classList.contains('is-active')) return;
        image.src = nextImage;
        requestAnimationFrame(() => image.classList.remove('is-changing'));
      };
      preload.onerror = () => image.classList.remove('is-changing');
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
    if (window.aibridzeFormState.isSubmitting(applicationForm)) return;
    const resume = applicationForm.querySelector('[name="resume"]');
    if (resume.files[0] && resume.files[0].size > 2 * 1024 * 1024) {
      applicationStatus.textContent = 'The resume must be no larger than 2 MB.';
      applicationStatus.classList.add('is-error');
      return;
    }
    window.aibridzeFormState.setSubmitting(applicationForm, true);
    applicationStatus.classList.remove('is-error', 'is-success');
    applicationStatus.textContent = 'Submitting your application…';
    try {
      const response = await fetch(applicationForm.getAttribute('action'), { method: 'POST', body: new FormData(applicationForm), credentials: 'same-origin' });
      if (!response.headers.get('content-type')?.includes('application/json')) {
        throw new Error('Unable to submit right now. Please try again or email career@aibridze.com.');
      }
      const payload = await response.json();
      if (!payload.success) throw new Error(payload.data?.message || 'Unable to submit the application.');
      applicationStatus.textContent = payload.data.message;
      applicationStatus.classList.add('is-success');
      applicationStatus.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      applicationForm.reset();
      opportunityInput.value = positionSelect.value;
    } catch (error) {
      applicationStatus.textContent = error.message;
      applicationStatus.classList.add('is-error');
    } finally {
      window.aibridzeFormState.setSubmitting(applicationForm, false);
    }
  });

  render();
});
