document.addEventListener('DOMContentLoaded', function () {
	const chatbotProcess = document.querySelector('.service-detail--chatbot .service-process__track');
	if (chatbotProcess) chatbotProcess.scrollLeft = 0;

	const visionProcess = document.querySelector('[data-vision-process]');
	if (visionProcess) {
		const timeline = visionProcess.querySelector('.service-process--vision__timeline');
		const steps = Array.from(visionProcess.querySelectorAll('[data-vision-step]'));
		let ticking = false;
		const updateVisionProcess = function () {
			ticking = false;
			if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
				timeline.style.setProperty('--vision-progress', '1');
				steps.forEach((step) => step.classList.add('is-reached', 'is-active'));
				return;
			}
			if (window.innerWidth <= 1099) {
				const activationLine = window.innerHeight * 0.5;
				let activeIndex = 0;
				steps.forEach((step, index) => {
					const node = step.querySelector('.service-process--vision__node');
					if (node && node.getBoundingClientRect().top <= activationLine) activeIndex = index;
				});
				const progress = steps.length > 1 ? activeIndex / (steps.length - 1) : 1;
				timeline.style.setProperty('--vision-progress', String(progress));
				steps.forEach((step, index) => {
					step.classList.toggle('is-reached', index <= activeIndex);
					step.classList.toggle('is-active', index === activeIndex);
				});
				return;
			}
			const rect = visionProcess.getBoundingClientRect();
			const range = Math.max(1, visionProcess.offsetHeight - window.innerHeight);
			const progress = Math.max(0, Math.min(1, -rect.top / range));
			const activeIndex = Math.min(steps.length - 1, Math.max(0, Math.round(progress * (steps.length - 1))));
			const steppedProgress = steps.length > 1 ? activeIndex / (steps.length - 1) : 1;
			timeline.style.setProperty('--vision-progress', String(steppedProgress));
			steps.forEach((step, index) => {
				step.classList.toggle('is-reached', index <= activeIndex);
				step.classList.toggle('is-active', index === activeIndex);
			});
		};
		const requestVisionUpdate = function () {
			if (!ticking) { ticking = true; window.requestAnimationFrame(updateVisionProcess); }
		};
		window.addEventListener('scroll', requestVisionUpdate, { passive: true });
		window.addEventListener('resize', requestVisionUpdate, { passive: true });
		updateVisionProcess();
	}

	const visionTechnologyAccordions = Array.from(document.querySelectorAll('.service-tech-stack__vision-mobile details'));
	visionTechnologyAccordions.forEach((item) => {
		item.addEventListener('toggle', function () {
			if (!item.open) return;
			visionTechnologyAccordions.forEach((sibling) => {
				if (sibling !== item) sibling.open = false;
			});
		});
	});
});

function activateRagProcessStep(selected) {
  const section = selected.closest('.service-process--rag');
  if (!section) return;

  section.querySelectorAll('.service-process--rag__step').forEach((step) => {
    const active = step === selected;
    step.classList.toggle('is-active', active);
    const trigger = step.querySelector('[data-rag-process-step]');
    if (trigger) trigger.setAttribute('aria-expanded', active ? 'true' : 'false');
  });
}

document.addEventListener('mouseover', function (event) {
  if (!window.matchMedia('(min-width: 901px) and (hover: hover)').matches) return;
  const selected = event.target.closest('.service-process--rag__step');
  if (selected) activateRagProcessStep(selected);
});

