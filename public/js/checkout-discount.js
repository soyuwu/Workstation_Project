/**
 * Reusable checkout discount system for hourly and monthly booking.
 * Exposes a function `initCheckoutDiscount` to configure and bind events.
 */
function initCheckoutDiscount(config) {
    const {
        applyDiscountUrl,
        csrfToken,
        workspaceId,
        inputSelector = '#discount-code-input',
        applyBtnSelector = '#apply-discount-btn',
        messageSelector = '#discount-message',
        hiddenInputSelector = '#hidden-discount-code',
        subtotalSelector = '#summary-subtotal',
        durationDiscountSelector = null,
        voucherDiscountSelector = '#summary-discount',
        voucherDiscountRowSelector = null,
        taxSelector = '#summary-tax',
        totalSelector = '#summary-total'
    } = config;

    const discountInput = document.querySelector(inputSelector);
    const applyBtn = document.querySelector(applyBtnSelector);
    const messageDiv = document.querySelector(messageSelector);
    const hiddenDiscountInput = document.querySelector(hiddenInputSelector);

    const summarySubtotal = document.querySelector(subtotalSelector);
    const summaryDurationDiscount = durationDiscountSelector ? document.querySelector(durationDiscountSelector) : null;
    const summaryVoucherDiscount = document.querySelector(voucherDiscountSelector);
    const voucherDiscountRow = voucherDiscountRowSelector ? document.querySelector(voucherDiscountRowSelector) : null;
    const summaryTax = document.querySelector(taxSelector);
    const summaryTotal = document.querySelector(totalSelector);

    if (!discountInput || !applyBtn || !summarySubtotal) return;

    const subtotalVal = parseFloat(summarySubtotal.getAttribute('data-value') || 0);
    const durationDiscountVal = summaryDurationDiscount ? parseFloat(summaryDurationDiscount.getAttribute('data-value') || 0) : 0;
    
    // Base amount to calculate the discount from
    const baseForVoucherVal = subtotalVal - durationDiscountVal;

    let appliedDiscountCode = '';

    // Handle Enter keypress in input field
    discountInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyBtn.click();
        }
    });

    applyBtn.addEventListener('click', function () {
        const code = discountInput.value.trim();
        if (!code) {
            showFeedback('Vui lòng nhập mã giảm giá.', 'text-red-500');
            resetDiscount();
            return;
        }

        // Toggle code removal if clicked again on an already applied code
        if (appliedDiscountCode && code === appliedDiscountCode) {
            resetDiscount();
            discountInput.value = '';
            showFeedback('Đã gỡ mã giảm giá.', 'text-slate-500');
            applyBtn.textContent = 'Áp dụng';
            applyBtn.className = 'rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition';
            discountInput.removeAttribute('readonly');
            return;
        }

        applyBtn.disabled = true;
        applyBtn.textContent = 'Đang kiểm tra...';

        fetch(applyDiscountUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                code: code,
                workspace_id: workspaceId,
                subtotal: baseForVoucherVal
            })
        })
        .then(response => response.json())
        .then(data => {
            applyBtn.disabled = false;
            if (data.success) {
                appliedDiscountCode = data.code;
                hiddenDiscountInput.value = data.code;
                discountInput.setAttribute('readonly', 'true');
                
                // Toggle Button class to red cancel style
                applyBtn.textContent = 'Hủy bỏ';
                applyBtn.className = 'rounded-xl bg-red-100 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-200 transition';

                showFeedback(data.message, 'text-green-600');

                // Compute updated figures
                const discountAmount = data.discount_amount;
                const afterDiscount = baseForVoucherVal - discountAmount;
                const taxAmount = Math.round(afterDiscount * 0.08);
                const totalAmount = afterDiscount + taxAmount;

                // Update UI elements
                if (voucherDiscountRow) {
                    voucherDiscountRow.style.display = 'flex';
                }
                if (summaryVoucherDiscount) {
                    summaryVoucherDiscount.textContent = `- ${new Intl.NumberFormat('vi-VN').format(discountAmount)} VNĐ`;
                }
                if (summaryTax) {
                    summaryTax.textContent = `${new Intl.NumberFormat('vi-VN').format(taxAmount)} VNĐ`;
                }
                if (summaryTotal) {
                    summaryTotal.textContent = `${new Intl.NumberFormat('vi-VN').format(totalAmount)} VNĐ`;
                }
            } else {
                showFeedback(data.message, 'text-red-500');
                resetDiscount();
                applyBtn.textContent = 'Áp dụng';
            }
        })
        .catch(error => {
            applyBtn.disabled = false;
            applyBtn.textContent = 'Áp dụng';
            showFeedback('Đã xảy ra lỗi hệ thống. Vui lòng thử lại.', 'text-red-500');
            resetDiscount();
        });
    });

    function showFeedback(msg, className) {
        if (!messageDiv) return;
        messageDiv.textContent = msg;
        messageDiv.className = `text-xs mt-2 font-medium ${className}`;
        messageDiv.classList.remove('hidden');
    }

    function resetDiscount() {
        appliedDiscountCode = '';
        if (hiddenDiscountInput) {
            hiddenDiscountInput.value = '';
        }
        
        const taxAmount = Math.round(baseForVoucherVal * 0.08);
        const totalAmount = baseForVoucherVal + taxAmount;

        if (voucherDiscountRow) {
            voucherDiscountRow.style.display = 'none';
        }
        if (summaryVoucherDiscount) {
            summaryVoucherDiscount.textContent = `- 0 VNĐ`;
        }
        if (summaryTax) {
            summaryTax.textContent = `${new Intl.NumberFormat('vi-VN').format(taxAmount)} VNĐ`;
        }
        if (summaryTotal) {
            summaryTotal.textContent = `${new Intl.NumberFormat('vi-VN').format(totalAmount)} VNĐ`;
        }
    }
}
