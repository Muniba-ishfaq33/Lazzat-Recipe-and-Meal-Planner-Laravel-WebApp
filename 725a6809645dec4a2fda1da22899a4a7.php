

<?php $__env->startSection('title', 'Recipe | Lazzat لذّت'); ?>

<?php $__env->startSection('content'); ?>
<!-- Navbar injected -->

<div id="detail-root">
  <!-- Loading state -->
  <div class="recipe-detail-hero" style="background:var(--cream-dark); display:flex; align-items:center; justify-content:center; margin-top:70px;">
    <div style="text-align:center; color:var(--warm-gray);">
      <div style="font-size:2rem; margin-bottom:10px;">🍳</div>
      <div>Loading recipe...</div>
    </div>
  </div>
</div>

<!-- Add to Planner Modal -->
<div class="modal-overlay" id="plannerModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Add to Meal Planner</h3>
      <button class="modal-close" onclick="closePlannerModal()">✕</button>
    </div>
    <div id="modal-meal-name" style="font-weight:600; margin-bottom:16px; color:var(--charcoal);"></div>
    <div class="form-group">
      <label class="form-label">Day</label>
      <select class="form-input" id="modal-day">
        <option value="0">Monday</option><option value="1">Tuesday</option>
        <option value="2">Wednesday</option><option value="3">Thursday</option>
        <option value="4">Friday</option><option value="5">Saturday</option><option value="6">Sunday</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Meal Type</label>
      <select class="form-input" id="modal-mealtype">
        <option value="0">Breakfast</option><option value="1">Lunch</option><option value="2">Dinner</option>
      </select>
    </div>
    <button class="btn-auth" onclick="confirmAddToPlanner()" style="margin-top:12px;">✅ Add to Planner</button>
  </div>
</div>

<footer class="footer" style="margin-top:0;">
  <div class="footer-bottom" data-t="footer-copy">© 2025 Lazzat · Made with ❤️ for Pakistani food lovers</div>
</footer>

