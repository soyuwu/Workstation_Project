@extends('layouts.admin.admin_master')
@section('page-title', 'Khách hàng & CRM')
@section('content')
      {{-- ============================================== --}}
      {{-- 6. QUẢN LÝ KHÁCH HÀNG & ĐÁNH GIÁ (CRM) --}}
      {{-- ============================================== --}}
      <div id="section-crm" class="content-section">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Thông Tin Khách Hàng</h1>
                        <p class="page-subtitle">Theo dõi hồ sơ người dùng, lịch sử đặt chỗ và phản hồi từ khách.</p>
                  </div>
                  <div class="section-actions">
                        <button class="btn btn-outline" id="btnExportCustomers">
                              <i class="ph-bold ph-export"></i> Xuất file Excel
                        </button>
                  </div>
            </div>

            {{-- CRM Sub-tab Navigation --}}
            <div class="filter-tabs" id="crmSubTabs">
                  <button class="filter-tab filter-tab--active" data-tab="crm-users">Danh sách Người dùng</button>
                  <button class="filter-tab" data-tab="crm-reviews">Đánh giá & Phản hồi</button>
            </div>

            {{-- Tab 1: Users List --}}
            <div class="sub-section" id="crm-users">
                  <div class="card card--table">
                        <table class="data-table" id="customersTable">
                              <thead>
                                    <tr>
                                          <th>Thông tin Khách hàng</th>
                                          <th>Liên hệ</th>
                                          <th>Tổng chi tiêu</th>
                                          <th>Số lần đặt chỗ</th>
                                          <th>Trạng thái tài khoản</th>
                                          <th class="text-center">Thao tác</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    <tr>
                                          <td>
                                                <div class="user-cell">
                                                      <img src="https://ui-avatars.com/api/?name=Nguyễn+Văn+A&background=random&color=fff"
                                                            class="avatar avatar--sm" alt="Avatar">
                                                      <div>
                                                            <b>Nguyễn Văn A</b><br>
                                                            <span class="text-muted text-sm">ID: KH-00124</span>
                                                      </div>
                                                </div>
                                          </td>
                                          <td>
                                                <div class="text-sm"><i class="ph-fill ph-phone text-muted"></i> 0901 234 567
                                                </div>
                                                <div class="text-sm"><i class="ph-fill ph-envelope-simple text-muted"></i>
                                                      nva.work@email.com</div>
                                          </td>
                                          <td>
                                                <b>14.500.000 ₫</b>
                                          </td>
                                          <td>24 lần booking</td>
                                          <td><span class="badge badge--green">Hoạt động</span></td>
                                          <td class="text-center">
                                                <div class="action-group">
                                                      <button class="btn btn-outline btn-sm btn-icon"
                                                            title="Cập nhật thông tin">
                                                            <i class="ph-bold ph-pencil-simple"></i>
                                                      </button>
                                                      <button class="btn btn-outline btn-sm btn-icon btn-icon--danger"
                                                            title="Xóa khách hàng">
                                                            <i class="ph-bold ph-trash"></i>
                                                      </button>
                                                </div>
                                          </td>
                                    </tr>
                                    <tr>
                                          <td>
                                                <div class="user-cell">
                                                      <img src="https://ui-avatars.com/api/?name=Trần+Thị+B&background=random&color=fff"
                                                            class="avatar avatar--sm" alt="Avatar">
                                                      <div>
                                                            <b>Trần Thị B</b><br>
                                                            <span class="text-muted text-sm">ID: KH-00892</span>
                                                      </div>
                                                </div>
                                          </td>
                                          <td>
                                                <div class="text-sm"><i class="ph-fill ph-phone text-muted"></i> 0912 345 678
                                                </div>
                                                <div class="text-sm"><i class="ph-fill ph-envelope-simple text-muted"></i>
                                                      ttb@company.vn
                                                </div>
                                          </td>
                                          <td>
                                                <b>2.150.000 ₫</b>
                                          </td>
                                          <td>5 lần booking</td>
                                          <td><span class="badge badge--green">Hoạt động</span></td>
                                          <td class="text-center">
                                                <div class="action-group">
                                                      <button class="btn btn-outline btn-sm btn-icon"
                                                            title="Cập nhật thông tin">
                                                            <i class="ph-bold ph-pencil-simple"></i>
                                                      </button>
                                                      <button class="btn btn-outline btn-sm btn-icon btn-icon--danger"
                                                            title="Xóa khách hàng">
                                                            <i class="ph-bold ph-trash"></i>
                                                      </button>
                                                </div>
                                          </td>
                                    </tr>
                                    <tr>
                                          <td>
                                                <div class="user-cell">
                                                      <img src="https://ui-avatars.com/api/?name=Lê+Hữu+C&background=random&color=fff"
                                                            class="avatar avatar--sm" alt="Avatar">
                                                      <div>
                                                            <b>Lê Hữu C (Spammer)</b><br>
                                                            <span class="text-muted text-sm">ID: KH-01255</span>
                                                      </div>
                                                </div>
                                          </td>
                                          <td>
                                                <div class="text-sm"><i class="ph-fill ph-phone text-muted"></i> 0988 999 000
                                                </div>
                                          </td>
                                          <td>
                                                <b>0 ₫</b><br>
                                                <span class="text-danger text-sm">12 lần book & hủy (No-show)</span>
                                          </td>
                                          <td>0 lần hoàn thành</td>
                                          <td><span class="badge badge--red">Bị chặn (Blacklist)</span></td>
                                          <td class="text-center">
                                                <div class="action-group">
                                                      <button class="btn btn-outline btn-sm btn-icon"
                                                            title="Cập nhật thông tin">
                                                            <i class="ph-bold ph-pencil-simple"></i>
                                                      </button>
                                                      <button class="btn btn-outline btn-sm btn-icon btn-icon--danger"
                                                            title="Xóa khách hàng">
                                                            <i class="ph-bold ph-trash"></i>
                                                      </button>
                                                </div>
                                          </td>
                                    </tr>
                              </tbody>
                        </table>
                  </div>
            </div>

            {{-- Tab 2: Reviews --}}
            <div class="sub-section sub-section--hidden" id="crm-reviews">
                  <div class="card">
                        <div class="card__header">
                              <h2 class="card__title"><i class="ph-fill ph-star"></i> Đánh giá từ Khách hàng</h2>
                        </div>

                        {{-- Review Item 1 --}}
                        <div class="review-item">
                              <div class="review-item__header">
                                    <div class="review-item__author">
                                          <img src="https://ui-avatars.com/api/?name=Lê+Hùng+Phát&background=random&color=fff"
                                                class="avatar avatar--xs" alt="Avatar">
                                          <div>
                                                <b>Lê Hùng Phát</b> đánh giá <b>Phòng M1</b>
                                          </div>
                                    </div>
                                    <div class="review-item__stars">
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                    </div>
                              </div>
                              <p class="review-item__content">Không gian rất yên tĩnh, mạng mạnh, phù hợp họp nhóm! Quán phục
                                    vụ tận
                                    tình, chắc chắn sẽ quay lại.</p>
                              <div class="review-item__meta">
                                    <span class="text-muted text-sm">2 ngày trước</span>
                              </div>
                              <div class="review-item__actions">
                                    <button class="btn btn-outline btn-sm">
                                          <i class="ph-bold ph-chat-text"></i> Trả lời
                                    </button>
                              </div>
                        </div>

                        {{-- Review Item 2 --}}
                        <div class="review-item">
                              <div class="review-item__header">
                                    <div class="review-item__author">
                                          <img src="https://ui-avatars.com/api/?name=Ẩn+Danh&background=cccccc&color=fff"
                                                class="avatar avatar--xs" alt="Avatar">
                                          <div>
                                                <b>Ẩn danh</b> đánh giá <b>Bàn C-10</b>
                                          </div>
                                    </div>
                                    <div class="review-item__stars">
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph ph-star star--empty"></i>
                                          <i class="ph ph-star star--empty"></i>
                                          <i class="ph ph-star star--empty"></i>
                                          <i class="ph ph-star star--empty"></i>
                                    </div>
                              </div>
                              <p class="review-item__content">Có ai đó để quên đồ dơ trên bàn chưa dọn. Dịch vụ vệ sinh cần
                                    cải thiện.
                              </p>
                              <div class="review-item__meta">
                                    <span class="text-muted text-sm">5 ngày trước</span>
                              </div>
                              <div class="review-item__actions">
                                    <button class="btn btn-outline btn-sm btn-sm--danger" id="btnHideReview">
                                          <i class="ph-bold ph-eye-slash"></i> Ẩn Spam/Cấm
                                    </button>
                                    <button class="btn btn-outline btn-sm">
                                          <i class="ph-bold ph-chat-text"></i> Trả lời (Xin lỗi)
                                    </button>
                              </div>
                        </div>

                        {{-- Review Item 3 --}}
                        <div class="review-item">
                              <div class="review-item__header">
                                    <div class="review-item__author">
                                          <img src="https://ui-avatars.com/api/?name=Trần+Minh&background=random&color=fff"
                                                class="avatar avatar--xs" alt="Avatar">
                                          <div>
                                                <b>Trần Minh</b> đánh giá <b>Pod P-01</b>
                                          </div>
                                    </div>
                                    <div class="review-item__stars">
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph-fill ph-star star--filled"></i>
                                          <i class="ph ph-star star--empty"></i>
                                    </div>
                              </div>
                              <p class="review-item__content">Pod cách âm tốt, phù hợp làm việc tập trung. Chỉ tiếc màn hình
                                    hơi nhỏ.
                              </p>
                              <div class="review-item__meta">
                                    <span class="text-muted text-sm">1 tuần trước</span>
                                    <span class="badge badge--gray text-sm">Admin đã phản hồi</span>
                              </div>
                              <div class="review-item__reply">
                                    <b>Admin:</b> Cảm ơn bạn Trần Minh! Chúng tôi đang nâng cấp màn hình cho Pod trong tuần
                                    tới ạ.
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <!-- Modal Thêm/Sửa Khách hàng -->
      <div id="customerModal" class="schedule-modal"
            style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
            <div class="modal-content"
                  style="background: #ffffff; color: #111827; width: 500px; max-width: 90%; border-radius: 16px; padding: 30px; position: relative;">
                  <i class="ph-bold ph-x close-modal" onclick="closeCustomerModal()"
                        style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>
                  <h2 id="customerModalTitle"
                        style="font-size: 22px; font-weight: bold; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        Thêm
                        Khách hàng</h2>

                  <form id="customerForm" style="margin-top: 20px;">
                        <div style="margin-bottom: 15px;">
                              <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tên khách hàng</label>
                              <input type="text" id="customerName" required
                                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                        </div>
                        <div style="margin-bottom: 15px;">
                              <label style="display: block; margin-bottom: 5px; font-weight: bold;">Số điện thoại</label>
                              <input type="text" id="customerPhone" required
                                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                        </div>
                        <div style="margin-bottom: 15px;">
                              <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                              <input type="email" id="customerEmail"
                                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;">
                        </div>
                        <button type="button" onclick="saveCustomer()" class="btn btn-primary"
                              style="width: 100%; padding: 12px; border: none; border-radius: 8px; background: #3b82f6; color: white; font-weight: bold; cursor: pointer;">Lưu
                              Thông tin</button>
                  </form>
            </div>
      </div>

      <script>
            let currentRowToEdit = null;

            function openCustomerModal(mode, btn = null) {
                  document.getElementById('customerModal').style.display = 'flex';
                  const form = document.getElementById('customerForm');
                  form.reset();

                  if (mode === 'add') {
                        document.getElementById('customerModalTitle').innerText = 'Thêm Khách hàng mới';
                        currentRowToEdit = null;
                  } else if (mode === 'edit' && btn) {
                        document.getElementById('customerModalTitle').innerText = 'Cập nhật Khách hàng';
                        currentRowToEdit = btn.closest('tr');

                        // Lấy dữ liệu từ row hiện tại để đổ vào form
                        const nameEl = currentRowToEdit.querySelector('.user-cell b');
                        const phoneEl = currentRowToEdit.querySelectorAll('td')[1].querySelectorAll('div.text-sm')[0];
                        const emailEl = currentRowToEdit.querySelectorAll('td')[1].querySelectorAll('div.text-sm')[1];

                        document.getElementById('customerName').value = nameEl ? nameEl.innerText : '';
                        // remove non-numeric chars for phone except spaces/plus
                        document.getElementById('customerPhone').value = phoneEl ? phoneEl.innerText.trim() : '';
                        document.getElementById('customerEmail').value = emailEl ? emailEl.innerText.trim() : '';
                  }
            }

            function closeCustomerModal() {
                  document.getElementById('customerModal').style.display = 'none';
                  currentRowToEdit = null;
            }

            function saveCustomer() {
                  const name = document.getElementById('customerName').value;
                  const phone = document.getElementById('customerPhone').value;
                  const email = document.getElementById('customerEmail').value || 'chưa cập nhật';

                  if (!name || !phone) {
                        alert('Vui lòng nhập tên và số điện thoại!');
                        return;
                  }

                  if (currentRowToEdit) {
                        // Edit existing
                        currentRowToEdit.querySelector('.user-cell b').innerText = name;
                        const tdContact = currentRowToEdit.querySelectorAll('td')[1];
                        tdContact.innerHTML = `<div class="text-sm"><i class="ph-fill ph-phone text-muted"></i> ${phone}</div>
                                                     <div class="text-sm"><i class="ph-fill ph-envelope-simple text-muted"></i> ${email}</div>`;
                  } else {
                        // Add new
                        const tbody = document.querySelector('#customersTable tbody');
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                                  <td>
                                      <div class="user-cell">
                                          <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random&color=fff" class="avatar avatar--sm" alt="Avatar">
                                          <div>
                                              <b>${name}</b><br>
                                              <span class="text-muted text-sm">ID: KH-NEW</span>
                                          </div>
                                      </div>
                                  </td>
                                  <td>
                                      <div class="text-sm"><i class="ph-fill ph-phone text-muted"></i> ${phone}</div>
                                      <div class="text-sm"><i class="ph-fill ph-envelope-simple text-muted"></i> ${email}</div>
                                  </td>
                                  <td><b>0 ₫</b></td>
                                  <td>0 lần booking</td>
                                  <td><span class="badge badge--green">Hoạt động</span></td>
                                  <td class="text-center">
                                      <div class="action-group">
                                          <button class="btn btn-outline btn-sm btn-icon" title="Xem lịch sử">
                                              <i class="ph-bold ph-eye"></i>
                                          </button>
                                          <button class="btn btn-outline btn-sm btn-icon" title="Cập nhật thông tin">
                                              <i class="ph-bold ph-pencil-simple"></i>
                                          </button>
                                          <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa khách hàng">
                                              <i class="ph-bold ph-trash"></i>
                                          </button>
                                      </div>
                                  </td>
                              `;
                        tbody.prepend(tr);
                  }

                  closeCustomerModal();
            }

            // Event Delegation cho bảng khách hàng
            document.addEventListener('DOMContentLoaded', function () {
                  const table = document.getElementById('customersTable');
                  if (table) {
                        table.addEventListener('click', function (e) {
                              const btnEdit = e.target.closest('button[title="Cập nhật thông tin"]');
                              const btnDelete = e.target.closest('button[title="Xóa khách hàng"]');

                              if (btnEdit) {
                                    openCustomerModal('edit', btnEdit);
                              } else if (btnDelete) {
                                    if (confirm('Bạn có chắc chắn muốn xóa khách hàng này? Tất cả dữ liệu liên quan sẽ bị ẩn.')) {
                                          btnDelete.closest('tr').remove();
                                    }
                              }
                        });
                  }
            });
      </script>

@endsection
