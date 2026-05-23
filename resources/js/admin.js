function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') ?? '';
}

function readJsonScript(id) {
    const el = document.getElementById(id);
    if (!el) return null;
    try {
        return JSON.parse(el.textContent || 'null');
    } catch {
        return null;
    }
}

function initFilterTabs() {
    document.querySelectorAll('.filter-tabs').forEach((tabGroup) => {
        const tabs = tabGroup.querySelectorAll('.filter-tab');
        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => t.classList.remove('filter-tab--active'));
                tab.classList.add('filter-tab--active');

                const tabTarget = tab.getAttribute('data-tab');
                if (tabTarget) {
                    const parentSection = tab.closest('.page-container') || document;
                    parentSection.querySelectorAll('.sub-section').forEach((sub) => {
                        sub.classList.add('sub-section--hidden');
                    });
                    parentSection.querySelector('#' + tabTarget)?.classList.remove('sub-section--hidden');
                    return;
                }

                const filterValue = tab.getAttribute('data-filter');
                if (!filterValue) return;

                const parentCard = tab.closest('.page-container') || document;
                parentCard
                    .querySelectorAll('tbody tr[data-status], tbody tr[data-type], tbody tr[data-category]')
                    .forEach((row) => {
                        if (filterValue === 'all') {
                            row.style.display = '';
                            return;
                        }

                        const rowFilter =
                            row.getAttribute('data-status') ||
                            row.getAttribute('data-type') ||
                            row.getAttribute('data-category');
                        row.style.display = rowFilter === filterValue ? '' : 'none';
                    });
            });
        });
    });
}