document.addEventListener('click', function (event) {
	const industriesMore = event.target.closest('[data-industries-more]');
	if (industriesMore) {
		const section = industriesMore.closest('.service-industries');
		if (section.closest('.service-detail--rag')) {
			section.classList.add('is-expanded');
			industriesMore.setAttribute('aria-expanded', 'true');
			return;
		}
		const expanded = !section.classList.contains('is-expanded');
		section.classList.toggle('is-expanded', expanded);
		industriesMore.setAttribute('aria-expanded', expanded ? 'true' : 'false');
		const label = industriesMore.querySelector('[data-more-label]');
		if (label) label.textContent = expanded ? 'View Less' : 'View More';
		return;
	}
  const ragProcessStep = event.target.closest('[data-rag-process-step]');
  if (ragProcessStep) {
    const selected = ragProcessStep.closest('.service-process--rag__step');
    activateRagProcessStep(selected);
    return;
  }
  const faqSummary = event.target.closest('.service-faq__item > summary');
  if (faqSummary) {
    const current = faqSummary.parentElement;
    current.closest('.service-faq__list').querySelectorAll('.service-faq__item[open]').forEach((item) => {
      if (item !== current) item.removeAttribute('open');
    });
    return;
  }
  const processDot = event.target.closest('[data-process-page]');
  if (processDot) {
    const section = processDot.closest('[data-service-process]');
    const track = section.querySelector('.service-process__track');
    const page = Number(processDot.dataset.processPage || 0);
	const cardsPerPage = section.closest('.service-detail--chatbot') ? 2 : 1;
	const pageCard = track.children[page * cardsPerPage];
	const firstCard = track.children[0];
	const targetLeft = pageCard && firstCard ? pageCard.offsetLeft - firstCard.offsetLeft : page * track.clientWidth;
	track.scrollTo({ left: targetLeft, behavior: 'smooth' });
    section.querySelectorAll('[data-process-page]').forEach((dot, index) => dot.classList.toggle('is-active', index === page));
    return;
  }
  const capabilityDot = event.target.closest('[data-capability-page]');
  if (capabilityDot) {
    const section = capabilityDot.closest('[data-service-capabilities]');
    const track = section.querySelector('.service-capabilities__track');
    const page = Number(capabilityDot.dataset.capabilityPage || 0);
	const pageCard = track.children[page * 2];
	const targetLeft = pageCard ? pageCard.offsetLeft - track.offsetLeft : page * track.clientWidth;
	track.scrollTo({ left: targetLeft, behavior: 'smooth' });
    section.querySelectorAll('[data-capability-page]').forEach((dot, index) => dot.classList.toggle('is-active', index === page));
    return;
  }
	const capabilityArrow = event.target.closest('[data-capability-direction]');
	if (capabilityArrow) {
		const section = capabilityArrow.closest('[data-service-capabilities]');
		const track = section.querySelector('.service-capabilities__track');
		const columns = Array.from(track.children);
		let page = columns.reduce((closest, column, index) => Math.abs((column.offsetLeft - track.offsetLeft) - track.scrollLeft) < closest.distance ? { index, distance: Math.abs((column.offsetLeft - track.offsetLeft) - track.scrollLeft) } : closest, { index: 0, distance: Infinity }).index;
		page = Math.max(0, Math.min(columns.length - 1, page + (capabilityArrow.dataset.capabilityDirection === 'next' ? 1 : -1)));
		track.scrollTo({ left: columns[page].offsetLeft - track.offsetLeft, behavior: 'smooth' });
		return;
	}
  const sliderButton = event.target.closest('[data-service-slide]');
  if (sliderButton) {
    const track = sliderButton.closest('[data-service-section]').querySelector('.service-group__grid');
    const cards = track ? Array.from(track.querySelectorAll('.service-card')) : [];
    const active = cards.findIndex((card) => card.classList.contains('is-active'));
    const direction = sliderButton.dataset.serviceSlide === 'next' ? 1 : -1;
    const nextIndex = Math.min(cards.length - 1, Math.max(0, (active < 0 ? 0 : active) + direction));
    if (cards[nextIndex]) {
      cards.forEach((card, index) => card.classList.toggle('is-active', index === nextIndex));
      track.scrollTo({ left: cards[nextIndex].offsetLeft - track.offsetLeft, behavior: 'smooth' });
    }
    return;
  }
  const ragExpertiseButton = event.target.closest('[data-rag-expertise-slide]');
  if (ragExpertiseButton) {
    const section = ragExpertiseButton.closest('.service-expertise--rag');
    const track = section ? section.querySelector('.service-expertise--rag__track') : null;
    const cards = track ? Array.from(track.children) : [];
    if (!track || !cards.length) return;
    let current = 0;
    let distance = Infinity;
    cards.forEach((card, index) => {
      const cardDistance = Math.abs(card.offsetLeft - track.offsetLeft - track.scrollLeft);
      if (cardDistance < distance) { distance = cardDistance; current = index; }
    });
    const direction = ragExpertiseButton.dataset.ragExpertiseSlide === 'next' ? 1 : -1;
    const next = Math.min(cards.length - 1, Math.max(0, current + direction));
    track.scrollTo({ left: cards[next].offsetLeft - track.offsetLeft, behavior: 'smooth' });
    return;
  }
  const selectedCard = event.target.closest('.service-section--cards .service-card');
  if (selectedCard) {
    selectedCard.closest('.service-group__grid').querySelectorAll('.service-card').forEach((card) => card.classList.toggle('is-active', card === selectedCard));
    return;
  }
  const tab = event.target.closest('[data-service-tab]'); if (!tab) return;
  const section = tab.closest('[data-service-section]');
  section.querySelectorAll('[data-service-tab]').forEach((item) => {
    const active = item === tab;
    item.classList.toggle('is-active', active);
    item.setAttribute('aria-selected', active ? 'true' : 'false');
  });
  section.querySelectorAll('[data-service-panel]').forEach((panel) => panel.classList.toggle('is-hidden', panel.dataset.servicePanel !== tab.dataset.serviceTab));
});

document.addEventListener('scroll', function (event) {
  const processTrack = event.target.closest && event.target.closest('.service-process__track');
	if (processTrack && window.innerWidth <= 900) {
		const isChatbot = Boolean(processTrack.closest('.service-detail--chatbot'));
		const pageCards = Array.from(processTrack.children).filter((card, index) => index % (isChatbot ? 2 : 1) === 0);
		let page = 0;
		let distance = Infinity;
		pageCards.forEach((card, index) => {
			const cardDistance = Math.abs((card.offsetLeft - processTrack.offsetLeft) - processTrack.scrollLeft);
			if (cardDistance < distance) { distance = cardDistance; page = index; }
		});
    processTrack.closest('[data-service-process]').querySelectorAll('[data-process-page]').forEach((dot, index) => dot.classList.toggle('is-active', index === page));
  }
  const track = event.target.closest && event.target.closest('.service-capabilities__track');
  if (!track || window.innerWidth > 1000) return;
	const pageCards = track.closest('.service-capabilities--vision') ? Array.from(track.children) : Array.from(track.children).filter((card, index) => index % 2 === 0);
	let page = 0;
	let distance = Infinity;
	pageCards.forEach((card, index) => {
		const cardDistance = Math.abs((card.offsetLeft - track.offsetLeft) - track.scrollLeft);
		if (cardDistance < distance) { distance = cardDistance; page = index; }
	});
  track.closest('[data-service-capabilities]').querySelectorAll('[data-capability-page]').forEach((dot, index) => dot.classList.toggle('is-active', index === page));
}, true);

document.addEventListener('keydown', function (event) {
  const card = event.target.closest('.service-section--cards .service-card');
  if (card && (event.key === 'Enter' || event.key === ' ')) { event.preventDefault(); card.click(); }
});
