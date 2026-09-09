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
  const move = (direction) => scroller.scrollBy({ left: direction * Math.max(220, scroller.clientWidth * 0.65), behavior: 'smooth' });
  previous.addEventListener('click', () => move(-1));
  next.addEventListener('click', () => move(1));

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
  const loadPosts = async (category, page = 1, fallbackUrl = '', updateHistory = true) => {
    results.setAttribute('aria-busy', 'true');
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
      if (updateHistory) updateUrl(category, payload.data.currentPage);
    } catch (error) {
      if (fallbackUrl) window.location.href = fallbackUrl;
    } finally {
      results.removeAttribute('aria-busy');
      results.classList.remove('is-loading');
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
    loadPosts(active?.dataset.blogCategory || '', page, control.href || '');
  });
  window.addEventListener('popstate', () => {
    const url = new URL(window.location.href);
    const pageMatch = url.pathname.match(/\/page\/(\d+)/);
    loadPosts(url.searchParams.get('blog_category') || '', Number(pageMatch?.[1] || 1), '', false);
  });
});
