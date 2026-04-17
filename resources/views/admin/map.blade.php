@extends('admin.admin_master')

@section('page-title', 'Không gian & Cơ sở')

@section('content')
    <div id="section-facility" class="content-section">
        <div class="section-header">
            <div>
                <h1 class="page-title">Quản lý Chỗ Ngồi & Không Gian</h1>
                <p class="page-subtitle">Kiểm soát trạng thái đặt chỗ, không gian đang sử dụng và chỗ trống dạng danh sách.
                </p>
            </div>
            <div class="section-actions">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="bookingDatePicker" style="font-weight: bold; font-size: 14px;">Chọn Ngày:</label>
                    <input type="date" id="bookingDatePicker"
                        style="padding: 8px 12px; border-radius: 6px; border: 1px solid #d1d5db; background: #fff; cursor: pointer; font-family: inherit;">
                </div>
            </div>
        </div>

        {{-- Lọc trạng thái --}}
        <div class="filter-tabs" id="statusSubTabs">
            <button class="filter-tab filter-tab--active" data-status="all">Tất cả thông tin</button>
            <button class="filter-tab" data-status="in-use">Đang sử dụng</button>
            <button class="filter-tab" data-status="booked">Đã đặt trước</button>
            <button class="filter-tab" data-status="available">Chỗ trống (Chưa đặt)</button>
        </div>

        <div class="sub-section">
            <div class="card card--table">
                <table class="data-table" id="facilityTable">
                    <thead>
                        <tr>
                            <th>Tên Không gian / Chỗ ngồi</th>
                            <th>Loại chỗ & Sức chứa</th>
                            <th>Trạng thái</th>
                            <th>Thông tin Khách hàng (Nếu có)</th>
                            <th>Khung giờ</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1: IN USE -->
                        <tr class="status-row" data-status="in-use">
                            <td>
                                <b>Phòng Hội Thảo (Grand Hall)</b><br>
                                <span class="text-muted text-sm">Mã: HT-01</span>
                            </td>
                            <td>
                                <span class="badge badge--blue"><i class="ph-bold ph-presentation-chart"></i> Hội
                                    Thảo</span><br>
                                <span class="text-muted text-sm">10-15 người</span>
                            </td>
                            <td><span class="badge badge--purple">Đang sử dụng</span></td>
                            <td>
                                <b>Công ty Tech Corp</b><br>
                                <span class="text-muted text-sm">SĐT: 0901234567 | #BK-1050</span>
                            </td>
                            <td><span style="font-weight: 500;">08:00 - 12:00</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Phòng Hội Thảo (Grand Hall)')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 2: BOOKED -->
                        <tr class="status-row" data-status="booked">
                            <td>
                                <b>Phòng Họp M1</b><br>
                                <span class="text-muted text-sm">Mã: RM-M1</span>
                            </td>
                            <td>
                                <span class="badge badge--amber"><i class="ph-bold ph-users-three"></i> Phòng Họp</span><br>
                                <span class="text-muted text-sm">6-9 người</span>
                            </td>
                            <td><span class="badge badge--amber">Đã đặt</span></td>
                            <td>
                                <b>Nguyễn Minh Tâm</b><br>
                                <span class="text-muted text-sm">SĐT: 0988000111 | #BK-1058</span>
                            </td>
                            <td><span style="font-weight: 500;">14:00 - 16:00</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Phòng Họp M1')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 3: AVAILABLE -->
                        <tr class="status-row" data-status="available">
                            <td>
                                <b>Phòng Họp M2</b><br>
                                <span class="text-muted text-sm">Mã: RM-M2</span>
                            </td>
                            <td>
                                <span class="badge badge--amber"><i class="ph-bold ph-users-three"></i> Phòng Họp</span><br>
                                <span class="text-muted text-sm">6-9 người</span>
                            </td>
                            <td><span class="badge badge--green">Chưa đặt</span></td>
                            <td>
                                <span class="text-muted text-sm">--</span>
                            </td>
                            <td><span class="text-muted text-sm">--</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Phòng Họp M2')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 4: IN USE GROUP -->
                        <tr class="status-row" data-status="in-use">
                            <td>
                                <b>Bàn Nhóm G1</b><br>
                                <span class="text-muted text-sm">Mã: GR-G1</span>
                            </td>
                            <td>
                                <span class="badge badge--blue"><i class="ph-bold ph-users"></i> Bàn Nhóm</span><br>
                                <span class="text-muted text-sm">3-5 người</span>
                            </td>
                            <td><span class="badge badge--purple">Đang sử dụng</span></td>
                            <td>
                                <b>Nhóm Đồ Án K12</b><br>
                                <span class="text-muted text-sm">SĐT: 0911222333 | #BK-1062</span>
                            </td>
                            <td><span style="font-weight: 500;">09:00 - 15:00</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Bàn Nhóm G1')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 5: BOOKED INDIVIDUAL -->
                        <tr class="status-row" data-status="booked">
                            <td>
                                <b>Bàn Cá Nhân T01</b><br>
                                <span class="text-muted text-sm">Mã: IND-T01</span>
                            </td>
                            <td>
                                <span class="badge" style="background:#e5e7eb; color:#374151;"><i
                                        class="ph-bold ph-user"></i> Bàn Đơn</span><br>
                                <span class="text-muted text-sm">1-2 người</span>
                            </td>
                            <td><span class="badge badge--amber">Đã đặt</span></td>
                            <td>
                                <b>Lê Hải Yến</b><br>
                                <span class="text-muted text-sm">SĐT: 0909123456 | #BK-1070</span>
                            </td>
                            <td><span style="font-weight: 500;">18:00 - 22:00</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Bàn Cá Nhân T01')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 6: AVAILABLE INDIVIDUAL -->
                        <tr class="status-row" data-status="available">
                            <td>
                                <b>Bàn Cá Nhân T02</b><br>
                                <span class="text-muted text-sm">Mã: IND-T02</span>
                            </td>
                            <td>
                                <span class="badge" style="background:#e5e7eb; color:#374151;"><i
                                        class="ph-bold ph-user"></i> Bàn Đơn</span><br>
                                <span class="text-muted text-sm">1-2 người</span>
                            </td>
                            <td><span class="badge badge--green">Chưa đặt</span></td>
                            <td>
                                <span class="text-muted text-sm">--</span>
                            </td>
                            <td><span class="text-muted text-sm">--</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Bàn Cá Nhân T02')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Row 7: BOOKED GROUP -->
                        <tr class="status-row" data-status="booked">
                            <td>
                                <b>Bàn Nhóm G2</b><br>
                                <span class="text-muted text-sm">Mã: GR-G2</span>
                            </td>
                            <td>
                                <span class="badge badge--blue"><i class="ph-bold ph-users"></i> Bàn Nhóm</span><br>
                                <span class="text-muted text-sm">3-5 người</span>
                            </td>
                            <td><span class="badge badge--amber">Đã đặt</span></td>
                            <td>
                                <b>Trần Văn Nam</b><br>
                                <span class="text-muted text-sm">SĐT: 0933444555 | #BK-1088</span>
                            </td>
                            <td><span style="font-weight: 500;">16:00 - 20:00</span></td>
                            <td class="text-center">
                                <button class="btn btn-outline btn-sm btn-icon" title="Xem chi tiết"
                                    onclick="showSchedule('Bàn Nhóm G2')">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết / Lịch đặt -->
    <div id="scheduleModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div
            style="background: #ffffff; color: #111827; width: 500px; max-width: 90%; border-radius: 16px; padding: 30px; position: relative; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <i class="ph-bold ph-x" onclick="closeSchedule()"
                style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>
            <h2 id="modalRoomName"
                style="font-size: 20px; font-weight: bold; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 15px;">
                Thông tin: Tên Phòng</h2>

            <p style="color: #4b5563; font-size: 14px; margin-bottom: 20px;">Lịch trình ngày <span id="modalDateDisplay"
                    style="font-weight: 600;">hôm nay</span>:</p>

            <div style="border-left: 2px solid #e5e7eb; padding-left: 15px; margin-left: 5px;">
                <div style="position: relative; margin-bottom: 20px;">
                    <div
                        style="position: absolute; left: -22px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #3b82f6;">
                    </div>
                    <span
                        style="display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 12px; margin-bottom: 5px;">08:00
                        - 12:00</span>
                    <div style="font-weight: 600; font-size: 15px;">Đang sử dụng</div>
                    <div style="color: #6b7280; font-size: 13px; margin-top: 3px;">Thông tin đã được mã hóa cho Demo.</div>
                </div>

                <div style="position: relative;">
                    <div
                        style="position: absolute; left: -22px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #f59e0b;">
                    </div>
                    <span
                        style="display: inline-block; background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 12px; margin-bottom: 5px;">14:00
                        - 16:00</span>
                    <div style="font-weight: 600; font-size: 15px;">Đã đặt trước</div>
                    <div style="color: #6b7280; font-size: 13px; margin-top: 3px;">Chờ khách đến check-in.</div>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button
                    style="flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; background: white; color: #374151; font-weight: 600; cursor: pointer;"
                    onclick="closeSchedule()">Đóng lại</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Thiết lập giá trị date picker là hôm nay
            var datePicker = document.getElementById('bookingDatePicker');
            if (datePicker) {
                datePicker.value = new Date().toISOString().split('T')[0];
            }

            // Xử lý bộ lọc tabs
            const tabs = document.querySelectorAll('.filter-tab');
            const rows = document.querySelectorAll('.status-row');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    // Xóa class active ở tất cả các tabs
                    tabs.forEach(t => t.classList.remove('filter-tab--active'));
                    // Thêm class active cho tab vừa click
                    this.classList.add('filter-tab--active');

                    const statusFilter = this.getAttribute('data-status');

                    rows.forEach(row => {
                        if (statusFilter === 'all') {
                            row.style.display = '';
                        } else {
                            if (row.getAttribute('data-status') === statusFilter) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });

        function showSchedule(roomName) {
            document.getElementById('modalRoomName').innerText = 'Thông tin chỗ: ' + roomName;

            var datePicker = document.getElementById('bookingDatePicker');
            var displayDate = "hôm nay";
            if (datePicker && datePicker.value) {
                var pickedDate = new Date(datePicker.value);
                var todayDate = new Date();

                if (pickedDate.toDateString() === todayDate.toDateString()) {
                    displayDate = "hôm nay (" + pickedDate.toLocaleDateString("vi-VN") + ")";
                } else {
                    displayDate = pickedDate.toLocaleDateString("vi-VN");
                }
            }
            document.getElementById('modalDateDisplay').innerText = displayDate;

            document.getElementById('scheduleModal').style.display = 'flex';
        }

        function closeSchedule() {
            document.getElementById('scheduleModal').style.display = 'none';
        }
    </script>
@endsection