(() => {
  'use strict';

  // These restrictions deter casual copying; browser tools remain accessible.
  const isEditable = (target) => {
    const element = target instanceof Element ? target : target?.parentElement;
    return Boolean(element && (element.isContentEditable || element.closest('input, textarea, select')));
  };

  for (const type of ['copy', 'cut', 'contextmenu', 'selectstart', 'dragstart']) {
    document.addEventListener(type, (event) => {
      if (!isEditable(event.target)) event.preventDefault();
    });
  }

  document.addEventListener('keydown', (event) => {
    const key = event.key.toLowerCase();
    const command = event.ctrlKey || event.metaKey;
    const inspectShortcut = key === 'f12'
      || (event.ctrlKey && event.shiftKey && ['i', 'j', 'c'].includes(key))
      || (event.metaKey && event.altKey && ['i', 'j', 'c'].includes(key))
      || (command && key === 'u');

    if (inspectShortcut || (command && ['c', 'x'].includes(key) && !isEditable(event.target))) {
      event.preventDefault();
    }
  });
})();
