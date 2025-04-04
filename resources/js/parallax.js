document.addEventListener('DOMContentLoaded', () => {
    const heroSection = document.querySelector('.relative.h-screen'); // Target the hero section
    const heroBackground = heroSection.querySelector('img'); // Target the background image

    if (!heroSection || !heroBackground) return; // Exit if elements not found

    window.addEventListener('scroll', () => {
        const scrollPosition = window.pageYOffset;
        // Apply a subtle vertical translation based on scroll position
        // Adjust the multiplier (0.3) for more or less parallax effect
        heroBackground.style.transform = `translateY(${scrollPosition * 0.3}px)`;
    });
}); 