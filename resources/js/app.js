import './bootstrap';

function initRevealAnimations() {
    const revealEls = document.querySelectorAll('.reveal');

    if (!revealEls.length) return;

    if (!('IntersectionObserver' in window)) {
        revealEls.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -10% 0px' }
    );

    revealEls.forEach((el) => observer.observe(el));
}

function animateCounter(el) {
    const target = parseFloat(el.dataset.target ?? '0');
    const suffix = el.dataset.suffix ?? '';
    const prefix = el.dataset.prefix ?? '';
    const duration = 1400;
    const start = performance.now();

    function tick(now) {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(target * eased);

        el.textContent = prefix + value.toLocaleString('en-US') + suffix;

        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    }

    requestAnimationFrame(tick);
}

function initCounters() {
    const counterEls = document.querySelectorAll('[data-counter]');

    if (!counterEls.length) return;

    if (!('IntersectionObserver' in window)) {
        counterEls.forEach((el) => animateCounter(el));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 }
    );

    counterEls.forEach((el) => observer.observe(el));
}

function initAccordions() {
    const triggers = document.querySelectorAll('.faq-trigger');

    if (!triggers.length) return;

    triggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const panel = document.getElementById(trigger.getAttribute('aria-controls'));
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';

            triggers.forEach((other) => {
                if (other === trigger) return;
                other.setAttribute('aria-expanded', 'false');
                document.getElementById(other.getAttribute('aria-controls')).style.gridTemplateRows = '0fr';
                other.querySelector('.faq-icon')?.classList.remove('rotate-180');
            });

            trigger.setAttribute('aria-expanded', String(!isOpen));
            panel.style.gridTemplateRows = isOpen ? '0fr' : '1fr';
            trigger.querySelector('.faq-icon')?.classList.toggle('rotate-180', !isOpen);
        });
    });
}

function initStickyNavbar() {
    const nav = document.getElementById('site-navbar');

    if (!nav) return;

    const scrolledClasses = ['bg-white/90', 'backdrop-blur-xl', 'shadow-lg', 'border-b', 'border-slate-200/80'];

    const onScroll = () => {
        if (window.scrollY > 8) {
            nav.classList.remove('bg-transparent');
            nav.classList.add(...scrolledClasses);
        } else {
            nav.classList.add('bg-transparent');
            nav.classList.remove(...scrolledClasses);
        }
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

function initMobileMenu() {
    const openBtn = document.getElementById('nav-menu-open');
    const closeBtn = document.getElementById('nav-menu-close');
    const menu = document.getElementById('mobile-menu');

    if (!openBtn || !menu) return;

    const links = menu.querySelectorAll('a');

    const openMenu = () => {
        menu.classList.remove('translate-x-full');
        menu.classList.add('translate-x-0');
        document.body.classList.add('overflow-hidden');
        openBtn.setAttribute('aria-expanded', 'true');
    };

    const closeMenu = () => {
        menu.classList.add('translate-x-full');
        menu.classList.remove('translate-x-0');
        document.body.classList.remove('overflow-hidden');
        openBtn.setAttribute('aria-expanded', 'false');
    };

    openBtn.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click', closeMenu);
    links.forEach((link) => link.addEventListener('click', closeMenu));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeMenu();
    });
}

function initVideoModal() {
    const triggers = document.querySelectorAll('[data-video-trigger]');
    const modal = document.getElementById('video-modal');
    const frame = document.getElementById('video-modal-frame');
    const closeBtn = document.getElementById('video-modal-close');

    if (!triggers.length || !modal || !frame) return;

    const openModal = (youtubeId) => {
        frame.innerHTML = `<iframe class="h-full w-full" src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0" title="Video testimonial" frameborder="0" allow="accelerate-magnetometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        frame.innerHTML = '';
    };

    triggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const youtubeId = trigger.dataset.youtubeId;
            if (youtubeId) openModal(youtubeId);
        });
    });

    closeBtn?.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeModal();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initRevealAnimations();
    initCounters();
    initAccordions();
    initStickyNavbar();
    initMobileMenu();
    initVideoModal();
});
