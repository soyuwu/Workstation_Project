function scheduleAutoDismiss(element) {
    const rawTimeout = element.getAttribute("data-auto-dismiss");
    const timeout = Number.parseInt(rawTimeout, 10);

    if (!Number.isFinite(timeout) || timeout <= 0) {
        return;
    }

    if (element.dataset.autoDismissBound === "1") {
        return;
    }
    element.dataset.autoDismissBound = "1";

    window.setTimeout(() => {
        element.classList.add("opacity-0");
        element.classList.add("pointer-events-none");

        window.setTimeout(() => {
            element.remove();
        }, 320);
    }, timeout);
}

export function initFlashAutoDismiss(root = document) {
    root.querySelectorAll("[data-auto-dismiss]").forEach((element) => {
        scheduleAutoDismiss(element);
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        initFlashAutoDismiss();
    });
} else {
    initFlashAutoDismiss();
}
