function initPaymentSuccessCountdown() {
    const root = document.querySelector("[data-home-url]");
    const countdownEl = document.getElementById("countdown-num");

    if (!root || !countdownEl) return;

    const homeUrl = root.getAttribute("data-home-url") || "/";
    const initial = Number(root.getAttribute("data-countdown-seconds") || 10);

    let count = Number.isFinite(initial) ? initial : 10;
    countdownEl.textContent = String(count);

    const timer = setInterval(() => {
        count -= 1;
        countdownEl.textContent = String(count);

        if (count <= 0) {
            clearInterval(timer);
            window.location.href = homeUrl;
        }
    }, 1000);
}

initPaymentSuccessCountdown();

