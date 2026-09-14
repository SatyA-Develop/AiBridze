const vm = require('node:vm');
const fs = require('node:fs');
const assert = require('node:assert/strict');
const source = fs.readFileSync('assets/js/portfolio-stack.js', 'utf8');
function setup(width = 1440) {
  let now = 0, id = 0;
  const frames = new Map(), listeners = {};
  const element = () => ({style: {setProperty() {}, removeProperty() {}}, classList: {toggle(name, value) {this[name] = value;}}, setAttribute() {}});
  const cards = Array.from({length: 3}, element);
  const viewport = Object.assign(element(), {offsetHeight: 600});
  const heading = Object.assign(element(), {offsetHeight: 144});
  const win = {scrollY: 1166, addEventListener(name, fn) {listeners[name] = fn;}, scrollTo({top}) {this.scrollY = top;}};
  const region = Object.assign(element(), {getBoundingClientRect: () => ({top: 1000 - win.scrollY})});
  const section = Object.assign(element(), {querySelector: s => s.includes('scroll-region') ? region : s.includes('viewport') ? viewport : heading, querySelectorAll: () => cards});
  vm.runInNewContext(source, {window: win, innerWidth: width, innerHeight: 900, matchMedia: () => ({matches: false, addEventListener() {}}), document: {querySelector: s => s.includes('portfolio-stack') ? section : s.includes('site-header') ? {offsetHeight: 88} : null}, performance: {now: () => now}, requestAnimationFrame: fn => {frames.set(++id, fn); return id;}, cancelAnimationFrame: n => frames.delete(n)});
  function tick(t) {now = t; const pending = [...frames.values()]; frames.clear(); pending.forEach(fn => fn(now));}
  function wheel(t, delta = 100) {now = t; let prevented = false; listeners.wheel({deltaY: delta, deltaX: 0, deltaMode: 0, preventDefault() {prevented = true;}}); return prevented;}
  return {win, cards, tick, wheel};
}
const s = setup();
assert(s.wheel(0));
s.tick(400); assert(s.cards[0].classList['is-active']);
s.tick(425); assert(s.cards[1].classList['is-active'], 'Details switch halfway through the image');
s.tick(850); assert.equal(s.win.scrollY, 2066);
assert(s.wheel(900), 'Next gesture immediately after completion is accepted');
s.tick(1750); assert.equal(s.win.scrollY, 2966);
assert.equal(s.wheel(1950), false, 'Page can scroll beyond final project');
const tail = setup(); tail.wheel(0);
for (let t=50;t<=1100;t+=50) {tail.tick(t); tail.wheel(t);}
assert.equal(tail.win.scrollY, 2066, 'Momentum does not skip a project');
tail.wheel(1300); tail.tick(2150); assert.equal(tail.win.scrollY, 2966);
const intro = setup(); intro.win.scrollY = 950; intro.wheel(0); intro.tick(850);
assert.equal(intro.win.scrollY,1166); assert(intro.cards[0].classList['is-active'], 'Heading gets a separate scroll step');
assert.equal(setup(800).wheel(0), false, 'Mobile and tablet retain native scrolling');
console.log('Portfolio scroll behavior checks passed.');
