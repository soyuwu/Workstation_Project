@extends('layouts.admin.admin_master')
@section('page-title', 'Marketing & Vouchers')
@section('content')
      {{-- ============================================== --}}
      {{-- 5. QUẢN LÝ MARKETING & VOUCHER --}}
      {{-- ============================================== --}}
      <div id="section-marketing" class="content-section">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Vouchers Khuyến Mãi</h1>
                        <p class="page-subtitle">Tạo và quản lý chiến dịch voucher, mã giảm giá.</p>
                  </div>
                  <div class="section-actions">
                        <button class="btn btn-primary" id="btnCreateVoucher">
                              <i class="ph-bold ph-ticket"></i> Tạo Voucher Mới
                        </button>
                  </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="filter-tabs" id="voucherFilterTabs">
                  <button class="filter-tab filter-tab--active" data-filter="all">Tất cả</button>
                  <button class="filter-tab" data-filter="active">Đang chạy</button>
                  <button class="filter-tab" data-filter="expired">Hết hạn / Hết lượt</button>
                  <button class="filter-tab" data-filter="scheduled">Chưa bắt đầu</button>
            </div>

            {{-- Vouchers Table --}}
            <div class="card card--table">
                  <table class="data-table" id="vouchersTable">
                        <thead>
                              <tr>
                                    <th>Mã Voucher</th>
                                    <th>Giảm giá (%)</th>
                                    <th>Giảm tối đa</th>
                                    <th>Thời gian áp dụng</th>
                                    <th>Đã dùng / Giới hạn</th>
                                    <th>Trạng thái</th>
                                    <th class="th-right">Thao tác</th>
                              </tr>
                        </thead>
                        <tbody>
                              <tr data-status="active">
                                    <td>
                                          <b class="voucher-code">SUMMER20</b>
                                    </td>
                                    <td><b>20%</b></td>
                                    <td>50.000 ₫</td>
                                    <td>
                                          01/05/2026 - 30/05/2026<br>
                                          <span class="text-muted text-sm">Còn 28 ngày</span>
                                    </td>
                                    <td>
                                          <div class="usage-bar">
                                                <div class="usage-bar__fill" style="width: 15%;"></div>
                                          </div>
                                          <span class="text-sm">15 / 100 lượt</span>
                                    </td>
                                    <td><span class="badge badge--green">Đang chạy</span></td>
                                    <td class="td-right">
                                          <div class="action-group">
                                                <button class="btn btn-outline btn-sm btn-icon" title="Chỉnh sửa">
                                                      <i class="ph-bold ph-pencil-simple"></i>
                                                </button>
                                                <button class="btn btn-outline btn-sm btn-icon btn-icon--danger"
                                                      title="Tắt voucher">
                                                      <i class="ph-bold ph-power"></i>
                                                </button>
                                          </div>
                                    </td>
                              </tr>
                              <tr data-status="active">
                                    <td>
                                          <b class="voucher-code">NEWUSER</b>
                                    </td>
                                    <td><b>50%</b></td>
                                    <td>100.000 ₫</td>
                                    <td>
                                          Không giới hạn<br>
                                          <span class="text-muted text-sm">Voucher cố định</span>
                                    </td>
                                    <td>
                                          <div class="usage-bar">
                                                <div class="usage-bar__fill" style="width: 24%;"></div>
                                          </div>
                                          <span class="text-sm">120 / 500 lượt</span>
                                    </td>
                                    <td><span class="badge badge--green">Đang chạy</span></td>
                                    <td class="td-right">
                                          <div class="action-group">
                                                <button class="btn btn-outline btn-sm btn-icon" title="Chỉnh sửa">
                                                      <i class="ph-bold ph-pencil-simple"></i>
                                                </button>
                                                <button class="btn btn-outline btn-sm btn-icon btn-icon--danger"
                                                      title="Tắt voucher">
                                                      <i class="ph-bold ph-power"></i>
                                                </button>
                                          </div>
                                    </td>
                              </tr>
                              <tr data-status="expired">
                                    <td>
                                          <b class="voucher-code voucher-code--expired">FLASH10</b>
                                    </td>
                                    <td class="text-muted">10%</td>
                                    <td class="text-muted">20.000 ₫</td>
                                    <td>
                                          <span class="text-muted">01/04/2026 - 05/04/2026</span><br>
                                          <span class="text-muted text-sm">Đã kết thúc</span>
                                    </td>
                                    <td>
                                          <div class="usage-bar usage-bar--full">
                                                <div class="usage-bar__fill" style="width: 100%;"></div>
                                          </div>
                                          <span class="text-muted text-sm">50 / 50 lượt (Hết)</span>
                                    </td>
                                    <td><span class="badge badge--red">Hết hạn / Hết lượt</span></td>
                                    <td class="td-right">
                                          <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết">
                                                <i class="ph-bold ph-eye"></i>
                                          </button>
                                    </td>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </div>

      {{-- Modal Create/Edit Voucher --}}
      <style>
            .modal-overlay {
                  position: fixed;
                  top: 0;
                  left: 0;
                  right: 0;
                  bottom: 0;
                  background: rgba(15, 23, 42, 0.8);
                  backdrop-filter: blur(4px);
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  z-index: 1000;
            }

            .modal-container {
                  background: #ffffff;
                  border-radius: 8px;
                  width: 90%;
                  max-width: 500px;
                  padding: 24px;
                  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                  border: 1px solid #e5e7eb;
            }

            .modal-header {
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  margin-bottom: 20px;
                  border-bottom: 1px solid #e5e7eb;
                  padding-bottom: 12px;
            }

            .modal-title {
                  font-size: 1.1rem;
                  font-weight: 600;
                  color: #111827;
            }

            .modal-body {
                  margin-bottom: 20px;
            }

            .modal-footer {
                  display: flex;
                  justify-content: flex-end;
                  gap: 12px;
                  border-top: 1px solid #e5e7eb;
                  padding-top: 16px;
            }
      </style>
      <div class="modal-overlay" id="voucherModal" style="display: none;">
            <div class="modal-container">
                  <div class="modal-header">
                        <h3 class="modal-title" id="voucherModalTitle">Thêm Voucher Mới</h3>
                        <button type="button" class="btn btn-icon btn-sm" onclick="closeVoucherModal()">
                              <i class="ph-bold ph-x"></i>
                        </button>
                  </div>
                  <div class="modal-body">
                        <form id="voucherForm">
                              <input type="hidden" id="voucherId">
                              <div class="form-group">
                                    <label for="voucherCode" class="form-label">Mã Voucher</label>
                                    <input type="text" id="voucherCode" class="input-field" placeholder="VD: SUMMER20"
                                          required style="width: 100%;">
                              </div>
                              <div class="form-group">
                                    <label for="voucherDiscount" class="form-label">Giảm giá (%)</label>
                                    <input type="number" id="voucherDiscount" class="input-field" placeholder="VD: 20"
                                          required style="width: 100%;">
                              </div>
                              <div class="form-group">
                                    <label for="voucherMaxDiscount" class="form-label">Giảm tối đa</label>
                                    <input type="text" id="voucherMaxDiscount" class="input-field" placeholder="VD: 50.000 ₫"
                                          style="width: 100%;">
                              </div>
                              <div class="form-group">
                                    <label for="voucherTime" class="form-label">Thời gian áp dụng</label>
                                    <input type="text" id="voucherTime" class="input-field"
                                          placeholder="VD: 01/05/2026 - 30/05/2026" style="width: 100%;">
                              </div>
                              <div class="form-group">
                                    <label for="voucherLimit" class="form-label">Giới hạn lượt</label>
                                    <input type="number" id="voucherLimit" class="input-field" placeholder="VD: 100"
                                          style="width: 100%;">
                              </div>
                        </form>
                  </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeVoucherModal()">Hủy</button>
                        <button type="button" class="btn btn-primary" onclick="saveVoucher()">Lưu Voucher</button>
                  </div>
            </div>
      </div>

