/* ================================== */
/* LÓGICA DEL CARRUSEL           */
/* ================================== */
const track = document.querySelector('.carrusel-track');
const slides = Array.from(track.children);
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');

// --- Selecciona los nuevos elementos de texto ---
const headerContent = document.querySelector('.header-content');
const slideTitle = document.getElementById('slide-title');
const slideSubtitle = document.getElementById('slide-subtitle');
// ---

let currentIndex = 0;
let autoSlideInterval;

// --- Define los textos para tus 5 slides ---
// (Asegúrate de que el número de textos coincida con tu número de imágenes)
const titles = [
    "Finanzas Urbanas Sostenibles", // Slide 1
    "Reduce tu Huella de Carbono",  // Slide 2
    "Ahorra Agua, Gana Beneficios", // Slide 3
    "Tu Movilidad Vale Más",        // Slide 4
    "Invierte en el Planeta"     // Slide 5
];

const subtitles = [
    "Conecta tus servicios, reduce tu huella y haz crecer tu dinero.",
    "Hemos ahorrado 1,500 toneladas de CO2 este mes.",
    "Conecta tu recibo de agua y obtén tasas preferenciales.",
    "Conoce nuestros Fondos de Inversión Verdes (ESG)."
];
// ---

function updateCarrusel() {
    // Mueve el track del carrusel
    if (slides.length > 0) {
        const width = slides[0].getBoundingClientRect().width;
        track.style.transform = `translateX(-${width * currentIndex}px)`;
    }

    // --- Lógica de animación de texto ---
    if (headerContent && slideTitle && slideSubtitle) {
        
        // 1. Oculta el texto (lo mueve hacia arriba y lo hace transparente)
        headerContent.style.transition = 'none'; // Quita la transición para ocultar al instante
        headerContent.style.opacity = 0;
        headerContent.style.transform = 'translate(-50%, -60%)'; // Posición inicial (arriba)

        // 2. Actualiza el texto basado en el nuevo currentIndex
        // Usamos 'currentIndex % titles.length' para evitar errores si hay más slides que textos
        slideTitle.textContent = titles[currentIndex % titles.length];
        slideSubtitle.textContent = subtitles[currentIndex % subtitles.length];

        // 3. Espera un momento (para que el slide se mueva) y luego anima la entrada
        setTimeout(() => {
            headerContent.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            headerContent.style.opacity = 1;
            headerContent.style.transform = 'translate(-50%, -50%)'; // Posición final (centrado)
        }, 300); // 300ms de retraso

        // 4. (Opcional) Limpia la transición después de que termine
        setTimeout(() => {
            if (headerContent) {
                headerContent.style.transition = 'none';
            }
        }, 800); // 300ms + 500ms
    }
}

function nextSlide() {
    if (slides.length > 0) {
        currentIndex = (currentIndex + 1) % slides.length;
        updateCarrusel();
    }
}

function prevSlide() {
    if (slides.length > 0) {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateCarrusel();
    }
}

// Asegura que los botones existan antes de añadirles eventos
if (nextButton && prevButton) {
    nextButton.addEventListener('click', () => {
        nextSlide();
        resetAutoSlide();
    });

    prevButton.addEventListener('click', () => {
        prevSlide();
        resetAutoSlide();
    });
}

function startAutoSlide() {
    // Solo inicia el auto-slide si hay más de una imagen
    if (slides.length > 1) {
        autoSlideInterval = setInterval(nextSlide, 5000);
    }
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

window.addEventListener('resize', updateCarrusel);
window.addEventListener('load', () => {
    updateCarrusel(); // Esto ahora también cargará el primer texto animado
    startAutoSlide();
});


/* ================================== */
/* LÓGICA DE ANIMACIÓN (SCROLL)   */
/* ================================== */
document.addEventListener("DOMContentLoaded", () => {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');

    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // Anima solo una vez
                }
            });
        }, {
            threshold: 0.1 // Se activa cuando el 10% del elemento es visible
        });

        animatedElements.forEach(el => {
            observer.observe(el);
        });
    } else {
        // Si el navegador es muy viejo, solo muestra los elementos
        animatedElements.forEach(el => {
            el.classList.add('is-visible');
        });
    }
});