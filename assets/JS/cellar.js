// assets/js/search.js

document.addEventListener('DOMContentLoaded', () => {
  /**
   * Generic live‐search binder.
   *
   * @param {string} inputSelector     – CSS selector for the text input
   * @param {string} endpoint          – API endpoint (no ?search=… part)
   * @param {string} containerSelector – CSS selector for the cards container
   * @param {function} renderItem      – (item) => HTML string for one result
   */
  function liveSearch({ inputSelector, endpoint, containerSelector, renderItem }) {
    const input   = document.querySelector(inputSelector);
    const container = document.querySelector(containerSelector);
    if (!input || !container) return; // only bind if both exist on the page

    // cache the initial HTML so we can restore on empty query
    const originalHTML = container.innerHTML;
    let timer;

    // debounce helper
    function debounce(fn, delay = 300) {
      clearTimeout(timer);
      timer = setTimeout(fn, delay);
    }

    input.addEventListener('input', e => {
      const query = e.target.value.trim();

      debounce(async () => {
        if (query === '') {
          // restore original cards when the search box is cleared
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

  // Instantiate for wines
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

  // Instantiate for cellars
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
});
