const SLOT_MINUTES = 30;

function parseJsonScriptTag(id) {
    const el = document.getElementById(id);
    if (!el) return null;

    try {
        return JSON.parse(el.textContent || "null");
    } catch {
        return null;
    }
}

function toYmd(date) {
    const y = String(date.getFullYear());
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
}

function timeStringToMinutes(timeStr) {
    const parts = String(timeStr || "").split(":");
    const hours = Number(parts[0] || 0);
    const minutes = Number(parts[1] || 0);
    return hours * 60 + minutes;
}

function minutesToTimeString(totalMinutes) {
    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;
    return String(h).padStart(2, "0") + ":" + String(m).padStart(2, "0");
}

function timeStringToSlotIndex(timeStr) {
    const minutes = timeStringToMinutes(timeStr);
    return Math.max(0, Math.min(48, Math.floor(minutes / SLOT_MINUTES)));
}

function slotIndexToTimeString(slotIndex) {
    const minutes = slotIndex * SLOT_MINUTES;
    return minutesToTimeString(minutes);
}

function clearSlotClasses(slotEl) {
    slotEl.classList.remove("disabled", "booked", "selected", "first", "last");
}

function isSlotBlocked(slotEl) {
    return (
        slotEl.classList.contains("disabled") || slotEl.classList.contains("booked")
    );
}

function markRangeSelected(slotEls, startSlot, endSlot) {
    const min = Math.min(startSlot, endSlot);
    const max = Math.max(startSlot, endSlot);

    slotEls.forEach((slotEl) => {
        slotEl.classList.remove("selected", "first", "last");
    });

    for (let i = min; i <= max; i++) {
        const slotEl = slotEls[i];
        if (!slotEl || isSlotBlocked(slotEl)) return false;
    }

    for (let i = min; i <= max; i++) {
        const slotEl = slotEls[i];
        slotEl.classList.add("selected");
        if (i === min) slotEl.classList.add("first");
        if (i === max) slotEl.classList.add("last");
    }

    return true;
}

function clearSelectedForRoom(slotEls) {
    slotEls.forEach((slotEl) => {
        slotEl.classList.remove("selected", "first", "last");
    });
}

function getRoomTracks() {
    return Array.from(document.querySelectorAll("[data-room-id]"));
}

function getSlotsForRoomTrack(roomTrackEl) {
    return Array.from(roomTrackEl.querySelectorAll(".time-slot"));
}

function renderForDate(dateStr, confirmedBookings, roomTracks) {
    const todayStr = toYmd(new Date());
    const isToday = dateStr === todayStr;
    const now = new Date();
    const nowMinutes = now.getHours() * 60 + now.getMinutes();
    const nowSlot = Math.floor(nowMinutes / SLOT_MINUTES);

    roomTracks.forEach(({ roomId, slots }) => {
        slots.forEach(clearSlotClasses);

        if (isToday) {
            slots.forEach((slotEl) => {
                const slotIndex = Number(slotEl.dataset.slot);
                if (Number.isFinite(slotIndex) && slotIndex <= nowSlot) {
                    slotEl.classList.add("disabled");
                }
            });
        }

        const bookingsForRoom = Array.isArray(confirmedBookings)
            ? confirmedBookings.filter(
                  (b) => String(b.room_id) === String(roomId) && b.date === dateStr,
              )
            : [];

        bookingsForRoom.forEach((booking) => {
            const startSlot = timeStringToSlotIndex(booking.start_time);
            const endSlotExclusive = timeStringToSlotIndex(booking.end_time);
            const lastSlot = Math.max(startSlot, endSlotExclusive - 1);

            for (let i = startSlot; i <= lastSlot; i++) {
                const slotEl = slots[i];
                if (!slotEl) continue;
                slotEl.classList.add("booked");
            }
        });
    });
}

function initHourlyBooking() {
    const dateInput = document.getElementById("booking_date");
    if (!dateInput) return;

    const confirmedBookings =
        parseJsonScriptTag("booking-hourly-confirmed-bookings") || [];

    const todayStr = toYmd(new Date());
    if (!dateInput.value) dateInput.value = todayStr;

    const checkoutSection = document.querySelector("[data-checkout-url]");
    const checkoutUrl = checkoutSection?.dataset?.checkoutUrl || "/booking/checkout";

    const roomTracks = getRoomTracks()
        .map((roomTrackEl) => ({
            el: roomTrackEl,
            roomId: roomTrackEl.dataset.roomId,
            slots: getSlotsForRoomTrack(roomTrackEl),
        }))
        .filter((t) => t.roomId && t.slots.length > 0);

    const selectionByRoomId = new Map();

    const clearAllSelections = () => {
        selectionByRoomId.clear();
        roomTracks.forEach(({ slots }) => clearSelectedForRoom(slots));
    };

    const render = () => {
        clearAllSelections();
        renderForDate(dateInput.value, confirmedBookings, roomTracks);
    };

    dateInput.addEventListener("change", render);

    document.addEventListener("click", (event) => {
        const target = event.target;
        if (!(target instanceof Element)) return;

        const slotEl = target.closest(".time-slot");
        if (!slotEl) return;

        const roomTrackEl = slotEl.closest("[data-room-id]");
        if (!roomTrackEl) return;

        const roomId = roomTrackEl.getAttribute("data-room-id");
        if (!roomId) return;

        const slotIndex = Number(slotEl.getAttribute("data-slot"));
        if (!Number.isFinite(slotIndex)) return;

        if (isSlotBlocked(slotEl)) return;

        const dateStr = dateInput.value;
        if (!dateStr) return;

        const track = roomTracks.find((t) => String(t.roomId) === String(roomId));
        if (!track) return;

        const current = selectionByRoomId.get(roomId) || null;

        if (!current) {
            clearAllSelections();
            selectionByRoomId.set(roomId, { start: slotIndex, end: null });
            slotEl.classList.add("selected", "first", "last");
            return;
        }

        if (current.end === null) {
            current.end = slotIndex;
            selectionByRoomId.set(roomId, current);

            const ok = markRangeSelected(track.slots, current.start, current.end);
            if (!ok) {
                selectionByRoomId.delete(roomId);
                clearSelectedForRoom(track.slots);
                return;
            }

            const startSlot = Math.min(current.start, current.end);
            const endSlot = Math.max(current.start, current.end);

            const startTime = slotIndexToTimeString(startSlot);
            const endTime = slotIndexToTimeString(endSlot + 1);

            const params = new URLSearchParams({
                room_id: String(roomId),
                date: String(dateStr),
                start_time: startTime,
                end_time: endTime,
            });

            window.location.href = `${checkoutUrl}?${params.toString()}`;
            return;
        }

        selectionByRoomId.delete(roomId);
        clearSelectedForRoom(track.slots);
    });

    render();
}

initHourlyBooking();
