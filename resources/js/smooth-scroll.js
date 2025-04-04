document.addEventListener('DOMContentLoaded', () => {
    // Get all navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    // Add click event listener to each link
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Get the target section
            const targetId = link.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                // Calculate the offset to account for the fixed header
                const headerHeight = document.querySelector('nav').offsetHeight;
                const targetPosition = targetSection.offsetTop - headerHeight;
                
                // Smooth scroll to the target section
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                const mobileMenu = document.querySelector('[x-data]');
                if (mobileMenu && mobileMenu.__x.$data.mobileMenu) {
                    mobileMenu.__x.$data.mobileMenu = false;
                }
            }
        });
    });
    
    // Add scroll event listener to update active navigation link
    const sections = document.querySelectorAll('section[id]');
    
    window.addEventListener('scroll', () => {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            const headerHeight = document.querySelector('nav').offsetHeight;
            
            if (window.pageYOffset >= (sectionTop - headerHeight - 100)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('text-sage-600');
            link.classList.add('text-sage-700');
            
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.remove('text-sage-700');
                link.classList.add('text-sage-600');
            }
        });
    });
}); 