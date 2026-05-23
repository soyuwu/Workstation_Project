@extends('admin.admin_master')

@section('page-title', 'Quản Lý Không Gian')

@section('content')
      <div id="section-workspace">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Quản Lý Không Gian</h1>
                  </div>
                  <div class="section-actions">
                        <button class="btn btn-primary" id="btnOpenWorkspaceModal">
                              <i class="ph-bold ph-plus"></i> Thêm Không Gian Mới
                        </button>
                  </div>
            </div>

            <div class="card card--table">
                  <table class="data-table" id="workspacesTable">
                        <thead>
                              <tr>
                                    <th>Mã</th>
                                    <th>Tên Không Gian</th>
                                    <th>Khu Vực</th>
                                    <th>Loại</th>
                                    <th>Sức Chứa</th>
                                    <th>Giá</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-center">Thao tác</th>
                              </tr>
                        </thead>
                        <tbody>
                              @forelse($workspaces ?? [] as $ws)
                                    <tr>
                                          <td><b>{{ $ws->code }}</b></td>
                                          <td>
                                                <div style="font-weight: 500;">{{ $ws->name }}</div>
                                                <div class="text-sm text-muted" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ws->description ?? 'Không có mô tả' }}</div>
                                          </td>
                                          <td>{{ $ws->area ? $ws->area->name : '--' }}</td>
                                          <td>{{ $ws->roomType ? $ws->roomType->name : '--' }}</td>
                                          <td>{{ $ws->capacity }} người</td>
                                          <td>
                                                <b>{{ number_format($ws->price_per_hour, 0, ',', '.') }} ₫</b>
                                                @if(!is_null($ws->price_per_month))
                                                      <div class="text-sm text-muted">/ tháng: <b>{{ number_format($ws->price_per_month, 0, ',', '.') }} ₫</b></div>
                                                @endif
                                          </td>
                                          <td>
                                                @if($ws->status == 'active')
                                                      <span class="badge badge--green">Hoạt động</span>
                                                @elseif($ws->status == 'maintenance')
                                                      <span class="badge badge--yellow">Bảo trì</span>
                                                @else
                                                      <span class="badge badge--gray">Ngừng cho thuê</span>
                                                @endif
                                          </td>
                                          <td class="text-center">
                                                <div class="action-group" style="justify-content: center;">
                                                      <button class="btn btn-outline btn-sm btn-icon" title="Sửa"
                                                            data-action="edit-workspace" data-workspace='@json($ws)'>
                                                            <i class="ph-bold ph-pencil-simple"></i>
                                                      </button>
                                                      <button class="btn btn-outline btn-sm btn-icon btn-icon--danger" title="Xóa"
                                                            data-action="delete-workspace" data-workspace-id="{{ $ws->id }}">
                                                            <i class="ph-bold ph-trash"></i>
                                                      </button>
                                                </div>
                                          </td>
                                    </tr>
                              @empty
                                    <tr>
                                          <td colspan="8" class="text-center py-4 text-muted">Chưa có không gian nào.</td>
                                    </tr>
                              @endforelse
                        </tbody>
                  </table>
            </div>
      </div>

      {{-- Modal Form --}}
      <div id="workspaceModal" class="schedule-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
            <div class="modal-content" style="background: #ffffff; color: #111827; width: 600px; max-width: 95%; border-radius: 16px; padding: 30px; position: relative; max-height: 90vh; overflow-y: auto;">
                  <i class="ph-bold ph-x close-modal" data-action="close-workspace-modal" style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>
                  
                  <h2 id="modalTitle" style="font-size: 22px; font-weight: bold; margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">Thêm Không Gian Mới</h2>
                  
                  <form id="workspaceForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="workspace_id" name="id">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Mã Không Gian (*)</label>
                                    <input type="text" id="ws_code" name="code" class="input-field" required placeholder="VD: R101">
                              </div>
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Tên Không Gian (*)</label>
                                    <input type="text" id="ws_name" name="name" class="input-field" required placeholder="Tên phòng/bàn">
                              </div>
                              
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Khu Vực (*)</label>
                                    <select id="ws_area" name="area_id" class="input-field" required>
                                          @foreach($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                          @endforeach
                                    </select>
                              </div>
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Loại Không Gian (*)</label>
                                    <select id="ws_room_type" name="room_type_id" class="input-field" required>
                                          @foreach($roomTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                          @endforeach
                                    </select>
                              </div>
                              
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Sức Chứa (người)</label>
                                    <input type="number" id="ws_capacity" name="capacity" class="input-field" required min="1" value="1">
                              </div>
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Giá / Giờ (VNĐ)</label>
                                    <input type="number" id="ws_price" name="price_per_hour" class="input-field" required min="0" value="0">
                              </div>
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Giá / Tháng (VNĐ) (tùy chọn)</label>
                                    <input type="number" id="ws_price_month" name="price_per_month" class="input-field" min="0" placeholder="Chỉ dùng cho gói thuê theo tháng">
                              </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                              <label style="display: block; font-weight: 500; margin-bottom: 5px;">Mô tả chi tiết</label>
                              <textarea id="ws_desc" name="description" class="input-field" style="min-height: 80px;" placeholder="Mô tả các tiện ích, đặc điểm..."></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                              <label style="display: block; font-weight: 500; margin-bottom: 5px;">Hình ảnh Không gian (Có thể chọn nhiều ảnh)</label>
                              <input type="file" id="ws_images" name="images[]" class="input-field" multiple accept="image/*" style="padding: 10px; background: #f9fafb;">
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Giờ thuê tối thiểu</label>
                                    <input type="number" id="ws_min_hours" name="min_booking_hours" class="input-field" required min="1" value="1">
                              </div>
                              <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: 500; margin-bottom: 5px;">Trạng Thái</label>
                                    <select id="ws_status" name="status" class="input-field" required>
                                          <option value="active">Hoạt động (Sẵn sàng)</option>
                                          <option value="maintenance">Đang bảo trì</option>
                                          <option value="inactive">Ngừng cho thuê</option>
                                    </select>
                              </div>
                        </div>

                        <div style="margin-top: 25px; text-align: right;">
                              <button type="button" class="btn btn-outline" data-action="close-workspace-modal" style="margin-right: 10px;">Hủy</button>
                              <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                        </div>
                  </form>
            </div>
      </div>
@endsection