<script src="<?php echo e(asset('js/translations.js')); ?>"></script>
<script src="<?php echo e(asset('js/navbar.js')); ?>"></script>
<script>
  injectNavbar('recipes');

  const params = new URLSearchParams(window.location.search);
  const mealId = params.get('id');
  let currentMeal = null;

  async function loadDetail() {
    if (!mealId) { window.location.href = '/recipes'; return; }
    try {
      currentMeal = await fetchMealDetail(mealId);
      if (!currentMeal) throw new Error('Not found');
      renderDetail(currentMeal);
    } catch(e) {
      document.getElementById('detail-root').innerHTML = `
        <div class="page-top" style="padding:80px 5%; text-align:center; color:var(--warm-gray);">
          <div style="font-size:3rem; margin-bottom:16px;">😕</div>
          <h2>Recipe not found</h2>
          <a href="/recipes" style="color:var(--saffron); font-weight:600; text-decoration:none; margin-top:16px; display:inline-block;">← Back to Recipes</a>
        </div>`;
    }
  }

  function getIngredients(meal) {
    const ingredients = [];
    for (let i = 1; i <= 20; i++) {
      const name = meal[`strIngredient${i}`];
      const measure = meal[`strMeasure${i}`];
      if (name && name.trim()) {
        ingredients.push({ name: name.trim(), measure: measure ? measure.trim() : '' });
      }
    }
    return ingredients;
  }

  function getSteps(meal) {
    if (!meal.strInstructions) return [];
    return meal.strInstructions
      .split(/\r\n|\n|\r/)
      .map(s => s.trim())
      .filter(s => s.length > 20)
      .slice(0, 12);
  }

  function renderDetail(meal) {
    const ingredients = getIngredients(meal);
    const steps = getSteps(meal);
    const cal = estimateCal(meal);
    const ytId = meal.strYoutube ? meal.strYoutube.split('v=')[1] : null;
    const area = meal.strArea || 'Pakistani';
    const flag = area === 'Indian' ? '🇮🇳' : '🇵🇰';

    document.title = `${meal.strMeal} | Lazzat لذّت`;

    const html = `
    <!-- Back link -->
    <div style="padding:80px 5% 0; display:flex; align-items:center;">
      <a href="/recipes" style="color:var(--saffron); font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px; font-size:0.95rem;" data-t="detail-back">← Back to Recipes</a>
    </div>

    <!-- Hero image -->
    <div class="recipe-detail-hero" style="margin-top:16px;">
      <img class="recipe-detail-img" src="${meal.strMealThumb}" alt="${meal.strMeal}" onerror="this.style.background='var(--cream-dark)'">
      <div class="recipe-detail-overlay"></div>
      <div class="recipe-detail-info">
        <h1 class="recipe-detail-title">${meal.strMeal}</h1>
        <div class="recipe-detail-meta">
          <span class="recipe-meta-chip">${flag} ${area}</span>
          <span class="recipe-meta-chip">🍽️ ${meal.strCategory || 'Main'}</span>
          <span class="recipe-meta-chip">🔥 ~${cal} kcal</span>
          <span class="recipe-meta-chip">⏱ 30–45 mins</span>
          <span class="recipe-meta-chip">👥 4 servings</span>
        </div>
        <div class="recipe-detail-actions">
          <button class="btn-save-recipe ${isRecipeSaved(meal.idMeal) ? 'saved' : ''}" id="saveRecipeBtn" onclick="toggleSaveRecipe()">
            <span class="save-icon">${isRecipeSaved(meal.idMeal) ? '✓' : '＋'}</span>
            <span>${isRecipeSaved(meal.idMeal) ? 'Saved Recipe' : 'Save Recipe'}</span>
          </button>
          <button class="btn-save-recipe favorite ${isFavoriteRecipe(meal.idMeal) ? 'saved' : ''}" id="favoriteRecipeBtn" onclick="toggleFavoriteRecipe()">
            <span class="save-icon">${isFavoriteRecipe(meal.idMeal) ? '♥' : '♡'}</span>
            <span>${isFavoriteRecipe(meal.idMeal) ? 'Favorited' : 'Add Favorite'}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div class="recipe-detail-body">
      <div class="recipe-detail-grid">
        <!-- Ingredients -->
        <div>
          <div class="ingredients-card">
            <h3 data-t="detail-ingredients">Ingredients</h3>
            ${ingredients.map(ing => `
              <div class="ingredient-item">
                <span class="ingredient-dot"></span>
                <span><strong>${ing.measure}</strong> ${ing.name}</span>
              </div>`).join('')}
          </div>
          <button class="btn-add-planner" onclick="openPlannerModal()" style="width:100%; justify-content:center; margin-top:16px;">
            <span data-t="detail-add-planner">+ Add to Meal Planner</span>
          </button>
          ${ytId ? `
          <a href="${meal.strYoutube}" target="_blank" rel="noopener" style="display:flex;align-items:center;justify-content:center;gap:8px;background:#FF0000;color:#fff;border-radius:12px;padding:12px 20px;text-decoration:none;font-weight:700;margin-top:12px;">
            ▶ Watch on YouTube
          </a>` : ''}
        </div>

        <!-- Steps -->
        <div>
          <div class="steps-section">
            <h3 data-t="detail-steps">Step-by-Step Instructions</h3>
            ${steps.length ? steps.map((step, i) => `
              <div class="step-item fade-in" style="animation-delay:${i*0.05}s">
                <div class="step-num">${i + 1}</div>
                <div class="step-text">${step}</div>
              </div>`).join('') : `<p style="color:var(--warm-gray); line-height:1.8;">${meal.strInstructions || 'Instructions not available.'}</p>`}
          </div>

          ${ytId ? `
          <div class="youtube-wrap" style="margin-top:36px;">
            <h3 style="font-family:'Playfair Display',serif; margin-bottom:16px; color:var(--charcoal);">📺 Video Guide</h3>
            <iframe src="https://www.youtube.com/embed/${ytId}" allowfullscreen loading="lazy" style="width:100%;aspect-ratio:16/9;border-radius:var(--radius);border:none;"></iframe>
          </div>` : ''}
        </div>
      </div>
    </div>`;

    document.getElementById('detail-root').innerHTML = html;
    applyTranslations();

    // Set modal meal name
    document.getElementById('modal-meal-name').textContent = meal.strMeal;
  }

  function openPlannerModal() {
    document.getElementById('plannerModal').classList.add('open');
  }

  function getSavedRecipes() {
    return store.get('savedRecipes') || [];
  }

  function getFavoriteRecipes() {
    return store.get('favorites') || [];
  }

  function isRecipeSaved(id) {
    return getSavedRecipes().some(recipe => String(recipe.id || recipe.idMeal) === String(id));
  }

  function isFavoriteRecipe(id) {
    return getFavoriteRecipes().some(recipe => String(recipe.id || recipe.idMeal) === String(id));
  }

  function recipeSnapshot(meal) {
    return {
      id: meal.idMeal,
      idMeal: meal.idMeal,
      name: meal.strMeal,
      strMeal: meal.strMeal,
      thumb: meal.strMealThumb,
      strMealThumb: meal.strMealThumb,
      category: meal.strCategory || '',
      area: meal.strArea || '',
      updatedAt: new Date().toISOString()
    };
  }

  function toggleSaveRecipe() {
    if (!currentMeal) return;
    const saved = getSavedRecipes();
    const id = String(currentMeal.idMeal);
    const index = saved.findIndex(recipe => String(recipe.id || recipe.idMeal) === id);

    if (index === -1) {
      saved.unshift({ ...recipeSnapshot(currentMeal), savedAt: new Date().toISOString() });
      showToast(`"${currentMeal.strMeal}" saved`, 'success');
    } else {
      saved.splice(index, 1);
      showToast(`"${currentMeal.strMeal}" removed from saved recipes`, 'info');
    }

    store.set('savedRecipes', saved);
    updateSaveButton();
  }

  function toggleFavoriteRecipe() {
    if (!currentMeal) return;
    const favorites = getFavoriteRecipes();
    const id = String(currentMeal.idMeal);
    const index = favorites.findIndex(recipe => String(recipe.id || recipe.idMeal) === id);

    if (index === -1) {
      favorites.unshift({ ...recipeSnapshot(currentMeal), favoritedAt: new Date().toISOString() });
      showToast(`"${currentMeal.strMeal}" added to favorites`, 'success');
    } else {
      favorites.splice(index, 1);
      showToast(`"${currentMeal.strMeal}" removed from favorites`, 'info');
    }

    store.set('favorites', favorites);
    updateSaveButton();
  }

  function updateSaveButton() {
    const btn = document.getElementById('saveRecipeBtn');
    if (!btn || !currentMeal) return;
    const saved = isRecipeSaved(currentMeal.idMeal);
    btn.classList.toggle('saved', saved);
    btn.innerHTML = `<span class="save-icon">${saved ? '✓' : '＋'}</span><span>${saved ? 'Saved Recipe' : 'Save Recipe'}</span>`;

    const favoriteBtn = document.getElementById('favoriteRecipeBtn');
    if (!favoriteBtn) return;
    const favorite = isFavoriteRecipe(currentMeal.idMeal);
    favoriteBtn.classList.toggle('saved', favorite);
    favoriteBtn.innerHTML = `<span class="save-icon">${favorite ? '♥' : '♡'}</span><span>${favorite ? 'Favorited' : 'Add Favorite'}</span>`;
  }

  function closePlannerModal() {
    document.getElementById('plannerModal').classList.remove('open');
  }
  document.getElementById('plannerModal').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) closePlannerModal();
  });

  function confirmAddToPlanner() {
    const dayIdx = parseInt(document.getElementById('modal-day').value);
    const mealIdx = parseInt(document.getElementById('modal-mealtype').value);
    const plan = store.get('planner') || {};
    if (!plan[dayIdx]) plan[dayIdx] = {};
    plan[dayIdx][mealIdx] = { id: currentMeal.idMeal, name: currentMeal.strMeal, thumb: currentMeal.strMealThumb };
    store.set('planner', plan);
    closePlannerModal();
    showToast(`"${currentMeal.strMeal}" added to planner! 🎉`, 'success');
  }

  loadDetail();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Laptop\Documents\Codex\2026-05-29\files-mentioned-by-the-user-lazzatttt\lazzat-laravel\resources\views/recipe-detail.blade.php ENDPATH**/ ?>