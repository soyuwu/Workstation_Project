function initPaymentVietqr() {
    const root = document.querySelector("[data-check-status-url][data-success-url]");
    if (!root) return;

    const checkStatusUrl = root.getAttribute("data-check-status-url");
    const successUrl = root.getAttribute("data-success-url");
    const pollMs = Number(root.getAttribute("data-poll-ms") || 3000);

    if (!checkStatusUrl || !successUrl) return;

    const timer = setInterval(async () => {
        try {
            const response = await fetch(checkStatusUrl, {
                headers: { Accept: "application/json" },
            });

            if (!response.ok) return;

            const data = await response.json();
            const paymentStatus = String(data?.payment_status || "");

            if (paymentStatus === "completed") {
                clearInterval(timer);
                window.location.href = successUrl;
            }
        } catch {
            // Ignore transient network errors.
        }
    }, pollMs);
}

initPaymentVietqr();
