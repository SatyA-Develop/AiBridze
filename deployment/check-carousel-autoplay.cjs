const assert = require('node:assert/strict');
const vm = require('node:vm');
const fs = require('node:fs');
const events = {};
const listeners = {};
let tick;
let count = 0;
const activeElement = { matches: () => false };
const document = { readyState: 'complete', hidden: false, activeElement,
  querySelector: () => null, querySelectorAll: () => [], addEventListener() {} };
const window = { addEventListener: (name, fn) => { events[name] = fn; } };
vm.runInNewContext(fs.readFileSync('assets/js/carousel-autoplay.js', 'utf8'), {
  window, document, matchMedia: () => ({ matches: false, addEventListener() {} }),
  setTimeout: fn => { tick = fn; }, clearTimeout() {},
  IntersectionObserver: class { constructor(fn) { this.fn = fn; } observe() { this.fn([{ isIntersecting: true }]); } }
});
window.aibridzeAutoplay({ querySelectorAll: () => [], contains: () => true,
  addEventListener: (name, fn) => { listeners[name] = fn; } }, () => count++);
tick(); assert.equal(count, 1, 'Pointer focus must not permanently stop autoplay');
listeners.pointerenter({ pointerType: 'mouse' }); tick(); assert.equal(count, 1);
listeners.pointerleave(); tick(); assert.equal(count, 2);
listeners.pointerdown(); tick(); assert.equal(count, 2);
events.pointerup(); tick(); assert.equal(count, 3);
listeners.pointerdown(); events.pointercancel(); tick(); assert.equal(count, 4);
activeElement.matches = () => true; tick(); assert.equal(count, 4, 'Keyboard focus pauses autoplay');
console.log('PASS: autoplay resumes after hover, touch release and cancellation; keyboard focus pauses.');