@endsection

@section('extra-js')
      <script>
            // ==============================================
            // VOUCHER MANAGEMENT JS FUNCTIONS
            // ==============================================

            const voucherModal = document.getElementById('voucherModal');
            const voucherForm = document.getElementById('voucherForm');
            const voucherModalTitle = document.getElementById('voucherModalTitle');
            const vouchersTableBody = document.querySelector('#vouchersTable tbody');

            let editingRow = null; // Biến lưu trữ dòng đang chỉnh sửa

            // Mở modal Thêm Voucher Mới
            document.getElementById('btnCreateVoucher').addEventListener('click', function () {
                  voucherModalTitle.textContent = 'Thêm Voucher Mới';
                  if (voucherForm) voucherForm.reset();
                  document.getElementById('voucherId').value = '';
                  editingRow = null;
                  if (voucherModal) voucherModal.style.display = 'flex';
            });

            // Đóng Modal
            function closeVoucherModal() {
                  if (voucherModal) voucherModal.style.display = 'none';
                  if (voucherForm) voucherForm.reset();
                  editingRow = null;
            }

            // Hàm Thêm mới / Cập nhật Voucher
            function saveVoucher() {
                  const id = document.getElementById('voucherId').value;
                  const code = document.getElementById('voucherCode').value;
                  const discount = document.getElementById('voucherDiscount').value;
                  const maxDiscount = document.getElementById('voucherMaxDiscount').value;
                  const time = document.getElementById('voucherTime').value || 'Không giới hạn';
                  const limit = document.getElementById('voucherLimit').value || '0';

                  if (!code || !discount) {
                        alert('Vui lòng điền mã voucher và phần trăm giảm giá.');
                        return;
                  }

                  if (editingRow) {
                        // Cập nhật dòng hiện tại
                        editingRow.querySelector('.voucher-code').textContent = code;
                        editingRow.cells[1].innerHTML = `<b>${discount}%</b>`;
                        editingRow.cells[2].textContent = maxDiscount || '-';
                        editingRow.cells[3].innerHTML = `${time}<br><span class="text-muted text-sm">Đã cập nhật</span>`;
                        editingRow.cells[4].innerHTML = `<div class="usage-bar"><div class="usage-bar__fill" style="width: 0%;"></div></div><span class="text-sm">0 / ${limit} lượt</span>`;
                        alert('Cập nhật voucher ' + code + ' thành công!');
                  } else {
                        // Thêm dòng mới
                        const tr = document.createElement('tr');
                        tr.setAttribute('data-status', 'active');

                        const trId = 'voucher-' + Date.now();
                        tr.setAttribute('id', trId);

                        tr.innerHTML = `
                                      <td><b class="voucher-code">${code}</b></td>
                                      <td><b>${discount}%</b></td>
                                      <td>${maxDiscount || '-'}</td>
                                      <td>${time}<br><span class="text-muted text-sm">Vừa tạo</span></td>
                                      <td>
                                          <div class="usage-bar">
                                              <div class="usage-bar__fill" style="width: 0%;"></div>
                                          </div>
                                          <span class="text-sm">0 / ${limit} lượt</span>
                                      </td>
                                      <td><span class="badge badge--green">Đang chạy</span></td>
                                      <td class="td-right">
                                          <div class="action-group">
                                              <button class="btn btn-outline btn-sm btn-icon edit-btn" title="Chỉnh sửa">
                                                  <i class="ph-bold ph-pencil-simple"></i>
                                              </button>
                                              <button class="btn btn-outline btn-sm btn-icon btn-icon--danger delete-btn" title="Tắt / Xóa voucher">
                                                  <i class="ph-bold ph-power"></i>
                                              </button>
                                          </div>
                                      </td>
                                  `;

                        // Gắn sự kiện cho nút Sửa / Xóa của dòng mới
                        tr.querySelector('.edit-btn').addEventListener('click', function () {
                              openEditVoucher(tr);
                        });
                        tr.querySelector('.delete-btn').addEventListener('click', function () {
                              deleteVoucher(tr);
                        });

                        vouchersTableBody.insertBefore(tr, vouchersTableBody.firstChild);
                        alert('Thêm voucher ' + code + ' thành công!');
                  }

                  closeVoucherModal();
            }

            // Mở UI Sửa từ Row
            function openEditVoucher(row) {
                  editingRow = row;
                  voucherModalTitle.textContent = 'Chỉnh sửa Voucher';

                  const code = row.querySelector('.voucher-code').textContent.trim();
                  const discountText = row.cells[1].textContent.trim();
                  const discount = parseInt(discountText.replace('%', ''));
                  const maxDiscount = row.cells[2].textContent.trim() === '-' ? '' : row.cells[2].textContent.trim();

                  const timeRaw = row.cells[3].innerHTML.split('<br>')[0].trim();
                  const time = timeRaw === 'Không giới hạn' ? '' : timeRaw;

                  const limitText = row.cells[4].querySelector('.text-sm').textContent.trim();
                  // Lấy ra số lượng giới hạn: ví dụ "15 / 100 lượt" -> "100"
                  let limitMatch = limitText.match(/\/ (\d+) lượt/);
                  const limit = limitMatch ? limitMatch[1] : '';

                  document.getElementById('voucherId').value = '1'; // Phân biệt đã có ID
                  document.getElementById('voucherCode').value = code;
                  document.getElementById('voucherDiscount').value = discount;
                  document.getElementById('voucherMaxDiscount').value = maxDiscount;
                  document.getElementById('voucherTime').value = time;
                  document.getElementById('voucherLimit').value = limit;

                  if (voucherModal) voucherModal.style.display = 'flex';
            }

            // Xóa Row
            function deleteVoucher(row) {
                  if (confirm('Bạn có chắc chắn muốn xóa voucher này không? Dữ liệu không thể khôi phục.')) {
                        row.remove();
                        alert('Đã xóa voucher thành công!');
                  }
            }

            // Gắn sự kiện cho các nút ban đầu trên giao diện
            document.addEventListener('DOMContentLoaded', function () {
                  // Nút sửa ban đầu
                  const editButtons = document.querySelectorAll('#vouchersTable tbody .action-group .btn[title="Chỉnh sửa"]');
                  editButtons.forEach(btn => {
                        btn.addEventListener('click', function () {
                              openEditVoucher(btn.closest('tr'));
                        });
                  });

                  // Nút xóa ban đầu 
                  const deleteButtons = document.querySelectorAll('#vouchersTable tbody .action-group .btn[title="Tắt voucher"]');
                  deleteButtons.forEach(btn => {
                        btn.addEventListener('click', function () {
                              deleteVoucher(btn.closest('tr'));
                        });
                  });

                  // Đóng modal khi click ra ngoài (overlay)
                  window.addEventListener('click', function (event) {
                        if (event.target === voucherModal) {
                              closeVoucherModal();
                        }
                  });
            });
      </script>
@endsection