@extends('layouts.admin.admin_master')

@section('page-title', 'Nhân sự & Phân quyền')

@section('content')
    {{-- ============================================== --}}
    {{-- 7. CÀI ĐẶT & PHÂN QUYỀN HỆ THỐNG --}}
    {{-- ============================================== --}}
    <div id="section-settings" class="content-section">
        <div class="section-header">
            <div>
                <h1 class="page-title">Nhân Sự & Phân Quyền</h1>
                <p class="page-subtitle">Quản lý tài khoản nhân viên và phân quyền hệ thống.</p>
            </div>
            <div class="section-actions">
                <button class="btn btn-primary" id="btnAddStaff" onclick="openStaffModal('add')">
                    <i class="ph-bold ph-plus"></i> Thêm Tài Khoản Nhân Viên
                </button>
            </div>
        </div>

        {{-- Settings Sub-tab Navigation --}}
        <div class="filter-tabs" id="settingsSubTabs">
            <button class="filter-tab filter-tab--active" data-tab="settings-staff">Nhân sự & Tài khoản</button>
            <button class="filter-tab" data-tab="settings-roles">Nhóm quyền</button>
        </div>

        {{-- Tab 1: Staff Accounts --}}
        <div class="sub-section" id="settings-staff">
            <div class="card card--table">
                <table class="data-table" id="staffTable">
                    <thead>
                        <tr>
                            <th>Thông tin Nhân viên</th>
                            <th>Chức vụ / Vị trí</th>
                            <th>Thông tin liên hệ</th>
                            <th>Quyền Hệ Thống</th>
                            <th>Trạng thái tài khoản</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://ui-avatars.com/api/?name=Quang+Huy&background=60a5fa&color=fff&bold=true"
                                        class="avatar avatar--sm" alt="Avatar">
                                    <div>
                                        <b>Nguyễn Vũ Quang Huy</b><br>
                                        <span class="text-muted text-sm">ID: ST-001 (Chủ hệ thống)</span>
                                    </div>
                                </div>
                            </td>
                            <td>Quản lý Điều hành</td>
                            <td class="text-sm">huy.nguyen@workstation.vn</td>
                            <td><span class="badge badge--purple"><i class="ph-bold ph-shield-check"></i> Super Admin</span>
                            </td>
                            <td><span class="badge badge--green">Đang hoạt động</span></td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button class="btn btn-outline btn-sm btn-icon" title="Cập nhật">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa nhân sự">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://ui-avatars.com/api/?name=Ngọc+Anh&background=10b981&color=fff&bold=true"
                                        class="avatar avatar--sm" alt="Avatar">
                                    <div>
                                        <b>Phạm Ngọc Anh</b><br>
                                        <span class="text-muted text-sm">ID: ST-002</span>
                                    </div>
                                </div>
                            </td>
                            <td>Trưởng quầy Lễ tân</td>
                            <td class="text-sm">anh.pham@workstation.vn</td>
                            <td><span class="badge badge--blue"><i class="ph-bold ph-user-circle-gear"></i>
                                    Receptionist</span></td>
                            <td><span class="badge badge--green">Đang hoạt động</span></td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button class="btn btn-outline btn-sm btn-icon" title="Cập nhật">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa nhân sự">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <img src="https://ui-avatars.com/api/?name=Tuấn+Anh&background=f59e0b&color=fff&bold=true"
                                        class="avatar avatar--sm" alt="Avatar">
                                    <div>
                                        <b>Lê Tuấn Anh</b><br>
                                        <span class="text-muted text-sm">ID: ST-005</span>
                                    </div>
                                </div>
                            </td>
                            <td>Barista Chính</td>
                            <td class="text-sm">tuananh.le@workstation.vn</td>
                            <td><span class="badge badge--amber"><i class="ph-bold ph-coffee"></i> Bếp / Pha chế</span></td>
                            <td><span class="badge badge--red">Không hoạt động</span></td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button class="btn btn-outline btn-sm btn-icon" title="Cập nhật">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa nhân sự">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 2: Role Groups --}}
        <div class="sub-section sub-section--hidden" id="settings-roles">
            <div class="card">
                <div class="card__header">
                    <h2 class="card__title"><i class="ph-fill ph-shield-star"></i> Nhóm quyền hệ thống</h2>
                    <button class="btn btn-outline btn-sm" id="btnAddRole">
                        <i class="ph-bold ph-plus"></i> Tạo nhóm quyền
                    </button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tên nhóm quyền</th>
                            <th>Mô tả</th>
                            <th>Số thành viên</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Super Admin</b></td>
                            <td class="text-muted text-sm">Toàn quyền truy cập hệ thống</td>
                            <td>1 người</td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Receptionist (Lễ tân)</b></td>
                            <td class="text-muted text-sm">Quản lý Booking, Check-in/out</td>
                            <td>2 người</td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button class="btn btn-outline btn-sm btn-icon" title="Sửa quyền">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa nhóm">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Bếp / Pha chế</b></td>
                            <td class="text-muted text-sm">Xem đơn F&B, cập nhật trạng thái</td>
                            <td>1 người</td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button class="btn btn-outline btn-sm btn-icon" title="Sửa quyền">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa nhóm">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <!-- Modal Thêm/Sửa Nhân sự -->
    <div id="staffModal" class="schedule-modal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div class="modal-content"
            style="background: #ffffff; color: #111827; width: 500px; max-width: 90%; border-radius: 16px; padding: 30px; position: relative;">
            <i class="ph-bold ph-x close-modal" onclick="closeStaffModal()"
                style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>
            <h2 id="staffModalTitle"
                style="font-size: 22px; font-weight: bold; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">Thêm
                Nhân sự</h2>

            <form id="staffForm" style="margin-top: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tên nhân sự</label>
                    <input type="text" id="staffName" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Chức vụ</label>
                    <input type="text" id="staffPosition" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                    <input type="email" id="staffEmail" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phân quyền</label>
                    <select id="staffRole" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                        <option value="Super Admin">Super Admin</option>
                        <option value="Receptionist">Lễ tân</option>
                        <option value="Bếp / Pha chế">Bếp / Pha chế</option>
                    </select>
                </div>
                <button type="button" onclick="saveStaff()" class="btn btn-primary"
                    style="width: 100%; padding: 12px; border: none; border-radius: 8px; background: #3b82f6; color: white; font-weight: bold; cursor: pointer;">Lưu
                    Thông tin</button>
            </form>
        </div>
    </div>

    <script>
        let currentStaffRowEdit = null;

        function openStaffModal(mode, btn = null) {
            document.getElementById('staffModal').style.display = 'flex';
            const form = document.getElementById('staffForm');
            form.reset();

            if (mode === 'add') {
                document.getElementById('staffModalTitle').innerText = 'Thêm Nhân sự mới';
                currentStaffRowEdit = null;
            } else if (mode === 'edit' && btn) {
                document.getElementById('staffModalTitle').innerText = 'Cập nhật Nhân sự';
                currentStaffRowEdit = btn.closest('tr');

                const nameEl = currentStaffRowEdit.querySelector('.user-cell b');
                const posEl = currentStaffRowEdit.querySelectorAll('td')[1];
                const emailEl = currentStaffRowEdit.querySelectorAll('td')[2];
                const roleEl = currentStaffRowEdit.querySelectorAll('td')[3];

                document.getElementById('staffName').value = nameEl ? nameEl.innerText : '';
                document.getElementById('staffPosition').value = posEl ? posEl.innerText.trim() : '';
                document.getElementById('staffEmail').value = emailEl ? emailEl.innerText.trim() : '';

                if (roleEl) {
                    const roleText = roleEl.innerText.trim();
                    let roleVal = 'Receptionist';
                    if (roleText.includes('Super Admin')) roleVal = 'Super Admin';
                    else if (roleText.includes('Bếp')) roleVal = 'Bếp / Pha chế';
                    document.getElementById('staffRole').value = roleVal;
                }
            }
        }

        function closeStaffModal() {
            document.getElementById('staffModal').style.display = 'none';
            currentStaffRowEdit = null;
        }

        function saveStaff() {
            const name = document.getElementById('staffName').value;
            const position = document.getElementById('staffPosition').value;
            const email = document.getElementById('staffEmail').value;
            const role = document.getElementById('staffRole').value;

            if (!name || !position || !email) {
                alert('Vui lòng nhập đầy đủ thông tin!');
                return;
            }

            let badgeClass = 'badge--blue';
            let iconClass = 'ph-user-circle-gear';
            if (role === 'Super Admin') {
                badgeClass = 'badge--purple';
                iconClass = 'ph-shield-check';
            } else if (role === 'Bếp / Pha chế') {
                badgeClass = 'badge--amber';
                iconClass = 'ph-coffee';
            }

            const roleHtml = `<span class="badge ${badgeClass}"><i class="ph-bold ${iconClass}"></i> ${role}</span>`;

            if (currentStaffRowEdit) {
                currentStaffRowEdit.querySelector('.user-cell b').innerText = name;
                currentStaffRowEdit.querySelectorAll('td')[1].innerText = position;
                currentStaffRowEdit.querySelectorAll('td')[2].innerText = email;
                currentStaffRowEdit.querySelectorAll('td')[3].innerHTML = roleHtml;
            } else {
                const tbody = document.querySelector('#staffTable tbody');
                const tr = document.createElement('tr');
                tr.innerHTML = `
                                    <td>
                                        <div class="user-cell">
                                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random&color=fff&bold=true" class="avatar avatar--sm" alt="Avatar">
                                            <div>
                                                <b>${name}</b><br>
                                                <span class="text-muted text-sm">ID: ST-NEW</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${position}</td>
                                    <td class="text-sm">${email}</td>
                                    <td>${roleHtml}</td>
                                    <td><span class="badge badge--green">Hoạt động</span></td>
                                    <td class="text-center">
                                        <div class="action-group">
                                            <button class="btn btn-outline btn-sm btn-icon" title="Cập nhật">
                                                <i class="ph-bold ph-pencil-simple"></i>
                                            </button>
                                            <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa nhân sự">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                if (tbody) tbody.prepend(tr);
            }

            closeStaffModal();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('staffTable');
            if (table) {
                table.addEventListener('click', function (e) {
                    const btnEdit = e.target.closest('button[title="Cập nhật"]');
                    const btnDelete = e.target.closest('button[title="Xóa nhân sự"]');

                    if (btnEdit) {
                        openStaffModal('edit', btnEdit);
                    } else if (btnDelete) {
                        if (confirm('Bạn có chắc chắn muốn xóa nhân sự này?')) {
                            btnDelete.closest('tr').remove();
                        }
                    }
                });
            }
        });
    </script>
@endsection
