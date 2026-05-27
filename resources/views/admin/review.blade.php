@extends('admin.admin_master')

@section('page-title', 'Quản Lý Đánh Giá')

@section('content')
      <div id="section-reviews" class="content-section">
            <div class="section-header">
                  <div>
                        <h1 class="page-title">Quản Lý Đánh Giá</h1>
                        <p class="page-subtitle">Xem, trả lời và điều chỉnh hiển thị đánh giá của khách hàng trên trang chủ</p>
                  </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="filter-tabs" id="reviewFilterTabs">
                  <button class="filter-tab filter-tab--active" data-filter="all">Tất cả</button>
                  <button class="filter-tab" data-filter="approved">Đang hiển thị</button>
                  <button class="filter-tab" data-filter="hidden">Đang ẩn</button>
            </div>

            {{-- Reviews Table --}}
            <div class="card card--table">
                  <table class="data-table" id="reviewsTable">
                        <thead>
                              <tr>
                                    <th>Khách hàng</th>
                                    <th>Không gian</th>
                                    <th>Đánh giá</th>
                                    <th style="width: 30%;">Nội dung</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                              </tr>
                        </thead>
                        <tbody>
                              @forelse($reviews ?? [] as $review)
                                    @php
                                        $rowFilter = $review->is_approved ? 'approved' : 'hidden';
                                        $statusText = $review->is_approved ? 'Đang hiển thị' : 'Đang ẩn';
                                        $statusClass = $review->is_approved ? 'badge--green' : 'badge--red';
                                        
                                        // Lấy phản hồi mới nhất
                                        $reply = $review->adminReplies->first();
                                        $replyText = $reply ? $reply->reply_text : '';
                                    @endphp
                                    <tr data-status="{{ $rowFilter }}" data-id="{{ $review->id }}" data-reply="{{ $replyText }}">
                                          <td>
                                                <div style="font-weight: 500;">{{ $review->author_name ?? ($review->user ? $review->user->name : 'Ẩn danh') }}</div>
                                                <div class="text-sm text-muted">{{ $review->user ? $review->user->email : '--' }}</div>
                                          </td>
                                          <td>
                                                <div>{{ $review->workspace ? $review->workspace->name : 'Không gian đã xóa' }}</div>
                                          </td>
                                          <td>
                                                <div style="color: #f59e0b; display: inline-flex; align-items: center; gap: 2px;">
                                                      @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                  <i class="ph-fill ph-star"></i>
                                                            @else
                                                                  <i class="ph-bold ph-star" style="color: #d1d5db;"></i>
                                                            @endif
                                                      @endfor
                                                      <span class="text-muted text-sm ml-1">({{ $review->rating }}/5)</span>
                                                </div>
                                          </td>
                                          <td>
                                                <div style="max-height: 80px; overflow-y: auto; line-height: 1.4;">
                                                      {{ $review->content }}
                                                </div>
                                                @if($replyText)
                                                      <div style="margin-top: 8px; padding-left: 12px; border-left: 2px solid #3b82f6; font-size: 13px;">
                                                            <strong class="text-muted">Đã trả lời:</strong>
                                                            <div style="font-style: italic; color: #4b5563;">{{ $replyText }}</div>
                                                      </div>
                                                @endif
                                          </td>
                                          <td>
                                                <div>{{ $review->created_at ? $review->created_at->format('d/m/Y') : '--' }}</div>
                                                <div class="text-sm text-muted">{{ $review->created_at ? $review->created_at->format('H:i') : '' }}</div>
                                          </td>
                                          <td>
                                                <span class="badge {{ $statusClass }} status-badge">{{ $statusText }}</span>
                                          </td>
                                          <td class="text-center">
                                                <div class="action-group" style="justify-content: center;">
                                                      <button class="btn btn-outline btn-sm btn-icon btn-toggle-visibility" 
                                                              title="{{ $review->is_approved ? 'Ẩn đánh giá' : 'Hiển thị đánh giá' }}" 
                                                              data-id="{{ $review->id }}">
                                                            @if($review->is_approved)
                                                                  <i class="ph-bold ph-eye-slash"></i>
                                                            @else
                                                                  <i class="ph-bold ph-eye"></i>
                                                            @endif
                                                      </button>
                                                      <button class="btn btn-outline btn-sm btn-icon btn-reply" 
                                                              title="Phản hồi" 
                                                              data-id="{{ $review->id }}"
                                                              data-author="{{ $review->author_name ?? ($review->user ? $review->user->name : 'Ẩn danh') }}"
                                                              data-content="{{ $review->content }}">
                                                            <i class="ph-bold ph-chats"></i>
                                                      </button>
                                                </div>
                                          </td>
                                    </tr>
                              @empty
                                    <tr>
                                          <td colspan="7" class="text-center py-4 text-muted">Chưa có đánh giá nào.</td>
                                    </tr>
                              @endforelse
                        </tbody>
                  </table>
            </div>
      </div>

      {{-- Modal Trả Lời Đánh Giá --}}
      <div id="replyReviewModal" class="schedule-modal"
            style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
            <div class="modal-content"
                  style="background: #ffffff; color: #111827; width: 500px; max-width: 95%; border-radius: 16px; padding: 30px; position: relative;">
                  <i class="ph-bold ph-x close-modal" data-action="close-reply-modal"
                        style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>

                  <h2 style="font-size: 20px; font-weight: bold; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;">
                        Phản Hồi Đánh Giá
                  </h2>

                  <div style="background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 14px; margin-bottom: 20px;">
                        <p style="margin-bottom: 8px;"><strong>Khách hàng:</strong> <span id="replyModalAuthor">--</span></p>
                        <p style="margin-bottom: 0;"><strong>Nội dung đánh giá:</strong> <span id="replyModalContent" style="font-style: italic; color: #4b5563;">--</span></p>
                  </div>

                  <form id="replyReviewForm">
                        @csrf
                        <input type="hidden" id="replyReviewId">
                        <div class="form-group" style="margin-bottom: 20px;">
                              <label for="replyText" class="form-label" style="font-weight: 600; margin-bottom: 8px;">Nội dung phản hồi của Admin</label>
                              <textarea id="replyText" class="input-field" rows="4" placeholder="Nhập câu trả lời của bạn ở đây..." required style="width: 100%; font-family: inherit; resize: vertical; padding: 10px;"></textarea>
                        </div>

                        <div style="text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                              <button class="btn btn-outline" type="button" data-action="close-reply-modal">Hủy</button>
                              <button class="btn btn-primary" type="submit">Gửi phản hồi</button>
                        </div>
                  </form>
            </div>
      </div>
@endsection
