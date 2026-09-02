(() => {
  const header = document.querySelector('[data-site-header]');

  if (!header) return;

  const toggle = header.querySelector('[data-menu-toggle]');
  const navigation = header.querySelector('[data-primary-navigation]');
  const tabButtons = header.querySelectorAll('[data-mega-tab]');
  const megaLinks = header.querySelectorAll('.menu-item--mega > a');
  const megaItems = header.querySelectorAll('.menu-item--mega');
  const mobileQuery = window.matchMedia('(max-width: 1100px)');
  let closeTimer;

  const closeMenu = () => {
    toggle.setAttribute('aria-expanded', 'false');
    header.classList.remove('is-menu-open');
    document.documentElement.classList.remove('has-mobile-menu');
    megaItems.forEach((item) => item.classList.remove('is-expanded', 'is-mega-open'));
  };

  toggle.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!isOpen));
    header.classList.toggle('is-menu-open', !isOpen);
    document.documentElement.classList.toggle('has-mobile-menu', !isOpen);
  });

  tabButtons.forEach((button) => {
    const activatePanel = () => {
      const menu = button.closest('[data-mega-menu]');
      const panelId = button.dataset.megaTab;

      menu.querySelectorAll('[data-mega-tab]').forEach((tab) => {
        const isCurrent = tab === button;
        tab.classList.toggle('is-active', isCurrent);
        tab.setAttribute('aria-selected', String(isCurrent));
      });

      menu.querySelectorAll('.mega-menu__panel').forEach((panel) => {
        panel.classList.toggle('is-active', panel.id === panelId);
      });
    };

    button.addEventListener('focus', activatePanel);
    button.addEventListener('click', activatePanel);
  });

  megaLinks.forEach((link) => {
    link.addEventListener('click', (event) => {
      if (mobileQuery.matches && !link.parentElement.classList.contains('is-expanded')) {
        event.preventDefault();
        header.querySelectorAll('.menu-item--mega.is-expanded').forEach((item) => item.classList.remove('is-expanded'));
        link.parentElement.classList.add('is-expanded');
      }
    });
  });

  megaItems.forEach((item) => {
    item.addEventListener('mouseenter', () => {
      window.clearTimeout(closeTimer);
      megaItems.forEach((otherItem) => otherItem.classList.toggle('is-mega-open', otherItem === item));
    });

    item.addEventListener('mouseleave', () => {
      closeTimer = window.setTimeout(() => item.classList.remove('is-mega-open'), 700);
    });

    item.querySelector('[data-mega-menu]')?.addEventListener('mouseenter', () => {
      window.clearTimeout(closeTimer);
      item.classList.add('is-mega-open');
    });
  });

  navigation.addEventListener('click', (event) => {
    if (!event.defaultPrevented && event.target.closest('a')) closeMenu();
  });

  mobileQuery.addEventListener('change', (event) => {
    if (!event.matches) closeMenu();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeMenu();
  });
})();
