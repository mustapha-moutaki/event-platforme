const carousel = document.getElementById('carousel');
let currentIndex = 0;
const slideCount = carousel.children.length;

function prevSlide() {
  currentIndex = (currentIndex - 1 + slideCount) % slideCount;
  carousel.style.transform = `translateX(-${currentIndex * 25}%)`;
}

function nextSlide() {
  currentIndex = (currentIndex + 1) % slideCount;
  carousel.style.transform = `translateX(-${currentIndex * 25}%)`;
}