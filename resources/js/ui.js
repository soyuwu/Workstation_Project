


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
                const target = Number.parseInt(
                    element.dataset.target || "0",
                    10,
                );
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
            const shouldShow =
                filter === "all" || card.dataset.category === filter;

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

            if (
                Object.prototype.hasOwnProperty.call(
                    toggle.dataset,
                    "authToggle",
                )
            ) {
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
