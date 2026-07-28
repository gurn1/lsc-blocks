import { store, getContext, getElement } from '@wordpress/interactivity';

const { state, actions } = store('lsc-projects-grid-wrapper', {
  actions: {
    async fetchGridData(event) {
      const context = getContext();
      const { ref } = getElement();

      const selected = event.target.value;
      const taxonomy = event.target.dataset.taxonomy;
      const gridEl = ref.closest('.wp-block-lsc-blocks-projects-grid')
        ?.querySelector('.lsc-projects-grid');

      if (!gridEl) {
        console.warn('Grid element not found in the DOM.');
        return;
      }

      // Once we've removed cards client-side below, they're gone for good -
      // every filter change after the first one has to go back to the server.
      const hasFilteredClientSideAlready = context.selectedCategory !== '';

      context.selectedCategory = selected;

      if (!hasFilteredClientSideAlready) {
        filterCardsByTerm(gridEl, selected);
        return;
      }

      context.isLoading = true;
      gridEl.innerHTML = '';

      // Cancel any in-flight request so a slow earlier response can't
      // overwrite a newer selection's result.
      actions.abortController?.abort();
      actions.abortController = new AbortController();

      try {
        const url = new URL(`${LSC_API.root}projects-grid`);
        url.searchParams.set('category', selected);
        url.searchParams.set('taxonomy', taxonomy);

        const response = await fetch(url.toString(), {
          headers: {
            'content-type': 'application/json',
            'X-WP-Nonce': LSC_API.nonce,
          },
          signal: actions.abortController.signal,
        });

        if (!response.ok) {
          throw new Error(`Request failed with status ${response.status}`);
        }

        const data = await response.json();

        gridEl.innerHTML = data?.html?.trim()
          ? data.html
          : `<p>${context.noItemsMessage}</p>`;
      } catch (err) {
        if (err.name === 'AbortError') return; // superseded by a newer request
        console.error(err);
        gridEl.innerHTML = `<p>${context.errorMessage}</p>`;
      } finally {
        context.isLoading = false;
      }
    },
  },
});

function filterCardsByTerm(gridEl, selectedTerm) {
  const cards = gridEl.querySelectorAll('.lsc-project-card');

  cards.forEach((card) => {
    const terms = card.dataset.tax ? card.dataset.tax.split(',') : [];

    if (selectedTerm && !terms.includes(selectedTerm)) {
      card.remove();
    }
  });
}