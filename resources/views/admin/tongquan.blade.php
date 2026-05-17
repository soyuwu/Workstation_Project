@extends('admin.admin_master')

@section('page-title', 'Tổng quan')

@section('content')
      {{-- ============================================== --}}
      {{-- 1. TỔNG QUAN (DASHBOARD ANALYTICS) --}}
      {{-- ============================================== --}}
      <div id="section-dashboard">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Tổng Quan</h1>
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
                              <span class="metric-value">{{ number_format($todayRevenue ?? 0, 0, ',', '.') }} ₫</span>
                              <span class="metric-change metric-change--up">
                                    {{-- Thêm logic so sánh sau --}}
                              </span>
                        </div>
                  </div>

                  <div class="metric-card">
                        <div class="metric-icon metric-icon--green">
                              <i class="ph-fill ph-calendar-plus"></i>
                        </div>
                        <div class="metric-body">
                              <span class="metric-label">Doanh thu trong tháng</span>
                              <span class="metric-value">{{ number_format($monthRevenue ?? 0, 0, ',', '.') }} ₫</span>
                              <span class="metric-change metric-change--up">
                                    {{-- Thêm logic so sánh sau --}}
                              </span>
                        </div>
                  </div>

                  <div class="metric-card">
                        <div class="metric-icon metric-icon--purple">
                              <i class="ph-fill ph-notebook"></i>
                        </div>
                        <div class="metric-body">
                              <span class="metric-label">Đơn đặt phòng mới</span>
                              <span class="metric-value">{{ $newBookingsCount ?? 0 }}</span>
                              <span class="metric-change metric-change--up">
                                    {{-- Thêm logic so sánh sau --}}
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
                              {{-- Render chart items dynamically --}}
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
                                    {{-- Render legend items dynamically --}}
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
                              @forelse($activities ?? [] as $activity)
                                    <tr>
                                          <td>
                                                <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: {{ $activity->status == 'confirmed' ? '#d1fae5' : ($activity->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; color: {{ $activity->status == 'confirmed' ? '#059669' : ($activity->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                                                      <i class="ph-bold {{ $activity->status == 'confirmed' ? 'ph-check' : ($activity->status == 'cancelled' ? 'ph-x' : 'ph-clock') }}"></i>
                                                </div>
                                          </td>
                                          <td>
                                                <div style="font-weight: 500;">{{ $activity->status == 'confirmed' ? 'Đã xác nhận' : ($activity->status == 'cancelled' ? 'Đã hủy' : 'Đơn mới') }} {{ $activity->booking_code }}</div>
                                                <div class="text-sm text-muted">Khách hàng: {{ $activity->user ? $activity->user->name : 'Khách vãng lai' }}</div>
                                          </td>
                                          <td>
                                                {{ $activity->workspace ? $activity->workspace->name : 'Phòng / Bàn' }}
                                          </td>
                                          <td>
                                                <div class="text-sm text-muted">{{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</div>
                                          </td>
                                    </tr>
                              @empty
                                    <tr>
                                          <td colspan="4" class="text-center py-4 text-muted">Chưa có hoạt động nào</td>
                                    </tr>
                              @endforelse
                        </tbody>
                  </table>
            </div>
      </div>
@endsection