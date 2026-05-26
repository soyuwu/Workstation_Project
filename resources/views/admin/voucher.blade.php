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
                  <button class="filter-tab" data-filter="pending">Đang chờ</button>
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
                                    $now = \Carbon\Carbon::now()->startOfDay();
                                    $validFrom = $voucher->valid_from ? \Carbon\Carbon::parse($voucher->valid_from)->startOfDay() : null;
                                    $validUntil = $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->endOfDay() : null;
                                    
                                    $percent = $voucher->usage_limit > 0 ? min(100, ($voucher->usage_count / $voucher->usage_limit) * 100) : 0;
                                    
                                    $statusText = 'Đang chạy';
                                    $statusBadgeClass = 'badge--green';
                                    $rowStatus = 'active';
                                    $isActive = true;

                                    if ($voucher->status == 'disabled') {
                                        $statusText = 'Tạm dừng';
                                        $statusBadgeClass = 'badge--red';
                                        $rowStatus = 'expired';
                                        $isActive = false;
                                    } elseif ($percent >= 100) {
                                        $statusText = 'Hết lượt';
                                        $statusBadgeClass = 'badge--red';
                                        $rowStatus = 'expired';
                                        $isActive = false;
                                    } elseif ($validUntil && \Carbon\Carbon::now()->greaterThan($validUntil)) {
                                        $statusText = 'Hết hạn';
                                        $statusBadgeClass = 'badge--red';
                                        $rowStatus = 'expired';
                                        $isActive = false;
                                    } elseif ($validFrom && \Carbon\Carbon::now()->lessThan($validFrom)) {
                                        $statusText = 'Đang chờ';
                                        $statusBadgeClass = 'badge--yellow';
                                        $rowStatus = 'pending';
                                        $isActive = false;
                                    }
                              @endphp
                              <tr data-status="{{ $rowStatus }}" 
                                  data-id="{{ $voucher->id }}"
                                  data-code="{{ $voucher->code }}"
                                  data-discount="{{ $voucher->discount_value }}"
                                  data-max-discount="{{ $voucher->max_discount }}"
                                  data-valid-from="{{ $voucher->valid_from ? \Carbon\Carbon::parse($voucher->valid_from)->format('Y-m-d') : '' }}"
                                  data-valid-until="{{ $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('Y-m-d') : '' }}"
                                  data-limit="{{ $voucher->usage_limit }}">
                                    <td>
                                          <b class="voucher-code {{ !$isActive ? 'voucher-code--expired' : '' }}">{{ $voucher->code }}</b>
                                    </td>
                                    <td class="{{ !$isActive ? 'text-muted' : '' }}"><b>{{ $voucher->discount_value }}{{ $voucher->discount_type == 'percentage' ? '%' : 'đ' }}</b></td>
                                    <td class="{{ !$isActive ? 'text-muted' : '' }}">{{ $voucher->max_discount ? number_format($voucher->max_discount, 0, ',', '.') . ' ₫' : '-' }}</td>
                                    <td>
                                          @if($voucher->valid_from || $voucher->valid_until)
                                                @if($voucher->valid_from)
                                                      <span class="{{ !$isActive ? 'text-muted' : '' }} text-sm">Bắt đầu: {{ \Carbon\Carbon::parse($voucher->valid_from)->format('Y-m-d') }}</span><br>
                                                @endif
                                                @if($voucher->valid_until)
                                                      <span class="{{ !$isActive ? 'text-muted' : '' }} text-sm">Kết thúc: {{ \Carbon\Carbon::parse($voucher->valid_until)->format('Y-m-d') }}</span>
                                                @endif
                                          @else
                                                <span class="text-muted text-sm">Không giới hạn</span>
                                          @endif
                                    </td>
                                    <td>
                                          <div class="usage-bar {{ $percent >= 100 ? 'usage-bar--full' : '' }}">
                                                <div class="usage-bar__fill" style="width: {{ $percent }}%;"></div>
                                          </div>
                                          <span class="text-sm {{ $percent >= 100 ? 'text-muted' : '' }}">{{ $voucher->usage_count }} / {{ $voucher->usage_limit ?: '∞' }} lượt {{ $percent >= 100 ? '(Hết)' : '' }}</span>
                                    </td>
                                    <td>
                                          <span class="badge {{ $statusBadgeClass }}">{{ $statusText }}</span>
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
                        <button type="button" class="btn btn-icon btn-sm" data-action="close-voucher-modal">
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
                                    <label for="voucherValidFrom" class="form-label">Ngày bắt đầu</label>
                                    <input type="date" id="voucherValidFrom" class="input-field" style="width: 100%;">
                              </div>
                              <div class="form-group">
                                    <label for="voucherValidUntil" class="form-label">Ngày kết thúc</label>
                                    <input type="date" id="voucherValidUntil" class="input-field" style="width: 100%;">
                              </div>
                              <div class="form-group">
                                    <label for="voucherLimit" class="form-label">Giới hạn lượt</label>
                                    <input type="number" id="voucherLimit" class="input-field" placeholder="VD: 100"
                                          style="width: 100%;">
                              </div>
                        </form>
                  </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-action="close-voucher-modal">Hủy</button>
                        <button type="button" class="btn btn-primary" data-action="save-voucher">Lưu Voucher</button>
                  </div>
            </div>
      </div>

@endsection
