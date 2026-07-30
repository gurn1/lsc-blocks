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
      const voteValue = event.target.closest('button').dataset.vote;

      if (context.hasVoted) return;

      context.hasVoted = true;
      window.localStorage.setItem(STORAGE_PREFIX + context.postId, voteValue);

      try {
        const response = await fetch(`${LSC_API.root}feedback/${context.postId}`, {
          method: 'POST',
          headers: {
            'content-type': 'application/json',
            'X-WP-Nonce': LSC_API.nonce,
          },
          body: JSON.stringify({ vote: voteValue }),
        });

        if (response.ok) {
          context.counts = await response.json();
        }
      } catch (err) {
        console.error(err);
        // The vote is already recorded locally via localStorage, so the
        // buttons stay hidden either way - a failed request just means the
        // displayed count won't reflect this vote until the page reloads.
      }
    },
  },
});