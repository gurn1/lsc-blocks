document.querySelectorAll('.lsc-reviews-mode-slider').forEach((slider) => {
  const track = slider.querySelector('.lsc-reviews');
  const prev = slider.querySelector('.lsc-reviews-prev');
  const next = slider.querySelector('.lsc-reviews-next');

  if (!track || !prev || !next) return;

  const scrollByOneCard = (direction) => {
    const card = track.querySelector('.lsc-review-card');
    const distance = card ? card.getBoundingClientRect().width + 16 : 300;
    track.scrollBy({ left: distance * direction, behavior: 'smooth' });
  };

  prev.addEventListener('click', () => scrollByOneCard(-1));
  next.addEventListener('click', () => scrollByOneCard(1));
});