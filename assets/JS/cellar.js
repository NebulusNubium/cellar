//filtre vin

document.addEventListener('DOMContentLoaded', () => {
  const input   = document.getElementById('wine-search');
  const cards   = document.querySelector('#liste .cards');
  let   timer;

  function debounce(fn, delay = 300) {
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  }

  async function lookup(query) {
    if (!query) {
      // Si la barre est vide, on remet la page originale
      cards.innerHTML = originalCardsHTML;
      return;
    }
    try {
      const resp = await fetch(`/api/wines?search=${encodeURIComponent(query)}`);
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const wines = await resp.json();

      // recrée mes cartes
      cards.innerHTML = wines.map(w => `
        <div class="card">
          <h2>${w.name}</h2>
          <p>${w.year}</p>
          <p>${w.grapes}</p>
          <p>${w.region}</p>
          <p>${w.country}</p>

          <p id="bouteille-description-${w.id}">
            ${w.description.slice(0,100)}… 
            <button onclick="toggleDescription(${w.id})">Expand</button>
          </p>
          <p id="bouteille-description-full-${w.id}" style="display:none;">
            ${w.description}
            <button onclick="toggleDescription(${w.id})">Reduce</button>
          </p>

          <div class="actions">
            <!-- e.g. your add-to-cellar button here -->
          </div>
        </div>
      `).join('');

    } catch (err) {
      console.error('Lookup error:', err);
      cards.innerHTML = `<p class="error">Could not load results.</p>`;
    }
  }

  // on stock la page originale pour quand la barre de recherche est vide
  const originalCardsHTML = cards.innerHTML;

  input.addEventListener('input', debounce(e => {
    lookup(e.target.value.trim());
  }));
});

