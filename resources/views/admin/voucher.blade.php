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
                        <button type="button" class="btn btn-outline" data-action="close-voucher-modal">Hủy</button>
                        <button type="button" class="btn btn-primary" data-action="save-voucher">Lưu Voucher</button>
                  </div>
            </div>
      </div>

@endsection
