/**
 * Booking Hourly - Timeline interaction logic
 */
(function () {
    const dateInput = document.getElementById("booking_date");
    const tracks = document.querySelectorAll(".timeline-grid[data-room-id]");
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, "0");
    const dd = String(today.getDate()).padStart(2, "0");
    dateInput.value = `${yyyy}-${mm}-${dd}`;
    dateInput.min = `${yyyy}-${mm}-${dd}`;

    // Mock some booked slots for realism
    mockBookings();

    dateInput.addEventListener("change", () => {
        updateTimelineState();
        mockBookings(); // Randomize mock data on date change
    });

    let isDragging = false;
    let startSlot = null;
    let currentRow = null;
    let currentRoomData = null;
    let selectedSlots = [];

    // Ngăn chặn hành vi kéo thả mặc định
    document.addEventListener("dragstart", (e) => e.preventDefault());

    const container = document.querySelector(".timeline-container");

    // Event Delegation cho mousedown
    container.addEventListener("mousedown", (e) => {
        const slot = e.target.closest(".time-slot");
        if (!slot) return;

        if (e.button !== 0) return;
        if (
            slot.classList.contains("disabled") ||
            slot.classList.contains("booked")
        )
            return;

        isDragging = true;
        startSlot = parseInt(slot.dataset.slot);
        currentRow = slot.closest(".timeline-grid");
        currentRoomData = {
            id: currentRow.dataset.roomId,
            name: currentRow.dataset.roomName,
            price: parseInt(currentRow.dataset.roomPrice),
            image: currentRow.dataset.roomImage,
        };

        clearSelection();
        selectRange(currentRow, startSlot, startSlot);
    });

    // Event Delegation cho mouseover
    container.addEventListener("mouseover", (e) => {
        if (!isDragging) return;
        const slot = e.target.closest(".time-slot");
        if (!slot) return;

        const track = slot.closest(".timeline-grid");
        if (track !== currentRow) return;

        const currentIndex = parseInt(slot.dataset.slot);
        const min = Math.min(startSlot, currentIndex);
        const max = Math.max(startSlot, currentIndex);

        const slots = Array.from(currentRow.querySelectorAll(".time-slot"));

        let canSelect = true;
        for (let i = min; i <= max; i++) {
            if (
                slots[i].classList.contains("disabled") ||
                slots[i].classList.contains("booked")
            ) {
                canSelect = false;
                break;
            }
        }

        if (canSelect) {
            selectRange(currentRow, min, max);
        }
    });

    // Mouseup trên toàn màn hình
    window.addEventListener("mouseup", () => {
        if (isDragging) {
            isDragging = false;
            if (selectedSlots.length > 0) {
                openCheckoutModal();
            }
        }
    });

    function selectRange(track, min, max) {
        clearSelection();
        selectedSlots = [];
        const slots = track.querySelectorAll(".time-slot");
        for (let i = min; i <= max; i++) {
            slots[i].classList.add("selected");
            if (i === min) slots[i].classList.add("first");
            if (i === max) slots[i].classList.add("last");
            selectedSlots.push(i);
        }
    }

    function clearSelection() {
        document.querySelectorAll(".time-slot.selected").forEach((el) => {
            el.classList.remove("selected", "first", "last");
        });
        selectedSlots = [];
    }

    function formatTime(slotIndex) {
        const hour = Math.floor(slotIndex / 2);
        const min = slotIndex % 2 === 0 ? "00" : "30";
        return `${String(hour).padStart(2, "0")}:${min}`;
    }

    function updateTimelineState() {
        const selectedDate = new Date(dateInput.value);
        const now = new Date();
        const isToday =
            selectedDate.toDateString() === now.toDateString();
        const currentSlot =
            now.getHours() * 2 + (now.getMinutes() >= 30 ? 1 : 0);

        document.querySelectorAll(".time-slot").forEach((slot) => {
            slot.classList.remove("disabled");
            const slotIndex = parseInt(slot.dataset.slot);

            if (isToday && slotIndex <= currentSlot) {
                slot.classList.add("disabled");
                slot.title = "Đã qua thời gian";
            }
        });
    }

    function mockBookings() {
        // Clear existing
        document
            .querySelectorAll(".time-slot.booked")
            .forEach((el) => el.classList.remove("booked"));

        // Randomly book some slots for demo
        tracks.forEach((track) => {
            const slots = track.querySelectorAll(".time-slot");
            // 30% chance to have a booking block per room
            if (Math.random() > 0.4) {
                let start = Math.floor(Math.random() * 30) + 8; // Random start between 4:00 and 19:00
                let length = Math.floor(Math.random() * 4) + 2; // 1 to 2 hours

                for (let i = start; i < start + length && i < 48; i++) {
                    if (!slots[i].classList.contains("disabled")) {
                        slots[i].classList.add("booked");
                        slots[i].title = "Đã có người đặt";
                    }
                }
            }
        });
    }

    // Init state
    updateTimelineState();

    // Modal Logic
    window.openCheckoutModal = () => {
        if (selectedSlots.length === 0) return;

        const startSlotIdx = Math.min(...selectedSlots);
        const endSlotIdx = Math.max(...selectedSlots) + 1; // +1 to mean "up to the end of that slot"

        const durationHours = selectedSlots.length * 0.5;
        const total = currentRoomData.price * durationHours;

        document.getElementById("modal_room_img").src =
            currentRoomData.image;
        document.getElementById("modal_room_name").innerText =
            currentRoomData.name;
        document.getElementById("modal_room_price").innerText =
            currentRoomData.price.toLocaleString("vi-VN") + "đ/h";

        // Format date DD/MM/YYYY
        const dArr = dateInput.value.split("-");
        document.getElementById(
            "modal_date",
        ).innerText = `${dArr[2]}/${dArr[1]}/${dArr[0]}`;

        document.getElementById(
            "modal_time",
        ).innerText = `${formatTime(startSlotIdx)} - ${formatTime(endSlotIdx)}`;
        document.getElementById(
            "modal_duration",
        ).innerText = `${durationHours} giờ`;
        document.getElementById("modal_total").innerText =
            total.toLocaleString("vi-VN") + " đ";

        document
            .getElementById("checkout_modal")
            .classList.add("active");
    };

    window.closeModal = () => {
        document
            .getElementById("checkout_modal")
            .classList.remove("active");
        clearSelection();
    };
})();
