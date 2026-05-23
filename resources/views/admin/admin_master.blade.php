<!DOCTYPE html>
<html lang="vi">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>@yield('page-title', 'Admin Dashboard') - Workstation Portal</title>
      <meta name="description" content="Bảng điều khiển quản trị hệ thống Workstation Booking">
      <!-- Modern Font: Inter -->
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
      <!-- Phosphor Icons -->
      <script src="https://unpkg.com/@phosphor-icons/web"></script>

      <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
      <div class="admin-layout">

            <!-- =============================== -->
            <!-- SIDEBAR NAVIGATION              -->
            <!-- =============================== -->
            <aside class="sidebar" id="adminSidebar">
                  <div class="sidebar__brand">
                        <span>WS Portal</span>
                  </div>

                  <nav class="sidebar__nav">
                        <a class="sidebar__link {{ Request::is('admin/tongquan') ? 'sidebar__link--active' : '' }}"
                              href="{{ route('admin.tongquan') }}">
                              <span>Tổng Quan</span>
	                        </a>
	                        @php
	                              $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')
	                                    ->whereHas('payment', function ($query) {
	                                          $query->where('payment_status', 'pending')
	                                                ->where('payment_method', 'bank_transfer')
	                                                ->whereNotNull('reported_at');
	                                    })
	                                    ->count();
	                        @endphp
                        <a class="sidebar__link {{ Request::is('admin/booking') ? 'sidebar__link--active' : '' }}"
                              href="{{ route('admin.booking') }}" style="display: flex; align-items: center;">
                              <span>Thông Tin Booking</span>
                              @if($pendingBookingsCount > 0)
                                    <span class="badge badge--red" style="margin-left: auto; border-radius: 50%; padding: 2px 6px; font-size: 11px;">{{ $pendingBookingsCount }}</span>
                              @endif
                        </a>

                        <a class="sidebar__link {{ Request::is('admin/voucher') ? 'sidebar__link--active' : '' }}"
                              href="{{ route('admin.voucher') }}">
                              <span>Vouchers Khuyến Mãi</span>
                        </a>
                        <a class="sidebar__link {{ Request::is('admin/taikhoan') ? 'sidebar__link--active' : '' }}"
                              href="{{ route('admin.taikhoan') }}">
                              <span>Quản Lý Tài Khoản</span>
                        </a>

                        <a class="sidebar__link {{ Request::is('admin/workspace') ? 'sidebar__link--active' : '' }}"
                              href="{{ route('admin.workspace') }}">
                              <span>Quản Lý Không Gian</span>
                        </a>


                  </nav>

                  <div class="sidebar__user">
                        <div class="sidebar__user-info">
                              <div class="sidebar__user-name">Quản Trị Viên</div>
                              <div class="sidebar__user-role">Super Admin</div>
                        </div>
                  </div>
            </aside>

            <!-- =============================== -->
            <!-- MAIN CONTENT AREA               -->
            <!-- =============================== -->
            <main class="main-content" id="mainContent">

                  <!-- TOP HEADER BAR -->
                  <header class="top-header" id="topHeader" style="justify-content: flex-end;">

                        <div class="header-actions">
                              <form action="{{ route('logOut') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm">
                                          Đăng xuất
                                    </button>
                              </form>
                        </div>
                  </header>

                  <!-- PAGE CONTENT -->
                  <div class="page-container" id="pageContainer">
                        @yield('content')
                  </div>
            </main>

      </div>

      <!-- =============================== -->
      <!-- JAVASCRIPT                       -->
      <!-- =============================== -->
      @vite('resources/js/admin.js')

      @yield('extra-js')
</body>

</html>
