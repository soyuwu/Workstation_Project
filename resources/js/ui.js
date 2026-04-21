const solidNavClasses = ["bg-white/95", "backdrop-blur-xl", "shadow-md"];
const transparentNavClasses = ["bg-transparent"];

function applyNavState(nav, isSolid) {
    const navLinks = nav.querySelectorAll(".ws-nav-link");
    const projectTitle = nav.querySelector(".project-title");
    const authLinks = nav.querySelectorAll(".ws-nav-auth");
    const ctaButton = nav.querySelector("[data-nav-cta]");
    const menuToggle = nav.querySelector("[data-menu-toggle]");

    if (isSolid) {
        nav.classList.add(...solidNavClasses);
        nav.classList.remove(...transparentNavClasses);

        navLinks.forEach((link) => {
            link.classList.remove("text-white", "hover:text-blue-200", "after:bg-white");
            link.classList.add("text-slate-600", "hover:text-primary", "after:bg-primary");
        });

        authLinks.forEach((link) => {
            link.classList.remove("text-white/80", "hover:text-white");
            link.classList.add("text-slate-600", "hover:text-slate-900");
        });

        if (projectTitle) {
            projectTitle.classList.remove("text-white");
            projectTitle.classList.add("text-on-surface");
        }

        if (ctaButton) {
            ctaButton.classList.remove("bg-white", "text-primary", "hover:bg-slate-100");
            ctaButton.classList.add("bg-primary", "text-white", "hover:opacity-90");
        }

        if (menuToggle) {
            menuToggle.classList.remove("text-white");
            menuToggle.classList.add("text-slate-700");
        }

        return;
    }

    nav.classList.remove(...solidNavClasses);
    nav.classList.add(...transparentNavClasses);

    navLinks.forEach((link) => {
        link.classList.add("text-white", "hover:text-blue-200", "after:bg-white");
        link.classList.remove("text-slate-600", "hover:text-primary", "after:bg-primary");
    });

    authLinks.forEach((link) => {
        link.classList.add("text-white/80", "hover:text-white");
        link.classList.remove("text-slate-600", "hover:text-slate-900");
    });

    if (projectTitle) {
        projectTitle.classList.add("text-white");
        projectTitle.classList.remove("text-on-surface");
    }

    if (ctaButton) {
        ctaButton.classList.add("bg-white", "text-primary", "hover:bg-slate-100");
        ctaButton.classList.remove("bg-primary", "text-white", "hover:opacity-90");
    }

    if (menuToggle) {
        menuToggle.classList.add("text-white");
        menuToggle.classList.remove("text-slate-700");
    }
}

function initNavigation() {
    const nav = document.querySelector(".ws-nav");

    if (!nav) {
        return;
    }

    const navMode = document.body.dataset.navMode || "dynamic";
    const menuToggle = nav.querySelector("[data-menu-toggle]");
    const mobileMenu = nav.querySelector("[data-mobile-menu]");

    const updateNav = () => {
        applyNavState(nav, navMode === "solid" || window.scrollY > 50);
    };

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener("click", () => {
            const isOpen = !mobileMenu.classList.contains("hidden");

            mobileMenu.classList.toggle("hidden", isOpen);
            menuToggle.setAttribute("aria-expanded", String(!isOpen));
        });

        mobileMenu.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                mobileMenu.classList.add("hidden");
                menuToggle.setAttribute("aria-expanded", "false");
            });
        });
    }

    if (navMode === "dynamic") {
        window.addEventListener("scroll", updateNav, { passive: true });
    }

    updateNav();
}

function initCarousel() {
    const carousel = document.querySelector(".carousel");

    if (!carousel) {
        return;
    }

    const slider = carousel.querySelector(".list");
    const thumbnail = carousel.querySelector(".thumbnail");
    const nextButton = carousel.querySelector("#next");
    const prevButton = carousel.querySelector("#prev");

    if (!slider || !thumbnail || !nextButton || !prevButton) {
        return;
    }

    const thumbnailItems = thumbnail.querySelectorAll(".item");

    if (thumbnailItems.length > 0) {
        thumbnail.appendChild(thumbnailItems[0]);
    }

    const timeRunning = 800;
    const timeAutoNext = 5000;
    let runningTimeout;
    let autoNextTimeout;

    const showSlider = (direction) => {
        const sliderItems = slider.querySelectorAll(".item");
        const nextThumbnailItems = thumbnail.querySelectorAll(".item");

        if (sliderItems.length === 0 || nextThumbnailItems.length === 0) {
            return;
        }

        if (direction === "next") {
            slider.appendChild(sliderItems[0]);
            thumbnail.appendChild(nextThumbnailItems[0]);
            carousel.classList.add("next");
        } else {
            slider.prepend(sliderItems[sliderItems.length - 1]);
            thumbnail.prepend(nextThumbnailItems[nextThumbnailItems.length - 1]);
            carousel.classList.add("prev");
        }

        window.clearTimeout(runningTimeout);
        runningTimeout = window.setTimeout(() => {
            carousel.classList.remove("next", "prev");
        }, timeRunning);

        window.clearTimeout(autoNextTimeout);
        autoNextTimeout = window.setTimeout(() => {
            nextButton.click();
        }, timeAutoNext);
    };

    nextButton.addEventListener("click", () => showSlider("next"));
    prevButton.addEventListener("click", () => showSlider("prev"));

    autoNextTimeout = window.setTimeout(() => {
        nextButton.click();
    }, timeAutoNext);
}

