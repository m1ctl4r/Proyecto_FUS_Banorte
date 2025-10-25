const track = document.querySelector('.carrusel-track');
const slides = Array.from(track.children);
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');
let currentIndex = 0;
let autoSlideInterval;

function updateCarrusel() {
    const width = slides[0].getBoundingClientRect().width;
    track.style.transform = `translateX(-${width * currentIndex}px)`;
}

function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    updateCarrusel();
}

function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateCarrusel();
}

nextButton.addEventListener('click', () => {
    nextSlide();
    resetAutoSlide();
});

prevButton.addEventListener('click', () => {
    prevSlide();
    resetAutoSlide();
});

function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, 5000);
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

window.addEventListener('resize', updateCarrusel);
window.addEventListener('load', () => {
    updateCarrusel();
    startAutoSlide();
});
