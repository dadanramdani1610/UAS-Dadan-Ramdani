// ===== NUSANTARAWILD - MAIN JS =====

// Navbar scroll effect

const navbar = document.getElementById('mainNavbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,.1)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    });
}

// Fade in on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.dest-card, .kategori-card, .why-card, .team-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
});

// ===== NUSANTARAWILD - COOKIE HANDLERS ARE DEFINED IN INDEX.PHP INLINE TO PREVENT CONFLICTS =====
// Scroll-to-top button functionality
const scrollBtn = document.getElementById('scrollTopBtn');
if (scrollBtn) {
    // Show or hide button based on scroll position
    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            scrollBtn.parentElement.style.display = 'block';
        } else {
            scrollBtn.parentElement.style.display = 'none';
        }
    });
    // Smooth scroll to top on click
    scrollBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