function initRevealAnimations() {
    const revealElements = document.querySelectorAll(
        ".reveal, .reveal-left, .reveal-right, .reveal-stagger",
    );

    if (revealElements.length === 0) {
        return;
    }

    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add("revealed");
                revealObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.15,
            rootMargin: "0px 0px -60px 0px",
        },
    );

    revealElements.forEach((element) => revealObserver.observe(element));
}

function initCounterAnimations() {
    const statNumbers = document.querySelectorAll(".stat-number[data-target]");

    if (statNumbers.length === 0) {
        return;
    }

    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const element = entry.target;
                const target = Number.parseInt(element.dataset.target || "0", 10);
                const duration = 2000;
                const startTime = performance.now();

                const updateCounter = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easedProgress = 1 - Math.pow(1 - progress, 3);
                    const currentValue = Math.round(target * easedProgress);
                    const suffix = target === 98 ? "%" : "+";

                    element.textContent = `${currentValue.toLocaleString()}${suffix}`;

                    if (progress < 1) {
                        window.requestAnimationFrame(updateCounter);
                    }
                };

                window.requestAnimationFrame(updateCounter);
                counterObserver.unobserve(element);
            });
        },
        { threshold: 0.5 },
    );

    statNumbers.forEach((element) => counterObserver.observe(element));
}

function initTestimonials() {
    if (!document.querySelector(".testimonial-swiper") || !window.Swiper) {
        return;
    }

    new window.Swiper(".testimonial-swiper", {
        slidesPerView: 1,
        spaceBetween: 16,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 24,
            },
        },
    });
}

function initWorkspaceFilter() {
    const filterButtons = document.querySelectorAll(".filter-btn[data-filter]");
    const cards = document.querySelectorAll(".workspace-card[data-category]");

    if (filterButtons.length === 0 || cards.length === 0) {
        return;
    }

    const updateFilter = (filter) => {
        cards.forEach((card) => {
            const shouldShow = filter === "all" || card.dataset.category === filter;

            if (shouldShow) {
                card.style.display = "";
                card.style.opacity = "0";
                card.style.transform = "translateY(20px)";

                window.requestAnimationFrame(() => {
                    card.style.opacity = "1";
                    card.style.transform = "translateY(0)";
                });

                return;
            }

            card.style.display = "none";
            card.style.opacity = "0";
            card.style.transform = "translateY(20px)";
        });
    };

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            filterButtons.forEach((item) => {
                item.classList.remove("bg-primary", "text-white");
                item.classList.add("bg-slate-100", "text-slate-600");
            });

            button.classList.remove("bg-slate-100", "text-slate-600");
            button.classList.add("bg-primary", "text-white");

            updateFilter(button.dataset.filter || "all");
        });
    });
}

function initAuthPanel() {
    const authContainer = document.querySelector("[data-auth-container]");
    const toggles = document.querySelectorAll("[data-auth-target]");

    if (!authContainer || toggles.length === 0) {
        return;
    }

    const updateToggles = (isRegister) => {
        toggles.forEach((toggle) => {
            const isActive =
                (toggle.dataset.authTarget === "register" && isRegister) ||
                (toggle.dataset.authTarget === "login" && !isRegister);

            if (Object.prototype.hasOwnProperty.call(toggle.dataset, "authToggle")) {
                toggle.classList.toggle("bg-white", isActive);
                toggle.classList.toggle("text-primary", isActive);
                toggle.classList.toggle("shadow-sm", isActive);
                toggle.classList.toggle("text-slate-600", !isActive);
            }

            toggle.setAttribute("aria-pressed", String(isActive));
        });
    };

    updateToggles(authContainer.classList.contains("is-register"));

    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const isRegister = toggle.dataset.authTarget === "register";

            authContainer.classList.toggle("is-register", isRegister);
            updateToggles(isRegister);
        });
    });
}

function initUi() {
    initNavigation();
    initCarousel();
    initRevealAnimations();
    initCounterAnimations();
    initTestimonials();
    initWorkspaceFilter();
    initAuthPanel();
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initUi);
} else {
    initUi();
}
