import { store, getContext } from '@wordpress/interactivity';

const STORAGE_PREFIX = 'lsc-feedback-vote-';

store('lsc-feedback-widget', {
  callbacks: {
    checkExistingVote: () => {
      const context = getContext();
      const stored = window.localStorage.getItem(STORAGE_PREFIX + context.postId);

      if (stored) {
        context.hasVoted = true;
      }
    },
  },
  actions: {
    async vote(event) {
      const context = getContext();
      const voteValue = event.target.dataset.vote;

      if (context.hasVoted) return;

      context.hasVoted = true;
      window.localStorage.setItem(STORAGE_PREFIX + context.postId, voteValue);

      try {
        await fetch(`${LSC_API.root}feedback/${context.postId}`, {
          method: 'POST',
          headers: {
            'content-type': 'application/json',
            'X-WP-Nonce': LSC_API.nonce,
          },
          body: JSON.stringify({ vote: voteValue }),
        });
      } catch (err) {
        console.error(err);
        // Vote is already recorded locally - a failed network call just
        // means the server-side tally missed one. Not worth reverting the
        // UI over, since re-showing the buttons would let them vote twice.
      }
    },
  },
});