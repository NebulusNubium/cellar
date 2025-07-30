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
  <div class="card" data-id="${w.id}">
    <h2 class="view-name">${w.name}</h2>
    
    <div class="image-content">
      <div class="wineImage">
        <img src="/uploads/images/${w.imageName}" alt="imageVin">
      </div>
      <div class="content">
        <p class="view-year">${w.year}</p>
        <p class="view-grapes">${w.grapes}</p>
        <p class="view-region">${w.regionName}</p>
        <p class="view-country">${w.countryName}</p>
      </div>
    </div>

    <p class="view-description" id="bouteille-description-full-${w.id}">
      ${w.description}
    </p>

    <div class="actions">
      <form action="/wine/${w.id}/add" method="POST" style="display:inline">
        <input type="hidden" name="_token" value="${w.csrfAdd}">
        <button type="submit" class="btn">Add to cellar</button>
      </form>

      ${w.isAdmin ? `
        <a href="/wine/${w.id}/edit" class="btn modifier">Edit</a>
        <form action="/wine/${w.id}/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this wine?');" style="display:inline">
          <input type="hidden" name="_token" value="${w.csrfDelete}">
          <button type="submit" class="btn supprimer">Delete</button>
        </form>
      ` : ''}
    </div>
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

  

