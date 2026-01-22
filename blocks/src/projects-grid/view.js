import { store, getContext } from '@wordpress/interactivity';

store('hiyield-projects-grid-wrapper', {
  state: {
    gridHtml: '',
    isLoading: false,
  },
  actions: {
    fetchGridData: async (event) => {
      const context = getContext();
      const selected = event.target.value;
      const taxonomy = event.target.dataset.taxonomy;
      const gridEl = document.querySelector('.hiyield-projects-grid');

      if(!gridEl) {
        console.warn('Grid element not found in the DOM.');
      }

      if( context.selectedCategory === '' ) {
        context.selectedCategory = selected;
        filterCardsByTerm(context.selectedCategory);
      } else {
        context.selectedCategory = selected;
        context.isLoading = true;

        try {
          const url = new URL(`${HYB_API.root}projects-grid`);
          url.searchParams.set('category', selected);
          url.searchParams.set('taxonomy', taxonomy);

          gridEl.innerHTML = '';

          const response = await fetch(url.toString(), {
            headers: { 
              "content-type": "application/json",
              "X-WP-Nonce": HYB_API.nonce
            }
          });
          const data = await response.json();
        
          if (data && typeof data.gridHtml === 'string' && data.gridHtml.trim() !== '') {
            gridEl.innerHTML = data.gridHtml;
          } else {
            gridEl.innerHTML = '<p>'+context.noItemsMessage+'</p>';
          } 
        } catch (err) {
          console.error(err);
          gridEl.innerHTML = '<p>'+context.errorMessage+'</p>';
        } finally {
          context.isLoading = false;
        }
      }
    },
  }
});

function filterCardsByTerm(selectedTerm) {
  const cards = document.querySelectorAll('.hiyield-project-card');

  if(cards) {
    cards.forEach((card) => {
      const terms = card.dataset.tax ? card.dataset.tax.split(',') : [];

      if (selectedTerm && !terms.includes(selectedTerm)) {
        card.remove();
      }
    });
  }
}