

<?php $__env->startSection('title', 'Grocery List | Lazzat لذّت'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-top">
  <div class="section-header" style="padding:40px 5% 0;">
    <span class="section-tag">Shopping</span>
    <h1 class="section-title" data-t="grocery-title">Grocery List</h1>
    <p class="section-sub" data-t="grocery-sub">Auto-generated from your meal plan</p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:20px;flex-wrap:wrap;">
      <button class="btn-primary" onclick="window.print()">🖨️ <span data-t="grocery-print">Print</span></button>
      <button style="background:var(--cream-dark);border:none;border-radius:10px;padding:10px 20px;cursor:pointer;font-family:inherit;" onclick="clearGrocery()">🗑 <span data-t="grocery-clear">Clear List</span></button>
    </div>
  </div>

  <section class="section" style="padding-top:28px; max-width:700px; margin:0 auto;">
    <!-- Progress -->
    <div class="grocery-progress" id="grocery-progress" style="display:none;">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-weight:700;color:var(--charcoal);" id="progress-text">0 items bought</span>
        <span style="color:var(--warm-gray);font-size:0.88rem;" id="progress-pct">0%</span>
      </div>
      <div class="progress-bar-wrap"><div class="progress-bar-fill" id="progress-bar" style="width:0%"></div></div>
    </div>

    <div class="grocery-list" id="grocery-list"></div>

    <div class="empty-state" id="grocery-empty" style="display:none;">
      <div class="empty-state-icon">🛒</div>
      <div class="empty-state-msg" data-t="grocery-empty">Your grocery list is empty. Add meals to your planner first!</div>
      <a href="/planner" class="btn-primary" style="display:inline-flex;margin-top:20px;">Go to Planner →</a>
    </div>
  </section>
</div>

<footer class="footer">
  <div class="footer-bottom" data-t="footer-copy">© 2025 Lazzat · Made with ❤️ for Pakistani food lovers</div>
</footer>

<script src="<?php echo e(asset('js/translations.js')); ?>"></script>
<script src="<?php echo e(asset('js/navbar.js')); ?>"></script>
<script>
  injectNavbar('grocery');

  let groceryItems = [];
  let checkedItems = store.get('grocery-checked') || [];

  function renderGrocery() {
    const list = document.getElementById('grocery-list');
    const empty = document.getElementById('grocery-empty');
    const progress = document.getElementById('grocery-progress');

    if (!groceryItems.length) {
      list.innerHTML = '';
      empty.style.display = 'block';
      progress.style.display = 'none';
      return;
    }
    empty.style.display = 'none';
    progress.style.display = 'block';

    // Deduplicate by ingredient name
    const seen = new Map();
    groceryItems.forEach(item => {
      const key = item.name.toLowerCase();
      if (!seen.has(key)) {
        seen.set(key, {...item, sources: [item.source]});
      } else {
        seen.get(key).sources.push(item.source);
      }
    });
    const deduped = [...seen.values()];

    list.innerHTML = deduped.map((item, i) => {
      const isChecked = checkedItems.includes(item.name.toLowerCase());
      return `
      <div class="grocery-item ${isChecked ? 'checked' : ''}" id="item-${i}">
        <div class="grocery-checkbox ${isChecked ? 'checked' : ''}" onclick="toggleItem(${i},'${item.name.toLowerCase().replace(/'/g,"\\'")}')">
          ${isChecked ? '✓' : ''}
        </div>
        <div class="grocery-name">${item.measure ? `<span style="color:var(--saffron);font-weight:700;">${item.measure}</span> ` : ''}${item.name}</div>
        <div class="grocery-source">📍 ${[...new Set(item.sources)].slice(0,2).join(', ')}</div>
      </div>`;
    }).join('');

    updateProgress(deduped);
  }

  function updateProgress(deduped) {
    const total = deduped.length;
    const bought = deduped.filter(item => checkedItems.includes(item.name.toLowerCase())).length;
    const pct = total ? Math.round((bought/total)*100) : 0;
    document.getElementById('progress-text').textContent = `${bought} / ${total} ${t('grocery-progress')}`;
    document.getElementById('progress-pct').textContent = `${pct}%`;
    document.getElementById('progress-bar').style.width = pct + '%';
    if (pct === 100) showToast('All items bought! 🎉', 'success');
  }

  function toggleItem(i, name) {
    const idx = checkedItems.indexOf(name);
    if (idx === -1) checkedItems.push(name);
    else checkedItems.splice(idx, 1);
    store.set('grocery-checked', checkedItems);
    renderGrocery();
  }

  function clearGrocery() {
    store.set('grocery', null);
    store.set('grocery-checked', []);
    groceryItems = [];
    checkedItems = [];
    renderGrocery();
    showToast('Grocery list cleared', 'info');
  }

  // Init
  const raw = store.get('grocery');
  groceryItems = Array.isArray(raw) ? raw : [];
  renderGrocery();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Laptop\Documents\Codex\2026-05-29\files-mentioned-by-the-user-lazzatttt\lazzat-laravel\resources\views/grocery.blade.php ENDPATH**/ ?>