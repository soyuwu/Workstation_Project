/**
 * Profile Dashboard and Payment Gateway Shared JS Utilities
 */

/**
 * Main dashboard tab switcher
 */
window.switchTab = function (tabId) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.replace('block', 'hidden');
    });

    // Show active tab
    const activeTab = document.getElementById(tabId);
    if (activeTab) {
        activeTab.classList.replace('hidden', 'block');
    }

    // Deactivate all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-primary-light', 'text-primary');
        btn.classList.add('text-slate-600', 'hover:bg-slate-50');
    });

    // Activate selected tab button
    const activeBtn = document.getElementById('btn-' + tabId);
    if (activeBtn) {
        activeBtn.classList.remove('text-slate-600', 'hover:bg-slate-50');
        activeBtn.classList.add('bg-primary-light', 'text-primary');
    }
}

/**
 * Filter bookings list: Upcoming, Active, Past
 */
window.filterBookings = function (filterId) {
    // Hide all booking categories
    document.querySelectorAll('.booking-filter-content').forEach(el => {
        el.classList.replace('block', 'hidden');
    });

    // Show selected category
    const activeContent = document.getElementById(filterId);
    if (activeContent) {
        activeContent.classList.replace('hidden', 'block');
    }

    // Deactivate all booking filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'shadow-sm', 'text-primary');
        btn.classList.add('text-slate-500', 'hover:text-slate-800');
    });

    // Activate selected filter button
    const activeBtn = document.getElementById('btn-' + filterId);
    if (activeBtn) {
        activeBtn.classList.remove('text-slate-500', 'hover:text-slate-800');
        activeBtn.classList.add('bg-white', 'shadow-sm', 'text-primary');
    }
}

/**
 * QR Code check-in modal controls
 */
window.openModalQR = function (bookingCode, workspaceName, bookingDate, startTime, endTime) {
    const qrCodeText = document.getElementById('qr-code-text');
    const qrWorkspaceText = document.getElementById('qr-workspace-text');
    const qrTimeText = document.getElementById('qr-time-text');
    const qrImg = document.getElementById('qr-img');
    const modal = document.getElementById('modal-qr');

    if (qrCodeText) qrCodeText.innerText = bookingCode;
    if (qrWorkspaceText) qrWorkspaceText.innerText = workspaceName;
    if (qrTimeText) qrTimeText.innerText = bookingDate + ' | ' + startTime + ' - ' + endTime;

    // Load QR dynamic image api
    if (qrImg) {
        qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(bookingCode);
    }

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

window.closeModalQR = function () {
    const modal = document.getElementById('modal-qr');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}

/**
 * Reviews modal controls
 */
window.openModalReview = function (bookingId, bookingCode, workspaceId, workspaceName) {
    const revBookingId = document.getElementById('rev-booking-id');
    const revBookingCode = document.getElementById('rev-booking-code');
    const revWorkspaceId = document.getElementById('rev-workspace-id');
    const revWorkspaceName = document.getElementById('rev-workspace-name');
    const modal = document.getElementById('modal-review');

    if (revBookingId) revBookingId.value = bookingId;
    if (revBookingCode) revBookingCode.innerText = 'Mã đặt chỗ: ' + bookingCode;
    if (revWorkspaceId) revWorkspaceId.value = workspaceId;
    if (revWorkspaceName) revWorkspaceName.innerText = 'Không gian: ' + workspaceName;

    // Default 5-star rating
    window.setRating(5);

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

window.closeModalReview = function () {
    const modal = document.getElementById('modal-review');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}

/**
 * Star selection rating helper
 */
window.setRating = function (ratingValue) {
    const revRating = document.getElementById('rev-rating');
    if (revRating) {
        revRating.value = ratingValue;
    }

    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById('star-' + i);
        if (star) {
            if (i <= ratingValue) {
                star.classList.remove('text-slate-300');
                star.classList.add('text-amber-400');
            } else {
                star.classList.remove('text-amber-400');
                star.classList.add('text-slate-300');
            }
        }
    }
}

/**
 * Unified Payment Method Switcher
 */
window.setPaymentMethod = function (method) {
    const selectedMethod = document.getElementById('selected-method');
    if (selectedMethod) {
        selectedMethod.value = method;
    }

    const btnBank = document.getElementById('btn-method-bank');
    const btnMomo = document.getElementById('btn-method-momo');
    const contentBank = document.getElementById('method-bank-content');
    const contentMomo = document.getElementById('method-momo-content');

    if (method === 'bank_transfer') {
        if (btnBank) btnBank.className = "p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2 bg-primary/5 border-primary text-primary";
        if (btnMomo) btnMomo.className = "p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2 border-slate-100 hover:border-slate-200 text-slate-500 bg-white";
        
        if (contentBank) contentBank.classList.replace('hidden', 'block');
        if (contentMomo) contentMomo.classList.replace('block', 'hidden');
    } else {
        if (btnMomo) btnMomo.className = "p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2 bg-pink-50/50 border-pink-500 text-pink-600";
        if (btnBank) btnBank.className = "p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2 border-slate-100 hover:border-slate-200 text-slate-500 bg-white";
        
        if (contentMomo) contentMomo.classList.replace('hidden', 'block');
        if (contentBank) contentBank.classList.replace('block', 'hidden');
    }
}

/**
 * Consolidated copy-to-clipboard handler for both dashboard and payment gateway
 */
window.copyToClipboard = function (text, btnElement, type = 'primary') {
    navigator.clipboard.writeText(text).then(() => {
        // Look for internal layout tags (Material Symbols and inner text)
        const labelSpan = btnElement.querySelector('span:not(.material-symbols-outlined)');
        const iconSpan = btnElement.querySelector('.material-symbols-outlined');

        if (labelSpan) {
            // Path A: Giao diện thẻ voucher có cấu trúc span riêng biệt
            const originalText = labelSpan.innerText;
            const originalIcon = iconSpan ? iconSpan.innerText : 'content_copy';

            labelSpan.innerText = 'Đã chép!';
            if (iconSpan) iconSpan.innerText = 'task_alt';

            btnElement.classList.replace('text-primary', 'text-emerald-600');
            btnElement.classList.replace('hover:bg-primary-light', 'hover:bg-emerald-50');
            btnElement.classList.add('bg-emerald-50', 'border-emerald-300');

            setTimeout(() => {
                labelSpan.innerText = originalText;
                if (iconSpan) iconSpan.innerText = originalIcon;
                btnElement.classList.replace('text-emerald-600', 'text-primary');
                btnElement.classList.replace('hover:bg-emerald-50', 'hover:bg-primary-light');
                btnElement.classList.remove('bg-emerald-50', 'border-emerald-300');
            }, 2000);
        } else {
            // Path B: Giao diện cổng thanh toán (chỉ có chữ và icon chung html)
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<span class="material-symbols-outlined text-sm font-bold">task_alt</span> Đã chép!';
            
            const normalColorClass = type === 'momo' ? 'text-pink-600' : 'text-primary';
            btnElement.classList.remove(normalColorClass);
            btnElement.classList.add('text-emerald-600');

            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
                btnElement.classList.remove('text-emerald-600');
                btnElement.classList.add(normalColorClass);
            }, 2000);
        }
    }).catch(err => {
        console.error('Lỗi sao chép: ', err);
    });
}
