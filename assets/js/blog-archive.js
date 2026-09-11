document.querySelectorAll('[data-blog-categories]').forEach((section) => {
  const scroller = section.querySelector('[data-category-scroller]');
  const previous = section.querySelector('[data-category-previous]');
  const next = section.querySelector('[data-category-next]');
  const results = document.querySelector('[data-blog-results]');
  const grid = results?.querySelector('[data-blog-grid]');
  const heading = results?.querySelector('[data-blog-results-title]');
  const pagination = results?.querySelector('[data-blog-pagination]');
  if (!scroller || !previous || !next || !results || !grid || !heading || !pagination) return;

  const escapeHtml = (value = '') => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character]);
  const categories = Array.from(scroller.querySelectorAll('[data-blog-category]'));
  const move = (direction) => {
    const index = categories.findIndex((link) => link.classList.contains('is-active'));
    const link = categories[index + direction];
    if (link && results.getAttribute('aria-busy') !== 'true') link.click();
  };
  previous.addEventListener('click', () => move(-1));
  next.addEventListener('click', () => move(1));
  const updateCategoryArrows = () => {
    const index = categories.findIndex((link) => link.classList.contains('is-active'));
    const loading = results.getAttribute('aria-busy') === 'true';
    previous.disabled = loading || index <= 0;
    next.disabled = loading || index >= categories.length - 1;
  };
  scroller.addEventListener('scroll', updateCategoryArrows, { passive: true });
  const categoryResize = new ResizeObserver(updateCategoryArrows);
  categoryResize.observe(scroller);
  Array.from(scroller.children).forEach((item) => categoryResize.observe(item));
  document.fonts.ready.then(updateCategoryArrows);
  updateCategoryArrows();

  const pageSequence = (current, total) => {
    if (total <= 7) return Array.from({ length: total }, (_, index) => index + 1);
    if (current <= 3 || current >= total - 2) return [1, 2, 3, '…', total - 2, total - 1, total];
    return [1, '…', current - 1, current, current + 1, '…', total];
  };
  const pageControl = (label, page, className, disabled = false) => disabled ? `<span class="page-numbers ${className} is-disabled">${label}</span>` : `<button class="page-numbers ${className}" type="button" data-blog-page="${page}">${label}</button>`;
  const renderPagination = (current, total) => {
    if (total < 2) { pagination.innerHTML = ''; return; }
    const desktopNumbers = pageSequence(current, total).map((page) => page === '…' ? '<span class="page-numbers dots">…</span>' : page === current ? `<span class="page-numbers current">${page}</span>` : `<button class="page-numbers" type="button" data-blog-page="${page}">${page}</button>`).join('');
    const mobileNumbers = `<span class="page-numbers current">${current}</span>${current < total ? `<button class="page-numbers" type="button" data-blog-page="${current + 1}">${current + 1}</button>` : ''}${current + 1 < total ? '<span class="page-numbers dots">…</span>' : ''}`;
    const prev = pageControl('Previous', current - 1, 'prev', current === 1);
    const nextControl = pageControl('Next', current + 1, 'next', current === total);
    pagination.innerHTML = `<nav class="blog-pagination blog-pagination--desktop" aria-label="Blog pagination">${prev}<div class="blog-pagination__numbers">${desktopNumbers}</div>${nextControl}</nav><nav class="blog-pagination blog-pagination--mobile" aria-label="Mobile blog pagination">${prev}<div class="blog-pagination__numbers">${mobileNumbers}</div>${nextControl}</nav>`;
  };
  const renderCards = (items) => {
    grid.innerHTML = items.map((item) => `<article class="all-blog-posts__card"><a class="all-blog-posts__image" href="${escapeHtml(item.url)}"><img src="${escapeHtml(item.image)}" width="405" height="240" alt="${escapeHtml(item.title)}"></a><div class="recent-posts__meta"><span>${escapeHtml(item.author)}</span><i aria-hidden="true"></i><time datetime="${escapeHtml(item.datetime)}">${escapeHtml(item.date)}</time></div><h3><a href="${escapeHtml(item.url)}">${escapeHtml(item.title)}</a></h3><p>${escapeHtml(item.excerpt)}</p><span class="recent-posts__badge">${escapeHtml(item.category)}</span></article>`).join('');
  };
  const updateUrl = (category, page) => {
    const url = new URL(window.location.href);
    url.pathname = url.pathname.replace(/\/page\/\d+\/?$/, '/');
    category ? url.searchParams.set('blog_category', category) : url.searchParams.delete('blog_category');
    if (page > 1) url.pathname = `${url.pathname.replace(/\/$/, '')}/page/${page}/`;
    window.history.pushState({ category, page }, '', url);
  };
  const loadPosts = async (category, page = 1, fallbackUrl = '', updateHistory = true, scrollToResults = false) => {
    results.setAttribute('aria-busy', 'true');
    updateCategoryArrows();
    results.classList.add('is-loading');
    const body = new FormData();
    body.append('action', 'aibridze_filter_blogs');
    body.append('nonce', aibridzeBlogArchive.nonce);
    body.append('category', category);
    body.append('paged', page);
    try {
      const response = await fetch(aibridzeBlogArchive.ajaxUrl, { method: 'POST', body, credentials: 'same-origin' });
      const payload = await response.json();
      if (!payload.success) throw new Error('Blog filtering failed');
      renderCards(payload.data.items);
      renderPagination(payload.data.currentPage, payload.data.totalPages);
      heading.textContent = payload.data.heading;
      scroller.querySelectorAll('[data-blog-category]').forEach((link) => link.classList.toggle('is-active', link.dataset.blogCategory === category));
      const active = categories.find((link) => link.classList.contains('is-active'));
      if (active) {
        const bounds = scroller.getBoundingClientRect();
        const item = active.getBoundingClientRect();
        scroller.scrollBy({ left: item.left - bounds.left - (bounds.width - item.width) / 2, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
      }
      if (updateHistory) updateUrl(category, payload.data.currentPage);
      if (scrollToResults) {
        const headerHeight = document.querySelector('.site-header')?.getBoundingClientRect().height || 0;
        window.scrollTo({
          top: Math.max(0, results.getBoundingClientRect().top + window.scrollY - headerHeight - 20),
          behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth',
        });
      }
    } catch (error) {
      if (fallbackUrl) window.location.href = fallbackUrl;
    } finally {
      results.removeAttribute('aria-busy');
      results.classList.remove('is-loading');
      updateCategoryArrows();
    }
  };
  scroller.addEventListener('click', (event) => {
    const link = event.target.closest('[data-blog-category]');
    if (!link) return;
    event.preventDefault();
    loadPosts(link.dataset.blogCategory || '', 1, link.href);
  });
  pagination.addEventListener('click', (event) => {
    const control = event.target.closest('[data-blog-page], a.page-numbers');
    if (!control || control.classList.contains('is-disabled')) return;
    event.preventDefault();
    const match = control.getAttribute('href')?.match(/\/page\/(\d+)/);
    const page = Number(control.dataset.blogPage || match?.[1] || 1);
    const active = scroller.querySelector('[data-blog-category].is-active');
    loadPosts(active?.dataset.blogCategory || '', page, control.href || '', true, true);
  });
  window.addEventListener('popstate', () => {
    const url = new URL(window.location.href);
    const pageMatch = url.pathname.match(/\/page\/(\d+)/);
    loadPosts(url.searchParams.get('blog_category') || '', Number(pageMatch?.[1] || 1), '', false);
  });
});
