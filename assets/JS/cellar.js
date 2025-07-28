document.addEventListener('DOMContentLoaded', () => {
  //barre de recherche vin & cave
  function liveSearch({ inputSelector, endpoint, containerSelector, renderItem }) {
    const input   = document.querySelector(inputSelector);
    const container = document.querySelector(containerSelector);
    if (!input || !container) return; // ne marche que s'il y a un input ou un container

    // stockage de la page originale
    const originalHTML = container.innerHTML;
    let timer;

    // fonction d'attente avant d'activer l'autre fonction
    function debounce(fn, delay = 300) {
      clearTimeout(timer);
      timer = setTimeout(fn, delay);
    }

    input.addEventListener('input', e => {
      const query = e.target.value.trim();

      debounce(async () => {
        if (query === '') {
          // remet la page originale si la recherche ne donne rien
          container.innerHTML = originalHTML;
          return;
        }

        try {
          const resp = await fetch(`${endpoint}?search=${encodeURIComponent(query)}`);
          if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
          const items = await resp.json();

          // re-render the grid
          container.innerHTML = items.map(renderItem).join('');
        } catch (err) {
          console.error('Live-search error:', err);
          container.innerHTML = `<p class="error">Could not load results.</p>`;
        }
      });
    });
  }

  // recherche vin
  liveSearch({
    inputSelector:     '#wine-search',
    endpoint:          '/api/wines',
    containerSelector: '#liste .cards',
    renderItem: w => `
      <div class="card">
        <h2>${w.name}</h2>
        <p>${w.year}</p>
        <p>${w.grapes}</p>
        <p>${w.region}</p>
        <p>${w.country}</p>
        <p id="bouteille-description-${w.id}">
          ${w.description.substring(0,100)}…
          <button onclick="toggleDescription(${w.id})">Expand</button>
        </p>
        <p id="bouteille-description-full-${w.id}" style="display:none;">
          ${w.description}
          <button onclick="toggleDescription(${w.id})">Reduce</button>
        </p>
      </div>
    `
  });

  // recherche cave
  liveSearch({
    inputSelector:     '#cellar-search',
    endpoint:          '/api/cellars',
    containerSelector: '#liste .cards',
    renderItem: c => `
      <div class="card">
        <h2>${c.owner}’s Cellar</h2>
        <p>${c.count} bottles</p>
        <a href="/cellar/${c.id}" class="btn">Visit this Cellar</a>
      </div>
    `
  });

//stock:
document.querySelectorAll('.stock-controls').forEach(ctrl=>{
  const card      = ctrl.closest('.card');
  const wineId    = card.dataset.id;
  const countSpan = ctrl.querySelector('.stock-count');

  ctrl.querySelectorAll('button').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      const delta = parseInt(btn.dataset.delta,10);
      const resp  = await fetch(`/stock/${wineId}`, {
        method:'POST',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify({ delta })
      });
      const data = await resp.json();
      if(data.success){
        countSpan.textContent = data.newStock;
      }
    });
  });
});

});

  

