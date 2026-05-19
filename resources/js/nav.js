/**
 * Navigation inline scripts
 * Megamenu hover + Profile dropdown toggle
 */
document.addEventListener("DOMContentLoaded", function () {
    // Megamenu Hover
    const wrapper = document.getElementById("megamenu-wrapper");
    const panel = document.getElementById("megamenu-panel");
    const arrow = document.getElementById("megamenu-arrow");
    let closeTimeout;

    function openMenu() {
        clearTimeout(closeTimeout);
        panel.classList.remove("opacity-0", "invisible", "-translate-y-2");
        panel.classList.add("opacity-100", "visible", "translate-y-0");
        if (arrow) arrow.classList.add("rotate-180");
    }

    function closeMenu() {
        closeTimeout = setTimeout(() => {
            panel.classList.add("opacity-0", "invisible", "-translate-y-2");
            panel.classList.remove("opacity-100", "visible", "translate-y-0");
            if (arrow) arrow.classList.remove("rotate-180");
        }, 150);
    }

    if (wrapper && panel) {
        wrapper.addEventListener("mouseenter", openMenu);
        wrapper.addEventListener("mouseleave", closeMenu);
        panel.addEventListener("mouseenter", openMenu);
        panel.addEventListener("mouseleave", closeMenu);
    }

    // Profile Dropdown Toggle
    const avatarBtn = document.getElementById("profile-avatar-btn");
    const dropdownMenu = document.getElementById("profile-dropdown-menu");

    if (avatarBtn && dropdownMenu) {
        avatarBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            const isOpen = !dropdownMenu.classList.contains("invisible");
            if (isOpen) {
                dropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "-translate-y-2",
                );
                dropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0",
                );
            } else {
                dropdownMenu.classList.remove(
                    "opacity-0",
                    "invisible",
                    "-translate-y-2",
                );
                dropdownMenu.classList.add(
                    "opacity-100",
                    "visible",
                    "translate-y-0",
                );
            }
        });

        document.addEventListener("click", function (e) {
            if (
                !dropdownMenu.contains(e.target) &&
                !avatarBtn.contains(e.target)
            ) {
                dropdownMenu.classList.add(
                    "opacity-0",
                    "invisible",
                    "-translate-y-2",
                );
                dropdownMenu.classList.remove(
                    "opacity-100",
                    "visible",
                    "translate-y-0",
                );
            }
        });
    }
});
