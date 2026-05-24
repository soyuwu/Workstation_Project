document.addEventListener('DOMContentLoaded', function() {
    // Megamenu Hover logic
    const wrapper = document.getElementById('megamenu-wrapper');
    const panel = document.getElementById('megamenu-panel');
    const arrow = document.getElementById('megamenu-arrow');
    let closeTimeout;

    function openMenu() {
        clearTimeout(closeTimeout);
        if (panel) {
            panel.classList.remove('opacity-0', 'invisible', '-translate-y-2');
            panel.classList.add('opacity-100', 'visible', 'translate-y-0');
        }
        if (arrow) arrow.classList.add('rotate-180');
    }

    function closeMenu() {
        closeTimeout = setTimeout(() => {
            if (panel) {
                panel.classList.add('opacity-0', 'invisible', '-translate-y-2');
                panel.classList.remove('opacity-100', 'visible', 'translate-y-0');
            }
            if (arrow) arrow.classList.remove('rotate-180');
        }, 150);
    }

    if (wrapper && panel) {
        wrapper.addEventListener('mouseenter', openMenu);
        wrapper.addEventListener('mouseleave', closeMenu);
        panel.addEventListener('mouseenter', openMenu);
        panel.addEventListener('mouseleave', closeMenu);
    }

    // Profile Dropdown (hover + click/tap + keyboard)
    const profileWrapper = document.querySelector('[data-profile-dropdown]');
    const profileTrigger = profileWrapper?.querySelector('[data-profile-dropdown-trigger]');
    const profileMenu = profileWrapper?.querySelector('[data-profile-dropdown-menu]');
    let profileCloseTimeout;

    function setProfileOpen(isOpen) {
        if (!profileWrapper || !profileTrigger) return;
        profileWrapper.classList.toggle('is-open', isOpen);
        profileTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    function openProfile() {
        clearTimeout(profileCloseTimeout);
        setProfileOpen(true);
    }

    function closeProfile(immediate = false) {
        clearTimeout(profileCloseTimeout);
        if (immediate) {
            setProfileOpen(false);
            return;
        }
        profileCloseTimeout = setTimeout(() => setProfileOpen(false), 150);
    }

    function toggleProfile() {
        if (!profileWrapper) return;
        setProfileOpen(!profileWrapper.classList.contains('is-open'));
    }

    if (profileWrapper && profileTrigger && profileMenu) {
        profileTrigger.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleProfile();
        });

        const canHover =
            typeof window.matchMedia === 'function' &&
            window.matchMedia('(any-hover: hover)').matches;

        if (canHover) {
            profileWrapper.addEventListener('mouseenter', openProfile);
            profileWrapper.addEventListener('mouseleave', () => closeProfile(false));
        }

        document.addEventListener('click', (event) => {
            if (profileWrapper.contains(event.target)) return;
            setProfileOpen(false);
        });

        document.addEventListener('focusin', (event) => {
            if (profileWrapper.contains(event.target)) return;
            setProfileOpen(false);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            if (!profileWrapper.classList.contains('is-open')) return;
            setProfileOpen(false);
            profileTrigger.focus();
        });
    }

    // Dynamic Scroll logic
    const nav = document.querySelector('.ws-nav');
    const navMode = document.body.getAttribute('data-nav-mode') || 'solid';

    if (nav && navMode === 'dynamic') {
        const navLinks = nav.querySelectorAll('.ws-nav-link');
        const projectTitle = nav.querySelector('.project-title');
        const authLinks = nav.querySelectorAll('a[href*="logIn"], a[href*="register"]');
        const ctaBtn = nav.querySelector('a[href*="booking"]');
        const menuToggle = nav.querySelector('#menu-toggle');

        function updateNavStyle() {
            if (window.scrollY > 50) {
                // Scrolled: white bg, dark text
                nav.classList.add('bg-white/95', 'backdrop-blur-xl', 'shadow-md', 'border-b', 'border-slate-100/80');
                nav.classList.remove('bg-transparent');
                navLinks.forEach(link => {
                    link.classList.remove('text-white', 'hover:text-blue-200');
                    link.classList.add('text-slate-600', 'hover:text-primary');
                    link.classList.remove('after:bg-white');
                    link.classList.add('after:bg-primary');
                });
                if (projectTitle) {
                    projectTitle.classList.remove('text-white');
                    projectTitle.classList.add('text-on-surface');
                }
                if (arrow) {
                    arrow.classList.remove('text-white');
                    arrow.classList.add('text-slate-600');
                }
                authLinks.forEach(link => {
                    link.classList.remove('text-white/80', 'hover:text-white');
                    link.classList.add('text-slate-600', 'hover:text-slate-900');
                });
                if (ctaBtn) {
                    ctaBtn.classList.remove('bg-white', 'text-primary', 'hover:bg-slate-100');
                    ctaBtn.classList.add('bg-primary', 'text-white', 'hover:opacity-90');
                }
                if (menuToggle) {
                    menuToggle.classList.remove('text-white');
                    menuToggle.classList.add('text-slate-700');
                }
            } else {
                // Top: transparent, white text
                nav.classList.remove('bg-white/95', 'backdrop-blur-xl', 'shadow-md', 'border-b', 'border-slate-100/80');
                nav.classList.add('bg-transparent');
                navLinks.forEach(link => {
                    link.classList.add('text-white', 'hover:text-blue-200');
                    link.classList.remove('text-slate-600', 'hover:text-primary');
                    link.classList.add('after:bg-white');
                    link.classList.remove('after:bg-primary');
                });
                if (projectTitle) {
                    projectTitle.classList.add('text-white');
                    projectTitle.classList.remove('text-on-surface');
                }
                if (arrow) {
                    arrow.classList.add('text-white');
                    arrow.classList.remove('text-slate-600');
                }
                authLinks.forEach(link => {
                    link.classList.add('text-white/80', 'hover:text-white');
                    link.classList.remove('text-slate-600', 'hover:text-slate-900');
                });
                if (ctaBtn) {
                    ctaBtn.classList.add('bg-white', 'text-primary', 'hover:bg-slate-100');
                    ctaBtn.classList.remove('bg-primary', 'text-white', 'hover:opacity-90');
                }
                if (menuToggle) {
                    menuToggle.classList.add('text-white');
                    menuToggle.classList.remove('text-slate-700');
                }
            }
        }

        window.addEventListener('scroll', updateNavStyle);
        updateNavStyle(); // Run on page load
    }
});
