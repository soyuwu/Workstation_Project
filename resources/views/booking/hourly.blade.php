@extends('layouts.app')

@section('title', 'Đặt ' . $serviceInfo['name'])
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
        
        /* Modal checkout */
        .checkout-modal {
            display: none;
        }
        .checkout-modal.active {
            display: flex;
        }
    </style>

    <x-common.sub-page-hero
        icon="{{ $serviceInfo['icon'] }}"
        subtitle="Thuê theo giờ / ngày"
        :title="$serviceInfo['name']"
        :description="$serviceInfo['desc']"
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
                        <div class="timeline-grid group hover:bg-slate-50 transition-colors" data-room-id="{{ $room['id'] }}" data-room-name="{{ $room['name'] }}" data-room-price="{{ $room['price'] }}" data-room-image="{{ $room['image'] }}">
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

    <!-- Checkout Modal -->
    <div id="checkout_modal" class="checkout-modal fixed inset-0 z-50 items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl animate-scale-in overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <h3 class="font-headline text-xl font-bold text-on-surface">Xác nhận đặt chỗ</h3>
                <button onclick="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
            
            <div class="p-6">
                <div class="flex gap-4 mb-6">
                    <img id="modal_room_img" src="" class="w-24 h-24 rounded-xl object-cover shadow-sm">
                    <div>
                        <h4 id="modal_room_name" class="font-bold text-lg text-on-surface mb-1">Room Name</h4>
                        <p class="text-sm text-slate-500 mb-2">Giá: <span id="modal_room_price" class="font-medium text-primary">0đ/h</span></p>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 mb-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Ngày đặt:</span>
                        <span id="modal_date" class="font-semibold text-slate-700">DD/MM/YYYY</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Khung giờ:</span>
                        <span id="modal_time" class="font-semibold text-slate-700">00:00 - 00:00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Thời lượng:</span>
                        <span id="modal_duration" class="font-semibold text-slate-700">0 giờ</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 flex justify-between">
                        <span class="text-slate-700 font-medium">Tổng tiền tạm tính:</span>
                        <span id="modal_total" class="font-bold text-xl text-primary">0 đ</span>
                    </div>
                </div>

                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <!-- Mock fields for demo -->
                    <button type="button" class="w-full rounded-xl bg-primary py-3.5 text-sm font-semibold text-white transition-all hover:bg-primary-dark hover:shadow-lg">
                        Tiến hành thanh toán
                    </button>
                </form>
            </div>
        </div>
    </div>

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

            // Mock some booked slots for realism
            mockBookings();

            dateInput.addEventListener('change', () => {
                updateTimelineState();
                mockBookings(); // Randomize mock data on date change
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
                    image: currentRow.dataset.roomImage
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
                const selectedDate = new Date(dateInput.value);
                const now = new Date();
                const isToday = selectedDate.toDateString() === now.toDateString();
                const currentSlot = (now.getHours() * 2) + (now.getMinutes() >= 30 ? 1 : 0);

                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.classList.remove('disabled');
                    const slotIndex = parseInt(slot.dataset.slot);
                    
                    if (isToday && slotIndex <= currentSlot) {
                        slot.classList.add('disabled');
                        slot.title = 'Đã qua thời gian';
                    }
                });
            }

            function mockBookings() {
                // Clear existing
                document.querySelectorAll('.time-slot.booked').forEach(el => el.classList.remove('booked'));
                
                // Randomly book some slots for demo
                tracks.forEach(track => {
                    const slots = track.querySelectorAll('.time-slot');
                    // 30% chance to have a booking block per room
                    if (Math.random() > 0.4) {
                        let start = Math.floor(Math.random() * 30) + 8; // Random start between 4:00 and 19:00
                        let length = Math.floor(Math.random() * 4) + 2; // 1 to 2 hours
                        
                        for(let i = start; i < start + length && i < 48; i++) {
                            if(!slots[i].classList.contains('disabled')) {
                                slots[i].classList.add('booked');
                                slots[i].title = 'Đã có người đặt';
                            }
                        }
                    }
                });
            }

            // Init state
            updateTimelineState();
            
            // Modal Logic
            window.openCheckoutModal = () => {
                if(selectedSlots.length === 0) return;
                
                const startSlotIdx = Math.min(...selectedSlots);
                const endSlotIdx = Math.max(...selectedSlots) + 1; // +1 to mean "up to the end of that slot"
                
                const durationHours = selectedSlots.length * 0.5;
                const total = currentRoomData.price * durationHours;

                document.getElementById('modal_room_img').src = currentRoomData.image;
                document.getElementById('modal_room_name').innerText = currentRoomData.name;
                document.getElementById('modal_room_price').innerText = currentRoomData.price.toLocaleString('vi-VN') + 'đ/h';
                
                // Format date DD/MM/YYYY
                const dArr = dateInput.value.split('-');
                document.getElementById('modal_date').innerText = `${dArr[2]}/${dArr[1]}/${dArr[0]}`;
                
                document.getElementById('modal_time').innerText = `${formatTime(startSlotIdx)} - ${formatTime(endSlotIdx)}`;
                document.getElementById('modal_duration').innerText = `${durationHours} giờ`;
                document.getElementById('modal_total').innerText = total.toLocaleString('vi-VN') + ' đ';

                document.getElementById('checkout_modal').classList.add('active');
            }

            window.closeModal = () => {
                document.getElementById('checkout_modal').classList.remove('active');
                clearSelection();
            }
        })();
    </script>
@endsection
