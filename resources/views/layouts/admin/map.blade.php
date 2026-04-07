@extends('layouts.admin.admin_master')

@section('page-title', 'Không gian & Cơ sở')

@section('content')
    <style>
        .cinema-map-container {
            background: #ffffffff;
            padding: 40px;
            border-radius: 16px;
            color: #0d0e0aff;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            min-height: 80vh;
        }

        .map-legend {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            justify-content: center;
            background: #ebebf0ff;
            padding: 16px 32px;
            border-radius: 30px;
            width: max-content;
            margin-inline: auto;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 600;
        }

        .legend-color {
            width: 18px;
            height: 18px;
            border-radius: 4px;
        }

        .color-active {
            background: #548bf9ff;
            box-shadow: 0 0 10px #86c2f3ff;
        }

        .color-maintenance {
            background: #efc587ff;
        }

        .floor-plan {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
            min-width: 900px;
            padding: 20px;
        }

        .space-item {
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px;
            text-align: center;
            transition: all 0.2s;
            position: relative;
        }

        .space-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Status: Được sử dụng (Active) */
        .status-active {
            background: #548bf9ff;
            cursor: pointer;
            border: 2px solid #729befff;
        }

        .status-active:hover {
            background: #2563eb;
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.4);
        }

        /* Status: Bảo trì (Maintenance) */
        .status-maintenance {
            background: #efc587ff;
            cursor: not-allowed;
            border: 2px solid #ecd484ff;
            opacity: 0.5;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0, 0, 0, 0.2) 10px, rgba(0, 0, 0, 0.2) 20px);
        }

        /* Layout Sizes */
        .space-seminar {
            grid-column: span 12;
            height: 120px;
            font-size: 18px;
        }

        .space-meeting {
            grid-column: span 6;
            height: 100px;
        }

        .space-group {
            grid-column: span 4;
            height: 90px;
        }

        .space-group-wide {
            grid-column: span 6;
            height: 90px;
        }

        .space-ind {
            grid-column: span 2;
            height: 80px;
        }

        .space-ind-wide {
            grid-column: span 3;
            height: 80px;
        }

        .section-label {
            grid-column: span 12;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 20px;
            border-bottom: 2px solid #374151;
            padding-bottom: 8px;
        }

        /* Modal Styles */
        .schedule-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: #ffffff;
            color: #111827;
            width: 500px;
            max-width: 90%;
            border-radius: 16px;
            padding: 30px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        .timeline {
            margin-top: 20px;
            border-left: 3px solid #e5e7eb;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 4px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #ef4444;
            /* Booked dot */
        }

        .time-badge {
            display: inline-block;
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 5px;
        }
    </style>

    <div id="section-facility">
        <div class="cinema-map-container">

            <h2 style="text-align: center; margin-bottom: 10px; font-size: 24px; font-weight: bold; letter-spacing: 2px;">
                BẢN ĐỒ VỊ TRÍ ĐẶT CHỖ
            </h2>

            <div style="text-align: center; margin-bottom: 30px;">
                <label for="bookingDatePicker" style="margin-right: 10px; font-weight: bold; font-size: 16px;">Chọn Ngày Xem:</label>
                <input type="date" id="bookingDatePicker"
                    style="padding: 8px 12px; border-radius: 6px; border: 1px solid #374151; background: #f4f5f6ff; color: black; cursor: pointer; font-family: inherit;">
            </div>

            <div class="map-legend">
                <div class="legend-item">
                    <div class="legend-color color-active"></div> <span>Được sử dụng</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color color-maintenance"></div> <span>Bảo trì</span>
                </div>
            </div>

            <div class="floor-plan">

                <div class="section-label">Hội Thảo (10-15 người)</div>
                <div class="space-item space-seminar status-active" onclick="showSchedule('Phòng Hội Thảo (Grand Hall)')">
                    <i class="ph-bold ph-presentation-chart" style="font-size: 32px; margin-bottom: 8px;"></i>
                    <div class="space-title">PHÒNG HỘI THẢO</div>
                </div>

                <div class="section-label">Phòng Họp (6-9 người)</div>
                <div class="space-item space-meeting status-active" onclick="showSchedule('Phòng Họp M1')">
                    <i class="ph-bold ph-users-three" style="font-size: 28px; margin-bottom: 6px;"></i>
                    <div class="space-title">PHÒNG HỘI THẢO M1</div>
                </div>
                <div class="space-item space-meeting status-active" onclick="showSchedule('Phòng Họp M2')">
                    <i class="ph-bold ph-users-three" style="font-size: 28px; margin-bottom: 6px;"></i>
                    <div class="space-title">PHÒNG HỘI THẢO M2</div>
                </div>

                <div class="section-label">Bàn Nhóm Học (3-5 người)</div>
                <div class="space-item space-group status-active" onclick="showSchedule('Bàn Nhóm G1')">
                    <div class="space-title">NHÓM G1</div>
                </div>
                <div class="space-item space-group status-maintenance">
                    <div class="space-title">NHÓM G2 (Bảo trì)</div>
                </div>
                <div class="space-item space-group status-active" onclick="showSchedule('Bàn Nhóm G3')">
                    <div class="space-title">NHÓM G3</div>
                </div>
                <div class="space-item space-group-wide status-active" onclick="showSchedule('Bàn Nhóm G4')">
                    <div class="space-title">NHÓM G4</div>
                </div>
                <div class="space-item space-group-wide status-active" onclick="showSchedule('Bàn Nhóm G5')">
                    <div class="space-title">NHÓM G5</div>
                </div>

                <div class="section-label">Bàn Cá Nhân (1-2 người)</div>
                <!-- Row of 6 -->
                <div class="space-item space-ind status-active" onclick="showSchedule('Bàn T01')">
                    <div class="space-title">T01</div>
                </div>
                <div class="space-item space-ind status-active" onclick="showSchedule('Bàn T02')">
                    <div class="space-title">T02</div>
                </div>
                <div class="space-item space-ind status-maintenance">
                    <div class="space-title">T03</div>
                </div>
                <div class="space-item space-ind status-active" onclick="showSchedule('Bàn T04')">
                    <div class="space-title">T04</div>
                </div>
                <div class="space-item space-ind status-active" onclick="showSchedule('Bàn T05')">
                    <div class="space-title">T05</div>
                </div>
                <div class="space-item space-ind status-active" onclick="showSchedule('Bàn T06')">
                    <div class="space-title">T06</div>
                </div>

                <!-- Row of 4 -->
                <div class="space-item space-ind-wide status-active" onclick="showSchedule('Bàn T07')">
                    <div class="space-title">T07</div>
                </div>
                <div class="space-item space-ind-wide status-maintenance">
                    <div class="space-title">T08</div>
                </div>
                <div class="space-item space-ind-wide status-active" onclick="showSchedule('Bàn T09')">
                    <div class="space-title">T09</div>
                </div>
                <div class="space-item space-ind-wide status-active" onclick="showSchedule('Bàn T10')">
                    <div class="space-title">T10</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scheduling Modal Overlay -->
    <div id="scheduleModal" class="schedule-modal">
        <div class="modal-content">
            <i class="ph-bold ph-x close-modal" onclick="closeSchedule()"></i>
            <h2 id="modalRoomName"
                style="font-size: 22px; font-weight: bold; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">Lịch
                Đặt: Tên Phòng</h2>

            <p style="margin-top: 15px; color: #6b7280;">Dưới đây là các khung giờ đã được khách hàng đặt tại chỗ này trong
                ngày <span id="modalDateDisplay" style="font-weight: bold;">hôm nay</span>.</p>

            <div class="timeline">
                <!-- Mock Data -->
                <div class="timeline-item">
                    <div class="time-badge">08:00 - 10:30</div>
                    <div style="font-weight: 600;">Khách: Nguyễn Minh Tâm</div>
                    <div style="color: #6b7280; font-size: 13px;">SĐT: 0901234567 | Mã Đơn: #BK-1050</div>
                </div>

                <div class="timeline-item">
                    <div class="time-badge">14:00 - 16:00</div>
                    <div style="font-weight: 600;">Khách: Lê Hải Yến</div>
                    <div style="color: #6b7280; font-size: 13px;">SĐT: 0988000111 | Mã Đơn: #BK-1058</div>
                </div>

                <div class="timeline-item">
                    <div class="time-badge">18:30 - 21:00</div>
                    <div style="font-weight: 600;">Khách: Đặt nhóm</div>
                    <div style="color: #6b7280; font-size: 13px;">Mã Đơn: #BK-1062</div>
                </div>
            </div>

            <button class="btn btn-primary"
                style="width: 100%; margin-top: 20px; padding: 12px; border: none; border-radius: 8px; background: #3b82f6; color: white; font-weight: bold; cursor: pointer;"
                onclick="closeSchedule()">Đóng</button>
        </div>
    </div>

    <script>
        // Đặt ngày mặc định là hôm nay
        document.addEventListener("DOMContentLoaded", function () {
            var datePicker = document.getElementById('bookingDatePicker');
            if (datePicker) {
                var today = new Date().toISOString().split('T')[0];
                datePicker.value = today;

                // Xử lý sự kiện khi thay đổi ngày
                datePicker.addEventListener('change', function () {
                    // Tùy chỉnh hiệu ứng hiển thị tải lại bản đồ khi đổi ngày (mock)
                    const items = document.querySelectorAll('.space-item');
                    items.forEach(item => {
                        item.style.opacity = '0.5';
                    });

                    setTimeout(() => {
                        items.forEach(item => {
                            item.style.opacity = '1';
                            // Giả lập random status
                            if (item.classList.contains('status-active') || item.classList.contains('status-maintenance')) {
                                if (Math.random() > 0.8) {
                                    item.classList.remove('status-active');
                                    item.classList.add('status-maintenance');
                                    if (item.querySelector('.space-title') && !item.querySelector('.space-title').innerText.includes('Bảo trì')) {
                                        // item.querySelector('.space-title').innerText += ' (Trống)';
                                    }
                                } else {
                                    item.classList.remove('status-maintenance');
                                    item.classList.add('status-active');
                                }
                            }
                        });

                        // Hiển thị thông báo Toast / Alert gọn nhẹ (tùy chọn)
                        let selectedDateData = new Date(this.value).toLocaleDateString("vi-VN");
                    }, 500);
                });
            }
        });

        function showSchedule(roomName) {
            document.getElementById('modalRoomName').innerText = 'Lịch Đặt: ' + roomName;

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