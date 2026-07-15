/* ================================================
   TERRA VIVID — Interactive JavaScript
   KKN Rowosari 3R Website
   ================================================ */

import '../css/app.css';

document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initScrollAnimations();
    initProductCards();
    initProductModal();
    initGalleryScroll();
    initLightbox();
    initCounterAnimation();
    initMobileMenu();
});

/* =================== NAVBAR SCROLL BEHAVIOR =================== */
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    if (!navbar.classList.contains('is-transparent')) {
        return; // If not intended to be transparent initially, stay solid.
    }

    const handleScroll = () => {
        if (window.scrollY > 80) {
            navbar.classList.remove('navbar--transparent');
            navbar.classList.add('navbar--solid');
        } else {
            navbar.classList.add('navbar--transparent');
            navbar.classList.remove('navbar--solid');
        }
    };

    handleScroll();
    window.addEventListener('scroll', handleScroll, { passive: true });
}

/* =================== SCROLL ANIMATIONS (Custom AOS) =================== */
function initScrollAnimations() {
    const elements = document.querySelectorAll('[data-aos]');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('aos-animate');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    });

    elements.forEach((el) => observer.observe(el));
}

/* =================== 3D TILT EFFECT ON PRODUCT CARDS =================== */
function initProductCards() {
    const cards = document.querySelectorAll('.product-card');

    cards.forEach((card) => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * -5;
            const rotateY = ((x - centerX) / centerX) * 5;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });
}

/* =================== PRODUCT DETAIL MODAL =================== */
function initProductModal() {
    const overlay = document.getElementById('productModal');
    if (!overlay) return;

    // Open modal on product card click
    document.querySelectorAll('.product-card').forEach((card) => {
        card.addEventListener('click', async (e) => {
            // Don't open modal if clicking a link
            if (e.target.closest('a')) return;

            const productId = card.dataset.productId;
            if (!productId) return;

            try {
                const response = await fetch(`/product/${productId}`);
                const product = await response.json();
                renderModal(product);
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            } catch (err) {
                console.error('Failed to load product:', err);
            }
        });
    });

    // Close modal
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay || e.target.closest('.modal__close')) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    function closeModal() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function renderModal(product) {
        const mainImage = overlay.querySelector('.modal__image');
        const galleryContainer = overlay.querySelector('.modal__gallery');
        const categoryEl = overlay.querySelector('.modal__category');
        const titleEl = overlay.querySelector('.modal__title');
        const priceEl = overlay.querySelector('.modal__price');
        const descEl = overlay.querySelector('.modal__desc');
        const shopeeLink = overlay.querySelector('.modal__shopee');
        const tokopediaLink = overlay.querySelector('.modal__tokopedia');
        const view3dLink = overlay.querySelector('.modal__view3d');

        // Set image
        if (product.images && product.images.length > 0) {
            mainImage.src = product.images[0].url;
            mainImage.alt = product.name;

            // Render gallery thumbnails
            galleryContainer.innerHTML = '';
            if (product.images.length > 1) {
                product.images.forEach((img, idx) => {
                    const thumb = document.createElement('img');
                    thumb.src = img.url;
                    thumb.alt = product.name;
                    thumb.className = 'modal__gallery-thumb' + (idx === 0 ? ' active' : '');
                    thumb.addEventListener('click', () => {
                        mainImage.src = img.url;
                        galleryContainer.querySelectorAll('.modal__gallery-thumb').forEach(t => t.classList.remove('active'));
                        thumb.classList.add('active');
                    });
                    galleryContainer.appendChild(thumb);
                });
            }
        } else {
            mainImage.src = '';
            mainImage.alt = 'No image';
            galleryContainer.innerHTML = '';
        }

        // Set text content
        categoryEl.textContent = product.category === 'organik' ? '🌱 Organik' : '✂️ Kriya';
        titleEl.textContent = product.name;
        priceEl.textContent = product.price;
        descEl.textContent = product.description || 'Tidak ada deskripsi.';

        // Marketplace links
        if (product.shopee_link) {
            shopeeLink.href = product.shopee_link;
            shopeeLink.style.display = '';
        } else {
            shopeeLink.style.display = 'none';
        }

        if (product.tokopedia_link) {
            tokopediaLink.href = product.tokopedia_link;
            tokopediaLink.style.display = '';
        } else {
            tokopediaLink.style.display = 'none';
        }

        // 3D Viewer link
        if (product.model_type && product.slug) {
            view3dLink.href = `/produk/${product.slug}`;
            view3dLink.style.display = '';
        } else {
            view3dLink.style.display = 'none';
        }

        // Kompos Eksplorasi link
        const komposLink = overlay.querySelector('.modal__kompos');
        if (komposLink) {
            const name = (product.name || '').toLowerCase();
            if (name.includes('kompos') || name.includes('pupuk')) {
                komposLink.style.display = '';
            } else {
                komposLink.style.display = 'none';
            }
        }
    }
}

/* =================== HORIZONTAL SCROLL GALLERY =================== */
function initGalleryScroll() {
    const gallery = document.querySelector('.gallery-scroll');
    if (!gallery) return;

    let isDown = false;
    let startX;
    let scrollLeft;

    gallery.addEventListener('mousedown', (e) => {
        isDown = true;
        gallery.style.cursor = 'grabbing';
        startX = e.pageX - gallery.offsetLeft;
        scrollLeft = gallery.scrollLeft;
    });

    gallery.addEventListener('mouseleave', () => {
        isDown = false;
        gallery.style.cursor = 'grab';
    });

    gallery.addEventListener('mouseup', () => {
        isDown = false;
        gallery.style.cursor = 'grab';
    });

    gallery.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - gallery.offsetLeft;
        const walk = (x - startX) * 1.5;
        gallery.scrollLeft = scrollLeft - walk;
    });
}

