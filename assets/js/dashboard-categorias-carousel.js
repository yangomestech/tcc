document.addEventListener('DOMContentLoaded', () => {
  const carousels = document.querySelectorAll('[data-event-carousel]');

  carousels.forEach((carousel) => {
    const track = carousel.querySelector('.cards');
    const cards = Array.from(carousel.querySelectorAll('.card'));
    const prevBtn = carousel.querySelector('[data-carousel-prev]');
    const nextBtn = carousel.querySelector('[data-carousel-next]');

    if (!track || !prevBtn || !nextBtn || cards.length === 0) return;

    let currentIndex = 0;

    // Remove qualquer flex inline deixado pelo JS antigo
    cards.forEach((card) => {
      card.style.removeProperty('flex');
      card.style.removeProperty('width');
    });

    function getCardsVisible() {
      const value = getComputedStyle(track).getPropertyValue('--cards-visible');
      const visible = parseInt(value, 10);

      return Number.isNaN(visible) ? 1 : visible;
    }

    function getMaxIndex() {
      const visible = getCardsVisible();
      return Math.max(0, cards.length - visible);
    }

    function updateButtons() {
      const visible = getCardsVisible();
      const maxIndex = getMaxIndex();

      const shouldHideButtons = cards.length <= visible;

      prevBtn.hidden = shouldHideButtons;
      nextBtn.hidden = shouldHideButtons;

      prevBtn.disabled = currentIndex <= 0;
      nextBtn.disabled = currentIndex >= maxIndex;
    }

    function goToCard(index) {
      const maxIndex = getMaxIndex();

      currentIndex = Math.max(0, Math.min(index, maxIndex));

      const targetCard = cards[currentIndex];

      if (!targetCard) return;

      track.scrollTo({
        left: targetCard.offsetLeft,
        behavior: 'smooth'
      });

      updateButtons();
    }

    prevBtn.addEventListener('click', () => {
      goToCard(currentIndex - getCardsVisible());
    });

    nextBtn.addEventListener('click', () => {
      goToCard(currentIndex + getCardsVisible());
    });

    window.addEventListener('resize', () => {
      goToCard(currentIndex);
      updateButtons();
    });

    updateButtons();
  });
});