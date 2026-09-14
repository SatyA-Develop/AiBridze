const vm = require('node:vm'), fs = require('node:fs'), assert = require('node:assert/strict');
const handlers = {}, errors = [];
function field(name, value='') {
 const attrs = {};
 return {name,value,willValidate:true,custom:'', validity:{valueMissing:false},
 matches(s){return s==='form'?[]:s==='input[type="email"]'?name==='email':true;},
 setCustomValidity(v){this.custom=v;},get validationMessage(){return this.custom || (this.validity.valueMissing?'Required':'');},
 setAttribute(k,v){attrs[k]=v;},getAttribute(k){return attrs[k];},hasAttribute(k){return k in attrs;},removeAttribute(k){delete attrs[k];},
 insertAdjacentElement(_,e){errors.push(e);this.error=e;},focus(){this.focused=true;}};
}
function form() {const f={elements:[field('full_name'),field('email','bad'),field('message')],handlers:{},querySelector(){return this.elements[1];},querySelectorAll(s){return s==='form'?[]:s==='input[type="email"]'?[this.elements[1]]:s==='.form-field-error'?this.elements.map(e=>e.error).filter(Boolean):this.elements;},addEventListener(k,fn){this.handlers[k]=fn;}};f.elements[0].validity.valueMissing=true;return f;}
const forms=[form(),form(),form()];let observer;
const document={body:{},querySelectorAll:s=>s==='form'?forms:forms.flatMap(f=>f.querySelectorAll(s)),addEventListener(k,fn){handlers[k]=fn;},createElement(){return {setAttribute(){}};}};
vm.runInNewContext(fs.readFileSync('assets/js/form-validation.js','utf8'),{document,MutationObserver:class{constructor(fn){observer=fn;}observe(){}}});
assert(forms.every(f=>f.noValidate));
for(const f of forms){let blocked=false;handlers.submit({target:f,preventDefault(){blocked=true;},stopImmediatePropagation(){}});assert(blocked);assert(f.elements[0].focused);assert.notEqual(f.elements[0].error.id,f.elements[1].error.id);assert(f.elements[1].error.textContent.includes('valid email'));
 f.elements[0].validity.valueMissing=false;f.elements[1].value='name@example.com';f.handlers.input({target:f.elements[1]});assert(f.elements[1].error.hidden);blocked=false;handlers.submit({target:f,preventDefault(){blocked=true;},stopImmediatePropagation(){}});assert(!blocked);f.handlers.reset();assert(f.elements.every(e=>e.error.hidden));}
const dynamic=form();dynamic.matches=s=>s==='form';observer([{addedNodes:[dynamic]}]);assert(dynamic.noValidate);
console.log('Inline validation: all forms, distinct field errors, correction, valid submit, reset and dynamic form checks passed.');

