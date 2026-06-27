/**
 * CatPicker — Visual multi-select category picker
 * Replaces the Choices.js multi-select with beautiful clickable cards/chips.
 *
 * Usage:
 *   const picker = new CatPicker({
 *     containerId : 'my-cat-picker',   // wrapping div id
 *     hiddenId    : 'f-cat',           // hidden <input> that stores comma-separated values
 *     categories  : [...],             // array of {id, name, icon, color}
 *     selected    : ['all'],           // pre-selected values
 *     multi       : true,              // allow multiple
 *   });
 *   picker.getValues();  // → ['all', '3']
 *   picker.reset();
 *   picker.setValues(['2', '5']);
 */
class CatPicker {
  constructor({ containerId, hiddenId, categories = [], selected = [], multi = true }) {
    this.containerId = containerId;
    this.hiddenId    = hiddenId;
    this.categories  = categories;
    this.selected    = new Set(selected.map(String));
    this.multi       = multi;

    this._render();
  }

  /* ─── Public API ─── */
  getValues() { return [...this.selected]; }

  reset() {
    this.selected.clear();
    this._syncHidden();
    this._update();
  }

  setValues(vals = []) {
    this.selected = new Set(vals.map(String));
    this._syncHidden();
    this._update();
  }

  setChoiceByValue(val) {
    if (!val) return;
    if (!this.multi) this.selected.clear();
    this.selected.add(String(val));
    this._syncHidden();
    this._update();
  }

  removeActiveItems() { this.reset(); }

  destroy() {
    const c = document.getElementById(this.containerId);
    if (c) c.innerHTML = '';
  }

  /* ─── Internals ─── */
  _render() {
    const wrap = document.getElementById(this.containerId);
    if (!wrap) return;

    wrap.innerHTML = '';
    wrap.className = 'cat-picker-wrap';

    // "All" pill
    const allPill = document.createElement('div');
    allPill.className = 'cat-pill cat-pill-all' + (this.selected.has('all') ? ' active' : '');
    allPill.dataset.val = 'all';
    allPill.innerHTML = `<span class="cat-pill-ico">🌐</span><span class="cat-pill-lbl">لكل الجمعيات</span>`;
    allPill.addEventListener('click', () => this._toggle('all', allPill));
    wrap.appendChild(allPill);

    // Category pills
    this.categories.forEach(cat => {
      const pill = document.createElement('div');
      const isActive = this.selected.has(String(cat.id));
      pill.className = 'cat-pill' + (isActive ? ' active' : '');
      pill.dataset.val = String(cat.id);
      pill.style.setProperty('--cc', cat.color || '#2ab8d0');
      pill.innerHTML = `<span class="cat-pill-ico">${cat.icon || '📁'}</span><span class="cat-pill-lbl">${cat.name}</span>`;
      pill.addEventListener('click', () => this._toggle(String(cat.id), pill));
      wrap.appendChild(pill);
    });

    this._syncHidden();
  }

  _toggle(val, el) {
    if (val === 'all') {
      // Clicking "all" clears everything and selects only all
      if (this.selected.has('all') && this.selected.size === 1) {
        // already only "all" selected — deselect
        this.selected.delete('all');
      } else {
        this.selected.clear();
        this.selected.add('all');
      }
    } else {
      if (!this.multi) {
        this.selected.clear();
        this.selected.add(val);
      } else {
        // Selecting a specific cat removes "all"
        this.selected.delete('all');
        if (this.selected.has(val)) {
          this.selected.delete(val);
        } else {
          this.selected.add(val);
        }
      }
    }
    this._syncHidden();
    this._update();
  }

  _update() {
    const wrap = document.getElementById(this.containerId);
    if (!wrap) return;
    wrap.querySelectorAll('.cat-pill').forEach(pill => {
      pill.classList.toggle('active', this.selected.has(pill.dataset.val));
    });
  }

  _syncHidden() {
    const h = document.getElementById(this.hiddenId);
    if (h) h.value = [...this.selected].join(',');
  }
}
