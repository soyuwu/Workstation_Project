@extends('admin.admin_master')

@section('page-title', 'Quản lý Tài Khoản')

@section('content')
    <div id="section-accounts" class="content-section">
        <div class="section-header">
            <div>
                <h1 class="page-title">Quản Lý Tài Khoản</h1>
            </div>
            <div class="section-actions">
                <button class="btn btn-primary" id="btnAddAccount" onclick="openAccountModal('add')">
                    <i class="ph-bold ph-plus"></i> Thêm Tài Khoản Mới
                </button>
            </div>
        </div>

        <div class="filter-tabs" id="accountFilterTabs">
            <button class="filter-tab filter-tab--active" data-filter="all">Tất cả</button>
            <button class="filter-tab" data-filter="admin">Admin / Staff</button>
            <button class="filter-tab" data-filter="customer">Khách hàng</button>
        </div>

        <div class="card card--table">
            <table class="data-table" id="accountsTable">
                <thead>
                    <tr>
                        <th>Thông tin Tài khoản</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th>Hoạt động Booking</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $account)
                    @php
                        $roleFilter = in_array($account->role, ['admin', 'staff']) ? 'admin' : 'customer';
                    @endphp
                    <tr data-id="{{ $account->id }}" data-category="{{ $roleFilter }}">
                        <td>
                            <div class="user-cell">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($account->name) }}&background=random&color=fff"
                                    class="avatar avatar--sm" alt="Avatar">
                                <div>
                                    <b>{{ $account->name }}</b><br>
                                    <span class="text-muted text-sm">ID: {{ $roleFilter == 'admin' ? 'ST' : 'KH' }}-{{ sprintf('%05d', $account->id) }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm"><i class="ph-fill ph-phone text-muted"></i> {{ $account->phone ?: '--' }}</div>
                            <div class="text-sm"><i class="ph-fill ph-envelope-simple text-muted"></i> {{ $account->email }}</div>
                        </td>
                        <td>
                            @if($account->role == 'admin')
                                <span class="badge badge--purple" data-role="admin"><i class="ph-bold ph-shield-check"></i> Super Admin</span>
                            @elseif($account->role == 'staff')
                                <span class="badge badge--blue" data-role="staff"><i class="ph-bold ph-user-circle-gear"></i> Staff</span>
                            @else
                                <span class="badge badge--gray" data-role="customer"><i class="ph-bold ph-user"></i> Khách hàng</span>
                            @endif
                        </td>
                        <td>
                            @if($account->role == 'customer')
                                <b>{{ number_format($account->bookings_sum_total_amount ?? 0, 0, ',', '.') }} ₫</b><br>
                                <span class="text-muted text-sm">{{ $account->bookings_count ?? 0 }} lần booking</span>
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </td>
                        <td>
                            @if($account->status == 'active')
                                <span class="badge badge--green">Hoạt động</span>
                            @else
                                <span class="badge badge--red">Vô hiệu hóa</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="action-group">
                                <button class="btn btn-outline btn-sm btn-icon edit-btn" title="Cập nhật">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </button>
                                <button class="btn btn-outline btn-sm btn-icon btn-icon--danger delete-btn" title="Xóa">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Thêm/Sửa Tài khoản -->
    <div id="accountModal" class="schedule-modal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div class="modal-content"
            style="background: #ffffff; color: #111827; width: 500px; max-width: 90%; border-radius: 16px; padding: 30px; position: relative;">
            <i class="ph-bold ph-x close-modal" onclick="closeAccountModal()"
                style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>
            <h2 id="accountModalTitle"
                style="font-size: 22px; font-weight: bold; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">Thêm Tài Khoản</h2>

            <form id="accountForm" style="margin-top: 20px;">
                <input type="hidden" id="accountId">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tên người dùng</label>
                    <input type="text" id="accountName" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                    <input type="email" id="accountEmail" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Số điện thoại</label>
                    <input type="text" id="accountPhone"
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phân quyền</label>
                    <select id="accountRole" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="customer">Khách hàng</option>
                        <option value="staff">Nhân viên (Staff)</option>
                        <option value="admin">Quản trị viên (Admin)</option>
                    </select>
                </div>
                <button type="button" onclick="saveAccount()" class="btn btn-primary"
                    style="width: 100%; padding: 12px; border: none; border-radius: 8px; background: #3b82f6; color: white; font-weight: bold; cursor: pointer;">Lưu Thông tin</button>
            </form>
        </div>
    </div>

    <script>
        let currentRowEdit = null;

        function openAccountModal(mode, btn = null) {
            document.getElementById('accountModal').style.display = 'flex';
            const form = document.getElementById('accountForm');
            form.reset();
            document.getElementById('accountId').value = '';

            if (mode === 'add') {
                document.getElementById('accountModalTitle').innerText = 'Thêm Tài Khoản Mới';
                currentRowEdit = null;
            } else if (mode === 'edit' && btn) {
                document.getElementById('accountModalTitle').innerText = 'Cập nhật Tài Khoản';
                currentRowEdit = btn.closest('tr');

                const id = currentRowEdit.getAttribute('data-id');
                const nameEl = currentRowEdit.querySelector('.user-cell b');
                const phoneEl = currentRowEdit.querySelectorAll('td')[1].querySelectorAll('div.text-sm')[0];
                const emailEl = currentRowEdit.querySelectorAll('td')[1].querySelectorAll('div.text-sm')[1];
                const roleSpan = currentRowEdit.querySelectorAll('td')[2].querySelector('.badge');

                document.getElementById('accountId').value = id;
                document.getElementById('accountName').value = nameEl ? nameEl.innerText : '';
                document.getElementById('accountPhone').value = phoneEl ? phoneEl.innerText.replace('--', '').trim() : '';
                document.getElementById('accountEmail').value = emailEl ? emailEl.innerText.trim() : '';

                if (roleSpan) {
                    const roleVal = roleSpan.getAttribute('data-role');
                    document.getElementById('accountRole').value = roleVal || 'customer';
                }
            }
        }

        function closeAccountModal() {
            document.getElementById('accountModal').style.display = 'none';
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

            const payload = {
                name: name,
                email: email,
                phone: phone,
                role: role,
                _token: '{{ csrf_token() }}'
            };

            let url = '{{ route('admin.taikhoan.store') }}';
            if (id) {
                url = `{{ url('admin/taikhoan') }}/${id}`;
                payload._method = 'PUT';
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (data.success) {
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
            const id = row.getAttribute('data-id');
            if (confirm('Bạn có chắc chắn muốn xóa tài khoản này? Tất cả dữ liệu liên quan sẽ bị ảnh hưởng.')) {
                try {
                    const response = await fetch(`{{ url('admin/taikhoan') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
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
        }

        document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('accountsTable');
            if (table) {
                table.addEventListener('click', function (e) {
                    const btnEdit = e.target.closest('.edit-btn');
                    const btnDelete = e.target.closest('.delete-btn');

                    if (btnEdit) {
                        openAccountModal('edit', btnEdit);
                    } else if (btnDelete) {
                        deleteAccount(btnDelete.closest('tr'));
                    }
                });
            }
        });
    </script>
@endsection