function initWorkspaceManagement() {
    const modal = document.getElementById('workspaceModal');
    const form = document.getElementById('workspaceForm');
    if (!modal || !form) return;

    const csrf = getCsrfToken();

    const workspaceIdInput = document.getElementById('workspace_id');
    const modalTitle = document.getElementById('modalTitle');

    function openWorkspaceModal() {
        form.reset();
        if (workspaceIdInput) workspaceIdInput.value = '';
        if (modalTitle) modalTitle.innerText = 'Thêm Không Gian Mới';
        modal.style.display = 'flex';
    }

    function closeWorkspaceModal() {
        modal.style.display = 'none';
    }

    function fillWorkspaceForm(ws) {
        if (!ws) return;
        if (workspaceIdInput) workspaceIdInput.value = ws.id ?? '';
        document.getElementById('ws_code').value = ws.code ?? '';
        document.getElementById('ws_name').value = ws.name ?? '';
        if (ws.area_id) document.getElementById('ws_area').value = ws.area_id;
        if (ws.room_type_id) document.getElementById('ws_room_type').value = ws.room_type_id;
        document.getElementById('ws_capacity').value = ws.capacity ?? 1;
        document.getElementById('ws_price').value = ws.price_per_hour ? Math.round(ws.price_per_hour) : 0;
        document.getElementById('ws_price_month').value = ws.price_per_month ? Math.round(ws.price_per_month) : '';
        document.getElementById('ws_desc').value = ws.description ?? '';
        document.getElementById('ws_min_hours').value = ws.min_booking_hours ?? 1;
        document.getElementById('ws_status').value = ws.status ?? 'active';
        if (modalTitle) modalTitle.innerText = 'Cập Nhật: ' + (ws.name ?? '');
        modal.style.display = 'flex';
    }

    async function deleteWorkspace(id) {
        if (!id) return;
        if (
            !confirm(
                'Bạn có chắc chắn muốn xóa không gian này? Các đơn đặt phòng (nếu có) vẫn sẽ được lưu trữ an toàn.'
            )
        ) {
            return;
        }

        try {
            const response = await fetch(`/admin/workspace/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    Accept: 'application/json',
                },
            });

            const data = await response.json().catch(() => ({}));
            if (response.ok && data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra!');
            }
        } catch {
            alert('Lỗi kết nối!');
        }
    }

    document.getElementById('btnOpenWorkspaceModal')?.addEventListener('click', openWorkspaceModal);
    document.querySelectorAll('[data-action="close-workspace-modal"]').forEach((btn) => {
        btn.addEventListener('click', closeWorkspaceModal);
    });

    document.getElementById('workspacesTable')?.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[data-action="edit-workspace"]');
        if (editBtn) {
            const raw = editBtn.getAttribute('data-workspace');
            try {
                fillWorkspaceForm(JSON.parse(raw || 'null'));
            } catch {
                // ignore
            }
            return;
        }

        const deleteBtn = e.target.closest('[data-action="delete-workspace"]');
        if (deleteBtn) {
            deleteWorkspace(deleteBtn.getAttribute('data-workspace-id'));
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const id = workspaceIdInput?.value?.trim() || '';

        let url = '/admin/workspace';
        if (id) {
            url = `/admin/workspace/${id}`;
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
            });

            const data = await response.json().catch(() => ({}));
            if (response.ok && data.success) {
                location.reload();
            } else {
                alert('Lưu không thành công, vui lòng kiểm tra lại thông tin!');
                console.error(data);
            }
        } catch {
            alert('Lỗi kết nối máy chủ!');
        }
    });
}

function initVoucherManagement() {
    const modal = document.getElementById('voucherModal');
    const form = document.getElementById('voucherForm');
    const title = document.getElementById('voucherModalTitle');
    if (!modal || !form || !title) return;

    const csrf = getCsrfToken();
    let editingRow = null;

    function openCreateVoucher() {
        title.textContent = 'Thêm Voucher Mới';
        form.reset();
        document.getElementById('voucherId').value = '';
        editingRow = null;
        modal.style.display = 'flex';
    }

    function closeVoucherModal() {
        modal.style.display = 'none';
        form.reset();
        editingRow = null;
    }

    function openEditVoucher(row) {
        if (!row) return;
        editingRow = row;
        title.textContent = 'Chỉnh sửa Voucher';

        const id = row.getAttribute('data-id');
        const code = row.querySelector('.voucher-code')?.textContent?.trim() ?? '';
        const discountText = row.cells?.[1]?.textContent?.trim() ?? '';
        const discount = parseInt(discountText.replace('%', '').replace('đ', ''), 10) || '';
        const maxDiscountText = row.cells?.[2]?.textContent?.trim() ?? '';
        const maxDiscount = maxDiscountText === '-' ? '' : maxDiscountText.replace(/\D/g, '');

        const timeRaw = row.cells?.[3]?.querySelector('span')?.textContent?.trim() ?? '';
        const time = timeRaw === 'Không giới hạn' ? '' : timeRaw;

        const limitText = row.cells?.[4]?.querySelector('.text-sm')?.textContent?.trim() ?? '';
        const limitMatch = limitText.match(/\/ (\d+) lượt/);
        const limit = limitMatch ? limitMatch[1] : '';

        document.getElementById('voucherId').value = id ?? '';
        document.getElementById('voucherCode').value = code;
        document.getElementById('voucherDiscount').value = discount;
        document.getElementById('voucherMaxDiscount').value = maxDiscount;
        document.getElementById('voucherTime').value = time;
        document.getElementById('voucherLimit').value = limit;

        modal.style.display = 'flex';
    }

    async function saveVoucher() {
        const id = document.getElementById('voucherId').value;
        const code = document.getElementById('voucherCode').value;
        const discount = document.getElementById('voucherDiscount').value;
        const maxDiscount = document.getElementById('voucherMaxDiscount').value || null;
        const time = document.getElementById('voucherTime').value || null;
        const limit = document.getElementById('voucherLimit').value || null;

        if (!code || !discount) {
            alert('Vui lòng điền mã voucher và phần trăm giảm giá.');
            return;
        }

        const payload = {
            code,
            discount_value: discount,
            max_discount: maxDiscount ? maxDiscount.replace(/\D/g, '') : null,
            valid_until: time,
            usage_limit: limit,
        };

        const url = id ? `/admin/voucher/${id}` : '/admin/voucher';
        const method = id ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));
            if (response.ok && data.success) {
                alert(id ? 'Cập nhật thành công!' : 'Thêm thành công!');
                location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
                console.log(data);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Lỗi kết nối server!');
        }
    }

    async function deleteVoucher(row) {
        if (!row) return;
        if (!confirm('Bạn có chắc chắn muốn xóa voucher này không? Dữ liệu không thể khôi phục.')) return;

        const id = row.getAttribute('data-id');
        if (!id) return;

        try {
            const response = await fetch(`/admin/voucher/${id}`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
            });

            const data = await response.json().catch(() => ({}));
            if (response.ok && data.success) {
                row.remove();
                alert('Đã xóa voucher thành công!');
            } else {
                alert('Có lỗi xảy ra.');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    document.getElementById('btnCreateVoucher')?.addEventListener('click', openCreateVoucher);
    document.querySelectorAll('[data-action="close-voucher-modal"]').forEach((btn) => {
        btn.addEventListener('click', closeVoucherModal);
    });
    document.querySelectorAll('[data-action="save-voucher"]').forEach((btn) => {
        btn.addEventListener('click', saveVoucher);
    });

    document.getElementById('vouchersTable')?.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-btn');
        const deleteBtn = e.target.closest('.delete-btn');

        if (editBtn) openEditVoucher(editBtn.closest('tr'));
        if (deleteBtn) deleteVoucher(deleteBtn.closest('tr'));
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) closeVoucherModal();
    });
}

function initAccountManagement() {
    const modal = document.getElementById('accountModal');
    const form = document.getElementById('accountForm');
    const title = document.getElementById('accountModalTitle');
    const table = document.getElementById('accountsTable');
    if (!modal || !form || !title || !table) return;

    const csrf = getCsrfToken();
    let currentRowEdit = null;

    function openAccountModal(mode, btn = null) {
        modal.style.display = 'flex';
        form.reset();
        document.getElementById('accountId').value = '';

        if (mode === 'add') {
            title.innerText = 'Thêm Tài Khoản Mới';
            currentRowEdit = null;
            return;
        }

        if (mode === 'edit' && btn) {
            title.innerText = 'Cập nhật Tài Khoản';
            currentRowEdit = btn.closest('tr');

            const id = currentRowEdit.getAttribute('data-id');
            const nameEl = currentRowEdit.querySelector('.user-cell b');
            const phoneEl = currentRowEdit.querySelectorAll('td')[1]?.querySelectorAll('div.text-sm')?.[0];
            const emailEl = currentRowEdit.querySelectorAll('td')[1]?.querySelectorAll('div.text-sm')?.[1];
            const roleSpan = currentRowEdit.querySelectorAll('td')[2]?.querySelector('.badge');

            document.getElementById('accountId').value = id ?? '';
            document.getElementById('accountName').value = nameEl?.innerText ?? '';
            document.getElementById('accountPhone').value = phoneEl?.innerText?.replace('--', '')?.trim() ?? '';
            document.getElementById('accountEmail').value = emailEl?.innerText?.trim() ?? '';
            if (roleSpan) {
                const roleVal = roleSpan.getAttribute('data-role');
                document.getElementById('accountRole').value = roleVal || 'customer';
            }
        }
    }

    function closeAccountModal() {
        modal.style.display = 'none';
        currentRowEdit = null;
    }

    async function saveAccount() {
        const id = document.getElementById('accountId').value;
        const name = document.getElementById('accountName').value;
        const email = document.getElementById('accountEmail').value;
        const phone = document.getElementById('accountPhone').value;
        const role = document.getElementById('accountRole').value;

        if (!name || !email) {
            alert('Vui lòng nhập tên và email!');
            return;
        }

        const payload = { name, email, phone, role };
        const url = id ? `/admin/taikhoan/${id}` : '/admin/taikhoan';
        const method = id ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));
            if (response.ok && data.success) {
                alert(id ? 'Cập nhật tài khoản thành công!' : 'Thêm tài khoản thành công!');
                location.reload();
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể lưu thông tin.'));
            }
        } catch (error) {
            console.error(error);
            alert('Lỗi kết nối server!');
        }
    }

    async function deleteAccount(row) {
        const id = row?.getAttribute('data-id');
        if (!id) return;

        if (!confirm('Bạn có chắc chắn muốn xóa tài khoản này? Tất cả dữ liệu liên quan sẽ bị ảnh hưởng.')) return;

        try {
            const response = await fetch(`/admin/taikhoan/${id}`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
            });

            const data = await response.json().catch(() => ({}));
            if (response.ok && data.success) {
                row.remove();
                alert('Đã xóa tài khoản.');
            } else {
                alert(data.message || 'Lỗi khi xóa.');
            }
        } catch (error) {
            console.error(error);
            alert('Lỗi kết nối!');
        }
    }

    document.getElementById('btnAddAccount')?.addEventListener('click', () => openAccountModal('add'));
    document.querySelectorAll('[data-action="close-account-modal"]').forEach((btn) => {
        btn.addEventListener('click', closeAccountModal);
    });
    document.querySelectorAll('[data-action="save-account"]').forEach((btn) => {
        btn.addEventListener('click', saveAccount);
    });

    table.addEventListener('click', (e) => {
        const btnEdit = e.target.closest('.edit-btn');
        const btnDelete = e.target.closest('.delete-btn');

        if (btnEdit) openAccountModal('edit', btnEdit);
        else if (btnDelete) deleteAccount(btnDelete.closest('tr'));
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) closeAccountModal();
    });
}

function initBookingManagement() {
    const table = document.getElementById('bookingsTable');
    const modal = document.getElementById('bookingDetailsModal');
    if (!table || !modal) return;

    const csrf = getCsrfToken();

    function closeModal() {
        modal.style.display = 'none';
    }

    document.querySelectorAll('[data-action="close-booking-modal"]').forEach((btn) => {
        btn.addEventListener('click', closeModal);
    });

    table.addEventListener('click', async (e) => {
        const btnView = e.target.closest('.btn-view-details');
        const btnApprove = e.target.closest('.btn-approve');
        const btnCancel = e.target.closest('.btn-cancel');

        if (btnView) {
            const id = btnView.getAttribute('data-id');
            document.getElementById('modalBookingId').innerText = id || '';
            document.getElementById('modalCustomerName').innerText = btnView.getAttribute('data-name') || '';
            document.getElementById('modalCustomerPhone').innerText = btnView.getAttribute('data-phone') || '';
            document.getElementById('modalRoom').innerText = btnView.getAttribute('data-room') || '';
            document.getElementById('modalTime').innerText = btnView.getAttribute('data-time') || '';
            document.getElementById('modalTotalAmount').innerText = btnView.getAttribute('data-total') || '';

            document.getElementById('modalPaymentMethod').innerText = btnView.getAttribute('data-method') || '';

            const statusEl = document.getElementById('modalPaymentStatus');
            const pStatus = btnView.getAttribute('data-status') || '';
            statusEl.innerText = pStatus;
            statusEl.className = 'badge';
            if (pStatus === 'Đã xác nhận') statusEl.classList.add('badge--green');
            else if (pStatus === 'Đã hủy') statusEl.classList.add('badge--red');
            else statusEl.classList.add('badge--yellow');

            const tbody = document.getElementById('modalServicesList');
            const basePrice = btnView.getAttribute('data-base') || '0 ₫';
            const tax = btnView.getAttribute('data-tax') || '0 ₫';

            tbody.innerHTML = `
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;">Phí thuê không gian</td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #f3f4f6;">1</td>
                    <td style="padding: 10px; text-align: right; border-bottom: 1px solid #f3f4f6;">${basePrice}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #f3f4f6;">Thuế (VAT 8%)</td>
                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #f3f4f6;">1</td>
                    <td style="padding: 10px; text-align: right; border-bottom: 1px solid #f3f4f6;">${tax}</td>
                </tr>
            `;

            modal.style.display = 'flex';
            return;
        }

        if (btnApprove) {
            if (!confirm('Xác nhận thanh toán và phê duyệt đặt phòng này?')) return;
            const id = btnApprove.getAttribute('data-id');
            try {
                const response = await fetch(`/admin/booking/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({}),
                });
                const data = await response.json().catch(() => ({}));
                if (response.ok && data.success) {
                    alert('Đã phê duyệt thành công!');
                    location.reload();
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi kết nối!');
            }
            return;
        }

        if (btnCancel) {
            if (!confirm('Từ chối đơn đặt phòng này?')) return;
            const id = btnCancel.getAttribute('data-id');
            try {
                const response = await fetch(`/admin/booking/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({}),
                });
                const data = await response.json().catch(() => ({}));
                if (response.ok && data.success) {
                    alert('Đã từ chối đơn!');
                    location.reload();
                }
            } catch (err) {
                console.error(err);
                alert('Lỗi kết nối!');
            }
        }
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });
}

function initDashboardCharts() {
    const data = readJsonScript('admin-dashboard-chart-data');
    if (!data) return;
    if (typeof window.Chart === 'undefined') return;

    const revenueCtx = document.getElementById('revenueChartCanvas');
    if (revenueCtx) {
        new window.Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: data.revenueDates || [],
                datasets: [
                    {
                        label: 'Doanh thu',
                        data: data.revenueValues || [],
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND',
                                    }).format(context.parsed.y);
                                }
                                return label;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND',
                                    maximumSignificantDigits: 3,
                                }).format(value);
                            },
                        },
                    },
                },
            },
        });
    }

    const roomTypeCtx = document.getElementById('roomTypePieCanvas');
    if (roomTypeCtx) {
        new window.Chart(roomTypeCtx, {
            type: 'doughnut',
            data: {
                labels: data.roomTypeLabels || [],
                datasets: [
                    {
                        data: data.roomTypeCounts || [],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                },
            },
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initFilterTabs();
    initWorkspaceManagement();
    initVoucherManagement();
    initAccountManagement();
    initBookingManagement();
    initDashboardCharts();
});