/* =================== LIGHTBOX =================== */
function initLightbox() {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox) return;

    const lightboxImg = lightbox.querySelector('.lightbox__img');

    document.querySelectorAll('.gallery-card').forEach((card) => {
        card.addEventListener('click', () => {
            const img = card.querySelector('img');
            if (!img) return;
            lightboxImg.src = img.src;
            lightboxImg.alt = img.alt || '';
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox || e.target.closest('.lightbox__close')) {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

/* =================== ANIMATED COUNTERS =================== */
function initCounterAnimation() {
    const counters = document.querySelectorAll('.stat-card__number');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach((counter) => observer.observe(counter));

    function animateCounter(el) {
        const target = parseInt(el.dataset.target) || 0;
        const suffix = el.dataset.suffix || '';
        const duration = 2000;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);

            el.textContent = current.toLocaleString('id-ID') + suffix;

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }
}

/* =================== MOBILE MENU =================== */
function initMobileMenu() {
    const toggle = document.querySelector('.navbar__toggle');
    const nav = document.querySelector('.navbar__nav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', () => {
        nav.classList.toggle('open');
        toggle.classList.toggle('active');
    });

    // Close when clicking a link
    nav.querySelectorAll('.navbar__link').forEach((link) => {
        link.addEventListener('click', () => {
            nav.classList.remove('open');
            toggle.classList.remove('active');
        });
    });
}

/* =================== ADMIN: IMAGE UPLOAD PREVIEW =================== */
window.initImageUpload = function(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;

    input.addEventListener('change', (e) => {
        preview.innerHTML = '';
        const files = Array.from(e.target.files);
        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
};

/* ================================================================
   ✨ UI/UX ENHANCEMENTS — NEW SCRIPTS
   ================================================================ */

document.addEventListener('DOMContentLoaded', () => {
    initScrollToTop();
    initTypingEffect();
    initFunElements();
    initButtonRipples();
});

/* =================== SCROLL TO TOP WITH PROGRESS =================== */
function initScrollToTop() {
    const scrollBtn = document.querySelector('.scroll-top');
    const ringProgress = document.querySelector('.ring-progress');
    if (!scrollBtn || !ringProgress) return;

    const circumference = 126; // 2 * PI * 20 (r=20)
    ringProgress.style.strokeDasharray = `${circumference} ${circumference}`;
    ringProgress.style.strokeDashoffset = circumference;

    const handleScroll = () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = scrollTop / docHeight;
        
        // Show/hide button
        if (scrollTop > 300) {
            scrollBtn.classList.add('visible');
        } else {
            scrollBtn.classList.remove('visible');
        }
        
        // Update progress ring
        const offset = circumference - (scrollPercent * circumference);
        ringProgress.style.strokeDashoffset = offset;
    };

    window.addEventListener('scroll', handleScroll, { passive: true });

    scrollBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/* =================== TYPING EFFECT =================== */
function initTypingEffect() {
    const labels = document.querySelectorAll('.section__label');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('typing');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    labels.forEach(label => observer.observe(label));
}

/* =================== BUTTON RIPPLE EFFECT =================== */
function initButtonRipples() {
    const buttons = document.querySelectorAll('.btn-primary, .btn-amber, .btn-outline');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Don't add ripple if we're clicking the inner span/icon directly
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.classList.add('btn-ripple');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

/* =================== FUN INTERACTIVE ELEMENTS =================== */
function initFunElements() {
    let firstProductClicked = false;
    
    // Confetti on first product click
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (!firstProductClicked && !e.target.closest('.product-card__action')) {
                firstProductClicked = true;
                fireConfetti(e.clientX, e.clientY);
            }
        });
    });
    
    // Emoji Rain on Hero Badge Hover
    const recycleBadge = document.querySelector('.hero__badge:last-child');
    if (recycleBadge) {
        recycleBadge.addEventListener('mouseenter', () => {
            if (!recycleBadge.dataset.raining) {
                recycleBadge.dataset.raining = 'true';
                createEmojiRain();
                setTimeout(() => {
                    recycleBadge.dataset.raining = '';
                }, 3000);
            }
        });
    }
}

function fireConfetti(x, y) {
    const colors = ['#4a7c59', '#c4a66a', '#2d6a4f', '#ffffff'];
    const count = 30;
    
    for (let i = 0; i < count; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti-piece';
        
        // Random properties
        const color = colors[Math.floor(Math.random() * colors.length)];
        const left = x + (Math.random() - 0.5) * 100;
        const top = y + (Math.random() - 0.5) * 100;
        
        confetti.style.backgroundColor = color;
        confetti.style.left = left + 'px';
        confetti.style.top = top + 'px';
        
        document.body.appendChild(confetti);
        
        // Remove after animation
        setTimeout(() => {
            confetti.remove();
        }, 1500);
    }
}

function createEmojiRain() {
    const emojis = ['♻️', '🌱', '🌍', '💚'];
    const count = 15;
    
    for (let i = 0; i < count; i++) {
        setTimeout(() => {
            const emoji = document.createElement('div');
            emoji.className = 'emoji-rain';
            emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            
            // Random start position near the top edge
            emoji.style.left = (Math.random() * 100) + 'vw';
            emoji.style.top = '-50px';
            
            document.body.appendChild(emoji);
            
            setTimeout(() => {
                emoji.remove();
            }, 2000);
        }, i * 100);
    }
}

