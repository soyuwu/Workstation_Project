const DISCOUNT_MAP = { 1: 0, 3: 0.05, 6: 0.1, 12: 0.15 };

function formatVnd(amount) {
    if (!Number.isFinite(amount)) return "";
    return amount.toLocaleString("vi-VN") + " VNĐ";
}

function initMonthlyBooking() {
    const roomCards = document.querySelectorAll("article[data-room-id]");
    if (roomCards.length === 0) return;

    const roomIdInput = document.getElementById("form_room_id");
    const selectedRoomInput = document.getElementById("selected_room");
    const durationSelect = document.getElementById("form_duration");
    const submitBtn = document.getElementById("submit-btn");

    const pricePreview = document.getElementById("price-preview");
    const previewBase = document.getElementById("preview-base");
    const previewDiscount = document.getElementById("preview-discount");
    const previewTotal = document.getElementById("preview-total");

    if (
        !roomIdInput ||
        !selectedRoomInput ||
        !durationSelect ||
        !submitBtn ||
        !pricePreview ||
        !previewBase ||
        !previewDiscount ||
        !previewTotal
    ) {
        return;
    }

    let selectedPrice = 0;

    const updatePreview = () => {
        if (!selectedPrice) {
            pricePreview.classList.add("hidden");
            return;
        }

        const months = parseInt(durationSelect.value, 10);
        const discount = DISCOUNT_MAP[months] || 0;

        const base = selectedPrice * months;
        const discountAmt = base * discount;
        const total = base - discountAmt;

        pricePreview.classList.remove("hidden");
        previewBase.textContent = formatVnd(base);
        previewDiscount.textContent = "- " + formatVnd(discountAmt);
        previewTotal.textContent = formatVnd(total);
    };

    const clearSelected = () => {
        roomCards.forEach((el) => {
            el.classList.remove("ring-2", "ring-primary", "border-primary");
        });
    };

    const selectCard = (card) => {
        const roomId = card.dataset.roomId;
        const roomName = card.dataset.roomName || "";
        const roomPrice = Number(card.dataset.roomPrice || 0);

        if (!roomId || !Number.isFinite(roomPrice) || roomPrice <= 0) return;

        selectedPrice = roomPrice;
        roomIdInput.value = roomId;
        selectedRoomInput.value = roomName;

        clearSelected();
        card.classList.add("ring-2", "ring-primary", "border-primary");

        updatePreview();
        submitBtn.disabled = false;

        selectedRoomInput.scrollIntoView({ behavior: "smooth", block: "center" });
    };

    roomCards.forEach((card) => {
        card.addEventListener("click", () => selectCard(card));
        card.addEventListener("keydown", (event) => {
            if (event.key !== "Enter" && event.key !== " ") return;
            event.preventDefault();
            selectCard(card);
        });

        card.setAttribute("role", "button");
        card.setAttribute("tabindex", "0");
    });

    durationSelect.addEventListener("change", updatePreview);
}

initMonthlyBooking();
