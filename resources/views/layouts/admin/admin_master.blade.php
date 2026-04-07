<!DOCTYPE html>
<html lang="vi">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>@yield('page-title', 'Admin Dashboard') - Workstation Portal</title>
      <meta name="description" content="Bảng điều khiển quản trị hệ thống Workstation Booking">
      <!-- Modern Font: Inter -->
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
      <!-- Phosphor Icons -->
      <script src="https://unpkg.com/@phosphor-icons/web"></script>

      <style>
            /* ===================================== */
            /* RESET & BASE                          */
            /* ===================================== */
            *,
            *::before,
            *::after {
                  box-sizing: border-box;
                  margin: 0;
                  padding: 0;
            }

            html {
                  font-size: 15px;
            }

            body {
                  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                  background: #f9fafb;
                  color: #111827;
                  min-height: 100vh;
                  overflow: hidden;
            }

            /* ===================================== */
            /* LAYOUT GRID                           */
            /* ===================================== */
            .admin-layout {
                  display: grid;
                  grid-template-columns: 280px 1fr;
                  min-height: 100vh;
            }

            /* ===================================== */
            /* SIDEBAR                               */
            /* ===================================== */
            .sidebar {
                  background: #ffffff;
                  border-right: 1px solid #e5e7eb;
                  display: flex;
                  flex-direction: column;
                  padding: 0;
                  position: sticky;
                  top: 0;
                  height: 100vh;
                  overflow-y: auto;
            }

            .sidebar__brand {
                  display: flex;
                  align-items: center;
                  gap: 12px;
                  padding: 24px 24px 20px;
                  font-size: 1.25rem;
                  font-weight: 700;
                  color: #111827;
                  border-bottom: 1px solid #e5e7eb;
            }

            .sidebar__brand i {
                  font-size: 1.6rem;
                  color: #111827;
            }

            .sidebar__nav {
                  flex: 1;
                  padding: 16px 12px;
                  display: flex;
                  flex-direction: column;
                  gap: 4px;
            }

            .sidebar__link {
                  display: flex;
                  align-items: center;
                  gap: 12px;
                  padding: 12px 16px;
                  border-radius: 6px;
                  color: #4b5563;
                  text-decoration: none;
                  font-size: 0.9rem;
                  font-weight: 500;
                  transition: all 0.2s ease;
                  cursor: pointer;
            }

            .sidebar__link:hover {
                  background: #f3f4f6;
                  color: #111827;
            }

            .sidebar__link i {
                  font-size: 1.3rem;
                  color: #6b7280;
            }

            .sidebar__link:hover i {
                  color: #111827;
            }

            .sidebar__link--active {
                  background: #e5e7eb;
                  color: #111827;
                  font-weight: 600;
            }

            .sidebar__link--active i {
                  color: #111827;
            }

            .sidebar__user {
                  display: flex;
                  align-items: center;
                  gap: 12px;
                  padding: 16px 20px;
                  border-top: 1px solid #e5e7eb;
                  margin-top: auto;
            }

            .sidebar__user-name {
                  font-weight: 600;
                  font-size: 0.9rem;
                  color: #111827;
            }

            .sidebar__user-role {
                  font-size: 0.75rem;
                  color: #6b7280;
            }

            /* ===================================== */
            /* MAIN CONTENT                          */
            /* ===================================== */
            .main-content {
                  display: flex;
                  flex-direction: column;
                  overflow-y: auto;
                  height: 100vh;
                  background: #f9fafb;
            }

            /* ===================================== */
            /* TOP HEADER                            */
            /* ===================================== */
            .top-header {
                  display: flex;
                  align-items: center;
                  justify-content: space-between;
                  padding: 16px 32px;
                  background: #ffffff;
                  border-bottom: 1px solid #e5e7eb;
                  position: sticky;
                  top: 0;
                  z-index: 100;
            }

            .search-box {
                  display: flex;
                  align-items: center;
                  gap: 10px;
                  background: #f3f4f6;
                  border: 1px solid #d1d5db;
                  border-radius: 6px;
                  padding: 8px 16px;
                  min-width: 320px;
            }

            .search-box i {
                  color: #6b7280;
                  font-size: 1.1rem;
            }

            .search-input {
                  background: transparent;
                  border: none;
                  color: #111827;
                  font-size: 0.9rem;
                  outline: none;
                  width: 100%;
                  font-family: inherit;
            }

            .search-input::placeholder {
                  color: #9ca3af;
            }

            .header-actions {
                  display: flex;
                  align-items: center;
                  gap: 16px;
            }

            .notification-bell {
                  position: relative;
                  cursor: pointer;
                  font-size: 1.3rem;
                  color: #4b5563;
            }

            .notification-bell:hover {
                  color: #111827;
            }

            .notification-dot {
                  position: absolute;
                  top: 0;
                  right: 0;
                  width: 8px;
                  height: 8px;
                  background: #ef4444;
                  border-radius: 50%;
            }

            /* ===================================== */
            /* PAGE CONTAINER                        */
            /* ===================================== */
            .page-container {
                  padding: 28px 32px;
                  flex: 1;
            }

            /* ===================================== */
            /* TYPOGRAPHY & HELPERS                  */
            /* ===================================== */
            .page-title {
                  font-size: 1.5rem;
                  font-weight: 700;
                  color: #111827;
            }

            .page-subtitle {
                  color: #6b7280;
                  font-size: 0.9rem;
                  margin-top: 4px;
            }

            .section-header {
                  display: flex;
                  justify-content: space-between;
                  align-items: flex-start;
                  margin-bottom: 24px;
                  flex-wrap: wrap;
                  gap: 16px;
            }

            .section-actions {
                  display: flex;
                  gap: 10px;
                  flex-wrap: wrap;
            }

            .text-muted {
                  color: #6b7280 !important;
            }

            .text-sm {
                  font-size: 0.8rem !important;
            }

            .text-danger {
                  color: #dc2626 !important;
            }

            .text-warning {
                  color: #d97706 !important;
            }

            .avatar {
                  border-radius: 50%;
                  object-fit: cover;
            }

            .avatar--sm {
                  width: 36px;
                  height: 36px;
            }

            .avatar--xs {
                  width: 28px;
                  height: 28px;
            }

            /* ===================================== */
            /* BUTTONS                               */
            /* ===================================== */
            .btn {
                  display: inline-flex;
                  align-items: center;
                  gap: 8px;
                  padding: 8px 16px;
                  border-radius: 6px;
                  font-size: 0.85rem;
                  font-weight: 500;
                  cursor: pointer;
                  border: 1px solid transparent;
                  transition: all 0.2s ease;
                  font-family: inherit;
                  white-space: nowrap;
            }

            .btn-primary {
                  background: #111827;
                  color: #ffffff;
                  border-color: #111827;
            }

            .btn-primary:hover {
                  background: #374151;
                  border-color: #374151;
            }

            .btn-success {
                  background: #059669;
                  color: #ffffff;
                  border-color: #059669;
            }

            .btn-success:hover {
                  background: #047857;
            }

            .btn-danger {
                  background: #dc2626;
                  color: #ffffff;
                  border-color: #dc2626;
            }

            .btn-danger:hover {
                  background: #b91c1c;
            }

            .btn-outline {
                  background: #ffffff;
                  border: 1px solid #d1d5db;
                  color: #374151;
            }

            .btn-outline:hover {
                  border-color: #9ca3af;
                  background: #f3f4f6;
            }

            .btn-sm {
                  padding: 6px 12px;
                  font-size: 0.8rem;
            }

            .btn-icon {
                  padding: 6px 10px;
            }

            .btn-icon--danger:hover {
                  border-color: #fca5a5;
                  color: #dc2626;
                  background: #fee2e2;
            }

            /* ===================================== */
            /* BADGE                                 */
            /* ===================================== */
            .badge {
                  display: inline-flex;
                  align-items: center;
                  gap: 4px;
                  padding: 4px 8px;
                  border-radius: 4px;
                  font-size: 0.75rem;
                  font-weight: 500;
                  white-space: nowrap;
                  border: 1px solid #d1d5db;
                  background: #f9fafb;
                  color: #374151;
            }

            .badge--green {
                  background: #d1fae5;
                  color: #065f46;
                  border-color: #a7f3d0;
            }

            .badge--red {
                  background: #fee2e2;
                  color: #991b1b;
                  border-color: #fecaca;
            }

            .badge--yellow {
                  background: #fef3c7;
                  color: #92400e;
                  border-color: #fde68a;
            }

            .badge--blue {
                  background: #dbeafe;
                  color: #1e40af;
                  border-color: #bfdbfe;
            }

            .badge--purple {
                  background: #ede9fe;
                  color: #5b21b6;
                  border-color: #ddd6fe;
            }

            .badge--amber {
                  background: #fef3c7;
                  color: #b45309;
                  border-color: #fde68a;
            }

            .badge--gray {
                  background: #f3f4f6;
                  color: #374151;
                  border-color: #e5e7eb;
            }

            .badge--pulse {
                  animation: none;
            }

            /* ===================================== */
            /* CARD                                  */
            /* ===================================== */
            .card {
                  background: #ffffff;
                  border: 1px solid #e5e7eb;
                  border-radius: 8px;
                  padding: 24px;
                  margin-bottom: 20px;
                  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .card--table {
                  padding: 0;
                  overflow-x: auto;
            }

            .card--table .card__header {
                  padding: 20px 24px;
                  margin-bottom: 0;
            }

            .card__header {
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  margin-bottom: 16px;
            }

            .card__title {
                  display: flex;
                  align-items: center;
                  gap: 8px;
                  font-size: 1rem;
                  font-weight: 600;
                  color: #111827;
            }

            /* ===================================== */
            /* DATA TABLE                            */
            /* ===================================== */
            .data-table {
                  width: 100%;
                  border-collapse: collapse;
                  font-size: 0.85rem;
            }

            .data-table thead {
                  background: #f9fafb;
                  border-bottom: 1px solid #e5e7eb;
            }

            .data-table th {
                  padding: 12px 20px;
                  text-align: left;
                  font-weight: 600;
                  color: #4b5563;
                  font-size: 0.75rem;
                  text-transform: uppercase;
            }

            .data-table td {
                  padding: 12px 20px;
                  border-bottom: 1px solid #f3f4f6;
                  vertical-align: middle;
                  color: #111827;
            }

            .data-table tbody tr:hover {
                  background: #f9fafb;
            }

            .th-right,
            .td-right {
                  text-align: right;
            }

            .action-group {
                  display: flex;
                  gap: 6px;
                  justify-content: flex-end;
            }

            /* ===================================== */
            /* METRICS & ITEMS                       */
            /* ===================================== */
            .metrics-grid {
                  display: grid;
                  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                  gap: 16px;
                  margin-bottom: 24px;
            }

            .metrics-grid--4 {
                  grid-template-columns: repeat(4, 1fr);
            }

            .metric-card {
                  background: #ffffff;
                  border: 1px solid #e5e7eb;
                  border-radius: 8px;
                  padding: 20px;
                  display: flex;
                  align-items: flex-start;
                  gap: 16px;
                  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .metric-card--sm {
                  padding: 16px;
            }

            .metric-icon {
                  width: 48px;
                  height: 48px;
                  border-radius: 8px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  font-size: 1.4rem;
                  background: #f3f4f6;
                  color: #4b5563;
            }

            .metric-icon--blue {
                  background: #dbeafe;
                  color: #1e40af;
                  border: none;
            }

            .metric-icon--green {
                  background: #d1fae5;
                  color: #065f46;
                  border: none;
            }

            .metric-icon--purple {
                  background: #ede9fe;
                  color: #5b21b6;
                  border: none;
            }

            .metric-icon--amber {
                  background: #fef3c7;
                  color: #92400e;
                  border: none;
            }

            .metric-icon--red {
                  background: #fee2e2;
                  color: #991b1b;
                  border: none;
            }

            .metric-body {
                  display: flex;
                  flex-direction: column;
                  gap: 4px;
            }

            .metric-label {
                  font-size: 0.8rem;
                  color: #6b7280;
            }

            .metric-value {
                  font-size: 1.5rem;
                  font-weight: 700;
                  color: #111827;
            }

            .metric-change {
                  font-size: 0.75rem;
                  display: flex;
                  align-items: center;
                  gap: 4px;
            }

            .metric-change--up {
                  color: #059669;
            }

            .metric-change--down {
                  color: #dc2626;
            }

            /* ===================================== */
            /* FILTER TABS                           */
            /* ===================================== */
            .filter-tabs {
                  display: flex;
                  gap: 6px;
                  margin-bottom: 20px;
                  flex-wrap: wrap;
            }

            .filter-tab {
                  padding: 6px 14px;
                  border-radius: 6px;
                  font-size: 0.85rem;
                  font-weight: 500;
                  cursor: pointer;
                  background: #ffffff;
                  border: 1px solid #d1d5db;
                  color: #4b5563;
            }

            .filter-tab:hover {
                  border-color: #9ca3af;
                  background: #f9fafb;
            }

            .filter-tab--active {
                  background: #111827;
                  border-color: #111827;
                  color: #ffffff;
            }

            /* ===================================== */
            /* FORM ELEMENTS                         */
            /* ===================================== */
            .input-field,
            .input-select {
                  background: #ffffff;
                  border: 1px solid #d1d5db;
                  border-radius: 6px;
                  padding: 8px 12px;
                  color: #111827;
                  font-size: 0.9rem;
                  outline: none;
                  width: 100%;
            }

            .input-field:focus,
            .input-select:focus {
                  border-color: #111827;
            }

            .input-select--sm {
                  padding: 6px 10px;
                  font-size: 0.8rem;
            }

            .form-group {
                  margin-bottom: 16px;
            }

            .form-label {
                  display: block;
                  font-size: 0.85rem;
                  font-weight: 500;
                  color: #374151;
                  margin-bottom: 6px;
            }

            /* ===================================== */
            /* MISC LAYOUT HELPERS                   */
            /* ===================================== */
            .dashboard-row {
                  display: grid;
                  grid-template-columns: 2fr 1fr;
                  gap: 20px;
                  margin-bottom: 20px;
            }

            .branch-selector {
                  display: flex;
                  gap: 16px;
                  margin-bottom: 20px;
                  flex-wrap: wrap;
            }

            .selector-group {
                  display: flex;
                  align-items: center;
                  gap: 8px;
            }

            .selector-label {
                  font-size: 0.85rem;
                  font-weight: 500;
                  color: #4b5563;
            }

            .qr-input-group {
                  display: flex;
                  align-items: center;
                  gap: 8px;
                  background: #ffffff;
                  border: 1px solid #d1d5db;
                  border-radius: 6px;
                  padding: 4px 12px;
            }

            .qr-input-group i {
                  color: #6b7280;
                  font-size: 1.2rem;
            }

            .qr-input-group .input-field {
                  border: none;
                  padding: 4px 0;
                  outline: none;
                  background: transparent;
            }

            .user-cell {
                  display: flex;
                  align-items: center;
                  gap: 12px;
            }

            .order-items {
                  list-style: none;
                  padding: 0;
            }

            .order-items li {
                  font-size: 0.85rem;
                  padding: 2px 0;
                  border-bottom: 1px solid #f3f4f6;
            }

            .order-items li:last-child {
                  border-bottom: none;
            }

            .item-thumb {
                  width: 40px;
                  height: 40px;
                  border-radius: 6px;
                  background: #f3f4f6;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  color: #6b7280;
                  border: 1px solid #e5e7eb;
            }

            .sub-section--hidden {
                  display: none;
            }

            /* ===================================== */
            /* CHARTS                                */
            /* ===================================== */
            .bar-chart {
                  display: flex;
                  align-items: flex-end;
                  gap: 12px;
                  height: 200px;
                  padding: 16px 0;
            }

            .bar-chart__item {
                  flex: 1;
                  display: flex;
                  flex-direction: column;
                  align-items: center;
                  gap: 8px;
                  height: 100%;
                  justify-content: flex-end;
            }

            .bar-chart__bar {
                  width: 100%;
                  max-width: 40px;
                  background: #9ca3af;
                  border-radius: 4px 4px 0 0;
                  position: relative;
                  cursor: pointer;
            }

            .bar-chart__bar:hover {
                  background: #6b7280;
            }

            .bar-chart__bar--active {
                  background: #111827;
            }

            .bar-chart__tooltip {
                  display: none;
                  position: absolute;
                  top: -36px;
                  left: 50%;
                  transform: translateX(-50%);
                  background: #111827;
                  color: #ffffff;
                  padding: 4px 8px;
                  border-radius: 4px;
                  font-size: 0.72rem;
                  white-space: nowrap;
            }

            .bar-chart__bar:hover .bar-chart__tooltip {
                  display: block;
            }

            .bar-chart__label {
                  font-size: 0.7rem;
                  color: #6b7280;
            }

            .pie-chart-wrapper {
                  display: flex;
                  align-items: center;
                  gap: 24px;
                  padding: 16px 0;
            }

            .pie-chart {
                  width: 130px;
                  height: 130px;
                  border-radius: 50%;
                  background: conic-gradient(#111827 0% 60%, #6b7280 60% 85%, #d1d5db 85% 100%);
                  position: relative;
            }

            .pie-chart::after {
                  content: '';
                  position: absolute;
                  top: 50%;
                  left: 50%;
                  width: 70px;
                  height: 70px;
                  background: #ffffff;
                  border-radius: 50%;
                  transform: translate(-50%, -50%);
            }

            .pie-chart__legend {
                  display: flex;
                  flex-direction: column;
                  gap: 8px;
            }

            .pie-chart__legend-item {
                  display: flex;
                  align-items: center;
                  gap: 8px;
                  font-size: 0.85rem;
                  color: #4b5563;
            }

            .legend-dot {
                  width: 12px;
                  height: 12px;
                  border-radius: 2px;
            }

            .legend-dot--blue {
                  background: #111827;
            }

            .legend-dot--green {
                  background: #6b7280;
            }

            .legend-dot--amber {
                  background: #d1d5db;
            }

            /* PROGRESS BARS */
            .occupancy-bar,
            .usage-bar {
                  width: 100%;
                  height: 6px;
                  background: #e5e7eb;
                  border-radius: 3px;
                  overflow: hidden;
                  margin-top: 4px;
            }

            .occupancy-bar__fill,
            .usage-bar__fill {
                  height: 100%;
                  background: #111827;
                  transition: width 0.6s ease;
            }

            .usage-bar--full .usage-bar__fill {
                  background: #dc2626;
            }

            /* ===================================== */
            /* REVIEW ITEMS                          */
            /* ===================================== */
            .review-item {
                  padding: 16px 0;
                  border-bottom: 1px solid #e5e7eb;
            }

            .review-item:last-child {
                  border-bottom: none;
            }

            .review-item__header {
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  margin-bottom: 8px;
            }

            .review-item__author {
                  display: flex;
                  align-items: center;
                  gap: 10px;
                  font-weight: 500;
                  color: #111827;
            }

            .review-item__stars {
                  display: flex;
                  gap: 2px;
            }

            .star--filled {
                  color: #f59e0b;
            }

            .star--empty {
                  color: #d1d5db;
            }

            .review-item__content {
                  color: #4b5563;
                  font-size: 0.9rem;
                  line-height: 1.5;
                  margin-bottom: 8px;
            }

            .review-item__meta {
                  display: flex;
                  gap: 12px;
                  align-items: center;
                  margin-bottom: 8px;
                  font-size: 0.8rem;
            }

            .review-item__actions {
                  display: flex;
                  gap: 8px;
            }

            .review-item__reply {
                  background: #f9fafb;
                  border-left: 3px solid #111827;
                  padding: 10px 14px;
                  border-radius: 0 4px 4px 0;
                  font-size: 0.85rem;
                  color: #4b5563;
                  margin-top: 8px;
            }

            /* ===================================== */
            /* VOUCHER CODE                          */
            /* ===================================== */
            .voucher-code {
                  font-family: 'Courier New', monospace;
                  background: #f3f4f6;
                  padding: 4px 8px;
                  border-radius: 4px;
                  color: #111827;
                  border: 1px solid #d1d5db;
            }

            .voucher-code--expired {
                  color: #9ca3af;
                  background: #f9fafb;
                  border-color: #e5e7eb;
            }

            /* ===================================== */
            /* ACTIVITY ICONS                        */
            /* ===================================== */
            .activity-icon {
                  font-size: 1.2rem;
            }

            .activity-icon--green {
                  color: #059669;
            }

            .activity-icon--amber {
                  color: #d97706;
            }

            .activity-icon--blue {
                  color: #2563eb;
            }

            .activity-icon--red {
                  color: #dc2626;
            }

            @media (max-width: 1024px) {
                  .admin-layout {
                        grid-template-columns: 1fr;
                  }

                  .sidebar {
                        display: none;
                  }

                  .dashboard-row {
                        grid-template-columns: 1fr;
                  }

                  .metrics-grid--4 {
                        grid-template-columns: repeat(2, 1fr);
                  }
            }

            @yield('extra-css')
      </style>
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
                        <a class="sidebar__link {{ Request::is('admin/dashboard') ? 'sidebar__link--active' : '' }}"
                              href="{{ url('/admin/dashboard') }}">
                              <span>Tổng Quan</span>
                        </a>
                        <a class="sidebar__link {{ Request::is('admin/booking') ? 'sidebar__link--active' : '' }}"
                              href="{{ url('/admin/booking') }}">
                              <span>Thông Tin Booking</span>
                        </a>
                        <a class="sidebar__link {{ Request::is('admin/facility') ? 'sidebar__link--active' : '' }}"
                              href="{{ url('/admin/facility') }}">
                              <span>Không Gian Cho Thuê</span>
                        </a>
                        <a class="sidebar__link {{ Request::is('admin/marketing') ? 'sidebar__link--active' : '' }}"
                              href="{{ url('/admin/marketing') }}">
                              <span>Vouchers Khuyến Mãi</span>
                        </a>
                        <a class="sidebar__link {{ Request::is('admin/crm') ? 'sidebar__link--active' : '' }}"
                              href="{{ url('/admin/crm') }}">
                              <span>Thông Tin Khách Hàng</span>
                        </a>
                        <a class="sidebar__link {{ Request::is('admin/settings') ? 'sidebar__link--active' : '' }}"
                              href="{{ url('/admin/settings') }}">
                              <span>Nhân Sự & Phân Quyền</span>
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
                  <header class="top-header" id="topHeader">
                        <div class="search-box">
                              <input type="text" placeholder="Tìm kiếm nhanh..." class="search-input" id="globalSearch">
                        </div>
                        <div class="header-actions">
                              <div class="notification-bell" id="notificationBell">
                                    <span style="font-size: 0.9rem; font-weight: 500;">Thông báo</span>
                                    <span class="notification-dot"
                                          style="position: relative; display: inline-block; top: -5px; right: 0;"></span>
                              </div>
                              <button class="btn btn-outline btn-sm" id="btnLogout">
                                    Đăng xuất
                              </button>
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
      <script>
            document.addEventListener('DOMContentLoaded', () => {

                  // =============================================
                  // 1. FILTER TABS (Generic handler for all filter tabs)
                  // =============================================
                  document.querySelectorAll('.filter-tabs').forEach(tabGroup => {
                        const tabs = tabGroup.querySelectorAll('.filter-tab');
                        tabs.forEach(tab => {
                              tab.addEventListener('click', function () {
                                    // Toggle active tab
                                    tabs.forEach(t => t.classList.remove('filter-tab--active'));
                                    this.classList.add('filter-tab--active');

                                    // Handle data-tab switching (sub-sections)
                                    const tabTarget = this.getAttribute('data-tab');
                                    if (tabTarget) {
                                          const parentSection = this.closest('.page-container') || document;
                                          if (parentSection) {
                                                parentSection.querySelectorAll('.sub-section').forEach(sub => {
                                                      sub.classList.add('sub-section--hidden');
                                                });
                                                const targetSub = parentSection.querySelector('#' + tabTarget);
                                                if (targetSub) {
                                                      targetSub.classList.remove('sub-section--hidden');
                                                }
                                          }
                                    }

                                    // Handle data-filter (table row filtering)
                                    const filterValue = this.getAttribute('data-filter');
                                    if (filterValue && !tabTarget) {
                                          const parentCard = this.closest('.page-container') || document;
                                          if (parentCard) {
                                                const rows = parentCard.querySelectorAll('tbody tr[data-status], tbody tr[data-type], tbody tr[data-category]');
                                                rows.forEach(row => {
                                                      if (filterValue === 'all') {
                                                            row.style.display = '';
                                                      } else {
                                                            const rowFilter = row.getAttribute('data-status') ||
                                                                  row.getAttribute('data-type') ||
                                                                  row.getAttribute('data-category');
                                                            row.style.display = (rowFilter === filterValue) ? '' : 'none';
                                                      }
                                                });
                                          }
                                    }
                              });
                        });
                  });

                  // =============================================
                  // 2. GLOBAL SEARCH (filter rows in active page)
                  // =============================================
                  const globalSearch = document.getElementById('globalSearch');
                  if (globalSearch) {
                        globalSearch.addEventListener('input', function () {
                              const query = this.value.toLowerCase().trim();
                              const rows = document.querySelectorAll('.page-container tbody tr');
                              rows.forEach(row => {
                                    const text = row.textContent.toLowerCase();
                                    row.style.display = (!query || text.includes(query)) ? '' : 'none';
                              });
                        });
                  }

            });
      </script>

      @yield('extra-js')
</body>

</html>