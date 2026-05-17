@extends('admin.admin_master')
@section('page-title', 'Marketing & Vouchers')
@section('content')
      {{-- ============================================== --}}
      {{-- 5. QUẢN LÝ MARKETING & VOUCHER --}}
      {{-- ============================================== --}}
      <div id="section-marketing" class="content-section">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Vouchers Khuyến Mãi</h1>
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
                                    <th class="text-center">Thao tác</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($vouchers as $voucher)
                              @php 
                                    $percent = $voucher->usage_limit > 0 ? min(100, ($voucher->usage_count / $voucher->usage_limit) * 100) : 0;
                                    $daysLeft = null;
                                    if ($voucher->valid_until) {
                                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($voucher->valid_until), false);
                                    }
                                    $isActive = $voucher->status == 'active' && $percent < 100 && ($daysLeft === null || $daysLeft >= 0);
                              @endphp
                              <tr data-status="{{ $isActive ? 'active' : 'expired' }}" data-id="{{ $voucher->id }}">
                                    <td>
                                          <b class="voucher-code {{ !$isActive ? 'voucher-code--expired' : '' }}">{{ $voucher->code }}</b>
                                    </td>
                                    <td class="{{ !$isActive ? 'text-muted' : '' }}"><b>{{ $voucher->discount_value }}{{ $voucher->discount_type == 'percentage' ? '%' : 'đ' }}</b></td>
                                    <td class="{{ !$isActive ? 'text-muted' : '' }}">{{ $voucher->max_discount ? number_format($voucher->max_discount, 0, ',', '.') . ' ₫' : '-' }}</td>
                                    <td>
                                          <span class="{{ !$isActive ? 'text-muted' : '' }}">{{ $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('Y-m-d') : 'Không giới hạn' }}</span><br>
                                          @if($voucher->valid_until)
                                                @if($daysLeft >= 0)
                                                      <span class="text-muted text-sm">Còn {{ (int)$daysLeft }} ngày</span>
                                                @else
                                                      <span class="text-muted text-sm">Đã kết thúc</span>
                                                @endif
                                          @else
                                                <span class="text-muted text-sm">Voucher cố định</span>
                                          @endif
                                    </td>
                                    <td>
                                          <div class="usage-bar {{ $percent >= 100 ? 'usage-bar--full' : '' }}">
                                                <div class="usage-bar__fill" style="width: {{ $percent }}%;"></div>
                                          </div>
                                          <span class="text-sm {{ $percent >= 100 ? 'text-muted' : '' }}">{{ $voucher->usage_count }} / {{ $voucher->usage_limit ?: '∞' }} lượt {{ $percent >= 100 ? '(Hết)' : '' }}</span>
                                    </td>
                                    <td>
                                          @if($isActive)
                                                <span class="badge badge--green">Đang chạy</span>
                                          @else
                                                <span class="badge badge--red">Hết hạn / Hết lượt</span>
                                          @endif
                                    </td>
                                    <td class="text-center">
                                          <div class="action-group">
                                                <button class="btn btn-outline btn-sm btn-icon edit-btn" title="Chỉnh sửa">
                                                      <i class="ph-bold ph-pencil-simple"></i>
                                                </button>
                                                <button class="btn btn-outline btn-sm btn-icon btn-icon--danger delete-btn"
                                                      title="Tắt / Xóa voucher">
                                                      <i class="ph-bold ph-power"></i>
                                                </button>
                                          </div>
                                    </td>
                              </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </div>

      {{-- Modal Create/Edit Voucher --}}
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
                                    <label for="voucherTime" class="form-label">Hạn áp dụng (YYYY-MM-DD)</label>
                                    <input type="date" id="voucherTime" class="input-field" style="width: 100%;">
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
                      code: code,
                      discount_value: discount,
                      max_discount: maxDiscount ? maxDiscount.replace(/\D/g, '') : null,
                      valid_until: time,
                      usage_limit: limit,
                      _token: '{{ csrf_token() }}'
                  };

                  let url = '{{ route('admin.voucher.store') }}';
                  let method = 'POST';

                  if (id) {
                      url = `{{ url('admin/voucher') }}/${id}`;
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

            // Mở UI Sửa từ Row
            function openEditVoucher(row) {
                  editingRow = row;
                  voucherModalTitle.textContent = 'Chỉnh sửa Voucher';

                  const id = row.getAttribute('data-id');
                  const code = row.querySelector('.voucher-code').textContent.trim();
                  const discountText = row.cells[1].textContent.trim();
                  const discount = parseInt(discountText.replace('%', '').replace('đ', ''));
                  const maxDiscountText = row.cells[2].textContent.trim();
                  const maxDiscount = maxDiscountText === '-' ? '' : maxDiscountText.replace(/\D/g, '');

                  const timeRaw = row.cells[3].querySelector('span').textContent.trim();
                  const time = timeRaw === 'Không giới hạn' ? '' : timeRaw;

                  const limitText = row.cells[4].querySelector('.text-sm').textContent.trim();
                  let limitMatch = limitText.match(/\/ (\d+) lượt/);
                  const limit = limitMatch ? limitMatch[1] : '';

                  document.getElementById('voucherId').value = id;
                  document.getElementById('voucherCode').value = code;
                  document.getElementById('voucherDiscount').value = discount;
                  document.getElementById('voucherMaxDiscount').value = maxDiscount;
                  document.getElementById('voucherTime').value = time;
                  document.getElementById('voucherLimit').value = limit;

                  if (voucherModal) voucherModal.style.display = 'flex';
            }

            // Xóa Row
            async function deleteVoucher(row) {
                  if (confirm('Bạn có chắc chắn muốn xóa voucher này không? Dữ liệu không thể khôi phục.')) {
                        const id = row.getAttribute('data-id');
                        try {
                              const response = await fetch(`{{ url('admin/voucher') }}/${id}`, {
                                    method: 'POST',
                                    headers: {
                                          'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                          _method: 'DELETE',
                                          _token: '{{ csrf_token() }}'
                                    })
                              });
                              const data = await response.json();
                              if (data.success) {
                                    row.remove();
                                    alert('Đã xóa voucher thành công!');
                              }
                        } catch (error) {
                              console.error('Error:', error);
                        }
                  }
            }

            // Gắn sự kiện cho các nút ban đầu trên giao diện
            document.addEventListener('DOMContentLoaded', function () {
                  // Nút sửa ban đầu
                  const editButtons = document.querySelectorAll('#vouchersTable tbody .action-group .edit-btn');
                  editButtons.forEach(btn => {
                        btn.addEventListener('click', function () {
                              openEditVoucher(btn.closest('tr'));
                        });
                  });

                  // Nút xóa ban đầu 
                  const deleteButtons = document.querySelectorAll('#vouchersTable tbody .action-group .delete-btn');
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