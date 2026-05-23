@extends('admin.admin_master')

@section('page-title', 'Quản lý Đặt chỗ')

@section('content')
      {{-- ============================================== --}}
      {{-- 2. QUẢN LÝ VẬN HÀNH & ĐẶT CHỖ (BOOKING) --}}
      {{-- ============================================== --}}
      <div id="section-booking">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Thông Tin Booking</h1>
                  </div>
            </div>

            {{-- Booking Status Filter Tabs --}}
            <div class="filter-tabs" id="bookingFilterTabs">
                  <button class="filter-tab filter-tab--active" data-filter="all">Tất cả</button>
                  <button class="filter-tab" data-filter="pending">Đã đặt</button>
                  <button class="filter-tab" data-filter="cancelled">Đã hủy</button>
            </div>

            {{-- Bookings Table --}}
            <div class="card card--table">
                  <table class="data-table" id="bookingsTable">
                        <thead>
                              <tr>
                                    <th>Mã Đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Không gian thuê</th>
                                    <th>Thời gian đặt</th>
                                    <th>Tổng tiền</th>
                                    <th class="text-center">Thao tác</th>
                              </tr>
                        </thead>
                        <tbody>
                              @forelse($bookings ?? [] as $booking)
                                    @php
                                        // Determine filter category
                                        $filterCat = 'all';
                                        if ($booking->status == 'pending') $filterCat = 'pending';
                                        elseif ($booking->status == 'cancelled') $filterCat = 'cancelled';
                                        
                                        // Determine display status
                                        $statusClass = 'badge--gray';
                                        $statusText = 'Không rõ';
                                        
                                        if ($booking->status == 'pending') {
                                            if ($booking->payment && $booking->payment->payment_method == 'bank_transfer' && $booking->payment->payment_status == 'pending' && $booking->payment->reported_at) {
                                                $statusClass = 'badge--yellow';
                                                $statusText = 'Chờ duyệt CK';
                                            } else {
                                                $statusClass = 'badge--gray';
                                                $statusText = 'Chưa thanh toán';
                                            }
                                        } elseif ($booking->status == 'confirmed') {
                                            $statusClass = 'badge--green';
                                            $statusText = 'Đã xác nhận';
                                        } elseif ($booking->status == 'cancelled') {
                                            $statusClass = 'badge--red';
                                            $statusText = 'Đã hủy';
                                        }
                                    @endphp
                                    <tr data-status="{{ $filterCat }}" data-id="{{ $booking->id }}">
                                          <td><b>{{ $booking->booking_code }}</b></td>
                                          <td>
                                                <div style="font-weight: 500;">{{ $booking->user ? $booking->user->name : 'Khách vãng lai' }}</div>
                                                <div class="text-sm text-muted">{{ $booking->user ? $booking->user->email : '--' }}</div>
                                          </td>
                                          <td>
                                                <div>{{ $booking->workspace ? $booking->workspace->name : 'Phòng / Bàn' }}</div>
                                                <div class="text-sm text-muted">{{ $booking->notes }}</div>
                                          </td>
                                          <td>
                                                <div>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</div>
                                                <div class="text-sm text-muted">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</div>
                                          </td>
                                          <td>
                                                <b>{{ number_format($booking->total_amount, 0, ',', '.') }} ₫</b>
                                          </td>
                                          <td class="text-center">
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                                
                                                <div class="action-group mt-2" style="justify-content: center;">
                                                    @if($booking->status == 'pending' && $booking->payment && $booking->payment->payment_method == 'bank_transfer' && $booking->payment->payment_status == 'pending' && $booking->payment->reported_at)
                                                        <button class="btn btn-outline btn-sm btn-icon btn-approve" title="Xác nhận" data-id="{{ $booking->id }}">
                                                            <i class="ph-bold ph-check text-success" style="color: #10b981;"></i>
                                                        </button>
                                                        <button class="btn btn-outline btn-sm btn-icon btn-cancel btn-icon--danger" title="Từ chối" data-id="{{ $booking->id }}">
                                                            <i class="ph-bold ph-x"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-outline btn-sm btn-icon btn-view-details" title="Chi tiết" 
                                                        data-id="{{ $booking->booking_code }}"
                                                        data-name="{{ $booking->user ? $booking->user->name : 'Khách vãng lai' }}"
                                                        data-phone="{{ $booking->user ? $booking->user->phone : '--' }}"
                                                        data-room="{{ $booking->workspace ? $booking->workspace->name : 'Phòng / Bàn' }}"
                                                        data-time="{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}"
                                                        data-total="{{ number_format($booking->total_amount, 0, ',', '.') }} ₫"
                                                        data-method="{{ $booking->payment ? ($booking->payment->payment_method == 'bank_transfer' ? 'Chuyển khoản' : 'MoMo') : '--' }}"
                                                        data-base="{{ number_format($booking->base_price, 0, ',', '.') }} ₫"
                                                        data-tax="{{ number_format($booking->tax, 0, ',', '.') }} ₫"
                                                        data-status="{{ $statusText }}">
                                                        <i class="ph-bold ph-eye"></i>
                                                    </button>
                                                </div>
                                          </td>
                                    </tr>
                              @empty
                                    <tr>
                                          <td colspan="6" class="text-center py-4 text-muted">Không có dữ liệu</td>
                                    </tr>
                              @endforelse
                        </tbody>
                  </table>
            </div>

      </div>
      </div>

      {{-- Modal Chi Tiết Booking --}}
      <div id="bookingDetailsModal" class="schedule-modal"
            style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
            <div class="modal-content"
                  style="background: #ffffff; color: #111827; width: 600px; max-width: 95%; border-radius: 16px; padding: 30px; position: relative; max-height: 90vh; overflow-y: auto;">
                  <i class="ph-bold ph-x close-modal" data-action="close-booking-modal"
                        style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>

                  <h2
                        style="font-size: 22px; font-weight: bold; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;">
                        Chi tiết Booking <span id="modalBookingId" style="color: #3b82f6;"></span>
                  </h2>

                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                              <h3 style="font-size: 16px; font-weight: bold; color: #4b5563; margin-bottom: 10px;">Thông tin
                                    khách
                                    hàng</h3>
                              <p style="margin-bottom: 5px; font-size: 14px;"><strong>Tên:</strong> <span
                                          id="modalCustomerName"></span></p>
                              <p style="margin-bottom: 5px; font-size: 14px;"><strong>Liên hệ:</strong> <span
                                          id="modalCustomerPhone"></span></p>
                        </div>
                        <div>
                              <h3 style="font-size: 16px; font-weight: bold; color: #4b5563; margin-bottom: 10px;">Không gian
                                    sử dụng
                              </h3>
                              <p style="margin-bottom: 5px; font-size: 14px;"><strong>Vị trí:</strong> <span
                                          id="modalRoom"></span>
                              </p>
                              <p style="margin-bottom: 5px; font-size: 14px;"><strong>Thời gian:</strong> <span
                                          id="modalTime"></span>
                              </p>
                        </div>
                  </div>

                  <h3
                        style="font-size: 16px; font-weight: bold; color: #4b5563; margin-bottom: 10px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                        Chi tiết dịch vụ và số tiền</h3>
                  <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
                        <thead style="background: #f3f4f6;">
                              <tr>
                                    <th
                                          style="padding: 10px; text-align: left; border-bottom: 1px solid #d1d5db; color: #374151;">
                                          Dịch
                                          vụ</th>
                                    <th
                                          style="padding: 10px; text-align: center; border-bottom: 1px solid #d1d5db; color: #374151;">
                                          SL
                                    </th>
                                    <th
                                          style="padding: 10px; text-align: right; border-bottom: 1px solid #d1d5db; color: #374151;">
                                          Thành tiền</th>
                              </tr>
                        </thead>
                        <tbody id="modalServicesList">
                        </tbody>
                        <tfoot>
                              <tr>
                                    <td colspan="2"
                                          style="padding: 15px 10px; text-align: right; font-weight: bold; font-size: 15px; border-top: 2px solid #e5e7eb;">
                                          Tổng cộng:</td>
                                    <td style="padding: 15px 10px; text-align: right; font-weight: bold; font-size: 16px; color: #ef4444; border-top: 2px solid #e5e7eb;"
                                          id="modalTotalAmount">0 ₫</td>
                              </tr>
                        </tfoot>
                  </table>

                  <h3 style="font-size: 16px; font-weight: bold; color: #4b5563; margin-bottom: 10px;">Thông tin Thanh toán
                  </h3>
                  <div
                        style="background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 14px;">
                        <p style="margin-bottom: 8px;"><strong>Trạng thái:</strong> <span class="badge badge--green"
                                    id="modalPaymentStatus" style="font-size: 12px;">--</span></p>
                        <p style="margin-bottom: 0;"><strong>Phương thức:</strong> <span id="modalPaymentMethod">--</span></p>
                  </div>

                  <div style="margin-top: 25px; text-align: right;">
                        <button class="btn btn-primary" type="button" data-action="close-booking-modal">Đóng</button>
                  </div>
            </div>
      </div>
@endsection
