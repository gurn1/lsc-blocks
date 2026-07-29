import { store, getContext } from '@wordpress/interactivity';

store('lsc-faq-accordion', {
  state: {
    get isItemOpen() {
      const context = getContext();
      return context.openItems.includes(context.itemId);
    },
    get isItemVisible() {
      const context = getContext();
      return context.selectedCategory === '' || context.category === context.selectedCategory;
    },
  },
  actions: {
    toggleItem() {
      const context = getContext();
      const isOpen = context.openItems.includes(context.itemId);

      if (context.allowMultiple) {
        context.openItems = isOpen
          ? context.openItems.filter((id) => id !== context.itemId)
          : [...context.openItems, context.itemId];
      } else {
        context.openItems = isOpen ? [] : [context.itemId];
      }
    },
    setCategory(event) {
      const context = getContext();
      context.selectedCategory = event.target.value;
    },
  },
});