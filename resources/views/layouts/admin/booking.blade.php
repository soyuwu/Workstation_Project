@extends('layouts.admin.admin_master')

@section('page-title', 'Quản lý Đặt chỗ')

@section('content')
      {{-- ============================================== --}}
      {{-- 2. QUẢN LÝ VẬN HÀNH & ĐẶT CHỖ (BOOKING) --}}
      {{-- ============================================== --}}
      <div id="section-booking">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Thông Tin Booking</h1>
                        <p class="page-subtitle">Duyệt đơn đặt phòng, theo dõi Check-in/Check-out và đối soát thanh toán.</p>
                  </div>
                  <div class="section-actions">
                        <div class="qr-input-group">
                              <i class="ph ph-qr-code"></i>
                              <input type="text" id="qrCodeInput" placeholder="Quét QR hoặc nhập mã booking..."
                                    class="input-field">
                        </div>
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
                              <tr data-status="pending">
                                    <td><b>#BK-1029</b></td>
                                    <td>
                                          <b>Nguyễn Trọng A</b><br>
                                          <span class="text-muted text-sm">SĐT: 0901 234 567</span>
                                    </td>
                                    <td>
                                          Phòng Họp Nhỏ (M1)<br>
                                          <span class="text-muted text-sm">09:00 - 11:00 (2 giờ)</span>
                                    </td>
                                    <td>
                                          <span class="text-muted text-sm">Ngày đặt: 02/04/2026</span><br>
                                          <span class="text-muted text-sm">Lúc: 08:30</span>
                                    </td>
                                    <td><b>300.000 ₫</b></td>
                                    <td class="text-center">
                                          <div class="action-group">
                                                <button class="btn btn-outline btn-sm btn-icon btn-view-details"
                                                      title="Xem chi tiết" data-id="#BK-1029" data-name="Nguyễn Trọng A"
                                                      data-phone="0901 234 567" data-room="Phòng Họp Nhỏ (M1)"
                                                      data-time="09:00 - 11:00 (2 giờ)" data-total="300.000 ₫"
                                                      data-method="MoMo" data-status="Thành công"
                                                      data-time-pay="Hôm nay, 08:45">
                                                      <i class="ph-bold ph-eye"></i> Chi tiết
                                                </button>
                                          </div>
                                    </td>
                              </tr>
                              <tr data-status="using">
                                    <td><b>#BK-1028</b></td>
                                    <td>
                                          <b>Lê Văn B</b><br>
                                          <span class="text-muted text-sm">Email: levb@gmail.com</span>
                                    </td>
                                    <td>
                                          Bàn chia sẻ (C-10)<br>
                                          <span class="text-muted text-sm">Cả ngày</span>
                                    </td>
                                    <td>
                                          <span class="text-sm">Ngày đặt: 01/04/2026</span><br>
                                          <span class="text-muted text-sm">Lúc: 15:20</span>
                                    </td>
                                    <td><b>150.000 ₫</b></td>
                                    <td class="text-center">
                                          <div class="action-group">
                                                <button class="btn btn-outline btn-sm btn-icon btn-view-details"
                                                      title="Xem chi tiết" data-id="#BK-1028" data-name="Lê Văn B"
                                                      data-phone="levb@gmail.com" data-room="Bàn chia sẻ (C-10)"
                                                      data-time="Cả ngày" data-total="150.000 ₫" data-method="Chưa thanh toán"
                                                      data-status="Chờ xử lý" data-time-pay="--">
                                                      <i class="ph-bold ph-eye"></i> Chi tiết
                                                </button>
                                          </div>
                                    </td>
                              </tr>
                              <tr data-status="completed">
                                    <td class="text-muted"><b>#BK-1020</b></td>
                                    <td>
                                          <span class="text-muted">Trần Thị C</span><br>
                                          <span class="text-muted text-sm">SĐT: 0912 345 678</span>
                                    </td>
                                    <td>
                                          <span class="text-muted">Pod Cá nhân (P-01)</span><br>
                                          <span class="text-muted text-sm">14:00 - 18:00 (4 giờ)</span>
                                    </td>
                                    <td>
                                          <span class="text-muted text-sm">Ngày đặt: 01/04/2026</span><br>
                                          <span class="text-muted text-sm">Lúc: 09:15</span>
                                    </td>
                                    <td class="text-muted">200.000 ₫</td>
                                    <td class="text-center">
                                          <button class="btn btn-outline btn-sm btn-icon btn-view-details"
                                                title="Xem chi tiết" data-id="#BK-1020" data-name="Trần Thị C"
                                                data-phone="0912 345 678" data-room="Pod Cá nhân (P-01)"
                                                data-time="14:00 - 18:00 (4 giờ)" data-total="200.000 ₫"
                                                data-method="Thẻ tín dụng" data-status="Thành công"
                                                data-time-pay="Hôm qua, 09:20">
                                                <i class="ph-bold ph-eye"></i> Chi tiết
                                          </button>
                                    </td>
                              </tr>
                              <tr data-status="cancelled">
                                    <td class="text-muted"><b>#BK-1015</b></td>
                                    <td>
                                          <span class="text-muted">Phạm Duy D</span><br>
                                          <span class="text-muted text-sm">SĐT: 0988 111 222</span>
                                    </td>
                                    <td>
                                          <span class="text-muted">Meeting Room M2</span><br>
                                          <span class="text-muted text-sm">10:00 - 12:00</span>
                                    </td>
                                    <td>
                                          <span class="text-muted text-sm">Ngày đặt: 30/03/2026</span><br>
                                          <span class="text-muted text-sm">Lúc: 11:45</span>
                                    </td>
                                    <td class="text-muted">500.000 ₫</td>
                                    <td class="text-center">
                                          <button class="btn btn-outline btn-sm btn-icon btn-view-details"
                                                title="Xem chi tiết" data-id="#BK-1015" data-name="Phạm Duy D"
                                                data-phone="0988 111 222" data-room="Meeting Room M2"
                                                data-time="10:00 - 12:00" data-total="500.000 ₫" data-method="Tiền mặt"
                                                data-status="Đã hoàn" data-time-pay="Hôm nay, 10:00">
                                                <i class="ph-bold ph-eye"></i> Chi tiết
                                          </button>
                                    </td>
                              </tr>
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
                  <i class="ph-bold ph-x close-modal"
                        onclick="document.getElementById('bookingDetailsModal').style.display='none'"
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
                                    id="modalPaymentStatus" style="font-size: 12px;">Đã thanh toán</span></p>
                        <p style="margin-bottom: 8px;"><strong>Phương thức:</strong> <span id="modalPaymentMethod">Chuyển
                                    khoản /
                                    MoMo</span></p>
                        <p style="margin-bottom: 0;"><strong>Thời gian giao dịch:</strong> <span
                                    id="modalPaymentTime">02/04/2026,
                                    08:45</span></p>
                  </div>

                  <div style="margin-top: 25px; text-align: right;">
                        <button class="btn btn-primary"
                              onclick="document.getElementById('bookingDetailsModal').style.display='none'">Đóng</button>
                  </div>
            </div>
      </div>

      <script>
            document.addEventListener('DOMContentLoaded', function () {
                  const mockServices = {
                        '#BK-1029': [
                              { name: 'Phòng Họp Nhỏ (M1) - 2 giờ', qty: 1, price: '200.000 ₫' },
                              { name: 'Cà phê đen đá', qty: 2, price: '60.000 ₫' },
                              { name: 'Trà Lipton', qty: 1, price: '40.000 ₫' }
                        ],
                        '#BK-1028': [
                              { name: 'Bàn chia sẻ (C-10) - Cả ngày', qty: 1, price: '100.000 ₫' },
                              { name: 'Mì Ý Spaghetti', qty: 1, price: '50.000 ₫' }
                        ],
                        '#BK-1020': [
                              { name: 'Pod Cá nhân (P-01) - 4 giờ', qty: 1, price: '150.000 ₫' },
                              { name: 'Nước suối', qty: 2, price: '20.000 ₫' },
                              { name: 'Snack khoai tây', qty: 1, price: '30.000 ₫' }
                        ],
                        '#BK-1015': [
                              { name: 'Meeting Room M2 - 2 giờ', qty: 1, price: '500.000 ₫' }
                        ]
                  };

                  const table = document.getElementById('bookingsTable');
                  if (table) {
                        table.addEventListener('click', function (e) {
                              const btn = e.target.closest('.btn-view-details');
                              if (btn) {
                                    const id = btn.getAttribute('data-id');
                                    document.getElementById('modalBookingId').innerText = id;
                                    document.getElementById('modalCustomerName').innerText = btn.getAttribute('data-name') || '';
                                    document.getElementById('modalCustomerPhone').innerText = btn.getAttribute('data-phone') || '';
                                    document.getElementById('modalRoom').innerText = btn.getAttribute('data-room') || '';
                                    document.getElementById('modalTime').innerText = btn.getAttribute('data-time') || '';
                                    document.getElementById('modalTotalAmount').innerText = btn.getAttribute('data-total') || '';

                                    document.getElementById('modalPaymentMethod').innerText = btn.getAttribute('data-method') || '';
                                    document.getElementById('modalPaymentTime').innerText = btn.getAttribute('data-time-pay') || '';

                                    const statusEl = document.getElementById('modalPaymentStatus');
                                    const pStatus = btn.getAttribute('data-status') || '';
                                    statusEl.innerText = pStatus;
                                    statusEl.className = 'badge';
                                    if (pStatus === 'Thành công') statusEl.classList.add('badge--green');
                                    else if (pStatus === 'Đã hoàn') statusEl.classList.add('badge--red');
                                    else statusEl.classList.add('badge--yellow');

                                    // Render services
                                    const tbody = document.getElementById('modalServicesList');
                                    tbody.innerHTML = '';
                                    const services = mockServices[id] || [];
                                    services.forEach(item => {
                                          const tr = document.createElement('tr');
                                          tr.innerHTML = '<td style="padding: 10px; border-bottom: 1px solid #f3f4f6;">' + item.name + '</td>' +
                                                '<td style="padding: 10px; text-align: center; border-bottom: 1px solid #f3f4f6;">' + item.qty + '</td>' +
                                                '<td style="padding: 10px; text-align: right; border-bottom: 1px solid #f3f4f6;">' + item.price + '</td>';
                                          tbody.appendChild(tr);
                                    });

                                    document.getElementById('bookingDetailsModal').style.display = 'flex';
                              }
                        });
                  }
            });
      </script>
@endsection
