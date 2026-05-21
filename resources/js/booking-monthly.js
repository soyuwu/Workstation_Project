/**
 * Booking Monthly - Room selection logic
 */
function selectRoom(event, name, price) {
    const input = document.getElementById("selected_room");
    input.value = name + " (" + price + ")";

    // Xóa style active cũ
    document.querySelectorAll("article").forEach((el) => {
        el.classList.remove("ring-2", "ring-primary", "border-primary");
    });

    // Thêm style active cho thẻ hiện tại
    event.currentTarget.classList.add(
        "ring-2",
        "ring-primary",
        "border-primary",
    );

    // Cuộn xuống form
    input.scrollIntoView({ behavior: "smooth", block: "center" });
}
