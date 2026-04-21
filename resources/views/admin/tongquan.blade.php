@extends('layouts.admin.admin_master')

@section('page-title', 'Tổng quan')

@section('content')
      {{-- ============================================== --}}
      {{-- 1. TỔNG QUAN (DASHBOARD ANALYTICS) --}}
      {{-- ============================================== --}}
      <div id="section-dashboard">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Tổng Quan</h1>
                        <p class="page-subtitle">Theo dõi hiệu suất kinh doanh Workstation theo thời gian thực.</p>
                  </div>
            </div>

            {{-- Key Metrics Cards --}}
            <div class="metrics-grid">
                  <div class="metric-card">
                        <div class="metric-icon metric-icon--blue">
                              <i class="ph-fill ph-currency-circle-dollar"></i>
                        </div>
                        <div class="metric-body">
                              <span class="metric-label">Doanh thu trong ngày</span>
                              <span class="metric-value">15.450.000 ₫</span>
                              <span class="metric-change metric-change--up">
                                    <i class="ph-bold ph-trend-up"></i> +12% so với hôm qua
                              </span>
                        </div>
                  </div>

                  <div class="metric-card">
                        <div class="metric-icon metric-icon--green">
                              <i class="ph-fill ph-calendar-plus"></i>
                        </div>
                        <div class="metric-body">
                              <span class="metric-label">Doanh thu trong tháng</span>
                              <span class="metric-value">420.000.000 ₫</span>
                              <span class="metric-change metric-change--up">
                                    <i class="ph-bold ph-trend-up"></i> +8% so với tháng trước
                              </span>
                        </div>
                  </div>

                  <div class="metric-card">
                        <div class="metric-icon metric-icon--purple">
                              <i class="ph-fill ph-notebook"></i>
                        </div>
                        <div class="metric-body">
                              <span class="metric-label">Đơn đặt phòng mới</span>
                              <span class="metric-value">42</span>
                              <span class="metric-change metric-change--up">
                                    <i class="ph-bold ph-trend-up"></i> +5 Bookings hôm nay
                              </span>
                        </div>
                  </div>


            </div>

            {{-- Charts & Activity Row --}}
            <div class="dashboard-row">
                  {{-- Revenue Bar Chart --}}
                  <div class="card card--chart">
                        <div class="card__header">
                              <h2 class="card__title">Biểu đồ Doanh thu theo ngày (7 ngày gần nhất)</h2>
                              <span class="badge badge--gray">Cập nhật Live</span>
                        </div>
                        <div class="bar-chart" id="revenueChart">
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar" style="height: 40%;" data-value="6.2tr">
                                          <span class="bar-chart__tooltip">6.200.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">27/03</span>
                              </div>
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar" style="height: 55%;" data-value="8.5tr">
                                          <span class="bar-chart__tooltip">8.500.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">28/03</span>
                              </div>
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar" style="height: 70%;" data-value="10.8tr">
                                          <span class="bar-chart__tooltip">10.800.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">29/03</span>
                              </div>
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar" style="height: 45%;" data-value="7.0tr">
                                          <span class="bar-chart__tooltip">7.000.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">30/03</span>
                              </div>
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar" style="height: 80%;" data-value="12.3tr">
                                          <span class="bar-chart__tooltip">12.300.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">31/03</span>
                              </div>
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar" style="height: 60%;" data-value="9.1tr">
                                          <span class="bar-chart__tooltip">9.100.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">01/04</span>
                              </div>
                              <div class="bar-chart__item">
                                    <div class="bar-chart__bar bar-chart__bar--active" style="height: 95%;"
                                          data-value="15.4tr">
                                          <span class="bar-chart__tooltip">15.450.000 ₫</span>
                                    </div>
                                    <span class="bar-chart__label">Hôm nay</span>
                              </div>
                        </div>
                  </div>

                  {{-- Pie Chart - Room Type Usage --}}
                  <div class="card card--pie">
                        <div class="card__header">
                              <h2 class="card__title">Tỉ lệ loại phòng được đặt</h2>
                        </div>
                        <div class="pie-chart-wrapper">
                              <div class="pie-chart" id="roomTypePie"></div>
                              <div class="pie-chart__legend">
                                    <div class="pie-chart__legend-item">
                                          <span class="legend-dot legend-dot--blue"></span>
                                          Chỗ ngồi tự do (60%)
                                    </div>
                                    <div class="pie-chart__legend-item">
                                          <span class="legend-dot legend-dot--green"></span>
                                          Phòng họp (25%)
                                    </div>
                                    <div class="pie-chart__legend-item">
                                          <span class="legend-dot legend-dot--amber"></span>
                                          Pod Cá nhân (15%)
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>

            {{-- Recent Activity --}}
            <div class="card">
                  <div class="card__header">
                        <h2 class="card__title">Hoạt động gần đây</h2>
                        <button class="btn btn-outline btn-sm">Xem tất cả</button>
                  </div>
                  <table class="data-table" id="recentActivityTable">
                        <thead>
                              <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Sự kiện</th>
                                    <th>Vị trí / Phòng</th>
                                    <th>Thời gian</th>
                              </tr>
                        </thead>
                        <tbody>
                              <tr>
                                    <td><i class="ph-fill ph-sign-in activity-icon activity-icon--green"></i></td>
                                    <td><b>Khách Nguyễn Phương</b> vừa Check-in</td>
                                    <td>Phòng Họp M1</td>
                                    <td class="text-muted">Vài giây trước</td>
                              </tr>
                              <tr>
                                    <td><i class="ph-fill ph-coffee activity-icon activity-icon--amber"></i></td>
                                    <td>Đơn F&B mới từ <b>Phòng M1</b></td>
                                    <td>2x Cà phê, 1x Trà đào</td>
                                    <td class="text-muted">2 phút trước</td>
                              </tr>
                              <tr>
                                    <td><i class="ph-fill ph-calendar-plus activity-icon activity-icon--blue"></i></td>
                                    <td>Booking mới: <b>Bàn C-10</b></td>
                                    <td>Co-working Khu B</td>
                                    <td class="text-muted">15 phút trước</td>
                              </tr>
                              <tr>
                                    <td><i class="ph-fill ph-sign-out activity-icon activity-icon--red"></i></td>
                                    <td><b>Trần Thị C</b> đã Check-out</td>
                                    <td>Pod P-01</td>
                                    <td class="text-muted">30 phút trước</td>
                              </tr>
                              <tr>
                                    <td><i class="ph-fill ph-credit-card activity-icon activity-icon--green"></i></td>
                                    <td>Thanh toán MoMo thành công <b>#BK-1029</b></td>
                                    <td>300.000 ₫</td>
                                    <td class="text-muted">1 giờ trước</td>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </div>
@endsection
