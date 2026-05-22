@extends('layouts.app')

@section('title', 'Đặt ' . $serviceInfo->name)
@section('nav-mode', 'solid')

@section('content')
    <style>
        /* Custom styles for timeline */
        .timeline-container {
            overflow-x: auto;
            position: relative;
        }
        .timeline-grid {
            display: grid;
            grid-template-columns: 200px repeat(48, minmax(30px, 1fr));
            border-bottom: 1px solid #e2e8f0;
            user-select: none;
        }
        .timeline-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            font-size: 0.75rem;
            color: #64748b;
        }
        .time-slot {
            height: 48px;
            border-right: 1px dashed #e2e8f0;
            cursor: pointer;
            transition: background-color 0.1s;
        }
        .time-slot.hour-mark {
            border-right: 1px solid #cbd5e1;
        }
        .time-slot.disabled {
            background-color: #cbd5e1;
            cursor: not-allowed;
            background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.03), rgba(0,0,0,0.03) 5px, rgba(0,0,0,0.08) 5px, rgba(0,0,0,0.08) 10px);
        }
        .time-slot.booked {
            background-color: #94a3b8;
            cursor: not-allowed;
            position: relative;
        }
        .time-slot.selected {
            background-color: #dbeafe !important;
            border-top: 2px solid #2563eb !important;
            border-bottom: 2px solid #2563eb !important;
        }
        .time-slot.selected.first {
            border-left: 2px solid #2563eb;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .time-slot.selected.last {
            border-right: 2px solid #2563eb;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        

    </style>

@section('content')
    <x-common.sub-page-hero
        icon="{{ $serviceInfo->icon }}"
        subtitle="Thuê theo giờ / ngày"
        :title="$serviceInfo->name"
        :description="$serviceInfo->booking_desc"
    />

    <section class="bg-slate-50 py-12">
        <div class="mx-auto max-w-[1400px] px-6">
            
            <!-- Controls -->
            <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Chọn ngày đặt</label>
                        <input type="date" id="booking_date" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-white border border-slate-200"></div>Trống</div>
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-slate-400"></div>Đã đặt</div>
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-slate-300" style="background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.03), rgba(0,0,0,0.03) 5px, rgba(0,0,0,0.08) 5px, rgba(0,0,0,0.08) 10px);"></div>Không khả dụng</div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
                <div class="timeline-container pb-4">
                    
                    <!-- Header (Hours 0-23) -->
                    <div class="timeline-grid timeline-header border-b border-slate-200">
                        <div class="p-4 font-semibold text-slate-700 bg-white sticky left-0 z-20 border-r border-slate-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">Danh sách phòng</div>
                        @for($i = 0; $i < 24; $i++)
                            <div class="col-span-2 p-2 border-r border-slate-200 text-center">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</div>
                        @endfor
                    </div>

                    <!-- Room Tracks -->
                    @foreach($rooms as $room)
                        <div class="timeline-grid group hover:bg-slate-50 transition-colors" data-room-id="{{ $room['id'] }}" data-room-name="{{ $room['name'] }}" data-room-price="{{ $room['price'] }}" data-room-image="{{ $room['image'] }}" data-room-capacity="{{ $room['capacity'] }}">
                            <!-- Room Info Sticky Column -->
                            <div class="p-3 bg-white group-hover:bg-slate-50 sticky left-0 z-10 border-r border-slate-200 flex items-center gap-3 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] transition-colors">
                                <img src="{{ $room['image'] }}" class="w-10 h-10 rounded-lg object-cover" alt="room">
                                <div>
                                    <h4 class="font-bold text-sm text-on-surface leading-tight">{{ $room['name'] }}</h4>
                                    <p class="text-xs text-slate-500">{{ $room['capacity'] }} • <span class="text-primary font-medium">{{ number_format($room['price']) }}đ/h</span></p>
                                </div>
                            </div>

                            <!-- 48 Time Slots (30 mins each) -->
                            @for($i = 0; $i < 48; $i++)
                                <div class="time-slot {{ $i % 2 !== 0 ? 'hour-mark' : '' }}" data-slot="{{ $i }}" title="{{ floor($i/2) }}:{{ $i%2===0 ? '00' : '30' }} - {{ floor(($i+1)/2) }}:{{ ($i+1)%2===0 ? '00' : '30' }}"></div>
                            @endfor
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </section>



    <script>
        (function() {
            const dateInput = document.getElementById('booking_date');
            const tracks = document.querySelectorAll('.timeline-grid[data-room-id]');
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            dateInput.value = `${yyyy}-${mm}-${dd}`;
            dateInput.min = `${yyyy}-${mm}-${dd}`;

            // Dữ liệu booking thực tế từ DB (được Controller truyền ra)
            const confirmedBookings = @json($confirmedBookings ?? []);

            function renderConfirmedBookings() {
                // Xóa trạng thái booked cũ
                document.querySelectorAll('.time-slot.booked').forEach(el => el.classList.remove('booked'));
                const selectedDateStr = dateInput.value; // YYYY-MM-DD

                confirmedBookings.forEach(booking => {
                    // Kiểm tra nếu booking thuộc ngày đang chọn
                    if (booking.date === selectedDateStr) {
                        const track = document.querySelector(`.timeline-grid[data-room-id="${booking.room_id}"]`);
                        if (track) {
                            // Chuyển start_time và end_time thành slot index
                            const startSlot = timeToSlot(booking.start_time);
                            const endSlot = timeToSlot(booking.end_time);
                            const slots = track.querySelectorAll('.time-slot');

                            for (let i = startSlot; i < endSlot && i < 48; i++) {
                                if (slots[i]) {
                                    slots[i].classList.add('booked');
                                    // Nếu đơn đã sử dụng xong (completed), ta thêm class disabled (xám) kèm title để phân biệt
                                    if (booking.status === 'completed') {
                                        slots[i].classList.add('disabled');
                                        slots[i].title = 'Đã sử dụng xong (Quá khứ)';
                                    } else {
                                        slots[i].title = 'Đã có người đặt';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function timeToSlot(timeStr) {
                // timeStr có dạng "HH:MM:SS" hoặc "HH:MM"
                const parts = timeStr.split(':');
                const hours = parseInt(parts[0], 10);
                const minutes = parseInt(parts[1], 10);
                return (hours * 2) + (minutes >= 30 ? 1 : 0);
            }

            // Render lần đầu
            renderConfirmedBookings();

            dateInput.addEventListener('change', () => {
                updateTimelineState();
                renderConfirmedBookings(); // Cập nhật lại danh sách booking khi đổi ngày
            });

            let isDragging = false;
            let startSlot = null;
            let currentRow = null;
            let currentRoomData = null;
            let selectedSlots = [];

            // Ngăn chặn hành vi kéo thả mặc định
            document.addEventListener('dragstart', (e) => e.preventDefault());

            const container = document.querySelector('.timeline-container');

            // Event Delegation cho mousedown
            container.addEventListener('mousedown', (e) => {
                const slot = e.target.closest('.time-slot');
                if (!slot) return;
                
                if (e.button !== 0) return;
                if (slot.classList.contains('disabled') || slot.classList.contains('booked')) return;
                
                isDragging = true;
                startSlot = parseInt(slot.dataset.slot);
                currentRow = slot.closest('.timeline-grid');
                currentRoomData = {
                    id: currentRow.dataset.roomId,
                    name: currentRow.dataset.roomName,
                    price: parseInt(currentRow.dataset.roomPrice),
                    image: currentRow.dataset.roomImage,
                    capacity: currentRow.dataset.roomCapacity
                };
                
                clearSelection();
                selectRange(currentRow, startSlot, startSlot);
            });

            // Event Delegation cho mouseover
            container.addEventListener('mouseover', (e) => {
                if (!isDragging) return;
                const slot = e.target.closest('.time-slot');
                if (!slot) return;
                
                const track = slot.closest('.timeline-grid');
                if (track !== currentRow) return;
                
                const currentIndex = parseInt(slot.dataset.slot);
                const min = Math.min(startSlot, currentIndex);
                const max = Math.max(startSlot, currentIndex);
                
                const slots = Array.from(currentRow.querySelectorAll('.time-slot'));
                
                let canSelect = true;
                for(let i = min; i <= max; i++) {
                    if(slots[i].classList.contains('disabled') || slots[i].classList.contains('booked')) {
                        canSelect = false;
                        break;
                    }
                }

                if(canSelect) {
                    selectRange(currentRow, min, max);
                }
            });

            // Mouseup trên toàn màn hình
            window.addEventListener('mouseup', () => {
                if (isDragging) {
                    isDragging = false;
                    if (selectedSlots.length > 0) {
                        openCheckoutModal();
                    }
                }
            });

            function selectRange(track, min, max) {
                clearSelection();
                selectedSlots = [];
                const slots = track.querySelectorAll('.time-slot');
                for(let i = min; i <= max; i++) {
                    slots[i].classList.add('selected');
                    if(i === min) slots[i].classList.add('first');
                    if(i === max) slots[i].classList.add('last');
                    selectedSlots.push(i);
                }
            }


            function clearSelection() {
                document.querySelectorAll('.time-slot.selected').forEach(el => {
                    el.classList.remove('selected', 'first', 'last');
                });
                selectedSlots = [];
            }

            function formatTime(slotIndex) {
                const hour = Math.floor(slotIndex / 2);
                const min = slotIndex % 2 === 0 ? '00' : '30';
                return `${String(hour).padStart(2, '0')}:${min}`;
            }

            function updateTimelineState() {
                const selectedDateStr = dateInput.value;
                const now = new Date();
                const yyyy = now.getFullYear();
                const mm = String(now.getMonth() + 1).padStart(2, '0');
                const dd = String(now.getDate()).padStart(2, '0');
                const todayStr = `${yyyy}-${mm}-${dd}`;

                const isToday = (selectedDateStr === todayStr);
                // Khung giờ hiện tại (mỗi slot 30 phút)
                const currentSlot = (now.getHours() * 2) + (now.getMinutes() >= 30 ? 1 : 0);

                document.querySelectorAll('.time-slot').forEach(slot => { slot.classList.remove('disabled'); });

                if (isToday) {
                    document.querySelectorAll('.time-slot').forEach(slot => {
                        const slotIndex = parseInt(slot.dataset.slot);
                        if (slotIndex <= currentSlot) {
                            slot.classList.add('disabled');
                            slot.title = 'Đã qua thời gian';
                        }
                    });
                }
            }

            // Init state
            updateTimelineState();
            
            // Handle Checkout Redirect
            function openCheckoutModal() {
                try {
                    if(selectedSlots.length === 0) return;
                    
                    const startSlotIdx = Math.min(...selectedSlots);
                    const endSlotIdx = Math.max(...selectedSlots) + 1; // +1 to mean "up to the end of that slot"
                    
                    const startTime = formatTime(startSlotIdx);
                    const endTime = formatTime(endSlotIdx);
                    
                    const params = new URLSearchParams({
                        room_id: currentRoomData.id,
                        room_name: currentRoomData.name,
                        room_price: currentRoomData.price,
                        room_image: currentRoomData.image,
                        room_capacity: currentRoomData.capacity,
                        date: dateInput.value,
                        start_time: startTime,
                        end_time: endTime
                    });

                    window.location.href = `/booking/checkout?${params.toString()}`;
                } catch (error) {
                    console.error("Lỗi khi chuyển trang:", error);
                    alert("Có lỗi xảy ra khi chuyển trang: " + error.message);
                }
            }
        })();
    </script>
@endsection
