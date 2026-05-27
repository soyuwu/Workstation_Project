function readJsonScript(id) {
    const el = document.getElementById(id);
    if (!el) return {};

    try {
        return JSON.parse(el.textContent || "{}");
    } catch {
        return {};
    }
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value || "--";
}

function toggle(el, show) {
    if (!el) return;
    el.classList.toggle("hidden", !show);
}

function formatRemaining(ms) {
    const totalSeconds = Math.max(0, Math.ceil(ms / 1000));
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return `${minutes}:${String(seconds).padStart(2, "0")}`;
}

function initPaymentCountdowns() {
    const countdownEls = Array.from(document.querySelectorAll("[data-payment-deadline]"));
    if (countdownEls.length === 0) return;

    let reloadQueued = false;

    const tick = () => {
        const now = Date.now();

        countdownEls.forEach((el) => {
            const deadline = Date.parse(el.getAttribute("data-payment-deadline") || "");
            if (!Number.isFinite(deadline)) return;

            const remaining = deadline - now;

            if (remaining <= 0) {
                el.textContent = "Hết hạn thanh toán";
                if (!reloadQueued) {
                    reloadQueued = true;
                    setTimeout(() => window.location.reload(), 1200);
                }
                return;
            }

            el.textContent = `Còn ${formatRemaining(remaining)}`;
        });
    };

    tick();
    setInterval(tick, 1000);
}

function initBookingDetailModal() {
    const details = readJsonScript("account-bookings-data");
    const modal = document.getElementById("booking-detail-modal");
    const panel = document.getElementById("booking-detail-panel");
    const cancelLink = document.getElementById("booking-cancel-link");
    const reviewForm = document.getElementById("booking-review-form");
    const payLink = document.getElementById("booking-modal-pay-link");
    const cancelButtonWrap = document.getElementById("booking-cancel-action");
    const noCancelReason = document.getElementById("booking-no-cancel-reason");
    const reviewSection = document.getElementById("booking-review-section");
    const existingReview = document.getElementById("booking-existing-review");
    const reviewUnavailable = document.getElementById("booking-review-unavailable");

    if (!modal || !panel) return;

    const openModal = (bookingId) => {
        const data = details[String(bookingId)];
        if (!data) return;

        setText("modal-booking-code", data.code);
        setText("modal-booking-workspace", data.workspace);
        setText("modal-booking-capacity", data.capacity);
        setText("modal-booking-date", data.date);
        setText("modal-booking-time", data.time);
        setText("modal-booking-created", data.created_at);
        setText("modal-booking-status", data.status);
        setText("modal-booking-payment-status", data.payment_status);
        setText("modal-booking-payment-method", data.payment_method);
        setText("modal-booking-paid-at", data.paid_at);
        setText("modal-booking-total", data.total_text);
        setText("modal-booking-base", data.base_text);
        setText("modal-booking-tax", data.tax_text);
        setText("modal-booking-notes", data.notes || "--");
        setText("modal-booking-cancel-policy", data.cancel_reason);
        setText("modal-booking-cancel-fee", data.cancel_fee_preview_text);
        setText("modal-booking-refund", data.refund_preview_text);

        const deadlineWrap = document.getElementById("modal-booking-deadline-wrap");
        toggle(deadlineWrap, Boolean(data.payment_deadline_text));
        setText("modal-booking-deadline", data.payment_deadline_text);

        if (payLink) {
            toggle(payLink, Boolean(data.can_pay && data.pay_url));
            if (data.pay_url) payLink.setAttribute("href", data.pay_url);
        }

        if (cancelLink) {
            cancelLink.href = data.cancel_url || "#";
        }
        toggle(cancelButtonWrap, Boolean(data.can_cancel && data.cancel_url));
        toggle(noCancelReason, !data.can_cancel);
        setText("booking-no-cancel-reason", data.cancel_reason);

        if (reviewForm) {
            reviewForm.action = data.review_url || "#";
            reviewForm.reset();
        }

        toggle(reviewSection, true);
        toggle(reviewForm, Boolean(data.can_review));
        toggle(existingReview, Boolean(data.review));
        toggle(reviewUnavailable, !data.can_review && !data.review);

        if (data.review) {
            setText("booking-existing-review-rating", `${data.review.rating}/5`);
            setText("booking-existing-review-content", data.review.content);
            setText("booking-existing-review-created", data.review.created_at);
        }

        modal.classList.remove("hidden");
        modal.classList.add("flex");
        document.body.classList.add("overflow-hidden");
        panel.focus();
    };

    const closeModal = () => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.classList.remove("overflow-hidden");
    };

    document.addEventListener("click", (event) => {
        const target = event.target;
        if (!(target instanceof Element)) return;

        if (target.closest("[data-close-booking-modal]")) {
            closeModal();
            return;
        }

        const opener = target.closest("[data-booking-detail-id]");
        if (!opener) return;

        const isExplicitOpen = Boolean(target.closest("[data-open-booking-detail]"));
        const isAction = Boolean(target.closest("a, button, input, select, textarea, form"));
        if (!isExplicitOpen && isAction) return;

        openModal(opener.getAttribute("data-booking-detail-id"));
    });

    modal.addEventListener("click", (event) => {
        if (event.target === modal) closeModal();
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !modal.classList.contains("hidden")) {
            closeModal();
        }
    });

}

document.addEventListener("DOMContentLoaded", () => {
    initPaymentCountdowns();
    initBookingDetailModal();
});
